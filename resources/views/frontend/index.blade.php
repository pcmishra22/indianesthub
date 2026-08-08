{{-- resources/views/frontend/index.blade.php --}}
@extends('frontend.layout')

{{-- ════════════════════════ SEO META ════════════════════════ --}}
@section('title', config('app.name') . ' – India\'s Real Asset Investment Marketplace')
@section('meta_description', 'IndianEstHub is India\'s real asset investment marketplace — verified property, commercial, agricultural land, industrial & new-launch investment opportunities, home loans, legal help and expert advice, all in one place.')
@section('meta_keywords', 'real estate investment india, property investment, commercial property investment, farmland investment, warehouse investment, investment score, new launch projects, home loan, property insurance, legal help, chandigarh mohali zirakpur property')
@section('canonical', url('/'))
@section('og_title', config('app.name') . ' – India\'s Real Asset Investment Marketplace')
@section('og_description', 'Explore ' . config('app.name') . ' — India\'s real asset investment marketplace. Verified property, commercial, agriculture & industrial investment opportunities in one place.')

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "WebSite",
  "name": "{{ config('app.name') }}",
  "url": "{{ url('/') }}",
  "description": "India's complete real estate ecosystem",
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
     INVESTMENT CATEGORIES
════════════════════════════════════════════════════════ --}}
<section class="hs-section white" id="investment-categories">
  <div class="container">
    <div class="hs-section-title">
      <span class="hs-badge">Investment Categories</span>
      <h2>Where Do You Want to Invest?</h2>
      <p>Every real, verifiable asset class — in one marketplace</p>
    </div>
    <div class="prop-type-grid">
      @php
        // Investment categories mapped to real, deliverable listing types.
        // Stocks / Mutual Funds / tradeable REIT units are intentionally
        // excluded — those require SEBI/AMFI broking or distributor licenses.
        $invCategories = [
          ['label'=>'Property','sub'=>'Residential homes & flats','icon'=>'bi-house-door','color'=>'#dbeafe','text'=>'#1d4ed8',
            'href'=>route('properties', ['property_type' => 'Residential']),
            'count'=>($propertyTypes['Residential'] ?? 0) + ($propertyTypes['Apartment'] ?? 0) + ($propertyTypes['Villa'] ?? 0)],
          ['label'=>'Commercial','sub'=>'Offices, shops, showrooms','icon'=>'bi-shop','color'=>'#fce7f3','text'=>'#be185d',
            'href'=>route('properties', ['property_type' => 'Commercial']),
            'count'=>$propertyTypes['Commercial'] ?? 0],
          ['label'=>'Agriculture','sub'=>'Farm houses & farmland','icon'=>'bi-tree','color'=>'#dcfce7','text'=>'#15803d',
            'href'=>route('properties', ['property_type' => 'Farm House']),
            'count'=>$propertyTypes['Farm House'] ?? 0],
          ['label'=>'Industrial','sub'=>'Warehouses & godowns','icon'=>'bi-building-gear','color'=>'#fef3c7','text'=>'#b45309',
            'href'=>route('properties', ['property_type' => 'Warehouse']),
            'count'=>$propertyTypes['Warehouse'] ?? 0],
          ['label'=>'Under Construction','sub'=>'New launches & pre-bookings','icon'=>'bi-building-add','color'=>'#ede9fe','text'=>'#7c3aed',
            'href'=>route('builders.index'),
            'count'=>$newLaunches->count(), 'suffix'=>' projects'],
          ['label'=>'Gold','sub'=>'Coming soon','icon'=>'bi-gem','color'=>'#fff7ed','text'=>'#ea580c',
            'href'=>'#','count'=>null,'badge'=>'Coming Soon'],
        ];
      @endphp
      @foreach($invCategories as $cat)
        <a href="{{ $cat['href'] }}" class="prop-type-chip" @if($cat['href']==='#') onclick="return false;" style="cursor:default;opacity:.85;" @endif>
          <div class="icon" style="background:{{ $cat['color'] }};color:{{ $cat['text'] }};">
            <i class="bi {{ $cat['icon'] }}"></i>
          </div>
          <div class="label">{{ $cat['label'] }}</div>
          <div class="cnt">
            @if(!empty($cat['badge']))
              {{ $cat['badge'] }}
            @elseif(($cat['count'] ?? 0) > 0)
              {{ $cat['count'] }}{{ $cat['suffix'] ?? '' }}{{ empty($cat['suffix']) ? ' listings' : '' }}
            @else
              {{ $cat['sub'] }}
            @endif
          </div>
        </a>
      @endforeach
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
      <h2>Featured Investment Opportunities</h2>
      <p>Handpicked properties, commercial spaces, farmland &amp; industrial listings across India</p>
    </div>
    <div class="row g-4">

      @php
        // Maps raw property_type values to an investor-facing asset-class label
        $assetClassMap = [
          'Commercial'=>'Commercial','Office'=>'Commercial','Retail Shop'=>'Commercial','Showroom'=>'Commercial',
          'Warehouse'=>'Industrial',
          'Farm House'=>'Agriculture Land','Plot'=>'Land',
        ];
        function assetClassLabel($property, $assetClassMap) {
          return $assetClassMap[$property->property_type ?? ''] ?? 'Property';
        }
      @endphp

      @forelse($featuredProperties->take(6) as $property)
        @php
          $imgUrl = homeImg($property, $fallbackImages);
          $lf     = strtolower($property->looking_for ?? '');
          $agentName  = $property->dealer?->name ?? ($property->builder?->company_name ?? $property->builder?->name ?? config('app.name'));
          $agentAvatar = asset('assets/img/real-estate/agent-' . (($property->id % 10) + 1) . '.webp');
          $assetClass = assetClassLabel($property, $assetClassMap);
        @endphp
        <div class="col-lg-4 col-md-6">
          <div class="prop-card h-100">
            <div class="prop-card-img">
              <img src="{{ $imgUrl }}" alt="{{ $property->title }}" loading="lazy">
              <div class="prop-img-overlay"></div>
              <div class="prop-badges">
                <span class="prop-badge badge-sale" style="background:#0a2d5e;">{{ $assetClass }}</span>
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
          <a href="{{ route('projects.show', $launch->slug) }}" class="launch-card d-block text-decoration-none h-100">
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
        <h2 class="fw-800 mt-2 mb-4" style="font-size:2rem;color:var(--text-dark);font-weight:800;">Why Invest With <span style="color:#0078d4;">{{ config('app.name') }}?</span></h2>
        <p style="color:#64748b;line-height:1.8;margin-bottom:24px;">We're not just a property listing site — we're building India's trust layer for real-asset investing. From verified builders and legal due-diligence to home loans and ROI insight on every listing, we help you invest with confidence, not guesswork.</p>
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
              ['icon'=>'bi-patch-check','color'=>'#dbeafe','text'=>'#1d4ed8','title'=>'Verified Builders','desc'=>'Every builder & dealer is background-checked and physically verified before listing. No fake projects, ever.'],
              ['icon'=>'bi-calculator','color'=>'#dcfce7','text'=>'#15803d','title'=>'Investment Calculator','desc'=>'Instantly estimate fair market value and pricing trends with our AI-powered price estimator.', 'href'=>route('price-estimator')],
              ['icon'=>'bi-graph-up-arrow','color'=>'#fef3c7','text'=>'#b45309','title'=>'ROI Prediction','desc'=>'Every listing is moving toward a built-in Investment Score — rental yield, appreciation & risk at a glance.', 'href'=>'#investment-score'],
              ['icon'=>'bi-headset','color'=>'#fce7f3','text'=>'#be185d','title'=>'Expert Advice','desc'=>'Our dedicated team is always available to guide you through the entire investing process, free of cost.', 'href'=>url('/contact')],
              ['icon'=>'bi-bank','color'=>'#ede9fe','text'=>'#7c3aed','title'=>'Home Loan Assistance','desc'=>'Compare and apply for home loans from partner banks & NBFCs with the best rates, right from the platform.', 'href'=>route('seo.loan')],
              ['icon'=>'bi-file-earmark-check','color'=>'#ecfdf5','text'=>'#065f46','title'=>'Legal Verification','desc'=>'Title checks, sale-deed registration and dispute due-diligence handled by our legal partner network.', 'href'=>route('seo.legal')],
              ['icon'=>'bi-shield-check','color'=>'#fef2f2','text'=>'#dc2626','title'=>'Property Insurance','desc'=>'Protect your investment against fire, theft and natural disasters with partner insurance plans.', 'href'=>route('seo.insurance')],
              ['icon'=>'bi-receipt','color'=>'#fff7ed','text'=>'#ea580c','title'=>'Tax Planning','desc'=>'Guidance on capital gains, TDS on property purchase and tax-saving structures for real asset investors.', 'href'=>url('/contact')],
            ];
          @endphp
          @foreach($whyItems as $item)
            <div class="col-md-6">
              <a href="{{ $item['href'] ?? '#' }}" class="why-card" style="text-decoration:none;">
                <div class="why-icon" style="background:{{ $item['color'] }};color:{{ $item['text'] }};"><i class="bi {{ $item['icon'] }}"></i></div>
                <div>
                  <div class="why-title">{{ $item['title'] }}</div>
                  <p class="why-desc">{{ $item['desc'] }}</p>
                </div>
              </a>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ════════════════════════════════════════════════════════
     INVESTMENT SCORE — flagship feature
