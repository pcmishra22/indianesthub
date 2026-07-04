{{-- resources/views/frontend/index.blade.php --}}
@extends('frontend.layout')

{{-- ════════════════════════ SEO META ════════════════════════ --}}
@section('title', config('app.name') . ' – Property in Top Cities Across India')
@section('meta_description', 'Find verified flats, villas & plots for sale and rent in Chandigarh, Mohali, Zirakpur & Panchkula. Browse 2,000+ listings from trusted dealers & builders.')
@section('meta_keywords', 'property in chandigarh, flats in mohali, property in zirakpur, real estate tricity, buy flat chandigarh, rent flat mohali, new projects zirakpur, 3bhk flats mohali, villas chandigarh, plots panchkula')
@section('canonical', url('/'))
@section('og_title', config('app.name') . ' – Chandigarh Tricity\'s #1 Real Estate Portal')
@section('og_description', 'Find verified flats, villas, plots & new projects in Chandigarh, Mohali, Zirakpur & Panchkula. Buy, sell or rent with confidence on ' . config('app.name') . '.')

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "WebSite",
  "name": "{{ config('app.name') }}",
  "url": "{{ url('/') }}",
  "description": "India's most trusted real estate portal for Chandigarh Tricity",
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
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "How do I find flats for sale in Zirakpur?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Visit {{ config('app.name') }} and browse our verified listings at /flats-in-zirakpur or use the search bar to filter by location, BHK, and budget."
      }
    },
    {
      "@type": "Question",
      "name": "Are the property listings on {{ config('app.name') }} verified?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, all property listings on {{ config('app.name') }} go through a verification process. Dealers and builders are required to provide valid contact details and property documents."
      }
    },
    {
      "@type": "Question",
      "name": "Which cities does {{ config('app.name') }} cover?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "{{ config('app.name') }} covers all major cities in Chandigarh Tricity – Chandigarh, Mohali, Zirakpur, Panchkula, Kharar, Derabassi, Mullanpur and surrounding areas."
      }
    }
  ]
}
</script>
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('assets/css/frontend/pages.css') }}">
@endsection

@section('content')

{{-- ════════════════════════════════════════════════════════
     HERO
════════════════════════════════════════════════════════ --}}
@include('frontend.partials.hero', [
  'totalProperties' => $totalProperties ?? 0,
  'totalDealers'    => $totalDealers ?? 0,
  'totalCities'     => $totalCities ?? 0,
  'totalBuilders'   => $totalBuilders ?? 0,
  'newLaunches'     => $newLaunches ?? collect(),
])

{{-- ════════════════════════════════════════════════════════
     BROWSE BY PROPERTY TYPE
════════════════════════════════════════════════════════ --}}
<section class="hs-section white">
  <div class="container">
    <!--<div class="col-12 mb-4">
      <img
        src="{{ asset('assets/img/loan_and_properties_banner.png') }}"
        alt="Loans & Properties"
        class="img-fluid rounded"
        loading="lazy"
      >
    </div>-->

    <div class="hs-section-title">
      <span class="hs-badge">Browse by Category</span>
      <h2>What Are You Looking For?</h2>
      <p>Find properties by type across Chandigarh Tricity</p>
    </div>
    <div class="prop-type-grid">
      @php
        $typeConfig = [
          'Apartment'   => ['icon'=>'bi-building','color'=>'#dbeafe','text'=>'#1d4ed8','label'=>'Apartment / Flat'],
          'Villa'       => ['icon'=>'bi-house','color'=>'#dcfce7','text'=>'#15803d','label'=>'Villa / House'],
          'Plot'        => ['icon'=>'bi-map','color'=>'#fef3c7','text'=>'#b45309','label'=>'Plot / Land'],
          'Commercial'  => ['icon'=>'bi-shop','color'=>'#fce7f3','text'=>'#be185d','label'=>'Commercial'],
          'Penthouse'   => ['icon'=>'bi-building-up','color'=>'#ede9fe','text'=>'#7c3aed','label'=>'Penthouse'],
          'Residential' => ['icon'=>'bi-house-door','color'=>'#ecfdf5','text'=>'#065f46','label'=>'Residential'],
        ];
      @endphp
      @foreach($typeConfig as $type => $cfg)
        @php $cnt = $propertyTypes[$type] ?? 0; @endphp
        <a href="{{ route('properties', ['property_type' => $type]) }}" class="prop-type-chip">
          <div class="icon" style="background:{{ $cfg['color'] }};color:{{ $cfg['text'] }};">
            <i class="bi {{ $cfg['icon'] }}"></i>
          </div>
          <div class="label">{{ $cfg['label'] }}</div>
          <div class="cnt">{{ $cnt > 0 ? $cnt.' listings' : 'Available' }}</div>
        </a>
      @endforeach
      {{-- Extra static types --}}
      <a href="{{ route('properties', ['looking_for' => 'PG']) }}" class="prop-type-chip">
        <div class="icon" style="background:#fff7ed;color:#ea580c;"><i class="bi bi-people"></i></div>
        <div class="label">PG / Co-living</div>
        <div class="cnt">Available</div>
      </a>
      <a href="{{ route('builders.index') }}" class="prop-type-chip">
        <div class="icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-building-add"></i></div>
        <div class="label">New Projects</div>
        <div class="cnt">{{ $newLaunches->count() }}+ projects</div>
      </a>
    </div>
  </div>
