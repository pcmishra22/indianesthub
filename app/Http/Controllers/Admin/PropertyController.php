<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::all();
        return view('backend.properties.index', compact('properties'));
    }

    public function show($id)
    {
        $property = Property::findOrFail($id);
        return view('backend.properties.show', compact('property'));
    }

    public function destroy($id)
    {
        // delete logic (existing placeholder)
        return back();
    }

    public function toggleFeatured(Property $property)
    {
        $property->is_featured = !$property->is_featured;
        $property->save();

        return back()->with('success', 'Property featured status updated.');
    }

    public function togglePublicContact(Request $request, Property $property)
    {
        $enabled = (bool) $request->input('enabled', false);
        $property->public_contact_enabled = $enabled;
        $property->save();

        return back()->with('success', 'Public contact setting updated successfully.');
    }
}
