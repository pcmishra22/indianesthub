<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\BuilderProject;
use App\Models\Dealer;
use Illuminate\Http\Request;

class SeoLandingController extends Controller
{
    /**
     * Allowed city slugs → display names + location data.
     */
    private function getCityMap(): array
    {
        return [
            'zirakpur'   => ['label' => 'Zirakpur',   'state' => 'Punjab',        'desc' => 'a fast-growing real estate hub on the Chandigarh-Ambala highway'],
            'mohali'     => ['label' => 'Mohali',     'state' => 'Punjab',        'desc' => 'one of Chandigarh\'s most sought-after planned city extensions'],
            'chandigarh' => ['label' => 'Chandigarh', 'state' => 'Chandigarh UT', 'desc' => 'India\'s best-planned city with premium real estate'],
            'panchkula'  => ['label' => 'Panchkula',  'state' => 'Haryana',       'desc' => 'a rapidly developing satellite city next to Chandigarh'],
            'kharar'     => ['label' => 'Kharar',     'state' => 'Punjab',        'desc' => 'an affordable real estate destination near Mohali'],
            'derabassi'  => ['label' => 'Derabassi',  'state' => 'Punjab',        'desc' => 'a growing township between Chandigarh and Ambala'],
            'mullanpur'  => ['label' => 'Mullanpur',  'state' => 'Punjab',        'desc' => 'Chandigarh\'s New City — the biggest township project in North India'],
            'patiala'    => ['label' => 'Patiala',    'state' => 'Punjab',        'desc' => 'Punjab\'s second largest city with rich heritage and growing real estate'],
            'ambala'     => ['label' => 'Ambala',     'state' => 'Haryana',       'desc' => 'a strategic city on NH44 with growing residential development'],
        ];
    }

    /**
     * Sub-localities for each city (for internal linking).
     */
    private function getSubLocalities(): array
    {
        return [
            'zirakpur'   => ['VIP Road', 'Patiala Road', 'Airport Road', 'Dhakoli', 'Baltana', 'Lohgarh'],
            'mohali'     => ['Phase 5', 'Phase 7', 'Phase 10', 'Phase 11', 'Sector 70', 'Sector 82', 'Aerocity'],
            'chandigarh' => ['Sector 9', 'Sector 20', 'Sector 35', 'Sector 44', 'Manimajra', 'Panchkula Road'],
            'panchkula'  => ['Sector 20', 'Sector 25', 'MDC', 'Sector 9', 'Sector 12A'],
            'kharar'     => ['Sector 125', 'Kurali Road', 'Landran', 'Gharuan'],
            'derabassi'  => ['JLPL', 'IVY City', 'New Derabassi', 'Barwala Road'],
            'mullanpur'  => ['New Chandigarh', 'Ecoville', 'Omaxe City', 'Uni Homes'],
            'patiala'    => ['Model Town', 'Tripuri', 'Rajpura Road', 'Lehal'],
            'ambala'     => ['Ambala Cantt', 'Ambala City', 'Saha Industrial', 'HSIIDC'],
        ];
    }

    /**
     * Common FAQ entries per page type.
     */
    private function getFaqs(string $type, string $label): array
    {
        $appName = config('app.name');

        return [
            [
                'q' => "How much does a {$type} cost in {$label}?",
                'a' => "Prices vary by locality and builder. In {$label}, you can find {$type}s starting from ₹20 Lakh going up to several Crore. Use {$appName} filters to set your exact budget range.",
            ],
            [
                'q' => "Are properties in {$label} RERA registered?",
                'a' => "Many new projects in {$label} are RERA registered. Always check the RERA ID on {$appName} property listings or verify at the official Punjab/Haryana RERA portal.",
            ],
            [
                'q' => "How do I contact agents for {$type}s in {$label}?",
                'a' => "You can directly call or send an inquiry to verified agents listed on {$appName}. Each property listing shows the agent's contact details and you can schedule a free site visit.",
            ],
            [
                'q' => "What documents are needed to buy property in {$label}?",
                'a' => "You typically need Aadhar card, PAN card, income proof, and bank statements. For home loans, additional documents like salary slips and bank statements for 6 months are required.",
            ],
        ];
    }

    // ─────────────────────────────────────────────────────────────────────
    //  HELPERS
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Guess the state name for a city label (used for dynamic cities not in getCityMap).
     */
    private function guessState(string $city): string
    {
        $map = [
            'Punjab'            => ['Ludhiana', 'Amritsar', 'Jalandhar', 'Bathinda', 'Ropar', 'Fatehgarh',
                                    'Banur', 'Morinda', 'Rajpura', 'Kurali', 'Gharuan', 'Landran',
                                    'Mandi Gobindgarh', 'Khanna', 'Phagwara', 'Pathankot'],
            'Haryana'           => ['Hisar', 'Rohtak', 'Gurugram', 'Gurgaon', 'Faridabad', 'Karnal',
                                    'Sonipat', 'Panipat', 'Yamunanagar', 'Sirsa', 'Bhiwani'],
            'Himachal Pradesh'  => ['Solan', 'Baddi', 'Shimla', 'Dharamsala', 'Nalagarh', 'Barotiwala'],
            'Chandigarh UT'     => ['Chandigarh', 'Manimajra'],
        ];

        foreach ($map as $state => $cities) {
            foreach ($cities as $c) {
                if (stripos($city, $c) !== false) {
                    return $state;
                }
            }
        }

        return 'India';
    }

