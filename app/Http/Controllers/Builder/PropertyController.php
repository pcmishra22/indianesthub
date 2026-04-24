<?php

namespace App\Http\Controllers\Builder;

use App\Http\Controllers\Controller;
use App\Models\BuilderProject;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
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

    private function authorizeProperty(BuilderProject $project, Property $property): void
    {
        $this->authorizeProject($project);
        if ($property->builder_project_id !== $project->id) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index(BuilderProject $project)
    {
        $this->authorizeProject($project);
        $properties = $project->properties()->latest()->paginate(10);
        return view('builder.properties.index', compact('project', 'properties'));
    }

    public function create(BuilderProject $project)
    {
        $this->authorizeProject($project);
        return view('builder.properties.create', compact('project'));
    }

    public function store(Request $request, BuilderProject $project)
    {
        $this->authorizeProject($project);

        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'property_type'    => ['required', 'string'],
            'bhk_type'         => ['nullable', 'string'],
            'option_type'      => ['nullable', 'string'],
            'price'            => ['nullable', 'numeric', 'min:0'],
            'area'             => ['nullable', 'numeric', 'min:0'],
            'bedrooms'         => ['nullable', 'integer', 'min:0'],
            'bathrooms'        => ['nullable', 'integer', 'min:0'],
            'balconies'        => ['nullable', 'integer', 'min:0'],
            'floor_number'     => ['nullable', 'string'],
            'furnishing_status'=> ['nullable', 'string'],
            'status'           => ['required', 'string'],
            'city'             => ['nullable', 'string', 'max:100'],
            'state'            => ['nullable', 'string', 'max:100'],
            'address'          => ['nullable', 'string', 'max:500'],
            'cover_image'      => ['nullable', 'image', 'max:3072'],
        ]);

        $validated['builder_id']         = $this->builder()->id;
        $validated['builder_project_id'] = $project->id;
        $validated['looking_for']        = $request->input('looking_for', 'Sale');

        $property = Property::create($validated);

        // Handle cover image
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store(
                "builder/{$this->builder()->id}/projects/{$project->id}/properties/{$property->id}/cover",
                'public'
            );
            $property->update(['cover_image' => $path]);
        }

        return redirect()->route('builder.projects.show', $project)
            ->with('success', 'Unit/Property added to project successfully!');
    }

    public function edit(BuilderProject $project, Property $property)
    {
        $this->authorizeProperty($project, $property);
        return view('builder.properties.edit', compact('project', 'property'));
    }

    public function update(Request $request, BuilderProject $project, Property $property)
    {
        $this->authorizeProperty($project, $property);

        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'property_type'    => ['required', 'string'],
            'bhk_type'         => ['nullable', 'string'],
            'option_type'      => ['nullable', 'string'],
            'price'            => ['nullable', 'numeric', 'min:0'],
            'area'             => ['nullable', 'numeric', 'min:0'],
            'bedrooms'         => ['nullable', 'integer', 'min:0'],
            'bathrooms'        => ['nullable', 'integer', 'min:0'],
            'balconies'        => ['nullable', 'integer', 'min:0'],
            'floor_number'     => ['nullable', 'string'],
            'furnishing_status'=> ['nullable', 'string'],
            'status'           => ['required', 'string'],
            'city'             => ['nullable', 'string', 'max:100'],
            'state'            => ['nullable', 'string', 'max:100'],
            'address'          => ['nullable', 'string', 'max:500'],
            'cover_image'      => ['nullable', 'image', 'max:3072'],
        ]);

        $validated['looking_for'] = $request->input('looking_for', $property->looking_for ?? 'Sale');

        // Handle new cover image
        if ($request->hasFile('cover_image')) {
            if ($property->cover_image) {
                Storage::disk('public')->delete($property->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store(
                "builder/{$this->builder()->id}/projects/{$project->id}/properties/{$property->id}/cover",
                'public'
            );
        }

        $property->update($validated);

        return redirect()->route('builder.projects.show', $project)
            ->with('success', 'Unit updated successfully!');
    }

    public function destroy(BuilderProject $project, Property $property)
    {
        $this->authorizeProperty($project, $property);

        if ($property->cover_image) {
            Storage::disk('public')->delete($property->cover_image);
        }

        $property->delete();

        return redirect()->route('builder.projects.show', $project)
            ->with('success', 'Unit deleted successfully.');
    }
}
