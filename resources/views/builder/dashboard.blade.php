@extends('builder.layout')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid p-0">

    {{-- Page Header --}}
    <div class="mb-3 px-3 pt-3">
        <h1 class="h3 d-inline align-middle fw-bold">Dashboard</h1>
        <span class="text-muted ms-2">Welcome, {{ $builder->display_name }}!</span>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 px-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Total Projects</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-primary">
                                <i class="align-middle" data-feather="layers"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">{{ $totalProjects }}</h1>
                    <div class="mb-0">
                        <span class="badge badge-soft-primary me-2">
                            <i class="mdi mdi-arrow-bottom-right"></i> {{ $activeProjects }}
                        </span>
                        <span class="text-muted">Active / In Progress</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Active Projects</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-warning">
                                <i class="align-middle" data-feather="trending-up"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">{{ $activeProjects }}</h1>
                    <div class="mb-0">
                        <span class="text-muted">Upcoming + Under Construction</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Total Units</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-success">
                                <i class="align-middle" data-feather="home"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">{{ $totalProperties }}</h1>
                    <div class="mb-0">
                        <span class="text-muted">Flats / Villas / Plots listed</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Leads</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-danger">
                                <i class="align-middle" data-feather="inbox"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">{{ $totalLeads }}</h1>
                    <div class="mb-0">
                        @if($newLeads > 0)
                        <span class="badge badge-soft-danger me-2">{{ $newLeads }} New</span>
                        @endif
                        <span class="text-muted">Total enquiries received</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 px-3">

        {{-- Recent Projects --}}
        <div class="col-xl-6">
            <div class="card flex-fill">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Projects</h5>
                    <a href="{{ route('builder.projects.index') }}" class="btn btn-sm btn-primary">
                        <i data-feather="plus" style="width:14px;height:14px;"></i> New Project
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Units</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentProjects as $project)
                            <tr>
                                <td>
                                    <strong>{{ $project->title }}</strong>
                                    <br><small class="text-muted">{{ $project->city }}{{ $project->city && $project->state ? ', ' : '' }}{{ $project->state }}</small>
                                </td>
                                <td>{{ $project->project_type }}</td>
                                <td>
                                    <span class="badge {{ $project->status_badge_class }}">
                                        {{ $project->status }}
                                    </span>
                                </td>
                                <td>{{ $project->properties_count }}</td>
                                <td>
                                    <a href="{{ route('builder.projects.show', $project) }}" class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No projects yet. <a href="{{ route('builder.projects.create') }}">Add your first project</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Recent Properties/Units --}}
        <div class="col-xl-6">
            <div class="card flex-fill">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recently Added Units</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Unit / Property</th>
                                <th>Project</th>
                                <th>Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentProperties as $property)
                            <tr>
                                <td>
                                    <strong>{{ Str::limit($property->title, 30) }}</strong>
                                    <br><small class="text-muted">{{ $property->bhk_type }} {{ $property->property_type }}</small>
                                </td>
                                <td>
                                    @if($property->builderProject)
                                        <a href="{{ route('builder.projects.show', $property->builderProject) }}">
                                            {{ Str::limit($property->builderProject->title, 25) }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($property->price)
                                        ₹{{ number_format($property->price) }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $property->status === 'Available' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $property->status ?? 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No units listed yet. Add units to your projects.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    {{-- Recent Leads --}}
    @if($recentLeads->count())
    <div class="row g-3 px-3 mt-1">
        <div class="col-12">
            <div class="card flex-fill">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        Recent Leads
                        @if($newLeads > 0)
                        <span class="badge bg-danger ms-2" style="font-size:.7rem;">{{ $newLeads }} New</span>
                        @endif
                    </h5>
                    <a href="{{ route('builder.leads.index') }}" class="btn btn-sm btn-outline-primary">
                        View All Leads
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size:.875rem;">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Project</th>
                                <th>Type</th>
                                <th>Received</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentLeads as $lead)
                            <tr>
                                <td><strong>{{ $lead->name }}</strong></td>
                                <td>
                                    <a href="tel:{{ $lead->phone }}" class="text-decoration-none">
                                        {{ $lead->phone }}
                                    </a>
                                </td>
                                <td>
                                    @if($lead->project)
                                        <a href="{{ route('builder.projects.show', $lead->builder_project_id) }}"
                                           style="font-size:.82rem;text-decoration:none;">
                                            {{ Str::limit($lead->project->title, 25) }}
                                        </a>
                                    @else
                                        <span class="text-muted">General</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-capitalize" style="font-size:.82rem;">{{ $lead->lead_type }}</span>
                                </td>
                                <td style="font-size:.78rem;color:#94a3b8;">
                                    {{ $lead->created_at->diffForHumans() }}
                                </td>
                                <td>
                                    @php
                                      $badge = match($lead->status) {
                                        'new'       => 'bg-primary',
                                        'contacted' => 'bg-warning',
                                        'converted' => 'bg-success',
                                        default     => 'bg-secondary',
                                      };
                                    @endphp
                                    <span class="badge {{ $badge }} text-capitalize">{{ $lead->status }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
