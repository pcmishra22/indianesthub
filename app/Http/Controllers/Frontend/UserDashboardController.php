<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Inquiry;
use App\Models\RecentlyViewed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserDashboardController extends Controller
{
    /**
     * Display the dashboard overview.
     */
    public function index()
    {
        $user = Auth::user();

        $totalWishlist       = Wishlist::where('user_id', $user->id)->count();
        $totalInquiries      = Inquiry::where('email', $user->email)->count();
        $totalRecentlyViewed = RecentlyViewed::where('user_id', $user->id)->count();

        $recentlyViewed = RecentlyViewed::where('user_id', $user->id)
            ->with('property')
            ->orderBy('viewed_at', 'desc')
            ->limit(4)
            ->get();

        $wishlistProperties = Wishlist::where('user_id', $user->id)
            ->with('property')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get()
            ->pluck('property')
            ->filter();

        return view('frontend.user.dashboard', compact(
            'totalWishlist',
            'totalInquiries',
            'totalRecentlyViewed',
            'recentlyViewed',
            'wishlistProperties'
        ));
    }

    /**
     * Show the user profile form.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('frontend.user.profile', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'            => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:password',
            'password'         => 'nullable|confirmed|min:8',
        ]);

        // If password change requested, verify current password
        if (!empty($validated['password'])) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }
            $user->password = bcrypt($validated['password']);
        }

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? $user->phone;
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Display the user's wishlisted properties (paginated).
     */
    public function wishlist()
    {
        $propertyIds = Wishlist::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->pluck('property_id');

        $wishlistProperties = \App\Models\Property::whereIn('id', $propertyIds)
            ->with('images')
            ->paginate(12);

        return view('frontend.user.wishlist', compact('wishlistProperties'));
    }

    /**
     * Display the user's inquiries (paginated, matched by email).
     */
    public function inquiries()
    {
        $inquiries = Inquiry::where('email', Auth::user()->email)
            ->with('property')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('frontend.user.inquiries', compact('inquiries'));
    }

    /**
     * Display the user's recently viewed properties (paginated).
     */
    public function recentlyViewed()
    {
        $recentlyViewed = RecentlyViewed::where('user_id', Auth::id())
            ->with('property.images')
            ->orderBy('viewed_at', 'desc')
            ->paginate(12);

        return view('frontend.user.recently-viewed', compact('recentlyViewed'));
    }
}
