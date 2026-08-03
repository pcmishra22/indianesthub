<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PropertyManagementLead;
use Illuminate\Http\Request;

class PropertyManagementLeadController extends Controller
{
    public function index()
    {
        return view('frontend.property-management');
    }

    /**
     * Store a new property management lead (AJAX friendly).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:100',
            'phone'                => 'required|string|min:10|max:15',
            'email'                => 'nullable|email|max:150',
            'property_type'        => 'nullable|string|max:50',
            'service_type'         => 'nullable|in:full-management,tenant-management,rent-collection,maintenance',
            'city'                 => 'nullable|string|max:100',
            'num_properties'       => 'nullable|integer|min:1',
            'currently_rented'     => 'nullable|boolean',
            'property_id'          => 'nullable|integer',
            'builder_project_id'   => 'nullable|integer',
            'source'               => 'nullable|string|max:100',
            'source_page'          => 'nullable|string|max:255',
        ]);

        $lead = PropertyManagementLead::create([
            ...$validated,
            'source'      => $validated['source']      ?? 'website',
            'source_page' => $validated['source_page'] ?? $request->header('referer'),
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Thank you! Our property management team will contact you within 24 hours.",
                'lead_id' => $lead->id,
            ]);
        }

        return back()->with('pm_success', 'Thank you! Our property management team will contact you within 24 hours.');
    }
}
