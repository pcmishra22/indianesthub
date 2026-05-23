@extends('frontend.layout')

@php
    // Build a canonical URL that includes relevant filters and pagination (except page 1)
    $params = request()->query();
    
    // Remove redundant page=1 parameter
    if (isset($params['page']) && $params['page'] <= 1) {
        unset($params['page']);
    }
    $canonicalUrl = count($params) ? url()->current() . '?' . http_build_query($params) : url()->current();
@endphp

{{-- ════════════════════════ SEO META ════════════════════════ --}}
@section('title', $seoTitle)
@section('meta_description', $seoDesc)
@section('meta_keywords', $h1 . ', property in ' . $cityLabel . ', real estate ' . $cityLabel . ', ' . config('app.name') . ', tricity real estate')
@section('canonical', $canonicalUrl)
@section('og_title', $h1 . ' | ' . config('app.name'))
@section('og_description', $seoDesc)
@section('og_url', url()->current())

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Properties","item":"{{ route('properties') }}"},
    {"@type":"ListItem","position":3,"name":"{{ $cityLabel }}","item":"{{ url('/properties/in/' . $citySlug) }}"},
    {"@type":"ListItem","position":4,"name":"{{ $h1 }}","item":"{{ url()->current() }}"}
  ]
}
</script>
@if($totalCount > 0)
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "ItemList",
  "name": "{{ $h1 }}",
  "description": "{{ $seoDesc }}",
  "numberOfItems": {{ $totalCount }},
  "url": "{{ url()->current() }}"
}
</script>
@endif
@if(count($faqs) > 0)
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    @foreach($faqs as $i => $faq)
    {
      "@type": "Question",
      "name": "{{ addslashes($faq['q']) }}",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "{{ addslashes($faq['a']) }}"
      }
    }{{ $i < count($faqs)-1 ? ',' : '' }}
    @endforeach
  ]
}
</script>
@endif
@endsection