    /**
     * Resolve a URL slug into city + optional sub-locality.
     *
     * Resolution order (first match wins):
     *  1. Predefined city map  →  rich metadata + sub-localities
     *  2. Area + predefined city  (e.g. "vip-road-zirakpur")
     *  3. Dynamic city  →  DB lookup, any city with active properties
     *  4. Dynamic area + city  →  last 1–2 words as city, rest as area
     *
     * Returns null (→ 404) only if no active properties are found anywhere.
     */
    private function resolveCity(string $slug): ?array
    {
        $cities = $this->getCityMap();

        // ── 1. Direct predefined city ────────────────────────────────────
        if (isset($cities[$slug])) {
            return [
                'citySlug'  => $slug,
                'cityData'  => $cities[$slug],
                'cityLabel' => $cities[$slug]['label'],
                'areaLabel' => null,
            ];
        }

        // ── 2. Area + predefined city  (e.g. "vip-road-zirakpur") ────────
        foreach ($cities as $citySlug => $cityData) {
            if (str_ends_with($slug, '-' . $citySlug)) {
                $areaSlug  = substr($slug, 0, strlen($slug) - strlen($citySlug) - 1);
                $areaLabel = collect(explode('-', $areaSlug))
                    ->map(fn ($w) => ucfirst($w))
                    ->implode(' ');

                return [
                    'citySlug'  => $citySlug,
                    'cityData'  => $cityData,
                    'cityLabel' => $cityData['label'],
                    'areaLabel' => $areaLabel,
                ];
            }
        }

        // ── 3 & 4. Dynamic resolution — check DB ─────────────────────────
        // Try the full slug as a city first, then progressively split
        // (last 1 word, last 2 words) to find an area+city combo.
        $parts = explode('-', $slug);

        for ($cityWords = count($parts); $cityWords >= 1; $cityWords--) {
            $cityParts = array_slice($parts, -$cityWords);
            $areaParts = array_slice($parts, 0, count($parts) - $cityWords);

            // Stop if remaining "area" part is unreasonably long (> 4 words)
            if (count($areaParts) > 4) {
                break;
            }

            $cityLabel = collect($cityParts)
                ->map(fn ($w) => ucfirst($w))
                ->implode(' ');

            $hasProperties = Property::whereNotIn(
                'status', ['sold', 'rented', 'inactive', 'draft', 'expired', 'Sold', 'Rented']
            )->whereNotNull('price')
            ->where('price', '>', 0)
            ->where(function ($q) use ($cityLabel) {
                $q->where('city', 'like', "%{$cityLabel}%")
                  ->orWhere('locality', 'like', "%{$cityLabel}%");
            })->exists();

            if ($hasProperties) {
                $areaLabel = !empty($areaParts)
                    ? collect($areaParts)->map(fn ($w) => ucfirst($w))->implode(' ')
                    : null;

                return [
                    'citySlug'  => implode('-', $cityParts),
                    'cityData'  => [
                        'label' => $cityLabel,
                        'state' => $this->guessState($cityLabel),
                        'desc'  => 'a growing real estate destination',
                    ],
                    'cityLabel' => $cityLabel,
                    'areaLabel' => $areaLabel,
                ];
            }
        }

        return null; // No properties found anywhere → 404
    }

    /**
     * Convert budget amount + unit to price in rupees.
     */
    private function parseBudget(string $amount, string $unit): int
    {
        return $unit === 'cr'
            ? (int) $amount * 10000000
            : (int) $amount * 100000;
    }

    /**
     * Generic BHK × property-type handler (sale or rent).
     * Shared by house, villa, duplex, flat variants to avoid duplication.
     */
    private function bhkPropertyInCity(
        Request $request,
        string  $bhk,
        string  $city,
        string  $propertyType,
        string  $lookingFor,
        string  $typeLabel,
        string  $pageType = 'flats'
    ) {
        $bhkMap = ['1' => '1 BHK', '2' => '2 BHK', '3' => '3 BHK', '4' => '4 BHK', '5' => '5 BHK'];
        if (!isset($bhkMap[$bhk])) abort(404);

        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $bhkLabel = $bhkMap[$bhk];
        $intent   = $lookingFor === 'Rent' ? 'for Rent' : 'for Sale';
        $h1       = "{$bhkLabel} {$typeLabel} {$intent} in {$loc['cityLabel']}";

        return $this->renderLanding(
            $loc['citySlug'], $propertyType, $lookingFor, $bhkLabel,
            $h1, $pageType, $request, null, $loc['areaLabel']
        );
    }

