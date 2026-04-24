@extends('backend.layout')
@section('title', 'Dealer Subscriptions')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0"><i class="fas fa-crown me-2 text-warning"></i>Dealer Subscriptions</h4>
    <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Add Subscription
    </a>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    @foreach([
        ['total',       'Total Plans',    'dark',    'fas fa-layer-group'],
        ['active',      'Active',         'success', 'fas fa-check-circle'],
        ['expired',     'Expired',        'danger',  'fas fa-times-circle'],
        ['cancelled',   'Cancelled',      'secondary','fas fa-ban'],
        ['expiring_soon','Expiring Soon', 'warning', 'fas fa-exclamation-triangle'],
    ] as [$key, $label, $color, $icon])
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center py-3" style="border-left:3px solid var(--bs-{{ $color }})!important;">
            <div class="fw-bold fs-4 text-{{ $color }}">{{ $stats[$key] }}</div>
            <div class="small text-muted">{{ $label }}</div>
        </div>
    </div>
    @endforeach
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center py-3" style="border-left:3px solid #16a34a!important;">
            <div class="fw-bold fs-4 text-success">₹{{ number_format($stats['revenue']) }}</div>
            <div class="small text-muted">Active Revenue</div>
        </div>
    </div>
</div>

{{-- Plan breakdown --}}
<div class="row g-3 mb-4">
    @foreach([['basic','Basic','secondary'],['premium','Premium','primary'],['enterprise','Enterprise','warning']] as [$plan,$label,$color])
    <div class="col-md-4">
        <div class="card border-0 shadow-sm py-3 px-4 d-flex flex-row align-items-center justify-content-between">
            <div>
                <div class="text-muted small">{{ $label }} Plans (Active)</div>
                <div class="fw-bold fs-3">{{ $stats[$plan] }}</div>
            </div>
            <span class="badge bg-{{ $color }} px-3 py-2" style="font-size:.85rem;">{{ ucfirst($plan) }}</span>
        </div>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small mb-1">Status</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="active"    {{ request('status')==='active'    ? 'selected':'' }}>Active</option>
                    <option value="expired"   {{ request('status')==='expired'   ? 'selected':'' }}>Expired</option>
                    <option value="cancelled" {{ request('status')==='cancelled' ? 'selected':'' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">Plan</label>
                <select name="plan" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Plans</option>
                    <option value="basic"      {{ request('plan')==='basic'      ? 'selected':'' }}>Basic</option>
                    <option value="premium"    {{ request('plan')==='premium'    ? 'selected':'' }}>Premium</option>
                    <option value="enterprise" {{ request('plan')==='enterprise' ? 'selected':'' }}>Enterprise</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label small mb-1">Search Dealer</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Dealer name or email"
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
            @if(request()->anyFilled(['status','plan','search']))
            <div class="col-md-2">
                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="fas fa-times me-1"></i> Clear
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

{{-- Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" style="font-size:.85rem;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Dealer</th>
                        <th>Plan</th>
                        <th>Price</th>
                        <th>Limits</th>
                        <th>Period</th>
                        <th>Days Left</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($subscriptions as $sub)
                <tr>
                    <td class="text-muted">{{ $sub->id }}</td>
                    <td>
                        @if($sub->dealer)
                        <div class="fw-semibold">{{ $sub->dealer->name }}</div>
                        <div class="text-muted small">{{ $sub->dealer->email }}</div>
                        @else
                        <span class="text-muted small">Dealer #{{ $sub->property_dealer_id }}</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ $sub->planBadge() }} px-3">{{ ucfirst($sub->plan) }}</span>
                    </td>
                    <td class="fw-semibold">₹{{ number_format($sub->price) }}</td>
                    <td>
                        <div class="small"><i class="fas fa-home text-muted me-1"></i>{{ $sub->property_limit }} props</div>
                        <div class="small"><i class="fas fa-star text-warning me-1"></i>{{ $sub->featured_limit }} featured</div>
                    </td>
                    <td class="small">
                        <div>{{ $sub->start_date?->format('d M Y') }}</div>
                        <div class="text-muted">→ {{ $sub->end_date?->format('d M Y') }}</div>
                    </td>
                    <td>
                        @if($sub->status === 'active')
                        @php $days = $sub->daysLeft(); @endphp
                        <span class="fw-semibold {{ $days <= 7 ? 'text-danger' : ($days <= 30 ? 'text-warning' : 'text-success') }}">
                            {{ $days }} days
                        </span>
                        @else
                        <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $sbadge = match($sub->status) { 'active'=>'success','expired'=>'danger',default=>'secondary' };
                        @endphp
                        <span class="badge bg-{{ $sbadge }}">{{ ucfirst($sub->status) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.subscriptions.edit', $sub) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                        <form method="POST" action="{{ route('admin.subscriptions.destroy', $sub) }}"
                              onsubmit="return confirm('Delete subscription?')" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Del</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="fas fa-crown fa-2x mb-2 d-block opacity-25"></i>
                        No subscriptions found.
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $subscriptions->links() }}</div>

@endsection
