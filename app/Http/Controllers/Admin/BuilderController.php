<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Builder;
use Illuminate\Http\Request;

class BuilderController extends Controller
{
    public function index(Request $request)
    {
        $query = Builder::withCount(['projects', 'leads', 'properties']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('name',       'like', "%{$search}%")
                  ->orWhere('email',      'like', "%{$search}%")
                  ->orWhere('phone',      'like', "%{$search}%")
                  ->orWhere('city',       'like', "%{$search}%");
            });
        }

        $builders = $query->latest()->paginate(15)->withQueryString();

        return view('backend.builders.index', compact('builders'));
    }

    public function show($id)
    {
        $builder = Builder::withCount(['projects', 'properties', 'leads'])
            ->findOrFail($id);

        $projects = $builder->projects()
            ->withCount(['properties', 'leads'])
            ->latest()
            ->get();

        $recentLeads = $builder->leads()
            ->with('project')
            ->latest()
            ->limit(10)
            ->get();

        return view('backend.builders.show', compact('builder', 'projects', 'recentLeads'));
    }

    public function destroy($id)
    {
        $builder = Builder::findOrFail($id);
        $name = $builder->company_name ?: $builder->name;
        $builder->delete();

        return redirect()->route('admin.builders.index')
            ->with('success', "Builder \"{$name}\" has been deleted.");
    }

    public function toggleStatus($id)
    {
        $builder = Builder::findOrFail($id);
        $builder->status = ($builder->status === 'active') ? 'blocked' : 'active';
        $builder->save();

        $label = ucfirst($builder->status);
        return back()->with('success', "Builder status updated to {$label}.");
    }

    public function toggleVerified($id)
    {
        $builder = Builder::findOrFail($id);
        $builder->is_verified = !$builder->is_verified;
        $builder->save();

        $label = $builder->is_verified ? 'Verified' : 'Unverified';
        return back()->with('success', "Builder marked as {$label}.");
    }
}
