@extends('backend.layout')
@section('title', 'Builder Details')
@section('content')

<div class="d-flex align-items-center mb-3">
    <a href="{{ route('admin.builders.index') }}" class="btn btn-sm btn-outline-secondary me-3">
        <i data-feather="arrow-left" style="width:14px;height:14px;"></i> Back
    </a>
    <h4 class="mb-0"><i data-feather="layers" class="me-2 text-primary"></i>Builder Details</h4>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-3">
    {{-- Left: Builder Profile --}}
    <div class="col-lg-8">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    @if($builder->logo)
                        <img src="{{ asset('storage/' . $builder->logo) }}" alt="logo"
                             class="rounded me-3" width="72" height="72" style="object-fit:cover;">
                    @else
                        <div class="rounded bg-primary d-flex align-items-center justify-content-center text-white fw-bold me-3"
                             style="width:72px;height:72px;font-size:1.8rem;">
                            {{ strtoupper(substr($builder->company_name ?: $builder->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h5 class="mb-0">{{ $builder->company_name ?: $builder->name }}</h5>
                        <small class="text-muted">{{ $builder->email }}</small><br>
                        <div class="mt-1 d-flex gap-2">
                            @if($builder->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Blocked</span>
                            @endif
                            @if($builder->is_verified)
                                <span class="badge bg-primary">Verified</span>
                            @else
                                <span class="badge bg-light text-dark">Unverified</span>
                            @endif
                        </div>
                    </div>
                    <div class="ms-auto d-flex gap-2">
                        <form action="{{ route('admin.builders.toggle-status', $builder->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $builder->status === 'active' ? 'btn-warning' : 'btn-success' }}">
                                {{ $builder->status === 'active' ? 'Block Builder' : 'Unblock Builder' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.builders.toggle-verified', $builder->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $builder->is_verified ? 'btn-outline-secondary' : 'btn-info' }}">
                                {{ $builder->is_verified ? 'Remove Verified' : 'Mark Verified ✓' }}
                            </button>
                        </form>
                    </div>
                </div>

                <table class="table table-bordered table-sm mb-0">
                    <tr><th style="width:160px;">Contact Name</th><td>{{ $builder->name }}</td></tr>
                    <tr><th>Company</th><td>{{ $builder->company_name ?: '–' }}</td></tr>
                    <tr><th>Phone</th><td>{{ $builder->phone ?: '–' }}</td></tr>
                    <tr><th>Email</th><td>{{ $builder->email }}</td></tr>
                    <tr><th>City</th><td>{{ $builder->city ?: '–' }}</td></tr>
                    <tr><th>Website</th><td>
                        @if($builder->website)
                            <a href="{{ $builder->website }}" target="_blank">{{ $builder->website }}</a>
                        @else –
                        @endif
                    </td></tr>
                    <tr><th>RERA Registration</th><td>{{ $builder->rera_registration ?: '–' }}</td></tr>
                    <tr><th>Established Year</th><td>{{ $builder->established_year ?: '–' }}</td></tr>
                    <tr><th>Delivered Projects</th><td>{{ $builder->total_delivered_projects ?? '–' }}</td></tr>
                    <tr><th>Cities Operating</th><td>{{ $builder->cities_operating ?: '–' }}</td></tr>
                    <tr><th>Rating</th><td>{{ $builder->rating ? $builder->rating . ' / 5' : '–' }}</td></tr>
                    <tr><th>Description</th><td>{{ $builder->description ?: '–' }}</td></tr>
                    <tr><th>Joined</th><td>{{ $builder->created_at?->format('d M Y, H:i') }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Right: Stats --}}
    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body text-center">
                <div class="row g-2">
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <div class="fs-4 fw-bold text-primary">{{ $builder->projects_count }}</div>
                            <small class="text-muted">Projects</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <div class="fs-4 fw-bold text-success">{{ $builder->properties_count }}</div>
                            <small class="text-muted">Properties</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <div class="fs-4 fw-bold text-warning">{{ $builder->leads_count }}</div>
                            <small class="text-muted">Leads</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="card border-danger shadow-sm">
            <div class="card-body">
                <h6 class="text-danger mb-2">Danger Zone</h6>
                <p class="small text-muted mb-2">Deleting this builder will permanently remove all their projects and leads.</p>
                <form action="{{ route('admin.builders.destroy', $builder->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm w-100"
                            onclick="return confirm('Permanently delete this builder and all their data?')">
                        Delete Builder
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Projects Table --}}
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <h6 class="fw-semibold mb-3 border-bottom pb-2">
            <i data-feather="grid" style="width:16px;height:16px;" class="me-1"></i>
            Projects ({{ $projects->count() }})
        </h6>
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>City</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Units</th>
                        <th>Properties</th>
                        <th>Leads</th>
                        <th>Featured</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($projects as $project)
                    <tr>
                        <td>
                            <a href="{{ route('admin.builder-projects.show', $project->id) }}" class="text-decoration-none fw-medium">
                                {{ \Str::limit($project->title, 35) }}
                            </a>
                        </td>
                        <td>{{ $project->city }}</td>
                        <td><span class="badge bg-secondary">{{ $project->project_type }}</span></td>
                        <td><span class="badge {{ $project->status_badge_class }}">{{ $project->status }}</span></td>
                        <td>{{ $project->available_units ?? '–' }}/{{ $project->total_units ?? '–' }}</td>
                        <td>{{ $project->properties_count }}</td>
                        <td>{{ $project->leads_count }}</td>
                        <td>
                            @if($project->is_featured)
                                <span class="badge bg-warning text-dark">Featured</span>
                            @else
                                <span class="text-muted small">–</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $project->created_at?->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted">No projects yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Recent Leads --}}
<div class="card shadow-sm">
    <div class="card-body">
        <h6 class="fw-semibold mb-3 border-bottom pb-2">
            <i data-feather="user-plus" style="width:16px;height:16px;" class="me-1"></i>
            Recent Leads ({{ $recentLeads->count() }})
        </h6>
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Project</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($recentLeads as $lead)
                    @php $ltColors = ['general'=>'primary','visit'=>'success','callback'=>'warning','brochure'=>'info','whatsapp'=>'secondary']; @endphp
                    <tr>
                        <td class="fw-medium">{{ $lead->name }}</td>
                        <td>{{ $lead->phone }}</td>
                        <td>{{ $lead->project?->title ?? '–' }}</td>
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