</section>

{{-- ════════════════════════════════════════════════════════
     FEATURED PROPERTIES
════════════════════════════════════════════════════════ --}}
@php
  $fallbackImages = [
    'property-exterior-1.webp','property-exterior-2.webp','property-exterior-3.webp',
    'property-exterior-4.webp','property-exterior-5.webp','property-exterior-7.webp',
    'property-exterior-8.webp','property-exterior-9.webp',
    'property-interior-1.webp','property-interior-2.webp',
    'property-interior-4.webp','property-interior-5.webp',
  ];
  function homeImg($property, $fallbackImages) {
    if (!empty($property->cover_image) && file_exists(storage_path('app/public/' . $property->cover_image)))
      return asset('storage/' . $property->cover_image);
    if ($property->images && $property->images->isNotEmpty() && file_exists(storage_path('app/public/' . $property->images->first()->image_path)))
      return asset('storage/' . $property->images->first()->image_path);
    return asset('assets/img/real-estate/' . $fallbackImages[$property->id % count($fallbackImages)]);
  }
  function homeFmt($price) {
    if ($price >= 10000000) return '₹' . number_format($price/10000000,2) . ' Cr';
    if ($price >= 100000)   return '₹' . number_format($price/100000,2) . ' L';
    return '₹' . number_format($price);
  }
@endphp

