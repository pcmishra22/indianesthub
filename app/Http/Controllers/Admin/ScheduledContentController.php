<?php
namespace App\Http\Controllers\Admin;

use App\Models\ScheduledContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ScheduledContentController extends Controller
{
    public function index()
    {
        $scheduledContents = ScheduledContent::all();
        return view('admin.schedule.index', compact('scheduledContents'));
    }

    public function create()
    {
        return view('admin.schedule.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'scheduled_at' => 'required|date',
        ]);
        ScheduledContent::create($validated);
        return redirect()->route('admin.schedule.index')->with('success', 'Content scheduled successfully.');
    }

    public function edit($id)
    {
        $content = ScheduledContent::findOrFail($id);
        return view('admin.schedule.edit', compact('content'));
    }

    public function update(Request $request, $id)
    {
        $content = ScheduledContent::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'scheduled_at' => 'required|date',
        ]);
        $content->update($validated);
        return redirect()->route('admin.schedule.index')->with('success', 'Content updated successfully.');
    }

    public function destroy($id)
    {
        $content = ScheduledContent::findOrFail($id);
        $content->delete();
        return redirect()->route('admin.schedule.index')->with('success', 'Content deleted successfully.');
    }
}
