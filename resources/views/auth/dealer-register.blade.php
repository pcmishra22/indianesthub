@extends('layouts.auth')
@section('title', 'Register as Property Dealer | ' . config('app.name'))

@section('auth-content')
<div class="auth-card" style="max-width:960px;margin:0 auto;">
  <div class="row g-0">

    <div class="col-md-4 auth-accent d-none d-md-flex">
      <div>
        <div style="width:52px;height:52px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:24px;">
          <i class="bi bi-building" style="font-size:1.6rem;"></i>
        </div>
        <h2>Post Properties. Get Leads.</h2>
        <p>Join Tricity's most active real estate platform and grow your business.</p>
        <ul>
          <li><i class="bi bi-check-circle-fill"></i> Free to register</li>
          <li><i class="bi bi-check-circle-fill"></i> Post your first listing in minutes</li>
          <li><i class="bi bi-check-circle-fill"></i> Leads from verified buyers</li>
          <li><i class="bi bi-check-circle-fill"></i> Dedicated dealer dashboard</li>
        </ul>
        <div class="mt-4" style="border-top:1px solid rgba(255,255,255,.2);padding-top:20px;font-size:.8rem;opacity:.8;">
          Already registered? <a href="{{ route('dealer.login') }}" style="color:#7dd3fc;font-weight:700;">Sign in →</a>
        </div>
      </div>
    </div>

    <div class="col-md-8 auth-form-panel">
      <div class="auth-title">Create Dealer Account</div>
      <div class="auth-sub">Start listing properties on {{ config('app.name') }} — free</div>

      @include('partials.partner-role-switcher', ['activeRole'=>'dealer','mode'=>'register'])

      @if($errors->any())
        <div class="alert alert-danger py-2 small mb-3">
          @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('dealer.register') }}" class="auth-form">
        @csrf
        <div class="row g-3 mb-3">
          <div class="col-sm-6">
            <label class="form-label">First Name *</label>
            <input class="form-control @error('first_name') is-invalid @enderror" type="text" name="first_name"
                   value="{{ old('first_name') }}" placeholder="Rajesh" required autofocus>
            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-sm-6">
            <label class="form-label">Last Name *</label>
            <input class="form-control @error('last_name') is-invalid @enderror" type="text" name="last_name"
                   value="{{ old('last_name') }}" placeholder="Sharma" required>
            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                   value="{{ old('phone') }}" placeholder="7340753780" required>
            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Company / Agency Name *</label>
          <input class="form-control @error('company_name') is-invalid @enderror" type="text" name="company_name"
                 value="{{ old('company_name') }}" placeholder="e.g. Tricity Realty" required>
          @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
        <button type="submit" class="auth-btn">Create Dealer Account — Free</button>
      </form>

      <div class="auth-switch">
        Already have an account? <a href="{{ route('dealer.login') }}">Sign in</a>
      </div>
    </div>

  </div>
</div>
@endsection
