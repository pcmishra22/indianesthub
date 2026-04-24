<?php

namespace App\Http\Controllers\Builder;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\BuilderLead;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $builder = Auth::guard('builder')->user();

        // Project stats
        $totalProjects  = $builder->projects()->count();
        $activeProjects = $builder->projects()
            ->whereIn('status', ['Upcoming', 'Under Construction'])
            ->count();

        // Property/unit stats across all projects
        $totalProperties = Property::where('builder_id', $builder->id)->count();
        $totalViews      = Property::where('builder_id', $builder->id)->sum('views_count');

        // Leads stats
        $totalLeads = BuilderLead::where('builder_id', $builder->id)->count();
        $newLeads   = BuilderLead::where('builder_id', $builder->id)->where('status', 'new')->count();

        // Recent leads (5)
        $recentLeads = BuilderLead::where('builder_id', $builder->id)
            ->with('project:id,title')
            ->latest()
            ->take(5)
            ->get();

        // Recent projects (5, with property count)
        $recentProjects = $builder->projects()
            ->withCount('properties')
            ->latest()
            ->take(5)
            ->get();

        // Recent properties across all builder's projects
        $recentProperties = Property::where('builder_id', $builder->id)
            ->with('builderProject')
            ->latest()
            ->take(5)
            ->get();

        return view('builder.dashboard', compact(
            'builder',
            'totalProjects',
            'activeProjects',
            'totalProperties',
            'totalViews',
            'totalLeads',
            'newLeads',
            'recentLeads',
            'recentProjects',
            'recentProperties'
        ));
    }
}
