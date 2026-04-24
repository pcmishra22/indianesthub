<?php

namespace App\Http\Controllers\Builder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $builder = Auth::guard('builder')->user();
        return view('builder.profile', compact('builder'));
    }

    public function update(Request $request)
    {
        $builder = Auth::guard('builder')->user();

        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'company_name'     => ['nullable', 'string', 'max:255'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'website'          => ['nullable', 'url', 'max:255'],
            'description'      => ['nullable', 'string'],
            'city'             => ['nullable', 'string', 'max:100'],
            'established_year' => ['nullable', 'string', 'max:10'],
            'logo'             => ['nullable', 'image', 'max:2048'],
            'email'            => ['required', 'email', 'unique:builders,email,' . $builder->id],
        ]);

        if ($request->hasFile('logo')) {
            if ($builder->logo) {
                Storage::disk('public')->delete($builder->logo);
            }
            $validated['logo'] = $request->file('logo')->store(
                "builder/{$builder->id}/logo",
                'public'
            );
        }

        // Handle password change if provided
        if ($request->filled('password')) {
            $request->validate([
                'current_password' => ['required'],
                'password'         => ['required', 'min:8', 'confirmed'],
            ]);

            if (!Hash::check($request->current_password, $builder->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            $validated['password'] = Hash::make($request->password);
        }

        $builder->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }
}
