<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropertyManagementLead;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PropertyManagementLeadController extends Controller
{
    public function index(Request $request)
    {
        $query = PropertyManagementLead::with(['property:id,title,slug', 'builderProject:id,title'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
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

        $stats = [
            'total'      => PropertyManagementLead::count(),
            'new'        => PropertyManagementLead::where('status', 'new')->count(),
            'contacted'  => PropertyManagementLead::where('status', 'contacted')->count(),
            'site_visit' => PropertyManagementLead::where('status', 'site-visit')->count(),
            'onboarded'  => PropertyManagementLead::where('status', 'onboarded')->count(),
        ];

        $leads = $query->paginate(20)->withQueryString();

        return view('backend.property-management-leads.index', compact('leads', 'stats'));
    }

    public function show($id)
    {
        $lead = PropertyManagementLead::with(['property:id,title,slug', 'builderProject:id,title'])
            ->findOrFail($id);

        return view('backend.property-management-leads.show', compact('lead'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,site-visit,onboarded,lost',
            'notes'  => 'nullable|string|max:1000',
        ]);

        $lead = PropertyManagementLead::findOrFail($id);
        $lead->update([
            'status' => $request->status,
            'notes'  => $request->notes ?? $lead->notes,
        ]);

        return back()->with('success', 'Property management lead status updated successfully.');
    }

    public function destroy($id)
    {
        PropertyManagementLead::findOrFail($id)->delete();
        return redirect()->route('admin.property-management-leads.index')
            ->with('success', 'Property management lead deleted successfully.');
    }

    public function export(Request $request): StreamedResponse
    {
        $query = PropertyManagementLead::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leads = $query->get();
        $filename = 'property-management-leads-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($leads) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID', 'Name', 'Phone', 'Email',
                'Property Type', 'Service Type', 'City', 'Num Properties', 'Currently Rented',
                'Source', 'Status', 'Notes', 'Date',
            ]);

            foreach ($leads as $lead) {
                fputcsv($handle, [
                    $lead->id,
                    $lead->name,
                    $lead->phone,
                    $lead->email,
                    $lead->property_type,
                    $lead->serviceTypeLabel(),
                    $lead->city,
                    $lead->num_properties,
                    $lead->currently_rented ? 'Yes' : 'No',
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