════════════════════════════════════════════════════════ --}}
<section class="hs-section" id="investment-score">
  <div class="container">
    <div class="hs-section-title">
      <span class="hs-badge" style="background:#fef3c7;color:#b45309;">Coming Soon · Flagship Feature</span>
      <h2>Every Listing Gets an <span style="color:#0078d4;">Investment Score</span></h2>
      <p>One number that tells you if this is a smart investment — rental yield, appreciation, risk, liquidity & builder trust, combined.</p>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div style="background:#fff;border-radius:20px;box-shadow:0 10px 40px rgba(0,0,0,.08);padding:32px;display:flex;flex-wrap:wrap;align-items:center;gap:32px;">
          <div style="flex:0 0 auto;text-align:center;min-width:150px;">
            <div style="width:130px;height:130px;border-radius:50%;background:conic-gradient(#16a34a 0% 92%, #e5e7eb 92% 100%);display:flex;align-items:center;justify-content:center;margin:0 auto;">
              <div style="width:104px;height:104px;border-radius:50%;background:#fff;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                <div style="font-size:1.9rem;font-weight:900;color:#16a34a;line-height:1;">92</div>
                <div style="font-size:.7rem;color:#94a3b8;font-weight:700;">/ 100</div>
              </div>
            </div>
            <div style="margin-top:10px;font-weight:800;color:#16a34a;font-size:.85rem;letter-spacing:.3px;">EXCELLENT</div>
          </div>
          <div style="flex:1 1 320px;">
            <div class="row g-3">
              @php
                $scoreMetrics = [
                  ['label'=>'Rental Yield','value'=>'8%','icon'=>'bi-cash-coin','color'=>'#15803d'],
                  ['label'=>'Expected Appreciation','value'=>'12% / yr','icon'=>'bi-graph-up-arrow','color'=>'#1d4ed8'],
                  ['label'=>'Risk','value'=>'Low','icon'=>'bi-shield-check','color'=>'#15803d'],
                  ['label'=>'Liquidity','value'=>'High','icon'=>'bi-arrow-left-right','color'=>'#7c3aed'],
                  ['label'=>'Builder Rating','value'=>'4.8 / 5','icon'=>'bi-star-fill','color'=>'#b45309'],
                  ['label'=>'Possession','value'=>'2027','icon'=>'bi-calendar-check','color'=>'#be185d'],
                ];
              @endphp
              @foreach($scoreMetrics as $m)
                <div class="col-6 col-md-4">
                  <div style="display:flex;align-items:center;gap:8px;">
                    <i class="bi {{ $m['icon'] }}" style="color:{{ $m['color'] }};font-size:1.1rem;"></i>
                    <div>
                      <div style="font-size:.7rem;color:#94a3b8;font-weight:600;">{{ $m['label'] }}</div>
                      <div style="font-size:.95rem;font-weight:800;color:#1e293b;">{{ $m['value'] }}</div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
        <p class="text-center mt-4" style="color:#94a3b8;font-size:.85rem;">
          <i class="bi bi-info-circle me-1"></i>Sample score shown for illustration — we're rolling this out across every listing.
        </p>
      </div>
    </div>
  </div>
