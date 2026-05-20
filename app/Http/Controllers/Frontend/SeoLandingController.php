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
            'zirakpur'   => ['VIP Road', 'Patiala Road', 'Airport Road', 'Dhakoli', 'Baltana', 'Lohgarh', 'Peer Muchalla'],
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
     * Builds the base property query with common filters.
     *
     * @param string      $cityLabel
     * @param string|null $propertyType
     * @param string|null $lookingFor
     * @param string|null $bhkType
     * @param int|null    $maxPrice
     * @param string|null $areaLabel
     * @param array       $extraFilters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildPropertyQuery(
        string $cityLabel,
        ?string $propertyType,
        ?string $lookingFor,
        ?string $bhkType,
        ?int $maxPrice,
        ?string $areaLabel,
        array $extraFilters
    ): \Illuminate\Database\Eloquent\Builder {
        $query = Property::with(['images', 'dealer', 'builder'])
            ->whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired', 'Sold', 'Rented'])
            ->whereNotNull('price')
            ->where('price', '>', 0)
            ->where(function ($q) use ($cityLabel, $areaLabel) {
                $q->where('city', 'like', "%{$cityLabel}%")
                  ->orWhere('locality', 'like', "%{$cityLabel}%")
                  ->orWhere('address', 'like', "%{$cityLabel}%");

                if ($areaLabel) {
                    $q->where(function ($subQ) use ($areaLabel) {
                        $subQ->where('locality', 'like', "%{$areaLabel}%")
                             ->orWhere('sub_locality', 'like', "%{$areaLabel}%")
                             ->orWhere('address', 'like', "%{$areaLabel}%");
                    });
                }
            });

        if ($propertyType) {
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

        if ($bhkType) $query->where('bhk_type', $bhkType);
        if ($maxPrice) $query->where('price', '<=', $maxPrice);
        foreach ($extraFilters as $col => $val) $query->where($col, $val);

        return $query;
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
        string  $h1_param, // Renamed to avoid conflict with local $h1
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
        $originalMaxPrice = $maxPrice;

        // Local variables for current query state, allowing fallbacks to modify them
        $_propertyType = $propertyType;
        $_lookingFor = $lookingFor;
        $_bhkType = $bhkType;
        $_maxPrice = $maxPrice;
        $_areaLabel = $areaLabel;
        $_extraFilters = $extraFilters;
        $h1 = $h1_param; // Initialize local H1 with the passed parameter

        $isFallback = false;

        // Initial query attempt
        $properties = $this->buildPropertyQuery(
            $cityLabel, $_propertyType, $_lookingFor, $_bhkType, $_maxPrice, $_areaLabel, $_extraFilters
        )->orderByRaw('is_boosted DESC, is_featured DESC, is_premium DESC, created_at DESC')
         ->paginate(12)->withQueryString();

        // Fallback 0: PG to Rent
        if ($properties->total() === 0 && strtolower($_lookingFor) === 'pg') {
            $_lookingFor = 'Rent'; // Change lookingFor to Rent
            $h1 = str_ireplace('PG', 'Rent', $h1); // Update H1 to reflect the change
            $isFallback = true;

            // Re-run the query with the updated lookingFor
            $properties = $this->buildPropertyQuery(
                $cityLabel, $_propertyType, $_lookingFor, $_bhkType, $_maxPrice, $_areaLabel, $_extraFilters
            )->orderByRaw('is_boosted DESC, is_featured DESC, is_premium DESC, created_at DESC')
             ->paginate(12)->withQueryString();
        }
        // ── Multi-stage Fallback ──────────────────────────────────────────
        // If 0 properties found for exact criteria, broaden search step-by-step:
        // 1. BHK -> Property Type -> City
        if ($properties->total() === 0 && ($_bhkType || $_maxPrice || !empty($_extraFilters))) {
            // FALLBACK 1: Broaden from specific BHK/Budget/Extras to just Property Type (e.g. "Flats in Zirakpur")
            $_bhkType = null;
            $_maxPrice = null;
            $_extraFilters = [];
            $isFallback = true;

            $properties = $this->buildPropertyQuery(
                $cityLabel, $_propertyType, $_lookingFor, $_bhkType, $_maxPrice, $_areaLabel, $_extraFilters
            )->orderByRaw('is_boosted DESC, is_featured DESC, is_premium DESC, created_at DESC')
             ->paginate(12)->withQueryString();

            if ($properties->total() > 0) {
                $h1 = ($_propertyType ? "{$_propertyType}s" : "Properties") . " for " . ($_lookingFor ?: 'Sale') . " in " . ($_areaLabel ? "{$_areaLabel}, {$cityLabel}" : $cityLabel);
            }
        }

        // FALLBACK 2: Broaden to any property in the city/area (e.g. "Properties in Zirakpur")
        if ($properties->total() === 0) {
            $_propertyType = null;
            $_bhkType = null;
            $_maxPrice = null;
            $_extraFilters = [];
            $_areaLabel = null; // Also broaden area label
            $isFallback = true;

            $properties = $this->buildPropertyQuery(
                $cityLabel, $_propertyType, $_lookingFor, $_bhkType, $_maxPrice, $_areaLabel, $_extraFilters
            )->orderByRaw('is_boosted DESC, is_featured DESC, is_premium DESC, created_at DESC')
             ->paginate(12)->withQueryString();

            // Update H1 to reflect general results for the city
            $h1 = "Available Properties in " . ($_areaLabel ? "{$_areaLabel}, {$cityLabel}" : $cityLabel);
        }

        // Soft 404 Mitigation: If we had to fallback or have 0 results, tell Google not to index this specific URL
        $noindex = $isFallback || $properties->total() === 0;

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
            ? ($originalMaxPrice >= 10000000
                ? '₹' . number_format($originalMaxPrice / 10000000, 1) . ' Crore'
                : '₹' . number_format($maxPrice / 100000) . ' Lakh')
            : null;

        $appName  = config('app.name');

        // SEO tuning for high-intent Zirakpur pages in the footer.
        // Targets:
        // - /flats-in-zirakpur ("flats for sale in zirakpur", "apartments for sale in zirakpur", "buy flat in zirakpur")
        // - /2bhk-flats-in-zirakpur, /3bhk-flats-in-zirakpur, /4bhk-flats-in-zirakpur
        // - /ready-to-move-flats-zirakpur
        $citySlugLower = strtolower($citySlug);

        // ── 1. ZIRAKPUR SEO TUNING ────────────────────────────────────────
        if ($citySlugLower === 'zirakpur') {
            if ($pageType === 'flats' && !$bhkType && !$maxPrice) {
                $seoTitle = "Flats for Sale in Zirakpur | Verified Listings | {$appName}";
                $seoDesc  = "Find flats for sale in Zirakpur, Punjab. Browse verified listings with photos, floor plans & direct agent contact on {$appName}. Best deals in Zirakpur.";
            } 
            else if ($pageType === 'bhk-flats' && $bhkType) {
                $bhkNum = trim(str_replace('BHK', '', $bhkType));
                $bhkNum = preg_replace('/[^0-9]/', '', $bhkNum);
                $bhkNum = $bhkNum ?: $bhkType;

                // /2bhk-flats-in-zirakpur-under-50-lakh
                if ($maxPrice && $maxPrice <= 5000000 && $bhkNum == '2') {
                    $seoTitle = "2 BHK Flats in Zirakpur Under 50 Lakh | Verified Listings | {$appName}";
                    $seoDesc  = "Find verified 2 BHK flats in Zirakpur under 50 Lakh. Browse budget apartments with photos, floor plans and direct agent contact on {$appName}.";
                }
                // /3bhk-flats-in-zirakpur-under-80-lakh
                else if ($maxPrice && $maxPrice <= 8000000 && $bhkNum == '3') {
                    $seoTitle = "3 BHK Flats in Zirakpur Under 80 Lakh | Verified Listings | {$appName}";
                    $seoDesc  = "Find verified 3 BHK flats in Zirakpur under 80 Lakh. Browse premium budget apartments with photos, floor plans and direct agent contact on {$appName}.";
                }
                else {
                    switch ($bhkNum) {
                        case '1':
                            $seoTitle = "1 BHK Flats for Sale in Zirakpur | Affordable Apartments | {$appName}";
                            $seoDesc  = "Looking for affordable 1 BHK flats in Zirakpur? Browse verified listings with photos, floor plans and direct agent contact on {$appName}.";
                            break;
                        case '2':
                            $seoTitle = "2 BHK Flats for Sale in Zirakpur | Best Gated Societies | {$appName}";
                            $seoDesc  = "Find the best 2 BHK flats for sale in Zirakpur's top gated societies. Browse verified listings with real photos, amenities, and floor plans on {$appName}.";
                            break;
                        case '3':
                            $seoTitle = "3 BHK Flats for Sale in Zirakpur | Luxury & Spacious | {$appName}";
                            $seoDesc  = "Explore premium 3 BHK flats for sale in Zirakpur. Spacious layouts, world-class amenities, and verified listings with direct agent contact on {$appName}.";
                            break;
                        default:
                            $seoTitle = "{$bhkType} Flats for Sale in Zirakpur | Verified Listings | {$appName}";
                            $seoDesc  = "Find verified {$bhkNum} BHK flats for sale in Zirakpur. Browse {$bhkNum} BHK listings with photos, floor plans and direct agent contact on {$appName}.";
                    }
                }
            }
            else if ($pageType === 'ready-to-move') {
                $seoTitle = "Ready to Move Flats in Zirakpur | Immediate Possession | {$appName}";
                $seoDesc  = "Browse verified ready to move flats in Zirakpur on {$appName}. Immediate possession options with photos and direct agent contact. No wait time.";
            }
            else if ($pageType === 'new-projects') {
                $seoTitle = "New Projects & New Flats in Zirakpur | Latest Launches | {$appName}";
                $seoDesc  = "Browse verified new projects and new flats in Zirakpur. Find RERA projects, latest launch updates, and connect with top builders in Zirakpur.";
            }
            else if ($pageType === 'flats' && $propertyType === '' && !$lookingFor && !$bhkType) {
                $seoTitle = "Zirakpur Property Listings | Verified Listings | {$appName}";
                $seoDesc  = "Browse verified property listings in Zirakpur on {$appName}. Find flats for sale, apartments, 2/3/4 BHK options and connect with trusted agents.";
            }
        }

        // ── 2. MOHALI SEO TUNING ──────────────────────────────────────────
        else if ($citySlugLower === 'mohali') {
            if ($pageType === 'flats' && !$bhkType && !$maxPrice) {
                $seoTitle = "Flats for Sale in Mohali | Verified Listings | {$appName}";
                $seoDesc  = "Find flats for sale in Mohali, Punjab. Browse verified listings with photos, floor plans & direct agent contact on {$appName}. Best deals in Mohali.";
            }
            else if ($pageType === 'bhk-flats' && $bhkType) {
                $bhkNum = trim(str_replace('BHK', '', $bhkType));
                $bhkNum = preg_replace('/[^0-9]/', '', $bhkNum);
                $bhkNum = $bhkNum ?: $bhkType;

                switch ($bhkNum) {
                    case '2':
                        $seoTitle = "2 BHK Flats for Sale in Mohali | Top Gated Societies | {$appName}";
                        $seoDesc  = "Find the best 2 BHK flats for sale in Mohali's top gated societies. Browse verified listings with real photos, amenities, and floor plans on {$appName}.";
                        break;
                    case '3':
                        $seoTitle = "3 BHK Flats for Sale in Mohali | Luxury & Spacious Living | {$appName}";
                        $seoDesc  = "Explore premium 3 BHK flats for sale in Mohali. Spacious layouts, world-class amenities, and verified listings with direct agent contact on {$appName}.";
                        break;
                    default:
                        $seoTitle = "{$bhkType} Flats for Sale in Mohali | Verified Listings | {$appName}";
                        $seoDesc  = "Find verified {$bhkNum} BHK flats for sale in Mohali. Browse listings with photos, floor plans and direct agent contact on {$appName}.";
                }
            }
            else if ($pageType === 'ready-to-move') {
                $seoTitle = "Ready to Move Flats in Mohali | Immediate Possession | {$appName}";
                $seoDesc  = "Find verified ready to move flats in Mohali. Browse immediate possession apartments with photos, floor plans and direct agent contact on {$appName}.";
            }
            else if ($pageType === 'new-projects') {
                $seoTitle = "New Projects & New Flats in Mohali | Latest Launches | {$appName}";
                $seoDesc  = "Browse verified new projects and new flats in Mohali. Find RERA projects, latest launch updates, and connect with top builders in Mohali.";
            }
        }

        // ── 3. CHANDIGARH SEO TUNING ──────────────────────────────────────
        else if ($citySlugLower === 'chandigarh') {
            if ($pageType === 'flats' && !$bhkType && !$maxPrice) {
                $seoTitle = "Flats for Sale in Chandigarh | Verified Listings | {$appName}";
                $seoDesc  = "Find flats for sale in Chandigarh, UT Chandigarh. Browse verified listings with photos, floor plans & direct agent contact on {$appName}. Best deals in Chandigarh.";
            }
            else if ($pageType === 'bhk-flats' && $bhkType) {
                $bhkNum = trim(str_replace('BHK', '', $bhkType));
                $bhkNum = preg_replace('/[^0-9]/', '', $bhkNum);
                $bhkNum = $bhkNum ?: $bhkType;

                switch ($bhkNum) {
                    case '1':
                        $seoTitle = "1 BHK Flats for Sale in Chandigarh | Affordable Apartments | {$appName}";
                        $seoDesc  = "Looking for affordable 1 BHK flats in Chandigarh? Browse verified listings with photos, floor plans and direct agent contact on {$appName}.";
                        break;
                    case '2':
                        $seoTitle = "2 BHK Flats for Sale in Chandigarh | Best Residential Sectors | {$appName}";
                        $seoDesc  = "Find the best 2 BHK flats for sale in Chandigarh's top sectors. Browse verified listings with real photos, amenities, and floor plans on {$appName}.";
                        break;
                    case '3':
                        $seoTitle = "3 BHK Flats for Sale in Chandigarh | Luxury & Spacious Living | {$appName}";
                        $seoDesc  = "Explore premium 3 BHK flats for sale in Chandigarh. Spacious layouts, world-class amenities, and verified listings with direct agent contact on {$appName}.";
                        break;
                    default:
                        $seoTitle = "{$bhkType} Flats for Sale in Chandigarh | Verified Listings | {$appName}";
                        $seoDesc  = "Find verified {$bhkNum} BHK flats for sale in Chandigarh. Browse {$bhkNum} BHK listings with photos, floor plans and direct agent contact on {$appName}.";
                }
            }
            else if ($pageType === 'flats' && $propertyType === '' && !$lookingFor && !$bhkType) {
                $seoTitle = "Property for Sale in Chandigarh | Verified Listings | {$appName}";
                $seoDesc  = "Find verified property listings for sale in Chandigarh on {$appName}. Browse flats in Chandigarh and connect with trusted agents.";
            }
        }

        // ── 4. PANCHKULA SEO TUNING ───────────────────────────────────────
        else if ($citySlugLower === 'panchkula') {
            if ($pageType === 'flats' && !$bhkType && !$maxPrice) {
                $seoTitle = "Property for Sale in Panchkula | Verified Listings | {$appName}";
                $seoDesc  = "Find verified property listings for sale in Panchkula on {$appName}. Browse flats in Panchkula and connect with trusted agents.";
            }
            else if ($pageType === 'new-projects') {
                $seoTitle = "New Projects in Panchkula | Verified Listings | {$appName}";
                $seoDesc  = "Browse verified new projects in Panchkula on {$appName}. Find RERA projects, launch updates, and connect with top builders.";
            }
            else if ($pageType === 'ready-to-move') {
                $seoTitle = "Ready to Move Flats in Panchkula | Verified Listings | {$appName}";
                $seoDesc  = "Browse verified ready to move flats in Panchkula on {$appName}. Immediate possession options with photos and direct agent contact.";
            }
            else if ($pageType === 'bhk-flats' && $bhkType) {
                $bhkNum = trim(str_replace('BHK', '', $bhkType));
                $bhkNum = preg_replace('/[^0-9]/', '', $bhkNum);
                $bhkNum = $bhkNum ?: $bhkType;
                $seoTitle = "{$bhkType} Flats for Sale in Panchkula | Verified Listings | {$appName}";
                $seoDesc  = "Find verified {$bhkNum} BHK flats for sale in Panchkula. Browse listings with photos, floor plans and direct agent contact on {$appName}.";
            }
        }

        // ── 5. MULLANPUR SEO TUNING ───────────────────────────────────────
        else if ($citySlugLower === 'mullanpur') {
            if ($pageType === 'flats' && !$bhkType && !$maxPrice) {
                $seoTitle = "Property for Sale in Mullanpur | New Chandigarh | {$appName}";
                $seoDesc  = "Browse verified property listings in Mullanpur, New Chandigarh. Find flats for sale, plots, villas and connect with trusted agents on {$appName}.";
            }
            else if ($pageType === 'ready-to-move') {
                $seoTitle = "Ready to Move Flats in Mullanpur | New Chandigarh | {$appName}";
                $seoDesc  = "Find verified ready to move flats in Mullanpur, New Chandigarh. Browse immediate possession apartments with photos and direct agent contact on {$appName}.";
            }
            else if ($pageType === 'new-projects') {
                $seoTitle = "New Projects & New Flats in Mullanpur | Latest Launches | {$appName}";
                $seoDesc  = "Browse verified new projects and new flats in Mullanpur, New Chandigarh. Find RERA projects, latest launch updates, and connect with trusted builders.";
            }
        }

        // ── 6. KHARAR SEO TUNING ──────────────────────────────────────────
        else if ($citySlugLower === 'kharar') {
            if ($pageType === 'flats' && !$bhkType && !$maxPrice) {
                $seoTitle = "Flats for Sale in Kharar | Affordable Apartments | {$appName}";
                $seoDesc  = "Find the best flats for sale in Kharar, Punjab. Explore affordable residential options, gated societies and verified listings with photos on {$appName}.";
            }
            else if ($pageType === 'bhk-flats' && $bhkType) {
                $seoTitle = "{$bhkType} Flats for Sale in Kharar | Verified Listings | {$appName}";
                $seoDesc  = "Looking for {$bhkType} flats in Kharar? Browse verified listings with real photos and floor plans. Affordable 2/3 BHK options near Mohali.";
            }
            else if ($pageType === 'ready-to-move') {
                $seoTitle = "Ready to Move Flats in Kharar | Immediate Possession | {$appName}";
                $seoDesc  = "Browse verified ready to move flats in Kharar. Find immediate possession apartments with direct agent contact on {$appName}. No wait time.";
            }
        }

        // ── 7. DERABASSI SEO TUNING ───────────────────────────────────────
        else if ($citySlugLower === 'derabassi') {
            if ($pageType === 'flats' && !$bhkType && !$maxPrice) {
                $seoTitle = "Flats for Sale in Derabassi | Property in Derabassi | {$appName}";
                $seoDesc  = "Explore flats and apartments for sale in Derabassi, Punjab. Verified property listings with photos, location maps, and agent contact on {$appName}.";
            }
            else if ($pageType === 'bhk-flats' && $bhkType) {
                $seoTitle = "{$bhkType} Flats for Sale in Derabassi | Verified Listings | {$appName}";
                $seoDesc  = "Find verified {$bhkType} flats for sale in Derabassi. Explore budget-friendly residential projects with amenities and direct dealer contact.";
            }
            else if ($pageType === 'ready-to-move') {
                $seoTitle = "Ready to Move Flats in Derabassi | Immediate Possession | {$appName}";
                $seoDesc  = "Find verified ready to move flats in Derabassi. Browse immediate possession apartments with photos and floor plans on {$appName}.";
            }
        }

        // ── 8. PATIALA & AMBALA SEO TUNING ────────────────────────────────
        else if (in_array($citySlugLower, ['patiala', 'ambala'])) {
            $locName = ucfirst($citySlugLower);
            if ($pageType === 'flats' && !$bhkType && !$maxPrice) {
                $seoTitle = "Flats for Sale in {$locName} | Verified Listings | {$appName}";
                $seoDesc  = "Browse verified flats and property listings for sale in {$locName}. Find apartments, villas, and plots with photos and agent contact on {$appName}.";
            }
            else if ($pageType === 'bhk-flats' && $bhkType) {
                $seoTitle = "{$bhkType} Flats for Sale in {$locName} | Verified Listings | {$appName}";
                $seoDesc  = "Looking for {$bhkType} flats in {$locName}? Explore verified listings with floor plans and direct contact. Best real estate deals in {$locName}.";
            }
            else if ($pageType === 'ready-to-move') {
                $seoTitle = "Ready to Move Flats in {$locName} | Immediate Possession | {$appName}";
                $seoDesc  = "Find verified ready to move flats in {$locName}. Browse immediate possession apartments with photos and direct agent contact on {$appName}.";
            }
        }

        // ── 9. HYPERLOCAL BHK TUNING (Dhakoli, Peer Muchalla) ──────────────
        else if ($pageType === 'bhk-flats' && $bhkType && (
            in_array($citySlugLower, ['dhakoli', 'peer-muchalla', 'peer-mushalla']) ||
            in_array(strtolower($areaLabel ?? ''), ['dhakoli', 'peer muchalla', 'peer mushalla'])
        )) {
            $bhkNum = trim(str_replace('BHK', '', $bhkType));
            $bhkNum = preg_replace('/[^0-9]/', '', $bhkNum);
            $bhkNum = $bhkNum ?: $bhkType;
            $seoTitle = "{$bhkType} Flats for Sale in {$locationLabel} | Verified Listings | {$appName}";
            $seoDesc  = "Looking for {$bhkType} flats in {$locationLabel}? Browse verified listings with photos, floor plans and direct agent contact. Perfect residential options in Zirakpur belt.";
        }

        // ── 10. OTHER CITIES (Fallback) ────────────────────────────────────
        else {
            $seoTitle = "{$h1} | Verified Listings | {$appName}";
            $seoDesc  = "Find {$totalCount}+ verified {$h1} on {$appName}. Browse with photos, floor plans & agent contact. Best deals in {$locationLabel}, {$city['state']}.";
        }

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

        $initialH1 = "Ready to Move Flats in {$loc['cityLabel']}"; // Store the initial H1 for SEO
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
        $totalCount    = $properties->total(); // This is the count for the specific query
        $h1            = $initialH1; // The H1 for the view, initially the specific one
        $initialPropertiesCount = $totalCount;
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

            if ($totalCount > 0) { // If fallback 1 yields results
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
                $totalCount = $properties->total(); // Total count for fallback 2
                $h1         = "Available Properties in {$cityLabel}";
                $isFallback = true;
            }
        }

        $subLocalities = $this->getSubLocalities()[$citySlug] ?? [];
        $faqs          = $this->getFaqs($isFallback ? 'property' : 'ready-to-move flat', $cityLabel); // This logic for FAQs seems fine
        $allCities     = $cities;
        $appName       = config('app.name');
        $seoTitle      = $isFallback ? "{$initialH1} | Verified Listings | {$appName}" : "{$initialH1} | Immediate Possession | {$appName}"; // Use initialH1 for SEO title
        $noindex       = $isFallback || $totalCount === 0;
        $canonicalUrl  = url()->current();
        $seoDesc       = $isFallback
            ? "Find verified properties in {$cityLabel} on {$appName}. Browse photos and contact agents directly." // This description is generic, consider using initialH1 here too for stronger SEO
            : "Browse {$initialPropertiesCount} ready-to-move flats in {$cityLabel}. Immediate possession, no wait time. Verified listings with photos and owner contact on {$appName}."; // Use initialPropertiesCount for specific count
        $pageType      = 'ready-to-move';
        $newProjects   = null;
        $pageDealers   = null;
        $areaLabel     = null;
        $budgetLabel   = null;

        return view('frontend.seo-landing', compact(
            'properties', 'newProjects', 'pageDealers', 'cityLabel', 'citySlug', 'h1',
            'seoTitle', 'seoDesc', 'subLocalities', 'faqs', 'allCities',
            'totalCount', 'pageType', 'areaLabel', 'budgetLabel', 'initialPropertiesCount', 'noindex', 'canonicalUrl'
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
