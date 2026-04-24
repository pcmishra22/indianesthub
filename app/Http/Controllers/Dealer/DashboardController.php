<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Inquiry;
use App\Models\ScheduleViewing;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $dealer = Auth::guard('dealer')->user();

        $totalProperties = Property::where('property_dealer_id', $dealer->id)->count();

        $activeProperties = Property::where('property_dealer_id', $dealer->id)
            ->where('status', 'Available')
            ->count();

        $totalInquiries = Inquiry::where('broker_id', $dealer->id)->count();

        $totalViewings = ScheduleViewing::where('dealer_id', $dealer->id)->count();

        $totalViews = Property::where('property_dealer_id', $dealer->id)
            ->sum('views_count');

        $recentInquiries = Inquiry::where('broker_id', $dealer->id)
            ->with('property')
            ->latest()
            ->take(5)
            ->get();

        $recentViewings = ScheduleViewing::where('dealer_id', $dealer->id)
            ->with('property')
            ->latest()
            ->take(5)
            ->get();

        $topProperties = Property::where('property_dealer_id', $dealer->id)
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        return view('dealer.dashboard', compact(
            'totalProperties',
            'activeProperties',
            'totalInquiries',
            'totalViewings',
            'totalViews',
            'recentInquiries',
            'recentViewings',
            'topProperties'
        ));
    }
}