</section>

{{-- ════════════════════════════════════════════════════════
     INVESTORS PORTAL — amount-based discovery
════════════════════════════════════════════════════════ --}}
<section class="hs-section white" id="investors">
  <div class="container">
    <div class="hs-section-title">
      <span class="hs-badge">For Investors</span>
      <h2>How Much Do You Want to Invest?</h2>
      <p>Tell us your budget — we'll show you what it can buy across property, commercial & land</p>
    </div>
    <div class="row g-3 justify-content-center">
      @php
        $budgets = [
          ['label'=>'₹5 Lakh',   'min'=>0,        'max'=>500000],
          ['label'=>'₹10 Lakh',  'min'=>500000,   'max'=>1000000],
          ['label'=>'₹25 Lakh',  'min'=>1000000,  'max'=>2500000],
          ['label'=>'₹50 Lakh',  'min'=>2500000,  'max'=>5000000],
          ['label'=>'₹1 Crore',  'min'=>5000000,  'max'=>10000000],
          ['label'=>'₹5 Crore',  'min'=>10000000, 'max'=>50000000],
        ];
      @endphp
      @foreach($budgets as $b)
        <div class="col-6 col-md-4 col-lg-2">
          <a href="{{ route('properties', ['min_price' => $b['min'], 'max_price' => $b['max']]) }}"
             class="d-block text-center text-decoration-none"
             style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:14px;padding:22px 10px;font-weight:800;color:#0a2d5e;transition:.2s;"
             onmouseover="this.style.borderColor='#0078d4';this.style.background='#eff6ff';"
             onmouseout="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc';">
            <i class="bi bi-wallet2 d-block mb-2" style="font-size:1.4rem;color:#0078d4;"></i>
            {{ $b['label'] }}
          </a>
        </div>
      @endforeach
    </div>
    <div class="text-center mt-4">
      <span style="color:#94a3b8;font-size:.9rem;">Every budget shows real, verified investment opportunities — updated live.</span>
    </div>
  </div>
