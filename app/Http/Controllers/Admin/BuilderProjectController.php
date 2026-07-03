<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BuilderProject;
use Illuminate\Http\Request;

class BuilderProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = BuilderProject::with('builder')
            ->withCount(['properties', 'leads']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('city',  'like', "%{$search}%")
                  ->orWhereHas('builder', function ($bq) use ($search) {
                      $bq->where('company_name', 'like', "%{$search}%")
                         ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        $projects = $query->latest()->paginate(15)->withQueryString();

        return view('backend.builder-projects.index', compact('projects'));
    }

    public function show($id)
    {
        $project = BuilderProject::with('builder')
            ->withCount(['properties', 'leads'])
            ->findOrFail($id);

        $properties = $project->properties()->latest()->get();
        $leads      = $project->leads()->latest()->limit(20)->get();

        return view('backend.builder-projects.show', compact('project', 'properties', 'leads'));
    }

    public function destroy($id)
    {
        $project = BuilderProject::findOrFail($id);
        $title   = $project->title;
        $project->delete();

        return redirect()->route('admin.builder-projects.index')
            ->with('success', "Project \"{$title}\" has been deleted.");
    }

    public function toggleFeatured($id)
    {
        $project = BuilderProject::findOrFail($id);
        $project->is_featured = !$project->is_featured;
        $project->save();

        $label = $project->is_featured ? 'Featured' : 'Unfeatured';
        return back()->with('success', "Project marked as {$label}.");
    }

    /**
     * Enable / disable a project's public visibility (hides it from the
     * public site regardless of its construction status).
     */
    public function toggleStatus($id)
    {
        $project = BuilderProject::findOrFail($id);
        $project->is_active = !$project->is_active;
        $project->save();

        $label = $project->is_active ? 'Enabled' : 'Disabled';
        return back()->with('success', "Project {$label} successfully.");
    }
}
