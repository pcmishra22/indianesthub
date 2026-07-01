<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ServiceProviderRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $categories = ServiceCategory::active()->get();
        return view('auth.service-provider-register', compact('categories'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'full_name'      => 'required|string|max:255',
            'business_name'  => 'nullable|string|max:255',
            'email'          => 'required|string|email|max:255|unique:service_providers,email',
            'phone'          => 'required|string|max:20',
            'city'           => 'required|string|max:255',
            'password'       => 'required|string|min:8|confirmed',
            // This is the "I want to sign up as Electrician / Mistry / Interior Designer..." selector.
            // multi-select so a provider can offer more than one trade.
            'categories'     => 'required|array|min:1',
            'categories.*'   => 'exists:service_categories,id',
        ]);

        $provider = ServiceProvider::create([
            'full_name'     => $request->full_name,
            'business_name' => $request->business_name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'city'          => $request->city,
            'password'      => Hash::make($request->password),
            'status'        => 'pending', // admin reviews & approves before going live publicly
        ]);

        $provider->categories()->sync($request->categories);

        Auth::guard('service_provider')->login($provider);

        return redirect()->route('service-provider.dashboard')
            ->with('status', 'Welcome! Your profile is pending review — complete it below while we verify your account.');
    }
}
