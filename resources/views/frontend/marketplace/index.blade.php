@extends('frontend.layout')

@section('title', 'Home Marketplace – Curtains, Furniture, Lighting & More | ' . config('app.name'))
@section('meta_description', 'Shop curtains, lights, furniture, modular kitchens, bathroom fittings, décor & smart home products from verified local vendors across Chandigarh, Mohali, Zirakpur & Panchkula. Free measurements & quotes.')
@section('canonical', route('marketplace.index'))

@section('schema')
<script type="application/ld+json">
{
  "@@context":"https://schema.org","@type":"BreadcrumbList",
  "itemListElement":[
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Marketplace","item":"{{ route('marketplace.index') }}"}
  ]
}
</script>
@endsection

@push('styles')
<style>
  .mkt-hero { background:linear-gradient(135deg,#0a2d5e 0%,#0078d4 100%); padding:56px 0 70px; color:#fff; }
  .mkt-hero h1 { font-weight:800; font-size:2.1rem; margin-bottom:10px; }
  .mkt-hero p { opacity:.9; max-width:640px; margin:0 auto 22px; }
  .mkt-stat { display:inline-flex; flex-direction:column; align-items:center; padding:0 22px; }
  .mkt-stat strong { font-size:1.4rem; font-weight:800; }
  .mkt-stat span { font-size:.78rem; opacity:.85; }

  .mkt-cat-card { background:#fff; border-radius:16px; border:1px solid #f1f5f9; box-shadow:0 4px 20px rgba(10,45,94,.07); overflow:hidden; text-decoration:none; display:block; height:100%; transition:transform .2s,box-shadow .2s; }
  .mkt-cat-card:hover { transform:translateY(-4px); box-shadow:0 12px 28px rgba(10,45,94,.14); }
  .mkt-cat-icon { height:84px; display:flex; align-items:center; justify-content:center; background:#eff6ff; }
  .mkt-cat-icon i { font-size:2.3rem; color:#0078d4; }
  .mkt-cat-body { padding:16px 18px 18px; }
  .mkt-cat-body h4 { font-size:.98rem; font-weight:700; color:#0a2d5e; margin-bottom:4px; }
  .mkt-cat-body p { font-size:.8rem; color:#64748b; margin-bottom:8px; min-height:2.2em; }
  .mkt-cat-count { font-size:.72rem; font-weight:700; color:#0078d4; background:#e0f2fe; padding:2px 9px; border-radius:12px; }

  .mkt-prod-card { background:#fff; border:1px solid #e2e8f0; border-radius:14px; overflow:hidden; height:100%; display:flex; flex-direction:column; transition:transform .15s,box-shadow .15s; text-decoration:none; color:inherit; }
  .mkt-prod-card:hover { transform:translateY(-3px); box-shadow:0 10px 26px rgba(2,132,199,.14); color:inherit; }
  .mkt-prod-img { aspect-ratio:4/3; background:#f1f5f9; overflow:hidden; position:relative; }
  .mkt-prod-img img { width:100%; height:100%; object-fit:cover; }
  .mkt-prod-badge { position:absolute; top:8px; left:8px; background:linear-gradient(135deg,#f59e0b,#ea580c); color:#fff; font-size:.65rem; font-weight:700; padding:3px 8px; border-radius:20px; }
  .mkt-prod-body { padding:12px 14px 14px; flex:1; display:flex; flex-direction:column; }
  .mkt-prod-cat { font-size:.65rem; text-transform:uppercase; letter-spacing:.5px; color:#0284c7; font-weight:700; margin-bottom:3px; }
  .mkt-prod-title { font-weight:700; color:#0f172a; font-size:.92rem; line-height:1.3; min-height:2.4em; }
  .mkt-prod-vendor { font-size:.76rem; color:#64748b; margin-top:4px; }
  .mkt-prod-price { font-weight:800; color:#0a2d5e; margin-top:8px; font-size:.92rem; }

  .mkt-city-pills { display:flex; flex-wrap:wrap; gap:8px; justify-content:center; margin-top:18px; }
  .mkt-city-pill { background:rgba(255,255,255,.14); border:1px solid rgba(255,255,255,.3); color:#fff; padding:6px 14px; border-radius:20px; font-size:.82rem; text-decoration:none; }
  .mkt-city-pill:hover, .mkt-city-pill.active { background:#fff; color:#0a2d5e; font-weight:700; }
</style>
@endpush

@section('content')

<section class="mkt-hero text-center">
  <div class="container">
    <span style="background:rgba(255,255,255,.15);font-size:.78rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;padding:4px 14px;border-radius:20px;">Home Marketplace</span>
    <h1 class="mt-3">Everything to Furnish Your New Home</h1>
    <p>Curtains, lighting, furniture, modular kitchens, bathroom fittings & more — from verified local shops. Get free measurements and quotes, no showroom visit needed.</p>

    <div class="d-flex justify-content-center flex-wrap">
      <div class="mkt-stat"><strong>{{ number_format($vendorCount) }}+</strong><span>Verified Vendors</span></div>
      <div class="mkt-stat"><strong>{{ number_format($productCount) }}+</strong><span>Products Listed</span></div>
      <div class="mkt-stat"><strong>8</strong><span>Categories</span></div>
    </div>

    @if($cities->count())
    <div class="mkt-city-pills">
      <a href="{{ route('marketplace.index') }}" class="mkt-city-pill {{ request('city') ? '' : 'active' }}">All Cities</a>
      @foreach($cities as $city)
        <a href="{{ route('marketplace.index', ['city' => $city]) }}" class="mkt-city-pill {{ request('city') === $city ? 'active' : '' }}">{{ $city }}</a>
      @endforeach
    </div>
    @endif
  </div>
</section>

<section style="padding:56px 0;">
  <div class="container">
    <div class="text-center mb-5">
      <h2 style="font-size:1.7rem;font-weight:800;color:#0a2d5e;">Shop by Category</h2>
      <p style="color:#64748b;">Browse verified vendors in every home category</p>
    </div>

    <div class="row g-4">
      @forelse($categories as $category)
        <div class="col-xl-3 col-lg-4 col-md-6">
          <a href="{{ route('marketplace.category', $category) }}" class="mkt-cat-card">
            <div class="mkt-cat-icon"><i class="bi {{ $category->icon ?? 'bi-shop' }}"></i></div>
            <div class="mkt-cat-body">
              <h4>{{ $category->name }}</h4>
              <p>{{ $category->tagline }}</p>
              <span class="mkt-cat-count">
                {{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}
              </span>
            </div>
          </a>
        </div>
      @empty
        <p class="text-muted text-center col-12">Categories coming soon.</p>
      @endforelse
    </div>
  </div>
</section>

@if($featuredProducts->count())
<section style="background:#f8faff;padding:56px 0 66px;border-top:1px solid #e2e8f0;">
  <div class="container">
    <div class="text-center mb-5">
      <h2 style="font-size:1.7rem;font-weight:800;color:#0a2d5e;">Featured Products</h2>
      <p style="color:#64748b;">Popular picks from top-rated vendors</p>
    </div>

    <div class="row g-4">
      @foreach($featuredProducts as $product)
        <div class="col-xl-3 col-lg-4 col-md-6">
          <a href="{{ route('marketplace.product', ['category' => $product->category, 'product' => $product]) }}" class="mkt-prod-card">
            <div class="mkt-prod-img">
              @if($product->cover_image || $product->images->count())
                <img src="{{ $product->cover_url }}" alt="{{ $product->name }}" loading="lazy">
              @else
                <div class="d-flex align-items-center justify-content-center h-100 text-muted"><i class="bi {{ $product->category?->icon ?? 'bi-shop' }}" style="font-size:2.2rem;"></i></div>
              @endif
              @if($product->is_featured)
                <span class="mkt-prod-badge"><i class="bi bi-star-fill"></i> Featured</span>
              @endif
            </div>
            <div class="mkt-prod-body">
              <div class="mkt-prod-cat">{{ $product->category?->name }}</div>
              <div class="mkt-prod-title">{{ $product->name }}</div>
              <div class="mkt-prod-vendor">
                <i class="bi bi-shop"></i> {{ $product->vendor?->business_name }}
                @if($product->vendor?->is_verified)<i class="bi bi-patch-check-fill text-success"></i>@endif
              </div>
              <div class="mkt-prod-price">{{ $product->price_label }}</div>
            </div>
          </a>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<div class="text-center py-5">
  <a href="{{ route('services') }}" class="text-decoration-none" style="color:#0078d4;font-weight:600;">
    Looking for a home service professional instead? Browse Services <i class="bi bi-arrow-right"></i>
  </a>
</div>

@endsection