    /**
     * Generic BHK × property-type × budget handler.
     */
    private function bhkPropertyUnderBudget(
        Request $request,
        string  $bhk,
        string  $city,
        string  $amount,
        string  $propertyType,
        string  $typeLabel,
        string  $pageType = 'flats'
    ) {
        $bhkMap = ['1' => '1 BHK', '2' => '2 BHK', '3' => '3 BHK', '4' => '4 BHK', '5' => '5 BHK'];
        if (!isset($bhkMap[$bhk])) abort(404);

        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $bhkLabel = $bhkMap[$bhk];
        $maxPrice = $this->parseBudget($amount, 'lakh');
        $h1       = "{$bhkLabel} {$typeLabel} in {$loc['cityLabel']} Under ₹{$amount} Lakh";

        return $this->renderLanding(
            $loc['citySlug'], $propertyType, 'Sale', $bhkLabel,
            $h1, $pageType, $request, $maxPrice
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    //  SHARED HANDLER
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Build query, load data and render the shared seo-landing view.
     *
     * @param string      $citySlug      Validated city slug from getCityMap()
     * @param string      $propertyType  e.g. "Flat", "Plot", "Villa", "" for any
     * @param string      $lookingFor    "Sale" | "Rent" | ""
     * @param string      $bhkType       e.g. "2 BHK" | ""
     * @param string      $h1            Page H1 heading
     * @param string      $pageType      Controls conditional sections in the view
     * @param Request     $request
     * @param int|null    $maxPrice      Upper price bound for budget pages
     * @param string|null $areaLabel     Sub-locality label for area+city pages
     * @param array       $extraFilters  Exact-match column filters e.g. ['gated_society' => true]
     */
    private function renderLanding(
        string  $citySlug,
        string  $propertyType,
        string  $lookingFor,
        string  $bhkType,
        string  $h1,
        string  $pageType,
        Request $request,
        ?int    $maxPrice     = null,
        ?string $areaLabel    = null,
        array   $extraFilters = []
    ) {
        $cities = $this->getCityMap();

        // For predefined cities use rich metadata; for dynamic cities reconstruct from slug.
        if (isset($cities[$citySlug])) {
            $city = $cities[$citySlug];
        } else {
            $cityLabel = collect(explode('-', $citySlug))
                ->map(fn ($w) => ucfirst($w))
                ->implode(' ');
            $city = [
                'label' => $cityLabel,
                'state' => $this->guessState($cityLabel),
                'desc'  => 'a growing real estate destination',
            ];
        }

        $cityLabel = $city['label'];

        // ── Build property query ──────────────────────────────────────────
        $query = Property::with(['images', 'dealer', 'builder'])
            ->whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired', 'Sold', 'Rented'])
            ->whereNotNull('price')
            ->where('price', '>', 0)
            ->where(function ($q) use ($cityLabel) {
                $q->where('city', 'like', "%{$cityLabel}%")
                  ->orWhere('locality', 'like', "%{$cityLabel}%")
                  ->orWhere('address', 'like', "%{$cityLabel}%");
            });

        // Sub-locality filter for area+city pages
        if ($areaLabel) {
            $query->where(function ($q) use ($areaLabel) {
                $q->where('locality', 'like', "%{$areaLabel}%")
                  ->orWhere('sub_locality', 'like', "%{$areaLabel}%")
                  ->orWhere('address', 'like', "%{$areaLabel}%");
            });
        }

        if ($propertyType) {
            // SEO-critical: allow common DB variants for flats.
            // In your DB, property_type can be values like Flat/Flats/Apartment/Apartments.
            if (in_array(strtolower(trim($propertyType)), ['flat', 'flats', 'apartment', 'apartments'])) {
                $query->where(function ($q) {
                    $q->where('property_type', 'like', '%Flat%')
                      ->orWhere('property_type', 'like', '%Flats%')
                      ->orWhere('property_type', 'like', '%Apartment%')
                      ->orWhere('property_type', 'like', '%Apartments%');
                });
            } else {
                $query->where('property_type', 'like', "%{$propertyType}%");
            }
        }

        if ($lookingFor) {
            if (in_array($lookingFor, ['Sale', 'Buy', 'buy', 'sale'])) {
                $query->whereIn('looking_for', ['Sale', 'Sell', 'Buy', 'sell', 'buy', 'sale']);
            } else {
                $query->where('looking_for', $lookingFor);
            }
        }

        if ($bhkType) {
            $query->where('bhk_type', $bhkType);
        }

        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        foreach ($extraFilters as $col => $val) {
            $query->where($col, $val);
        }

        $query->orderByRaw('is_boosted DESC, is_featured DESC, is_premium DESC, created_at DESC');
        $properties = $query->paginate(12)->withQueryString();

        // ── Multi-stage Fallback ──────────────────────────────────────────
        // If 0 properties found for exact criteria, broaden search step-by-step:
        // 1. BHK -> Property Type -> City
        if ($properties->total() === 0) {
            // FALLBACK 1: Broaden from specific BHK/Budget/Extras to just Property Type (e.g. "Flats in Zirakpur")
            if ($bhkType || $maxPrice || !empty($extraFilters)) {
                $query = Property::with(['images', 'dealer', 'builder'])
                    ->whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired', 'Sold', 'Rented'])
                    ->whereNotNull('price')->where('price', '>', 0)
                    ->where(function ($q) use ($cityLabel) {
                        $q->where('city', 'like', "%{$cityLabel}%")
                          ->orWhere('locality', 'like', "%{$cityLabel}%")
                          ->orWhere('address', 'like', "%{$cityLabel}%");
                    });

                if ($areaLabel) {
                    $query->where(function ($q) use ($areaLabel) {
                        $q->where('locality', 'like', "%{$areaLabel}%")
                          ->orWhere('sub_locality', 'like', "%{$areaLabel}%")
                          ->orWhere('address', 'like', "%{$areaLabel}%");
                    });
                }

                if ($propertyType) {
                    $query->where('property_type', 'like', "%{$propertyType}%");
                }

                if ($lookingFor) {
                    if (in_array($lookingFor, ['Sale', 'Buy', 'buy', 'sale'])) {
                        $query->whereIn('looking_for', ['Sale', 'Sell', 'Buy', 'sell', 'buy', 'sale']);
                    } else {
                        $query->where('looking_for', $lookingFor);
                    }
                }

                $query->orderByRaw('is_boosted DESC, is_featured DESC, is_premium DESC, created_at DESC');
                $properties = $query->paginate(12)->withQueryString();

                if ($properties->total() > 0) {
                    $h1 = ($propertyType ? "{$propertyType}s" : "Properties") . " for " . ($lookingFor ?: 'Sale') . " in " . ($areaLabel ? "{$areaLabel}, {$cityLabel}" : $cityLabel);
                }
            }

            // FALLBACK 2: Broaden to any property in the city/area (e.g. "Properties in Zirakpur")
            if ($properties->total() === 0) {
                $query = Property::with(['images', 'dealer', 'builder'])
                    ->whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired', 'Sold', 'Rented'])
                    ->whereNotNull('price')->where('price', '>', 0)
                    ->where(function ($q) use ($cityLabel) {
                        $q->where('city', 'like', "%{$cityLabel}%")
                          ->orWhere('locality', 'like', "%{$cityLabel}%")
                          ->orWhere('address', 'like', "%{$cityLabel}%");
                    });

                if ($lookingFor) {
                    if (in_array($lookingFor, ['Sale', 'Buy', 'buy', 'sale'])) {
                        $query->whereIn('looking_for', ['Sale', 'Sell', 'Buy', 'sell', 'buy', 'sale']);
                    } else {
                        $query->where('looking_for', $lookingFor);
                    }
                }

                $query->orderByRaw('is_boosted DESC, is_featured DESC, is_premium DESC, created_at DESC');
                $properties = $query->paginate(12)->withQueryString();

                // Update H1 to reflect general results for the city
                $h1 = "Available Properties in " . ($areaLabel ? "{$areaLabel}, {$cityLabel}" : $cityLabel);
            }
        }

        // ── Builder projects (new-projects / upcoming / best-projects) ────
        $newProjects = null;
        if (in_array($pageType, ['new-projects', 'upcoming', 'best-projects'])) {
            $statuses = $pageType === 'upcoming'
                ? ['Upcoming']
                : ['Upcoming', 'Under Construction', 'Ready to Move'];

            $newProjects = BuilderProject::whereIn('status', $statuses)
                ->where(function ($q) use ($cityLabel) {
                    $q->where('city', 'like', "%{$cityLabel}%")
                      ->orWhere('address', 'like', "%{$cityLabel}%");
                })
                ->with('builder')
                ->limit(6)
                ->get();
        }

        // ── Dealers / agents (buyer-intent pages) ─────────────────────────
        $pageDealers = null;
        if (in_array($pageType, ['dealers', 'agents'])) {
            $pageDealers = Dealer::where('status', 'active')
                ->where(function ($q) use ($cityLabel, $citySlug) {
                    $q->where('operating_cities', 'like', "%{$cityLabel}%")
                      ->orWhere('operating_cities', 'like', "%{$citySlug}%");
                })
                ->limit(12)
                ->get();
        }

        // ── SEO metadata ──────────────────────────────────────────────────
        $subLocalities = $this->getSubLocalities()[$citySlug] ?? [];
        $locationLabel = $areaLabel ? "{$areaLabel}, {$cityLabel}" : $cityLabel;
        $faqs          = $this->getFaqs($h1, $locationLabel);
        $allCities     = $cities;
        $totalCount    = $properties->total();

        $budgetLabel = $maxPrice
            ? ($maxPrice >= 10000000
                ? '₹' . number_format($maxPrice / 10000000, 1) . ' Crore'
                : '₹' . number_format($maxPrice / 100000) . ' Lakh')
            : null;

        $appName  = config('app.name');
        $seoTitle = "{$h1} | Verified Listings | {$appName}";
        $seoDesc  = "Find {$totalCount}+ verified {$h1} on {$appName}. Browse with photos, floor plans & agent contact. Best deals in {$locationLabel}, {$city['state']}.";

        return view('frontend.seo-landing', compact(
            'properties', 'newProjects', 'pageDealers',
            'cityLabel', 'citySlug', 'h1',
            'seoTitle', 'seoDesc', 'subLocalities', 'faqs', 'allCities',
            'totalCount', 'pageType', 'city', 'areaLabel', 'budgetLabel'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    //  EXISTING PAGE HANDLERS — updated to use resolveCity()
    //  Area+city combos are automatically handled: /flats-in-vip-road-zirakpur
    // ─────────────────────────────────────────────────────────────────────

    /** /flats-in-{city|area-city} */
    public function flatsInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = $loc['areaLabel']
            ? "Flats for Sale in {$loc['areaLabel']}, {$loc['cityLabel']}"
            : "Flats for Sale in {$loc['cityLabel']}";

        return $this->renderLanding(
            $loc['citySlug'], 'Flat', 'Sale', '', $h1, 'flats', $request, null, $loc['areaLabel']
        );
    }

    /** /rent-flats-in-{city|area-city} */
    public function rentFlatsInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = $loc['areaLabel']
            ? "Flats for Rent in {$loc['areaLabel']}, {$loc['cityLabel']}"
            : "Flats for Rent in {$loc['cityLabel']}";

        return $this->renderLanding(
            $loc['citySlug'], 'Flat', 'Rent', '', $h1, 'rent-flats', $request, null, $loc['areaLabel']
        );
    }

    /** /plots-in-{city|area-city} */
    public function plotsInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = $loc['areaLabel']
            ? "Plots for Sale in {$loc['areaLabel']}, {$loc['cityLabel']}"
            : "Plots for Sale in {$loc['cityLabel']}";

        return $this->renderLanding(
            $loc['citySlug'], 'Plot', 'Sale', '', $h1, 'plots', $request, null, $loc['areaLabel']
        );
    }

    /** /villas-in-{city|area-city} */
    public function villasInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = $loc['areaLabel']
            ? "Villas for Sale in {$loc['areaLabel']}, {$loc['cityLabel']}"
            : "Villas for Sale in {$loc['cityLabel']}";

        return $this->renderLanding(
            $loc['citySlug'], 'Villa', 'Sale', '', $h1, 'villas', $request, null, $loc['areaLabel']
        );
    }

    /** /new-projects-in-{city} */
    public function newProjectsInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        return $this->renderLanding(
            $loc['citySlug'], '', '', '', "New Projects in {$loc['cityLabel']}", 'new-projects', $request
        );
    }

