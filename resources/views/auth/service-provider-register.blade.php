@extends('layouts.auth')
@section('title', 'Register as Service Provider | ' . config('app.name'))

@section('auth-content')
<div class="auth-card" style="max-width:1040px;margin:0 auto;">
  <div class="row g-0">

    <div class="col-md-4 auth-accent d-none d-md-flex" style="background:linear-gradient(160deg,#4c1d95 0%,#7c3aed 60%,#a78bfa 100%);">
      <div>
        <div style="width:52px;height:52px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:24px;">
          <i class="bi bi-tools" style="font-size:1.6rem;"></i>
        </div>
        <h2>One Signup. All Services.</h2>
        <p>Register once and offer any home service — electrician, plumber, interior designer, loan provider and more.</p>
        <ul>
          <li><i class="bi bi-check-circle-fill"></i> 100% free to register</li>
          <li><i class="bi bi-check-circle-fill"></i> Choose your own service category</li>
          <li><i class="bi bi-check-circle-fill"></i> Get leads from property buyers</li>
          <li><i class="bi bi-check-circle-fill"></i> Verified badge after approval</li>
        </ul>
        <div class="mt-4" style="border-top:1px solid rgba(255,255,255,.2);padding-top:20px;font-size:.8rem;opacity:.8;">
          Already registered? <a href="{{ route('service-provider.login') }}" style="color:#c4b5fd;font-weight:700;">Sign in →</a>
        </div>
      </div>
    </div>

    <div class="col-md-8 auth-form-panel">
      <div class="auth-title">Register as Service Provider</div>
      <div class="auth-sub">Electricians, plumbers, interior designers, loan providers & more</div>

      @include('partials.partner-role-switcher', ['activeRole'=>'service_provider','mode'=>'register'])

      @if($errors->any())
        <div class="alert alert-danger py-2 small mb-3">
          @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('service-provider.register') }}" class="auth-form">
        @csrf
        <div class="row g-3 mb-3">
          <div class="col-sm-6">
            <label class="form-label">Full Name *</label>
            <input class="form-control @error('full_name') is-invalid @enderror" type="text" name="full_name"
                   value="{{ old('full_name') }}" placeholder="Your full name" required autofocus>
            @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-sm-6">
            <label class="form-label">Business / Shop Name</label>
            <input class="form-control" type="text" name="business_name"
                   value="{{ old('business_name') }}" placeholder="Optional — e.g. Sharma Electricals">
          </div>
        </div>
        <div class="row g-3 mb-3">
          <div class="col-sm-6">
            <label class="form-label">Email *</label>
            <input class="form-control @error('email') is-invalid @enderror" type="email" name="email"
                   value="{{ old('email') }}" placeholder="you@example.com" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-sm-6">
            <label class="form-label">Phone *</label>
            <input class="form-control @error('phone') is-invalid @enderror" type="text" name="phone"
                   value="{{ old('phone') }}" placeholder="10-digit mobile" required>
            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="row g-3 mb-3">
          <div class="col-sm-6">
            <label class="form-label">City *</label>
            <input class="form-control @error('city') is-invalid @enderror" type="text" name="city"
                   value="{{ old('city') }}" placeholder="e.g. Zirakpur" required>
            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
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

        <label class="form-label fw-semibold mb-1">I want to sign up as * <span class="fw-normal text-muted" style="font-size:.78rem;">(select one or more)</span></label>
        @error('categories')<div class="text-danger small mb-1">{{ $message }}</div>@enderror
        <div class="cat-grid mb-4">
          @foreach($categories as $cat)
            <div class="cat-tile">
              <input type="checkbox" id="cat-{{ $cat->id }}" name="categories[]" value="{{ $cat->id }}"
                     {{ in_array($cat->id, old('categories', [])) ? 'checked' : '' }}>
              <label for="cat-{{ $cat->id }}">
                <i class="bi {{ $cat->icon ?? 'bi-tools' }}"></i>{{ $cat->name }}
              </label>
            </div>
          @endforeach
        </div>

        <button type="submit" class="auth-btn" style="background:linear-gradient(135deg,#4c1d95,#7c3aed);">Create Service Provider Account — Free</button>
      </form>

      <div class="auth-switch">
        Already registered? <a href="{{ route('service-provider.login') }}">Sign in</a>
      </div>
    </div>

  </div>
</div>
@endsection
