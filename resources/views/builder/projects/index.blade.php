@extends('builder.layout')

@section('title', 'My Projects')

@section('content')
<div class="container-fluid p-0">

    <div class="d-flex align-items-center justify-content-between px-3 pt-3 mb-3 flex-wrap gap-2">
        <h1 class="h3 mb-0 fw-bold">My Projects</h1>
        <a href="{{ route('builder.projects.create') }}" class="btn btn-primary">
            <i data-feather="plus" style="width:16px;height:16px;"></i> Add New Project
        </a>
    </div>

    <div class="px-3">
        @if($projects->count())
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Project</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Units</th>
                            <th>Price Range</th>
                            <th>Status</th>
                            <th>Units Listed</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $i => $project)
                        <tr>
                            <td class="text-muted">{{ $projects->firstItem() + $i }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($project->cover_image)
                                        <img src="{{ asset('storage/' . $project->cover_image) }}" alt=""
                                             style="width:40px;height:40px;object-fit:cover;border-radius:6px;">
                                    @else
                                        <div style="width:40px;height:40px;border-radius:6px;background:#e9ecef;display:flex;align-items:center;justify-content:center;">
                                            <i data-feather="layers" style="width:18px;height:18px;color:#6c757d;"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $project->title }}</strong>
                                        @if($project->is_featured)
                                            <span class="badge bg-warning text-dark ms-1" style="font-size:.65rem;">Featured</span>
                                        @endif
                                        @if($project->rera_id)
                                            <br><small class="text-muted">RERA: {{ $project->rera_id }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $project->project_type }}</td>
                            <td>
                                {{ $project->city }}{{ $project->city && $project->state ? ', ' : '' }}{{ $project->state }}
                            </td>
                            <td>
                                {{ $project->available_units ?? '—' }}
                                @if($project->total_units)
                                    <small class="text-muted">/ {{ $project->total_units }}</small>
                                @endif
                            </td>
                            <td>
                                @if($project->price_from || $project->price_to)
                                    <small>
                                        @if($project->price_from) ₹{{ number_format($project->price_from) }} @endif
                                        @if($project->price_from && $project->price_to) – @endif
                                        @if($project->price_to) ₹{{ number_format($project->price_to) }} @endif
                                    </small>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $project->status_badge_class }}">{{ $project->status }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $project->properties_count }}</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('builder.projects.show', $project) }}"
                                       class="btn btn-sm btn-outline-primary" title="View">
                                        <i data-feather="eye" style="width:14px;height:14px;"></i>
                                    </a>
                                    <a href="{{ route('builder.projects.edit', $project) }}"
                                       class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i data-feather="edit-2" style="width:14px;height:14px;"></i>
                                    </a>
                                    <form action="{{ route('builder.projects.destroy', $project) }}" method="POST"
                                          onsubmit="return confirm('Delete this project and all its units?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i data-feather="trash-2" style="width:14px;height:14px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($projects->hasPages())
            <div class="card-footer">{{ $projects->links() }}</div>
            @endif
        </div>
        @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i data-feather="layers" style="width:56px;height:56px;color:#dee2e6;"></i>
                <h4 class="mt-3 mb-1 text-muted">No Projects Yet</h4>
                <p class="text-muted mb-4">Start by adding your first upcoming project.</p>
                <a href="{{ route('builder.projects.create') }}" class="btn btn-primary">
                    <i data-feather="plus" style="width:16px;height:16px;"></i> Add First Project
                </a>
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
