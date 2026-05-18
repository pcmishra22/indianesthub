<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Blog listing page — /blog
     */
    public function index(Request $request)
    {
        $query = BlogPost::published()->latest('published_at');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('excerpt', 'like', "%{$s}%")
                  ->orWhere('content', 'like', "%{$s}%");
            });
        }

        $posts      = $query->paginate(9)->withQueryString();
        $categories = BlogPost::published()->distinct()->pluck('category')->filter()->sort();

        return view('frontend.blog', compact('posts', 'categories'));
    }

    /**
     * Blog details — /blog/{blog:slug}
     */
    public function show(BlogPost $blog)
    {
        abort_if(!($blog->is_published && !empty($blog->published_at) && $blog->published_at <= now()), 404);

        // Increment views
        $blog->increment('views_count');

        // Related posts (same category, exclude current)
        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $blog->id)
            ->where('category', $blog->category)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('frontend.blog-show', compact('blog', 'relatedPosts'));
    }

    /**
     * Legacy route: /blog-details (fallback)
     */
    public function details()
    {
        return view('frontend.blog-details');
    }
}
