<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', config('app.name'))</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Fonts (same as main site, needed for header/footer typography) -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/vendor/bootstrap-icons/bootstrap-icons.css">
  <link rel="stylesheet" href="/assets/vendor/aos/aos.css">
  <link rel="stylesheet" href="/assets/vendor/swiper/swiper-bundle.min.css">

  <!-- Main CSS File -->
  <link rel="stylesheet" href="/assets/css/main.css">

  <!-- Global Blue Theme Override (styles the site header/footer - was missing here) -->
  <link rel="stylesheet" href="{{ asset('assets/css/frontend/layout.css') }}">
  @stack('styles')
  <style>
    body { background: #f1f5f9; }

    /* ── Auth page wrapper ── */
    .auth-wrap {
      min-height: calc(100vh - 130px);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 48px 16px;
    }

    /* ── Auth card ── */
    .auth-card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 8px 40px rgba(10,45,94,.10);
      overflow: hidden;
      width: 100%;
    }

    /* ── Left accent panel (desktop only) ── */
    .auth-accent {
      background: linear-gradient(160deg, #0a2d5e 0%, #0078d4 60%, #0ea5e9 100%);
      color: #fff;
      padding: 48px 36px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .auth-accent h2 { font-size: 1.6rem; font-weight: 800; margin-bottom: 12px; }
    .auth-accent p  { opacity: .85; font-size: .9rem; line-height: 1.75; }
    .auth-accent ul { padding: 0; list-style: none; margin-top: 24px; }
    .auth-accent ul li {
      display: flex; align-items: center; gap: 10px;
      font-size: .85rem; margin-bottom: 12px; opacity: .92;
    }
    .auth-accent ul li i { font-size: 1.1rem; flex-shrink: 0; color: #7dd3fc; }

    /* ── Right form panel ── */
    .auth-form-panel { padding: 40px 36px; }
    @media (max-width: 767px) {
      .auth-form-panel { padding: 28px 20px; }
      .auth-accent     { display: none !important; }
    }

    /* ── Role switcher tabs ── */
    .role-switcher {
      display: flex;
      background: #f1f5f9;
      border-radius: 10px;
      padding: 4px;
      margin-bottom: 24px;
      gap: 3px;
    }
    .role-tab {
      flex: 1; text-align: center;
      padding: 8px 6px;
      border-radius: 7px;
      font-size: .75rem; font-weight: 600;
      color: #64748b;
      text-decoration: none;
      transition: all .15s;
      white-space: nowrap;
      display: flex; align-items: center; justify-content: center; gap: 5px;
    }
    .role-tab.active {
      background: #fff;
      color: #0078d4;
      box-shadow: 0 1px 6px rgba(0,120,212,.15);
    }
    .role-tab:hover:not(.active) { background: #e2e8f0; color: #0a2d5e; text-decoration: none; }

    /* ── Form elements ── */
    .auth-title { font-size: 1.5rem; font-weight: 800; color: #0a2d5e; margin-bottom: 4px; }
    .auth-sub   { font-size: .85rem; color: #64748b; margin-bottom: 24px; }
    .auth-form .form-label  { font-weight: 600; font-size: .82rem; color: #374151; }
    .auth-form .form-control {
      border: 1.5px solid #e2e8f0; border-radius: 8px;
      font-size: .88rem; padding: 10px 14px; color: #1e293b;
    }
    .auth-form .form-control:focus { border-color: #0078d4; box-shadow: 0 0 0 3px rgba(0,120,212,.1); }
    .auth-form .form-control.is-invalid { border-color: #ef4444; }
    .auth-btn {
      width: 100%; padding: 12px; border-radius: 8px; font-weight: 700;
      font-size: .95rem; border: none;
      background: linear-gradient(135deg, #0a2d5e, #0078d4);
      color: #fff; margin-top: 4px; transition: opacity .15s;
    }
    .auth-btn:hover { opacity: .92; }
    .auth-divider {
      text-align: center; margin: 18px 0;
      font-size: .78rem; color: #94a3b8; position: relative;
    }
    .auth-divider::before, .auth-divider::after {
      content: ''; position: absolute; top: 50%; width: 40%; height: 1px; background: #e2e8f0;
    }
    .auth-divider::before { left: 0; }
    .auth-divider::after  { right: 0; }
    .auth-switch {
      text-align: center; font-size: .83rem; color: #64748b; margin-top: 16px;
    }
    .auth-switch a { color: #0078d4; font-weight: 700; text-decoration: none; }
    .auth-switch a:hover { text-decoration: underline; }

    /* ── Category tile picker ── */
    .cat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px,1fr)); gap: 8px; margin-bottom: 4px; }
    .cat-tile  { position: relative; }
    .cat-tile input { position: absolute; opacity: 0; inset: 0; cursor: pointer; margin: 0; }
    .cat-tile label {
      display: flex; align-items: center; gap: 7px;
      padding: 9px 12px; border: 1.5px solid #e2e8f0; border-radius: 8px;
      cursor: pointer; font-size: .78rem; font-weight: 600; color: #475569;
      transition: all .15s; background: #f8fafc;
    }
    .cat-tile input:checked + label { border-color: #0078d4; background: #eff6ff; color: #1d4ed8; }
    .cat-tile label i { color: #0078d4; }
  </style>
</head>
<body>

{{-- Full site header --}}
@include('frontend.partials.header')

<div class="auth-wrap">
  <div class="container">
    @yield('auth-content')
  </div>
</div>

{{-- Full site footer --}}
@include('frontend.partials.footer')

<script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/vendor/php-email-form/validate.js"></script>
<script src="/assets/vendor/aos/aos.js"></script>
<script src="/assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="/assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="/assets/js/main.js"></script>
@yield('scripts')
</body>
</html>
