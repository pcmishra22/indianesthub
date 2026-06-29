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
        $type = $request->get('type', 'all'); // all | property | builder

        // ── Property Inquiries ─────────────────────────────────────────
        $propertyInquiries = collect();
        if (in_array($type, ['all', 'property'])) {
            $propertyInquiries = Inquiry::with('property')
                ->latest()
                ->get()
                ->map(function ($inq) {
                    return [
                        'id'         => $inq->id,
                        'type'       => 'property',
                        'name'       => $inq->name,
                        'email'      => $inq->email,
                        'phone'      => $inq->phone,
                        'message'    => $inq->message,
                        'status'     => $inq->status ? 'active' : 'inactive',
                        'subject'    => $inq->property ? $inq->property->title : '—',
                        'subject_url'=> $inq->property
                                            ? route('property-details', $inq->property->slug)
                                            : null,
                        'source'     => $inq->source ?? 'website',
                        'created_at' => $inq->created_at,
                        'detail_url' => route('admin.inquiries.show', $inq->id),
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
                    $subject = $lead->project
                        ? $lead->project->title
                        : ($lead->builder ? $lead->builder->company_name : '—');

                    return [
                        'id'         => $lead->id,
                        'type'       => 'builder',
                        'name'       => $lead->name,
                        'email'      => $lead->email,
                        'phone'      => $lead->phone,
                        'message'    => $lead->message,
                        'status'     => $lead->status,
                        'subject'    => $subject,
                        'subject_url'=> $lead->project && $lead->project->slug
                                            ? route('projects.show', $lead->project->slug)
                                            : null,
                        'source'     => $lead->source ?? 'website',
                        'created_at' => $lead->created_at,
                        'detail_url' => route('admin.builder-leads.show', $lead->id),
                    ];
                });
        }

        // ── Merge & sort by latest ─────────────────────────────────────
        $inquiries = $propertyInquiries->concat($builderLeads)
            ->sortByDesc('created_at')
            ->values();

        // Counts for tab badges
        $counts = [
            'all'      => Inquiry::count() + BuilderLead::count(),
            'property' => Inquiry::count(),
            'builder'  => BuilderLead::count(),
        ];

        return view('backend.inquiries.index', compact('inquiries', 'type', 'counts'));
    }

    public function show($id)
    {
        return view('backend.inquiries.show', compact('id'));
    }

    public function destroy($id)
    {
        Inquiry::findOrFail($id)->delete();
        return back()->with('success', 'Inquiry deleted.');
    }
}
