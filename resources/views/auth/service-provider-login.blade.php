@extends('layouts.auth')
@section('title', 'Service Provider Login | ' . config('app.name'))

@section('auth-content')
<div class="auth-card" style="max-width:900px;margin:0 auto;">
  <div class="row g-0">

    <div class="col-md-5 auth-accent d-none d-md-flex" style="background:linear-gradient(160deg,#4c1d95 0%,#7c3aed 60%,#a78bfa 100%);">
      <div>
        <div style="width:52px;height:52px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:24px;">
          <i class="bi bi-tools" style="font-size:1.6rem;"></i>
        </div>
        <h2>Service Provider Portal</h2>
        <p>Electricians, plumbers, interior designers, loan providers — manage your profile and get leads.</p>
        <ul>
          <li><i class="bi bi-check-circle-fill"></i> Free provider profile</li>
          <li><i class="bi bi-check-circle-fill"></i> Get leads from homebuyers</li>
          <li><i class="bi bi-check-circle-fill"></i> Verified badge for credibility</li>
          <li><i class="bi bi-check-circle-fill"></i> WhatsApp & call leads instantly</li>
        </ul>
        <div class="mt-4" style="border-top:1px solid rgba(255,255,255,.2);padding-top:20px;font-size:.8rem;opacity:.8;">
          New provider? <a href="{{ route('service-provider.register') }}" style="color:#c4b5fd;font-weight:700;">Register free →</a>
        </div>
      </div>
    </div>

    <div class="col-md-7 auth-form-panel">
      <div class="auth-title">Welcome back</div>
      <div class="auth-sub">Sign in to your service provider account</div>

      @include('partials.partner-role-switcher', ['activeRole'=>'service_provider','mode'=>'login'])

      @if(session('status'))
        <div class="alert alert-success py-2 small alert-dismissible fade show">
          {{ session('status') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger py-2 small">
          @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('service-provider.login') }}" class="auth-form">
        @csrf
        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input class="form-control @error('email') is-invalid @enderror" type="email" name="email"
                 value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input class="form-control" type="password" name="password" placeholder="Your password" required>
        </div>
        <div class="form-check mb-3">
          <input type="checkbox" class="form-check-input" name="remember" id="remsp" {{ old('remember') ? 'checked':'' }}>
          <label class="form-check-label small text-muted" for="remsp">Remember me</label>
        </div>
        <button type="submit" class="auth-btn" style="background:linear-gradient(135deg,#4c1d95,#7c3aed);">Sign In to Provider Portal</button>
      </form>

      <div class="auth-switch">
        Don't have an account? <a href="{{ route('service-provider.register') }}">Register as a Service Provider</a>
      </div>
    </div>

  </div>
</div>
@endsection
