<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PrivacyController extends Controller
{
    public function index()
    {
        // Return the correct view for privacy
        return view('frontend.privacy');
    }

    public function policy()
    {
        return view('frontend.privacy-policy');
    }
}
