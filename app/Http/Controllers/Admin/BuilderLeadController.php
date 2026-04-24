<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BuilderLead;
use Illuminate\Http\Request;

class BuilderLeadController extends Controller
{
    public function index(Request $request)
    {
        $query = BuilderLead::with(['builder', 'project'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('lead_type')) {
            $query->where('lead_type', $request->lead_type);
        }

        $leads = $query->paginate(20);

        return view('backend.builder-leads.index', compact('leads'));
    }

    public function show($id)
    {
        $lead = BuilderLead::with(['builder', 'project'])->findOrFail($id);
        return view('backend.builder-leads.show', compact('lead'));
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
