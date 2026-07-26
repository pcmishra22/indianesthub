<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogPost;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = BlogPost::latest()->paginate(15);
        return view('backend.blogs.index', compact('blogs'));
    }

    public function create()
    {
        $blog = null;
        return view('backend.blog.create', compact('blog'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:blog_posts,slug',
            'excerpt'           => 'nullable|string|max:500',
            'content'           => 'required|string',
            'featured_image'    => 'nullable|image|max:4096',
            'category'          => 'nullable|string|max:100',
            'status'            => 'required|in:draft,published',
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:500',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);
        $validated['author_id'] = auth('admin')->id();
        $validated['published_at'] = $validated['status'] === 'published' ? now() : null;

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        BlogPost::create($validated);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post created successfully.');
    }

    public function show($id)
    {
        $blog = BlogPost::findOrFail($id);
        return view('backend.blogs.show', compact('blog'));
    }

    public function edit($id)
    {
        $blog = BlogPost::findOrFail($id);
        return view('backend.blogs.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $blog = BlogPost::findOrFail($id);

        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:blog_posts,slug,' . $blog->id,
            'excerpt'           => 'nullable|string|max:500',
            'content'           => 'required|string',
            'featured_image'    => 'nullable|image|max:4096',
            'category'          => 'nullable|string|max:100',
            'status'            => 'required|in:draft,published',
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:500',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);
        if ($validated['status'] === 'published' && !$blog->published_at) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        $blog->update($validated);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy($id)
    {
        $blog = BlogPost::findOrFail($id);
        $blog->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Blog post deleted successfully.');
    }
}
