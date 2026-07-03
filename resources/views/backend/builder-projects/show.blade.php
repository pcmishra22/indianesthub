@extends('backend.layout')
@section('title', 'Project Details')
@section('content')

<div class="d-flex align-items-center mb-3">
    <a href="{{ route('admin.builder-projects.index') }}" class="btn btn-sm btn-outline-secondary me-3">
        <i data-feather="arrow-left" style="width:14px;height:14px;"></i> Back
    </a>
    <h4 class="mb-0"><i data-feather="grid" class="me-2 text-primary"></i>Project Details</h4>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Traffic summary + link to viewer list --}}
@php
    $projectViewsTotal = \App\Models\ProjectView::where('builder_project_id', $project->id)->where('event_type', 'page_view')->count();
@endphp
<div class="row g-2 mb-3">
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Project Page Views</div>
                <div class="fs-4 fw-bold text-primary">{{ number_format($projectViewsTotal) }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4 d-flex align-items-stretch">
        <div class="card shadow-sm w-100">
            <div class="card-body d-flex align-items-center">
                <a href="{{ route('admin.builder-projects.viewers.index', $project->id) }}" class="btn btn-sm btn-outline-primary">
                    View all viewers
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Main Details --}}
    <div class="col-lg-8">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="mb-1">{{ $project->title }}</h5>
                        <small class="text-muted">
                            By <a href="{{ route('admin.builders.show', $project->builder_id) }}">
                                {{ $project->builder?->company_name ?? $project->builder?->name ?? 'Unknown' }}
                            </a>
                        </small>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge {{ $project->status_badge_class }} fs-6">{{ $project->status }}</span>
                        @if($project->is_active)
                            <span class="badge bg-success">Enabled</span>
                        @else
                            <span class="badge bg-danger">Disabled</span>
                        @endif
                        @if($project->is_featured)
                            <span class="badge bg-warning text-dark">⭐ Featured</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <form action="{{ route('admin.builder-projects.toggle-status', $project->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $project->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                            {{ $project->is_active ? 'Disable Project' : 'Enable Project' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.builder-projects.toggle-featured', $project->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $project->is_featured ? 'btn-outline-secondary' : 'btn-outline-warning' }}">
                            {{ $project->is_featured ? 'Unfeature' : 'Feature' }}
                        </button>
                    </form>
                </div>

                <table class="table table-bordered table-sm mb-3">
                    <tr>
                        <th style="width:160px;">Project Type</th><td>{{ $project->project_type }}</td>
                        <th style="width:160px;">RERA ID</th><td>{{ $project->rera_id ?: '–' }}</td>
                    </tr>
                    <tr>
                        <th>City</th><td>{{ $project->city }}</td>
                        <th>State</th><td>{{ $project->state ?: '–' }}</td>
                    </tr>
                    <tr>
                        <th>Address</th><td colspan="3">{{ $project->address ?: '–' }}</td>
                    </tr>
                    <tr>
                        <th>Total Units</th><td>{{ $project->total_units ?? '–' }}</td>
                        <th>Available Units</th><td>{{ $project->available_units ?? '–' }}</td>
                    </tr>
                    <tr>
                        <th>Price From</th><td>{{ $project->price_from ? '₹' . number_format($project->price_from) : '–' }}</td>
                        <th>Price To</th><td>{{ $project->price_to ? '₹' . number_format($project->price_to) : '–' }}</td>
                    </tr>
                    <tr>
                        <th>Possession Date</th><td>{{ $project->possession_date?->format('d M Y') ?? '–' }}</td>
                        <th>Created</th><td>{{ $project->created_at?->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Amenities</th><td colspan="3">{{ $project->amenities ?: '–' }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td colspan="3" style="white-space:pre-wrap;">{{ $project->description ?: '–' }}</td>
                    </tr>
                </table>

                <div class="d-flex gap-2">
                    <form action="{{ route('admin.builder-projects.toggle-featured', $project->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $project->is_featured ? 'btn-outline-secondary' : 'btn-warning' }}">
                            {{ $project->is_featured ? 'Remove Featured' : '⭐ Mark as Featured' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Cover Image --}}
        @if($project->cover_image)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-semibold mb-2">Cover Image</h6>
                <img src="{{ asset('storage/' . $project->cover_image) }}" alt="cover"
                     class="img-fluid rounded" style="max-height:280px;object-fit:cover;width:100%;">
            </div>
        </div>
        @endif

        {{-- Gallery --}}
        @if($project->gallery_images && count($project->gallery_images) > 0)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-semibold mb-2">Gallery ({{ count($project->gallery_images) }} images)</h6>
                <div class="row g-2">
                    @foreach($project->gallery_images as $img)
                    <div class="col-4 col-md-3">
                        <img src="{{ asset('storage/' . $img) }}" alt="gallery"
                             class="img-fluid rounded" style="height:90px;object-fit:cover;width:100%;">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Right: Stats + Danger --}}
    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-semibold mb-2">Stats</h6>
                <div class="row g-2 text-center">
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <div class="fs-4 fw-bold text-success">{{ $properties->count() }}</div>
                            <small class="text-muted">Properties</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <div class="fs-4 fw-bold text-warning">{{ $leads->count() }}</div>
                            <small class="text-muted">Leads</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <div class="fs-4 fw-bold text-info">{{ $project->views_count ?? 0 }}</div>
                            <small class="text-muted">Views</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-danger shadow-sm">
            <div class="card-body">
                <h6 class="text-danger mb-2">Danger Zone</h6>
                <p class="small text-muted mb-2">Deleting this project removes all properties and leads under it.</p>
                <form action="{{ route('admin.builder-projects.destroy', $project->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm w-100"
                            onclick="return confirm('Permanently delete this project?')">
                        Delete Project
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Properties Table --}}
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <h6 class="fw-semibold mb-3 border-bottom pb-2">
            <i data-feather="home" style="width:16px;height:16px;" class="me-1"></i>
            Properties ({{ $properties->count() }})
        </h6>
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Title</th><th>BHK</th><th>Type</th><th>Price</th><th>Area</th><th>Status</th></tr>
                </thead>
                <tbody>
                @forelse($properties as $prop)
                    <tr>
                        <td>{{ \Str::limit($prop->title, 35) }}</td>
                        <td>{{ $prop->bhk_type ?: '–' }}</td>
                        <td>{{ $prop->property_type ?: '–' }}</td>
                        <td>{{ $prop->price ? '₹' . number_format($prop->price) : '–' }}</td>
                        <td>{{ $prop->area ? $prop->area . ' sqft' : '–' }}</td>
                        <td><span class="badge bg-{{ $prop->status === 'available' ? 'success' : 'secondary' }}">{{ ucfirst($prop->status ?: 'unknown') }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">No properties yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Leads Table --}}
<div class="card shadow-sm">
    <div class="card-body">
        <h6 class="fw-semibold mb-3 border-bottom pb-2">
            <i data-feather="user-plus" style="width:16px;height:16px;" class="me-1"></i>
            Leads ({{ $leads->count() }})
        </h6>
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Name</th><th>Phone</th><th>Email</th><th>Type</th><th>Status</th><th>Date</th></tr>
                </thead>
                <tbody>
                @forelse($leads as $lead)
                    @php $ltColors = ['general'=>'primary','visit'=>'success','callback'=>'warning','brochure'=>'info','whatsapp'=>'secondary']; @endphp
                    <tr>
                        <td class="fw-medium">{{ $lead->name }}</td>
                        <td>{{ $lead->phone }}</td>
                        <td>{{ $lead->email ?: '–' }}</td>
                        <td><span class="badge bg-{{ $ltColors[$lead->lead_type] ?? 'secondary' }}">{{ ucfirst($lead->lead_type) }}</span></td>
                        <td>
                            @if($lead->status === 'new') <span class="badge bg-info text-dark">New</span>
                            @elseif($lead->status === 'contacted') <span class="badge bg-warning text-dark">Contacted</span>
                            @elseif($lead->status === 'converted') <span class="badge bg-success">Converted</span>
                            @else <span class="badge bg-danger">Lost</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $lead->created_at?->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">No leads yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
