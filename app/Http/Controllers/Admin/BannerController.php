<?php
namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::latest()->paginate(20);
        return view('backend.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('backend.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'image'  => 'required|image|max:2048',
            'status' => 'nullable|in:active,inactive',
        ]);
        $path = $request->file('image')->store('banners', 'public');
        $status = $request->status ?? 'active';
        $statusBool = $status === 'inactive' ? false : true;

        Banner::create([
            'title'  => $request->title,
            'image'  => $path,
            'status' => $statusBool,
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('backend.banners.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        $request->validate([
            'title'  => 'required|string|max:255',
            'image'  => 'nullable|image|max:2048',
            'status' => 'nullable|in:active,inactive',
        ]);
        $status = $request->status ?? ($banner->status ? 'active' : 'inactive');
        $statusBool = $status === 'inactive' ? false : true;

        $data = ['title' => $request->title, 'status' => $statusBool];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }
        $banner->update($data);
        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }
}
