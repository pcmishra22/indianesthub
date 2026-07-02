@extends('layouts.auth')
@section('title', 'Register as Builder | ' . config('app.name'))

@section('auth-content')
<div class="auth-card" style="max-width:960px;margin:0 auto;">
  <div class="row g-0">

    <div class="col-md-4 auth-accent d-none d-md-flex" style="background:linear-gradient(160deg,#064e3b 0%,#059669 60%,#10b981 100%);">
      <div>
        <div style="width:52px;height:52px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:24px;">
          <i class="bi bi-buildings" style="font-size:1.6rem;"></i>
        </div>
        <h2>List Your Projects. Sell Faster.</h2>
        <p>Reach thousands of serious buyers looking for new projects in Tricity.</p>
        <ul>
          <li><i class="bi bi-check-circle-fill"></i> Free registration</li>
          <li><i class="bi bi-check-circle-fill"></i> Unlimited project listings</li>
          <li><i class="bi bi-check-circle-fill"></i> Direct buyer inquiries</li>
          <li><i class="bi bi-check-circle-fill"></i> Dedicated builder profile page</li>
        </ul>
        <div class="mt-4" style="border-top:1px solid rgba(255,255,255,.2);padding-top:20px;font-size:.8rem;opacity:.8;">
          Already registered? <a href="{{ route('builder.login') }}" style="color:#6ee7b7;font-weight:700;">Sign in →</a>
        </div>
      </div>
    </div>

    <div class="col-md-8 auth-form-panel">
      <div class="auth-title">Create Builder Account</div>
      <div class="auth-sub">Join {{ config('app.name') }} to showcase your projects</div>

      @include('partials.partner-role-switcher', ['activeRole'=>'builder','mode'=>'register'])

      @if($errors->any())
        <div class="alert alert-danger py-2 small mb-3">
          @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('builder.register') }}" class="auth-form">
        @csrf
        <div class="row g-3 mb-3">
          <div class="col-sm-6">
            <label class="form-label">Your Name *</label>
            <input class="form-control @error('name') is-invalid @enderror" type="text" name="name"
                   value="{{ old('name') }}" placeholder="Full name" required autofocus>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-sm-6">
            <label class="form-label">Company / Builder Name *</label>
            <input class="form-control @error('company_name') is-invalid @enderror" type="text" name="company_name"
                   value="{{ old('company_name') }}" placeholder="e.g. Sobha Developers" required>
            @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="row g-3 mb-3">
          <div class="col-sm-6">
            <label class="form-label">Email *</label>
            <input class="form-control @error('email') is-invalid @enderror" type="email" name="email"
                   value="{{ old('email') }}" placeholder="company@example.com" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-sm-6">
            <label class="form-label">Phone *</label>
            <input class="form-control @error('phone') is-invalid @enderror" type="text" name="phone"
                   value="{{ old('phone') }}" placeholder="+91 98765 43210" required>
            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="row g-3 mb-4">
          <div class="col-sm-6">
            <label class="form-label">Password *</label>
            <input class="form-control @error('password') is-invalid @enderror" type="password" name="password"
                   placeholder="Min 8 characters" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-sm-6">
            <label class="form-label">Confirm Password *</label>
            <input class="form-control" type="password" name="password_confirmation" placeholder="Re-enter" required>
          </div>
        </div>
        <button type="submit" class="auth-btn" style="background:linear-gradient(135deg,#064e3b,#059669);">Create Builder Account — Free</button>
      </form>

      <div class="auth-switch">
        Already have an account? <a href="{{ route('builder.login') }}">Sign in</a>
      </div>
    </div>

  </div>
</div>
@endsection
