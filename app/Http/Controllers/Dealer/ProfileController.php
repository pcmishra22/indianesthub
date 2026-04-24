<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $dealer = Auth::guard('dealer')->user();
        return view('dealer.profile-edit', compact('dealer'));
    }

    public function update(Request $request)
    {
        $dealer = Auth::guard('dealer')->user();

        $validated = $request->validate([
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'phone'         => 'required|string|max:20',
            'company_name'  => 'required|string|max:200',
            'bio'           => 'nullable|string|max:1000',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'current_password'  => 'nullable|string',
            'new_password'      => 'nullable|string|min:8|confirmed',
        ]);

        // Only update safe profile fields — email and status are admin-only
        $dealer->first_name   = $validated['first_name'];
        $dealer->last_name    = $validated['last_name'];
        $dealer->phone        = $validated['phone'];
        $dealer->company_name = $validated['company_name'];
        $dealer->bio          = $validated['bio'] ?? $dealer->bio;

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('dealer-photos', 'public');
            $dealer->profile_photo = $path;
        }

        // Handle password change — requires current password verification
        if ($request->filled('new_password')) {
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $dealer->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $dealer->password = Hash::make($validated['new_password']);
        }

        $dealer->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
