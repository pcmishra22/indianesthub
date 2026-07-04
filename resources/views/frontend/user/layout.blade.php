{{-- resources/views/frontend/user/layout.blade.php --}}
@extends('frontend.layout')

@section('head')
<link rel="stylesheet" href="{{ asset('assets/css/frontend/pages.css') }}">
@endsection

@section('content')
<main class="main">
  {{-- Page Title --}}
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">@yield('page-title', 'My Account')</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="{{ url('/') }}">Home</a></li>
          <li class="current">@yield('page-title', 'My Account')</li>
        </ol>
      </nav>
    </div>
  </div>

  <section class="section">
    <div class="container">
      <div class="user-dashboard-wrapper">
        <div class="row g-4">
          {{-- Mobile Sidebar Toggle --}}
          <div class="col-12 d-lg-none">
            <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#userSidebarOffcanvas" aria-controls="userSidebarOffcanvas">
              <i class="bi bi-list me-1"></i> Menu
            </button>
          </div>

          {{-- Offcanvas Sidebar (Mobile) --}}
          <div class="offcanvas offcanvas-start user-sidebar-offcanvas d-lg-none" tabindex="-1" id="userSidebarOffcanvas" aria-labelledby="userSidebarOffcanvasLabel">
            <div class="offcanvas-header">
              <h5 class="offcanvas-title" id="userSidebarOffcanvasLabel">My Account</h5>
              <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0">
              @include('frontend.user._sidebar-nav')
            </div>
          </div>

          {{-- Desktop Sidebar --}}
          <div class="col-lg-3 d-none d-lg-block">
            <div class="user-sidebar">
              <div class="sidebar-header">
                <div class="user-avatar">
                  <i class="bi bi-person-fill"></i>
                </div>
                <h5>{{ Auth::user()->name ?? 'User' }}</h5>
                <small>{{ Auth::user()->email ?? '' }}</small>
              </div>
              @include('frontend.user._sidebar-nav')
            </div>
          </div>

          {{-- Main Content Area --}}
          <div class="col-lg-9">
            <div class="user-content">
              @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {{ session('success') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif
              @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  {{ session('error') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif
              @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <ul class="mb-0">
                    @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif
              @yield('user-content')
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
