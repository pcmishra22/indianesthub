<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalLead;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LegalLeadController extends Controller
{
    public function index(Request $request)
    {
        $query = LegalLead::with(['property:id,title,slug', 'builderProject:id,title'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('legal_issue_type')) {
            $query->where('legal_issue_type', $request->legal_issue_type);
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
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('city', 'like', "%{$s}%");
            });
        }

        $stats = [
            'total'                  => LegalLead::count(),
            'new'                    => LegalLead::where('status', 'new')->count(),
            'contacted'              => LegalLead::where('status', 'contacted')->count(),
            'scheduled'              => LegalLead::where('status', 'consultation_scheduled')->count(),
            'resolved'               => LegalLead::where('status', 'resolved')->count(),
        ];

        $leads = $query->paginate(20)->withQueryString();

        return view('backend.legal-leads.index', compact('leads', 'stats'));
    }

    public function show($id)
    {
        $lead = LegalLead::with(['property:id,title,slug', 'builderProject:id,title'])
            ->findOrFail($id);

        return view('backend.legal-leads.show', compact('lead'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,consultation_scheduled,resolved,closed',
            'notes'  => 'nullable|string|max:2000',
        ]);

        $lead = LegalLead::findOrFail($id);
        $lead->update([
            'status' => $request->status,
            'notes'  => $request->notes ?? $lead->notes,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Legal lead status updated successfully.');
    }

    public function destroy($id)
    {
        LegalLead::findOrFail($id)->delete();
        return redirect()->route('admin.legal-leads.index')
            ->with('success', 'Legal lead deleted successfully.');
    }

    public function export(Request $request): StreamedResponse
    {
        $query = LegalLead::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $leads = $query->get();

        $filename = 'legal-leads-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($leads) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID', 'Name', 'Phone', 'Email',
                'Issue Type', 'Description', 'City',
                'Preferred Date', 'Source', 'Status', 'Notes', 'Date',
            ]);

            foreach ($leads as $lead) {
                fputcsv($handle, [
                    $lead->id,
                    $lead->name,
                    $lead->phone,
                    $lead->email ?? '',
                    $lead->issueTypeLabel(),
                    $lead->description ?? '',
                    $lead->city ?? '',
                    $lead->preferred_date ? $lead->preferred_date->format('d M Y') : '',
                    $lead->source,
                    $lead->status,
                    $lead->notes ?? '',
                    $lead->created_at->format('d M Y H:i'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
