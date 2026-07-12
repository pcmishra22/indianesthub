<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

/**
 * CityDataDiscoveryService
 *
 * Finds candidate real-world businesses for a given city so an admin can
 * review and confirm them before they're written into the database.
 *
 * Uses Mappls (MapmyIndia) as the primary source when credentials are
 * configured — free, no credit card required, India-focused business data.
 * Falls back automatically to OpenStreetMap's Overpass API (free, no signup)
 * when Mappls isn't configured or finds nothing.
 */
class CityDataDiscoveryService
{
    protected ?string $overpassUrl;
    protected ?string $nominatimUrl;
    protected ?string $mapplsClientId;
    protected ?string $mapplsClientSecret;

    /**
     * Diagnostic info from the most recent request, so the UI can tell the
     * difference between "the request itself failed" (network/firewall/DNS/
     * rate-limit) and "the request succeeded but found zero matches" (a
     * genuine OSM data-coverage gap). Both look like "no candidates" to the
     * end user otherwise, but they need very different fixes.
     */
    protected ?string $lastGeocodeError = null;
    protected ?string $lastOverpassError = null;
    protected ?string $lastMapplsTokenError = null;

    public function __construct()
    {
        $this->overpassUrl = config('openstreetmap.overpass_url', 'https://overpass-api.de/api/interpreter');
        $this->nominatimUrl = config('openstreetmap.nominatim_url', 'https://nominatim.openstreetmap.org');
        $this->mapplsClientId = config('mappls.client_id');
        $this->mapplsClientSecret = config('mappls.client_secret');
    }

    /**
     * @return array{candidates: array, notice: ?string}
     */
    public function discover(string $type, string $city, $csvFile = null): array
    {
        $normalizedCity = $this->normalizeCity($city);

        return match ($type) {
            'builder' => $this->discoverAndDiagnose($normalizedCity, 'builder'),
            'agent'   => $this->discoverAndDiagnose($normalizedCity, 'agent'),
            'property' => $this->discoverProperties($normalizedCity, $csvFile),
            default => ['candidates' => [], 'notice' => 'Unknown type.'],
        };
    }

    protected function normalizeCity(string $city): string
    {
        $city = trim($city);
        $city = str_replace(['-', '_'], ' ', $city);
        $city = preg_replace('/\s+/', ' ', $city);

        // If admin passes a hyphenated slug (e.g. "zirakpur-city"), prefer the first token.
        // This makes queries like "real estate builders ... in zirakpur" much more reliable.
        $parts = explode(' ', $city);
        $first = $parts[0] ?? $city;

        return strtolower($first);
    }

