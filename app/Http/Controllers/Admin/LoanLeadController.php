<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanLead;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LoanLeadController extends Controller
{
    public function index(Request $request)
    {
        $query = LoanLead::with(['property:id,title,slug', 'builderProject:id,title'])
            ->latest();

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->employment_type);
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
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        // Stats
        $stats = [
            'total'        => LoanLead::count(),
            'new'          => LoanLead::where('status', 'new')->count(),
            'contacted'    => LoanLead::where('status', 'contacted')->count(),
            'pre_approved' => LoanLead::where('status', 'pre-approved')->count(),
            'disbursed'    => LoanLead::where('status', 'disbursed')->count(),
        ];

        $leads = $query->paginate(20)->withQueryString();

        return view('backend.loan-leads.index', compact('leads', 'stats'));
    }

    public function show($id)
    {
        $lead = LoanLead::with(['property:id,title,slug', 'builderProject:id,title'])
            ->findOrFail($id);

        return view('backend.loan-leads.show', compact('lead'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,pre-approved,disbursed,lost',
            'notes'  => 'nullable|string|max:1000',
        ]);

        $lead = LoanLead::findOrFail($id);
        $lead->update([
            'status' => $request->status,
            'notes'  => $request->notes ?? $lead->notes,
        ]);

        return back()->with('success', 'Loan lead status updated successfully.');
    }

    public function destroy($id)
    {
        LoanLead::findOrFail($id)->delete();
        return redirect()->route('admin.loan-leads.index')
            ->with('success', 'Loan lead deleted successfully.');
    }

    /**
     * Export loan leads as CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = LoanLead::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leads = $query->get();

        $filename = 'loan-leads-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($leads) {
            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, [
                'ID', 'Name', 'Phone', 'Email',
                'Loan Amount', 'Property Value', 'Employment', 'Monthly Income',
                'Tenure', 'Purpose', 'Source', 'Status', 'Notes', 'Date',
            ]);

            foreach ($leads as $lead) {
                fputcsv($handle, [
                    $lead->id,
                    $lead->name,
                    $lead->phone,
                    $lead->email,
                    $lead->loan_amount,
                    $lead->property_value,
                    $lead->employment_type,
                    $lead->monthly_income,
                    $lead->loan_tenure ? $lead->loan_tenure . ' years' : '',
                    $lead->loan_purpose,
                    $lead->source,
                    $lead->status,
                    $lead->notes,
                    $lead->created_at->format('d M Y H:i'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
