<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Dealer;
use Illuminate\Http\Request;

class AgentsController extends Controller
{
    /**
     * Phase A — Dealer list page (/agents)
     */
    public function index(Request $request)
    {
        $query = Dealer::withCount('properties')
            ->where('status', 'active')
            ->orderByDesc('properties_count');

        // Optional keyword search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%")
                  ->orWhere('company_name', 'like', "%{$s}%")
                  ->orWhere('operating_cities', 'like', "%{$s}%");
            });
        }

        $dealers = $query->paginate(12)->withQueryString();

        return view('frontend.agents', compact('dealers'));
    }

    /**
     * Phase B — Dealer profile + all their properties (/agent-profile/{slug})
     */
    public function profile(Dealer $dealer)
    {
        $dealer->loadCount('properties');

        $properties = $dealer->properties()
            ->with('images')
            // Show any property that is not explicitly removed.
            // (Dealer list pages should not hide the dealer's own properties.)
            ->whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired'])
            ->orWhereNull('status')
            ->orderByDesc('created_at')
            ->paginate(9);


        $totalViews = $dealer->properties()->sum('views_count');

        $citiesServed = $dealer->properties()
            ->whereNotNull('city')
            ->distinct()
            ->pluck('city')
            ->filter()
            ->unique()
            ->values();

        return view('frontend.agent-profile', compact('dealer', 'properties', 'totalViews', 'citiesServed'));
    }
}