    /**
     * Mappls (MapmyIndia) Text Search — the primary data source when
     * credentials are configured. An India-focused mapping company with
     * "no credit card required" free signup and far deeper coverage of
     * Indian cities/towns than global providers like OSM. Uses OAuth2
     * client-credentials: exchanges MAPPLS_CLIENT_ID/MAPPLS_CLIENT_SECRET
     * for a bearer token (cached ~24h), then calls the Text Search API.
     * Set both env vars to enable; without them, this is skipped entirely
     * and the service falls back to the free, zero-config OpenStreetMap
     * path below.
     *
     * @return array{results: array, error: ?string}
     */
    protected function queryMappls(string $city, string $queryPrefix): array
    {
        if (!$this->mapplsClientId || !$this->mapplsClientSecret) {
            return ['results' => [], 'error' => null]; // Not configured — silently skip, not an error.
        }

        $token = $this->getMapplsAccessToken();
        if (!$token) {
            return ['results' => [], 'error' => $this->lastMapplsTokenError ?? 'Could not obtain a Mappls access token.'];
        }

        // A location bias greatly improves result relevance/coverage per Mappls'
        // own docs ("STRONGLY RECOMMENDED"). Reuse the same geocoding used for
        // the OSM fallback so we don't duplicate that logic.
        $coordinates = $this->getCityCoordinates($city) ?? $this->getFallbackCoordinatesForCity($city);

        try {
            $params = [
                'query' => $queryPrefix,
                'region' => 'ind',
            ];
            if ($coordinates) {
                $params['location'] = $coordinates[0] . ',' . $coordinates[1];
            }

            $response = Http::timeout(15)
                ->withHeaders(['Authorization' => 'bearer ' . $token])
                ->get('https://atlas.mappls.com/api/places/textsearch/json', $params);

            if ($response->status() === 401) {
                // Token might have just expired server-side; don't cache a bad one.
                \Cache::forget('mappls_access_token');
                return ['results' => [], 'error' => 'Mappls rejected the access token (HTTP 401). It may have '
                    . 'expired or your credentials may be invalid — this will self-correct on the next search.'];
            }
            if ($response->status() === 403) {
                return ['results' => [], 'error' => 'Mappls key has hit its daily/hourly request limit (HTTP 403).'];
            }
            if ($response->status() === 204) {
                return ['results' => [], 'error' => null]; // Valid request, genuinely zero matches.
            }
            if ($response->failed()) {
                return ['results' => [], 'error' => 'Mappls Text Search request failed: HTTP ' . $response->status()];
            }

            $locations = $response->json('suggestedLocations', []);
            $results = [];
            foreach ($locations as $place) {
                $candidate = self::mapMapplsPlaceToCandidate($place, $city);
                if ($candidate) {
                    $results[] = $candidate;
                }
            }

            return ['results' => $results, 'error' => null];
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return ['results' => [], 'error' => 'Could not connect to Mappls Text Search (' . $e->getMessage() . '). '
                . 'Check your server can reach atlas.mappls.com.'];
        } catch (\Exception $e) {
            \Log::warning('Mappls Text Search error: ' . $e->getMessage());
            return ['results' => [], 'error' => $e->getMessage()];
        }
    }

    /**
     * Fetches (and caches for its stated lifetime) an OAuth2 bearer token via
     * the client-credentials grant, per Mappls' Token Generation API.
     */
    protected function getMapplsAccessToken(): ?string
    {
        $this->lastMapplsTokenError = null;

        return \Cache::remember('mappls_access_token', 3600 * 12, function () {
            try {
                $response = Http::asForm()
                    ->timeout(10)
                    ->post('https://outpost.mappls.com/api/security/oauth/token', [
                        'grant_type' => 'client_credentials',
                        'client_id' => $this->mapplsClientId,
                        'client_secret' => $this->mapplsClientSecret,
                    ]);

                if ($response->failed()) {
                    $this->lastMapplsTokenError = 'Mappls token request failed: HTTP ' . $response->status()
                        . '. Check MAPPLS_CLIENT_ID / MAPPLS_CLIENT_SECRET are correct.';
                    return null;
                }

                $token = $response->json('access_token');
                if (!$token) {
                    $this->lastMapplsTokenError = 'Mappls token response did not include an access_token.';
                    return null;
                }

                return $token;
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                $this->lastMapplsTokenError = 'Could not connect to Mappls token endpoint (' . $e->getMessage() . '). '
                    . 'Check your server can reach outpost.mappls.com.';
                return null;
            } catch (\Exception $e) {
                $this->lastMapplsTokenError = $e->getMessage();
                return null;
            }
        });
    }

    /**
     * Maps one raw suggestedLocations entry from Mappls' Text Search response
     * into our candidate shape. Pure/static so it's directly unit-testable
     * against Mappls' documented JSON schema without a live API call.
     */
    public static function mapMapplsPlaceToCandidate(array $place, string $city): ?array
    {
        $name = $place['placeName'] ?? null;
        if (!$name) {
            return null;
        }

        return [
            'source'          => 'mappls',
            'source_place_id' => $place['eLoc'] ?? null,
            'name'            => $name,
            'company_name'    => $name,
            'phone'           => null,
            'website'         => null,
            'address'         => $place['placeAddress'] ?? null,
            'city'            => $city,
            'rating'          => null,
            'latitude'        => null,
            'longitude'       => null,
        ];
    }

