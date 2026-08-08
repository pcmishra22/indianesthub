


{{-- TOP ANNOUNCEMENT BAR --}}
<div class="top-bar" id="topBar">
  <div class="top-bar-inner">

    {{-- Left CTAs --}}
    <div class="top-bar-links">
      <a href="{{ route('dealer.register') }}" class="top-bar-cta post">
        <i class="bi bi-plus-circle-fill"></i>
        <span>Post Property Free</span>
      </a>
      <a href="{{ route('service-provider.register') }}" class="top-bar-cta service">
        <i class="bi bi-tools"></i>
        <span>Offer a Service Free</span>
      </a>
      <a href="{{ route('investors') }}" class="top-bar-cta" style="background:#0a2d5e;color:#50e6ff;">
        <i class="bi bi-graph-up-arrow"></i>
        <span>Partner With Us</span>
      </a>
      <a href="{{ route('marketplace.index') }}" class="top-bar-cta" style="background:#0a2d5e;color:#fbbf24;">
        <i class="bi bi-shop"></i>
        <span>Marketplace</span>
      </a>
    </div>

    {{-- Scrolling ticker --}}
    <div class="top-bar-ticker">
      <span>
        🏠 &nbsp; 323+ verified properties in Tricity &nbsp;•&nbsp;
        ⚡ Electricians, Plumbers, Interior Designers & more available in Zirakpur, Mohali, Chandigarh &nbsp;•&nbsp;
        🏗️ New launches from top builders &nbsp;•&nbsp;
        💰 Home Loan assistance available &nbsp;•&nbsp;
        ✅ 100% verified listings &nbsp;•&nbsp;
        📍 Serving Chandigarh · Mohali · Zirakpur · Panchkula · Kharar
      </span>
    </div>

    {{-- Dismiss --}}
    <div class="top-bar-close" onclick="document.getElementById('topBar').style.display='none'" title="Close">✕</div>

  </div>
