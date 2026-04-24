<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    /**
     * Display the security compliance page.
     */
    public function index()
    {
        // For now, just return a view with empty security data
        $compliance = [];
        return view('frontend.security.index', compact('compliance'));
    }
}
