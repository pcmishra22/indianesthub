<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LegalLead;
use Illuminate\Http\Request;

class LegalLeadController extends Controller
{
    /**
     * Store a new legal help request (AJAX friendly).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:100',
            'phone'              => 'required|string|min:10|max:15',
            'email'              => 'nullable|email|max:150',
            'legal_issue_type'   => 'nullable|in:property_dispute,title_verification,sale_deed,will_registration,rental_agreement,court_case,other',
            'description'        => 'nullable|string|max:1000',
            'preferred_date'     => 'nullable|date|after_or_equal:today',
            'city'               => 'nullable|string|max:100',
            'property_id'        => 'nullable|integer',
            'builder_project_id' => 'nullable|integer',
            'source'             => 'nullable|string|max:100',
            'source_page'        => 'nullable|string|max:255',
        ]);

        LegalLead::create([
            ...$validated,
            'legal_issue_type' => $validated['legal_issue_type'] ?? 'other',
            'source'           => $validated['source']      ?? 'website',
            'source_page'      => $validated['source_page'] ?? $request->header('referer'),
            'ip_address'       => $request->ip(),
            'user_agent'       => $request->userAgent(),
        ]);

        $message = 'Thank you! Our legal expert will contact you within 24 hours.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back()->with('legal_success', $message);
    }
}
