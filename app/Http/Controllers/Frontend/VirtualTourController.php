<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VirtualTourController extends Controller
{
    /**
     * Display the virtual tours AR page.
     */
    public function index()
    {
        // For now, just return a view with empty virtual tour data
        $tours = [];
        return view('frontend.virtual-tour', compact('tours'));
    }
}
