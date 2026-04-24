@extends('backend.layout')
@section('title', 'Edit Subscription #' . $subscription->id)
@section('content')

<div class="mb-4">
    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-sm btn-light me-2">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
    <span class="fs-5 fw-bold"><i class="fas fa-crown me-2 text-warning"></i>Edit Subscription #{{ $subscription->id }}</span>
</div>

<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        {{-- Dealer info (read-only) --}}
        <div class="mb-4 p-3 rounded" style="background:#f8fafc;border:1px solid #e2e8f0;">
            <div class="fw-semibold">{{ $subscription->dealer?->name ?? 'Dealer #'.$subscription->property_dealer_id }}</div>
            <div class="text-muted small">{{ $subscription->dealer?->email }}</div>
        </div>

        <form method="POST" action="{{ route('admin.subscriptions.update', $subscription) }}">
            @csrf @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Plan</label>
                    <select name="plan" class="form-select" required>
                        <option value="basic"      {{ $subscription->plan==='basic'      ?'selected':'' }}>Basic</option>
                        <option value="premium"    {{ $subscription->plan==='premium'    ?'selected':'' }}>Premium</option>
                        <option value="enterprise" {{ $subscription->plan==='enterprise' ?'selected':'' }}>Enterprise</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Price (₹)</label>
                    <input type="number" name="price" class="form-control" required
                           value="{{ old('price', $subscription->price) }}" min="0" step="100">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Property Limit</label>
                    <input type="number" name="property_limit" class="form-control" required
                           value="{{ old('property_limit', $subscription->property_limit) }}" min="1">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Featured Property Limit</label>
                    <input type="number" name="featured_limit" class="form-control"
                           value="{{ old('featured_limit', $subscription->featured_limit) }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">End Date</label>
                    <input type="date" name="end_date" class="form-control" required
                           value="{{ old('end_date', $subscription->end_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="active"    {{ $subscription->status==='active'    ?'selected':'' }}>Active</option>
                        <option value="expired"   {{ $subscription->status==='expired'   ?'selected':'' }}>Expired</option>
                        <option value="cancelled" {{ $subscription->status==='cancelled' ?'selected':'' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end gap-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="priority_support" id="ps"
                               value="1" {{ $subscription->priority_support ? 'checked':'' }}>
                        <label class="form-check-label fw-semibold" for="ps">Priority Support</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="analytics_access" id="aa"
                               value="1" {{ $subscription->analytics_access ? 'checked':'' }}>
                        <label class="form-check-label fw-semibold" for="aa">Analytics Access</label>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save me-1"></i> Save Changes
                </button>
                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>

@endsection
