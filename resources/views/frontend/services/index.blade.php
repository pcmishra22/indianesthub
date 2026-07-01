@extends('frontend.layout')

@section('title', 'Home Services in Chandigarh Tricity | Electricians, Interior Designers & More')
@section('meta_description', 'Find verified electricians, plumbers, interior designers, loan providers, insurance agents and more across Chandigarh, Mohali, Zirakpur & Panchkula on ' . config('app.name') . '.')
@section('canonical', route('services'))

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Services","item":"{{ route('services') }}"}
  ]
}
</script>
@endsection

@section('content')
<section class="hs-section white" style="padding-top:40px;">
  <div class="container">
    <div class="hs-section-title">
      <span class="hs-badge">Home Services</span>
      <h1 style="font-size:2rem;font-weight:800;color:#1e293b;">Everything Your Home Needs, In One Place</h1>
      <p>Find verified electricians, plumbers, interior designers, loan providers, insurance agents and more across Chandigarh Tricity</p>
    </div>

    <div class="row g-3">
      @forelse($categories as $category)
        <div class="col-lg-3 col-md-4 col-6">
          <a href="{{ route('services.category', $category) }}" class="prop-type-chip" style="width:100%;">
            <div class="icon" style="background:#dbeafe;color:#1d4ed8;">
              <i class="bi {{ $category->icon ?? 'bi-tools' }}"></i>
            </div>
            <div class="label">{{ $category->name }}</div>
            <div class="cnt">{{ $category->providers_count }} {{ Str::plural('provider', $category->providers_count) }}</div>
          </a>
        </div>
      @empty
        <p class="text-muted">No service categories available yet.</p>
      @endforelse
    </div>

    <div class="mt-5">
      <h2 style="font-size:1.3rem;font-weight:700;">Browse by City</h2>
      <div class="d-flex flex-wrap gap-2 mt-2">
        @foreach($cities as $slug => $label)
          <span class="text-muted small fw-semibold">{{ $label }}:</span>
          @foreach($categories->take(4) as $category)
            <a href="{{ route('services.category.city', ['category' => $category, 'city' => $slug]) }}"
               class="popular-tag">{{ $category->name }} in {{ $label }}</a>
          @endforeach
        @endforeach
      </div>
    </div>
  </div>
</section>
@endsection
