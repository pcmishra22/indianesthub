<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BuilderLead;
use App\Models\BuilderProject;
use App\Models\ProjectView;
use Illuminate\Http\Request;

class ProjectViewersController extends Controller
{
    public function index(Request $request, $id)
    {
        $project = BuilderProject::with('builder')->findOrFail($id);

        $from = $request->input('from');
        $to   = $request->input('to');

        $query = ProjectView::where('builder_project_id', $project->id)
            ->where('event_type', 'page_view')
            ->orderByDesc('viewed_at');

        if ($from && $to) {
            $fromDt = \Carbon\Carbon::parse($from)->startOfDay();
            $toDt   = \Carbon\Carbon::parse($to)->endOfDay();
            $query->whereBetween('viewed_at', [$fromDt, $toDt]);
        }

        $projectViews = $query->paginate(200);

        $tokenList = $projectViews->pluck('visitor_token')->filter()->unique()->values();

        $leadsByToken = BuilderLead::where('builder_project_id', $project->id)
            ->when($tokenList->isNotEmpty(), function ($q) use ($tokenList) {
                $q->whereIn('visitor_token', $tokenList);
            })
            ->get(['id', 'visitor_token', 'name', 'phone', 'email', 'lead_type', 'created_at'])
            ->groupBy('visitor_token');

        return view('backend.builder-projects.viewers', [
            'project' => $project,
            'projectViews' => $projectViews,
            'leadsByToken' => $leadsByToken,
            'from' => $from,
            'to' => $to,
        ]);
    }
}
