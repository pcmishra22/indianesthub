@extends('frontend.layout')

@php
  $categoryNames = $provider->categories->pluck('name')->join(', ');
@endphp

@section('title', $provider->display_name . ' - ' . $categoryNames . ' in ' . $provider->city)
@section('meta_description', $provider->display_name . ' is a verified ' . strtolower($categoryNames) . ' in ' . $provider->city . '. View experience, pricing and contact details on ' . config('app.name') . '.')
@section('canonical', route('services.profile', $provider))

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "{{ $provider->display_name }}",
  "image": "{{ $provider->profile_photo ? asset('storage/'.$provider->profile_photo) : asset('assets/img/real-estate/agent-1.webp') }}",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "{{ $provider->city }}"
  },
  @if($provider->starting_price)
  "priceRange": "₹{{ number_format($provider->starting_price) }}+",
  @endif
  "description": "{{ Str::limit(strip_tags($provider->bio ?? $categoryNames), 200) }}"
}
</script>
@endsection

@section('content')
<section class="hs-section white" style="padding-top:40px;">
  <div class="container">
    <nav class="small text-muted mb-3">
      <a href="{{ url('/') }}">Home</a> /
      <a href="{{ route('services') }}">Services</a> /
      {{ $provider->display_name }}
    </nav>

    <div class="row g-4">
      <div class="col-lg-4">
        <div class="prop-card">
          <div class="prop-card-body text-center" style="padding:28px 20px;">
            @if($provider->profile_photo)
              <img src="{{ asset('storage/'.$provider->profile_photo) }}" class="rounded-circle mb-3" style="width:110px;height:110px;object-fit:cover;">
            @else
              <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width:110px;height:110px;">
                <i class="bi bi-person fs-1 text-muted"></i>
              </div>
            @endif
            <h1 style="font-size:1.3rem;font-weight:800;margin-bottom:4px;">{{ $provider->display_name }}</h1>
            <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill"></i> {{ $provider->city }}</p>
            @if($provider->is_verified)
              <span class="prop-badge badge-sale" style="position:static;display:inline-block;margin-bottom:10px;">Verified</span>
            @endif
            <div class="d-flex flex-wrap justify-content-center gap-1 mb-3">
              @foreach($provider->categories as $cat)
                <a href="{{ route('services.category.city', ['category' => $cat, 'city' => Str::slug($provider->city)]) }}"
                   class="prop-type-tag" style="text-decoration:none;">{{ $cat->name }}</a>
              @endforeach
            </div>
            @if($provider->years_experience)
              <p class="small mb-1"><strong>{{ $provider->years_experience }}+ years</strong> experience</p>
            @endif
            @if($provider->starting_price)
              <p class="small mb-3">Starting at <strong>₹{{ number_format($provider->starting_price) }}</strong>{{ $provider->price_unit ? ' '.$provider->price_unit : '' }}</p>
            @endif
            <a href="https://wa.me/91{{ preg_replace('/\D/','',$provider->phone) }}?text=Hi%2C%20I%20found%20you%20on%20{{ urlencode(config('app.name')) }}.%20I%20need%20{{ urlencode(strtolower($categoryNames)) }}%20help."
               target="_blank" class="btn-view-prop w-100 d-block text-center" style="margin-bottom:8px;">
              <i class="bi bi-whatsapp"></i> WhatsApp
            </a>
            <a href="tel:+91{{ preg_replace('/\D/','',$provider->phone) }}" class="btn-view-prop w-100 d-block text-center" style="background:#0a2d5e;">
              <i class="bi bi-telephone"></i> Call Now
            </a>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="prop-card">
          <div class="prop-card-body" style="padding:28px;">
            <h2 style="font-size:1.2rem;font-weight:800;margin-bottom:14px;">About</h2>
            @if($provider->bio)
              <p style="color:#475569;line-height:1.8;">{{ $provider->bio }}</p>
            @else
              <p class="text-muted">This service provider hasn't added a bio yet.</p>
            @endif

            @if(!empty($provider->operating_areas))
              <h2 style="font-size:1.2rem;font-weight:800;margin:24px 0 10px;">Areas Served</h2>
              <div class="d-flex flex-wrap gap-2">
                @foreach($provider->operating_areas as $area)
                  <span class="prop-type-tag">{{ $area }}</span>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
