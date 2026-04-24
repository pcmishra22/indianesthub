@extends('frontend.layout')

@section('title', 'Login - ' . config('app.name'))

@section('head')
<style>
    .auth-section {
        min-height: 80vh;
        display: flex;
        align-items: center;
        padding: 60px 0;
        background: linear-gradient(135deg, #f8f9fa 0%, #eef5fb 100%);
    }
    .auth-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: none;
    }
    .auth-card .card-body {
        padding: 40px 45px;
    }
    .auth-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .auth-header h2 {
        font-weight: 700;
        color: #2c3e50;
        font-size: 28px;
        margin-bottom: 8px;
    }
    .auth-header p {
        color: #6c757d;
        font-size: 15px;
    }
    .auth-card .form-label {
        font-weight: 600;
        color: #344054;
        font-size: 14px;
        margin-bottom: 6px;
    }
    .auth-card .form-control {
        border: 1.5px solid #d0d5dd;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 15px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .auth-card .form-control:focus {
        border-color: #0078d4;
        box-shadow: 0 0 0 3px rgba(0, 120, 212, 0.15);
    }
    .auth-card .form-control.is-invalid {
        border-color: #dc3545;
    }
    .btn-auth {
        background-color: #0078d4;
        border: none;
        border-radius: 10px;
        padding: 13px;
        font-size: 16px;
        font-weight: 600;
        color: #fff;
        width: 100%;
        transition: background-color 0.2s, transform 0.1s;
    }
    .btn-auth:hover {
        background-color: #0a2d5e;
        color: #fff;
        transform: translateY(-1px);
    }
    .btn-auth:active {
        transform: translateY(0);
    }
    .auth-footer {
        text-align: center;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
    }
    .auth-footer a {
        color: #0078d4;
        font-weight: 600;
        text-decoration: none;
    }
    .auth-footer a:hover {
        color: #0a2d5e;
        text-decoration: underline;
    }
    .auth-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #0078d4, #0f4c81);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    .auth-icon i {
        font-size: 28px;
        color: #fff;
    }
    .form-check-input:checked {
        background-color: #0078d4;
        border-color: #0078d4;
    }
    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(0, 120, 212, 0.15);
        border-color: #0078d4;
    }
    .dealer-link {
        display: inline-block;
        margin-top: 12px;
        color: #6c757d;
        font-size: 14px;
    }
    .dealer-link a {
        color: #0078d4;
        font-weight: 600;
        text-decoration: none;
    }
    .dealer-link a:hover {
        color: #0a2d5e;
        text-decoration: underline;
    }
    .invalid-feedback {
        font-size: 13px;
    }
</style>
@endsection

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7 col-sm-10">
                <div class="auth-card card">
                    <div class="card-body">
                        <div class="auth-header">
                            <div class="auth-icon">
                                <i class="bi bi-box-arrow-in-right"></i>
                            </div>
                            <h2>Welcome Back</h2>
                            <p>Sign in to your {{ config('app.name') }} account</p>
                        </div>

                        {{-- Session Status --}}
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-1"></i> {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Validation Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong><i class="bi bi-exclamation-triangle me-1"></i> Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px; border: 1.5px solid #d0d5dd; border-right: none;">
                                        <i class="bi bi-envelope text-muted"></i>
                                    </span>
                                    <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}"
                                           placeholder="Enter your email" required autofocus
                                           style="border-radius: 0 10px 10px 0;">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Password --}}
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px; border: 1.5px solid #d0d5dd; border-right: none;">
                                        <i class="bi bi-lock text-muted"></i>
                                    </span>
                                    <input type="password" class="form-control border-start-0 @error('password') is-invalid @enderror"
                                           id="password" name="password"
                                           placeholder="Enter your password" required
                                           style="border-radius: 0 10px 10px 0;">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Remember Me --}}
                            <div class="mb-4 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                           {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember" style="font-size: 14px; color: #555;">
                                        Remember me
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" style="font-size: 14px; color: #0078d4; text-decoration: none; font-weight: 500;">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>

                            {{-- Submit --}}
                            <div class="mb-3">
                                <button type="submit" class="btn btn-auth">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                                </button>
                            </div>
                        </form>

                        <div class="auth-footer">
                            <p class="mb-0">Don't have an account?
                                <a href="{{ route('register') }}">Register here</a>
                            </p>
                            <p class="dealer-link">Are you a dealer?
                                <a href="{{ route('dealer.login') }}">Login here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
