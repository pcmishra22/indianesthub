<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * CityDataDiscoveryService
 *
 * Finds candidate real-world businesses for a given city so an admin can
 * review and confirm them before they're written into the database.
 *
 * IMPORTANT — what this does and doesn't do:
 *
 * - Builders & Agents: uses the Google Places API (Text Search + Place
 *   Details), which is a legitimate, ToS-compliant, paid API designed
 *   exactly for "find businesses of type X in city Y". Requires your own
 *   Google Cloud API key with the Places API enabled — see setup notes
 *   at the bottom of this file.
 *
 * - Properties (individual for-sale listings): there is no general,
 *   ToS-compliant public API for pulling live listings off portals like
 *   99acres / MagicBricks / Housing.com. Scraping those sites directly
 *   would violate their Terms of Service and their anti-bot protections,
 *   so this service does not do that. discoverProperties() intentionally
 *   returns an empty result with an explanatory message. If you have (or
 *   can license) a legitimate listings data feed — an MLS-style API, a
 *   paid data-provider contract, or your own dealer-submitted feed — wire
 *   it in here and the rest of the review/confirm pipeline works unchanged.
 *   In the meantime, use the CSV import path for properties.
 */
class CityDataDiscoveryService
{
    protected ?string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.google_places.key');
    }

    /**
     * @return array{candidates: array, notice: ?string}
     */
    public function discover(string $type, string $city): array
    {
        return match ($type) {
            'builder' => [
                'candidates' => $this->discoverBusinesses($city, 'real estate builders and developers in'),
                'notice'     => null,
            ],
            'agent' => [
                'candidates' => $this->discoverBusinesses($city, 'real estate agents and property dealers in'),
                'notice'     => null,
            ],
            'property' => [
                'candidates' => $this->discoverProperties($city),
                'notice'     => 'Live property-listing data cannot be auto-crawled from third-party portals '
                    . '(it would violate their Terms of Service). Use the CSV import option below, or wire a '
                    . 'licensed listings feed into CityDataDiscoveryService::discoverProperties().',
            ],
            default => ['candidates' => [], 'notice' => 'Unknown type.'],
        };
    }

    protected function discoverBusinesses(string $city, string $queryPrefix): array
    {
        if (!$this->apiKey) {
            throw new \RuntimeException(
                'GOOGLE_PLACES_API_KEY is not configured. Add it to .env and config/services.php to enable discovery.'
            );
        }

        $query = "{$queryPrefix} {$city}";
        $results = [];
        $pageToken = null;
        $pagesFetched = 0;

        do {
            $params = $pageToken
                ? ['pagetoken' => $pageToken, 'key' => $this->apiKey]
                : ['query' => $query, 'key' => $this->apiKey];

            if ($pageToken) {
                // Google requires a short delay before a next_page_token becomes usable.
                sleep(2);
            }

            $response = Http::get('https://maps.googleapis.com/maps/api/place/textsearch/json', $params);
            $data = $response->json() ?? [];

            foreach ($data['results'] ?? [] as $place) {
                $results[] = $this->mapPlaceToCandidate($place, $city);
            }

            $pageToken = $data['next_page_token'] ?? null;
            $pagesFetched++;
        } while ($pageToken && $pagesFetched < 3); // Places API caps at 3 pages (~60 results) per query

        return $results;
    }

    protected function mapPlaceToCandidate(array $place, string $city): array
    {
        $details = $this->fetchPlaceDetails($place['place_id'] ?? null);

        $name = $place['name'] ?? 'Unknown';

        return [
            'source'          => 'google_places',
            'source_place_id' => $place['place_id'] ?? null,
            'name'            => $name,
            'company_name'    => $name,
            'phone'           => $details['formatted_phone_number'] ?? null,
            'website'         => $details['website'] ?? null,
            'address'         => $place['formatted_address'] ?? null,
            'city'            => $city,
            'rating'          => $place['rating'] ?? null,
            'latitude'        => $place['geometry']['location']['lat'] ?? null,
            'longitude'       => $place['geometry']['location']['lng'] ?? null,
        ];
    }

    protected function fetchPlaceDetails(?string $placeId): array
    {
        if (!$placeId || !$this->apiKey) {
            return [];
        }

        $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
            'place_id' => $placeId,
            'fields'   => 'formatted_phone_number,website',
            'key'      => $this->apiKey,
        ]);

        return $response->json()['result'] ?? [];
    }

    protected function discoverProperties(string $city): array
    {
        return [];
    }
}

/**
 * SETUP NOTES
 * -----------
 * 1. Enable "Places API" in Google Cloud Console and generate an API key.
 * 2. Add to .env:      GOOGLE_PLACES_API_KEY=your_key_here
 * 3. Add to config/services.php:
 *      'google_places' => [
 *          'key' => env('GOOGLE_PLACES_API_KEY'),
 *      ],
 * 4. Google Places is a paid API beyond a monthly free credit — check current
 *    pricing before running this against many cities.
 * 5. Google Places does not return business email addresses (they aren't
 *    public business data). Imported builder/agent records get a placeholder
 *    email and no password login; have the admin edit the real email in once
 *    confirmed, or reach out to the business directly.
 */
