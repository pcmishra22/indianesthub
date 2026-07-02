@extends('layouts.auth')
@section('title', 'Login | ' . config('app.name'))

@section('auth-content')
<div class="auth-card" style="max-width:860px;margin:0 auto;">
  <div class="row g-0">

    <div class="col-md-5 auth-accent d-none d-md-flex">
      <div>
        <div style="width:52px;height:52px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:24px;">
          <i class="bi bi-house-heart" style="font-size:1.6rem;"></i>
        </div>
        <h2>Find Your Dream Home</h2>
        <p>Log in to save your favourite properties, track inquiries and get personalised recommendations.</p>
        <ul>
          <li><i class="bi bi-check-circle-fill"></i> Save & compare properties</li>
          <li><i class="bi bi-check-circle-fill"></i> Contact dealers instantly</li>
          <li><i class="bi bi-check-circle-fill"></i> Track your inquiries</li>
          <li><i class="bi bi-check-circle-fill"></i> Get alerts for new listings</li>
        </ul>
        <div class="mt-4" style="border-top:1px solid rgba(255,255,255,.2);padding-top:20px;font-size:.8rem;opacity:.8;">
          New here? <a href="{{ route('register') }}" style="color:#7dd3fc;font-weight:700;">Create a free account →</a>
        </div>
      </div>
    </div>

    <div class="col-md-7 auth-form-panel">
      <div class="auth-title">Welcome back</div>
      <div class="auth-sub">Sign in to your account</div>

      <x-auth-session-status class="mb-3" :status="session('status')" />

      @if($errors->any())
        <div class="alert alert-danger py-2 small mb-3">
          @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf
        <div class="mb-3">
          <label class="form-label" for="email">Email Address</label>
          <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email"
                 value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
          <label class="form-label" for="password">Password</label>
          <input id="password" class="form-control" type="password" name="password" placeholder="Your password" required>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="form-check mb-0">
            <input type="checkbox" class="form-check-input" name="remember" id="remuser">
            <label class="form-check-label small text-muted" for="remuser">Remember me</label>
          </div>
          @if(Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="small" style="color:#0078d4;text-decoration:none;">Forgot password?</a>
          @endif
        </div>
        <button type="submit" class="auth-btn">Sign In</button>
      </form>

      <div class="auth-divider">or</div>

      <div class="text-center">
        <p class="small text-muted mb-2">Are you a partner?</p>
        <div class="d-flex gap-2 justify-content-center flex-wrap">
          <a href="{{ route('dealer.login') }}" class="btn btn-sm btn-outline-secondary" style="font-size:.78rem;border-radius:6px;">
            <i class="bi bi-building me-1"></i>Dealer Login
          </a>
          <a href="{{ route('builder.login') }}" class="btn btn-sm btn-outline-secondary" style="font-size:.78rem;border-radius:6px;">
            <i class="bi bi-buildings me-1"></i>Builder Login
          </a>
          <a href="{{ route('service-provider.login') }}" class="btn btn-sm btn-outline-secondary" style="font-size:.78rem;border-radius:6px;">
            <i class="bi bi-tools me-1"></i>Provider Login
          </a>
        </div>
      </div>

      <div class="auth-switch">
        Don't have an account? <a href="{{ route('register') }}">Register free</a>
      </div>
    </div>

  </div>
</div>
@endsection
