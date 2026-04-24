<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Toggle a property in the authenticated user's wishlist.
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'property_id' => 'required|integer|exists:properties,id',
        ]);

        $userId = Auth::id();
        $propertyId = $request->input('property_id');

        $existing = Wishlist::where('user_id', $userId)
            ->where('property_id', $propertyId)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'removed']);
        }

        Wishlist::create([
            'user_id' => $userId,
            'property_id' => $propertyId,
        ]);

        return response()->json(['status' => 'added']);
    }

    /**
     * Check if a property is saved in the authenticated user's wishlist.
     */
    public function isSaved($propertyId)
    {
        $saved = Wishlist::where('user_id', Auth::id())
            ->where('property_id', $propertyId)
            ->exists();

        return response()->json(['saved' => $saved]);
    }
}
