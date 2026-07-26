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
    /** @var string[] Tried in order; first success wins. overpass-api.de is
     * first because it's the instance confirmed reachable from this
     * deployment. The mirror alternatives after it exist for when
     * overpass-api.de itself is overloaded (a known issue — it's a free,
     * heavily-used shared server), but this deployment's own outbound
     * connections to those specific mirrors have failed with 0-byte
     * responses, consistent with a host-level firewall block on those
     * domains rather than the mirrors being down.
     */
    protected array $overpassUrls;
    protected ?string $nominatimUrl;
    protected ?string $mapplsApiKey;

    /**
     * Diagnostic info from the most recent request, so the UI can tell the
     * difference between "the request itself failed" (network/firewall/DNS/
     * rate-limit) and "the request succeeded but found zero matches" (a
     * genuine OSM data-coverage gap). Both look like "no candidates" to the
     * end user otherwise, but they need very different fixes.
     */
    protected ?string $lastGeocodeError = null;
    protected ?string $lastOverpassError = null;

    public function __construct()
    {
        // If openstreetmap.overpass_url is explicitly configured, respect it as
        // the only endpoint (someone deliberately pointed this at a specific
        // instance, e.g. a self-hosted one). Otherwise, use the known-good
        // mirror list with automatic failover.
        // Order matters: overpass-api.de is tried first because it's the one
        // confirmed reachable from this deployment (a real search succeeded
        // through it). The mirrors below it are kept as fallbacks for when
        // overpass-api.de itself is overloaded, but real-world testing showed
        // "0 bytes received" connection failures to both of them specifically —
        // consistent with a host-level firewall blocking those two domains by
        // name/IP rather than general network trouble (which would affect all
        // three equally). If your host later confirms/lifts a block on these,
        // reordering here is the only change needed.
        $configuredUrl = config('services.openstreetmap.overpass_url');
        $this->overpassUrls = $configuredUrl ? [$configuredUrl] : [
            'https://overpass-api.de/api/interpreter',
            'https://overpass.kumi.systems/api/interpreter',
            'https://overpass.private.coffee/api/interpreter',
        ];
        $this->nominatimUrl = config('services.openstreetmap.nominatim_url', 'https://nominatim.openstreetmap.org');
        $this->mapplsApiKey = config('services.mappls.key');
    }

    /**
     * @return array{candidates: array, notice: ?string}
     */
    public function discover(string $type, string $city, $csvFile = null, ?string $builderName = null): array
    {
        $normalizedCity = $this->normalizeCity($city);
        $normalizedBuilderName = $builderName !== null ? $this->normalizeBuilderName($builderName) : null;

        return match ($type) {
            'builder' => $this->discoverAndDiagnose($normalizedCity, 'builder', $normalizedBuilderName),
            'agent'   => $this->discoverAndDiagnose($normalizedCity, 'agent'),
            'property' => $this->discoverProperties($normalizedCity, $csvFile),
            default => ['candidates' => [], 'notice' => 'Unknown type.'],
        };
    }

    /**
     * Cleans up the user-typed builder name before it's woven into search
     * queries. Multi-spaces collapsed, trimmed; never lowercased because
     * Mappls/Overpass match names case-insensitively but the case the admin
     * typed is the case the user expects to see in the result list.
     */
    protected function normalizeBuilderName(string $name): ?string
    {
        $name = trim($name);
        if ($name === '') {
            return null;
        }
        return preg_replace('/\s+/', ' ', $name);
    }

    protected function normalizeCity(string $city): string
    {
        $city = trim($city);
        $city = str_replace(['-', '_'], ' ', $city);
        $city = preg_replace('/\s+/', ' ', $city);

        return strtolower($city);
    }

    /**
     * A narrower version of normalizeCity() used only for looking up
     * getFallbackCoordinatesForCity()'s hardcoded map, whose keys are single
     * words (e.g. "zirakpur"). This intentionally keeps the old "just the
     * first word" behavior, but ONLY as a fallback lookup key — never as the
     * actual city text sent to search APIs, since that previously mangled
     * genuine multi-word places (e.g. "Peer Muchalla" -> "peer", "New Delhi"
     * -> "new") into near-meaningless single words.
     */
    protected function firstWordOf(string $normalizedCity): string
    {
        $parts = explode(' ', $normalizedCity);
        return $parts[0] ?? $normalizedCity;
    }

    /**
     * Query text variants tried per type. Mappls' search is a deterministic
     * "top N for this exact query string" lookup — sending the same single
     * query every time (as this used to) means a repeat search for the same
     * city always returns the identical result set. Running several
     * different-but-related query strings and merging the results surfaces a
     * meaningfully wider set of real businesses instead.
     */
    protected function mapplsQueryVariants(string $type): array
    {
        return $type === 'builder'
            ? ['real estate builders', 'property developers', 'construction company', 'real estate developers']
            : ['real estate agents', 'property dealers', 'real estate agency', 'real estate consultants'];
    }

    /**
     * Mappls (MapmyIndia) Autosuggest/Search API — the primary data source
     * when a key is configured. An India-focused mapping company with "no
     * credit card required" free signup and far deeper coverage of Indian
     * cities/towns than global providers like OSM. Uses the static REST API
     * key directly as the `access_token` query parameter (no OAuth token
     * exchange needed — this matches the "Static Key" shown in the Mappls
     * Console under Credentials). Set MAPPLS_API_KEY to enable; without it,
     * this is skipped entirely and the service falls back to the free,
     * zero-config OpenStreetMap path below.
     *
     * Fires several query-text variants (see mapplsQueryVariants()) at once
     * via Http::pool() so the wall-clock cost stays roughly the same as a
     * single request (bounded by the slowest of the batch, not their sum)
     * while returning a meaningfully more varied result set than any one
     * query alone — this is what fixes "always the same data" without
     * making the timeout problem worse.
     *
     * @return array{results: array, error: ?string}
     */
    protected function queryMappls(string $city, string $queryPrefix): array
    {
        if (!$this->mapplsApiKey) {
            return ['results' => [], 'error' => null]; // Not configured — silently skip, not an error.
        }

        // Check the free, instant hardcoded map first — most searches are for
        // a handful of major metros/NCR towns that are already in it, so this
        // avoids an ~8s Nominatim round-trip for the common case. Only falls
        // through to the network geocoder for cities not in that list.
        $coordinates = $this->getFallbackCoordinatesForCity($city) ?? $this->getCityCoordinates($city);

        // $queryPrefix's caller-provided value is kept as the type ('builder'
        // vs 'agent') isn't otherwise available here — infer it from which
        // variant list it matches so this stays a drop-in replacement.
        $isBuilder = str_contains($queryPrefix, 'builder');
        $variants = $this->mapplsQueryVariants($isBuilder ? 'builder' : 'agent');

        $buildParams = function (string $variantQuery) use ($city, $coordinates) {
            $params = [
                'query' => $variantQuery . ' in ' . ucwords($city),
                'region' => 'ind',
                'access_token' => $this->mapplsApiKey,
            ];
            if ($coordinates) {
                $params['location'] = $coordinates[0] . ',' . $coordinates[1];
            }
            return $params;
        };

        try {
            // Fire all variants concurrently — total wall time is bounded by
            // the slowest single response, not the sum of all of them.
            $responses = Http::pool(fn ($pool) => array_map(
                fn ($variantQuery) => $pool->timeout(7)->get(
                    'https://atlas.mappls.com/api/places/search/json',
                    $buildParams($variantQuery)
                ),
                $variants
            ));

            $allResults = [];
            $lastAuthError = null;
            $anySucceeded = false;

            foreach ($responses as $response) {
                // Http::pool() returns a ConnectionException object (not a
                // Response) for entries that failed to connect/timed out —
                // skip those individually rather than failing the whole batch.
                if (!($response instanceof \Illuminate\Http\Client\Response)) {
                    continue;
                }

                if ($response->status() === 401 || $response->status() === 403) {
                    $lastAuthError = 'Mappls rejected the request (HTTP ' . $response->status() . '). '
                        . 'Check that MAPPLS_API_KEY is correct and active in the Mappls Console '
                        . '(Credentials tab), and that it isn\'t restricted to a different domain/IP under Whitelisting.';
                    continue;
                }
                if ($response->status() === 204 || $response->failed()) {
                    continue;
                }

                $anySucceeded = true;
                $locations = $response->json('suggestedLocations', []);
                foreach ($locations as $place) {
                    $candidate = self::mapMapplsPlaceToCandidate($place, $city);
                    if ($candidate) {
                        $allResults[] = $candidate;
                    }
                }
            }

            $merged = [];
            foreach ($allResults as $candidate) {
                $merged = $this->mergeUniqueCandidates($merged, [$candidate]);
            }

            if (!$anySucceeded && $lastAuthError) {
                return ['results' => [], 'error' => $lastAuthError];
            }
            if (!$anySucceeded && empty($merged)) {
                return ['results' => [], 'error' => 'Could not connect to Mappls, or all requests timed out. '
                    . 'Check your server can reach atlas.mappls.com within a few seconds.'];
            }

            return ['results' => $merged, 'error' => null];
        } catch (\Exception $e) {
            \Log::warning('Mappls Search error: ' . $e->getMessage());
            return ['results' => [], 'error' => $e->getMessage()];
        }
    }

    /**
     * Maps one raw suggestedLocations entry from Mappls' Search response
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

    /**
     * @param int $maxAttempts How many mirrors to try before giving up. Kept low
     *   for the expensive name-regex phase (see discoverAndDiagnose) so a total
     *   failure there can't blow the overall request past PHP's execution time
     *   limit — full failover across all mirrors is reserved for the cheap tag
     *   query, which is the common/important path.
     */
    protected function queryOverpass(string $query, int $maxAttempts = 3): array
    {
        $this->lastOverpassError = null;

        // The query strings are built with literal "\n" inside single-quoted
        // PHP strings, which PHP does NOT convert to real newlines. Left as-is,
        // Overpass receives a syntactically invalid query (stray backslashes)
        // and rejects it. Normalize to real whitespace before sending.
        $query = str_replace('\\n', "\n", $query);

        $errors = [];
        $urlsToTry = array_slice($this->overpassUrls, 0, max(1, $maxAttempts));

        foreach ($urlsToTry as $url) {
            try {
                // Loosened from 6s after seeing all 3 independent mirrors time out
                // identically in real usage — that pattern points to the timeout
                // being too aggressive for this server's network conditions, not
                // three unrelated servers failing simultaneously. Kept well under
                // typical shared-hosting PHP max_execution_time even when summed
                // across a few mirror attempts (see maxAttempts docblock above).
                $response = Http::asForm()
                    ->timeout(12)
                    ->withHeaders(['User-Agent' => 'IndianEstHub-CityImport/1.0 (contact: admin@indianesthub.com)'])
                    ->post($url, ['data' => $query]);

                if ($response->status() === 504 || $response->status() === 429) {
                    $errors[] = parse_url($url, PHP_URL_HOST) . ': HTTP ' . $response->status()
                        . ($response->status() === 504 ? ' (timeout)' : ' (rate-limited)');
                    continue; // try the next mirror
                }

                if ($response->failed()) {
                    $errors[] = parse_url($url, PHP_URL_HOST) . ': HTTP ' . $response->status();
                    continue; // try the next mirror
                }

                $data = $response->json();

                if (isset($data['error'])) {
                    $errors[] = parse_url($url, PHP_URL_HOST) . ': ' . $data['error'];
                    continue; // try the next mirror
                }

                $elements = $data['elements'] ?? [];
                error_log("Overpass ($url) returned " . count($elements) . " elements");
                return $elements; // success — no need to try further mirrors
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                $errors[] = parse_url($url, PHP_URL_HOST) . ': could not connect (' . $e->getMessage() . ')';
                continue; // try the next mirror
            } catch (\Exception $e) {
                $errors[] = parse_url($url, PHP_URL_HOST) . ': ' . $e->getMessage();
                continue; // try the next mirror
            }
        }

        // Every attempted mirror failed.
        $summary = implode(' | ', $errors);
        $allConnectionErrors = count($errors) === count($urlsToTry)
            && str_contains($summary, 'could not connect');

        if ($allConnectionErrors) {
            $this->lastOverpassError = 'Could not connect to any Overpass mirror (' . $summary . '). '
                . 'This usually means your server cannot make outbound HTTPS requests at all — check your '
                . 'hosting firewall / outbound network rules.';
        } else {
            $this->lastOverpassError = 'All Overpass mirrors failed: ' . $summary . '. This looks like the '
                . 'public Overpass instances are having a rough moment right now (they\'re free, shared, '
                . 'community-run servers) — usually transient, worth trying again shortly.';
        }

        \Log::warning('Overpass API: all mirrors failed — ' . $summary);
        return [];
    }

    /**
     * Merges two candidate lists (e.g. tag-query + name-query OSM results),
     * keeping the first occurrence of each and dropping later duplicates.
     * Prefers source_place_id when present (exact OSM element match); falls
     * back to a normalized name+city key for elements without one.
     */
    protected function mergeUniqueCandidates(array $first, array $second): array
    {
        $merged = $first;
        $seen = [];
        foreach ($merged as $candidate) {
            $seen[$this->candidateDedupeKey($candidate)] = true;
        }

        foreach ($second as $candidate) {
            $key = $this->candidateDedupeKey($candidate);
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $merged[] = $candidate;
            }
        }

        return $merged;
    }

    protected function candidateDedupeKey(array $candidate): string
    {
        if (!empty($candidate['source_place_id'])) {
            return 'id:' . $candidate['source_place_id'];
        }

        return 'name:' . strtolower(trim($candidate['name'] ?? '')) . '|' . strtolower(trim($candidate['city'] ?? ''));
    }

    protected function getCityCoordinates(string $city): ?array
    {
        $this->lastGeocodeError = null;

        try {
            // Use Nominatim to get coordinates for the city
            $response = Http::timeout(8)
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
        // 2a) Check the free, instant hardcoded map first (same reasoning as
        // the Mappls path above — most searches are for cities already in it,
        // so this skips an ~8s Nominatim round-trip for the common case).
        $coordinates = $this->getFallbackCoordinatesForCity($city);
        $usedFallbackCenter = (bool) $coordinates;

        // 2b) Only hit Nominatim over the network for cities not in that map.
        if (!$coordinates) {
            $coordinates = $this->getCityCoordinates($city);
            $usedFallbackCenter = false;
        }

        // 2c) If we have coordinates from either source, actually query Overpass.
        // Two phases: a cheap tag-only query first at the full radius, and a
        // name-regex query over a smaller radius (name-regex is much pricier
        // for Overpass to evaluate, hence the smaller radius + single mirror).
        // Both are always run and merged — previously the name-regex query
        // only ran when the tag query found literally nothing, which meant a
        // city with 1-2 tagged businesses would show only those same 1-2
        // results on every search, forever, even though a broader name-based
        // search over the same area could well turn up different builders
        // that just weren't tagged with the "correct" OSM office/shop tag
        // (very common in India). Merging catches those without giving up
        // the performance benefit of trying the cheap query first.
        if ($coordinates) {
            [$lat, $lon] = $coordinates;
            $isBuilder = ($type === 'builder');

            $tagQuery = $this->buildOverpassTagQuery($lat, $lon, 15000, $isBuilder);
            $elements = $this->queryOverpass($tagQuery, maxAttempts: 2); // cheap query — try 2 mirrors
            $reachedOverpassWithRealCoordinates = ($this->lastOverpassError === null);

            $results = $this->mapOsmElements($elements, $city);

            if ($this->lastOverpassError === null) {
                // Also run the broader name-regex query and merge in anything
                // new it finds, regardless of whether the tag query already
                // found something — this is what surfaces different builders
                // on repeat searches instead of the same handful every time.
                $nameQuery = $this->buildOverpassNameQuery($lat, $lon, 5000, $isBuilder);
                $nameElements = $this->queryOverpass($nameQuery, maxAttempts: 1); // expensive query — only 1 mirror, no failover
                $reachedOverpassWithRealCoordinates = $reachedOverpassWithRealCoordinates && ($this->lastOverpassError === null);
                $nameResults = $this->mapOsmElements($nameElements, $city);

                $results = $this->mergeUniqueCandidates($results, $nameResults);
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

    protected function getFallbackCoordinatesForCity(string $normalizedCity): ?array
    {
        // Approximate centers for major Indian cities/localities, used when
        // Nominatim geocoding fails or is rate-limited. Keys are full,
        // space-separated normalized names (output of normalizeCity()) so
        // multi-word localities need their own explicit entry here — a
        // partial/first-word match is tried separately as a last resort,
        // see below.
        $map = [
            'zirakpur' => [30.6646, 76.7929],
            'mohali' => [30.6785, 76.7230],
            'chandigarh' => [30.7333, 76.7794],
            'panchkula' => [30.7056, 76.8585],
            'derabassi' => [30.6630, 76.7140],
            'dera bassi' => [30.6630, 76.7140],
            'kharar' => [30.7490, 76.6500],
            'kharar mohali' => [30.7490, 76.6500],
            'peer muchalla' => [30.6616, 76.8633],
            'pune' => [18.5204, 73.8567],
            'lucknow' => [26.8467, 80.9462],
            'delhi' => [28.6139, 77.2090],
            'new delhi' => [28.6139, 77.2090],
            'mumbai' => [19.0760, 72.8777],
            'navi mumbai' => [19.0330, 73.0297],
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
            'greater noida' => [28.4744, 77.5040],
            'gurugram' => [28.4595, 77.0266],
            'gurgaon' => [28.4595, 77.0266],
            'ghaziabad' => [28.6692, 77.4538],
            'faridabad' => [28.4089, 77.3178],
        ];

        // 1) Exact match on the full (possibly multi-word) normalized city name.
        if (isset($map[$normalizedCity])) {
            return $map[$normalizedCity];
        }

        // 2) Last resort: try just the first word (e.g. an unlisted locality
        // like "Peer Muchalla Extension" might still match "peer muchalla"'s
        // neighboring town if we had "peer" mapped — in practice this mostly
        // helps cases like "Zirakpur City" -> "zirakpur"). Deliberately tried
        // only after the full-name match fails, and only as a coordinate
        // fallback — never as what gets sent to the search APIs themselves.
        $firstWord = $this->firstWordOf($normalizedCity);
        if ($firstWord !== $normalizedCity && isset($map[$firstWord])) {
            return $map[$firstWord];
        }

        return null;
    }

    /**
     * Builds the cheap, tag-only Overpass QL query for a given center point +
     * radius. Fast — Overpass can answer this from its tag index directly.
     */
    protected function buildOverpassTagQuery(float $lat, float $lon, int $radius, bool $isBuilder): string
    {
        $around = "around:{$radius},{$lat},{$lon}";
        $lines = ['[out:json][timeout:25];', '('];

        $lines[] = "  nwr[\"office\"=\"estate_agent\"]({$around});";
        $lines[] = "  nwr[\"amenity\"=\"real_estate_agency\"]({$around});";
        $lines[] = $isBuilder
            ? "  nwr[\"shop\"=\"real_estate_agency\"]({$around});"
            : "  nwr[\"shop\"=\"estate_agent\"]({$around});";

        $lines[] = ');';
        $lines[] = 'out center;';

        return implode("\n", $lines);
    }

    /**
     * Builds the name-regex Overpass QL query — catches real-world listings
     * that were mapped without the "correct" OSM tag (very common in India),
     * but regex scans over "name" are expensive for Overpass to evaluate and
     * can time out over a large radius in a densely-mapped metro. Only called
     * with a deliberately smaller radius than the tag query for that reason.
     */
    protected function buildOverpassNameQuery(float $lat, float $lon, int $radius, bool $isBuilder): string
    {
        $around = "around:{$radius},{$lat},{$lon}";
        $namePattern = $isBuilder
            ? 'builders|developers|realty|properties|infra|construction'
            : 'real estate|realty|properties|property dealer|estate agent';

        $lines = ['[out:json][timeout:25];', '('];
        $lines[] = "  nwr[\"name\"~\"{$namePattern}\",i]({$around});";
        $lines[] = ');';
        $lines[] = 'out center;';

        return implode("\n", $lines);
    }


    /**
     * Maps a raw array of Overpass elements into candidate records, dropping
     * any that couldn't be mapped (e.g. no usable name).
     */
    protected function mapOsmElements(array $elements, string $city): array
    {
        $results = [];
        foreach ($elements as $element) {
            $candidate = $this->mapOsmElementToCandidate($element, $city);
            if ($candidate) {
                $results[] = $candidate;
            }
        }
        return $results;
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