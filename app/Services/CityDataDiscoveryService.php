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
 * Uses OpenStreetMap Overpass API as a free alternative to Google Places.
 */
class CityDataDiscoveryService
{
    protected ?string $overpassUrl;
    protected ?string $nominatimUrl;

    public function __construct()
    {
        $this->overpassUrl = config('openstreetmap.overpass_url', 'https://overpass-api.de/api/interpreter');
        $this->nominatimUrl = config('openstreetmap.nominatim_url', 'https://nominatim.openstreetmap.org');
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

    protected function queryOverpass(string $query): array
    {
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
                throw new \RuntimeException('Overpass API request failed: ' . $response->status());
            }

            $data = $response->json();

            if (isset($data['error'])) {
                throw new \RuntimeException('Overpass API error: ' . $data['error']);
            }

            $elements = $data['elements'] ?? [];
            error_log("Overpass returned " . count($elements) . " elements");
            return $elements;
        } catch (\Exception $e) {
            // Log the error but return empty results to avoid breaking the flow
            \Log::warning('Overpass API error: ' . $e->getMessage());
            return [];
        }
    }

    protected function getCityCoordinates(string $city): ?array
    {
        try {
            // Use Nominatim to get coordinates for the city
            $response = Http::timeout(10)
                ->withHeaders(['User-Agent' => 'IndianEstHub-CityImport/1.0 (contact: admin@indianesthub.com)'])
                ->get($this->nominatimUrl . '/search', [
                    'q' => $city . ', India',
                    'format' => 'json',
                    'limit' => 1,
                    'addressdetails' => 1,
                    'bounded' => 1
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
            }

            return null;
        } catch (\Exception $e) {
            // If geocoding fails, we'll fall back to a less precise search
            \Log::warning('Nominatim geocoding failed for city "' . $city . '": ' . $e->getMessage());
            return null;
        }
    }

    protected function discoverBusinesses(string $city, string $queryPrefix): array
    {
        // Build Overpass QL query to find relevant businesses
        $query = $this->buildOverpassQuery($city, $queryPrefix);

        $elements = $this->queryOverpass($query);
        $results = [];

        foreach ($elements as $element) {
            $candidate = $this->mapOsmElementToCandidate($element, $city);
            if ($candidate) {
                $results[] = $candidate;
            }
        }

        // Limit results to prevent overwhelming the UI
        return array_slice($results, 0, 50);
    }

    protected function discoverAndDiagnose(string $city, string $type): array
    {
        // type: builder | agent
        $queryPrefix = $type === 'builder'
            ? 'real estate builders and developers'
            : 'real estate agents and property dealers';

        $notice = null;

        // 1) Try discovery with normal geocoding
        $candidates = $this->discoverBusinesses($city, $queryPrefix);

        if (!empty($candidates)) {
            return ['candidates' => $candidates, 'notice' => null];
        }

        // 2) If empty, attempt a city-specific fallback center for Tricity
        //    (Zirakpur is often inconsistently geocoded by free Nominatim results).
        $fallback = $this->getFallbackCoordinatesForCity($city);
        if ($fallback) {
            [$lat, $lon] = $fallback;
            $radius = 10000;

            $query = $this->buildOverpassQueryFromCoordinates($city, $queryPrefix, $lat, $lon, $radius);
            $elements = $this->queryOverpass($query);

            $results = [];
            foreach ($elements as $element) {
                $candidate = $this->mapOsmElementToCandidate($element, $city);
                if ($candidate) {
                    $results[] = $candidate;
                }
            }

            $results = array_slice($results, 0, 50);
            if (!empty($results)) {
                return ['candidates' => $results, 'notice' => 'No matches found using geocoding. Showing results using a fallback search center near ' . ucfirst($city) . '.'];
            }
        }

        // 3) Final user-facing explanation
        $notice = 'No candidates found for "' . $city . '". This can happen if Overpass/Nominatim is rate-limited or if the city name does not match OpenStreetMap data. Try again later or enter a nearby locality (e.g., Mohali/Chandigarh for Zirakpur).';

        return ['candidates' => [], 'notice' => $notice];
    }

    protected function getFallbackCoordinatesForCity(string $city): ?array
    {
        // Approximate centers for common Tricity searches.
        $map = [
            'zirakpur' => [30.6646, 76.7929],
            'mohali' => [30.6785, 76.7230],
            'chandigarh' => [30.7333, 76.7794],
            'panchkula' => [30.7056, 76.8585],
            'derabassi' => [30.6630, 76.7140],
            'kharar' => [30.7490, 76.6500],
            'kharar mohali' => [30.7490, 76.6500],
            'pune' => [18.5204, 73.8567],
        ];

        return $map[$city] ?? null;
    }

    protected function buildOverpassQueryFromCoordinates(string $city, string $queryPrefix, float $lat, float $lon, int $radius): string
    {
        $query = '[out:json][timeout:25];\n';
        $query .= '( \n';

        if (strpos(strtolower($queryPrefix), 'builder') !== false ||
            strpos(strtolower($queryPrefix), 'developer') !== false) {
            $query .= '  node["office"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
            $query .= '  way["office"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
            $query .= '  relation["office"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
            $query .= '  node["shop"="real_estate_agency"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
            $query .= '  way["shop"="real_estate_agency"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
            $query .= '  relation["shop"="real_estate_agency"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
            $query .= '  node["amenity"="real_estate_agency"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
            $query .= '  way["amenity"="real_estate_agency"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
            $query .= '  relation["amenity"="real_estate_agency"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
        } elseif (strpos(strtolower($queryPrefix), 'agent') !== false ||
                 strpos(strtolower($queryPrefix), 'dealer') !== false) {
            $query .= '  node["office"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
            $query .= '  way["office"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
            $query .= '  relation["office"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
            $query .= '  node["shop"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
            $query .= '  way["shop"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
            $query .= '  relation["shop"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
            $query .= '  node["amenity"="real_estate_agency"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
            $query .= '  way["amenity"="real_estate_agency"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
            $query .= '  relation["amenity"="real_estate_agency"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
        } else {
            $query .= '  node["office"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
            $query .= '  way["office"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
            $query .= '  relation["office"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
        }

        $query .= ');\n';
        $query .= 'out center;\n';

        error_log("Overpass fallback query for $city ($queryPrefix): center=($lat,$lon), radius=$radius");

        return $query;
    }


    protected function buildOverpassQuery(string $city, string $queryPrefix): string
    {
        // Get coordinates for the city
        $coordinates = $this->getCityCoordinates($city);

        if ($coordinates) {
            [$lat, $lon] = $coordinates;
            // Define a reasonable search radius (about 10km radius)
            $radius = 10000; // meters

            $query = '[out:json][timeout:25];\n';
            $query .= '( \n';

            // Define what we're looking for based on the query prefix
            if (strpos(strtolower($queryPrefix), 'builder') !== false ||
                strpos(strtolower($queryPrefix), 'developer') !== false) {
                // Real estate builders and developers
                $query .= '  node["office"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
                $query .= '  way["office"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
                $query .= '  relation["office"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
                $query .= '  node["shop"="real_estate_agency"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
                $query .= '  way["shop"="real_estate_agency"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
                $query .= '  relation["shop"="real_estate_agency"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
                $query .= '  node["amenity"="real_estate_agency"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
                $query .= '  way["amenity"="real_estate_agency"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
                $query .= '  relation["amenity"="real_estate_agency"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
            } elseif (strpos(strtolower($queryPrefix), 'agent') !== false ||
                     strpos(strtolower($queryPrefix), 'dealer') !== false) {
                // Real estate agents and property dealers
                $query .= '  node["office"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
                $query .= '  way["office"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
                $query .= '  relation["office"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
                $query .= '  node["shop"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
                $query .= '  way["shop"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
                $query .= '  relation["shop"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
                $query .= '  node["amenity"="real_estate_agency"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
                $query .= '  way["amenity"="real_estate_agency"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
                $query .= '  relation["amenity"="real_estate_agency"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
            } else {
                // Default to estate agents
                $query .= '  node["office"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
                $query .= '  way["office"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
                $query .= '  relation["office"="estate_agent"](around:' . $radius . ',' . $lat . ',' . $lon . ');\n';
            }

            $query .= ');\n';
            $query .= 'out center; // Include center coordinates for ways and relations\n';

            error_log("Overpass query for $city ($queryPrefix):\n$query");

            return $query;
        } else {
            // Fallback: if we can't geocode the city, return an empty query
            // This will result in no results but won't break the application
            error_log("Could not geocode $city, returning empty query");
            return '[out:json][timeout:5];\nout;';
        }
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