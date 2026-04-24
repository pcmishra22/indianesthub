<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\BuilderLead;
use Illuminate\Http\Request;

class BuilderController extends Controller
{
    /**
     * Public builder listing page (/builders)
     */
    public function index(Request $request)
    {
        $query = Builder::withCount('projects')
            ->where('status', 'active')
            ->orderByDesc('projects_count');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('company_name', 'like', "%{$s}%")
                  ->orWhere('name',         'like', "%{$s}%")
                  ->orWhere('city',         'like', "%{$s}%")
                  ->orWhere('cities_operating', 'like', "%{$s}%");
            });
        }

        if ($request->filled('city')) {
            $city = $request->city;
            $query->where(function ($q) use ($city) {
                $q->where('city', 'like', "%{$city}%")
                  ->orWhere('cities_operating', 'like', "%{$city}%");
            });
        }

        $builders = $query->paginate(12)->withQueryString();

        $totalBuilders   = Builder::where('status', 'active')->count();
        $totalProjects   = BuilderProject::count();
        $verifiedCount   = Builder::where('is_verified', true)->count();

        return view('frontend.builders', compact('builders', 'totalBuilders', 'totalProjects', 'verifiedCount'));
    }

    /**
     * Public builder profile page (/builders/{builder})
     */
    public function show(Builder $builder)
    {
        abort_if($builder->status !== 'active', 404);

        $builder->loadCount('projects');

        $projects = $builder->projects()
            ->withCount('properties')
            ->latest()
            ->paginate(6);

        $totalUnits = $builder->projects()->sum('total_units');

        $citiesServed = $builder->projects()
            ->whereNotNull('city')
            ->distinct()
            ->pluck('city')
            ->filter()
            ->unique()
            ->values();

        return view('frontend.builder-profile', compact('builder', 'projects', 'totalUnits', 'citiesServed'));
    }

    /**
     * Public project detail page (/projects/{project})
     */
    public function projectDetail(BuilderProject $project)
    {
        $project->load(['builder', 'amenityItems', 'properties' => function ($q) {
            $q->whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired'])
              ->orderBy('price');
        }]);

        // Increment views
        $project->increment('views_count');

        // Grouped amenities
        $amenitiesByCategory = $project->amenityItems->groupBy('category');

        // Price summary
        $minPrice = $project->properties->min('price') ?? $project->price_from;
        $maxPrice = $project->properties->max('price') ?? $project->price_to;

        // Other projects by same builder (exclude current)
        $relatedProjects = BuilderProject::where('builder_id', $project->builder_id)
            ->where('id', '!=', $project->id)
            ->withCount('properties')
            ->latest()
            ->limit(3)
            ->get();

        return view('frontend.project-detail', compact(
            'project', 'amenitiesByCategory', 'minPrice', 'maxPrice', 'relatedProjects'
        ));
    }

    /**
     * Handle lead form submission from project detail page (AJAX / POST)
     */
    public function submitLead(Request $request, BuilderProject $project)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'email'     => ['nullable', 'email', 'max:150'],
            'phone'     => ['required', 'string', 'max:20'],
            'message'   => ['nullable', 'string', 'max:1000'],
            'lead_type' => ['required', 'in:general,visit,callback,brochure,whatsapp'],
        ]);

        BuilderLead::create([
            'builder_id'         => $project->builder_id,
            'builder_project_id' => $project->id,
            'name'               => $validated['name'],
            'email'              => $validated['email'] ?? null,
            'phone'              => $validated['phone'],
            'message'            => $validated['message'] ?? null,
            'lead_type'          => $validated['lead_type'],
            'source'             => 'website',
            'status'             => 'new',
        ]);

        // Update leads count on project
        $project->increment('leads_count');

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Your enquiry has been submitted. We will contact you shortly!']);
        }

        return back()->with('success', 'Your enquiry has been submitted. We will contact you shortly!');
    }
}
