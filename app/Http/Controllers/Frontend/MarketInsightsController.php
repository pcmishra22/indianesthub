<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MarketInsightsController extends Controller
{
    public function index()
    {
        // Dummy data for market insights
        $insights = [
            'average_price' => 750000,
            'total_properties' => 1200,
            'hot_areas' => ['Downtown', 'Uptown', 'Suburbia'],
        ];
        return view('frontend.market-insights', compact('insights'));
    }
}
