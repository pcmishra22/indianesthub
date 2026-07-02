<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name') . ' – Real Estate in Chandigarh, Mohali, Zirakpur | Buy, Sell, Rent')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ══════════════════════════════════════════
         SEO CORE META TAGS
    ══════════════════════════════════════════ -->
    <meta name="description" content="@yield('meta_description', config('app.name') . ' – Find verified flats, villas &amp; plots for sale &amp; rent in Chandigarh, Mohali, Zirakpur &amp; Panchkula. 10,000+ verified listings on India\'s most trusted Tricity real estate portal.')">
    <meta name="keywords" content="@yield('meta_keywords', 'property in chandigarh, flats in mohali, property in zirakpur, real estate tricity, flats for sale panchkula, buy flat chandigarh, rent flat mohali, new projects zirakpur')">
    <meta name="robots" content="@yield('robots', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1')">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <link rel="alternate" hreflang="en-IN" href="{{ url()->current() }}">

    <!-- ══════════════════════════════════════════
         OPEN GRAPH (Facebook, LinkedIn, WhatsApp)
    ══════════════════════════════════════════ -->
    <meta property="og:type"        content="@yield('og_type', 'website')">
    <meta property="og:site_name"   content="{{ config('app.name') }}">
    <meta property="og:locale"      content="en_IN">
    <meta property="og:title"       content="@yield('og_title', config('app.name') . ' – Real Estate in Chandigarh Tricity')">
    <meta property="og:description" content="@yield('og_description', 'Find verified flats, villas &amp; plots for sale &amp; rent in Chandigarh, Mohali, Zirakpur. Your most trusted Tricity property portal.')">
    <meta property="og:url"         content="@yield('og_url', url()->current())">
    <meta property="og:image"       content="@yield('og_image', asset('assets/img/og-default.jpg'))">
    <meta property="og:image:width"  content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt"   content="@yield('og_image_alt', config('app.name') . ' – Real Estate Tricity')">

    <!-- ══════════════════════════════════════════
         TWITTER / X CARD
    ══════════════════════════════════════════ -->
    <meta name="twitter:card"        content="summary_large_image">
    @if(env('SOCIAL_TWITTER_HANDLE'))
    <meta name="twitter:site"        content="{{ env('SOCIAL_TWITTER_HANDLE') }}">
    @endif
    <meta name="twitter:title"       content="@yield('twitter_title', config('app.name') . ' – Real Estate in Chandigarh Tricity')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Find verified flats, villas &amp; plots for sale &amp; rent in Chandigarh, Mohali, Zirakpur &amp; Panchkula.')">
    <meta name="twitter:image"       content="@yield('twitter_image', asset('assets/img/og-default.jpg'))">

    <!-- ══════════════════════════════════════════
         JSON-LD: ORGANIZATION SCHEMA (sitewide)
    ══════════════════════════════════════════ -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@type": "RealEstateAgent",
      "name": "{{ config("app.name") }}",
      "alternateName": "Indianes Hub",
      "url": "{{ url('/') }}",
      "logo": {
        "@type": "ImageObject",
        "url": "{{ asset('assets/img/logo.png') }}",
        "width": 200,
        "height": 60
      },
      "image": "{{ asset('assets/img/og-default.jpg') }}",
      "description": "{{ config("app.name") }} is India's most trusted real estate portal for Chandigarh Tricity – buy, sell and rent verified properties in Chandigarh, Mohali, Zirakpur and Panchkula.",
      "email": "admin@indianesthub.com",
      "telephone": "+91-{{ config('app.contact_phone','7340753780') }}",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Chandigarh",
        "addressRegion": "Punjab",
        "addressCountry": "IN"
      },
      "areaServed": [
        "Chandigarh", "Mohali", "Zirakpur", "Panchkula",
        "Kharar", "Derabassi", "Mullanpur", "Tricity"
      ],
      "sameAs": [
        "https://indianesthub.com"
      ],
      "potentialAction": {
        "@type": "SearchAction",
        "target": {
          "@type": "EntryPoint",
          "urlTemplate": "{{ url('/properties') }}?keyword={search_term_string}"
        },
        "query-input": "required name=search_term_string"
      }
    }
    </script>

    <!-- Page-specific schema injected here -->
    @yield('schema')

    <!-- Favicons -->
    <link rel="icon" href="/assets/img/favicon.png">
    <link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png">

    <!-- Fonts -->
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

    <!-- Global Blue Theme Override -->
    <style>
      /* Header – Edge-inspired deep blue gradient */
      .header {
        background: linear-gradient(135deg, #0a2d5e 0%, #0f4c81 60%, #1565c0 100%) !important;
        box-shadow: 0 2px 20px rgba(10,45,94,0.35);
      }
      .scrolled .header {
        background: linear-gradient(135deg, #061c3d 0%, #0a3670 60%, #1059b0 100%) !important;
        box-shadow: 0 4px 24px rgba(6,28,61,0.45);
      }
      /* Nav active link – bright cyan underline */
      .navmenu a.active {
        color: #fff !important;
        position: relative;
      }
      .navmenu a.active::after {
        content: '';
        position: absolute;
        bottom: 10px; left: 15px; right: 15px;
        height: 2px;
        background: #50e6ff;
        border-radius: 2px;
      }
      /* Scroll-to-top button */
      .scroll-top { background: linear-gradient(135deg, #0078d4, #50e6ff); }
      .scroll-top:hover { background: linear-gradient(135deg, #0a2d5e, #0078d4); }
      /* Bootstrap primary overrides */
      .btn-primary, .btn-outline-primary:hover {
        background: linear-gradient(135deg, #0a2d5e, #0078d4) !important;
        border-color: transparent !important;
      }
      .btn-outline-primary {
        border-color: #0078d4 !important;
        color: #0078d4 !important;
      }
      /* Page title sections */
      .page-title { background: #eef5fb !important; }
      .page-title .breadcrumbs a,
      .breadcrumb-item a { color: #0078d4; }
    </style>
    @yield('head')

    {{-- Google Analytics GA4 --}}
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('app.ga_id', 'G-DB1DLSLXLY') }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '{{ config('app.ga_id', 'G-DB1DLSLXLY') }}');
    </script>
</head>
<body>
    @include('frontend.partials.header')
    @yield('content')
    @include('frontend.partials.footer')

    {{-- ─── Scroll to Top ─── --}}
    <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    {{-- ════════════════════════════════════════════
         FLOATING WHATSAPP + CALLBACK BUTTONS
         Fixed bottom-right — visible on all pages
    ════════════════════════════════════════════ --}}
    <style>
      .floating-cta-wrap {
        position: fixed;
        bottom: 80px;
        right: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 10px;
      }
      .floating-cta-wrap .fcta-label {
        font-size: 11px;
        font-weight: 600;
        color: #fff;
        background: rgba(0,0,0,.5);
        padding: 2px 8px;
        border-radius: 20px;
        white-space: nowrap;
        opacity: 0;
        transition: opacity .2s;
        pointer-events: none;
      }
      .floating-cta-wrap .fcta-btn:hover + .fcta-label,
      .floating-cta-wrap .fcta-item:hover .fcta-label { opacity: 1; }
      .floating-cta-wrap .fcta-item {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 4px;
      }
      .fcta-btn {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(0,0,0,.25);
        transition: transform .2s, box-shadow .2s;
        text-decoration: none;
        color: #fff;
      }
      .fcta-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 22px rgba(0,0,0,.32);
        color: #fff;
      }
      .fcta-whatsapp { background: #25D366; }
      .fcta-call     { background: linear-gradient(135deg, #0a2d5e, #0078d4); }
      .fcta-pulse {
        animation: pulseglow 2.5s infinite;
      }
      @keyframes pulseglow {
        0%, 100% { box-shadow: 0 4px 16px rgba(37,211,102,.35); }
        50%       { box-shadow: 0 4px 28px rgba(37,211,102,.75); }
      }
    </style>

    <div class="floating-cta-wrap">
      {{-- WhatsApp --}}
      <div class="fcta-item">
        <a href="https://wa.me/91{{ config('app.whatsapp_number','7340753780') }}?text=Hi%2C%20I%20found%20you%20on%20{{ urlencode(config('app.name')) }}.%20I%20need%20help%20finding%20a%20property."
           class="fcta-btn fcta-whatsapp fcta-pulse"
           target="_blank" rel="noopener"
           title="Chat on WhatsApp">
          <i class="bi bi-whatsapp"></i>
        </a>
        <span class="fcta-label">WhatsApp Us</span>
      </div>

      {{-- Call Button --}}
      <div class="fcta-item">
        <a href="tel:+91{{ config('app.contact_phone','7340753780') }}"
           class="fcta-btn fcta-call"
           title="Call Us Now">
          <i class="bi bi-telephone-fill"></i>
        </a>
        <span class="fcta-label">Call Now</span>
      </div>
    </div>

    {{-- ════════════════════════════════════════════
         STICKY BOTTOM BAR (mobile only)
         Shows Call | WhatsApp | Post Property
    ════════════════════════════════════════════ --}}
    <style>
      .sticky-bottom-bar {
        position: fixed;
        bottom: 0; left: 0; right: 0;
        z-index: 9998;
        display: none;
        background: #fff;
        border-top: 1px solid #e2e8f0;
        box-shadow: 0 -4px 16px rgba(0,0,0,.12);
        padding: 8px 12px;
      }
      @media (max-width: 768px) {
        .sticky-bottom-bar { display: flex; }
        .floating-cta-wrap { bottom: 72px; right: 14px; }
        .floating-cta-wrap .fcta-btn { width: 44px; height: 44px; font-size: 1.2rem; }
      }
      .sticky-bottom-bar .sbb-btn {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 600;
        gap: 2px;
        text-decoration: none;
        border-radius: 8px;
        padding: 6px 4px;
        transition: background .15s;
      }
      .sticky-bottom-bar .sbb-btn i { font-size: 1.2rem; }
      .sbb-call    { color: #0078d4; }
      .sbb-wa      { color: #25D366; }
      .sbb-post    { color: #fff; background: linear-gradient(135deg, #0a2d5e, #0078d4); }
      .sbb-divider { width: 1px; background: #e2e8f0; margin: 0 4px; }
    </style>

    <div class="sticky-bottom-bar d-md-none">
      <a href="tel:+91{{ config('app.contact_phone','7340753780') }}" class="sbb-btn sbb-call">
        <i class="bi bi-telephone-fill"></i>Call
      </a>
      <div class="sbb-divider"></div>
      <a href="https://wa.me/91{{ config('app.whatsapp_number','7340753780') }}?text=Hi%2C%20I%20need%20property%20help." class="sbb-btn sbb-wa" target="_blank" rel="noopener">
        <i class="bi bi-whatsapp"></i>WhatsApp
      </a>
      <div class="sbb-divider"></div>
      <a href="{{ route('dealer.login') }}" class="sbb-btn sbb-post mx-2">
        <i class="bi bi-plus-circle"></i>Post Property
      </a>
    </div>

    <!-- Vendor JS Files -->
    <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/vendor/php-email-form/validate.js"></script>
    <script src="/assets/vendor/aos/aos.js"></script>
    <script src="/assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="/assets/vendor/swiper/swiper-bundle.min.js"></script>
    <!-- Main JS File -->
    <script src="/assets/js/main.js"></script>
    @yield('scripts')

    {{-- Loan, Insurance & Legal Modals (available on every page) --}}
    @include('frontend.partials.loan-eligibility-modal')
    @include('frontend.partials.insurance-modal')
    @include('frontend.partials.legal-modal')
</body>
</html>
