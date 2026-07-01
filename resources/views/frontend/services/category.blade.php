@extends('frontend.layout')

@section('title', $category->name . ' in Chandigarh Tricity | Verified ' . $category->name . 's')
@section('meta_description', 'Find verified ' . strtolower($category->name) . 's in Chandigarh, Mohali, Zirakpur & Panchkula. Compare profiles, experience and pricing on ' . config('app.name') . '.')
@section('canonical', route('services.category', $category))

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Services","item":"{{ route('services') }}"},
    {"@type":"ListItem","position":3,"name":"{{ $category->name }}","item":"{{ route('services.category', $category) }}"}
  ]
}
</script>
@endsection

@section('content')
<section class="hs-section white" style="padding-top:40px;">
  <div class="container">
    <nav class="small text-muted mb-3">
      <a href="{{ url('/') }}">Home</a> /
      <a href="{{ route('services') }}">Services</a> /
      {{ $category->name }}
    </nav>

    <div class="hs-section-title" style="text-align:left;margin-bottom:24px;">
      <h1 style="font-size:1.9rem;font-weight:800;color:#1e293b;">{{ $category->name }} in Chandigarh Tricity</h1>
      <p style="margin:0;">Browse verified {{ strtolower($category->name) }}s across Chandigarh, Mohali, Zirakpur &amp; Panchkula</p>
    </div>

    <div class="d-flex flex-wrap gap-2 mb-4">
      <a href="{{ route('services.category', $category) }}"
         class="popular-tag {{ request('city') ? '' : 'active' }}" style="{{ request('city') ? '' : 'background:#0078d4;color:#fff;' }}">All Cities</a>
      @foreach($cities as $slug => $label)
        <a href="{{ route('services.category.city', ['category' => $category, 'city' => $slug]) }}" class="popular-tag">{{ $label }}</a>
      @endforeach
    </div>

    <div class="row g-4">
      @forelse($providers as $provider)
        <div class="col-lg-4 col-md-6">
          <div class="prop-card h-100">
            <div class="prop-card-body" style="padding:20px;">
              <div class="d-flex align-items-center gap-3 mb-3">
                @if($provider->profile_photo)
                  <img src="{{ asset('storage/'.$provider->profile_photo) }}" style="width:56px;height:56px;border-radius:50%;object-fit:cover;">
                @else
                  <div style="width:56px;height:56px;border-radius:50%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-person fs-4 text-muted"></i>
                  </div>
                @endif
                <div>
                  <div class="prop-title" style="margin:0;">{{ $provider->display_name }}</div>
                  <div class="prop-addr"><i class="bi bi-geo-alt-fill"></i> {{ $provider->city }}</div>
                </div>
                @if($provider->is_verified)
                  <span class="prop-badge badge-sale ms-auto" style="position:static;">Verified</span>
                @endif
              </div>
              @if($provider->bio)
                <p class="small text-muted">{{ Str::limit($provider->bio, 90) }}</p>
              @endif
              <div class="d-flex justify-content-between align-items-center mt-3">
                @if($provider->years_experience)
                  <span class="small text-muted">{{ $provider->years_experience }}+ yrs experience</span>
                @endif
                <a href="{{ route('services.profile', $provider) }}" class="btn-view-prop">View Profile <i class="bi bi-arrow-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12">
          <p class="text-muted">No verified {{ strtolower($category->name) }}s listed yet. Check back soon, or
            <a href="{{ route('service-provider.register') }}">register as a {{ $category->name }}</a>.</p>
        </div>
      @endforelse
    </div>

    <div class="mt-4">{{ $providers->withQueryString()->links() }}</div>
  </div>
</section>
@endsection
