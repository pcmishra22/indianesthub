{{-- resources/views/frontend/user/dashboard.blade.php --}}
@extends('frontend.user.layout')

@section('page-title', 'Dashboard')

@section('user-content')
  {{-- Welcome Message --}}
  <div class="mb-4">
    <h4 class="fw-bold">Welcome back, {{ Auth::user()->name ?? 'User' }}!</h4>
    <p class="text-muted mb-0">Here is a summary of your account activity.</p>
  </div>

  {{-- Stats Cards --}}
  <div class="row g-4 mb-4">
    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #dc3545 !important;">
        <div class="card-body d-flex align-items-center">
          <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; background: #fde8e8;">
            <i class="bi bi-heart-fill text-danger fs-5"></i>
          </div>
          <div>
            <h3 class="mb-0 fw-bold">{{ $totalWishlist ?? 0 }}</h3>
            <small class="text-muted">Wishlisted Properties</small>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #077f46 !important;">
        <div class="card-body d-flex align-items-center">
          <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; background: #e6f7ef;">
            <i class="bi bi-chat-left-text-fill fs-5" style="color: #077f46;"></i>
          </div>
          <div>
            <h3 class="mb-0 fw-bold">{{ $totalInquiries ?? 0 }}</h3>
            <small class="text-muted">Total Inquiries</small>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #0d6efd !important;">
        <div class="card-body d-flex align-items-center">
          <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; background: #e7f0ff;">
            <i class="bi bi-clock-history fs-5 text-primary"></i>
          </div>
          <div>
            <h3 class="mb-0 fw-bold">{{ $totalRecentlyViewed ?? 0 }}</h3>
            <small class="text-muted">Recently Viewed</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Quick Links --}}
  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom">
      <h5 class="mb-0 fw-semibold"><i class="bi bi-lightning me-2"></i>Quick Links</h5>
    </div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-4 col-6">
          <a href="{{ route('properties') }}" class="d-flex align-items-center text-decoration-none p-3 rounded-3" style="background: #f8f9fa; transition: all 0.2s;">
            <i class="bi bi-search fs-4 me-3" style="color: #077f46;"></i>
            <div>
              <strong class="text-dark d-block" style="font-size: 14px;">Browse Properties</strong>
              <small class="text-muted">Find your dream home</small>
            </div>
          </a>
        </div>
        <div class="col-md-4 col-6">
          <a href="{{ route('user.wishlist') }}" class="d-flex align-items-center text-decoration-none p-3 rounded-3" style="background: #f8f9fa; transition: all 0.2s;">
            <i class="bi bi-heart fs-4 me-3 text-danger"></i>
            <div>
              <strong class="text-dark d-block" style="font-size: 14px;">My Wishlist</strong>
              <small class="text-muted">Saved properties</small>
            </div>
          </a>
        </div>
        <div class="col-md-4 col-6">
          <a href="{{ route('user.profile') }}" class="d-flex align-items-center text-decoration-none p-3 rounded-3" style="background: #f8f9fa; transition: all 0.2s;">
            <i class="bi bi-person-gear fs-4 me-3 text-primary"></i>
            <div>
              <strong class="text-dark d-block" style="font-size: 14px;">Edit Profile</strong>
              <small class="text-muted">Update your details</small>
            </div>
          </a>
        </div>
        <div class="col-md-4 col-6">
          <a href="{{ route('user.inquiries') }}" class="d-flex align-items-center text-decoration-none p-3 rounded-3" style="background: #f8f9fa; transition: all 0.2s;">
            <i class="bi bi-chat-left-text fs-4 me-3" style="color: #077f46;"></i>
            <div>
              <strong class="text-dark d-block" style="font-size: 14px;">My Inquiries</strong>
              <small class="text-muted">Track your inquiries</small>
            </div>
          </a>
        </div>
        <div class="col-md-4 col-6">
          <a href="{{ route('user.recently-viewed') }}" class="d-flex align-items-center text-decoration-none p-3 rounded-3" style="background: #f8f9fa; transition: all 0.2s;">
            <i class="bi bi-clock-history fs-4 me-3 text-warning"></i>
            <div>
              <strong class="text-dark d-block" style="font-size: 14px;">Recently Viewed</strong>
              <small class="text-muted">Your browsing history</small>
            </div>
          </a>
        </div>
        <div class="col-md-4 col-6">
          <a href="{{ route('contact') }}" class="d-flex align-items-center text-decoration-none p-3 rounded-3" style="background: #f8f9fa; transition: all 0.2s;">
            <i class="bi bi-headset fs-4 me-3 text-info"></i>
            <div>
              <strong class="text-dark d-block" style="font-size: 14px;">Contact Support</strong>
              <small class="text-muted">Need help?</small>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection
