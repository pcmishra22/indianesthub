@extends('backend.layout')
@section('title', 'Builder Projects')
@section('content')

<style>
    /* Keep the Actions column visible on screen even when the table scrolls
       horizontally (this table has 14 columns and won't fit on most screens). */
    .sticky-action-col {
        position: sticky;
        right: 0;
        background: #fff;
        box-shadow: -2px 0 4px rgba(0,0,0,0.06);
        border-left: 1px solid #e9ecef;
        padding-left: 12px !important;
        z-index: 2;
    }
    thead .sticky-action-col {
        background: #f8f9fa;
        z-index: 3;
    }
    /* `position: sticky` creates its own stacking context, which traps the
       dropdown-menu's z-index inside it — so an open dropdown was being
       painted UNDER the next row's sticky Actions cell (both sitting at the
       same z-index: 2, with the later one in DOM order winning). Bumping the
       z-index of just the cell whose dropdown is currently open (toggled via
       JS below) lifts it above every other row's sticky column so the menu
       is never clipped/overlapped. */
    .sticky-action-col.dropdown-open {
        z-index: 1055;
    }
</style>

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
                        <th>Enabled</th>
                        <th style="width:80px;" class="text-center">Units</th>
                        <th style="width:60px;" class="text-center">Props</th>
                        <th style="width:60px;" class="text-center">Leads</th>
                        <th style="width:80px;" class="text-center">Featured</th>
                        <th>Date</th>
                        <th style="width:110px;" class="text-center sticky-action-col">Actions</th>
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
                        <td>
                            @if($project->is_active)
                                <span class="badge bg-success">Enabled</span>
                            @else
                                <span class="badge bg-danger">Disabled</span>
                            @endif
                        </td>
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
                        <td class="text-center sticky-action-col">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.builder-projects.show', $project->id) }}">
                                            <i data-feather="eye" style="width:14px;height:14px;" class="me-1"></i> View
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.builder-projects.viewers.index', $project->id) }}">
                                            <i data-feather="users" style="width:14px;height:14px;" class="me-1"></i> Viewers
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.builder-projects.toggle-status', $project->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                @if($project->is_active)
                                                    <i data-feather="slash" style="width:14px;height:14px;" class="me-1"></i> Disable (Block)
                                                @else
                                                    <i data-feather="check-circle" style="width:14px;height:14px;" class="me-1"></i> Enable (Unblock)
                                                @endif
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.builder-projects.toggle-featured', $project->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i data-feather="star" style="width:14px;height:14px;" class="me-1"></i>
                                                {{ $project->is_featured ? 'Unfeature' : 'Feature' }}
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.builder-projects.destroy', $project->id) }}" method="POST"
                                              onsubmit="return confirm('Delete this project? Properties and leads will be removed.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i data-feather="trash-2" style="width:14px;height:14px;" class="me-1"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="14" class="text-center text-muted py-4">No builder projects found.</td></tr>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.sticky-action-col .dropdown').forEach(function (dropdown) {
        dropdown.addEventListener('show.bs.dropdown', function () {
            dropdown.closest('.sticky-action-col')?.classList.add('dropdown-open');
        });
        dropdown.addEventListener('hide.bs.dropdown', function () {
            dropdown.closest('.sticky-action-col')?.classList.remove('dropdown-open');
        });
    });
});
</script>

@endsection
