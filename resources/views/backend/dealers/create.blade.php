@extends('backend.layout')
@section('title', 'Add Dealer')
@section('content')

<div class="mb-4">
    <a href="{{ route('admin.dealers.index') }}" class="btn btn-sm btn-light me-2">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
    <span class="fs-5 fw-bold"><i class="fas fa-user-plus me-2 text-primary"></i>Add New Dealer</span>
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
<div class="col-lg-9">
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <form method="POST" action="{{ route('admin.dealers.store') }}">
            @csrf

            {{-- Basic Info --}}
            <div class="fw-semibold text-muted small text-uppercase mb-3 pb-1" style="border-bottom:1px solid #e2e8f0;">
                <i class="fas fa-user me-1"></i> Basic Information
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                           value="{{ old('first_name') }}" placeholder="e.g. Rajesh" required>
                    @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Last Name</label>
                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                           value="{{ old('last_name') }}" placeholder="e.g. Sharma">
                    @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="dealer@example.com" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone') }}" placeholder="9876543210" required>
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Company Name</label>
                    <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror"
                           value="{{ old('company_name') }}" placeholder="e.g. Tricity Realty">
                    @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="active"   {{ old('status','active')==='active'   ? 'selected':'' }}>Active</option>
                        <option value="inactive" {{ old('status')==='inactive' ? 'selected':'' }}>Inactive</option>
                        <option value="blocked"  {{ old('status')==='blocked'  ? 'selected':'' }}>Blocked</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Password --}}
            <div class="fw-semibold text-muted small text-uppercase mb-3 pb-1" style="border-bottom:1px solid #e2e8f0;">
                <i class="fas fa-lock me-1"></i> Login Credentials
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" name="password" id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Min. 8 characters" required autocomplete="new-password">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePwd()">
                            <i class="fas fa-eye" id="pwd-eye"></i>
                        </button>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="generatePwd()">
                        <i class="fas fa-random me-1"></i> Generate Password
                    </button>
                </div>
            </div>

            {{-- Profile --}}
            <div class="fw-semibold text-muted small text-uppercase mb-3 pb-1" style="border-bottom:1px solid #e2e8f0;">
                <i class="fas fa-id-card me-1"></i> Profile Details
            </div>
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label class="form-label fw-semibold">Bio</label>
                    <textarea name="bio" class="form-control @error('bio') is-invalid @enderror"
                              rows="3" placeholder="Brief introduction about the dealer…">{{ old('bio') }}</textarea>
                    @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Specializations</label>
                    <input type="text" name="specializations"
                           class="form-control @error('specializations') is-invalid @enderror"
                           value="{{ old('specializations') }}"
                           placeholder="e.g. Residential, Commercial, Plots">
                    <div class="form-text small">Comma-separated values</div>
                    @error('specializations')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Operating Cities</label>
                    <input type="text" name="operating_cities"
                           class="form-control @error('operating_cities') is-invalid @enderror"
                           value="{{ old('operating_cities') }}"
                           placeholder="e.g. Mohali, Chandigarh, Zirakpur">
                    <div class="form-text small">Comma-separated values</div>
                    @error('operating_cities')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-5 fw-semibold">
                    <i class="fas fa-save me-2"></i> Create Dealer
                </button>
                <a href="{{ route('admin.dealers.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>

    </div>
</div>
</div>
</div>

<script>
function togglePwd() {
    const f = document.getElementById('password');
    const e = document.getElementById('pwd-eye');
    if (f.type === 'password') { f.type = 'text'; e.className = 'fas fa-eye-slash'; }
    else                       { f.type = 'password'; e.className = 'fas fa-eye'; }
}
function generatePwd() {
    const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$!';
    let pwd = '';
    for (let i = 0; i < 12; i++) pwd += chars[Math.floor(Math.random() * chars.length)];
    const f = document.getElementById('password');
    f.type = 'text';
    f.value = pwd;
    document.getElementById('pwd-eye').className = 'fas fa-eye-slash';
    // Copy to clipboard
    navigator.clipboard?.writeText(pwd).then(() => alert('Password generated and copied: ' + pwd));
}
</script>

@endsection
