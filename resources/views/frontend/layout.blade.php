<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name') . ' – India\'s Complete Real Estate Ecosystem')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="ai-chat-property-id" content="@yield('ai_chat_property_id', '')">

    <!-- ══════════════════════════════════════════
         SEO CORE META TAGS
    ══════════════════════════════════════════ -->
    <meta name="description" content="@yield('meta_description', config('app.name') . ' – India\'s complete real estate ecosystem. Verified properties for sale &amp; rent, new projects, home loans, insurance, legal help, service providers &amp; a home marketplace across Chandigarh, Mohali, Zirakpur, Panchkula, Pune, Bangalore, Hyderabad &amp; Delhi NCR.')">
    <meta name="keywords" content="@yield('meta_keywords', 'property in chandigarh, flats in mohali, property in zirakpur, real estate tricity, flats for sale panchkula, buy flat chandigarh, rent flat mohali, new projects zirakpur, home loan, property insurance, legal help, home marketplace')">
    <meta name="robots" content="@yield('robots', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1')">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <link rel="alternate" hreflang="en-IN" href="{{ url()->current() }}">

    <!-- ══════════════════════════════════════════
         OPEN GRAPH (Facebook, LinkedIn, WhatsApp)
    ══════════════════════════════════════════ -->
    <meta property="og:type"        content="@yield('og_type', 'website')">
    <meta property="og:site_name"   content="{{ config('app.name') }}">
    <meta property="og:locale"      content="en_IN">
    <meta property="og:title"       content="@yield('og_title', config('app.name') . ' – India\'s Complete Real Estate Ecosystem')">
    <meta property="og:description" content="@yield('og_description', 'India\'s complete real estate ecosystem — verified properties, new projects, home loans, insurance, legal help, service providers &amp; a home marketplace all in one place.')">
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
    <meta name="twitter:title"       content="@yield('twitter_title', config('app.name') . ' – India\'s Complete Real Estate Ecosystem')">
    <meta name="twitter:description" content="@yield('twitter_description', 'India\'s complete real estate ecosystem — verified properties, new projects, home loans, insurance, legal help, service providers &amp; a home marketplace all in one place.')">
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
      "description": "{{ config("app.name") }} is India's complete real estate ecosystem – verified properties for sale and rent, new projects, home loans, property insurance, legal help, service providers and a home marketplace across Chandigarh Tricity, Pune, Bangalore, Hyderabad and Delhi NCR.",
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
    <link rel="stylesheet" href="{{ asset('assets/css/frontend/layout.css') }}">
    @stack('styles')
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

    {{-- AI Chat Assistant — floating widget (available on every page) --}}
    @include('frontend.partials.ai-chat-widget')
</body>
</html>
