@extends('frontend.layout')

@section('title', 'Home Services in Chandigarh Tricity | Electricians, Interior Designers & More | ' . config('app.name'))
@section('meta_description', 'Find verified electricians, plumbers, interior designers, loan providers, insurance agents and 8+ more home services across Chandigarh, Mohali, Zirakpur & Panchkula.')
@section('canonical', route('services'))
@section('og_title', 'Home Services Marketplace | ' . config('app.name'))

@section('schema')
<script type="application/ld+json">
{
  "@@context":"https://schema.org","@type":"BreadcrumbList",
  "itemListElement":[
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Services","item":"{{ route('services') }}"}
  ]
}
</script>
@endsection

@section('content')
{{-- ═══ HERO ═══ --}}
<div class="page-title" style="background:linear-gradient(135deg,#0369a1 0%,#0284c7 50%,#0ea5e9 100%) !important;padding:48px 0 36px;">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <div>
      <h1 class="mb-1" style="color:#fff !important;font-weight:800;font-size:2rem;">Home Services Marketplace</h1>
      <p style="color:rgba(255,255,255,.85)!important;margin:0;">Electricians, Interior Designers, Loan Providers & more — all verified, all in Tricity</p>
    </div>
    <nav class="breadcrumbs mt-3 mt-lg-0">
      <ol style="background:rgba(255,255,255,.15);border-radius:30px;padding:6px 18px;display:flex;gap:6px;align-items:center;list-style:none;margin:0;">
        <li><a href="{{ url('/') }}" style="color:rgba(255,255,255,.85);text-decoration:none;">Home</a></li>
        <li style="color:#fff;font-weight:600;">Services</li>
      </ol>
    </nav>
  </div>
</div>

{{-- ═══ STATS STRIP ═══ --}}
<section style="background:linear-gradient(135deg,#dbeafe 0%,#bfdbfe 100%);padding:36px 0;border-bottom:1px solid #93c5fd;">
  <div class="container text-center">
    <p style="max-width:720px;margin:0 auto 28px;color:#1e3a5f;font-size:1.05rem;line-height:1.85;font-weight:500;">
      {{ config('app.name') }} is Tricity's all-in-one home marketplace — find a property <em>and</em> every service your home will ever need, from the same trusted platform.
    </p>
    <div class="row g-3 justify-content-center">
      @php
        $stats = [
          ['icon'=>'bi-tools',         'num'=> $totalProviders . '+', 'label'=>'Verified Providers'],
          ['icon'=>'bi-grid',          'num'=> $categories->count(),   'label'=>'Service Categories'],
          ['icon'=>'bi-geo-alt',       'num'=>'10+',                   'label'=>'Cities Covered'],
          ['icon'=>'bi-shield-check',  'num'=>'100%',                  'label'=>'Verified Profiles'],
        ];
      @endphp
      @foreach($stats as $s)
      <div class="col-6 col-md-3">
        <div style="background:rgba(255,255,255,.8);backdrop-filter:blur(6px);border:1px solid rgba(255,255,255,.9);border-radius:14px;padding:20px 12px;box-shadow:0 4px 16px rgba(10,45,94,.10);">
          <i class="bi {{ $s['icon'] }}" style="font-size:1.8rem;color:#0078d4;"></i>
          <div style="font-size:1.5rem;font-weight:800;color:#0a2d5e;margin-top:6px;">{{ $s['num'] }}</div>
          <div style="font-size:.82rem;color:#1e3a5f;font-weight:500;margin-top:2px;">{{ $s['label'] }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══ SERVICE CATEGORIES GRID ═══ --}}
<section style="padding:60px 0 20px;">
  <div class="container">
    <div class="text-center mb-5">
      <span style="background:#dbeafe;color:#1d4ed8;font-size:.78rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;padding:4px 14px;border-radius:20px;">Browse by Service</span>
      <h2 style="font-size:1.9rem;font-weight:800;color:#0a2d5e;margin-top:12px;">What Home Service Do You Need?</h2>
      <p style="color:#64748b;">Click any category to browse verified providers in your area</p>
    </div>

    <div class="row g-3">
      @forelse($categories as $category)
      <div class="col-xl-2 col-lg-3 col-md-4 col-6">
        <a href="{{ route('services.category', $category) }}"
           style="display:flex;flex-direction:column;align-items:center;gap:10px;padding:22px 12px;background:#fff;border:1.5px solid #e2e8f0;border-radius:14px;text-decoration:none;transition:all .2s;text-align:center;"
           onmouseover="this.style.borderColor='#0078d4';this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,120,212,.15)'"
           onmouseout="this.style.borderColor='#e2e8f0';this.style.transform='';this.style.boxShadow=''">
          <div style="width:52px;height:52px;background:#dbeafe;border-radius:12px;display:flex;align-items:center;justify-content:center;">
            <i class="bi {{ $category->icon ?? 'bi-tools' }}" style="font-size:1.5rem;color:#1d4ed8;"></i>
          </div>
          <div style="font-size:.88rem;font-weight:700;color:#1e293b;">{{ $category->name }}</div>
          <div style="font-size:.74rem;color:#94a3b8;">{{ $category->providers_count }} {{ Str::plural('provider', $category->providers_count) }}</div>
        </a>
      </div>
      @empty
        <p class="text-muted col-12">No service categories available yet.</p>
      @endforelse
    </div>

    {{-- CTA for providers --}}
    <div class="text-center mt-5 p-4" style="background:#f0f7ff;border-radius:14px;border:1px solid #bfdbfe;">
      <h4 style="font-weight:700;color:#0a2d5e;">Are you a service provider?</h4>
      <p style="color:#475569;margin-bottom:16px;">Join hundreds of electricians, plumbers, designers and more already on {{ config('app.name') }}</p>
      <a href="{{ route('service-provider.register') }}" style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#0a2d5e,#0078d4);color:#fff;padding:12px 28px;border-radius:8px;font-weight:700;text-decoration:none;font-size:.95rem;">
        <i class="bi bi-tools"></i> Register as a Service Provider — Free
      </a>
    </div>
  </div>
</section>

{{-- ═══ BROWSE BY CITY ═══ --}}
<section style="padding:40px 0 60px;">
  <div class="container">
    <div class="text-center mb-4">
      <h2 style="font-size:1.5rem;font-weight:800;color:#0a2d5e;">Browse Services by City</h2>
    </div>
    <div class="row g-3">
      @foreach($cities as $slug => $label)
      <div class="col-lg-6">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;">
          <div style="font-weight:700;color:#1e293b;margin-bottom:10px;"><i class="bi bi-geo-alt-fill text-danger me-2"></i>{{ $label }}</div>
          <div class="d-flex flex-wrap gap-2">
            @foreach($categories->take(6) as $cat)
            <a href="{{ route('services.category.city', ['category'=>$cat,'city'=>$slug]) }}"
               style="font-size:.75rem;font-weight:600;padding:4px 10px;border:1px solid #e2e8f0;border-radius:20px;color:#475569;text-decoration:none;background:#f8fafc;"
               onmouseover="this.style.borderColor='#0078d4';this.style.color='#0078d4'"
               onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#475569'">
              {{ $cat->name }}
            </a>
            @endforeach
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

@include('frontend.partials.services')
@endsection