    protected function queryOverpass(string $query): array
    {
        $this->lastOverpassError = null;

        try {
            // The query strings are built with literal "\n" inside single-quoted
            // PHP strings, which PHP does NOT convert to real newlines. Left as-is,
            // Overpass receives a syntactically invalid query (stray backslashes)
            // and rejects it. Normalize to real whitespace before sending.
            $query = str_replace('\\n', "\n", $query);

            // Overpass expects the query as a form field named "data" (like
            // `curl -d "data=..."`), not as a raw string body or JSON payload.
            // Http::post($url, $query) with a string was being JSON-encoded and
            // sent with the wrong Content-Type, which Overpass can't parse.
            $response = Http::asForm()
                ->timeout(30)
                ->withHeaders(['User-Agent' => 'IndianEstHub-CityImport/1.0 (contact: admin@indianesthub.com)'])
                ->post($this->overpassUrl, ['data' => $query]);

            if ($response->failed()) {
                throw new \RuntimeException('Overpass API request failed: HTTP ' . $response->status());
            }

            $data = $response->json();

            if (isset($data['error'])) {
                throw new \RuntimeException('Overpass API error: ' . $data['error']);
            }

            $elements = $data['elements'] ?? [];
            error_log("Overpass returned " . count($elements) . " elements");
            return $elements;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // This is what a firewall/network block, DNS failure, or timeout looks
            // like — the request never got a response at all.
            $this->lastOverpassError = 'Could not connect to Overpass API (' . $e->getMessage() . '). '
                . 'This usually means your server cannot reach overpass-api.de — check your hosting '
                . 'firewall / outbound network rules.';
            \Log::warning('Overpass connection error: ' . $e->getMessage());
            return [];
        } catch (\Exception $e) {
            $this->lastOverpassError = $e->getMessage();
            \Log::warning('Overpass API error: ' . $e->getMessage());
            return [];
        }
    }

