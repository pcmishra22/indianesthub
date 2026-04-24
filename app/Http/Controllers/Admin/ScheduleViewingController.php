<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduleViewing;
use Illuminate\Http\Request;

class ScheduleViewingController extends Controller
{
    public function index(Request $request)
    {
        $query = ScheduleViewing::with(['property', 'dealer'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%")
                  ->orWhere('phone', 'like', "%$s%");
            });
        }

        $viewings = $query->paginate(20)->withQueryString();

        $stats = [
            'total'     => ScheduleViewing::count(),
            'pending'   => ScheduleViewing::where('status', 'pending')->count(),
            'confirmed' => ScheduleViewing::where('status', 'confirmed')->count(),
            'completed' => ScheduleViewing::where('status', 'completed')->count(),
            'cancelled' => ScheduleViewing::where('status', 'cancelled')->count(),
            'today'     => ScheduleViewing::whereDate('date', today())->count(),
        ];

        return view('backend.schedule-viewings.index', compact('viewings', 'stats'));
    }

    public function show(ScheduleViewing $scheduleViewing)
    {
        $scheduleViewing->load(['property', 'dealer']);
        return view('backend.schedule-viewings.show', compact('scheduleViewing'));
    }

    public function updateStatus(Request $request, ScheduleViewing $scheduleViewing)
    {
        $request->validate([
            'status'      => 'required|in:pending,confirmed,completed,cancelled',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $scheduleViewing->update([
            'status'      => $request->status,
            'admin_notes' => $request->admin_notes ?? $scheduleViewing->admin_notes,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Viewing status updated.');
    }

    public function destroy(ScheduleViewing $scheduleViewing)
    {
        $scheduleViewing->delete();
        return redirect()->route('admin.schedule-viewings.index')
                         ->with('success', 'Schedule viewing deleted.');
    }
}
