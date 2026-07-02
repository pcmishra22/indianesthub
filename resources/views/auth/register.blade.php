@extends('layouts.auth')
@section('title', 'Register | ' . config('app.name'))

@section('auth-content')
<div class="auth-card" style="max-width:860px;margin:0 auto;">
  <div class="row g-0">

    <div class="col-md-5 auth-accent d-none d-md-flex">
      <div>
        <div style="width:52px;height:52px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:24px;">
          <i class="bi bi-house-heart" style="font-size:1.6rem;"></i>
        </div>
        <h2>Join {{ config('app.name') }}</h2>
        <p>Create your free account and start your property journey in Chandigarh Tricity today.</p>
        <ul>
          <li><i class="bi bi-check-circle-fill"></i> Save favourite properties</li>
          <li><i class="bi bi-check-circle-fill"></i> Contact dealers & builders</li>
          <li><i class="bi bi-check-circle-fill"></i> Get new listing alerts</li>
          <li><i class="bi bi-check-circle-fill"></i> 100% free, always</li>
        </ul>
        <div class="mt-4" style="border-top:1px solid rgba(255,255,255,.2);padding-top:20px;font-size:.8rem;opacity:.8;">
          Already a member? <a href="{{ route('login') }}" style="color:#7dd3fc;font-weight:700;">Sign in →</a>
        </div>
      </div>
    </div>

    <div class="col-md-7 auth-form-panel">
      <div class="auth-title">Create your account</div>
      <div class="auth-sub">Free forever — no credit card needed</div>

      @if($errors->any())
        <div class="alert alert-danger py-2 small mb-3">
          @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf
        <div class="mb-3">
          <label class="form-label" for="name">Full Name *</label>
          <input id="name" class="form-control @error('name') is-invalid @enderror" type="text" name="name"
                 value="{{ old('name') }}" placeholder="Your name" required autofocus>
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
          <label class="form-label" for="reg_email">Email Address *</label>
          <input id="reg_email" class="form-control @error('email') is-invalid @enderror" type="email" name="email"
                 value="{{ old('email') }}" placeholder="you@example.com" required>
          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
        <button type="submit" class="auth-btn">Create Free Account</button>
      </form>

      <div class="auth-divider">Are you a partner?</div>
      <div class="d-flex gap-2 justify-content-center flex-wrap mb-2">
        <a href="{{ route('dealer.register') }}" class="btn btn-sm btn-outline-secondary" style="font-size:.78rem;border-radius:6px;">
          <i class="bi bi-building me-1"></i>Register as Dealer
        </a>
        <a href="{{ route('builder.register') }}" class="btn btn-sm btn-outline-secondary" style="font-size:.78rem;border-radius:6px;">
          <i class="bi bi-buildings me-1"></i>Register as Builder
        </a>
        <a href="{{ route('service-provider.register') }}" class="btn btn-sm btn-outline-secondary" style="font-size:.78rem;border-radius:6px;">
          <i class="bi bi-tools me-1"></i>Offer a Service
        </a>
      </div>

      <div class="auth-switch">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
      </div>
    </div>

  </div>
</div>
@endsection
