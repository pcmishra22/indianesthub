@extends('frontend.layout')

@section('title', 'Register - ' . config('app.name'))

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
    .invalid-feedback {
        font-size: 13px;
    }
</style>
@endsection

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <div class="auth-card card">
                    <div class="card-body">
                        <div class="auth-header">
                            <div class="auth-icon">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <h2>Create Your Account</h2>
                            <p>Join {{ config('app.name') }} and find your dream property</p>
                        </div>

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

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            {{-- Name --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px; border: 1.5px solid #d0d5dd; border-right: none;">
                                        <i class="bi bi-person text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}"
                                           placeholder="Enter your full name" required autofocus
                                           style="border-radius: 0 10px 10px 0;">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px; border: 1.5px solid #d0d5dd; border-right: none;">
                                        <i class="bi bi-envelope text-muted"></i>
                                    </span>
                                    <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}"
                                           placeholder="Enter your email" required
                                           style="border-radius: 0 10px 10px 0;">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Phone --}}
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px; border: 1.5px solid #d0d5dd; border-right: none;">
                                        <i class="bi bi-telephone text-muted"></i>
                                    </span>
                                    <input type="tel" class="form-control border-start-0 @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone') }}"
                                           placeholder="Enter your phone number" required
                                           style="border-radius: 0 10px 10px 0;">
                                    @error('phone')
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
                                           placeholder="Create a password" required
                                           style="border-radius: 0 10px 10px 0;">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Confirm Password --}}
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px; border: 1.5px solid #d0d5dd; border-right: none;">
                                        <i class="bi bi-lock-fill text-muted"></i>
                                    </span>
                                    <input type="password" class="form-control border-start-0 @error('password_confirmation') is-invalid @enderror"
                                           id="password_confirmation" name="password_confirmation"
                                           placeholder="Confirm your password" required
                                           style="border-radius: 0 10px 10px 0;">
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="mb-3">
                                <button type="submit" class="btn btn-auth">
                                    <i class="bi bi-person-plus me-2"></i>Create Account
                                </button>
                            </div>
                        </form>

                        <div class="auth-footer">
                            <p class="mb-0">Already have an account?
                                <a href="{{ route('login') }}">Login here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