<section class="hs-section">
  <div class="container">
    <div class="hs-section-title">
      <span class="hs-badge">Top Picks</span>
      <h2>Featured Properties</h2>
      <p>Handpicked premium listings across Chandigarh, Mohali &amp; Zirakpur</p>
    </div>
    <div class="row g-4">



      @forelse($featuredProperties->take(6) as $property)
        @php
          $imgUrl = homeImg($property, $fallbackImages);
          $lf     = strtolower($property->looking_for ?? '');
          $agentName  = $property->dealer?->name ?? ($property->builder?->company_name ?? $property->builder?->name ?? config('app.name'));
          $agentAvatar = asset('assets/img/real-estate/agent-' . (($property->id % 10) + 1) . '.webp');
        @endphp
        <div class="col-lg-4 col-md-6">
          <div class="prop-card h-100">
            <div class="prop-card-img">
              <img src="{{ $imgUrl }}" alt="{{ $property->title }}" loading="lazy">
              <div class="prop-img-overlay"></div>
              <div class="prop-badges">
                @if(in_array($lf,['sale','sell','buy'])) <span class="prop-badge badge-sale">For Sale</span>
                @elseif($lf==='rent') <span class="prop-badge badge-rent">For Rent</span>
                @elseif($lf==='pg') <span class="prop-badge badge-pg">PG</span>
                @else <span class="prop-badge badge-sale">{{ $property->looking_for }}</span> @endif
                @if($property->is_featured) <span class="prop-badge badge-featured">Featured</span> @endif
                @if($property->is_premium)  <span class="prop-badge badge-premium">Premium</span> @endif
                @if($property->builder_id)  <span class="prop-badge badge-builder">Builder</span> @endif
              </div>
              <button class="prop-wishlist-btn"><i class="bi bi-heart"></i></button>
              <div class="prop-price-bar">
                <span class="prop-price">{{ homeFmt($property->price) }}</span>
              </div>
            </div>
            <div class="prop-card-body">
              @if($property->property_type) <span class="prop-type-tag">{{ $property->property_type }}</span> @endif
              <div class="prop-title">{{ $property->title }}</div>
              <div class="prop-addr"><i class="bi bi-geo-alt-fill"></i> {{ $property->locality ? $property->locality.', ':'' }}{{ $property->city }}</div>
              <div class="prop-specs">
                @if($property->bedrooms) <div class="prop-spec"><i class="bi bi-house-door"></i> {{ $property->bedrooms }} Bed</div> @endif
                @if($property->bathrooms)<div class="prop-spec"><i class="bi bi-droplet"></i> {{ $property->bathrooms }} Bath</div> @endif
                @if($property->area)     <div class="prop-spec"><i class="bi bi-arrows-angle-expand"></i> {{ number_format($property->area) }} sqft</div> @endif
              </div>
              <div class="prop-footer">
                <div class="prop-agent">
                  <img src="{{ $agentAvatar }}" alt="agent" class="prop-agent-avatar" onerror="this.src='/assets/img/real-estate/agent-1.webp'">
                  <div class="prop-agent-name">{{ $agentName }}</div>
                </div>
                <a href="{{ route('property-details', $property) }}" class="btn-view-prop">View <i class="bi bi-arrow-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      @empty
        {{-- Show latest if no featured --}}
        @foreach($latestProperties->take(6) as $property)
          @php $imgUrl = homeImg($property, $fallbackImages); $lf = strtolower($property->looking_for ?? '');
               $agentName = $property->dealer?->name ?? ($property->builder?->company_name ?? config('app.name'));
               $agentAvatar = asset('assets/img/real-estate/agent-' . (($property->id % 10) + 1) . '.webp');
          @endphp
          <div class="col-lg-4 col-md-6">
            <div class="prop-card h-100">
              <div class="prop-card-img">
                <img src="{{ $imgUrl }}" alt="{{ $property->title }}" loading="lazy">
                <div class="prop-img-overlay"></div>
                <div class="prop-badges">
                  @if(in_array($lf,['sale','sell','buy'])) <span class="prop-badge badge-sale">For Sale</span>
                  @elseif($lf==='rent') <span class="prop-badge badge-rent">For Rent</span>
                  @else <span class="prop-badge badge-sale">{{ $property->looking_for }}</span> @endif
                  <span class="prop-badge badge-new">New</span>
                </div>
                <button class="prop-wishlist-btn"><i class="bi bi-heart"></i></button>
                <div class="prop-price-bar">
                  <span class="prop-price">{{ homeFmt($property->price) }}</span>
                </div>
              </div>
              <div class="prop-card-body">
                @if($property->property_type) <span class="prop-type-tag">{{ $property->property_type }}</span> @endif
                <div class="prop-title">{{ $property->title }}</div>
                <div class="prop-addr"><i class="bi bi-geo-alt-fill"></i> {{ $property->locality ? $property->locality.', ':'' }}{{ $property->city }}</div>
                <div class="prop-specs">
                  @if($property->bedrooms) <div class="prop-spec"><i class="bi bi-house-door"></i> {{ $property->bedrooms }} Bed</div> @endif
                  @if($property->bathrooms)<div class="prop-spec"><i class="bi bi-droplet"></i> {{ $property->bathrooms }} Bath</div> @endif
                  @if($property->area)     <div class="prop-spec"><i class="bi bi-arrows-angle-expand"></i> {{ number_format($property->area) }} sqft</div> @endif
                </div>
                <div class="prop-footer">
                  <div class="prop-agent">
                    <img src="{{ $agentAvatar }}" alt="agent" class="prop-agent-avatar">
                    <div class="prop-agent-name">{{ $agentName }}</div>
                  </div>
                  <a href="{{ route('property-details', $property) }}" class="btn-view-prop">View <i class="bi bi-arrow-right"></i></a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      @endforelse
    </div>
    <div class="text-center mt-5">
      <a href="{{ route('properties') }}" class="btn-view-prop" style="padding:12px 32px;font-size:.95rem;border-radius:12px;">
        View All Properties <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
  </div>
