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
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('looking_for', 'like', "%{$search}%");
            });
        }

        if ($lookingFor = $request->input('looking_for')) {
            $query->where('looking_for', $lookingFor);
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
        $property = Property::findOrFail($id);
        $property->delete(); // soft delete — row is retained so the public listing URL can show a graceful "no longer available" page instead of a hard 404
        return back()->with('success', 'Property deleted successfully.');
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

    /**
     * Enable / disable a property's public visibility.
     * Disabling sets status = inactive (already excluded everywhere on the
     * public site) while remembering the previous status so re-enabling
     * restores it instead of always going back to "active".
     */
    public function toggleStatus(Property $property)
    {
        if ($property->status === 'inactive') {
            $property->status = $property->previous_status ?: 'active';
            $property->previous_status = null;
            $label = 'Enabled';
        } else {
            $property->previous_status = $property->status;
            $property->status = 'inactive';
            $label = 'Disabled';
        }
        $property->save();

        return back()->with('success', "Property {$label} successfully.");
    }
}
