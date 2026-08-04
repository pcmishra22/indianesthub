<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BuilderLead;
use Illuminate\Http\Request;

class BuilderLeadController extends Controller
{
    public function index(Request $request)
    {
        $query = BuilderLead::with(['builder', 'project', 'property'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('lead_type')) {
            $query->where('lead_type', $request->lead_type);
        }

        $leads = $query->paginate(20);

        return view('backend.builder-leads.index', compact('leads'));
    }

    /**
     * "Live Call Clicks" queue — every call_click/whatsapp_click lead that's
     * still status=new, newest first. This is the screen staff pull up the
     * moment the office phone rings, to see who most recently tapped Call
     * and on which property, so they know the context before answering.
     */
    public function liveClicks(Request $request)
    {
        $leads = BuilderLead::with(['builder', 'project', 'property'])
            ->whereIn('lead_type', ['call_click', 'whatsapp_click'])
            ->where('status', 'new')
            ->latest()
            ->paginate(30);

        return view('backend.builder-leads.live-clicks', compact('leads'));
    }

    public function show($id)
    {
        $lead = BuilderLead::with(['builder', 'project', 'property'])->findOrFail($id);
        return view('backend.builder-leads.show', compact('lead'));
    }

    /**
     * Complete a click-tracked lead once staff has actually spoken to the
     * caller — fills in the identity that wasn't known at click time.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'  => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'notes' => 'nullable|string|max:2000',
        ]);

        $lead = BuilderLead::findOrFail($id);
        $lead->update($validated);

        // Talking to the caller and capturing their details is itself a
        // meaningful step forward — move it out of "new" automatically so
        // it drops off the Live Call Clicks queue instead of staff having
        // to also remember to change the status separately.
        if ($lead->status === 'new' && $request->filled('phone')) {
            $lead->status = 'contacted';
            $lead->save();
        }

        $lead->recomputeHotScore();

        return back()->with('success', 'Caller details saved.');
    }

    public function destroy($id)
    {
        BuilderLead::findOrFail($id)->delete();
        return redirect()->route('admin.builder-leads.index')
            ->with('success', 'Lead deleted successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,converted,lost',
        ]);

        $lead = BuilderLead::findOrFail($id);
        $lead->status = $request->status;
        $lead->save();

        return back()->with('success', 'Lead status updated to ' . ucfirst($request->status) . '.');
    }
}