</section>

{{-- ════════════════════════════════════════════════════════
     PROPERTY AUCTIONS — fair-price teaser banner
════════════════════════════════════════════════════════ --}}
<section class="hs-section" style="background:linear-gradient(135deg,#0a2340,#0a2d5e);padding:56px 0;">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-7 text-white">
        <span class="badge mb-2" style="background:rgba(255,255,255,.15);color:#fff;font-size:.75rem;padding:6px 14px;border-radius:20px;">
          <i class="bi bi-hammer me-1"></i> New
        </span>
        <h2 class="fw-bold mb-2" style="font-size:1.6rem;">Selling Urgently? Don't Let a Dealer Lowball You.</h2>
        <p class="text-white-50 mb-0" style="max-width:520px;">
          List your property in a document-verified, transparent auction. KYC-checked bidders compete openly —
          you get the market's real price, not one dealer's rushed offer.
        </p>
      </div>
      <div class="col-lg-5 text-lg-end">
        <a href="{{ route('auctions.submit.create') }}" class="btn btn-light fw-semibold px-4 py-2 me-2 mb-2" style="border-radius:10px;">
          List for Auction
        </a>
        <a href="{{ route('auctions.index') }}" class="btn fw-semibold px-4 py-2 mb-2" style="border-radius:10px;background:rgba(255,255,255,.12);border:1.5px solid rgba(255,255,255,.4);color:#fff;">
          Browse Live Auctions
        </a>
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