    protected function getCityCoordinates(string $city): ?array
    {
        $this->lastGeocodeError = null;

        try {
            // Use Nominatim to get coordinates for the city
            $response = Http::timeout(10)
                ->withHeaders(['User-Agent' => 'IndianEstHub-CityImport/1.0 (contact: admin@indianesthub.com)'])
                ->get($this->nominatimUrl . '/search', [
                    'q' => $city . ', India',
                    'format' => 'json',
                    'limit' => 1,
                    'addressdetails' => 1,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data)) {
                    $lat = $data[0]['lat'] ?? null;
                    $lon = $data[0]['lon'] ?? null;
                    if ($lat !== null && $lon !== null) {
                        error_log("Geocoded $city: lat=$lat, lon=$lon");
                        return [(float)$lat, (float)$lon];
                    }
                }
                // Request succeeded but Nominatim had no match for this city name.
                $this->lastGeocodeError = 'Nominatim returned no match for "' . $city . '".';
                return null;
            }

            $this->lastGeocodeError = 'Nominatim request failed: HTTP ' . $response->status();
            return null;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $this->lastGeocodeError = 'Could not connect to Nominatim (' . $e->getMessage() . '). '
                . 'This usually means your server cannot reach nominatim.openstreetmap.org — check your '
                . 'hosting firewall / outbound network rules.';
            \Log::warning('Nominatim connection error for city "' . $city . '": ' . $e->getMessage());
            return null;
        } catch (\Exception $e) {
            $this->lastGeocodeError = $e->getMessage();
            \Log::warning('Nominatim geocoding failed for city "' . $city . '": ' . $e->getMessage());
            return null;
        }
    }

    protected function discoverAndDiagnose(string $city, string $type): array
    {
        // type: builder | agent
        $queryPrefix = $type === 'builder'
            ? 'real estate builders and developers'
            : 'real estate agents and property dealers';

        // 1) Mappls (if credentials are configured) — real, actively-maintained,
        // India-focused business data. Tried first because OSM's coverage of
        // Indian cities is inconsistent. If it's not configured, errors out, or
        // just finds nothing, we always continue on to the free OSM path below
        // rather than stopping — broken/unset Mappls credentials should never
        // block the free fallback from running.
        $mapplsResult = $this->queryMappls($city, $queryPrefix);
        if (!empty($mapplsResult['results'])) {
            return ['candidates' => array_slice($mapplsResult['results'], 0, 50), 'notice' => null];
        }
        $mapplsError = $mapplsResult['error']; // kept for the final message only, doesn't stop execution

        $reachedOverpassWithRealCoordinates = false;

        // 2) Free OpenStreetMap fallback (used automatically when no Google key
        // is configured, when Google errored, or when Google found nothing).
        // 2a) Geocode the city via Nominatim.
        $coordinates = $this->getCityCoordinates($city);

        // 2b) If that failed, fall back to a hardcoded center for major Indian cities.
        $usedFallbackCenter = false;
        if (!$coordinates) {
            $coordinates = $this->getFallbackCoordinatesForCity($city);
            $usedFallbackCenter = (bool) $coordinates;
        }

        // 2c) If we have coordinates from either source, actually query Overpass.
        if ($coordinates) {
            [$lat, $lon] = $coordinates;
            $query = $this->buildOverpassQueryFromCoordinates($city, $queryPrefix, $lat, $lon, 15000);
            $elements = $this->queryOverpass($query);
            $reachedOverpassWithRealCoordinates = ($this->lastOverpassError === null);

            $results = [];
            foreach ($elements as $element) {
                $candidate = $this->mapOsmElementToCandidate($element, $city);
                if ($candidate) {
                    $results[] = $candidate;
                }
            }
            $results = array_slice($results, 0, 50);

            if (!empty($results)) {
                $notice = $usedFallbackCenter
                    ? 'Showing results using a fallback search center near ' . ucfirst($city) . '.'
                    : null;
                return ['candidates' => $results, 'notice' => $notice];
            }
        }

        // 3) Nothing found. Build a specific, honest explanation from whatever
        // diagnostics were actually captured above, rather than assuming.
        $notice = $this->buildDiagnosticNotice($city, $coordinates !== null, $reachedOverpassWithRealCoordinates, $mapplsError);

        return ['candidates' => [], 'notice' => $notice];
    }

    /**
     * Turns the connection-level diagnostics captured during this request into
     * a specific, actionable message — telling apart "the request itself never
     * worked" (network/firewall problem — fixable on the server) from "the
     * request worked but OSM just has no data here" (a coverage gap — CSV
     * import is the real fix) from "we couldn't even geocode this city name".
     */
    protected function buildDiagnosticNotice(string $city, bool $hadCoordinates, bool $overpassSucceeded, ?string $mapplsError = null): string
    {
        if ($this->lastGeocodeError && str_contains($this->lastGeocodeError, 'Could not connect')) {
            return '⚠️ ' . $this->lastGeocodeError;
        }

        if ($this->lastOverpassError && str_contains($this->lastOverpassError, 'Could not connect')) {
            return '⚠️ ' . $this->lastOverpassError;
        }

        if (!$hadCoordinates) {
            return '⚠️ Could not determine coordinates for "' . $city . '" — Nominatim did not recognize this '
                . 'city name and no fallback center is configured for it. Try a nearby larger city name '
                . '(e.g. the nearest state capital), or use the CSV import option (choose "Property" as the '
                . 'type) to add listings manually instead.';
        }

        if (!$overpassSucceeded && $this->lastOverpassError) {
            return '⚠️ The Overpass API request did not complete successfully: ' . $this->lastOverpassError
                . '. This is a request-level failure (not just "no data") — check your server logs for the full '
                . 'error, and confirm your hosting firewall allows outbound HTTPS requests to overpass-api.de.';
        }

        // Coordinates were resolved and the Overpass request completed without a
        // connection/HTTP error — it just returned zero matches. This really is
        // most likely a genuine OpenStreetMap data-coverage gap.
        $mapplsNote = $mapplsError
            ? ' (Note: Mappls credentials are configured but that request also failed: ' . $mapplsError . ')'
            : '';

        return 'No real estate businesses found in OpenStreetMap for "' . $city . '" within a 15km radius.'
            . $mapplsNote . ' The request to OpenStreetMap completed successfully but returned zero matches, so '
            . 'this looks like a genuine data-coverage gap rather than a connection problem — OSM\'s '
            . 'business-listing coverage varies a lot by city in India. Try a nearby larger city/locality name, '
            . 'or use the CSV import option (choose "Property" as the type) to add listings manually instead.';
    }

    protected function getFallbackCoordinatesForCity(string $city): ?array
    {
        // Approximate centers for major Indian cities, used when Nominatim
        // geocoding fails or is rate-limited.
        $map = [
            'zirakpur' => [30.6646, 76.7929],
            'mohali' => [30.6785, 76.7230],
            'chandigarh' => [30.7333, 76.7794],
            'panchkula' => [30.7056, 76.8585],
            'derabassi' => [30.6630, 76.7140],
            'kharar' => [30.7490, 76.6500],
            'kharar mohali' => [30.7490, 76.6500],
            'pune' => [18.5204, 73.8567],
            'lucknow' => [26.8467, 80.9462],
            'delhi' => [28.6139, 77.2090],
            'mumbai' => [19.0760, 72.8777],
            'bangalore' => [12.9716, 77.5946],
            'bengaluru' => [12.9716, 77.5946],
            'hyderabad' => [17.3850, 78.4867],
            'chennai' => [13.0827, 80.2707],
            'kolkata' => [22.5726, 88.3639],
            'ahmedabad' => [23.0225, 72.5714],
            'jaipur' => [26.9124, 75.7873],
            'surat' => [21.1702, 72.8311],
            'kanpur' => [26.4499, 80.3319],
            'nagpur' => [21.1458, 79.0882],
            'indore' => [22.7196, 75.8577],
            'bhopal' => [23.2599, 77.4126],
            'patna' => [25.5941, 85.1376],
            'noida' => [28.5355, 77.3910],
            'gurugram' => [28.4595, 77.0266],
            'gurgaon' => [28.4595, 77.0266],
            'ghaziabad' => [28.6692, 77.4538],
            'faridabad' => [28.4089, 77.3178],
        ];

        return $map[$city] ?? null;
    }

    /**
     * Builds the Overpass QL query for a given center point + radius.
     *
     * Strict OSM tagging (office=estate_agent, shop=real_estate_agency, etc.)
     * has sparse coverage in most Indian cities outside a handful of
     * well-mapped metros, so — in addition to the standard tags — this also
     * matches on business name (e.g. "... Properties", "... Realty",
     * "... Builders") to catch real-world listings that were mapped without
     * the "correct" OSM tag. This trades a little precision for much better
     * recall; the admin still reviews and ticks every row before anything
     * is saved, so false positives are cheap to reject.
     */
    protected function buildOverpassQueryFromCoordinates(string $city, string $queryPrefix, float $lat, float $lon, int $radius): string
    {
        $isBuilder = strpos(strtolower($queryPrefix), 'builder') !== false
            || strpos(strtolower($queryPrefix), 'developer') !== false;

        $around = "around:{$radius},{$lat},{$lon}";
        $lines = ['[out:json][timeout:30];', '('];

        // Standard OSM tags.
        $lines[] = "  nwr[\"office\"=\"estate_agent\"]({$around});";
        $lines[] = "  nwr[\"amenity\"=\"real_estate_agency\"]({$around});";
        $lines[] = $isBuilder
            ? "  nwr[\"shop\"=\"real_estate_agency\"]({$around});"
            : "  nwr[\"shop\"=\"estate_agent\"]({$around});";

        // Name-based fallback for businesses mapped without a real-estate-specific tag.
        $namePattern = $isBuilder
            ? 'builders|developers|realty|properties|infra|construction'
            : 'real estate|realty|properties|property dealer|estate agent';
        $lines[] = "  nwr[\"name\"~\"{$namePattern}\",i]({$around});";

        $lines[] = ');';
        $lines[] = 'out center;';

        $query = implode("\n", $lines);
        error_log("Overpass query for $city ($queryPrefix): center=($lat,$lon), radius=$radius");

        return $query;
    }


    protected function mapOsmElementToCandidate(array $element, string $city): ?array
    {
        // Extract basic information
        $tags = $element['tags'] ?? [];

        // Get name
        $name = $tags['name'] ?? null;
        if (!$name) {
            // Try alternative name fields
            $name = $tags['official_name'] ?? $tags['brand'] ?? $tags['operator'] ?? null;
        }

        if (!$name) {
            // Skip if no name
            return null;
        }

        // Get coordinates
        $lat = null;
        $lon = null;

        if (isset($element['lat']) && isset($element['lon'])) {
            $lat = $element['lat'];
            $lon = $element['lon'];
        } elseif (isset($element['center']['lat']) && isset($element['center']['lon'])) {
            // For ways and relations, the center is provided
            $lat = $element['center']['lat'];
            $lon = $element['center']['lon'];
        }

        if ($lat === null || $lon === null) {
            // Skip if no coordinates
            return null;
        }

        // Get address components
        $addressParts = [];
        if ($tags['house_number'] ?? false) {
            $addressParts[] = $tags['house_number'];
        }
        if ($tags['street'] ?? false) {
            $addressParts[] = $tags['street'];
        }
        if ($tags['postcode'] ?? false) {
            $addressParts[] = $tags['postcode'];
        }
        if ($tags['city'] ?? false) {
            $addressParts[] = $tags['city'];
        }

        $address = implode(', ', array_filter($addressParts));
        if (!$address) {
            $address = $tags['address'] ?? null;
        }

        // Get contact info
        $phone = $tags['phone'] ?? $tags['contact:phone'] ?? $tags['telephone'] ?? null;
        $website = $tags['website'] ?? $tags['contact:website'] ?? $tags['url'] ?? null;

        return [
            'source'          => 'openstreetmap',
            'source_place_id' => $element['id'] ?? null,
            'name'            => $name,
            'company_name'    => $name,
            'phone'           => $phone,
            'website'         => $website,
            'address'         => $address,
            'city'            => $city,
            'rating'          => null, // OSM doesn't typically have ratings
            'latitude'        => $lat,
            'longitude'       => $lon,
        ];
    }

    /**
     * Property listings can't be auto-crawled from third-party portals or search
     * engines — that would violate their Terms of Service (and Google/portal
     * scraping is actively blocked/prohibited). Instead, admins upload a CSV and
     * we stage the rows here for review exactly like the builder/agent flow —
     * nothing is written to the properties table until confirmed.
     *
     * @return array{candidates: array, notice: ?string}
     */
    protected function discoverProperties(string $city, $csvFile = null): array
    {
        if (!$csvFile) {
            return [
                'candidates' => [],
                'notice' => 'Live property-listing data cannot be auto-crawled from third-party portals or '
                    . 'search engines (it would violate their Terms of Service). Upload a CSV file below to '
                    . 'import properties in bulk instead — nothing is saved until you review and confirm the rows.',
            ];
        }

        $rows = self::readCsvRows($csvFile->getRealPath());
        $parsed = self::parseCsvPropertyRows($rows, $city);

        $notice = null;
        if ($parsed['skipped'] > 0) {
            $notice = $parsed['skipped'] . ' row(s) were skipped because they were missing a required '
                . 'column (title, property_type, looking_for, address, city, state, price). '
                . count($parsed['candidates']) . ' row(s) are ready to review below.';
        }

        return ['candidates' => $parsed['candidates'], 'notice' => $notice];
    }

    /**
     * Reads a CSV file into an array of associative rows (header => value).
     * Pulled out as its own method so it's trivial to unit test without a DB.
     */
    public static function readCsvRows(string $path): array
    {
        $rows = [];
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return $rows;
        }

        $header = fgetcsv($handle);
        if ($header === false) {
            fclose($handle);
            return $rows;
        }
        // Strip a UTF-8 BOM if present on the first header cell (common with Excel exports).
        $header[0] = preg_replace("/^\xEF\xBB\xBF/", '', $header[0]);
        $header = array_map(fn ($h) => strtolower(trim($h)), $header);

        while (($row = fgetcsv($handle)) !== false) {
            // Skip fully blank lines (fgetcsv returns [null] for them).
            if ($row === [null] || $row === false) {
                continue;
            }
            // Pad/truncate the row to match the header length so array_combine never throws.
            if (count($row) < count($header)) {
                $row = array_pad($row, count($header), null);
            } elseif (count($row) > count($header)) {
                $row = array_slice($row, 0, count($header));
            }
            $rows[] = array_combine($header, $row);
        }
        fclose($handle);

        return $rows;
    }

    /**
     * Validates and normalizes raw CSV rows into candidate property records.
     * Framework-independent (no DB/Http calls) so it can be unit tested directly.
     *
     * @return array{candidates: array, skipped: int}
     */
    public static function parseCsvPropertyRows(array $rows, string $defaultCity): array
    {
        $required = ['title', 'property_type', 'looking_for', 'address', 'city', 'state', 'price'];
        $allowedLookingFor = ['Sale', 'Rent', 'PG', 'Renovate'];

        $candidates = [];
        $skipped = 0;

        foreach ($rows as $row) {
            // Normalize keys/values (trim whitespace, drop empty strings so isset() checks below work).
            $row = array_map(fn ($v) => is_string($v) ? trim($v) : $v, $row);
            $row = array_filter($row, fn ($v) => $v !== null && $v !== '');

            $hasAllRequired = true;
            foreach ($required as $field) {
                if (!isset($row[$field]) || $row[$field] === '') {
                    $hasAllRequired = false;
                    break;
                }
            }
            if (!$hasAllRequired) {
                $skipped++;
                continue;
            }

            // Normalize looking_for casing (e.g. "sale" / "SALE" -> "Sale"); fall back to "Sale" if unrecognized.
            $lookingFor = ucfirst(strtolower($row['looking_for']));
            if (!in_array($lookingFor, $allowedLookingFor, true)) {
                $lookingFor = 'Sale';
            }

            // Price must be a plain positive number (strip commas/currency symbols admins often paste in).
            $price = preg_replace('/[^0-9.]/', '', (string) $row['price']);
            if ($price === '' || !is_numeric($price)) {
                $skipped++;
                continue;
            }

            $candidates[] = [
                'source'        => 'manual_csv',
                'title'         => $row['title'],
                'description'   => $row['description'] ?? null,
                'property_type' => $row['property_type'],
                'looking_for'   => $lookingFor,
                'address'       => $row['address'],
                'city'          => $row['city'] ?: $defaultCity,
                'state'         => $row['state'],
                'country'       => $row['country'] ?? 'India',
                'price'         => (float) $price,
                'bedrooms'      => isset($row['bedrooms']) ? (int) $row['bedrooms'] : null,
                'bathrooms'     => isset($row['bathrooms']) ? (int) $row['bathrooms'] : null,
                'area'          => isset($row['area']) ? (int) $row['area'] : null,
                'furnishing'    => $row['furnishing'] ?? null,
                'amenities'     => $row['amenities'] ?? null,
            ];
        }

        return ['candidates' => $candidates, 'skipped' => $skipped];
    }
}