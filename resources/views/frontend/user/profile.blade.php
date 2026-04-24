{{-- resources/views/frontend/user/profile.blade.php --}}
@extends('frontend.user.layout')

@section('page-title', 'My Profile')

@section('user-content')
  <div class="mb-4">
    <h4 class="fw-bold">My Profile</h4>
    <p class="text-muted mb-0">Update your personal information and password.</p>
  </div>

  <form action="{{ route('user.profile.update') }}" method="POST">
    @csrf
    @method('PUT')

    {{-- Personal Information --}}
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-white border-bottom">
        <h5 class="mb-0 fw-semibold"><i class="bi bi-person me-2"></i>Personal Information</h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label for="name" class="form-label fw-medium">Full Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label for="email" class="form-label fw-medium">Email Address <span class="text-danger">*</span></label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label for="phone" class="form-label fw-medium">Phone Number</label>
            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" placeholder="+91 XXXXX XXXXX">
            @error('phone')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>
    </div>

    {{-- Change Password --}}
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-white border-bottom">
        <h5 class="mb-0 fw-semibold"><i class="bi bi-shield-lock me-2"></i>Change Password</h5>
      </div>
      <div class="card-body">
        <p class="text-muted mb-3" style="font-size: 13px;">Leave password fields blank if you do not wish to change your password.</p>
        <div class="row g-3">
          <div class="col-md-4">
            <label for="current_password" class="form-label fw-medium">Current Password</label>
            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" placeholder="Enter current password">
            @error('current_password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-4">
            <label for="password" class="form-label fw-medium">New Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter new password">
            @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-4">
            <label for="password_confirmation" class="form-label fw-medium">Confirm New Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-end">
      <button type="submit" class="btn px-4 py-2" style="background-color: #077f46; color: #fff; border-radius: 8px; font-weight: 600;">
        <i class="bi bi-check-circle me-1"></i> Save Changes
      </button>
    </div>
  </form>
@endsection
