<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ServiceProviderDashboardController extends Controller
{
    public function index()
    {
        $provider = Auth::guard('service_provider')->user()->load('categories');

        $leadsReceived = $provider->leads()->count();
        $reviewsCount = $provider->reviews_count;
        $averageRating = $provider->average_rating;
        $recentReviews = $provider->approvedReviews()->with('user')->latest()->take(5)->get();
        $portfolioCount = $provider->portfolios()->count();

        return view('service-provider.dashboard', compact(
            'provider', 'leadsReceived', 'reviewsCount', 'averageRating', 'recentReviews', 'portfolioCount'
        ));
    }

    public function editProfile()
    {
        $provider   = Auth::guard('service_provider')->user()->load('categories');
        $categories = ServiceCategory::active()->get();
        return view('service-provider.profile-edit', compact('provider', 'categories'));
    }

    public function updateProfile(Request $request)
    {
        $provider = Auth::guard('service_provider')->user();

        $request->validate([
            'full_name'        => 'required|string|max:255',
            'business_name'    => 'nullable|string|max:255',
            'phone'            => 'required|string|max:20',
            'city'             => 'required|string|max:255',
            'bio'               => 'nullable|string|max:2000',
            'years_experience' => 'nullable|integer|min:0|max:60',
            'starting_price'   => 'nullable|numeric|min:0',
            'price_unit'       => 'nullable|string|max:50',
            'operating_areas'  => 'nullable|array',
            'categories'       => 'required|array|min:1',
            'categories.*'     => 'exists:service_categories,id',
            'profile_photo'    => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'full_name', 'business_name', 'phone', 'city',
            'bio', 'years_experience', 'starting_price', 'price_unit',
        ]);

        $data['operating_areas'] = $request->input('operating_areas', []);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('service-providers', 'public');
            $data['profile_photo'] = $path;
        }

        $provider->update($data);
        $provider->categories()->sync($request->categories);

        return redirect()->route('service-provider.dashboard')
            ->with('status', 'Profile updated successfully.');
    }
}
