<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InsuranceLead;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InsuranceLeadController extends Controller
{
    public function index(Request $request)
    {
        $query = InsuranceLead::with(['property', 'builderProject', 'loanLead'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('insurance_type')) {
            $query->where('insurance_type', $request->insurance_type);
        }
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('phone', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%")
                  ->orWhere('property_city', 'like', "%$s%");
            });
        }

        $leads = $query->paginate(20)->withQueryString();

        $stats = [
            'total'     => InsuranceLead::count(),
            'new'       => InsuranceLead::where('status', 'new')->count(),
            'contacted' => InsuranceLead::where('status', 'contacted')->count(),
            'quoted'    => InsuranceLead::where('status', 'quoted')->count(),
            'converted' => InsuranceLead::where('status', 'converted')->count(),
            'revenue'   => InsuranceLead::whereNotNull('commission_earned')->sum('commission_earned'),
        ];

        return view('backend.insurance-leads.index', compact('leads', 'stats'));
    }

    public function show(InsuranceLead $lead)
    {
        $lead->load(['property', 'builderProject', 'loanLead']);
        return view('backend.insurance-leads.show', compact('lead'));
    }

    public function updateStatus(Request $request, InsuranceLead $lead)
    {
        $request->validate([
            'status'           => 'required|in:new,contacted,quoted,converted,lost',
            'notes'            => 'nullable|string|max:2000',
            'premium_quoted'   => 'nullable|numeric|min:0',
            'commission_earned'=> 'nullable|numeric|min:0',
        ]);

        $lead->update([
            'status'            => $request->status,
            'notes'             => $request->notes ?? $lead->notes,
            'premium_quoted'    => $request->premium_quoted ?? $lead->premium_quoted,
            'commission_earned' => $request->commission_earned ?? $lead->commission_earned,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Insurance lead status updated.');
    }

    public function destroy(InsuranceLead $lead)
    {
        $lead->delete();
        return redirect()->route('admin.insurance-leads.index')
                         ->with('success', 'Insurance lead deleted.');
    }

    public function export(Request $request): StreamedResponse
    {
        $query = InsuranceLead::with(['property', 'builderProject'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('from_date')) $query->whereDate('created_at', '>=', $request->from_date);
        if ($request->filled('to_date'))   $query->whereDate('created_at', '<=', $request->to_date);

        $leads = $query->get();

        $response = new StreamedResponse(function () use ($leads) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'ID', 'Name', 'Phone', 'Email',
                'Property Value', 'Property Type', 'City',
                'Insurance Type', 'Coverage Amount',
                'Preferred Insurer', 'Source', 'Status',
                'Premium Quoted (₹)', 'Commission (₹)',
                'Loan Bundle', 'Date',
            ]);

            foreach ($leads as $lead) {
                fputcsv($handle, [
                    $lead->id,
                    $lead->name,
                    $lead->phone,
                    $lead->email ?? '',
                    $lead->property_value ?? '',
                    $lead->property_type ?? '',
                    $lead->property_city ?? '',
                    $lead->insuranceTypeLabel(),
                    $lead->coverage_amount ?? '',
                    $lead->preferred_insurer ?? '',
                    $lead->source,
                    $lead->status,
                    $lead->premium_quoted ?? '',
                    $lead->commission_earned ?? '',
                    $lead->loan_lead_id ? 'Yes' : 'No',
                    $lead->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition',
            'attachment; filename="insurance-leads-' . now()->format('Y-m-d') . '.csv"');

        return $response;
    }
}
