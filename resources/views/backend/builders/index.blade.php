@extends('backend.layout')
@section('title', 'Builders')
@section('content')

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
                        <th style="width:200px;">Actions</th>
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
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="{{ route('admin.builders.show', $builder->id) }}"
                                   class="btn btn-sm btn-outline-primary">View</a>

                                {{-- Block / Unblock --}}
                                <form action="{{ route('admin.builders.toggle-status', $builder->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $builder->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                            onclick="return confirm('Toggle status for this builder?')">
                                        {{ $builder->status === 'active' ? 'Block' : 'Unblock' }}
                                    </button>
                                </form>

                                {{-- Verify / Unverify --}}
                                <form action="{{ route('admin.builders.toggle-verified', $builder->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $builder->is_verified ? 'btn-outline-secondary' : 'btn-outline-info' }}">
                                        {{ $builder->is_verified ? 'Unverify' : 'Verify' }}
                                    </button>
                                </form>

                                {{-- Delete --}}
                                <form action="{{ route('admin.builders.destroy', $builder->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this builder? All their projects and leads will also be deleted.')">
                                        Delete
                                    </button>
                                </form>
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