</section>

{{-- ════════════════════════════════════════════════════════
     NEW LAUNCHES — Builder Projects
════════════════════════════════════════════════════════ --}}
@if($newLaunches->count() > 0)
<section class="hs-section white">
  <div class="container">
    <div class="hs-section-title">
      <span class="hs-badge">Hot New Launches</span>
      <h2>New Projects & Launches</h2>
      <p>Upcoming and under-construction projects from top builders</p>
    </div>
    <div class="row g-4">
      @foreach($newLaunches as $launch)
        @php
          $launchImg = null;
          if (!empty($launch->cover_image) && file_exists(storage_path('app/public/'.$launch->cover_image)))
            $launchImg = asset('storage/'.$launch->cover_image);
          else
            $launchImg = asset('assets/img/real-estate/property-exterior-' . (($launch->id % 9) + 1) . '.webp');
          $priceFrom = $launch->price_from ? homeFmt($launch->price_from) : null;
          $priceTo   = $launch->price_to   ? homeFmt($launch->price_to)   : null;
        @endphp
        <div class="col-lg-3 col-md-6">
          <a href="{{ route('projects.show', $launch->id) }}" class="launch-card d-block text-decoration-none h-100">
            <div class="launch-card-img">
              <img src="{{ $launchImg }}" alt="{{ $launch->title }}" loading="lazy">
              @php
                $sc = match($launch->status) { 'Upcoming' => 'status-upcoming', 'Under Construction' => 'status-under', 'Ready to Move' => 'status-ready', default => 'status-under' };
              @endphp
              <span class="launch-status-badge {{ $sc }}">{{ $launch->status }}</span>
              @if($launch->builder)
                <span class="launch-builder-badge"><i class="bi bi-building me-1"></i>{{ Str::limit($launch->builder->company_name ?? $launch->builder->name, 18) }}</span>
              @endif
            </div>
            <div class="launch-card-body">
              <div class="launch-title">{{ $launch->title }}</div>
              <div class="launch-loc"><i class="bi bi-geo-alt-fill"></i> {{ $launch->city }}{{ $launch->state ? ', '.$launch->state : '' }}</div>
              @if($priceFrom)
                <div class="launch-price">{{ $priceFrom }}</div>
                <div class="launch-price-label">onwards @if($priceTo) – {{ $priceTo }} @endif</div>
              @else
                <div class="launch-price-label">Price on Request</div>
              @endif
              <div class="launch-meta">
                @if($launch->total_units) <div class="launch-meta-item"><i class="bi bi-building"></i> {{ $launch->total_units }} Units</div> @endif
                @if($launch->possession_date) <div class="launch-meta-item"><i class="bi bi-calendar-check"></i> {{ \Carbon\Carbon::parse($launch->possession_date)->format('M Y') }}</div> @endif
                @if($launch->rera_id) <div class="launch-meta-item"><i class="bi bi-patch-check"></i> RERA</div> @endif
              </div>
            </div>
          </a>
        </div>
      @endforeach
    </div>
    <div class="text-center mt-5">
      <a href="{{ route('builders.index') }}" class="btn-view-prop" style="padding:12px 32px;font-size:.95rem;border-radius:12px;">
        Explore All Projects <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
  </div>
</section>
@endif

