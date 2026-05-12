<?php

namespace App\Http\Controllers\Builder;

use App\Http\Controllers\Controller;
use App\Models\BuilderProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Amenity;

class ProjectController extends Controller
{
    private function builder()
    {
        return Auth::guard('builder')->user();
    }

    private function authorizeProject(BuilderProject $project): void
    {
        if ($project->builder_id !== $this->builder()->id) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index()
    {
        $projects = BuilderProject::where('builder_id', $this->builder()->id)
            ->withCount('properties')
            ->latest()
            ->paginate(10);

        return view('builder.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('builder.projects.create');
    }

    private function projectValidationRules(): array
    {
        return [
            'title'              => ['required', 'string', 'max:255'],
            'description'        => ['nullable', 'string'],
            'project_type'       => ['required', 'string'],
            'status'             => ['required', 'string'],
            'address'            => ['nullable', 'string', 'max:500'],
            'city'               => ['nullable', 'string', 'max:100'],
            'state'              => ['nullable', 'string', 'max:100'],
            'total_units'        => ['nullable', 'integer', 'min:1'],
            'available_units'    => ['nullable', 'integer', 'min:0'],
            'total_towers'       => ['nullable', 'integer', 'min:1'],
            'floors_per_tower'   => ['nullable', 'string', 'max:50'],
            'price_from'         => ['nullable', 'numeric', 'min:0'],
            'price_to'           => ['nullable', 'numeric', 'min:0'],
            'possession_date'    => ['nullable', 'date'],
            'cover_image'        => ['nullable', 'image', 'max:3072'],
            'master_plan'        => ['nullable', 'image', 'max:5120'],
            'brochure'           => ['nullable', 'mimes:pdf', 'max:10240'],
            'gallery_images'     => ['nullable', 'array'],
            'gallery_images.*'   => ['image', 'max:3072'],
            'video_url'          => ['nullable', 'url'],
            'virtual_tour_url'   => ['nullable', 'url'],
            'amenities'          => ['nullable', 'string'],
            'amenity_ids'        => ['nullable', 'array'],
            'amenity_ids.*'      => ['integer', 'exists:amenities,id'],
            'rera_id'            => ['nullable', 'string', 'max:100'],
            'is_featured'        => ['boolean'],
            // Location intelligence
            'latitude'           => ['nullable', 'numeric'],
            'longitude'          => ['nullable', 'numeric'],
            'metro_distance'     => ['nullable', 'string', 'max:100'],
            'connectivity_score' => ['nullable', 'string', 'max:50'],
            'nearby_schools'     => ['nullable', 'string'],
            'nearby_hospitals'   => ['nullable', 'string'],
            'future_infra'       => ['nullable', 'string'],
        ];
    }

    private function handleProjectFiles(Request $request, BuilderProject $project): void
    {
        $builderId = $this->builder()->id;
        $base      = "builder/{$builderId}/projects/{$project->id}";

        if ($request->hasFile('cover_image')) {
            if ($project->cover_image) Storage::disk('public')->delete($project->cover_image);
            $project->cover_image = $request->file('cover_image')->store("{$base}/cover", 'public');
        }
        if ($request->hasFile('master_plan')) {
            if ($project->master_plan) Storage::disk('public')->delete($project->master_plan);
            $project->master_plan = $request->file('master_plan')->store("{$base}/master_plan", 'public');
        }
        if ($request->hasFile('brochure')) {
            if ($project->brochure) Storage::disk('public')->delete($project->brochure);
            $project->brochure = $request->file('brochure')->store("{$base}/brochure", 'public');
        }
        if ($request->hasFile('gallery_images')) {
            $existing = $project->gallery_images ?? [];
            foreach ($request->file('gallery_images') as $file) {
                $existing[] = $file->store("{$base}/gallery", 'public');
            }
            $project->gallery_images = $existing;
        }
        $project->save();
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->projectValidationRules());
        $validated['builder_id']  = $this->builder()->id;
        $validated['is_featured'] = $request->boolean('is_featured');

        // Remove file/relation fields from mass-assignment
        $amenityIds = $request->input('amenity_ids', []);
        unset($validated['cover_image'], $validated['master_plan'], $validated['brochure'],
              $validated['gallery_images'], $validated['amenity_ids']);

        $project = BuilderProject::create($validated);
        $this->handleProjectFiles($request, $project);

        // Sync amenities M2M
        if (!empty($amenityIds)) {
            $project->amenityItems()->sync($amenityIds);
        }

        // Notify builder + admins about new project
        try {
            $builder = $this->builder();
            $adminRecipients = [
                'admin@indianesthub.com',
                'pcmishra22@gmail.com',
            ];

            $subject = 'New Project is added';
            $message = $subject . ': ' . ($project->title ?? $project->name ?? 'Project') . ' (ID: ' . $project->id . ')';

            if ($builder && !empty($builder->email)) {
                \Illuminate\Support\Facades\Mail::raw($message, function ($m) use ($builder) {
                    $m->to($builder->email)->subject('New Project is added');
                });
            }

            \Illuminate\Support\Facades\Mail::raw($message, function ($m) use ($adminRecipients) {
                $m->to($adminRecipients)->subject('New Project is added');
            });
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Builder project notification failed', [
                'project_id' => $project->id,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()->route('builder.projects.show', $project)
            ->with('success', 'Project created successfully!');
    }

    public function show(BuilderProject $project)
    {
        $this->authorizeProject($project);
        $project->load('amenityItems');
        $properties = $project->properties()->latest()->paginate(10);
        return view('builder.projects.show', compact('project', 'properties'));
    }

    public function edit(BuilderProject $project)
    {
        $this->authorizeProject($project);
        $project->load('amenityItems');
        return view('builder.projects.edit', compact('project'));
    }

    public function update(Request $request, BuilderProject $project)
    {
        $this->authorizeProject($project);

        $validated = $request->validate($this->projectValidationRules());
        $validated['is_featured'] = $request->boolean('is_featured');

        $amenityIds = $request->input('amenity_ids', []);
        unset($validated['cover_image'], $validated['master_plan'], $validated['brochure'],
              $validated['gallery_images'], $validated['amenity_ids']);

        $project->fill($validated);
        $project->save();

        $this->handleProjectFiles($request, $project);
        $project->amenityItems()->sync($amenityIds);

        return redirect()->route('builder.projects.show', $project)
            ->with('success', 'Project updated successfully!');
    }

    public function destroy(BuilderProject $project)
    {
        $this->authorizeProject($project);

        // Delete associated files
        if ($project->cover_image) {
            Storage::disk('public')->delete($project->cover_image);
        }
        if ($project->gallery_images) {
            Storage::disk('public')->delete($project->gallery_images);
        }

        $project->delete();

        return redirect()->route('builder.projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
