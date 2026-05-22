<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertiesController extends Controller
{
    public function autocomplete(Request $request)
    {
        $term = $request->get('term', '');
        $results = Property::query()
            ->where('status', 'Available')
            ->where(function ($q) use ($term) {
                $q->where('city', 'like', "%{$term}%")
                  ->orWhere('locality', 'like', "%{$term}%")
                  ->orWhere('landmark', 'like', "%{$term}%")
                  ->orWhere('title', 'like', "%{$term}%")
                  ->orWhere('address', 'like', "%{$term}%");
            })
            ->limit(10)
            ->get(['city', 'locality', 'landmark', 'title', 'address']);
        $suggestions = [];
        foreach ($results as $row) {
            foreach (['city', 'locality', 'landmark', 'title', 'address'] as $field) {
                if (!empty($row[$field]) && stripos($row[$field], $term) !== false) {
                    $suggestions[] = $row[$field];
                }
            }
        }
        $suggestions = array_unique($suggestions);
        return response()->json(array_values($suggestions));
    }

    public function getLocationMap(): array
    {
        return [
            'dhakoli'          => ['label' => 'Dhakoli',           'lat' => 30.6400, 'lng' => 76.8190],
            'zirakpur'         => ['label' => 'Zirakpur',          'lat' => 30.6525, 'lng' => 76.8371],
            'peer-muchalla'    => ['label' => 'Peer Muchalla',     'lat' => 30.6530, 'lng' => 76.8290],
            'derabassi'        => ['label' => 'Derabassi',         'lat' => 30.5952, 'lng' => 76.8378],
            'panchkula'        => ['label' => 'Panchkula',         'lat' => 30.6942, 'lng' => 76.8606],
            'chandigarh'       => ['label' => 'Chandigarh',        'lat' => 30.7333, 'lng' => 76.7794],
            'mohali'           => ['label' => 'Mohali',            'lat' => 30.6967, 'lng' => 76.7356],
            'manimajra'        => ['label' => 'Manimajra',         'lat' => 30.7313, 'lng' => 76.8740],
            'banur'            => ['label' => 'Banur',             'lat' => 30.5664, 'lng' => 76.7200],
            'landran'          => ['label' => 'Landran',           'lat' => 30.7594, 'lng' => 76.7000],
            'mullanpur'        => ['label' => 'Mullanpur',         'lat' => 30.7831, 'lng' => 76.7109],
            'kharar'           => ['label' => 'Kharar',            'lat' => 30.7447, 'lng' => 76.6479],
            'gharuan'          => ['label' => 'Gharuan',           'lat' => 30.7083, 'lng' => 76.5128],
            'kurali'           => ['label' => 'Kurali',            'lat' => 30.8336, 'lng' => 76.6072],
            'morinda'          => ['label' => 'Morinda',           'lat' => 30.7883, 'lng' => 76.4897],
            'fatehgarh-sahib'  => ['label' => 'Fatehgarh Sahib',   'lat' => 30.6481, 'lng' => 76.3894],
            'pinjore'          => ['label' => 'Pinjore',           'lat' => 30.7987, 'lng' => 76.9153],
            'kalka'            => ['label' => 'Kalka',             'lat' => 30.8467, 'lng' => 76.9453],
            'solan'            => ['label' => 'Solan',             'lat' => 30.9097, 'lng' => 77.0993],
            'baddi'            => ['label' => 'Baddi',             'lat' => 30.9597, 'lng' => 76.7898],
            'barotiwala'       => ['label' => 'Barotiwala',        'lat' => 30.9467, 'lng' => 76.7878],
            'nalagarh'         => ['label' => 'Nalagarh',          'lat' => 31.0424, 'lng' => 76.7155],
            'rajpura'          => ['label' => 'Rajpura',           'lat' => 30.4831, 'lng' => 76.5917],
            'ambala'           => ['label' => 'Ambala',            'lat' => 30.3783, 'lng' => 76.7767],
            'ropar'            => ['label' => 'Ropar',             'lat' => 30.9641, 'lng' => 76.5311],
            'rupnagar'         => ['label' => 'Ropar / Rupnagar',  'lat' => 30.9641, 'lng' => 76.5311],
            'patiala'          => ['label' => 'Patiala',           'lat' => 30.3398, 'lng' => 76.3869],
        ];
    }

    public function locationSearch(Request $request, string $location)
    {
        $locations = $this->getLocationMap();
        $slug = strtolower(trim($location));

        if (!isset($locations[$slug])) {
            abort(404);
        }

        $loc      = $locations[$slug];
        $radius   = 10;
        $latDelta = $radius / 111.0;
        $lngDelta = $radius / 96.5;

        $minLat = $loc['lat'] - $latDelta;
        $maxLat = $loc['lat'] + $latDelta;
        $minLng = $loc['lng'] - $lngDelta;
        $maxLng = $loc['lng'] + $lngDelta;

        $query = Property::with(['images', 'dealer', 'builder'])
            ->paidAndValid()
            ->whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired', 'Sold', 'Rented'])
            ->whereNotNull('price')
            ->where('price', '>', 0)
            ->where(function ($q) use ($loc, $minLat, $maxLat, $minLng, $maxLng) {
                $q->where(function ($inner) use ($minLat, $maxLat, $minLng, $maxLng) {
                    $inner->whereNotNull('latitude')
                          ->whereNotNull('longitude')
                          ->whereBetween('latitude', [$minLat, $maxLat])
                          ->whereBetween('longitude', [$minLng, $maxLng]);
                })
                ->orWhere('city', 'like', '%' . $loc['label'] . '%')
                ->orWhere('locality', 'like', '%' . $loc['label'] . '%');
            });

        if ($request->filled('property_type')) $query->where('property_type', $request->property_type);
        if ($request->filled('looking_for')) {
            $lf = $request->looking_for;
            if (in_array($lf, ['Sale', 'Buy', 'buy', 'sale'])) {
                $query->whereIn('looking_for', ['Sale', 'Sell', 'Buy', 'sell', 'buy', 'sale']);
            } else {
                $query->where('looking_for', $lf);
            }
        }
        if ($request->filled('bhk_type')) $query->where('bhk_type', $request->bhk_type);
        if ($request->filled('min_price')) $query->where('price', '>=', $request->min_price);
        if ($request->filled('max_price')) $query->where('price', '<=', $request->max_price);
        if ($request->filled('min_area')) $query->where('area', '>=', $request->min_area);
        if ($request->filled('max_area')) $query->where('area', '<=', $request->max_area);
        if ($request->filled('bedrooms')) $query->where('bedrooms', '>=', $request->bedrooms);
        if ($request->filled('furnishing_status')) $query->where('furnishing_status', $request->furnishing_status);
        if ($request->filled('pet_friendly')) $query->where('pet_friendly', true);
        if ($request->filled('gated_society')) $query->where('gated_society', true);
        if ($request->filled('vastu_compliant')) $query->where('vastu_compliant', true);

        $sortBy = $request->get('sort_by', 'newest');
        switch ($sortBy) {
            case 'price_low':  $query->orderBy('price', 'asc'); break;
            case 'price_high': $query->orderBy('price', 'desc'); break;
            case 'area': $query->orderBy('area', 'desc'); break;
            default: $query->orderByRaw('is_boosted DESC, boosted_until DESC, is_featured DESC, is_premium DESC, created_at DESC');
        }

        $properties = $query->paginate(24)->withQueryString();
        $cities = Property::whereNotNull('city')->distinct()->pluck('city')->filter()->sort();
        $propertyTypes = Property::whereNotNull('property_type')->distinct()->pluck('property_type')->filter()->sort();
        $locationLabel = $loc['label'];
        $locationRadius = $radius;

        $seoTitle = "Properties in {$locationLabel} | Buy & Rent Flats, Villas & Plots | IndianestHub";
        $seoDescription = "Browse {$properties->total()} verified properties in {$locationLabel} and nearby areas within {$radius} km. Find flats, villas, plots for sale & rent. Connect with verified agents on IndianestHub.";
        $seoH1 = "Properties in {$locationLabel}";
        $seoIntro = "Looking for property in {$locationLabel}? IndianestHub lists {$properties->total()} verified properties within {$radius} km of {$locationLabel} — including flats, villas, plots and commercial spaces. Browse by BHK type, budget and property status to find your perfect match.";

        return view('frontend.properties', compact('properties', 'cities', 'propertyTypes', 'locationLabel', 'locationRadius', 'seoTitle', 'seoDescription', 'seoH1', 'seoIntro'));
    }

    public function index(Request $request)
    {
        // Normalize common query params (some upstream URLs may append extra data like dates)
        $normalizeParam = function ($value): ?string {
            if ($value === null) {
                return null;
            }
            $value = trim((string) $value);
            // Strip wrapping quotes
            $value = trim($value, "\"' ");
            return $value === '' ? null : $value;
        };

        // If we're on the base listing page (/properties) we should not apply
        // any SEO landing fallback that can override query-string searches.
        $isBasePropertiesPage = $request->path() === 'properties';


        $normalizeLookingFor = function ($value): ?string {
            $value = $value === null ? null : (string) $value;
            $value = $value === null ? null : trim($value);
            $value = $value === null ? null : trim($value, "\"' ");

            if (!$value) {
                return null;
            }

            // Handle cases like "PG,2026-04-01" => "PG"
            $parts = explode(',', $value, 2);
            $first = $parts[0] ?? null;
            $first = $first === null ? null : trim($first);
            $first = $first === null ? null : trim($first, "\"' ");

            return $first === '' ? null : $first;
        };

        $lookingFor = $normalizeLookingFor($request->get('looking_for'));

        // SEO-friendly route: /properties-in/{city}
        $routeCity = $request->route('city');
        $city     = $normalizeParam($request->get('city') ?? $routeCity);
        $locality = $normalizeParam($request->get('locality'));
        $sector   = $normalizeParam($request->get('sector'));

        $buildBaseQuery = function($lFor = null) use ($request) {

            $q = Property::with(['images', 'dealer', 'builder'])
                ->paidAndValid()
                ->whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired', 'Sold', 'Rented'])
                ->whereNotNull('price')
                ->where('listing_status', 'active')
                ->where('price', '>', 0);

            if ($request->filled('keyword')) {
                $keyword = $request->keyword;
                $q->where(function ($inner) use ($keyword) {
                    $inner->where('title', 'like', "%{$keyword}%")
                      ->orWhere('description', 'like', "%{$keyword}%")
                      ->orWhere('address', 'like', "%{$keyword}%")
                      ->orWhere('locality', 'like', "%{$keyword}%")
                      ->orWhere('city', 'like', "%{$keyword}%");
                });
            }

            if ($request->filled('property_type')) {
                $pType = $request->property_type;
                if (in_array(strtolower(trim($pType)), ['flat', 'flats', 'apartment', 'apartments'])) {
                    $q->where(function ($inner) {
                        $inner->where('property_type', 'like', '%Flat%')
                          ->orWhere('property_type', 'like', '%Flats%')
                          ->orWhere('property_type', 'like', '%Apartment%')
                          ->orWhere('property_type', 'like', '%Apartments%');
                    });
                } else {
                    $q->where('property_type', 'like', "%{$pType}%");
                }
            }

            $useLF = $lFor ?? $request->get('looking_for');
            if ($useLF) {
                if (in_array($useLF, ['Sale', 'Buy', 'buy', 'sale'])) {
                    $q->whereIn('looking_for', ['Sale', 'Sell', 'Buy', 'sell', 'buy', 'sale']);
                } else {
                    $q->where('looking_for', $useLF);
                }
            }

            if ($request->filled('bhk_type')) $q->where('bhk_type', $request->bhk_type);
            if ($request->filled('min_price')) $q->where('price', '>=', $request->min_price);
            if ($request->filled('max_price')) $q->where('price', '<=', $request->max_price);
            if ($request->filled('min_area')) $q->where('area', '>=', $request->min_area);
            if ($request->filled('max_area')) $q->where('area', '<=', $request->max_area);
            if ($request->filled('bedrooms')) $q->where('bedrooms', '>=', $request->bedrooms);
            if ($request->filled('furnishing_status')) $q->where('furnishing_status', $request->furnishing_status);
            if ($request->filled('pet_friendly')) $q->where('pet_friendly', true);
            if ($request->filled('gated_society')) $q->where('gated_society', true);
            if ($request->filled('vastu_compliant')) $q->where('vastu_compliant', true);

            return $q;
        };

        // Sorting
        $sortBy = $request->get('sort_by', 'newest');
        $applySorting = function ($q) use ($sortBy) {
            switch ($sortBy) {
                case 'price_low':
                    $q->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $q->orderBy('price', 'desc');
                    break;
                case 'area':
                    $q->orderBy('area', 'desc');
                    break;
                default:
                    $q->orderByRaw('is_boosted DESC, boosted_until DESC, is_featured DESC, is_premium DESC, created_at DESC');
                    break;
            }
        };

        $baseQuery = $buildBaseQuery();
        $attempt = function (callable $applyLocationFilters) use ($baseQuery, $applySorting) {
            $q = clone $baseQuery;
            $applyLocationFilters($q);
            $applySorting($q);
            return $q->paginate(24)->withQueryString();
        };

        $attemptCount = 0;
        $properties = null;

        // Attempt 1: exact city + locality
        if (!empty($city) && !empty($locality)) {
            $q1 = clone $baseQuery;
            $q1->where('city', 'like', "%{$city}%")
               ->where('locality', 'like', "%{$locality}%");
            $applySorting($q1);
            if ($q1->count() > 0) {
                $properties = $q1->paginate(24)->withQueryString();
            }
        }

        // Attempt 2: city only
        if ($properties === null && !empty($city)) {
            $q2 = clone $baseQuery;
            $q2->where('city', 'like', "%{$city}%")
               ->orWhere('locality', 'like', "%{$city}%");
            $applySorting($q2);
            if ($q2->count() > 0) {
                $properties = $q2->paginate(24)->withQueryString();
            }
        }

        // Attempt 3: sector only (best-effort). The model currently does not have a `sector` field,
        // but DB may. If the column doesn't exist, this attempt will be safely skipped.
        if ($properties === null && !empty($sector)) {
            try {
                $q3 = clone $baseQuery;
                $q3->where('sector', 'like', "%{$sector}%");
                $applySorting($q3);
                if ($q3->count() > 0) {
                    $properties = $q3->paginate(24)->withQueryString();
                }
            } catch (\Throwable $e) {
                // Ignore if `sector` column doesn't exist.
            }
        }

        // IMPORTANT CONSISTENCY NOTE:
        // /properties/in/{location} uses locationSearch(): 10km geo-bounding-box OR label match.
        // To ensure counts match, when ?city={slug} is a known location slug we must
        // use the same geo+label filter BEFORE the result is finalized.
        //
        // This runs regardless of whether attempt1/attempt2 already found results.
        if (!empty($city) && $request->filled('city')) {
            $locations = $this->getLocationMap();
            $slug = strtolower(trim((string) $city));

            if (isset($locations[$slug])) {
                $loc = $locations[$slug];
                $radius = 10;
                $latDelta = $radius / 111.0;
                $lngDelta = $radius / 96.5;

                $minLat = $loc['lat'] - $latDelta;
                $maxLat = $loc['lat'] + $latDelta;
                $minLng = $loc['lng'] - $lngDelta;
                $maxLng = $loc['lng'] + $lngDelta;

                $qGeo = clone $baseQuery;
                $qGeo->where(function ($qGeoOuter) use ($loc, $minLat, $maxLat, $minLng, $maxLng) {
                    $qGeoOuter->where(function ($inner) use ($minLat, $maxLat, $minLng, $maxLng) {
                        $inner->whereNotNull('latitude')
                              ->whereNotNull('longitude')
                              ->whereBetween('latitude', [$minLat, $maxLat])
                              ->whereBetween('longitude', [$minLng, $maxLng]);
                    })
                    ->orWhere('city', 'like', '%' . $loc['label'] . '%')
                    ->orWhere('locality', 'like', '%' . $loc['label'] . '%');
                });

                $applySorting($qGeo);
                $properties = $qGeo->paginate(24)->withQueryString();
            }
        }


        // Final fallback: whatever filters remain (no city/locality/sector gating)
        if ($properties === null || $properties->total() === 0) {
            $q0 = clone $baseQuery;
            $applySorting($q0);
            $properties = $q0->paginate(24)->withQueryString();
        }

        // Important: on /properties we must keep results strictly matching
        // the query-string filters. Do NOT widen search via PG->Rent or
        // “ultimate fallback” (it changes behavior vs earlier working version).
        if (!$isBasePropertiesPage) {
            // Fallback: If still no results and looking for PG, try Rent
            if ($properties->total() === 0 && strtolower($lookingFor) === 'pg') {
                $baseQuery = $buildBaseQuery('Rent');
                $qFallback = clone $baseQuery;
                if (!empty($city)) {
                    $qFallback->where('city', 'like', "%{$city}%");
                }
                $applySorting($qFallback);
                $properties = $qFallback->paginate(24)->withQueryString();
            }

            // Ultimate Fallback: Just show latest properties in the city if any, or any properties
            if ($properties->total() === 0) {
                $properties = Property::with(['images', 'dealer', 'builder'])
                    ->paidAndValid()
                    ->whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired', 'Sold', 'Rented'])
                    ->where('listing_status', 'active')
                    ->when($city, function($q) use ($city) {
                        $q->where('city', 'like', "%{$city}%");
                    })
                    ->orderByRaw('is_boosted DESC, created_at DESC')
                    ->paginate(24);
            }
        }


        $cities = Property::whereNotNull('city')->distinct()->pluck('city')->filter()->sort();
        $propertyTypes = Property::whereNotNull('property_type')->distinct()->pluck('property_type')->filter()->sort();

        return view('frontend.properties', compact('properties', 'cities', 'propertyTypes'));
    }
}
