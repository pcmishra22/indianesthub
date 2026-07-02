@extends('layouts.auth')
@section('title', 'Builder Login | ' . config('app.name'))

@section('auth-content')
<div class="auth-card" style="max-width:900px;margin:0 auto;">
  <div class="row g-0">

    <div class="col-md-5 auth-accent d-none d-md-flex" style="background:linear-gradient(160deg,#064e3b 0%,#059669 60%,#10b981 100%);">
      <div>
        <div style="width:52px;height:52px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:24px;">
          <i class="bi bi-buildings" style="font-size:1.6rem;"></i>
        </div>
        <h2>Builder Portal</h2>
        <p>Showcase your projects, manage units and connect with serious buyers across Tricity.</p>
        <ul>
          <li><i class="bi bi-check-circle-fill"></i> List new launch projects</li>
          <li><i class="bi bi-check-circle-fill"></i> Manage floor plans & units</li>
          <li><i class="bi bi-check-circle-fill"></i> Track project leads</li>
          <li><i class="bi bi-check-circle-fill"></i> RERA project showcase</li>
        </ul>
        <div class="mt-4" style="border-top:1px solid rgba(255,255,255,.2);padding-top:20px;font-size:.8rem;opacity:.8;">
          New builder? <a href="{{ route('builder.register') }}" style="color:#6ee7b7;font-weight:700;">Register free →</a>
        </div>
      </div>
    </div>

    <div class="col-md-7 auth-form-panel">
      <div class="auth-title">Welcome back</div>
      <div class="auth-sub">Sign in to your builder account</div>

      @include('partials.partner-role-switcher', ['activeRole'=>'builder','mode'=>'login'])

      @if($errors->any())
        <div class="alert alert-danger py-2 small mb-3">
          @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('builder.login') }}" class="auth-form">
        @csrf
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input class="form-control @error('email') is-invalid @enderror" type="email" name="email"
                 value="{{ old('email') }}" placeholder="company@example.com" required autofocus>
          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input class="form-control" type="password" name="password" placeholder="Your password" required>
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="remember" id="remb">
          <label class="form-check-label small text-muted" for="remb">Remember me</label>
        </div>
        <button type="submit" class="auth-btn" style="background:linear-gradient(135deg,#064e3b,#059669);">Sign In to Builder Portal</button>
      </form>

      <div class="auth-switch">
        Don't have an account? <a href="{{ route('builder.register') }}">Register as a Builder</a>
      </div>
    </div>

  </div>
</div>
@endsection
