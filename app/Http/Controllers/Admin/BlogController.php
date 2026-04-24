<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
    public function index() {
        $blogs = Blog::all();
        return view('backend.blogs.index', compact('blogs'));
    }
    public function create() { return view('backend.blog.create'); }
    public function store(Request $request) { /* store logic */ return back(); }
    public function show($id) {
        $blog = Blog::findOrFail($id);
        return view('backend.blogs.show', compact('blog'));
    }
    public function edit($id) {
        $blog = Blog::findOrFail($id);
        return view('backend.blogs.edit', compact('blog'));
    }
    public function update(Request $request, $id) { /* update logic */ return back(); }
    public function destroy($id) { /* delete logic */ return back(); }
}
