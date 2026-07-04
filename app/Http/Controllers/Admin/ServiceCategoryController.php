<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceCategory::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('icon', 'like', "%{$search}%");
            });
        }

        if ($active = $request->input('is_active')) {
            $query->where('is_active', $active === '1');
        }

        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'oldest' => $query->oldest(),
            'name' => $query->orderBy('name'),
            'sort' => $query->orderBy('sort_order'),
            default => $query->latest(),
        };

        $categories = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => ServiceCategory::count(),
            'active' => ServiceCategory::where('is_active', true)->count(),
            'inactive' => ServiceCategory::where('is_active', false)->count(),
        ];

        return view('backend.services.index', compact('categories', 'stats'));
    }

    public function show(ServiceCategory $service_category)
    {
        $service_category->load('providers');
        return view('backend.services.show', ['category' => $service_category]);
    }

    public function create()
    {
        return view('backend.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:service_categories,slug',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'is_active' => 'required|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $category = ServiceCategory::create($validated);

        return redirect()->route('admin.services.show', $category)
            ->with('success', 'Service created successfully.');
    }

    public function edit(ServiceCategory $service_category)
    {
        return view('backend.services.edit', ['category' => $service_category]);
    }

    public function update(Request $request, ServiceCategory $service_category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:service_categories,slug,' . $service_category->id,
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'is_active' => 'required|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $service_category->update($validated);

        return redirect()->route('admin.services.show', $service_category)
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(ServiceCategory $service_category)
    {
        $service_category->providers()->detach();
        $service_category->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }
}

