<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;

class AIRecommendationController extends Controller
{
    public function recommend(Request $request)
    {
        // Placeholder: Recommend properties based on user history
        $userId = $request->user()->id ?? null;
        $properties = Property::inRandomOrder()->take(5)->get();
        return view('frontend.recommendations', compact('properties'));
    }
}
