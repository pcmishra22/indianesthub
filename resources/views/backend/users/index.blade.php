@extends('backend.layout')
@section('title', 'All Accounts')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0"><i data-feather="users" class="me-2 text-primary"></i>All Accounts</h4>
    <span class="badge bg-primary fs-6">{{ $users->total() }} total</span>
</div>

{{-- Type summary --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-4 fw-bold">{{ $counts['user'] }}</div>
            <div class="small text-muted">Customers</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-4 fw-bold">{{ $counts['dealer'] }}</div>
            <div class="small text-muted">Agents / Dealers</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-4 fw-bold">{{ $counts['builder'] }}</div>
            <div class="small text-muted">Builders</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-4 fw-bold">{{ $counts['service_provider'] }}</div>
            <div class="small text-muted">Service Providers</div>
        </div>
    </div>
</div>

{{-- Search & type filter --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label small mb-1">Search</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control"
                           placeholder="Name, email, phone, company…"
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i data-feather="search" style="width:14px;height:14px;"></i></button>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Account Type</label>
                <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Types ({{ array_sum($counts) }})</option>
                    <option value="user" {{ request('type') === 'user' ? 'selected' : '' }}>Customers ({{ $counts['user'] }})</option>
                    <option value="dealer" {{ request('type') === 'dealer' ? 'selected' : '' }}>Agents / Dealers ({{ $counts['dealer'] }})</option>
                    <option value="builder" {{ request('type') === 'builder' ? 'selected' : '' }}>Builders ({{ $counts['builder'] }})</option>
                    <option value="service_provider" {{ request('type') === 'service_provider' ? 'selected' : '' }}>Service Providers ({{ $counts['service_provider'] }})</option>
                </select>
            </div>
            @if(request()->filled('search') || request()->filled('type'))
            <div class="col-md-3">
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary w-100">
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
                        <th style="width:140px;">Type</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th style="width:100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($users as $row)
                    <tr>
                        <td>
                            @php
                                $typeColors = [
                                    'user' => 'primary',
                                    'dealer' => 'info',
                                    'builder' => 'dark',
                                    'service_provider' => 'purple',
                                ];
                                $tc = $typeColors[$row['type']] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $tc }}" @if($tc === 'purple') style="background:#7c3aed!important;" @endif>
                                {{ $row['type_label'] }}
                            </span>
                        </td>
                        <td class="fw-semibold">{{ $row['name'] }}</td>
                        <td>{{ $row['email'] }}</td>
                        <td>{{ $row['phone'] }}</td>
                        <td>
                            <span class="badge bg-{{ $row['badge_color'] }}">{{ ucfirst($row['status']) }}</span>
                        </td>
                        <td class="text-muted small">{{ $row['created_at']?->format('d M Y') }}</td>
                        <td>
                            @if($row['view_url'])
                                <a href="{{ $row['view_url'] }}" class="btn btn-sm btn-outline-primary">View</a>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No accounts found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }}</small>
        {{ $users->links() }}
    </div>
    @endif
</div>

<p class="text-muted small mt-3">
    <i data-feather="info" style="width:14px;height:14px;" class="me-1"></i>
    "View" opens each account's own management page (Dealers, Builders, Service Providers, or Users), where you can block, edit, verify or delete it.
</p>

@endsection
