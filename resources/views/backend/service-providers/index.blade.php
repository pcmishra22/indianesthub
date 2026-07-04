@extends('backend.layout')
@section('title', 'Service Providers')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0"><i class="fas fa-user-tie me-2 text-primary"></i>Service Providers</h4>
    <a href="{{ route('admin.service-providers.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Add Provider
    </a>
</div>

<div class="row g-3 mb-4">
    @foreach([
        ['total',    'Total',         'dark',      'fas fa-users'],
        ['approved', 'Approved',     'success',   'fas fa-check-circle'],
        ['pending',  'Pending',      'warning',   'fas fa-clock'],
        ['rejected', 'Rejected',     'danger',     'fas fa-times-circle'],
        ['suspended','Suspended',    'secondary', 'fas fa-ban'],
    ] as [$key, $label, $color, $icon])
    <div class="col-6 col-md">
        <div class="card border-0 shadow-sm text-center py-3" style="border-left:3px solid var(--bs-{{ $color }})!important;">
            <div class="fw-bold fs-4 text-{{ $color }}">{{ $stats[$key] ?? 0 }}</div>
            <div class="small text-muted"><i class="{{ $icon }} me-1"></i>{{ $label }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small mb-1">Status</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    @foreach(['pending','approved','rejected','suspended'] as $s)
                        <option value="{{ $s }}" {{ request('status')===$s ? 'selected':'' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">Verified</label>
                <select name="is_verified" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="1" {{ request('is_verified')==='1' ? 'selected':'' }}>Verified</option>
                    <option value="0" {{ request('is_verified')==='0' ? 'selected':'' }}>Not Verified</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">Sort By</label>
                <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="newest" {{ request('sort','newest')==='newest' ? 'selected':'' }}>Newest</option>
                    <option value="oldest" {{ request('sort')==='oldest' ? 'selected':'' }}>Oldest</option>
                    <option value="name" {{ request('sort')==='name' ? 'selected':'' }}>Name A→Z</option>
                    <option value="verified" {{ request('sort')==='verified' ? 'selected':'' }}>Verified First</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label small mb-1">Search</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Name, business, email, phone, city…" value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
            @if(request()->anyFilled(['status','is_verified','sort','search']))
            <div class="col-md-12">
                <a href="{{ route('admin.service-providers.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="fas fa-times me-1"></i> Clear
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:.85rem;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Provider</th>
                        <th>City</th>
                        <th>Categories</th>
                        <th>Verified</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($providers as $provider)
                    <tr>
                        <td class="text-muted">{{ $provider->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($provider->profile_photo)
                                    <img src="{{ asset('storage/'.$provider->profile_photo) }}" class="rounded-circle" style="width:34px;height:34px;object-fit:cover;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width:34px;height:34px;font-size:.8rem;flex-shrink:0;">
                                        {{ strtoupper(substr($provider->business_name ?? $provider->full_name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $provider->business_name ?: $provider->full_name }}</div>
                                    <div class="text-muted small">{{ $provider->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $provider->city ?: '—' }}</td>
                        <td>
                            <span class="fw-semibold">{{ $provider->categories_count ?? 0 }}</span>
                            <span class="text-muted small">cats</span>
                        </td>
                        <td>
                            @if($provider->is_verified)
                                <span class="badge bg-success">Verified</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $sc = match($provider->status ?? 'pending') {
                                    'approved' => 'success',
                                    'pending' => 'warning',
                                    'rejected' => 'danger',
                                    'suspended' => 'secondary',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $sc }}">{{ ucfirst($provider->status ?? 'pending') }}</span>
                        </td>
                        <td class="text-muted small">{{ $provider->created_at?->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.service-providers.show', $provider) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.service-providers.edit', $provider) }}" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{ route('admin.service-providers.destroy', $provider) }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete this provider?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="fas fa-user-tie fa-2x mb-2 d-block opacity-25"></i>
                            No service providers found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $providers->links() }}</div>
@endsection

