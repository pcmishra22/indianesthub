<?php

namespace App\Http\Controllers\Builder;

use App\Http\Controllers\Controller;
use App\Models\BuilderLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadsController extends Controller
{
    private function builder()
    {
        return Auth::guard('builder')->user();
    }

    // ── Index ──────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $builderId = $this->builder()->id;

        $query = BuilderLead::where('builder_id', $builderId)
            ->with('project:id,title')
            ->latest();

        if ($request->filled('status'))     $query->where('status',               $request->status);
        if ($request->filled('lead_type'))  $query->where('lead_type',            $request->lead_type);
        if ($request->filled('project_id')) $query->where('builder_project_id',   $request->project_id);
        if ($request->filled('heat')) {
            match ($request->heat) {
                'hot'  => $query->where('hot_score', '>=', 80),
                'warm' => $query->whereBetween('hot_score', [50, 79]),
                'cold' => $query->where('hot_score', '<', 50),
                default => null,
            };
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name',  'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $leads = $query->paginate(20)->withQueryString();

        // ── Stats ──────────────────────────────────────────────────────────
        $base  = BuilderLead::where('builder_id', $builderId);
        $stats = [
            'total'     => (clone $base)->count(),
            'new'       => (clone $base)->where('status', 'new')->count(),
            'contacted' => (clone $base)->where('status', 'contacted')->count(),
            'converted' => (clone $base)->where('status', 'converted')->count(),
            'hot'       => (clone $base)->where('hot_score', '>=', 80)->count(),
            'overdue'   => (clone $base)->whereNotNull('follow_up_at')
                                        ->where('follow_up_at', '<', now())
                                        ->whereNotIn('status', ['converted', 'lost'])
                                        ->count(),
            'today'     => (clone $base)->whereDate('created_at', today())->count(),
            'this_week' => (clone $base)->where('created_at', '>=', now()->startOfWeek())->count(),
        ];

        $projects = $this->builder()->projects()->select('id', 'title')->orderBy('title')->get();

        return view('builder.leads.index', compact('leads', 'stats', 'projects'));
    }

    // ── Update status ──────────────────────────────────────────────────────

    public function updateStatus(Request $request, BuilderLead $lead)
    {
        abort_if($lead->builder_id !== $this->builder()->id, 403);
        $request->validate(['status' => ['required', 'in:new,contacted,converted,lost']]);
        $lead->update(['status' => $request->status]);
        $lead->recomputeHotScore();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'Status updated.');
    }

    // ── Save notes + follow-up ─────────────────────────────────────────────

    public function saveNotes(Request $request, BuilderLead $lead)
    {
        abort_if($lead->builder_id !== $this->builder()->id, 403);
        $request->validate([
            'notes'       => ['nullable', 'string', 'max:2000'],
            'follow_up_at'=> ['nullable', 'date'],
        ]);
        $lead->update([
            'notes'       => $request->notes,
            'follow_up_at'=> $request->follow_up_at ?: null,
        ]);
        return response()->json(['success' => true]);
    }

    // ── Add call log entry ─────────────────────────────────────────────────

    public function addCallLog(Request $request, BuilderLead $lead)
    {
        abort_if($lead->builder_id !== $this->builder()->id, 403);
        $request->validate([
            'note'     => ['required', 'string', 'max:500'],
            'duration' => ['nullable', 'integer', 'min:0'],
        ]);
        $lead->addCallLog($request->note, $request->duration);

        // Auto-move status to contacted
        if ($lead->status === 'new') {
            $lead->update(['status' => 'contacted']);
        }

        return response()->json([
            'success'  => true,
            'call_log' => $lead->fresh()->call_log,
            'hot_score'=> $lead->fresh()->hot_score,
        ]);
    }

    // ── CSV export ─────────────────────────────────────────────────────────

    public function export(Request $request): StreamedResponse
    {
        $builderId = $this->builder()->id;
        $query = BuilderLead::where('builder_id', $builderId)
            ->with('project:id,title')
            ->latest();

        if ($request->filled('status'))     $query->where('status',             $request->status);
        if ($request->filled('lead_type'))  $query->where('lead_type',          $request->lead_type);
        if ($request->filled('project_id')) $query->where('builder_project_id', $request->project_id);

        $leads = $query->get();

        $filename = 'leads-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($leads) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Name', 'Phone', 'Email', 'Project', 'Type', 'Status', 'Hot Score', 'Follow-up', 'Message', 'Notes', 'Date']);
            foreach ($leads as $lead) {
                fputcsv($out, [
                    $lead->id,
                    $lead->name,
                    $lead->phone,
                    $lead->email ?? '',
                    $lead->project->title ?? 'General',
                    ucfirst($lead->lead_type),
                    ucfirst($lead->status),
                    $lead->hot_score,
                    $lead->follow_up_at?->format('d M Y h:i A') ?? '',
                    $lead->message ?? '',
                    $lead->notes   ?? '',
                    $lead->created_at->format('d M Y h:i A'),
                ]);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    // ── Delete ─────────────────────────────────────────────────────────────

    public function destroy(BuilderLead $lead)
    {
        abort_if($lead->builder_id !== $this->builder()->id, 403);
        $lead->delete();
        return back()->with('success', 'Lead removed.');
    }
}
