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

        // SEO tuning for high-intent Zirakpur pages in the footer.
        // Targets:
        // - /flats-in-zirakpur ("flats for sale in zirakpur", "apartments for sale in zirakpur", "buy flat in zirakpur")
        // - /2bhk-flats-in-zirakpur, /3bhk-flats-in-zirakpur, /4bhk-flats-in-zirakpur
        // - /ready-to-move-flats-zirakpur
        $citySlugLower = strtolower($citySlug);

        if ($citySlugLower === 'zirakpur' && $pageType === 'flats' && !$bhkType) {
            $seoTitle = "Flats for Sale in Zirakpur | Verified Listings | {$appName}";
            $seoDesc  = "Find flats for sale in Zirakpur, Punjab. Browse verified listings with photos, floor plans & direct agent contact on {$appName}. Best deals in Zirakpur.";
        }
        // Zirakpur property listings + real estate (footer anchors)
        else if ($citySlugLower === 'zirakpur' && $pageType === 'flats' && $propertyType === '' && !$lookingFor && !$bhkType) {
            $seoTitle = "Zirakpur Property Listings | Verified Listings | {$appName}";
            $seoDesc  = "Browse verified property listings in Zirakpur on {$appName}. Find flats for sale, apartments, 2/3/4 BHK options and connect with trusted agents.";
        }
        // Mohali / Chandigarh listing pages for footer SEO
        else if ($citySlugLower === 'mohali' && $pageType === 'flats' && $propertyType === '' && !$lookingFor && !$bhkType) {
            $seoTitle = "Flats for Sale in Mohali | Verified Listings | {$appName}";
            $seoDesc  = "Find flats for sale in Mohali, Punjab. Browse verified listings with photos, floor plans & direct agent contact on {$appName}. Best deals in Mohali.";
        }
        else if ($citySlugLower === 'chandigarh' && $pageType === 'flats' && $propertyType === '' && !$lookingFor && !$bhkType) {
            $seoTitle = "Flats for Sale in Chandigarh | Verified Listings | {$appName}";
            $seoDesc  = "Find flats for sale in Chandigarh, UT Chandigarh. Browse verified listings with photos, floor plans & direct agent contact on {$appName}. Best deals in Chandigarh.";
        }
        // Panchkula listing pages for footer SEO
        else if ($citySlugLower === 'panchkula' && $pageType === 'flats' && $propertyType === '' && !$lookingFor && !$bhkType) {
            $seoTitle = "Property for Sale in Panchkula | Verified Listings | {$appName}";
            $seoDesc  = "Find verified property listings for sale in Panchkula on {$appName}. Browse flats in Panchkula and connect with trusted agents.";
        }

        // Panchkula (new projects) — footer: /new-projects-in-panchkula
        else if ($citySlugLower === 'panchkula' && $pageType === 'new-projects' && $propertyType === '' && !$lookingFor && !$bhkType) {
            $seoTitle = "New Projects in Panchkula | Verified Listings | {$appName}";
            $seoDesc  = "Browse verified new projects in Panchkula on {$appName}. Find RERA projects, launch updates, and connect with top builders.";
        }

        // Panchkula (affordable) — /affordable-flats-in-panchkula
        else if ($citySlugLower === 'panchkula' && $pageType === 'flats' && $propertyType === 'Flat' && $maxPrice) {
            $seoTitle = "Affordable Flats in Panchkula | Verified Listings | {$appName}";
            $seoDesc  = "Find affordable flats in Panchkula within your budget on {$appName}. Browse verified 2/3/4 BHK listings with photos and contact agents directly.";
        }

        // Panchkula (resale) — /resale-flats-in-panchkula
        else if ($citySlugLower === 'panchkula' && $pageType === 'flats' && $propertyType === 'Flat' && isset($extraFilters['listing_type']) && $extraFilters['listing_type'] === 'Resale') {
            $seoTitle = "Resale Flats in Panchkula | Verified Listings | {$appName}";
            $seoDesc  = "Find resale flats in Panchkula with verified listings on {$appName}. Compare 2/3/4 BHK options and contact trusted owners/agents.";
        }

        // Panchkula (RTM) — /ready-to-move-flats-panchkula
        else if ($citySlugLower === 'panchkula' && $pageType === 'ready-to-move') {
            $seoTitle = "Ready to Move Flats in Panchkula | Verified Listings | {$appName}";
            $seoDesc  = "Browse verified ready to move flats in Panchkula on {$appName}. Immediate possession options with photos and direct agent contact.";
        }

        // Panchkula BHK pages — /2bhk-flats-in-panchkula and /3bhk-flats-in-panchkula
        else if ($pageType === 'bhk-flats' && $citySlugLower === 'panchkula' && $bhkType) {
            $bhkNum = trim(str_replace('BHK', '', $bhkType));
            $bhkNum = preg_replace('/[^0-9]/', '', $bhkNum);
            $bhkNum = $bhkNum ?: $bhkType;
            $seoTitle = "{$bhkType} Flats for Sale in Panchkula | Verified Listings | {$appName}";
            $seoDesc  = "Find verified {$bhkNum} BHK flats for sale in Panchkula. Browse 2/3/4 BHK listings with photos, floor plans and direct agent contact on {$appName}. Best deals in Panchkula.";
        }

        // Mullanpur listing pages for footer SEO
        else if ($citySlugLower === 'mullanpur' && $pageType === 'flats' && $propertyType === '' && !$lookingFor && !$bhkType) {
            $seoTitle = "Property for Sale in Mullanpur | New Chandigarh | {$appName}";
            $seoDesc  = "Browse verified property listings in Mullanpur, New Chandigarh. Find flats for sale, plots, villas and connect with trusted agents on {$appName}.";
        }

        // Mullanpur (flats specifically)
        else if ($citySlugLower === 'mullanpur' && $pageType === 'flats' && $propertyType === 'Flat' && !$lookingFor && !$bhkType && !$maxPrice) {
             $seoTitle = "Flats for Sale in Mullanpur | New Chandigarh | {$appName}";
             $seoDesc  = "Find verified flats for sale in Mullanpur, New Chandigarh. Browse 2/3/4 BHK apartments with photos, floor plans & direct agent contact on {$appName}.";
        }

        // Mullanpur (resale)
        else if ($citySlugLower === 'mullanpur' && $pageType === 'flats' && $propertyType === 'Flat' && isset($extraFilters['listing_type']) && $extraFilters['listing_type'] === 'Resale') {
            $seoTitle = "Resale Flats in Mullanpur | Verified Listings | {$appName}";
            $seoDesc  = "Find verified resale flats in Mullanpur, New Chandigarh. Compare 2/3/4 BHK options from owners and trusted agents on {$appName}.";
        }

        // Mullanpur (RTM)
        else if ($citySlugLower === 'mullanpur' && $pageType === 'ready-to-move') {
            $seoTitle = "Ready to Move Flats in Mullanpur | New Chandigarh | {$appName}";
            $seoDesc  = "Find verified ready to move flats in Mullanpur, New Chandigarh. Browse immediate possession apartments with photos and direct agent contact on {$appName}.";
        }

        // Mullanpur BHK pages — /2bhk-flats-in-mullanpur and /3bhk-flats-in-mullanpur
        else if ($pageType === 'bhk-flats' && $citySlugLower === 'mullanpur' && $bhkType) {
            $bhkNum = trim(str_replace('BHK', '', $bhkType));
            $bhkNum = preg_replace('/[^0-9]/', '', $bhkNum);
            $bhkNum = $bhkNum ?: $bhkType;
            $seoTitle = "{$bhkType} Flats in Mullanpur | New Chandigarh | {$appName}";
            $seoDesc  = "Find verified {$bhkNum} BHK flats in Mullanpur, New Chandigarh. Browse 2/3/4 BHK listings with photos, floor plans and direct agent contact on {$appName}.";
        }

        // Chandigarh - property for sale landing (footer: properties/in/chandigarh)
        else if ($citySlugLower === 'chandigarh' && $pageType === 'flats' && $propertyType === '' && !$lookingFor && !$bhkType) {
            $seoTitle = "Property for Sale in Chandigarh | Verified Listings | {$appName}";
            $seoDesc  = "Find verified property listings for sale in Chandigarh on {$appName}. Browse flats in Chandigarh and connect with trusted agents.";
        }

        // Mullanpur (new projects)
        else if ($citySlugLower === 'mullanpur' && $pageType === 'new-projects') {
            $seoTitle = "New Projects & New Flats in Mullanpur | Latest Launches | {$appName}";
            $seoDesc  = "Browse verified new projects and new flats in Mullanpur, New Chandigarh. Find RERA projects, latest launch updates, and connect with trusted builders.";
        }

        // Mullanpur (affordable)
        else if ($citySlugLower === 'mullanpur' && $pageType === 'flats' && $propertyType === 'Flat' && $maxPrice) {
            $seoTitle = "Affordable Flats in Mullanpur | Budget Apartments | {$appName}";
            $seoDesc  = "Find affordable flats in Mullanpur, New Chandigarh within your budget. Browse verified budget-friendly 2/3 BHK listings with photos on {$appName}.";
        }


        // Kharar BHK pages
        else if ($pageType === 'bhk-flats' && $citySlugLower === 'kharar' && $bhkType) {
            $bhkNum = trim(str_replace('BHK', '', $bhkType));
            $bhkNum = preg_replace('/[^0-9]/', '', $bhkNum);
            $bhkNum = $bhkNum ?: $bhkType;
            $seoTitle = "{$bhkType} Flats for Sale in Kharar | Verified Listings | {$appName}";
            $seoDesc  = "Find verified {$bhkNum} BHK flats for sale in Kharar. Browse 2/3/4 BHK listings with photos, floor plans and direct agent contact on {$appName}. Best deals in Kharar.";
        }

        // Kharar listing pages (flats)
        else if ($citySlugLower === 'kharar' && $pageType === 'flats' && $propertyType === '' && !$lookingFor && !$bhkType) {
            $seoTitle = "Property for Sale in Kharar | Verified Listings | {$appName}";
            $seoDesc  = "Find verified property listings for sale in Kharar on {$appName}. Browse flats in Kharar and connect with trusted agents.";
        }

        // Kharar (new projects)
        else if ($citySlugLower === 'kharar' && $pageType === 'new-projects') {
            $seoTitle = "New Projects & New Flats in Kharar | Latest Launches | {$appName}";
            $seoDesc  = "Browse verified new projects and new flats in Kharar. Find latest launch updates, RERA projects, and connect with top builders in Kharar.";
        }

        // Kharar (affordable)
        else if ($citySlugLower === 'kharar' && $pageType === 'flats' && $propertyType === 'Flat' && $maxPrice) {
            $seoTitle = "Affordable Flats in Kharar | Budget Apartments | {$appName}";
            $seoDesc  = "Find affordable flats in Kharar within your budget. Browse verified budget-friendly 2/3/4 BHK listings with photos and contact agents directly on {$appName}.";
        }

        // Kharar (resale)
        else if ($citySlugLower === 'kharar' && $pageType === 'flats' && $propertyType === 'Flat' && isset($extraFilters['listing_type']) && $extraFilters['listing_type'] === 'Resale') {
            $seoTitle = "Resale Flats in Kharar | Verified Listings | {$appName}";
            $seoDesc  = "Find verified resale flats in Kharar. Compare 2/3/4 BHK options from owners and trusted agents on {$appName} for the best resale deals in Kharar.";
        }

        // Kharar (RTM)
        else if ($citySlugLower === 'kharar' && $pageType === 'ready-to-move') {
            $seoTitle = "Ready to Move Flats in Kharar | Verified Listings | {$appName}";
            $seoDesc  = "Browse verified ready to move flats in Kharar on {$appName}. Immediate possession options with photos and direct agent contact.";
        }

        // BHK landing pages (SEO-critical): tune meta description to match the exact search intent.


        // Targets: /2bhk-flats-in-zirakpur, /3bhk-flats-in-zirakpur, /4bhk-flats-in-zirakpur
        else if ($pageType === 'bhk-flats' && $citySlugLower === 'zirakpur' && $bhkType) {


            // $bhkType is like "2 BHK".
            $bhkNum = trim(str_replace('BHK', '', $bhkType));
            $bhkNum = preg_replace('/[^0-9]/', '', $bhkNum);
            $bhkNum = $bhkNum ?: $bhkType;

            $seoTitle = "{$bhkType} Flats for Sale in Zirakpur | Verified Listings | {$appName}";
            $seoDesc  = "Find verified {$bhkNum} BHK flats for sale in Zirakpur. Browse 2/3/4 BHK listings with photos, floor plans and direct agent contact on {$appName}. Best deals in Zirakpur, Punjab.";
        } else {
            $seoTitle = "{$h1} | Verified Listings | {$appName}";
            $seoDesc  = "Find {$totalCount}+ verified {$h1} on {$appName}. Browse with photos, floor plans & agent contact. Best deals in {$locationLabel}, {$city['state']}.";
        }

        // Zirakpur ready-to-move handled by readyToMoveIn() in this controller.

        // DERABASSI (footer links + common city pages)
        if ($citySlugLower === 'derabassi') {
            // ready to move
            if ($pageType === 'ready-to-move') {
                $seoTitle = "Ready to Move Flats in Derabassi | Immediate Possession | {$appName}";
                $seoDesc  = "Find verified ready to move flats in Derabassi. Browse immediate possession apartments with photos, floor plans and direct agent contact on {$appName}.";
            }
            // new projects
            else if ($pageType === 'new-projects') {
                $seoTitle = "New Projects & New Flats in Derabassi | Latest Launches | {$appName}";
                $seoDesc  = "Browse verified new projects and new flats in Derabassi. Find RERA projects, latest launch updates, and connect with top builders in Derabassi.";
            }
            // property for sale (properties/in/derabassi style)
            else if ($pageType === 'flats' && $propertyType === '' && !$lookingFor && !$bhkType) {
                $seoTitle = "Property for Sale in Derabassi | Verified Listings | {$appName}";
                $seoDesc  = "Find property for sale in Derabassi on {$appName}. Browse verified listings of flats, plots, and villas with direct agent contact in Derabassi.";
            }
            // affordable
            else if ($pageType === 'flats' && $propertyType === 'Flat' && $maxPrice) {
                $seoTitle = "Affordable Flats in Derabassi | Budget Apartments | {$appName}";
                $seoDesc  = "Find affordable flats in Derabassi within your budget. Browse verified budget-friendly 2/3 BHK listings with photos and contact agents directly on {$appName}.";
            }
            // resale
            else if ($pageType === 'flats' && $propertyType === 'Flat' && isset($extraFilters['listing_type']) && $extraFilters['listing_type'] === 'Resale') {
                $seoTitle = "Resale Flats in Derabassi | Verified Listings | {$appName}";
                $seoDesc  = "Find verified resale flats in Derabassi. Compare 2/3/4 BHK options from owners and trusted agents on {$appName} for the best resale deals in Derabassi.";
            }
            // BHK
            else if ($pageType === 'bhk-flats' && $bhkType) {
                $bhkNum = trim(str_replace('BHK', '', $bhkType));
                $bhkNum = preg_replace('/[^0-9]/', '', $bhkNum);
                $bhkNum = $bhkNum ?: $bhkType;
                $seoTitle = "{$bhkType} Flats in Derabassi | Verified Listings | {$appName}";
                $seoDesc  = "Find verified {$bhkNum} BHK flats for sale in Derabassi. Browse listings with photos, floor plans and direct agent contact on {$appName}.";
            }
        }

        // Budget/Filter pages are already handled by renderLanding(), but add explicit Zirakpur long-tail intent.
        if ($citySlugLower === 'zirakpur' && $pageType === 'bhk-flats' && $bhkType) {
            // /2bhk-flats-in-zirakpur-under-50-lakh
            if (!empty($maxPrice) && $maxPrice <= 5000000 && str_starts_with($bhkType, '2')) {
                $bhkNum = trim(str_replace('BHK', '', $bhkType));
                $bhkNum = preg_replace('/[^0-9]/', '', $bhkNum);
                $bhkNum = $bhkNum ?: $bhkType;
                $seoTitle = "2 BHK Flats in Zirakpur Under 50 Lakh | Verified Listings | {$appName}";
                $seoDesc  = "Find verified 2 BHK flats in Zirakpur under 50 Lakh. Browse budget apartments with photos, floor plans and direct agent contact on {$appName}.";
            }
            // /3bhk-flats-in-zirakpur-under-80-lakh
            if (!empty($maxPrice) && $maxPrice <= 8000000 && str_starts_with($bhkType, '3')) {
                $bhkNum = trim(str_replace('BHK', '', $bhkType));
                $bhkNum = preg_replace('/[^0-9]/', '', $bhkNum);
                $bhkNum = $bhkNum ?: $bhkType;
                $seoTitle = "3 BHK Flats in Zirakpur Under 80 Lakh | Verified Listings | {$appName}";
                $seoDesc  = "Find verified 3 BHK flats in Zirakpur under 80 Lakh. Browse premium budget apartments with photos, floor plans and direct agent contact on {$appName}.";
            }
        }

        // Zirakpur (resale)
        else if ($citySlugLower === 'zirakpur' && $pageType === 'flats' && $propertyType === 'Flat' && isset($extraFilters['listing_type']) && $extraFilters['listing_type'] === 'Resale') {
            $seoTitle = "Resale Flats in Zirakpur | Verified Listings | {$appName}";
            $seoDesc  = "Find verified resale flats in Zirakpur. Compare 2/3/4 BHK options from owners and trusted agents on {$appName} for the best resale deals in Zirakpur.";
        }
        // Zirakpur (affordable)
        else if ($citySlugLower === 'zirakpur' && $pageType === 'flats' && $propertyType === 'Flat' && $maxPrice) {
            $seoTitle = "Affordable Flats in Zirakpur | Budget Apartments | {$appName}";
            $seoDesc  = "Find affordable flats in Zirakpur within your budget. Browse verified budget-friendly 2/3 BHK listings with photos and contact agents directly on {$appName}.";
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
        $seoDesc       = $isFallback
            ? "Find verified properties in {$cityLabel} on {$appName}. Browse photos and contact agents directly." // This description is generic, consider using initialH1 here too for stronger SEO
            : "Browse {$initialPropertiesCount} ready-to-move flats in {$cityLabel}. Immediate possession, no wait time. Verified listings with photos and owner contact on {$appName}."; // Use initialPropertiesCount for specific count
        $pageType      = 'ready-to-move';
        $newProjects   = null;
        $pageDealers   = null;
        $areaLabel     = null;
        $budgetLabel   = null;

        return view('frontend.seo-landing', compact(
            'properties', 'newProjects', 'pageDealers', 'cityLabel', 'citySlug', 'h1', // Pass the potentially modified H1 to the view
            'seoTitle', 'seoDesc', 'subLocalities', 'faqs', 'allCities',
            'totalCount', 'pageType', 'areaLabel', 'budgetLabel', 'initialPropertiesCount' // Pass initial count for potential display in view
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