{{-- ════════════════════════ PAGE STYLES ════════════════════════ --}}
@section('head')
<style>
.seo-hero {
  background: linear-gradient(135deg, #0a2d5e 0%, #0f4c81 55%, #1565c0 100%);
  padding: 56px 0 90px;
  position: relative;
  overflow: hidden;
}
.seo-hero::after {
  content: '';
  position: absolute;
  bottom: -2px; left: 0; right: 0;
  height: 60px;
  background: #f4f6f9;
  clip-path: ellipse(55% 100% at 50% 100%);
}
.seo-hero h1 { color:#fff; font-size:2.4rem; font-weight:800; letter-spacing:-0.5px; }
.seo-hero p  { color:rgba(255,255,255,.8); font-size:1.05rem; }
.seo-hero .breadcrumb-item a { color:rgba(255,255,255,.75); }
.seo-hero .breadcrumb-item.active { color:rgba(255,255,255,.55); }
.seo-hero .breadcrumb-item+.breadcrumb-item::before { color:rgba(255,255,255,.4); }
.stat-badge { display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.2); color:#fff; padding:6px 14px; border-radius:20px; font-size:.85rem; font-weight:600; margin:4px 4px 0 0; }
.seo-intro-box { background:#fff; border-left:4px solid #0078d4; padding:18px 22px; border-radius:0 8px 8px 0; margin-bottom:28px; }
.seo-intro-box p { margin:0; color:#475569; line-height:1.75; }
.prop-card { border:none; border-radius:12px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,.07); transition:transform .2s,box-shadow .2s; }
.prop-card:hover { transform:translateY(-4px); box-shadow:0 8px 28px rgba(0,0,0,.12); }
.prop-img { width:100%; height:200px; object-fit:cover; }
.prop-price { font-size:1.2rem; font-weight:800; color:#0a2d5e; }
.prop-badge { font-size:.7rem; font-weight:700; padding:3px 8px; border-radius:4px; }
.locality-chip { display:inline-block; background:#eef5fb; color:#0a2d5e; border:1px solid #bee3f8; padding:5px 12px; border-radius:20px; font-size:.83rem; font-weight:600; margin:3px; text-decoration:none; transition:background .2s; }
.locality-chip:hover { background:#0078d4; color:#fff; border-color:#0078d4; }
.faq-item { background:#fff; border-radius:10px; padding:20px 24px; margin-bottom:12px; box-shadow:0 1px 6px rgba(0,0,0,.06); }
.faq-item h5 { color:#0a2d5e; font-weight:700; margin-bottom:8px; font-size:1rem; }
.faq-item p  { color:#475569; margin:0; line-height:1.7; }
.city-link-card { display:flex; flex-direction:column; align-items:center; justify-content:center; background:#fff; border:1.5px solid #e2e8f0; border-radius:10px; padding:16px 10px; text-decoration:none; color:#0a2d5e; font-weight:700; font-size:.88rem; text-align:center; transition:all .2s; }
.city-link-card:hover { border-color:#0078d4; background:#eef5fb; color:#0078d4; }
.city-link-card i { font-size:1.6rem; color:#0078d4; margin-bottom:6px; }
.section-title-line { display:inline-block; padding-bottom:10px; border-bottom:3px solid #0078d4; margin-bottom:0; }
</style>
@endsection

@section('content')

{{-- ════════════════════ HERO BANNER ════════════════════ --}}
<div class="seo-hero">
  <div class="container">
    <nav aria-label="breadcrumb" style="margin-bottom:10px;">
      <ol class="breadcrumb" style="background:none;padding:0;margin:0;">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('properties') }}">Properties</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/properties/in/' . $citySlug) }}">{{ $cityLabel }}</a></li>
        <li class="breadcrumb-item active">{{ $h1 }}</li>
      </ol>
    </nav>
    <h1>{{ $h1 }}</h1>
    <p>{{ $totalCount }}+ verified listings in {{ $areaLabel ? $areaLabel.', ' : '' }}{{ $cityLabel }} — {{ $city['desc'] ?? '' }}</p>
    <div class="mt-3">
      <span class="stat-badge"><i class="bi bi-house-check"></i> Verified Listings</span>
      <span class="stat-badge"><i class="bi bi-shield-check"></i> RERA Projects</span>
      <span class="stat-badge"><i class="bi bi-people"></i> Top Agents</span>
      <span class="stat-badge"><i class="bi bi-telephone"></i> Free Consultation</span>
      @if($budgetLabel)
      <span class="stat-badge" style="background:rgba(255,193,7,.25);border-color:rgba(255,193,7,.5);">
        <i class="bi bi-currency-rupee"></i> Under {{ $budgetLabel }}
      </span>
      @endif
    </div>
  </div>
</div>

<main class="main" style="background:#f4f6f9; padding-bottom:60px;">
  <div class="container py-4">

    {{-- ════════════════════ SEO INTRO ════════════════════ --}}
    <div class="seo-intro-box">
      <p>{{ $seoDesc }} Browse listings with photos, floor plans and agent contact details. Use the filter below to narrow by BHK type, price range and property status.</p>
    </div>

    {{-- ════════════════════ DEALERS / AGENTS GRID (dealers & agents pages) ════════════════════ --}}
    @if(in_array($pageType, ['dealers','agents']) && !empty($pageDealers) && $pageDealers->isNotEmpty())
    <div class="mb-5">
      <h2 class="fw-800 mb-3" style="font-size:1.5rem;color:#0a2d5e;">
        <span class="section-title-line">
          {{ $pageType === 'dealers' ? 'Property Dealers' : 'Real Estate Agents' }} in {{ $cityLabel }}
        </span>
      </h2>
      <div class="row g-3">
        @foreach($pageDealers as $dealer)
        @php
          $photo = $dealer->profile_photo
            ? asset('storage/'.$dealer->profile_photo)
            : asset('assets/img/agents/agent-'.($loop->index % 6 + 1).'.jpg');
        @endphp
        <div class="col-md-6 col-lg-4">
          <div class="d-flex gap-3 align-items-start p-3 bg-white rounded-3 h-100" style="box-shadow:0 2px 10px rgba(0,0,0,.07);">
            <img src="{{ $photo }}" alt="{{ $dealer->first_name }} {{ $dealer->last_name }}"
                 style="width:62px;height:62px;border-radius:50%;object-fit:cover;flex-shrink:0;border:2px solid #e2e8f0;">
            <div class="flex-grow-1 min-width-0">
              <strong style="color:#0a2d5e;font-size:.95rem;">{{ $dealer->first_name }} {{ $dealer->last_name }}</strong>
              @if($dealer->company_name)
                <p class="text-muted mb-1" style="font-size:.8rem;">{{ $dealer->company_name }}</p>
              @endif
              @if(!empty($dealer->specializations))
                <p class="mb-1" style="font-size:.78rem;color:#475569;">
                  {{ is_array($dealer->specializations) ? implode(', ', array_slice($dealer->specializations, 0, 2)) : $dealer->specializations }}
                </p>
              @endif
              @if($dealer->slug)
                <a href="{{ route('agent-profile', $dealer->slug) }}" class="btn btn-outline-primary btn-sm py-1 px-2" style="font-size:.74rem;">View Profile</a>
              @endif
            </div>
          </div>
        </div>
        @endforeach
      </div>
      <div class="text-center mt-3">
        <a href="{{ route('agents') }}?city={{ urlencode($cityLabel) }}" class="btn btn-primary px-4">
          View All Agents in {{ $cityLabel }}
        </a>
      </div>
    </div>
    @endif

    {{-- ════════════════════ NEW PROJECTS (only for new-projects page) ════════════════════ --}}
    @if($pageType === 'new-projects' && $newProjects && $newProjects->isNotEmpty())
    <div class="mb-5">
      <h2 class="fw-800 mb-3" style="font-size:1.5rem;color:#0a2d5e;">
        <span class="section-title-line">New Launch Projects in {{ $cityLabel }}</span>
      </h2>
      <div class="row g-3">
        @foreach($newProjects as $project)
        <div class="col-md-4">
          <a href="{{ route('projects.show', $project->id) }}" class="text-decoration-none">
            <div class="prop-card card h-100">
              @php
                $projImg = $project->cover_image ? asset('storage/'.$project->cover_image) : asset('assets/img/og-default.jpg');
              @endphp
              <img src="{{ $projImg }}" class="prop-img" alt="{{ $project->title }} – {{ $cityLabel }}" loading="lazy">
              <div class="card-body p-3">
                <span class="badge bg-warning text-dark prop-badge mb-1">{{ $project->status }}</span>
                <h3 class="fw-700 mb-1" style="font-size:1rem;color:#0a2d5e;">{{ $project->title }}</h3>
                <p class="text-muted mb-1" style="font-size:.83rem;"><i class="bi bi-geo-alt me-1"></i>{{ $project->city ?? $cityLabel }}</p>
                @if($project->price_from)
                  <div class="prop-price">₹{{ number_format($project->price_from/100000, 1) }}L+</div>
                @endif
                <small class="text-muted">By {{ $project->builder->company_name ?? $project->builder->name ?? 'Builder' }}</small>
              </div>
            </div>
          </a>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    {{-- ════════════════════ PROPERTY LISTINGS ════════════════════ --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h2 class="fw-800 mb-0" style="font-size:1.4rem;color:#0a2d5e;">
        <span class="section-title-line">{{ $properties->total() }} Properties Found</span>
      </h2>
      <a href="{{ route('properties') }}?city={{ urlencode($cityLabel) }}" class="btn btn-outline-primary btn-sm">View All</a>
    </div>

    @if($properties->isEmpty())
      <div class="text-center py-5">
        <i class="bi bi-house-slash" style="font-size:3rem;color:#94a3b8;"></i>
        <h3 class="mt-3" style="color:#475569;">No listings found right now</h3>
        <p class="text-muted">Check back soon or <a href="{{ route('properties') }}?city={{ urlencode($cityLabel) }}">browse all properties in {{ $cityLabel }}</a>.</p>
      </div>
    @else
    <div class="row g-3 mb-4">
      @foreach($properties as $property)
      @php
        $img = null;
        if (!empty($property->cover_image) && file_exists(storage_path('app/public/'.$property->cover_image))) {
          $img = asset('storage/'.$property->cover_image);
        } elseif ($property->images && $property->images->isNotEmpty()) {
          $img = asset('storage/'.$property->images->first()->image_path);
        }
        if (!$img) {
          $fallback = ['property-exterior-1.webp','property-exterior-2.webp','property-exterior-3.webp','property-interior-1.webp','property-interior-2.webp'];
          $img = asset('assets/img/real-estate/'.$fallback[$property->id % count($fallback)]);
        }
        $price = $property->price;
        $priceStr = $price >= 10000000 ? '₹'.number_format($price/10000000,2).' Cr' : ($price >= 100000 ? '₹'.number_format($price/100000,2).' L' : '₹'.number_format($price));
        $agentName = $property->dealer?->first_name ? ($property->dealer->first_name.' '.$property->dealer->last_name) : ($property->builder?->company_name ?? config('app.name'));
      @endphp
      <div class="col-md-6 col-lg-4">
        <a href="{{ route('property-details', $property->slug) }}" class="text-decoration-none d-block h-100">
          <div class="prop-card card h-100">
            <div class="position-relative">
              <img src="{{ $img }}" class="prop-img" alt="{{ $property->bhk_type ?? '' }} {{ $property->property_type ?? 'Property' }} in {{ $property->locality ?? $cityLabel }}" loading="lazy">
              @if($property->is_featured || $property->is_premium || $property->is_boosted)
                <span class="badge bg-warning text-dark prop-badge position-absolute" style="top:8px;left:8px;">Featured</span>
              @endif
              <span class="badge {{ $property->looking_for === 'Rent' ? 'bg-info' : 'bg-success' }} prop-badge position-absolute" style="top:8px;right:8px;">
                For {{ $property->looking_for ?? 'Sale' }}
              </span>
            </div>
            <div class="card-body p-3">
              <div class="prop-price mb-1">{{ $priceStr }}</div>
              <h3 class="fw-600 mb-1" style="font-size:.93rem;color:#1e293b;line-height:1.3;">{{ $property->title }}</h3>
              <p class="text-muted mb-1" style="font-size:.82rem;">
                <i class="bi bi-geo-alt me-1"></i>{{ $property->locality ?? '' }}{{ $property->locality && $property->city ? ', ' : '' }}{{ $property->city ?? $cityLabel }}
              </p>
              <div class="d-flex flex-wrap gap-1 mt-1">
                @if($property->bhk_type)
                  <span class="badge bg-light text-dark border" style="font-size:.72rem;">{{ $property->bhk_type }}</span>
                @endif
                @if($property->area)
                  <span class="badge bg-light text-dark border" style="font-size:.72rem;">{{ number_format($property->area) }} sqft</span>
                @endif
                @if($property->furnishing_status)
                  <span class="badge bg-light text-dark border" style="font-size:.72rem;">{{ $property->furnishing_status }}</span>
                @endif
              </div>
              <div class="d-flex align-items-center justify-content-between mt-2 pt-2" style="border-top:1px solid #f1f5f9;">
                <small class="text-muted"><i class="bi bi-person me-1"></i>{{ $agentName }}</small>
                <span class="btn btn-primary btn-sm py-1 px-3" style="font-size:.75rem;">View</span>
              </div>
            </div>
          </div>
        </a>
      </div>
      @endforeach
    </div>
    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
      {{ $properties->links('vendor.pagination.indianesthub') }}
    </div>
    @endif

    {{-- ════════════════════ EXPLORE LOCALITIES ════════════════════ --}}
    @if(count($subLocalities) > 0)
    <div class="mt-5 mb-4">
      <h2 class="fw-800 mb-3" style="font-size:1.3rem;color:#0a2d5e;">
        <span class="section-title-line">Explore Popular Localities in {{ $cityLabel }}</span>
      </h2>
      <div class="d-flex flex-wrap">
        @foreach($subLocalities as $sub)
          <a href="{{ route('properties') }}?city={{ urlencode($cityLabel) }}&locality={{ urlencode($sub) }}"
             class="locality-chip">
            <i class="bi bi-geo-alt me-1"></i>{{ $sub }}
          </a>
        @endforeach
      </div>
    </div>
    @endif

    {{-- ════════════════════ EXPLORE OTHER CITIES ════════════════════ --}}
    <div class="mt-5 mb-4">
      <h2 class="fw-800 mb-3" style="font-size:1.3rem;color:#0a2d5e;">
        <span class="section-title-line">Similar Pages in Other Cities</span>
      </h2>
      <div class="row g-2">
        @foreach($allCities as $slug => $cityData)
          @if($slug !== $citySlug)
          <div class="col-6 col-md-3 col-lg-2">
            @php
              $otherUrl = match($pageType) {
                'flats'         => url('/flats-in-'.$slug),
                'rent-flats'    => url('/rent-flats-in-'.$slug),
                'plots'         => url('/plots-in-'.$slug),
                'villas'        => url('/villas-in-'.$slug),
                'new-projects'  => url('/new-projects-in-'.$slug),
                'ready-to-move' => url('/ready-to-move-flats-'.$slug),
                'bhk-flats'     => url('/flats-in-'.$slug),
                'house'         => url('/independent-house-for-sale-in-'.$slug),
                'commercial'    => url('/commercial-property-in-'.$slug),
                'dealers'       => url('/property-dealers-in-'.$slug),
                'agents'        => url('/real-estate-agents-in-'.$slug),
                'upcoming'      => url('/upcoming-projects-in-'.$slug),
                'rera'          => url('/rera-approved-projects-in-'.$slug),
                'investment'    => url('/investment-property-in-'.$slug),
                'best-projects' => url('/best-residential-projects-in-'.$slug),
                default         => url('/properties/in/'.$slug),
              };
            @endphp
            <a href="{{ $otherUrl }}" class="city-link-card">
              <i class="bi bi-buildings"></i>
              {{ $cityData['label'] }}
            </a>
          </div>
          @endif
        @endforeach
      </div>
    </div>

    {{-- ════════════════════ WHY IndianestHub ════════════════════ --}}
    <div class="row g-3 mt-4 mb-5">
      <div class="col-12">
        <h2 class="fw-800 mb-3" style="font-size:1.3rem;color:#0a2d5e;">
          <span class="section-title-line">Why Find {{ $h1 }} on {{ config('app.name') }}?</span>
        </h2>
      </div>
      @foreach([
        ['bi-shield-check','Verified Listings','Every property is verified with agent contact details and ownership documents.'],
        ['bi-camera','Photos & Floor Plans','Browse listings with multiple photos, floor plans and virtual tours.'],
        ['bi-people','Top Local Agents','Connect directly with verified dealers and agents in '.$cityLabel.'.'],
        ['bi-bank','Free Home Loan Check','Get instant home loan eligibility check with leading banks.'],
        ['bi-star','RERA Registered Projects','Find RERA-registered builder projects with transparent pricing.'],
        ['bi-telephone','No Brokerage Queries','Direct owner listings available. Save on brokerage.'],
      ] as $feat)
      <div class="col-md-4">
        <div class="d-flex gap-3 align-items-start p-3 bg-white rounded-3 h-100" style="box-shadow:0 1px 6px rgba(0,0,0,.06);">
          <i class="bi {{ $feat[0] }}" style="font-size:1.8rem;color:#0078d4;flex-shrink:0;"></i>
          <div>
            <strong style="color:#0a2d5e;">{{ $feat[1] }}</strong>
            <p class="text-muted mb-0 mt-1" style="font-size:.85rem;line-height:1.5;">{{ $feat[2] }}</p>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    {{-- ════════════════════ FAQ SECTION ════════════════════ --}}
    <div class="mt-4 mb-5">
      <h2 class="fw-800 mb-3" style="font-size:1.3rem;color:#0a2d5e;">
        <span class="section-title-line">Frequently Asked Questions</span>
      </h2>
      @foreach($faqs as $faq)
      <div class="faq-item">
        <h5>{{ $faq['q'] }}</h5>
        <p>{{ $faq['a'] }}</p>
      </div>
      @endforeach
    </div>

    {{-- ════════════════════ CTA STRIP ════════════════════ --}}
    <div class="text-center py-4 px-4 rounded-3 mb-4"
         style="background:linear-gradient(135deg,#0a2d5e,#0078d4);color:#fff;">
      <h3 class="fw-800 mb-2">Looking to sell or rent your property in {{ $cityLabel }}?</h3>
      <p class="mb-3" style="opacity:.85;">List your property on {{ config('app.name') }} and reach lakhs of verified buyers & renters today.</p>
      <a href="{{ route('dealer.register') }}" class="btn btn-warning fw-700 px-4 py-2 me-2">Post Free Property</a>
      <a href="{{ route('contact') }}" class="btn btn-outline-light fw-600 px-4 py-2">Contact Us</a>
    </div>

  </div>
</main>

@endsection