    /** /ready-to-move-flats-in-{city} — custom query for OR possession logic */
    public function readyToMoveIn(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $cityLabel = $loc['cityLabel'];
        $citySlug  = $loc['citySlug'];
        $cityData  = $loc['cityData'];
        $cities    = $this->getCityMap();

        $query = Property::with(['images', 'dealer', 'builder'])
            ->whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired', 'Sold', 'Rented'])
            ->whereNotNull('price')->where('price', '>', 0)
            ->where(function ($q) use ($cityLabel) {
                $q->where('city', 'like', "%{$cityLabel}%")
                  ->orWhere('locality', 'like', "%{$cityLabel}%");
            })
            ->where('property_type', 'like', '%Flat%')
            ->where(function ($q) {
                $q->where('possession_status', 'Ready to Move')
                  ->orWhere('property_age', '>', 0);
            })
            ->orderByRaw('is_boosted DESC, is_featured DESC, created_at DESC');

        $properties    = $query->paginate(12)->withQueryString();
        $totalCount    = $properties->total();
        $h1            = "Ready to Move Flats in {$cityLabel}";
        $isFallback    = false;

        // ── Multi-stage Fallback for Ready to Move ────────────────────────
        if ($totalCount === 0) {
            // FALLBACK 1: Broaden to all Flats in the city (ignoring possession status)
            $query = Property::with(['images', 'dealer', 'builder'])
                ->whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired', 'Sold', 'Rented'])
                ->whereNotNull('price')->where('price', '>', 0)
                ->where(function ($q) use ($cityLabel) {
                    $q->where('city', 'like', "%{$cityLabel}%")
                      ->orWhere('locality', 'like', "%{$cityLabel}%");
                })
                ->where('property_type', 'like', '%Flat%')
                ->orderByRaw('is_boosted DESC, is_featured DESC, created_at DESC');

            $properties = $query->paginate(12)->withQueryString();
            $totalCount = $properties->total();

            if ($totalCount > 0) {
                $h1 = "Flats for Sale in {$cityLabel}";
                $isFallback = true;
            } else {
                // FALLBACK 2: Broaden to all Properties in the city
                $query = Property::with(['images', 'dealer', 'builder'])
                    ->whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired', 'Sold', 'Rented'])
                    ->whereNotNull('price')->where('price', '>', 0)
                    ->where(function ($q) use ($cityLabel) {
                        $q->where('city', 'like', "%{$cityLabel}%")
                          ->orWhere('locality', 'like', "%{$cityLabel}%");
                    })
                    ->orderByRaw('is_boosted DESC, is_featured DESC, created_at DESC');

                $properties = $query->paginate(12)->withQueryString();
                $totalCount = $properties->total();
                $h1         = "Available Properties in {$cityLabel}";
                $isFallback = true;
            }
        }

        $subLocalities = $this->getSubLocalities()[$citySlug] ?? [];
        $faqs          = $this->getFaqs($isFallback ? 'property' : 'ready-to-move flat', $cityLabel);
        $allCities     = $cities;
        $appName       = config('app.name');
        $seoTitle      = $isFallback ? "{$h1} | Verified Listings | {$appName}" : "{$h1} | Immediate Possession | {$appName}";
        $seoDesc       = $isFallback 
            ? "Find verified properties in {$cityLabel} on {$appName}. Browse photos and contact agents directly."
            : "Browse {$totalCount} ready-to-move flats in {$cityLabel}. Immediate possession, no wait time. Verified listings with photos and owner contact on {$appName}.";
        $pageType      = 'ready-to-move';
        $newProjects   = null;
        $pageDealers   = null;
        $areaLabel     = null;
        $budgetLabel   = null;

        return view('frontend.seo-landing', compact(
            'properties', 'newProjects', 'pageDealers', 'cityLabel', 'citySlug', 'h1',
            'seoTitle', 'seoDesc', 'subLocalities', 'faqs', 'allCities',
            'totalCount', 'pageType', 'areaLabel', 'budgetLabel'
        ) + ['city' => $cityData]);
    }

