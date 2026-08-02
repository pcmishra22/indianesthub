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

        // ── "Today" action list ──────────────────────────────────────────
        // Overdue follow-ups: promised a callback and it's now past due.
        $overdueFollowUps = Inquiry::where('broker_id', $dealer->id)
            ->overdue()
            ->with('property:id,title')
            ->orderBy('follow_up_at')
            ->take(8)
            ->get();

        // Hot leads (score 80+) that haven't been called yet — the ones
        // most likely to convert if contacted today.
        $hotUncalledLeads = Inquiry::where('broker_id', $dealer->id)
            ->where('hot_score', '>=', 80)
            ->where(function ($q) {
                $q->whereNull('call_log')->orWhereRaw("JSON_LENGTH(call_log) = 0");
            })
            ->whereNotIn('status', ['Converted', 'Lost'])
            ->with('property:id,title')
            ->orderByDesc('hot_score')
            ->take(8)
            ->get();

        // Site visits scheduled for today.
        $todaysVisits = ScheduleViewing::where('dealer_id', $dealer->id)
            ->whereDate('date', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->with('property:id,title')
            ->orderBy('time')
            ->get();

        return view('dealer.dashboard', compact(
            'totalProperties',
            'activeProperties',
            'totalInquiries',
            'totalViewings',
            'totalViews',
            'recentInquiries',
            'recentViewings',
            'topProperties',
            'overdueFollowUps',
            'hotUncalledLeads',
            'todaysVisits'
        ));
    }
}