{{-- ════════════════════════════════════════════════════════
     TOP CITIES
════════════════════════════════════════════════════════ --}}
@if($topCities->count() > 0)
<section class="hs-section">
  <div class="container">
    <div class="hs-section-title">
      <span class="hs-badge">Location</span>
      <h2>Browse by City</h2>
      <p>Find properties in Tricity's most sought-after locations</p>
    </div>
    @php
      $cityImages = [
        'Mumbai'    => 'property-exterior-7.webp',
        'Bengaluru' => 'property-exterior-8.webp',
        'Gurugram'  => 'property-exterior-4.webp',
        'Delhi'     => 'property-exterior-5.webp',
        'Hyderabad' => 'property-exterior-3.webp',
        'Pune'      => 'property-exterior-2.webp',
        'Chennai'   => 'property-exterior-1.webp',
        'Kolkata'   => 'property-exterior-9.webp',
      ];
      $cityColors = [
        '#0a2d5e','#0f4c81','#1565c0','#0e7490','#065f46','#7c3aed','#be185d','#b45309'
      ];
    @endphp
    <div class="row g-3">
      @foreach($topCities as $i => $cityData)
        @php
          $cityName = $cityData->city;
          $count    = $cityData->count;
          $img = $cityImages[$cityName] ?? $fallbackImages[$i % count($fallbackImages)];
          $color = $cityColors[$i % count($cityColors)];
        @endphp
        <div class="col-lg-3 col-md-6 mb-4"> {{-- Changed to consistently show 4 images per line --}}
          <a href="{{ route('properties', ['city' => $cityName]) }}" class="city-card">
            <img src="/assets/img/real-estate/{{ $img }}" alt="{{ $cityName }}" loading="lazy">
            <div class="city-card-overlay"></div>
            <div class="city-card-info">
              <div class="city-card-name"><i class="bi bi-geo-alt me-1"></i>{{ $cityName }}</div>
              <div class="city-card-count">{{ number_format($count) }} Properties Available</div>
            </div>
          </a>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ════════════════════════════════════════════════════════
     LATEST PROPERTIES
════════════════════════════════════════════════════════ --}}
<section class="hs-section white">
  <div class="container">
    <div class="hs-section-title">
      <span class="hs-badge">Just Added</span>
      <h2>Latest Properties</h2>
      <p>Fresh listings added recently — be the first to explore</p>
    </div>
    <div class="row g-4">
      @foreach($latestProperties->take(8) as $property)
        @php
          $imgUrl = homeImg($property, $fallbackImages);
          $lf     = strtolower($property->looking_for ?? '');
          $agentName  = $property->dealer?->name ?? ($property->builder?->company_name ?? $property->builder?->name ?? config('app.name'));
          $agentAvatar = asset('assets/img/real-estate/agent-' . (($property->id % 10) + 1) . '.webp');
          $isNew = $property->created_at && $property->created_at->diffInDays(now()) <= 7;
        @endphp
        <div class="col-lg-3 col-md-6">
          <div class="prop-card h-100">
            <div class="prop-card-img" style="height:180px;">
              <img src="{{ $imgUrl }}" alt="{{ $property->title }}" loading="lazy">
              <div class="prop-img-overlay"></div>
              <div class="prop-badges">
                @if(in_array($lf,['sale','sell','buy'])) <span class="prop-badge badge-sale">For Sale</span>
                @elseif($lf==='rent') <span class="prop-badge badge-rent">For Rent</span>
                @elseif($lf==='pg')  <span class="prop-badge badge-pg">PG</span>
                @else <span class="prop-badge badge-sale">{{ $property->looking_for }}</span> @endif
                @if($isNew) <span class="prop-badge badge-new">New</span> @endif
              </div>
              <button class="prop-wishlist-btn"><i class="bi bi-heart"></i></button>
              <div class="prop-price-bar">
                <span class="prop-price" style="font-size:1rem;">{{ homeFmt($property->price) }}</span>
              </div>
            </div>
            <div class="prop-card-body">
              @if($property->property_type) <span class="prop-type-tag">{{ $property->property_type }}</span> @endif
              <div class="prop-title">{{ Str::limit($property->title, 50) }}</div>
              <div class="prop-addr"><i class="bi bi-geo-alt-fill"></i> {{ $property->locality ? $property->locality.', ':'' }}{{ $property->city }}</div>
              <div class="prop-specs">
                @if($property->bedrooms) <div class="prop-spec"><i class="bi bi-house-door"></i> {{ $property->bedrooms }}Bed</div> @endif
                @if($property->bathrooms)<div class="prop-spec"><i class="bi bi-droplet"></i> {{ $property->bathrooms }}Bath</div> @endif
                @if($property->area)     <div class="prop-spec"><i class="bi bi-arrows-angle-expand"></i> {{ number_format($property->area) }}sqft</div> @endif
              </div>
              <div class="prop-footer">
                <div class="prop-agent">
                  <img src="{{ $agentAvatar }}" alt="agent" class="prop-agent-avatar" onerror="this.src='/assets/img/real-estate/agent-1.webp'">
                  <div class="prop-agent-name">{{ Str::limit($agentName, 16) }}</div>
                </div>
                <a href="{{ route('property-details', $property) }}" class="btn-view-prop">View</a>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
    <div class="text-center mt-5">
      <a href="{{ route('properties') }}" class="btn-view-prop" style="padding:12px 32px;font-size:.95rem;border-radius:12px;">
        Browse All {{ number_format($totalProperties) }}+ Listings <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
  </div>