    /** /{bhk}bhk-flats-in-{city|area-city} */
    public function bhkFlatsInCity(Request $request, string $bhk, string $city)
    {
        $bhkMap = ['1' => '1 BHK', '2' => '2 BHK', '3' => '3 BHK', '4' => '4 BHK', '5' => '5 BHK'];
        if (!isset($bhkMap[$bhk])) abort(404);

        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $bhkLabel = $bhkMap[$bhk];
        $h1 = $loc['areaLabel']
            ? "{$bhkLabel} Flats for Sale in {$loc['areaLabel']}, {$loc['cityLabel']}"
            : "{$bhkLabel} Flats for Sale in {$loc['cityLabel']}";

        return $this->renderLanding(
            $loc['citySlug'], 'Flat', 'Sale', $bhkLabel, $h1, 'bhk-flats', $request, null, $loc['areaLabel']
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    //  BUDGET-BASED PAGES
    // ─────────────────────────────────────────────────────────────────────

    /** /flats-in-{city}-under-{amount}-lakh */
    public function flatsUnderBudget(Request $request, string $city, string $amount)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $maxPrice = $this->parseBudget($amount, 'lakh');
        $h1 = "Flats in {$loc['cityLabel']} Under ₹{$amount} Lakh";
        return $this->renderLanding($loc['citySlug'], 'Flat', 'Sale', '', $h1, 'flats', $request, $maxPrice);
    }

    /** /plots-in-{city}-under-{amount}-lakh */
    public function plotsUnderBudget(Request $request, string $city, string $amount)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $maxPrice = $this->parseBudget($amount, 'lakh');
        $h1 = "Plots in {$loc['cityLabel']} Under ₹{$amount} Lakh";
        return $this->renderLanding($loc['citySlug'], 'Plot', 'Sale', '', $h1, 'plots', $request, $maxPrice);
    }

