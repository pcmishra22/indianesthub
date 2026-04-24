<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function show($slug)
    {
        $property = Property::where('slug', $slug)->with(['images', 'dealer'])->firstOrFail();
        return view('frontend.property-details', compact('property'));
    }
}