</section>

{{-- ════════════════════════════════════════════════════════
     HOW IT WORKS
════════════════════════════════════════════════════════ --}}
<section class="hs-section">
  <div class="container">
    <div class="hs-section-title">
      <span class="hs-badge">Simple Process</span>
      <h2>How It Works</h2>
      <p>Find your perfect property in 3 easy steps</p>
    </div>
    <div class="row g-4 align-items-center">
      <div class="col-lg-3 col-md-6">
        <div class="hiw-card">
          <div class="hiw-step-num">1</div>
          <div class="hiw-icon" style="background:#dbeafe;color:#1d4ed8;"><i class="bi bi-search"></i></div>
          <div class="hiw-title">Search Properties</div>
          <p class="hiw-desc">Use our powerful search to filter by city, budget, BHK type, and more to find your ideal property.</p>
        </div>
      </div>
      <div class="col-lg-1 d-none d-lg-flex">
        <div class="hiw-connector"><i class="bi bi-arrow-right"></i></div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="hiw-card">
          <div class="hiw-step-num">2</div>
          <div class="hiw-icon" style="background:#dcfce7;color:#15803d;"><i class="bi bi-person-check"></i></div>
          <div class="hiw-title">Connect with Agent</div>
          <p class="hiw-desc">Contact verified dealers or builders directly. Schedule a site visit or request a callback.</p>
        </div>
      </div>
      <div class="col-lg-1 d-none d-lg-flex">
        <div class="hiw-connector"><i class="bi bi-arrow-right"></i></div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="hiw-card">
          <div class="hiw-step-num">3</div>
          <div class="hiw-icon" style="background:#fef3c7;color:#b45309;"><i class="bi bi-key"></i></div>
          <div class="hiw-title">Move In</div>
          <p class="hiw-desc">Complete the documentation, make the deal, and get the keys to your new home. It's that simple!</p>
        </div>
      </div>
      <div class="col-lg-1 d-none d-lg-flex">
        <div class="hiw-connector"><i class="bi bi-arrow-right"></i></div>
      </div>
      <div class="col-lg-1 d-none d-lg-block"></div>
    </div>
  </div>
</section>

