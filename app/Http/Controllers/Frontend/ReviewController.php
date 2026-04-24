<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews.
     */
    public function index()
    {
        $reviews = \App\Models\Review::with('property', 'user')->latest()->paginate(20);
        return view('frontend.reviews.index', compact('reviews'));
    }

    /**
     * Store a new review for a property.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'nullable|integer|exists:properties,id',
            'agent_id' => 'nullable|integer|exists:agents,id',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'property_id' => $validated['property_id'] ?? null,
            'agent_id' => $validated['agent_id'] ?? null,
            'rating' => $validated['rating'],
            'review_text' => $validated['review_text'],
        ]);

        return redirect()->back()->with('success', 'Your review has been submitted successfully!');
    }
}
