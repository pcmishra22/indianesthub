<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('property_type', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $properties = $query->latest()->paginate(20)->withQueryString();

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

    public function toggleStatus(Property $property)
    {
        $property->listing_status = ($property->listing_status === 'active') ? 'inactive' : 'active';
        $property->save();

        $label = ucfirst($property->listing_status);
        return back()->with('success', "Property listing status updated to {$label}.");
    }

    public function togglePublicContact(Request $request, Property $property)
    {
        $enabled = (bool) $request->input('enabled', false);
        $property->public_contact_enabled = $enabled;
        $property->save();

        return back()->with('success', 'Public contact setting updated successfully.');
    }
}
