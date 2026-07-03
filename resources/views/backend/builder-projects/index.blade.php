@extends('backend.layout')
@section('title', 'Builder Projects')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0"><i data-feather="grid" class="me-2 text-primary"></i>Builder Projects</h4>
    <span class="badge bg-primary fs-6">{{ $projects->total() }} total</span>
</div>

{{-- Search --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-8">
                <label class="form-label small mb-1">Search</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control"
                           placeholder="Project title, city, builder name…"
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i data-feather="search" style="width:14px;height:14px;"></i></button>
                </div>
            </div>
            @if(request()->filled('search'))
            <div class="col-md-2">
                <a href="{{ route('admin.builder-projects.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i data-feather="x" style="width:14px;height:14px;" class="me-1"></i>Clear
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px;">#</th>
                        <th style="width:70px;">Cover</th>
                        <th>Project Title</th>
                        <th>Builder</th>
                        <th>City</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Units</th>
                        <th>Props</th>
                        <th>Leads</th>
                        <th>Featured</th>
                        <th>Date</th>
                        <th style="width:180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($projects as $project)
                    <tr>
                        <td class="text-muted small">{{ $project->id }}</td>
                        <td>
                            @if($project->cover_image)
                                <img src="{{ asset('storage/' . $project->cover_image) }}" alt="cover"
                                     class="rounded" width="52" height="38" style="object-fit:cover;">
                            @else
                                <div class="rounded bg-secondary d-flex align-items-center justify-content-center text-white"
                                     style="width:52px;height:38px;font-size:0.7rem;">No img</div>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.builder-projects.show', $project->id) }}" class="text-decoration-none fw-medium">
                                {{ \Str::limit($project->title, 32) }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.builders.show', $project->builder_id) }}" class="text-decoration-none text-muted small">
                                {{ $project->builder?->company_name ?? $project->builder?->name ?? '–' }}
                            </a>
                        </td>
                        <td>{{ $project->city }}</td>
                        <td><span class="badge bg-secondary">{{ $project->project_type }}</span></td>
                        <td><span class="badge {{ $project->status_badge_class }}">{{ $project->status }}</span></td>
                        <td class="text-center">
                            <small>{{ $project->available_units ?? '?' }}/{{ $project->total_units ?? '?' }}</small>
                        </td>
                        <td class="text-center">{{ $project->properties_count }}</td>
                        <td class="text-center">{{ $project->leads_count }}</td>
                        <td class="text-center">
                            @if($project->is_featured)
                                <span class="badge bg-warning text-dark">⭐ Yes</span>
                            @else
                                <span class="text-muted small">No</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $project->created_at?->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.builder-projects.show', $project->id) }}"
                                   class="btn btn-sm btn-outline-primary">View</a>

                                <form action="{{ route('admin.builder-projects.toggle-featured', $project->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $project->is_featured ? 'btn-outline-secondary' : 'btn-outline-warning' }}">
                                        {{ $project->is_featured ? 'Unfeature' : 'Feature' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.builder-projects.destroy', $project->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this project? Properties and leads will be removed.')">
                                        Del
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="13" class="text-center text-muted py-4">No builder projects found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($projects->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">Showing {{ $projects->firstItem() }}–{{ $projects->lastItem() }} of {{ $projects->total() }}</small>
        {{ $projects->links() }}
    </div>
    @endif
</div>

@endsection