</div>

  <header id="header" class="header d-flex align-items-center sticky-top" style="overflow: visible;">
    <style>
      /* Keep nav from overflowing and pushing the Login/Register buttons out
         of the visible header bar on medium-wide desktop screens. */
      #header .container-fluid { flex-wrap: nowrap; }
      #navmenu { min-width: 0; overflow-x: auto; scrollbar-width: none; }
      #navmenu::-webkit-scrollbar { display: none; }
      #navmenu > ul { flex-wrap: nowrap; white-space: nowrap; }
      #header .d-none.d-xl-flex { flex-shrink: 0; margin-left: 12px; }
    </style>
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="{{ url('/') }}" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.webp" alt=""> -->
        <svg class="my-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <g id="bgCarrier" stroke-width="0"></g>
          <g id="tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
          <g id="iconCarrier">
            <path d="M22 22L2 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path d="M2 11L6.06296 7.74968M22 11L13.8741 4.49931C12.7784 3.62279 11.2216 3.62279 10.1259 4.49931L9.34398 5.12486" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path d="M15.5 5.5V3.5C15.5 3.22386 15.7239 3 16 3H18.5C18.7761 3 19 3.22386 19 3.5V8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path d="M4 22V9.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path d="M20 9.5V13.5M20 22V17.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path d="M15 22V17C15 15.5858 15 14.8787 14.5607 14.4393C14.1213 14 13.4142 14 12 14C10.5858 14 9.87868 14 9.43934 14.4393M9 22V17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M14 9.5C14 10.6046 13.1046 11.5 12 11.5C10.8954 11.5 10 10.6046 10 9.5C10 8.39543 10.8954 7.5 12 7.5C13.1046 7.5 14 8.39543 14 9.5Z" stroke="currentColor" stroke-width="1.5"></path>
          </g>
        </svg>
        <h1 class="sitename">{{ config('app.name') }}</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>
          <li><a href="{{ url('/properties') }}" class="{{ request()->is('properties') ? 'active' : '' }}">Properties</a></li>
          <li><a href="{{ route('auctions.index') }}" class="{{ request()->is('auctions*') ? 'active' : '' }}"><i class="bi bi-hammer me-1" style="font-size:.75rem;"></i>Auctions</a></li>
          <li><a href="{{ url('/properties?looking_for=Sale') }}" class="{{ request()->is('properties') && request('looking_for') == 'Sale' ? 'active' : '' }}">Buy</a></li>
          <li><a href="{{ url('/properties?looking_for=Rent') }}" class="{{ request()->is('properties') && request('looking_for') == 'Rent' ? 'active' : '' }}">Rent</a></li>
          <li><a href="{{ url('/properties?looking_for=Renovate') }}" class="{{ request()->is('properties') && request('looking_for') == 'Renovate' ? 'active' : '' }}">Renovate</a></li>
          <li><a href="{{ url('/services') }}" class="{{ request()->is('services') ? 'active' : '' }}">Services</a></li>
          <li><a href="{{ url('/#investors') }}" style="color:#0078d4!important;font-weight:700;"><i class="bi bi-cash-coin me-1" style="font-size:.75rem;"></i>Investors</a></li>
          <li><a href="{{ url('/agents') }}" class="{{ request()->is('agents') ? 'active' : '' }}">Agents</a></li>
          <li><a href="{{ route('builders.index') }}" class="{{ request()->is('builders*') ? 'active' : '' }}">Builders</a></li>
          <li><a href="{{ url('/blog') }}" class="{{ request()->is('blog') ? 'active' : '' }}">Blog</a></li>
          <li><a href="{{ url('/pricing') }}" class="{{ request()->is('pricing') ? 'active' : '' }}" style="{{ request()->is('pricing') ? '' : 'color:#fbbf24!important;font-weight:700;' }}"><i class="bi bi-tag-fill me-1" style="font-size:.75rem;"></i>Pricing</a></li>
          <li><a href="{{ url('/contact') }}" class="{{ request()->is('contact') ? 'active' : '' }}">Contact</a></li>

          {{-- Finance & Legal Dropdown --}}
          <li class="dropdown">
            <a href="#"><span>Finance &amp; Legal</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li>
                <a href="#" onclick="event.preventDefault(); openLoanModal(null, null, 'header');">
                  <i class="bi bi-bank me-1"></i> Home Loan Eligibility
                </a>
              </li>
              <li>
                <a href="#" onclick="event.preventDefault(); openInsuranceModal(null, null, 'header');">
                  <i class="bi bi-shield-check me-1"></i> Property Insurance
                </a>
              </li>
              <li>
                <a href="#" onclick="event.preventDefault(); openLegalModal(null, null, 'header');" style="color:#7c3aed!important;font-weight:600;">
                  <i class="bi bi-briefcase me-1"></i> Legal Help
                </a>
              </li>
            </ul>
          </li>

          {{-- Mobile-only Partner With Us link --}}
          <li class="d-xl-none">
            <a href="{{ route('investors') }}" style="color:#0078d4!important;font-weight:700;"><i class="bi bi-graph-up-arrow me-1"></i> Partner With Us</a>
          </li>

          {{-- Mobile-only Finance & Legal links --}}
          <li class="d-xl-none">
            <a href="#" onclick="event.preventDefault(); openLoanModal(null, null, 'header');"><i class="bi bi-bank me-1"></i> Home Loan</a>
          </li>
          <li class="d-xl-none">
            <a href="#" onclick="event.preventDefault(); openInsuranceModal(null, null, 'header');"><i class="bi bi-shield-check me-1"></i> Insurance</a>
          </li>
          <li class="d-xl-none">
            <a href="#" onclick="event.preventDefault(); openLegalModal(null, null, 'header');" style="color:#7c3aed!important;"><i class="bi bi-briefcase me-1"></i> Legal Help</a>
          </li>

          {{-- Mobile-only auth links --}}
          @guest
            <li class="d-xl-none"><a href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i> Login</a></li>
            <li class="d-xl-none"><a href="{{ route('register') }}"><i class="bi bi-person-plus me-1"></i> Register</a></li>
          @endguest

          @auth
            <li class="d-xl-none"><a href="{{ route('user.dashboard') }}"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a></li>
            <li class="d-xl-none"><a href="{{ route('user.wishlist') }}"><i class="bi bi-heart me-1"></i> My Wishlist</a></li>
            <li class="d-xl-none">
              <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
              </a>
              <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </li>
          @endauth
        </ul>
      </nav>
      <i class="mobile-nav-toggle d-xl-none bi bi-list ms-auto"></i>

      {{-- Desktop auth buttons --}}
      <div class="d-none d-xl-flex align-items-center gap-2">

        @guest
          <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary px-3 py-2" style="border-radius:8px;font-weight:600;font-size:13px;">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login
          </a>
          <a href="{{ route('register') }}" class="btn btn-sm px-3 py-2" style="background:linear-gradient(135deg,#0a2d5e,#0078d4);color:#fff;border-radius:8px;font-weight:600;font-size:13px;border:none;">
            <i class="bi bi-person-plus me-1"></i> Register
          </a>
        @endguest

        @auth
          <div class="dropdown">
            <a class="btn btn-sm btn-outline-primary dropdown-toggle px-3 py-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius:8px;font-weight:600;font-size:13px;border-color:#0078d4;color:#0078d4;">
              <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius:10px;border:1px solid #e9ecef;min-width:200px;">
              <li><a class="dropdown-item py-2" href="{{ route('user.dashboard') }}"><i class="bi bi-speedometer2 me-2 text-muted"></i> Dashboard</a></li>
              <li><a class="dropdown-item py-2" href="{{ route('user.profile') }}"><i class="bi bi-person me-2 text-muted"></i> My Profile</a></li>
              <li><a class="dropdown-item py-2" href="{{ route('user.wishlist') }}"><i class="bi bi-heart me-2 text-muted"></i> My Wishlist</a></li>
              <li><a class="dropdown-item py-2" href="{{ route('user.inquiries') }}"><i class="bi bi-chat-left-text me-2 text-muted"></i> My Inquiries</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form-desktop').submit();">
                  <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
                <form id="logout-form-desktop" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
              </li>
            </ul>
          </div>
        @endauth
      </div>
    </div><!-- End container-fluid -->
  </header>
