@php
    $isEdit = isset($vendor) && $vendor;
@endphp
<div class="row g-3">
    <div class="col-md-7">
        <label class="form-label fw-semibold">Business name *</label>
        <input type="text" name="business_name" class="form-control" required
               value="{{ old('business_name', $vendor->business_name ?? '') }}">
    </div>
    <div class="col-md-5">
        <label class="form-label fw-semibold">Owner name</label>
        <input type="text" name="owner_name" class="form-control"
               value="{{ old('owner_name', $vendor->owner_name ?? '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Phone *</label>
        <input type="text" name="phone" class="form-control" required
               value="{{ old('phone', $vendor->phone ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">WhatsApp</label>
        <input type="text" name="whatsapp" class="form-control"
               value="{{ old('whatsapp', $vendor->whatsapp ?? '') }}">
        <small class="text-muted">Leave blank to use phone.</small>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" class="form-control"
               value="{{ old('email', $vendor->email ?? '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">City</label>
        <input type="text" name="city" class="form-control"
               value="{{ old('city', $vendor->city ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Area</label>
        <input type="text" name="area" class="form-control"
               value="{{ old('area', $vendor->area ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Years in business</label>
        <input type="number" name="years_in_business" min="0" class="form-control"
               value="{{ old('years_in_business', $vendor->years_in_business ?? '') }}">
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">Address</label>
        <input type="text" name="address" class="form-control"
               value="{{ old('address', $vendor->address ?? '') }}">
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">Description</label>
        <textarea name="description" rows="3" class="form-control">{{ old('description', $vendor->description ?? '') }}</textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Commission %</label>
        <input type="number" name="commission_pct" min="0" max="50" step="0.1" class="form-control" required
               value="{{ old('commission_pct', $vendor->commission_pct ?? '8.0') }}">
        <small class="text-muted">Default 8% on confirmed orders.</small>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Logo</label>
        <input type="file" name="logo" accept="image/*" class="form-control">
        @if($isEdit && $vendor->logo)
            <small class="text-muted">Current: <a href="{{ asset('storage/'.$vendor->logo) }}" target="_blank">view</a></small>
        @endif
    </div>
    <div class="col-md-4 d-flex align-items-end gap-3">
        <div class="form-check">
            <input type="checkbox" name="is_verified" value="1" class="form-check-input" id="is_verified"
                {{ old('is_verified', $vendor->is_verified ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_verified">Verified</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                {{ old('is_active', $vendor->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
    </div>
</div>