    /** /villa-in-{city}-under-{amount}-cr */
    public function villaUnderBudget(Request $request, string $city, string $amount)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $maxPrice = $this->parseBudget($amount, 'cr');
        $h1 = "Villas in {$loc['cityLabel']} Under ₹{$amount} Crore";
        return $this->renderLanding($loc['citySlug'], 'Villa', 'Sale', '', $h1, 'villas', $request, $maxPrice);
    }

    /** /{bhk}bhk-flats-in-{city}-under-{amount}-lakh */
    public function bhkFlatsUnderBudget(Request $request, string $bhk, string $city, string $amount)
    {
        return $this->bhkPropertyUnderBudget($request, $bhk, $city, $amount, 'Flat', 'Flat', 'bhk-flats');
    }

    /** /{bhk}bhk-house-in-{city}-under-{amount}-lakh */
    public function bhkHouseUnderBudget(Request $request, string $bhk, string $city, string $amount)
    {
        return $this->bhkPropertyUnderBudget($request, $bhk, $city, $amount, 'Independent House', 'House', 'house');
    }

    /** /{bhk}bhk-villa-in-{city}-under-{amount}-lakh */
    public function bhkVillaUnderBudget(Request $request, string $bhk, string $city, string $amount)
    {
        return $this->bhkPropertyUnderBudget($request, $bhk, $city, $amount, 'Villa', 'Villa', 'villas');
    }

    // ─────────────────────────────────────────────────────────────────────
    //  HOUSE / VILLA TYPES
    // ─────────────────────────────────────────────────────────────────────

    /** /independent-house-for-sale-in-{city} */
    public function independentHouseInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "Independent House for Sale in {$loc['cityLabel']}";
        return $this->renderLanding($loc['citySlug'], 'Independent House', 'Sale', '', $h1, 'house', $request);
    }

    /** /duplex-house-for-sale-in-{city} */
    public function duplexHouseInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "Duplex House for Sale in {$loc['cityLabel']}";
        return $this->renderLanding($loc['citySlug'], 'Duplex', 'Sale', '', $h1, 'house', $request);
    }

    // ─── BHK × Property Type × Sale ──────────────────────────────────────

    /** /{bhk}bhk-house-for-sale-in-{city} */
    public function bhkHouseForSaleInCity(Request $request, string $bhk, string $city)
    {
        return $this->bhkPropertyInCity($request, $bhk, $city, 'Independent House', 'Sale', 'House', 'house');
    }

    /** /{bhk}bhk-villa-for-sale-in-{city} */
    public function bhkVillaForSaleInCity(Request $request, string $bhk, string $city)
    {
        return $this->bhkPropertyInCity($request, $bhk, $city, 'Villa', 'Sale', 'Villa', 'villas');
    }

    /** /{bhk}bhk-duplex-for-sale-in-{city} */
    public function bhkDuplexForSaleInCity(Request $request, string $bhk, string $city)
    {
        return $this->bhkPropertyInCity($request, $bhk, $city, 'Duplex', 'Sale', 'Duplex House', 'house');
    }

    // ─────────────────────────────────────────────────────────────────────
    //  COMMERCIAL PROPERTIES
    // ─────────────────────────────────────────────────────────────────────

    /** /commercial-property-in-{city} */
    public function commercialInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "Commercial Property for Sale in {$loc['cityLabel']}";
        return $this->renderLanding($loc['citySlug'], 'Commercial', 'Sale', '', $h1, 'commercial', $request);
    }

    /** /shops-for-sale-in-{city} */
    public function shopsInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "Shops for Sale in {$loc['cityLabel']}";
        return $this->renderLanding($loc['citySlug'], 'Shop', 'Sale', '', $h1, 'commercial', $request);
    }

    /** /office-space-in-{city} */
    public function officeSpaceInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "Office Space for Sale in {$loc['cityLabel']}";
        return $this->renderLanding($loc['citySlug'], 'Office', 'Sale', '', $h1, 'commercial', $request);
    }

    /** /sco-for-sale-in-{city} */
    public function scoInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "SCO for Sale in {$loc['cityLabel']}";
        return $this->renderLanding($loc['citySlug'], 'SCO', 'Sale', '', $h1, 'commercial', $request);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  BUYER INTENT PAGES
    // ─────────────────────────────────────────────────────────────────────

    /** /property-dealers-in-{city} */
    public function dealersInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "Property Dealers in {$loc['cityLabel']}";
        return $this->renderLanding($loc['citySlug'], '', '', '', $h1, 'dealers', $request);
    }

    /** /real-estate-agents-in-{city} */
    public function agentsInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "Real Estate Agents in {$loc['cityLabel']}";
        return $this->renderLanding($loc['citySlug'], '', '', '', $h1, 'agents', $request);
    }

    /** /upcoming-projects-in-{city} */
    public function upcomingProjectsInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "Upcoming Projects in {$loc['cityLabel']}";
        return $this->renderLanding($loc['citySlug'], '', '', '', $h1, 'upcoming', $request);
    }

    /** /rera-approved-projects-in-{city} */
    public function reraProjectsInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "RERA Approved Projects in {$loc['cityLabel']}";
        return $this->renderLanding(
            $loc['citySlug'], '', 'Sale', '', $h1, 'rera', $request,
            null, null, ['rera_verified' => true]
        );
    }

    /** /investment-property-in-{city} */
    public function investmentPropertyInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "Investment Property in {$loc['cityLabel']}";
        return $this->renderLanding($loc['citySlug'], '', 'Sale', '', $h1, 'investment', $request);
    }

    /** /best-residential-projects-in-{city} */
    public function bestResidentialProjectsInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "Best Residential Projects in {$loc['cityLabel']}";
        return $this->renderLanding($loc['citySlug'], '', '', '', $h1, 'best-projects', $request);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  SPECIAL FILTER PAGES
    // ─────────────────────────────────────────────────────────────────────

    /** /gated-society-flats-in-{city} */
    public function gatedSocietyFlatsInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "Gated Society Flats in {$loc['cityLabel']}";
        return $this->renderLanding(
            $loc['citySlug'], 'Flat', 'Sale', '', $h1, 'flats', $request,
            null, null, ['gated_society' => true]
        );
    }

    /** /luxury-flats-in-{city} */
    public function luxuryFlatsInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "Luxury Flats for Sale in {$loc['cityLabel']}";
        return $this->renderLanding(
            $loc['citySlug'], 'Flat', 'Sale', '', $h1, 'flats', $request,
            null, null, ['is_premium' => true]
        );
    }

    /** /affordable-flats-in-{city} (under ₹50 Lakh) */
    public function affordableFlatsInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "Affordable Flats for Sale in {$loc['cityLabel']}";
        return $this->renderLanding(
            $loc['citySlug'], 'Flat', 'Sale', '', $h1, 'flats', $request, 5000000
        );
    }

    /** /resale-flats-in-{city} */
    public function resaleFlatsInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "Resale Flats for Sale in {$loc['cityLabel']}";
        return $this->renderLanding(
            $loc['citySlug'], 'Flat', 'Sale', '', $h1, 'flats', $request,
            null, null, ['listing_type' => 'Resale']
        );
    }

    /** /furnished-flats-in-{city} */
    public function furnishedFlatsInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "Furnished Flats for Sale in {$loc['cityLabel']}";
        return $this->renderLanding(
            $loc['citySlug'], 'Flat', 'Sale', '', $h1, 'flats', $request,
            null, null, ['furnishing' => 'Furnished']
        );
    }

    /** /apartments-in-{city} */
    public function apartmentsInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "Apartments for Sale in {$loc['cityLabel']}";
        return $this->renderLanding(
            $loc['citySlug'], 'Flat', 'Sale', '', $h1, 'flats', $request
        );
    }

    /** /property-listings-in-{city} */
    public function propertyListingsInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "{$loc['cityLabel']} Property Listings";
        return $this->renderLanding($loc['citySlug'], '', 'Sale', '', $h1, 'flats', $request);
    }

    /** /{city}-real-estate */
    public function realEstateInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "{$loc['cityLabel']} Real Estate";
        return $this->renderLanding($loc['citySlug'], '', 'Sale', '', $h1, 'flats', $request);
    }

    /** /flats-in-{city}-with-loan-facility */
    public function loanFacilityFlatsInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "Flats for Sale in {$loc['cityLabel']} with Loan Facility";
        return $this->renderLanding($loc['citySlug'], 'Flat', 'Sale', '', $h1, 'flats', $request);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  EXTENDED RENTAL PAGES
    // ─────────────────────────────────────────────────────────────────────

    /** /house-for-rent-in-{city} */
    public function houseForRentInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "House for Rent in {$loc['cityLabel']}";
        return $this->renderLanding(
            $loc['citySlug'], 'Independent House', 'Rent', '', $h1, 'rent-flats', $request
        );
    }

    /** /{bhk}bhk-flat-for-rent-in-{city} */
    public function bhkFlatForRentInCity(Request $request, string $bhk, string $city)
    {
        return $this->bhkPropertyInCity($request, $bhk, $city, 'Flat', 'Rent', 'Flat', 'rent-flats');
    }

    /** /{bhk}bhk-house-for-rent-in-{city} */
    public function bhkHouseForRentInCity(Request $request, string $bhk, string $city)
    {
        return $this->bhkPropertyInCity($request, $bhk, $city, 'Independent House', 'Rent', 'House', 'rent-flats');
    }

    /** /{bhk}bhk-villa-for-rent-in-{city} */
    public function bhkVillaForRentInCity(Request $request, string $bhk, string $city)
    {
        return $this->bhkPropertyInCity($request, $bhk, $city, 'Villa', 'Rent', 'Villa', 'rent-flats');
    }

    /** /commercial-shop-for-rent-in-{city} */
    public function shopForRentInCity(Request $request, string $city)
    {
        $loc = $this->resolveCity($city);
        if (!$loc) abort(404);

        $h1 = "Commercial Shop for Rent in {$loc['cityLabel']}";
        return $this->renderLanding($loc['citySlug'], 'Shop', 'Rent', '', $h1, 'commercial', $request);
    }
}
