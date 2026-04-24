<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;

class CompareController extends Controller
{
    /**
     * Compare selected properties (for GET /property/compare).
     */
    public function compare(Request $request)
    {
        $propertyIds = $request->input('properties', []);
        $properties = Property::with('images')->whereIn('id', $propertyIds)->get();
        $allProperties = Property::all();

        return view('frontend.compare', [
            'properties' => $properties,
            'allProperties' => $allProperties,
        ]);
    }

    /**
     * Display the property comparison page.
     */
    public function index(Request $request)
    {
        $propertyIds = $request->session()->get('compare_properties', []);
        $properties = Property::with('images')->whereIn('id', $propertyIds)->get();
        $allProperties = Property::all();

        return view('frontend.compare', [
            'properties' => $properties,
            'allProperties' => $allProperties,
        ]);
    }

    /**
     * Add a property to the comparison list (max 4).
     */
    public function add(Request $request)
    {
        $request->validate([
            'property_id' => 'required|integer|exists:properties,id',
        ]);

        $propertyId = $request->input('property_id');
        $compareList = $request->session()->get('compare_properties', []);

        if (in_array($propertyId, $compareList)) {
            return response()->json([
                'success' => false,
                'message' => 'Property is already in the comparison list.',
                'compare_properties' => $compareList,
            ]);
        }

        if (count($compareList) >= 4) {
            return response()->json([
                'success' => false,
                'message' => 'You can compare a maximum of 4 properties.',
                'compare_properties' => $compareList,
            ]);
        }

        $compareList[] = $propertyId;
        $request->session()->put('compare_properties', $compareList);

        return response()->json([
            'success' => true,
            'message' => 'Property added to comparison.',
            'compare_properties' => $compareList,
        ]);
    }

    /**
     * Remove a property from the comparison list.
     */
    public function remove(Request $request)
    {
        $request->validate([
            'property_id' => 'required|integer',
        ]);

        $propertyId = $request->input('property_id');
        $compareList = $request->session()->get('compare_properties', []);
        $compareList = array_values(array_diff($compareList, [$propertyId]));
        $request->session()->put('compare_properties', $compareList);

        return response()->json([
            'success' => true,
            'message' => 'Property removed from comparison.',
            'compare_properties' => $compareList,
        ]);
    }

    /**
     * Clear all properties from the comparison list.
     */
    public function clear(Request $request)
    {
        $request->session()->forget('compare_properties');

        return response()->json([
            'success' => true,
            'message' => 'Comparison list cleared.',
            'compare_properties' => [],
        ]);
    }
}
