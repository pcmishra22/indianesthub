@extends('backend.layout')
@section('title', 'Builders')
@section('content')

<style>
    .sticky-action-col {
        position: sticky;
        right: 0;
        background: #fff;
        box-shadow: -2px 0 4px rgba(0,0,0,0.06);
    }
    thead .sticky-action-col {
        background: #f8f9fa;
        z-index: 3;
    }
    tbody .sticky-action-col {
        z-index: 2;
    }
</style>

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0"><i data-feather="layers" class="me-2 text-primary"></i>Builders</h4>
    <span class="badge bg-primary fs-6">{{ $builders->total() }} total</span>
</div>

{{-- Search --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-8">
                <label class="form-label small mb-1">Search</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control"
                           placeholder="Company / name, email, phone, city…"
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i data-feather="search" style="width:14px;height:14px;"></i></button>
                </div>
            </div>
            @if(request()->filled('search'))
            <div class="col-md-2">
                <a href="{{ route('admin.builders.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i data-feather="x" style="width:14px;height:14px;" class="me-1"></i>Clear
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
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
                        <th style="width:60px;">Logo</th>
                        <th>Company / Name</th>
                        <th>City</th>
                        <th>Status</th>
                        <th>Verified</th>
                        <th>Projects</th>
                        <th>Leads</th>
                        <th>Joined</th>
                        <th style="width:110px;" class="text-center sticky-action-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($builders as $builder)
                    <tr>
                        <td class="text-muted small">{{ $builder->id }}</td>
                        <td>
                            @if($builder->logo)
                                <img src="{{ asset('storage/' . $builder->logo) }}" alt="logo"
                                     class="rounded-circle" width="38" height="38" style="object-fit:cover;">
                            @else
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold"
                                     style="width:38px;height:38px;font-size:1rem;">
                                    {{ strtoupper(substr($builder->company_name ?: $builder->name, 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $builder->company_name ?: $builder->name }}</div>
                            <small class="text-muted">{{ $builder->email }}</small>
                        </td>
                        <td>{{ $builder->city ?: '–' }}</td>
                        <td>
                            @if($builder->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Blocked</span>
                            @endif
                        </td>
                        <td>
                            @if($builder->is_verified)
                                <span class="badge bg-primary"><i data-feather="check-circle" style="width:12px;height:12px;" class="me-1"></i>Verified</span>
                            @else
                                <span class="badge bg-light text-dark">Unverified</span>
                            @endif
                        </td>
                        <td><span class="badge bg-info text-dark">{{ $builder->projects_count }}</span></td>
                        <td><span class="badge bg-secondary">{{ $builder->leads_count }}</span></td>
                        <td class="text-muted small">{{ $builder->created_at?->format('d M Y') }}</td>
                        <td class="text-center sticky-action-col">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.builders.show', $builder->id) }}">
                                            <i data-feather="eye" style="width:14px;height:14px;" class="me-1"></i> View
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.builders.viewers.index', $builder->id) }}">
                                            <i data-feather="users" style="width:14px;height:14px;" class="me-1"></i> Viewers
                                        </a>
                                    </li>
                                    <li>
                                        {{-- Block / Unblock --}}
                                        <form action="{{ route('admin.builders.toggle-status', $builder->id) }}" method="POST"
                                              onsubmit="return confirm('Toggle status for this builder?')">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                @if($builder->status === 'active')
                                                    <i data-feather="slash" style="width:14px;height:14px;" class="me-1"></i> Block
                                                @else
                                                    <i data-feather="check-circle" style="width:14px;height:14px;" class="me-1"></i> Unblock
                                                @endif
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        {{-- Verify / Unverify --}}
                                        <form action="{{ route('admin.builders.toggle-verified', $builder->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i data-feather="shield" style="width:14px;height:14px;" class="me-1"></i>
                                                {{ $builder->is_verified ? 'Unverify' : 'Verify' }}
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        {{-- Delete --}}
                                        <form action="{{ route('admin.builders.destroy', $builder->id) }}" method="POST"
                                              onsubmit="return confirm('Delete this builder? All their projects and leads will also be deleted.')">
                                            @csrf
                                            @method('DELETE')
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
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">No builders found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($builders->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">Showing {{ $builders->firstItem() }}–{{ $builders->lastItem() }} of {{ $builders->total() }}</small>
        {{ $builders->links() }}
    </div>
    @endif
</div>

@endsection
