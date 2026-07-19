@extends('backend.layout')
@section('title', 'Marketplace Leads')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <h4 class="mb-0"><i class="fas fa-shopping-bag me-2 text-primary"></i>Marketplace Leads</h4>
    <span class="text-muted small">Manual commission tracking. Mark each lead won/lost after vendor confirms.</span>
</div>

<div class="row g-3 mb-4">
    @foreach([
        ['new',        'New',        'danger',   'fas fa-bell'],
        ['contacted',  'Contacted',  'warning',  'fas fa-phone'],
        ['won',        'Won',        'success',  'fas fa-check-circle'],
        ['lost',       'Lost',       'secondary','fas fa-times-circle'],
    ] as [$key, $label, $color, $icon])
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3" style="border-left:3px solid var(--bs-{{ $color }})!important;">
            <div class="fw-bold fs-3 text-{{ $color }}">{{ $stats[$key] ?? 0 }}</div>
            <div class="small text-muted"><i class="{{ $icon }} me-1"></i>{{ $label }}</div>
        </div>
    </div>
    @endforeach
    <div class="col-12 col-md-12">
        <div class="card border-0 shadow-sm text-center py-3" style="border-left:3px solid #16a34a!important;background:linear-gradient(90deg,#f0fdf4,#fff);">
            <div class="fw-bold fs-3 text-success">₹{{ number_format($stats['commission'] ?? 0) }}</div>
            <div class="small text-muted"><i class="fas fa-coins me-1"></i>Commission collected to date</div>
        </div>
    </div>
</div>

<form method="GET" action="{{ route('admin.marketplace.leads.index') }}" class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search by name, phone, email…">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All status</option>
                    @foreach(\App\Models\MarketplaceLead::STATUSES as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-sm btn-primary w-100"><i class="fas fa-filter me-1"></i>Filter</button>
            </div>
        </div>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Customer</th>
                    <th>Vendor</th>
                    <th>Product</th>
                    <th>Property</th>
                    <th>BHK / Windows</th>
                    <th>Status</th>
                    <th>Commission</th>
                    <th>When</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                <tr class="{{ $lead->status === 'new' ? 'table-warning' : '' }}">
                    <td>
                        <div class="fw-semibold">{{ $lead->name }}</div>
                        <small class="text-muted">
                            <a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a>
                            @if($lead->email) · <a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a>@endif
                        </small>
                    </td>
                    <td>{{ $lead->vendor?->business_name }}</td>
                    <td>
                        @if($lead->product)
                            <a href="{{ route('admin.marketplace.products.show', $lead->product) }}">{{ $lead->product->name }}</a>
                        @else
                            <span class="text-muted small">General</span>
                        @endif
                    </td>
                    <td>
                        @if($lead->property)
                            <a href="{{ route('property-details', $lead->property) }}" target="_blank" title="View property">
                                #{{ $lead->property_id }}
                            </a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        {{ $lead->bhk_type ?? '—' }}
                        @if($lead->window_count)
                            <small class="text-muted">· {{ $lead->window_count }} windows</small>
                        @endif
                    </td>
                    <td>
                        @php
                            $badge = ['new' => 'danger', 'contacted' => 'warning', 'won' => 'success', 'lost' => 'secondary'][$lead->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ ucfirst($lead->status) }}</span>
                    </td>
                    <td>
                        @if($lead->commission_amount)
                            <span class="fw-semibold">₹{{ number_format($lead->commission_amount, 0) }}</span>
                            @if($lead->commission_collected)
                                <i class="bi bi-check-circle-fill text-success" title="Collected"></i>
                            @else
                                <i class="bi bi-clock-history text-warning" title="Pending collection"></i>
                            @endif
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td><small class="text-muted">{{ $lead->created_at->diffForHumans() }}</small></td>
                    <td class="text-end">
                        <a href="{{ route('admin.marketplace.leads.show', $lead) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                        @if($lead->vendor)
                        <form method="POST" action="{{ route('admin.marketplace.leads.nudge', $lead) }}" class="d-inline" title="Nudge vendor via WhatsApp">
                            @csrf
                            <button class="btn btn-sm btn-outline-success"><i class="bi bi-whatsapp"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                    No leads yet. Once users click "Get Free Quote" on property pages, leads will appear here.
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $leads->links() }}</div>
</div>
@endsection
