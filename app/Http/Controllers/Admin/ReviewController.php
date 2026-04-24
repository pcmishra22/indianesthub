<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'property'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('review_text', 'like', "%$s%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%$s%"));
            });
        }

        $reviews = $query->paginate(20)->withQueryString();

        $stats = [
            'total'    => Review::count(),
            'pending'  => Review::where('status', 'pending')->count(),
            'approved' => Review::where('status', 'approved')->count(),
            'rejected' => Review::where('status', 'rejected')->count(),
            'avg_rating' => round(Review::avg('rating'), 1),
        ];

        return view('backend.reviews.index', compact('reviews', 'stats'));
    }

    public function show(Review $review)
    {
        $review->load(['user', 'property']);
        return view('backend.reviews.show', compact('review'));
    }

    public function approve(Review $review)
    {
        $review->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Review approved and published.');
    }

    public function reject(Review $review)
    {
        $review->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Review rejected.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')
                         ->with('success', 'Review deleted.');
    }
}
