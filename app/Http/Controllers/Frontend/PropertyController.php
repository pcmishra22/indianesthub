<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function show($slug)
    {
        $property = Property::where('slug', $slug)->with(['images', 'dealer'])->firstOrFail();

        // Mask dealer contact details if the user is not logged in
        if (!Auth::check() && !$property->public_contact_enabled && $property->dealer) {
            $property->dealer->phone = 'Login to view';
            $property->dealer->email = 'Login to view';
        }

        return view('frontend.property-details', compact('property'));
    }
}
