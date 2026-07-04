<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ServiceProviderController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceProvider::withCount(['categories']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('business_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($verified = $request->input('is_verified')) {
            $query->where('is_verified', $verified === '1');
        }

        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'oldest' => $query->oldest(),
            'name' => $query->orderBy('full_name'),
            'verified' => $query->orderByDesc('is_verified')->latest(),
            default => $query->latest(),
        };

        $providers = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => ServiceProvider::count(),
            'approved' => ServiceProvider::where('status', 'approved')->count(),
            'pending' => ServiceProvider::where('status', 'pending')->count(),
            'rejected' => ServiceProvider::where('status', 'rejected')->count(),
            'suspended' => ServiceProvider::where('status', 'suspended')->count(),
        ];

        return view('backend.service-providers.index', compact('providers', 'stats'));
    }

    public function show(ServiceProvider $service_provider)
    {
        $service_provider->load('categories');
        return view('backend.service-providers.show', ['provider' => $service_provider]);
    }

    public function create()
    {
        $categories = ServiceCategory::active()->get();
        return view('backend.service-providers.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:service_providers,email',
            'password' => ['required', 'string', 'min:8'],
            'status' => 'required|in:pending,approved,rejected,suspended',
            'is_verified' => 'nullable|boolean',
            'profile_photo' => 'nullable|image|max:2048',
            'bio' => 'nullable|string|max:2000',
            'years_experience' => 'nullable|integer|min:0|max:60',
            'city' => 'nullable|string|max:255',
            'operating_areas' => 'nullable|array',
            'operating_areas.*' => 'string|max:255',
            'starting_price' => 'nullable|numeric|min:0',
            'price_unit' => 'nullable|string|max:50',
            'meta' => 'nullable|array',
            'meta.*' => 'nullable',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:service_categories,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_verified'] = (bool) ($validated['is_verified'] ?? false);

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('service-providers', 'public');
        }

        $provider = ServiceProvider::create($validated);
        $provider->categories()->sync($request->input('categories', []));

        return redirect()->route('admin.service-providers.show', $provider)
            ->with('success', 'Service Provider created successfully.');
    }

    public function edit(ServiceProvider $service_provider)
    {
        $service_provider->load('categories');
        $categories = ServiceCategory::active()->get();
        return view('backend.service-providers.edit', ['provider' => $service_provider, 'categories' => $categories]);
    }

    public function update(Request $request, ServiceProvider $service_provider)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:service_providers,email,' . $service_provider->id,
            'password' => ['nullable', 'string', 'min:8'],
            'status' => 'required|in:pending,approved,rejected,suspended',
            'is_verified' => 'nullable|boolean',
            'profile_photo' => 'nullable|image|max:2048',
            'bio' => 'nullable|string|max:2000',
            'years_experience' => 'nullable|integer|min:0|max:60',
            'city' => 'nullable|string|max:255',
            'operating_areas' => 'nullable|array',
            'operating_areas.*' => 'string|max:255',
            'starting_price' => 'nullable|numeric|min:0',
            'price_unit' => 'nullable|string|max:50',
            'meta' => 'nullable|array',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:service_categories,id',
        ]);

        $validated['is_verified'] = (bool) ($validated['is_verified'] ?? false);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('service-providers', 'public');
        }

        $service_provider->update($validated);
        $service_provider->categories()->sync($request->input('categories', []));

        return redirect()->route('admin.service-providers.show', $service_provider)
            ->with('success', 'Service Provider updated successfully.');
    }

    public function destroy(ServiceProvider $service_provider)
    {
        // Detach categories first (pivot cleanup)
        $service_provider->categories()->detach();
        $service_provider->delete();

        return redirect()->route('admin.service-providers.index')
            ->with('success', 'Service Provider deleted successfully.');
    }
}

