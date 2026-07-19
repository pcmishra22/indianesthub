@extends('backend.layout')
@section('title', 'Lead #' . $lead->id)
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <h4 class="mb-0"><i class="fas fa-shopping-bag me-2 text-primary"></i>Lead #{{ $lead->id }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.marketplace.leads.index') }}" class="btn btn-sm btn-light">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        @if($lead->vendor)
        <form method="POST" action="{{ route('admin.marketplace.leads.nudge', $lead) }}" class="d-inline">
            @csrf
            <button class="btn btn-sm btn-outline-success"><i class="bi bi-whatsapp me-1"></i> Nudge Vendor</button>
        </form>
        @endif
        <form method="POST" action="{{ route('admin.marketplace.leads.destroy', $lead) }}" class="d-inline" onsubmit="return confirm('Delete this lead?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
        </form>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white"><strong>Customer</strong></div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-6"><strong>Name:</strong> {{ $lead->name }}</div>
                    <div class="col-md-6"><strong>Phone:</strong> <a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a></div>
                    <div class="col-md-6"><strong>Email:</strong> {{ $lead->email ?? '—' }}</div>
                    <div class="col-md-6"><strong>City:</strong> {{ $lead->city ?? '—' }}</div>
                    <div class="col-md-4"><strong>BHK:</strong> {{ $lead->bhk_type ?? '—' }}</div>
                    <div class="col-md-4"><strong>Windows:</strong> {{ $lead->window_count ?? '—' }}</div>
                    <div class="col-md-4"><strong>Fabric:</strong> {{ $lead->fabric_preference ?? '—' }}</div>
                    @if($lead->notes)
                    <div class="col-12"><strong>Notes:</strong><br>{{ $lead->notes }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white"><strong>Vendor &amp; Product</strong></div>
            <div class="card-body">
                <p class="mb-1"><strong>Vendor:</strong>
                    @if($lead->vendor)
                        <a href="{{ route('admin.marketplace.vendors.show', $lead->vendor) }}">{{ $lead->vendor->business_name }}</a>
                        · <a href="tel:{{ $lead->vendor->phone }}">{{ $lead->vendor->phone }}</a>
                    @else
                        <span class="text-muted">deleted</span>
                    @endif
                </p>
                <p class="mb-0"><strong>Product:</strong>
                    @if($lead->product)
                        <a href="{{ route('admin.marketplace.products.show', $lead->product) }}">{{ $lead->product->name }}</a>
                        <small class="text-muted">({{ $lead->product->price_label }})</small>
                    @else
                        <span class="text-muted">General inquiry</span>
                    @endif
                </p>
            </div>
        </div>

        @if($lead->property)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><strong>Source Property</strong></div>
            <div class="card-body">
                <a href="{{ route('property-details', $lead->property) }}" target="_blank">
                    {{ $lead->property->title }}
                </a>
                <small class="text-muted">— {{ $lead->property->city }} · {{ $lead->property->bhk_type }} · ₹{{ number_format($lead->property->price) }}</small>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-5">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white"><strong>Status &amp; Commission</strong></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.marketplace.leads.update', $lead) }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select" required>
                            @foreach(\App\Models\MarketplaceLead::STATUSES as $s)
                                <option value="{{ $s }}" {{ $lead->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Order value (₹)</label>
                            <input type="number" name="order_value" min="0" step="0.01" class="form-control"
                                   value="{{ old('order_value', $lead->order_value) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Commission (₹)</label>
                            <input type="number" name="commission_amount" min="0" step="0.01" class="form-control"
                                   value="{{ old('commission_amount', $lead->commission_amount) }}">
                            <small class="text-muted">Auto-derived at {{ $lead->vendor?->commission_pct ?? 8 }}% on Won if blank.</small>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="commission_collected" value="1" id="cc"
                            {{ old('commission_collected', $lead->commission_collected) ? 'checked' : '' }}>
                        <label class="form-check-label" for="cc">Commission collected</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Admin notes</label>
                        <textarea name="admin_notes" rows="3" class="form-control">{{ old('admin_notes', $lead->admin_notes) }}</textarea>
                    </div>

                    <button class="btn btn-primary w-100"><i class="fas fa-save me-1"></i> Save</button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><strong>Tracking</strong></div>
            <div class="card-body small text-muted">
                <p class="mb-1"><strong>Created:</strong> {{ $lead->created_at->format('d M Y, h:i A') }} ({{ $lead->created_at->diffForHumans() }})</p>
                @if($lead->contacted_at)
                    <p class="mb-1"><strong>Contacted:</strong> {{ $lead->contacted_at->format('d M Y, h:i A') }}</p>
                @endif
                @if($lead->closed_at)
                    <p class="mb-1"><strong>Closed:</strong> {{ $lead->closed_at->format('d M Y, h:i A') }}</p>
                @endif
                @if($lead->source_page)
                    <p class="mb-1"><strong>Source:</strong> <a href="{{ $lead->source_page }}" target="_blank">{{ \Illuminate\Support\Str::limit($lead->source_page, 50) }}</a></p>
                @endif
                @if($lead->ip_address)
                    <p class="mb-0"><strong>IP:</strong> {{ $lead->ip_address }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
