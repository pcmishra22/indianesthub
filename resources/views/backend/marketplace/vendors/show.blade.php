@extends('backend.layout')
@section('title', $vendor->business_name)
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <h4 class="mb-0"><i class="fas fa-store me-2 text-primary"></i>{{ $vendor->business_name }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.marketplace.vendors.edit', $vendor) }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <form method="POST" action="{{ route('admin.marketplace.vendors.toggle-verified', $vendor) }}" class="d-inline">
            @csrf
            <button class="btn btn-sm {{ $vendor->is_verified ? 'btn-success' : 'btn-outline-success' }}">
                <i class="bi bi-patch-check-fill me-1"></i>
                {{ $vendor->is_verified ? 'Verified' : 'Mark Verified' }}
            </button>
        </form>
        <form method="POST" action="{{ route('admin.marketplace.vendors.toggle-active', $vendor) }}" class="d-inline">
            @csrf
            <button class="btn btn-sm {{ $vendor->is_active ? 'btn-warning' : 'btn-outline-secondary' }}">
                {{ $vendor->is_active ? 'Deactivate' : 'Activate' }}
            </button>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 text-center">
            <div class="fw-bold fs-4 text-primary">{{ $vendor->products->count() }}</div>
            <div class="small text-muted">Products</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 text-center">
            <div class="fw-bold fs-4 text-success">{{ $vendor->leads->count() }}</div>
            <div class="small text-muted">Leads</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 text-center">
            <div class="fw-bold fs-4 text-info">{{ number_format($vendor->commission_pct, 1) }}%</div>
            <div class="small text-muted">Commission</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 text-center">
            <div class="fw-bold fs-4 {{ $vendor->is_active ? 'text-success' : 'text-secondary' }}">
                {{ $vendor->is_active ? 'Active' : 'Inactive' }}
            </div>
            <div class="small text-muted">Status</div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <h6 class="text-muted text-uppercase small mb-3">Vendor details</h6>
        <div class="row g-3">
            <div class="col-md-4"><strong>Owner:</strong> {{ $vendor->owner_name ?? '—' }}</div>
            <div class="col-md-4"><strong>Phone:</strong> <a href="tel:{{ $vendor->phone }}">{{ $vendor->phone }}</a></div>
            <div class="col-md-4"><strong>WhatsApp:</strong> <a href="{{ $vendor->whatsapp_link }}" target="_blank">Open chat</a></div>
            <div class="col-md-4"><strong>Email:</strong> {{ $vendor->email ?? '—' }}</div>
            <div class="col-md-4"><strong>City:</strong> {{ $vendor->city ?? '—' }}</div>
            <div class="col-md-4"><strong>Area:</strong> {{ $vendor->area ?? '—' }}</div>
            @if($vendor->address)
            <div class="col-12"><strong>Address:</strong> {{ $vendor->address }}</div>
            @endif
            @if($vendor->description)
            <div class="col-12"><strong>About:</strong> {{ $vendor->description }}</div>
            @endif
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>Products ({{ $vendor->products->count() }})</strong></div>
    <ul class="list-group list-group-flush">
        @forelse($vendor->products as $p)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold">{{ $p->name }}</div>
                    <small class="text-muted">{{ $p->category?->name }} · {{ $p->price_label }}</small>
                </div>
                <span class="badge bg-light text-dark">{{ $p->leads_count }} leads</span>
            </li>
        @empty
            <li class="list-group-item text-center text-muted">No products yet.</li>
        @endforelse
    </ul>
</div>
@endsection
