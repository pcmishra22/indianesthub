<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\AiPriceEstimatorService;
use Illuminate\Http\Request;

class PriceEstimatorController extends Controller
{
    public function index()
    {
        return view('frontend.price-estimator');
    }

    public function estimate(Request $request, AiPriceEstimatorService $ai)
    {
        $attrs = $request->validate([
            'city'              => 'required|string|max:100',
            'locality'          => 'nullable|string|max:150',
            'property_type'     => 'nullable|string|max:100',
            'bhk_type'          => 'nullable|string|max:50',
            'area'              => 'nullable|numeric|min:0',
            'area_unit'         => 'nullable|string|max:20',
            'furnishing_status' => 'nullable|string|max:50',
        ]);

        $result = $ai->estimate($attrs);

        if ($result['error']) {
            return response()->json(['error' => true, 'message' => $result['message']], 422);
        }

        return response()->json($result);
    }
}
