<?php

namespace App\Http\Controllers\Builder;

use App\Http\Controllers\Controller;
use App\Models\BuilderLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadsController extends Controller
{
    private function builder()
    {
        return Auth::guard('builder')->user();
    }

    public function index(Request $request)
    {
        $query = BuilderLead::where('builder_id', $this->builder()->id)
            ->with('project:id,title')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('lead_type')) {
            $query->where('lead_type', $request->lead_type);
        }
        if ($request->filled('project_id')) {
            $query->where('builder_project_id', $request->project_id);
        }

        $leads = $query->paginate(20)->withQueryString();

        $stats = [
            'total'     => BuilderLead::where('builder_id', $this->builder()->id)->count(),
            'new'       => BuilderLead::where('builder_id', $this->builder()->id)->where('status', 'new')->count(),
            'contacted' => BuilderLead::where('builder_id', $this->builder()->id)->where('status', 'contacted')->count(),
            'converted' => BuilderLead::where('builder_id', $this->builder()->id)->where('status', 'converted')->count(),
        ];

        $projects = $this->builder()->projects()->select('id', 'title')->orderBy('title')->get();

        return view('builder.leads.index', compact('leads', 'stats', 'projects'));
    }

    public function updateStatus(Request $request, BuilderLead $lead)
    {
        // Ensure lead belongs to this builder
        abort_if($lead->builder_id !== $this->builder()->id, 403);

        $request->validate(['status' => ['required', 'in:new,contacted,converted,lost']]);
        $lead->update(['status' => $request->status]);

        return back()->with('success', 'Lead status updated.');
    }

    public function destroy(BuilderLead $lead)
    {
        abort_if($lead->builder_id !== $this->builder()->id, 403);
        $lead->delete();
        return back()->with('success', 'Lead removed.');
    }
}