{{-- ════════════════════════════════════════════════════════
     WHY HOMESPACE
════════════════════════════════════════════════════════ --}}
<section class="hs-section white">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-5">
        <span class="hs-badge">Why Us</span>
        <h2 class="fw-800 mt-2 mb-4" style="font-size:2rem;color:var(--text-dark);font-weight:800;">Why Choose <span style="color:#0078d4;">{{ config('app.name') }}?</span></h2>
        <p style="color:#64748b;line-height:1.8;margin-bottom:24px;">We started as Chandigarh Tricity's dedicated real estate platform and now connect home seekers with verified dealers and top builders across Chandigarh, Mohali, Zirakpur, Panchkula and expanding into Pune, Bangalore, Hyderabad & Delhi NCR. Our commitment to transparency and quality ensures you make informed decisions.</p>
        <div class="d-flex gap-4 flex-wrap" style="margin-bottom:28px;">
          <div class="text-center">
            <div style="font-size:1.8rem;font-weight:900;color:#0078d4;">{{ number_format($totalProperties) }}+</div>
            <div style="font-size:.8rem;color:#94a3b8;font-weight:600;">Active Listings</div>
          </div>
          <div class="text-center">
            <div style="font-size:1.8rem;font-weight:900;color:#0078d4;">{{ $totalCities ?? 14 }}+</div>
            <div style="font-size:.8rem;color:#94a3b8;font-weight:600;">Cities</div>
          </div>
          <div class="text-center">
            <div style="font-size:1.8rem;font-weight:900;color:#0078d4;">{{ $satisfactionRate ?? 96 }}%</div>
            <div style="font-size:.8rem;color:#94a3b8;font-weight:600;">Satisfaction</div>
          </div>
        </div>
        <a href="{{ url('/contact') }}" class="btn-view-prop" style="padding:12px 28px;font-size:.9rem;border-radius:12px;">Get In Touch</a>
      </div>
      <div class="col-lg-7">
        <div class="row g-3">
          @php
            $whyItems = [
              ['icon'=>'bi-patch-check','color'=>'#dbeafe','text'=>'#1d4ed8','title'=>'100% Verified Listings','desc'=>'Every property is physically verified by our team before listing. No fake listings, ever.'],
              ['icon'=>'bi-shield-lock','color'=>'#dcfce7','text'=>'#15803d','title'=>'Secure Transactions','desc'=>'Your data and transactions are protected with bank-grade security and RERA compliance.'],
              ['icon'=>'bi-headset','color'=>'#fce7f3','text'=>'#be185d','title'=>'24/7 Expert Support','desc'=>'Our dedicated team is always available to guide you through the entire buying/renting process.'],
              ['icon'=>'bi-graph-up-arrow','color'=>'#fef3c7','text'=>'#b45309','title'=>'Best Price Guarantee','desc'=>'We help you get the best deal with transparent pricing, market insights, and negotiation support.'],
              ['icon'=>'bi-building-check','color'=>'#ede9fe','text'=>'#7c3aed','title'=>'Top Builders Network','desc'=>'Access to '.($totalBuilders ?? 6).'+ verified builders and their exclusive new launch projects.'],
              ['icon'=>'bi-phone','color'=>'#ecfdf5','text'=>'#065f46','title'=>'Easy Mobile Access','desc'=>'Find, save and compare properties on the go. Our platform works seamlessly on all devices.'],
            ];
          @endphp
          @foreach($whyItems as $item)
            <div class="col-md-6">
              <div class="why-card">
                <div class="why-icon" style="background:{{ $item['color'] }};color:{{ $item['text'] }};"><i class="bi {{ $item['icon'] }}"></i></div>
                <div>
                  <div class="why-title">{{ $item['title'] }}</div>
                  <p class="why-desc">{{ $item['desc'] }}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ════════════════════════════════════════════════════════
     TOP BUILDERS
════════════════════════════════════════════════════════ --}}
@if($topBuilders->count() > 0)
<section class="hs-section">
  <div class="container">
    <div class="hs-section-title">
      <span class="hs-badge">Trusted Partners</span>
      <h2>Top Builders & Developers</h2>
      <p>Leading real estate developers building in Chandigarh Tricity</p>
    </div>
    <div class="row g-3">
      @foreach($topBuilders as $builder)
        <div class="col-lg-4 col-md-6">
          <a href="{{ route('builders.show', $builder) }}" class="builder-chip">
            @if($builder->logo && file_exists(storage_path('app/public/'.$builder->logo)))
              <img src="{{ asset('storage/'.$builder->logo) }}" alt="{{ $builder->company_name }}" class="builder-avatar">
            @else
              <div class="builder-avatar d-flex align-items-center justify-content-center" style="background:linear-gradient(135deg,#dbeafe,#ede9fe);">
                <i class="bi bi-building" style="color:#0078d4;font-size:1.3rem;"></i>
              </div>
            @endif
            <div>
              <div class="builder-name">{{ $builder->company_name ?? $builder->name }}</div>
              <div class="builder-city"><i class="bi bi-geo-alt me-1"></i>{{ $builder->city ?? 'Pan India' }}</div>
              @if($builder->is_verified)
                <div class="builder-verified"><i class="bi bi-patch-check-fill"></i> Verified Builder</div>
              @endif
            </div>
          </a>
        </div>
      @endforeach
    </div>
    <div class="text-center mt-4">
      <a href="{{ route('builders.index') }}" class="btn-view-prop" style="padding:12px 28px;font-size:.9rem;border-radius:12px;">
        View All Builders <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
  </div>
