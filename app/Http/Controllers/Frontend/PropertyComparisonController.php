<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;

class PropertyComparisonController extends Controller
{
    public function index(Request $request)
    {
        $propertyIds = $request->input('properties', []);
        $sort = $request->input('sort');
        $properties = Property::whereIn('id', $propertyIds)->get();
        if ($sort && in_array($sort, ['price', 'area', 'rating'])) {
            $properties = $properties->sortBy($sort);
        }
        $allProperties = Property::all();
        return view('frontend.compare', compact('properties', 'allProperties'));
    }
}
