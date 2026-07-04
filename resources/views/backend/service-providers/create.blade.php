@extends('backend.layout')
@section('title', 'Add Service Provider')
@section('content')

<div class="mb-4">
    <a href="{{ route('admin.service-providers.index') }}" class="btn btn-sm btn-light me-2">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
    <span class="fs-5 fw-bold"><i class="fas fa-user-plus me-2 text-primary"></i>Add Service Provider</span>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show mb-4">
    <i class="fas fa-exclamation-circle me-2"></i>
    <strong>Please fix the errors below:</strong>
    <ul class="mb-0 mt-1 small">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                <form method="POST" action="{{ route('admin.service-providers.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="fw-semibold text-muted small text-uppercase mb-3 pb-1" style="border-bottom:1px solid #e2e8f0;">
                        <i class="fas fa-user me-1"></i> Basic Information
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" required>
                            @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Business Name</label>
                            <input type="text" name="business_name" class="form-control @error('business_name') is-invalid @enderror" value="{{ old('business_name') }}">
                            @error('business_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                @foreach(['pending','approved','rejected','suspended'] as $s)
                                    <option value="{{ $s }}" {{ old('status','pending')===$s ? 'selected':'' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Verified</label>
                            <select name="is_verified" class="form-select @error('is_verified') is-invalid @enderror">
                                <option value="0" {{ old('is_verified','0')==='0' ? 'selected':'' }}>No</option>
                                <option value="1" {{ old('is_verified','0')==='1' ? 'selected':'' }}>Yes</option>
                            </select>
                            @error('is_verified')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">City</label>
                            <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}">
                            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="fw-semibold text-muted small text-uppercase mb-3 pb-1" style="border-bottom:1px solid #e2e8f0;">
                        <i class="fas fa-lock me-1"></i> Login Credentials
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Profile Photo</label>
                            <input type="file" name="profile_photo" class="form-control @error('profile_photo') is-invalid @enderror" accept="image/*">
                            @error('profile_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="fw-semibold text-muted small text-uppercase mb-3 pb-1" style="border-bottom:1px solid #e2e8f0;">
                        <i class="fas fa-id-card me-1"></i> Profile Details
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Years of Experience</label>
                            <input type="number" name="years_experience" class="form-control @error('years_experience') is-invalid @enderror" min="0" max="60" value="{{ old('years_experience') }}">
                            @error('years_experience')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Bio</label>
                            <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="3">{{ old('bio') }}</textarea>
                            @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Starting Price</label>
                            <input type="number" step="0.01" name="starting_price" class="form-control @error('starting_price') is-invalid @enderror" value="{{ old('starting_price') }}">
                            @error('starting_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Price Unit</label>
                            <input type="text" name="price_unit" class="form-control @error('price_unit') is-invalid @enderror" value="{{ old('price_unit') }}" placeholder="per sqft / per visit / per project">
                            @error('price_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Operating Areas <span class="text-muted small">(comma-separated)</span></label>
                            <input type="text" name="operating_areas_input" class="form-control" placeholder="Zirakpur, Mohali" value="{{ old('operating_areas_input') }}">
                            <input type="hidden" name="operating_areas" id="operating_areas">
                            @error('operating_areas')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="fw-semibold text-muted small text-uppercase mb-3 pb-1" style="border-bottom:1px solid #e2e8f0;">
                        <i class="fas fa-tags me-1"></i> Services Categories
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Select Categories <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            @foreach($categories as $cat)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $cat->id }}" {{ in_array($cat->id, old('categories', [])) ? 'checked':'' }}>
                                        <label class="form-check-label">{{ $cat->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('categories')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-5 fw-semibold"><i class="fas fa-save me-2"></i>Create Provider</button>
                        <a href="{{ route('admin.service-providers.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<script>
(function(){
    const input = document.querySelector('input[name="operating_areas_input"]');
    const hidden = document.getElementById('operating_areas');
    function sync(){
        const v = (input?.value || '').trim();
        if(!v){ hidden.value = JSON.stringify([]); return; }
        const arr = v.split(',').map(s=>s.trim()).filter(Boolean);
        hidden.value = JSON.stringify(arr);
    }
    input?.addEventListener('input', sync);
    sync();
})();
</script>

@endsection