</section>
@endif

{{-- ════════════════════════════════════════════════════════
     TESTIMONIALS
════════════════════════════════════════════════════════ --}}
<section class="hs-section white">
  <div class="container">
    <div class="hs-section-title">
      <span class="hs-badge">Reviews</span>
      <h2>What Our Customers Say</h2>
      <p>Thousands of happy homeowners have found their dream home through {{ config('app.name') }}</p>
    </div>
    <div class="row g-4">
      @php
        $appName = config('app.name');
        $testimonials = [
          ['name'=>'Priya Sharma','role'=>'Homebuyer, Zirakpur','avatar'=>'person/person-f-5.webp',
           'text'=>"Found our dream 3BHK apartment through {$appName} in just 2 weeks! The verified listings and responsive agents made the process completely stress-free.",
           'stars'=>5],
          ['name'=>'Rahul Mehta','role'=>'Investor, Mohali','avatar'=>'person/person-f-9.webp',
           'text'=>"The new projects section is brilliant. I connected with a top builder and invested in a pre-launch project at the best price. Highly recommend {$appName}!",
           'stars'=>5],
          ['name'=>'Anita Reddy','role'=>'Renter, Chandigarh','avatar'=>'person/person-f-7.webp',
           'text'=>"Used {$appName} to find a furnished 2BHK near my office. The filter options are incredible and I got exactly what I was looking for within my budget.",
           'stars'=>5],
        ];
      @endphp
      @foreach($testimonials as $t)
        <div class="col-lg-4 col-md-6">
          <div class="testi-card">
            <div class="quote-icon"><i class="bi bi-quote"></i></div>
            <div class="testi-stars">
              @for($s=0;$s<$t['stars'];$s++) <i class="bi bi-star-fill"></i> @endfor
            </div>
            <p class="testi-text">"{{ $t['text'] }}"</p>
            <div class="testi-author">
              <img src="/assets/img/{{ $t['avatar'] }}" alt="{{ $t['name'] }}" class="testi-avatar" onerror="this.src='/assets/img/real-estate/agent-1.webp'">
              <div>
                <div class="testi-name">{{ $t['name'] }}</div>
                <div class="testi-role">{{ $t['role'] }}</div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ════════════════════════════════════════════════════════
     CTA — POST YOUR PROPERTY
════════════════════════════════════════════════════════ --}}
<section class="hs-section">
  <div class="container">
    <div class="cta-post">
      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <h2>Ready to List Your Property?</h2>
          <p>Join {{ number_format($totalDealers + $totalBuilders) }}+ dealers and builders already on {{ config('app.name') }}. Reach lakhs of verified buyers and renters today.</p>
          <div class="mb-3">
            <span class="cta-feature-pill"><i class="bi bi-check2-circle"></i> Free to List</span>
            <span class="cta-feature-pill"><i class="bi bi-check2-circle"></i> Verified Buyers</span>
            <span class="cta-feature-pill"><i class="bi bi-check2-circle"></i> Instant Exposure</span>
            <span class="cta-feature-pill"><i class="bi bi-check2-circle"></i> 24/7 Support</span>
          </div>
          <div class="d-flex gap-3 flex-wrap">
            <a href="{{ route('dealer.login') }}" class="btn-cta-white">
              <i class="bi bi-person-badge"></i> Dealer / Agent Login
            </a>
            <a href="{{ route('builder.login') }}" class="btn-cta-outline">
              <i class="bi bi-building-add"></i> Builder Login
            </a>
          </div>
        </div>
        <div class="col-lg-5 d-none d-lg-block text-end">
          <img src="/assets/img/real-estate/features-1.webp" alt="List Property"
               style="width:100%;max-width:340px;border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,.3);">
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
