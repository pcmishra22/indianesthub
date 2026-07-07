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
    public function discover(string $type, string $city): array
    {
        $normalizedCity = $this->normalizeCity($city);

        return match ($type) {
            'builder' => [
                'candidates' => $this->discoverBusinesses($normalizedCity, 'real estate builders and developers'),
                'notice'     => null,
            ],
            'agent' => [
                'candidates' => $this->discoverBusinesses($normalizedCity, 'real estate agents and property dealers'),
                'notice'     => null,
            ],
            'property' => [
                'candidates' => $this->discoverProperties($normalizedCity),
                'notice'     => 'Live property-listing data cannot be auto-crawled from third-party portals '
                    . '(it would violate their Terms of Service). Use the CSV import option below, or wire a '
                    . 'licensed listings feed into CityDataDiscoveryService::discoverProperties().',
            ],
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
            $response = Http::timeout(30)
                ->post($this->overpassUrl, $query);

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

    protected function discoverProperties(string $city): array
    {
        // Properties (individual listings) are not available from OSM in a reliable way
        // This would require scraping real estate portals which violates their ToS
        return [];
    }
}