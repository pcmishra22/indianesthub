<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    /**
     * Display the AI property recommendations page.
     */
    public function index()
    {
        // For now, just return a view with empty recommendations
        $recommendations = [];
        return view('frontend.recommendations', compact('recommendations'));
    }
}
