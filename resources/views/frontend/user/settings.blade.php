@extends('frontend.user.layout')

@section('page-title', 'Account Settings')

@section('user-content')
  <div class="mb-4">
    <h4 class="fw-bold">Account Settings</h4>
    <p class="text-muted mb-0">Manage your account preferences and security settings.</p>
  </div>

  <form action="{{ route('user.profile.update') }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-white border-bottom">
        <h5 class="mb-0 fw-semibold"><i class="bi bi-person me-2"></i>Personal Info</h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label for="name" class="form-label fw-medium">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', Auth::user()->name) }}">
          </div>
          <div class="col-md-6">
            <label for="email" class="form-label fw-medium">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', Auth::user()->email) }}">
          </div>
          <div class="col-md-6">
            <label for="phone" class="form-label fw-medium">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}">
          </div>
        </div>
      </div>
    </div>
    <div class="d-flex justify-content-end">
      <button type="submit" class="btn btn-success">Save Changes</button>
    </div>
  </form>

  <hr>

  <div class="mb-4">
    <h5 class="fw-semibold">Security</h5>
    <a href="{{ route('2fa.form') }}" class="btn btn-outline-primary">Manage Two-Factor Authentication</a>
    <a href="{{ route('phone.verify.form') }}" class="btn btn-outline-secondary ms-2">Verify Phone</a>
  </div>
@endsection
