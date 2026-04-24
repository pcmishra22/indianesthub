@extends('backend.layout')
@section('title', 'Add Subscription')
@section('content')

<div class="mb-4">
    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-sm btn-light me-2">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
    <span class="fs-5 fw-bold"><i class="fas fa-crown me-2 text-warning"></i>Add New Subscription</span>
</div>

<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.subscriptions.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Dealer <span class="text-danger">*</span></label>
                    <select name="property_dealer_id" class="form-select" required>
                        <option value="">Select Dealer...</option>
                        @foreach($dealers as $dealer)
                        <option value="{{ $dealer->id }}" {{ old('property_dealer_id') == $dealer->id ? 'selected':'' }}>
                            {{ $dealer->name }} ({{ $dealer->email }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Plan <span class="text-danger">*</span></label>
                    <select name="plan" class="form-select" required onchange="fillPlanDefaults(this.value)">
                        <option value="">Select Plan</option>
                        <option value="basic"      {{ old('plan')==='basic'      ?'selected':'' }}>Basic</option>
                        <option value="premium"    {{ old('plan')==='premium'    ?'selected':'' }}>Premium</option>
                        <option value="enterprise" {{ old('plan')==='enterprise' ?'selected':'' }}>Enterprise</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Price (₹) <span class="text-danger">*</span></label>
                    <input type="number" name="price" id="price" class="form-control" required
                           value="{{ old('price') }}" min="0" step="100" placeholder="e.g. 2999">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Property Limit <span class="text-danger">*</span></label>
                    <input type="number" name="property_limit" id="property_limit" class="form-control" required
                           value="{{ old('property_limit', 10) }}" min="1">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Featured Property Limit</label>
                    <input type="number" name="featured_limit" id="featured_limit" class="form-control"
                           value="{{ old('featured_limit', 2) }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-control" required value="{{ old('start_date', date('Y-m-d')) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">End Date <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" class="form-control" required value="{{ old('end_date') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="active"    {{ old('status','active')==='active'    ?'selected':'' }}>Active</option>
                        <option value="expired"   {{ old('status')==='expired'   ?'selected':'' }}>Expired</option>
                        <option value="cancelled" {{ old('status')==='cancelled' ?'selected':'' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="priority_support" id="priority_support"
                               value="1" {{ old('priority_support') ? 'checked':'' }}>
                        <label class="form-check-label fw-semibold" for="priority_support">Priority Support</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="analytics_access" id="analytics_access"
                               value="1" {{ old('analytics_access') ? 'checked':'' }}>
                        <label class="form-check-label fw-semibold" for="analytics_access">Analytics Access</label>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save me-1"></i> Create Subscription
                </button>
                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>

<script>
const planDefaults = {
    basic:      { price: 999,   property_limit: 10,  featured_limit: 2 },
    premium:    { price: 2999,  property_limit: 50,  featured_limit: 10 },
    enterprise: { price: 7999,  property_limit: 999, featured_limit: 50 },
};
function fillPlanDefaults(plan) {
    if (!planDefaults[plan]) return;
    const d = planDefaults[plan];
    document.getElementById('price').value           = d.price;
    document.getElementById('property_limit').value  = d.property_limit;
    document.getElementById('featured_limit').value  = d.featured_limit;
    // Set end date to 1 year from today
    const end = new Date();
    end.setFullYear(end.getFullYear() + 1);
    document.querySelector('[name="end_date"]').value = end.toISOString().split('T')[0];
}
</script>

@endsection
