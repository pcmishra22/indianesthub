@extends('layouts.auth')
@section('title', 'Dealer Login | ' . config('app.name'))

@section('auth-content')
<div class="auth-card" style="max-width:900px;margin:0 auto;">
  <div class="row g-0">

    {{-- LEFT ACCENT --}}
    <div class="col-md-5 auth-accent d-none d-md-flex">
      <div>
        <div style="width:52px;height:52px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:24px;">
          <i class="bi bi-building" style="font-size:1.6rem;"></i>
        </div>
        <h2>Property Dealer Portal</h2>
        <p>Manage your listings, leads and inquiries from one powerful dashboard.</p>
        <ul>
          <li><i class="bi bi-check-circle-fill"></i> Post unlimited properties</li>
          <li><i class="bi bi-check-circle-fill"></i> Get direct buyer leads</li>
          <li><i class="bi bi-check-circle-fill"></i> Track views & inquiries</li>
          <li><i class="bi bi-check-circle-fill"></i> Featured listing options</li>
        </ul>
        <div class="mt-4" style="border-top:1px solid rgba(255,255,255,.2);padding-top:20px;font-size:.8rem;opacity:.8;">
          New partner? <a href="{{ route('dealer.register') }}" style="color:#7dd3fc;font-weight:700;">Register free →</a>
        </div>
      </div>
    </div>

    {{-- RIGHT FORM --}}
    <div class="col-md-7 auth-form-panel">
      <div class="auth-title">Welcome back</div>
      <div class="auth-sub">Sign in to your dealer account</div>

      @include('partials.partner-role-switcher', ['activeRole'=>'dealer','mode'=>'login'])

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 small" role="alert">
          {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger py-2 small">
          @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('dealer.login') }}" class="auth-form">
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
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="form-check mb-0">
            <input type="checkbox" class="form-check-input" name="remember" id="rem" {{ old('remember') ? 'checked':'' }}>
            <label class="form-check-label small text-muted" for="rem">Remember me</label>
          </div>
        </div>
        <button type="submit" class="auth-btn">Sign In to Dealer Portal</button>
      </form>

      <div class="auth-switch">
        Don't have an account? <a href="{{ route('dealer.register') }}">Register as a Dealer</a>
      </div>
    </div>

  </div>
</div>
@endsection
