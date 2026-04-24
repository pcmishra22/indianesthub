<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics reporting page.
     */
    public function index()
    {
        // For now, just return a view with empty analytics data
        $analytics = [];
        return view('frontend.analytics.index', compact('analytics'));
    }
}
