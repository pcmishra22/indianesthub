<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LoanLead;
use Illuminate\Http\Request;

class LoanLeadController extends Controller
{
    /**
     * Store a new loan lead (AJAX friendly).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:100',
            'phone'           => 'required|string|min:10|max:15',
            'email'           => 'nullable|email|max:150',
            'loan_amount'     => 'nullable|numeric|min:0',
            'property_value'  => 'nullable|numeric|min:0',
            'employment_type' => 'nullable|in:salaried,self-employed,business',
            'monthly_income'  => 'nullable|numeric|min:0',
            'loan_tenure'     => 'nullable|in:5,10,15,20,25,30',
            'loan_purpose'    => 'nullable|in:purchase,construction,renovation,balance-transfer',
            'property_id'     => 'nullable|integer',
            'builder_project_id' => 'nullable|integer',
            'source'          => 'nullable|string|max:100',
            'source_page'     => 'nullable|string|max:255',
        ]);

        $lead = LoanLead::create([
            ...$validated,
            'source'      => $validated['source']      ?? 'website',
            'source_page' => $validated['source_page'] ?? $request->header('referer'),
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'      => true,
                'message'      => 'Thank you! Our loan expert will contact you within 2 hours.',
                'loan_lead_id' => $lead->id,  // passed to insurance bundle flow
            ]);
        }

        return back()->with('loan_success', 'Thank you! Our loan expert will contact you within 2 hours.');
    }
}
