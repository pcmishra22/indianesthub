<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\InsuranceLead;
use Illuminate\Http\Request;

class InsuranceLeadController extends Controller
{
    /**
     * Store a new insurance lead via AJAX or regular POST.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'               => 'required|string|max:255',
            'phone'              => 'required|string|min:10|max:20',
            'email'              => 'nullable|email|max:255',
            'property_value'     => 'nullable|numeric|min:0',
            'property_type'      => 'nullable|string|max:100',
            'property_city'      => 'nullable|string|max:100',
            'possession_status'  => 'nullable|in:ready,under-construction',
            'insurance_type'     => 'nullable|in:home,content,both,fire',
            'coverage_amount'    => 'nullable|numeric|min:0',
            'preferred_insurer'  => 'nullable|string|max:100',
            'property_id'        => 'nullable|exists:properties,id',
            'builder_project_id' => 'nullable|exists:builder_projects,id',
            'loan_lead_id'       => 'nullable|exists:loan_leads,id',
            'source'             => 'nullable|string|max:100',
            'source_page'        => 'nullable|string|max:500',
        ]);

        InsuranceLead::create([
            'property_id'        => $request->property_id,
            'builder_project_id' => $request->builder_project_id,
            'loan_lead_id'       => $request->loan_lead_id,
            'name'               => $request->name,
            'phone'              => $request->phone,
            'email'              => $request->email,
            'property_value'     => $request->property_value,
            'property_type'      => $request->property_type,
            'property_city'      => $request->property_city,
            'possession_status'  => $request->possession_status ?? 'ready',
            'insurance_type'     => $request->insurance_type ?? 'home',
            'coverage_amount'    => $request->coverage_amount,
            'preferred_insurer'  => $request->preferred_insurer,
            'source'             => $request->source ?? 'website',
            'source_page'        => $request->source_page,
            'ip_address'         => $request->ip(),
            'user_agent'         => $request->userAgent(),
        ]);

        $message = 'Our insurance expert will contact you within 2 hours with the best quote!';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->back()->with('success', $message);
    }
}
