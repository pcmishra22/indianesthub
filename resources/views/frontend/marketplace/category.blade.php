@extends('frontend.layout')

@section('title', $category->name . ' – Shop Verified Vendors in Chandigarh Tricity | ' . config('app.name'))
@section('meta_description', $category->tagline . '. Compare prices and get free quotes from verified ' . strtolower($category->name) . ' vendors across Chandigarh, Mohali, Zirakpur & Panchkula.')
@section('canonical', route('marketplace.category', $category))

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Marketplace","item":"{{ route('marketplace.index') }}"},
    {"@type":"ListItem","position":3,"name":"{{ $category->name }}","item":"{{ route('marketplace.category', $category) }}"}
  ]
}
</script>
@endsection

@push('styles')
<style>
  .mkt-cat-nav { display:flex; flex-wrap:wrap; gap:8px; }
  .mkt-cat-nav a { background:#f1f5f9; color:#475569; font-size:.82rem; font-weight:600; padding:6px 14px; border-radius:20px; text-decoration:none; }
  .mkt-cat-nav a.active { background:#0078d4; color:#fff; }
  .mkt-prod-card { background:#fff; border:1px solid #e2e8f0; border-radius:14px; overflow:hidden; height:100%; display:flex; flex-direction:column; transition:transform .15s,box-shadow .15s; }
  .mkt-prod-card:hover { transform:translateY(-3px); box-shadow:0 10px 26px rgba(2,132,199,.14); }
  .mkt-prod-img { aspect-ratio:4/3; background:#f1f5f9; overflow:hidden; position:relative; }
  .mkt-prod-img img { width:100%; height:100%; object-fit:cover; }
  .mkt-prod-badge { position:absolute; top:8px; left:8px; background:linear-gradient(135deg,#f59e0b,#ea580c); color:#fff; font-size:.65rem; font-weight:700; padding:3px 8px; border-radius:20px; }
  .mkt-prod-body { padding:12px 14px 14px; flex:1; display:flex; flex-direction:column; }
  .mkt-prod-title { font-weight:700; color:#0f172a; font-size:.92rem; line-height:1.3; min-height:2.4em; }
  .mkt-prod-vendor { font-size:.76rem; color:#64748b; margin-top:4px; }
  .mkt-prod-price { font-weight:800; color:#0a2d5e; margin-top:8px; font-size:.92rem; }
</style>
@endpush

@section('content')
<section style="padding:36px 0 60px;">
  <div class="container">
    <nav class="small text-muted mb-3">
      <a href="{{ url('/') }}">Home</a> /
      <a href="{{ route('marketplace.index') }}">Marketplace</a> /
      {{ $category->name }}
    </nav>

    <div class="mb-4">
      <h1 style="font-size:1.8rem;font-weight:800;color:#0a2d5e;"><i class="bi {{ $category->icon ?? 'bi-shop' }}"></i> {{ $category->name }}</h1>
      <p style="color:#64748b;margin:0;">{{ $category->tagline }}</p>
    </div>

    <div class="mkt-cat-nav mb-4">
      @foreach($allCategories as $c)
        <a href="{{ route('marketplace.category', $c) }}" class="{{ $c->id === $category->id ? 'active' : '' }}">{{ $c->name }}</a>
      @endforeach
    </div>

    <form method="GET" class="row g-2 mb-4 align-items-end">
      <div class="col-md-4 col-6">
        <label class="form-label small fw-semibold mb-1">City</label>
        <select name="city" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">All Cities</option>
          @foreach($cities as $c)
            <option value="{{ $c }}" {{ request('city') === $c ? 'selected' : '' }}>{{ $c }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3 col-6">
        <label class="form-label small fw-semibold mb-1">BHK</label>
        <select name="bhk" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">Any</option>
          @for($i = 1; $i <= 5; $i++)
            <option value="{{ $i }}" {{ request('bhk') == $i ? 'selected' : '' }}>{{ $i }}BHK</option>
          @endfor
        </select>
      </div>
      <div class="col-md-4 col-8">
        <label class="form-label small fw-semibold mb-1">Search</label>
        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="e.g. blackout, modular...">
      </div>
      <div class="col-md-1 col-4">
        <button class="btn btn-primary btn-sm w-100" style="border-radius:8px;"><i class="bi bi-search"></i></button>
      </div>
    </form>

    <div class="row g-4">
      @forelse($products as $product)
        <div class="col-lg-3 col-md-4 col-6">
          <a href="{{ route('marketplace.product', ['category' => $category, 'product' => $product]) }}" class="text-decoration-none">
            <div class="mkt-prod-card">
              <div class="mkt-prod-img">
                @if($product->cover_image || $product->images->count())
                  <img src="{{ $product->cover_url }}" alt="{{ $product->name }}" loading="lazy">
                @else
                  <div class="d-flex align-items-center justify-content-center h-100 text-muted"><i class="bi {{ $category->icon ?? 'bi-shop' }}" style="font-size:2.2rem;"></i></div>
                @endif
                @if($product->is_featured)
                  <span class="mkt-prod-badge"><i class="bi bi-star-fill"></i> Featured</span>
                @endif
              </div>
              <div class="mkt-prod-body">
                <div class="mkt-prod-title" style="color:#0f172a;">{{ $product->name }}</div>
                <div class="mkt-prod-vendor">
                  <i class="bi bi-shop"></i> {{ $product->vendor?->business_name }}
                  @if($product->vendor?->is_verified)<i class="bi bi-patch-check-fill text-success"></i>@endif
                </div>
                <div class="mkt-prod-price">{{ $product->price_label }}</div>
              </div>
            </div>
          </a>
        </div>
      @empty
        <div class="col-12">
          <p class="text-muted text-center py-5">No {{ strtolower($category->name) }} products listed yet for these filters. Check back soon, or try a different city.</p>
        </div>
      @endforelse
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
  </div>
</section>
@endsection
