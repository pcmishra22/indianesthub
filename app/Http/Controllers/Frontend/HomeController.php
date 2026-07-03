<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Dealer;
use App\Models\Property;

class HomeController extends Controller
{
    public function index()
    {
        // Featured / Premium properties (boosted or featured)
        $featuredProperties = Property::with(['images', 'dealer', 'builder'])
            ->whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired', 'Sold', 'Rented'])
            ->whereNotNull('price')->where('price', '>', 0)
            ->orderByRaw('is_boosted DESC, is_featured DESC, is_premium DESC, created_at DESC')
            ->limit(6)
            ->get();

        // Latest properties
        $latestProperties = Property::with(['images', 'dealer', 'builder'])
            ->whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired', 'Sold', 'Rented'])
            ->whereNotNull('price')->where('price', '>', 0)
            ->latest()
            ->limit(8)
            ->get();

        // New Launches – builder projects
        $newLaunches = BuilderProject::with('builder')
            ->where('is_active', true)
            ->whereIn('status', ['Upcoming', 'Under Construction'])
            ->latest()
            ->limit(4)
            ->get();

        // Top cities with property count
        $topCities = Property::whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired', 'Sold', 'Rented'])
            ->whereNotNull('city')
            ->whereNotNull('price')->where('price', '>', 0)
            ->selectRaw('city, count(*) as count')
            ->groupBy('city')
            ->orderByDesc('count')
            ->limit(8)
            ->get();

        // Top dealers (property_dealers table has no `name` column; use company/first/last)
        $topDealers = Dealer::where(function ($q) {
            $q->whereNotNull('company_name')->where('company_name', '!=', '')
              ->orWhereNotNull('first_name')->where('first_name', '!=', '')
              ->orWhereNotNull('last_name')->where('last_name', '!=', '');
        })
            ->limit(6)
            ->get();


        // Top builders
        $topBuilders = Builder::where('status', 'active')
            ->orWhereNull('status')
            ->limit(6)
            ->get();

        // Stats
        $totalProperties = Property::whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired', 'Sold', 'Rented'])
            ->whereNotNull('price')->where('price', '>', 0)
            ->count();

        $totalDealers   = Dealer::count();
        $totalBuilders  = Builder::count();
        $totalCities    = Property::whereNotNull('city')->distinct()->count('city');

        // Property types with counts
        $propertyTypes = Property::whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired', 'Sold', 'Rented'])
            ->whereNotNull('property_type')
            ->selectRaw('property_type, count(*) as count')
            ->groupBy('property_type')
            ->orderByDesc('count')
            ->pluck('count', 'property_type');

        return view('frontend.index', compact(
            'featuredProperties',
            'latestProperties',
            'newLaunches',
            'topCities',
            'topDealers',
            'topBuilders',
            'totalProperties',
            'totalDealers',
            'totalBuilders',
            'totalCities',
            'propertyTypes'
        ));
    }
}
