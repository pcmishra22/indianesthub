<?php

namespace App\Http\Controllers;

use App\Services\AiPropertyDescriptionService;
use Illuminate\Http\Request;

class PropertyAiController extends Controller
{
    public function generateDescription(Request $request, AiPropertyDescriptionService $ai)
    {
        $attrs = $request->validate([
            'title'             => 'nullable|string|max:255',
            'property_type'     => 'nullable|string|max:100',
            'bhk_type'          => 'nullable|string|max:50',
            'bedrooms'          => 'nullable|numeric',
            'bathrooms'         => 'nullable|numeric',
            'city'              => 'nullable|string|max:100',
            'locality'          => 'nullable|string|max:150',
            'sub_locality'      => 'nullable|string|max:150',
            'area'              => 'nullable|numeric',
            'area_unit'         => 'nullable|string|max:20',
            'price'             => 'nullable|numeric',
            'furnishing_status' => 'nullable|string|max:50',
            'facing'            => 'nullable|string|max:50',
            'floor_number'      => 'nullable|numeric',
            'total_floors'      => 'nullable|numeric',
            'listing_type'      => 'nullable|string|max:20',
            'amenities'         => 'nullable|array',
            'amenities.*'       => 'string|max:100',
            'tone'              => 'nullable|in:professional,friendly,luxury,concise',
        ]);

        $result = $ai->generate($attrs);

        if ($result['error']) {
            return response()->json(['error' => true, 'message' => $result['message']], 422);
        }

        return response()->json([
            'error' => false,
            'description' => $result['description'],
            'meta_description' => $result['meta_description'],
        ]);
    }
}
