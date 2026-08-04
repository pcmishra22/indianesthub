<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BuilderLead;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');

        // ── Property Inquiries ─────────────────────────────────────────
        $propertyInquiries = collect();
        if (in_array($type, ['all', 'property'])) {
            $propertyInquiries = Inquiry::with('property')
                ->latest()
                ->get()
                ->map(function ($inq) {
                    return [
                        'id'          => $inq->id,
                        'type'        => 'property',
                        'name'        => $inq->name,
                        'email'       => $inq->email ?? '—',
                        'phone'       => $inq->phone ?? '—',
                        'message'     => $inq->message,
                        'status'      => $inq->status ? 'active' : 'new',
                        'subject'     => optional($inq->property)->title ?? '—',
                        'subject_url' => ($inq->property && $inq->property->slug)
                                            ? route('property-details', $inq->property->slug)
                                            : null,
                        'source'      => $inq->source ?? 'website',
                        'created_at'  => $inq->created_at,
                        'detail_url'  => route('admin.inquiries.show', $inq->id),
                    ];
                });
        }

        // ── Builder / Project Leads ────────────────────────────────────
        $builderLeads = collect();
        if (in_array($type, ['all', 'builder'])) {
            $builderLeads = BuilderLead::with(['builder', 'project'])
                ->latest()
                ->get()
                ->map(function ($lead) {
                    $subject = optional($lead->project)->title
                        ?? optional($lead->builder)->company_name
                        ?? '—';

                    return [
                        'id'          => $lead->id,
                        'type'        => 'builder',
                        'name'        => $lead->name,
                        'email'       => $lead->email ?? '—',
                        'phone'       => $lead->phone ?? '—',
                        'message'     => $lead->message,
                        'status'      => $lead->status,
                        'subject'     => $subject,
                        'subject_url' => ($lead->project && $lead->project->slug)
                                            ? route('projects.show', $lead->project->slug)
                                            : null,
                        'source'      => $lead->source ?? 'website',
                        'created_at'  => $lead->created_at,
                        'detail_url'  => route('admin.builder-leads.show', $lead->id),
                    ];
                });
        }

        // ── Merge & sort by latest ─────────────────────────────────────
        $inquiries = $propertyInquiries
            ->concat($builderLeads)
            ->sortByDesc('created_at')
            ->values();

        $counts = [
            'all'      => Inquiry::count() + BuilderLead::count(),
            'property' => Inquiry::count(),
            'builder'  => BuilderLead::count(),
        ];

        return view('backend.inquiries.index', compact('inquiries', 'type', 'counts'));
    }

    public function show($id)
    {
        $inquiry = Inquiry::with('property', 'broker')->findOrFail($id);

        return view('backend.inquiries.show', compact('inquiry'));
    }

    public function updateStatus(Request $request, $id)
    {
        $inquiry = Inquiry::findOrFail($id);

        $request->validate([
            'status' => 'required|in:new,contacted,converted,lost',
        ]);

        $inquiry->update(['status' => $request->status]);
        $inquiry->recomputeHotScore();

        return back()->with('success', 'Status updated.');
    }

    public function destroy($id)
    {
        Inquiry::findOrFail($id)->delete();
        return redirect()->route('admin.inquiries.index')->with('success', 'Inquiry deleted.');
    }
}
