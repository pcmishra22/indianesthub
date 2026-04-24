<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;
use App\Models\Review;
use App\Models\Inquiry;

class DashboardController extends Controller
{
    public function index()
    {
        $agentId = Auth::guard('agent')->id();
        $listingsCount = Property::where('property_dealer_id', $agentId)->count();
        $viewsCount = Property::where('property_dealer_id', $agentId)->sum('views_count');
        $inquiriesCount = Inquiry::where('broker_id', $agentId)->count();
        $avgRating = Review::where('agent_id', $agentId)->avg('rating');
        return view('agent.dashboard', compact('listingsCount', 'viewsCount', 'inquiriesCount', 'avgRating'));
    }
}
