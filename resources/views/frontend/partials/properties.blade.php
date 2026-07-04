@php
  // Fallback image cycling based on property id
  $fallbackImages = [
    'property-exterior-1.webp','property-exterior-2.webp','property-exterior-3.webp',
    'property-exterior-4.webp','property-exterior-5.webp','property-exterior-7.webp',
    'property-exterior-8.webp','property-exterior-9.webp',
    'property-interior-1.webp','property-interior-2.webp',
    'property-interior-4.webp','property-interior-5.webp',
  ];

  function propImage($property, $fallbackImages) {
    if (!empty($property->cover_image)) {
      $storagePath = storage_path('app/public/' . $property->cover_image);
      if (file_exists($storagePath)) {
        return asset('storage/' . $property->cover_image);
      }
    }
    if ($property->images && $property->images->isNotEmpty()) {
      $imgPath = storage_path('app/public/' . $property->images->first()->image_path);
      if (file_exists($imgPath)) {
        return asset('storage/' . $property->images->first()->image_path);
      }
    }
    $idx = $property->id % count($fallbackImages);
    return asset('assets/img/real-estate/' . $fallbackImages[$idx]);
  }

  function formatPrice($price) {
    if ($price >= 10000000) return '₹' . number_format($price / 10000000, 2) . ' Cr';
    if ($price >= 100000)   return '₹' . number_format($price / 100000, 2) . ' L';
    return '₹' . number_format($price);
  }
@endphp

<style>
/* ─── Properties Page ─────────────────────────────────────── */
.prop-hero {
  background: linear-gradient(135deg, #0f4c81 0%, #1a6fc4 50%, #0d9488 100%);
  padding: 56px 0 90px;
  position: relative;
  overflow: hidden;
}
.prop-hero::after {
  content: '';
  position: absolute;
  bottom: -2px; left: 0; right: 0;
  height: 60px;
  background: #f4f6f9;
  clip-path: ellipse(55% 100% at 50% 100%);
}
.prop-hero h1 { color:#fff; font-size:2.4rem; font-weight:800; letter-spacing:-0.5px; }
.prop-hero p  { color:rgba(255,255,255,.8); font-size:1rem; }
.prop-hero .breadcrumb-item a { color:rgba(255,255,255,.75); }
.prop-hero .breadcrumb-item.active { color:rgba(255,255,255,.55); }
.prop-hero .breadcrumb-item+.breadcrumb-item::before { color:rgba(255,255,255,.4); }

/* Stats pills */
.stat-pills { display:flex; gap:12px; flex-wrap:wrap; margin-top:18px; }
.stat-pill {
  background:rgba(255,255,255,.15); backdrop-filter:blur(4px);
  border:1px solid rgba(255,255,255,.25);
  border-radius:50px; padding:6px 18px;
  color:#fff; font-size:.82rem; font-weight:600;
}
.stat-pill i { margin-right:5px; }

/* ─── Search card ──────────────────────────────────────────── */
.prop-search-card {
  background:#fff;
  border-radius:20px;
  box-shadow:0 8px 40px rgba(0,0,0,.12);
  padding:28px 28px 20px;
  margin-top:-50px;
  position:relative;
  z-index:10;
}
.prop-type-tabs .nav-link {
  border-radius:50px !important;
  font-weight:600; font-size:.85rem;
  padding:7px 20px;
  color:#6c757d;
  border:2px solid #e9ecef !important;
  margin-right:6px;
  transition:all .2s;
}
.prop-type-tabs .nav-link.active,
.prop-type-tabs .nav-link:hover {
  background:linear-gradient(135deg,#0f4c81,#1a6fc4) !important;
  color:#fff !important; border-color:transparent !important;
}
.prop-search-card .form-control,
.prop-search-card .form-select {
  border-radius:10px; border:1.5px solid #e4e8f0;
  font-size:.875rem; padding:10px 14px;
  transition:border-color .2s;
}
.prop-search-card .form-control:focus,
.prop-search-card .form-select:focus {
  border-color:#1a6fc4; box-shadow:0 0 0 3px rgba(26,111,196,.12);
}
.prop-search-card label {
  font-size:.72rem; font-weight:700;
  text-transform:uppercase; letter-spacing:.5px;
  color:#8a94a6; margin-bottom:5px; display:block;
}
.btn-search-main {
  background:linear-gradient(135deg,#0f4c81,#1a6fc4);
  color:#fff; border:none; border-radius:12px;
  padding:11px 28px; font-weight:700; font-size:.9rem;
  transition:opacity .2s;
}
.btn-search-main:hover { opacity:.88; color:#fff; }
.btn-adv-toggle {
  background:none; border:1.5px solid #e4e8f0;
  border-radius:10px; padding:9px 18px;
  font-size:.82rem; font-weight:600; color:#6c757d;
  cursor:pointer; transition:all .2s;
}
.btn-adv-toggle:hover { border-color:#1a6fc4; color:#1a6fc4; }

/* Active filter tags */
.filter-tags { display:flex; flex-wrap:wrap; gap:6px; margin-top:10px; }
.filter-tag {
  background:#e8f0fe; color:#1a6fc4; border-radius:50px;
  padding:4px 12px; font-size:.78rem; font-weight:600;
  display:flex; align-items:center; gap:6px;
}
.filter-tag a { color:#1a6fc4; text-decoration:none; line-height:1; }
.filter-tag a:hover { color:#c0392b; }

/* ─── Results bar ──────────────────────────────────────────── */
.results-bar {
  background:#fff; border-radius:14px;
  padding:14px 20px;
  box-shadow:0 2px 10px rgba(0,0,0,.06);
  margin-bottom:24px;
}
.results-count { font-size:1rem; font-weight:700; color:#1e293b; }
.results-count span { color:#1a6fc4; }
.view-toggle-btns .vbtn {
  background:none; border:1.5px solid #e4e8f0; border-radius:8px;
  padding:6px 10px; font-size:1rem; color:#6c757d; cursor:pointer;
  transition:all .2s; margin-left:4px;
}
.view-toggle-btns .vbtn.active,
.view-toggle-btns .vbtn:hover {
  background:#1a6fc4; border-color:#1a6fc4; color:#fff;
}

/* ─── Property Cards (Grid) ────────────────────────────────── */
.prop-card {
  background:#fff; border-radius:18px;
  box-shadow:0 2px 16px rgba(0,0,0,.07);
  overflow:hidden; transition:transform .22s, box-shadow .22s;
  height:100%;
}
.prop-card:hover {
  transform:translateY(-5px);
  box-shadow:0 12px 40px rgba(0,0,0,.13);
}
.prop-card-img {
  position:relative; height:220px; overflow:hidden;
}
.prop-card-img img {
  width:100%; height:100%; object-fit:cover;
  transition:transform .4s ease;
}
.prop-card:hover .prop-card-img img { transform:scale(1.06); }
.prop-img-overlay {
  position:absolute; inset:0;
  background:linear-gradient(to top, rgba(0,0,0,.45) 0%, transparent 60%);
}
.prop-badges {
  position:absolute; top:12px; left:12px;
  display:flex; flex-wrap:wrap; gap:5px;
}
.prop-badge {
  padding:3px 10px; border-radius:50px; font-size:.72rem; font-weight:700;
  text-transform:uppercase; letter-spacing:.4px;
}
.badge-sale   { background:#16a34a; color:#fff; }
.badge-rent   { background:#0891b2; color:#fff; }
.badge-pg     { background:#7c3aed; color:#fff; }
.badge-featured { background:#f59e0b; color:#fff; }
.badge-new    { background:#ef4444; color:#fff; }
.badge-premium { background:linear-gradient(135deg,#b45309,#f59e0b); color:#fff; }
.badge-boosted { background:linear-gradient(135deg,#7c3aed,#a855f7); color:#fff; }
.badge-builder { background:#0f766e; color:#fff; }

.prop-wishlist-btn {
  position:absolute; top:12px; right:12px;
  width:34px; height:34px; border-radius:50%;
  background:rgba(255,255,255,.9); border:none;
  display:flex; align-items:center; justify-content:center;
  font-size:1rem; color:#64748b; cursor:pointer;
  transition:all .2s;
}
.prop-wishlist-btn:hover { background:#fff; color:#ef4444; transform:scale(1.1); }

.prop-price-bar {
  position:absolute; bottom:0; left:0; right:0;
  padding:10px 14px;
  display:flex; align-items:center; justify-content:space-between;
}
.prop-price {
  font-size:1.25rem; font-weight:800; color:#fff;
  text-shadow:0 1px 3px rgba(0,0,0,.4);
}
.prop-price-per-sqft {
  font-size:.72rem; color:rgba(255,255,255,.8);
  background:rgba(0,0,0,.3); border-radius:50px;
  padding:2px 8px;
}

.prop-card-body { padding:16px 18px 18px; }
.prop-type-tag {
  display:inline-block; background:#f1f5f9; color:#475569;
  border-radius:6px; padding:3px 10px; font-size:.72rem; font-weight:700;
  text-transform:uppercase; letter-spacing:.4px; margin-bottom:8px;
}
.prop-title {
  font-size:.97rem; font-weight:700; color:#1e293b;
  margin-bottom:5px; line-height:1.3;
  display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
}
.prop-addr {
  font-size:.8rem; color:#64748b; margin-bottom:10px;
  display:flex; align-items:center; gap:4px;
  white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}
.prop-addr i { flex-shrink:0; color:#1a6fc4; }
.prop-specs {
  display:flex; gap:12px; padding:10px 0;
  border-top:1px solid #f1f5f9; border-bottom:1px solid #f1f5f9;
  margin-bottom:12px;
}
.prop-spec {
  display:flex; align-items:center; gap:5px;
  font-size:.78rem; color:#475569; font-weight:600;
}
.prop-spec i { color:#1a6fc4; font-size:.85rem; }

.prop-footer {
  display:flex; align-items:center; justify-content:space-between;
}
.prop-agent {
  display:flex; align-items:center; gap:8px; flex:1; min-width:0;
}
.prop-agent-avatar {
  width:32px; height:32px; border-radius:50%;
  object-fit:cover; flex-shrink:0; border:2px solid #e2e8f0;
}
.prop-agent-name {
  font-size:.78rem; font-weight:700; color:#334155;
  white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}
.prop-agent-type { font-size:.7rem; color:#94a3b8; }
.btn-view-prop {
  background:linear-gradient(135deg,#0f4c81,#1a6fc4);
  color:#fff; border:none; border-radius:8px;
  padding:6px 14px; font-size:.78rem; font-weight:700;
  text-decoration:none; white-space:nowrap;
  transition:opacity .2s;
}
.btn-view-prop:hover { opacity:.85; color:#fff; }

/* ─── List (row) view ──────────────────────────────────────── */
.prop-list-card {
  background:#fff; border-radius:16px;
  box-shadow:0 2px 12px rgba(0,0,0,.07);
  overflow:hidden; display:flex;
  transition:box-shadow .22s;
}
.prop-list-card:hover { box-shadow:0 8px 32px rgba(0,0,0,.12); }
.prop-list-img {
  width:260px; min-width:260px; position:relative; overflow:hidden;
}
.prop-list-img img {
  width:100%; height:100%; object-fit:cover;
  transition:transform .4s;
}
.prop-list-card:hover .prop-list-img img { transform:scale(1.05); }
.prop-list-body {
  flex:1; padding:20px 22px; display:flex; flex-direction:column; justify-content:space-between;
}
.prop-list-title { font-size:1.08rem; font-weight:800; color:#1e293b; margin-bottom:4px; }
.prop-list-price { font-size:1.4rem; font-weight:800; color:#1a6fc4; }
.prop-list-specs { display:flex; gap:16px; flex-wrap:wrap; margin:8px 0; }
.prop-list-spec {
  display:flex; align-items:center; gap:5px;
  font-size:.82rem; color:#475569; font-weight:600;
}
.prop-list-spec i { color:#1a6fc4; }
.prop-list-actions { display:flex; gap:10px; margin-top:12px; flex-wrap:wrap; }
.btn-list-call {
  background:#dcfce7; color:#16a34a; border:none; border-radius:8px;
  padding:8px 16px; font-size:.8rem; font-weight:700; text-decoration:none;
  transition:all .2s;
}
.btn-list-call:hover { background:#16a34a; color:#fff; }
.btn-list-view {
  background:linear-gradient(135deg,#0f4c81,#1a6fc4);
  color:#fff; border:none; border-radius:8px;
  padding:8px 20px; font-size:.8rem; font-weight:700;
  text-decoration:none; transition:opacity .2s;
}
.btn-list-view:hover { opacity:.85; color:#fff; }

/* hidden views */
.view-grid { display:block; }
.view-list { display:none; }
.view-grid.hidden { display:none; }
.view-list.hidden { display:none; }

/* No results */
.no-results-box {
  text-align:center; padding:60px 20px;
  background:#fff; border-radius:20px;
  box-shadow:0 2px 16px rgba(0,0,0,.06);
}
.no-results-box i { font-size:3.5rem; color:#cbd5e1; }
.no-results-box h4 { margin-top:16px; color:#475569; font-weight:700; }
.no-results-box p { color:#94a3b8; }

/* ─── Pagination ───────────────────────────────────────────── */
.page-link { border-radius:8px !important; margin:0 2px; font-weight:600; }
.page-item.active .page-link {
  background:linear-gradient(135deg,#0f4c81,#1a6fc4);
  border-color:transparent;
}

@media(max-width:768px) {
  .prop-list-card { flex-direction:column; }
  .prop-list-img { width:100%; min-width:unset; height:200px; }
  .prop-hero h1 { font-size:1.7rem; }
}
</style>

{{-- ═══════════════════════════════════════════════════════ --}}
{{--  HERO BANNER                                           --}}
{{-- ═══════════════════════════════════════════════════════ --}}
<div class="prop-hero">
  <div class="container">
    <nav aria-label="breadcrumb" style="margin-bottom:8px;">
      <ol class="breadcrumb" style="background:none;padding:0;margin:0;">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('properties') }}">Properties</a></li>
        @if(isset($locationLabel))
          <li class="breadcrumb-item active">{{ $locationLabel }}</li>
        @else
          <li class="breadcrumb-item active">All Properties</li>
        @endif
      </ol>
    </nav>
    @if(isset($locationLabel))
      <h1><i class="bi bi-geo-alt-fill me-2" style="font-size:2rem;"></i>{{ $seoH1 ?? 'Properties in ' . $locationLabel }}</h1>
      <p>{{ $seoIntro ?? ($properties->total() . ' verified listings in ' . $locationLabel . ' & nearby areas within ' . ($locationRadius ?? 10) . ' km') }}</p>
    @else
      <h1><i class="bi bi-buildings me-2" style="font-size:2rem;"></i>Find Your Perfect Property in Tricity</h1>
      <p>Explore {{ $properties->total() }} verified listings across Chandigarh, Mohali, Zirakpur &amp; Panchkula</p>
    @endif
    <div class="stat-pills">
      <div class="stat-pill"><i class="bi bi-house-check"></i> For Sale</div>
      <div class="stat-pill"><i class="bi bi-key"></i> For Rent</div>
      <div class="stat-pill"><i class="bi bi-people"></i> PG / Co-living</div>
      <div class="stat-pill"><i class="bi bi-shield-check"></i> Verified Listings</div>
    </div>
  </div>
</div>

<main class="main" style="background:#f4f6f9; padding-bottom:60px;">
  <div class="container">

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- SEARCH CARD                                       --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="prop-search-card">
      {{-- Type tabs --}}
      <ul class="nav prop-type-tabs mb-4" id="lookingForTabs">
        <li class="nav-item">
          <a class="nav-link {{ !request('looking_for') ? 'active' : '' }}"
             href="{{ url('/properties') . '?' . http_build_query(request()->except(['looking_for', 'page'])) }}">
            <i class="bi bi-buildings me-1"></i> All
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request('looking_for') == 'Sale' ? 'active' : '' }}"
             href="{{ url('/properties') . '?' . http_build_query(array_merge(request()->except(['looking_for','page']), ['looking_for'=>'Sale'])) }}">
            <i class="bi bi-house-check me-1"></i> Buy
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request('looking_for') == 'Rent' ? 'active' : '' }}"
             href="{{ url('/properties') . '?' . http_build_query(array_merge(request()->except(['looking_for','page']), ['looking_for'=>'Rent'])) }}">
            <i class="bi bi-key me-1"></i> Rent
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request('looking_for') == 'PG' ? 'active' : '' }}"
             href="{{ url('/properties') . '?' . http_build_query(array_merge(request()->except(['looking_for','page']), ['looking_for'=>'PG'])) }}">
            <i class="bi bi-people me-1"></i> PG
          </a>
        </li>
      </ul>

      <form method="GET" action="{{ url('/properties') }}" id="propSearchForm">
        @if(request('looking_for'))
          <input type="hidden" name="looking_for" value="{{ request('looking_for') }}">
        @endif

        {{-- Main filter row --}}
        <div class="row g-3 align-items-end">
          <div class="col-lg-3 col-md-6">
            <label><i class="bi bi-search me-1"></i>Search</label>
            <input type="text" name="keyword" class="form-control"
                   placeholder="Area, city, project, landmark…"
                   value="{{ request('keyword') }}"
                   autocomplete="off" id="propKeyword">
          </div>
          <div class="col-lg-2 col-md-6">
            <label><i class="bi bi-geo-alt me-1"></i>City</label>
            <select name="city" class="form-select">
              <option value="">All Cities</option>
              @foreach($cities as $city)
                <option value="{{ $city }}" {{ request('city')==$city ? 'selected':'' }}>{{ $city }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-lg-2 col-md-6">
            <label><i class="bi bi-building me-1"></i>Type</label>
            <select name="property_type" class="form-select">
              <option value="">All Types</option>
              @foreach($propertyTypes as $type)
                <option value="{{ $type }}" {{ request('property_type')==$type ? 'selected':'' }}>{{ $type }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-lg-2 col-md-6">
            <label><i class="bi bi-currency-rupee me-1"></i>Budget</label>
            <select name="min_price" class="form-select">
              <option value="">Min Price</option>
              <option value="500000"   {{ request('min_price')=='500000'   ? 'selected':'' }}>₹5 Lakh+</option>
              <option value="1000000"  {{ request('min_price')=='1000000'  ? 'selected':'' }}>₹10 Lakh+</option>
              <option value="2500000"  {{ request('min_price')=='2500000'  ? 'selected':'' }}>₹25 Lakh+</option>
              <option value="5000000"  {{ request('min_price')=='5000000'  ? 'selected':'' }}>₹50 Lakh+</option>
              <option value="10000000" {{ request('min_price')=='10000000' ? 'selected':'' }}>₹1 Cr+</option>
              <option value="20000000" {{ request('min_price')=='20000000' ? 'selected':'' }}>₹2 Cr+</option>
              <option value="50000000" {{ request('min_price')=='50000000' ? 'selected':'' }}>₹5 Cr+</option>
            </select>
          </div>
          <div class="col-lg-2 col-md-6">
            <label>&nbsp;</label>
            <select name="max_price" class="form-select">
              <option value="">Max Price</option>
              <option value="1000000"   {{ request('max_price')=='1000000'   ? 'selected':'' }}>Up to ₹10L</option>
              <option value="2500000"   {{ request('max_price')=='2500000'   ? 'selected':'' }}>Up to ₹25L</option>
              <option value="5000000"   {{ request('max_price')=='5000000'   ? 'selected':'' }}>Up to ₹50L</option>
              <option value="10000000"  {{ request('max_price')=='10000000'  ? 'selected':'' }}>Up to ₹1 Cr</option>
              <option value="20000000"  {{ request('max_price')=='20000000'  ? 'selected':'' }}>Up to ₹2 Cr</option>
              <option value="50000000"  {{ request('max_price')=='50000000'  ? 'selected':'' }}>Up to ₹5 Cr</option>
              <option value="100000000" {{ request('max_price')=='100000000' ? 'selected':'' }}>₹5 Cr+</option>
            </select>
          </div>
          <div class="col-lg-1 col-md-6">
            <label>&nbsp;</label>
            <button type="submit" class="btn-search-main w-100">
              <i class="bi bi-search"></i>
            </button>
          </div>
        </div>

        {{-- Advanced filters (collapsible) --}}
        <div class="mt-3">
          <button type="button" class="btn-adv-toggle" onclick="toggleAdv(this)">
            <i class="bi bi-sliders me-1"></i> More Filters
            <i class="bi bi-chevron-down ms-1" id="advChevron"></i>
          </button>
          @if(request()->anyFilled(['keyword','city','property_type','min_price','max_price','bedrooms','furnishing_status','bhk_type','sort_by','pet_friendly','gated_society','vastu_compliant']))
            <a href="{{ url('/properties') }}" class="ms-2" style="font-size:.82rem; color:#e11d48; font-weight:600;">
              <i class="bi bi-x-circle me-1"></i>Clear All
            </a>
          @endif
        </div>

        <div id="advFilters" style="display:none;" class="mt-3">
          <div class="row g-3 align-items-end">
            <div class="col-lg-2 col-md-4">
              <label><i class="bi bi-grid-3x3 me-1"></i>BHK</label>
              <select name="bhk_type" class="form-select">
                <option value="">Any BHK</option>
                @foreach(['1 BHK','2 BHK','3 BHK','4 BHK','5 BHK','Studio','Villa','Penthouse'] as $bhk)
                  <option value="{{ $bhk }}" {{ request('bhk_type')==$bhk ? 'selected':'' }}>{{ $bhk }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-lg-2 col-md-4">
              <label><i class="bi bi-house-door me-1"></i>Bedrooms</label>
              <select name="bedrooms" class="form-select">
                <option value="">Any</option>
                @foreach([1,2,3,4,5] as $b)
                  <option value="{{ $b }}" {{ request('bedrooms')==$b ? 'selected':'' }}>{{ $b }}+</option>
                @endforeach
              </select>
            </div>
            <div class="col-lg-2 col-md-4">
              <label><i class="bi bi-lamp me-1"></i>Furnishing</label>
              <select name="furnishing_status" class="form-select">
                <option value="">Any</option>
                <option value="Furnished"      {{ request('furnishing_status')=='Furnished'      ? 'selected':'' }}>Furnished</option>
                <option value="Semi-Furnished" {{ request('furnishing_status')=='Semi-Furnished' ? 'selected':'' }}>Semi-Furnished</option>
                <option value="Unfurnished"    {{ request('furnishing_status')=='Unfurnished'    ? 'selected':'' }}>Unfurnished</option>
              </select>
            </div>
            <div class="col-lg-2 col-md-4">
              <label><i class="bi bi-sort-down me-1"></i>Sort By</label>
              <select name="sort_by" class="form-select">
                <option value="newest"     {{ request('sort_by','newest')=='newest'     ? 'selected':'' }}>Newest First</option>
                <option value="price_low"  {{ request('sort_by')=='price_low'           ? 'selected':'' }}>Price: Low → High</option>
                <option value="price_high" {{ request('sort_by')=='price_high'          ? 'selected':'' }}>Price: High → Low</option>
                <option value="area"       {{ request('sort_by')=='area'                ? 'selected':'' }}>Largest Area</option>
              </select>
            </div>
            <div class="col-lg-4 col-md-8 d-flex align-items-end gap-3 flex-wrap pb-1">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="pet_friendly" value="1" id="chkPet" {{ request('pet_friendly') ? 'checked':'' }}>
                <label class="form-check-label" for="chkPet" style="font-size:.82rem;font-weight:600;">🐾 Pet Friendly</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="gated_society" value="1" id="chkGated" {{ request('gated_society') ? 'checked':'' }}>
                <label class="form-check-label" for="chkGated" style="font-size:.82rem;font-weight:600;">🏡 Gated</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="vastu_compliant" value="1" id="chkVastu" {{ request('vastu_compliant') ? 'checked':'' }}>
                <label class="form-check-label" for="chkVastu" style="font-size:.82rem;font-weight:600;">🔱 Vastu</label>
              </div>
            </div>
          </div>
        </div>
      </form>

      {{-- Active filter tags --}}
      @php
        $activeFilters = [];
        if(request('keyword'))           $activeFilters[] = ['label'=>'Search: '.request('keyword'),   'key'=>'keyword'];
        if(request('city'))              $activeFilters[] = ['label'=>'City: '.request('city'),         'key'=>'city'];
        if(request('property_type'))     $activeFilters[] = ['label'=>request('property_type'),         'key'=>'property_type'];
        if(request('bhk_type'))          $activeFilters[] = ['label'=>request('bhk_type'),              'key'=>'bhk_type'];
        if(request('min_price'))         $activeFilters[] = ['label'=>'Min: ₹'.number_format(request('min_price')), 'key'=>'min_price'];
        if(request('max_price'))         $activeFilters[] = ['label'=>'Max: ₹'.number_format(request('max_price')), 'key'=>'max_price'];
        if(request('bedrooms'))          $activeFilters[] = ['label'=>request('bedrooms').'+ Beds',     'key'=>'bedrooms'];
        if(request('furnishing_status')) $activeFilters[] = ['label'=>request('furnishing_status'),     'key'=>'furnishing_status'];
        if(request('pet_friendly'))      $activeFilters[] = ['label'=>'Pet Friendly',                   'key'=>'pet_friendly'];
        if(request('gated_society'))     $activeFilters[] = ['label'=>'Gated Society',                  'key'=>'gated_society'];
        if(request('vastu_compliant'))   $activeFilters[] = ['label'=>'Vastu Compliant',               'key'=>'vastu_compliant'];
      @endphp
      @if(count($activeFilters))
        <div class="filter-tags mt-3">
          @foreach($activeFilters as $f)
            <span class="filter-tag">
              {{ $f['label'] }}
              @php
                $nextQuery = request()->except([$f['key'], 'page']);
                $nextCity   = $nextQuery['city'] ?? null;
                $baseUrl    = $nextCity ? route('properties.city', ['city' => $nextCity]) : url('/properties');
              @endphp
              <a href="{{ $baseUrl . (count($nextQuery) ? ('?'.http_build_query($nextQuery)) : '') }}">&times;</a>
            </span>
          @endforeach
        </div>
      @endif
    </div>{{-- /prop-search-card --}}

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- RESULTS BAR                                       --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="results-bar d-flex align-items-center justify-content-between mt-4">
            <div class="results-count">
        <span>{{ $properties->total() }}</span> Properties
        @php $displayCity = request('city'); @endphp
        @if(!$displayCity && request()->route('city')) $displayCity = request()->route('city'); @endif
        @if($displayCity) in {{ $displayCity }} @endif
        @if(request('looking_for') == 'Sale') &nbsp;· For Sale @elseif(request('looking_for') == 'Rent') &nbsp;· For Rent @elseif(request('looking_for') == 'PG') &nbsp;· PG @endif
        @if($properties->total() > 0)
          <small class="text-muted ms-2">({{ $properties->firstItem() }}–{{ $properties->lastItem() }} shown)</small>
        @endif
      </div>
      <div class="view-toggle-btns" id="viewToggle">
        <button class="vbtn active" onclick="setView('grid', this)" title="Grid View"><i class="bi bi-grid-3x3-gap"></i></button>
        <button class="vbtn"        onclick="setView('list', this)" title="List View"><i class="bi bi-view-stacked"></i></button>
      </div>
    </div>

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- GRID VIEW                                         --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="view-grid" id="viewGrid">
      @forelse($properties as $property)
        @php
          $imgUrl   = propImage($property, $fallbackImages);
          $priceStr = formatPrice($property->price);
          $lookingFor = strtolower($property->looking_for ?? '');
          $isNew    = $property->created_at && $property->created_at->diffInDays(now()) <= 7;
          $agentName  = $property->dealer?->name ?? ($property->builder?->company_name ?? $property->builder?->name ?? null);
          $agentType  = $property->dealer ? ($property->dealer->agency ?? 'Property Dealer') : ($property->builder ? 'Builder / Developer' : '');
          $agentPhone = config('app.contact_phone','7340753780');
          $agentAvatar = asset('assets/img/real-estate/agent-' . (($property->id % 10) + 1) . '.webp');
        @endphp

        @if($loop->first)
          <div class="row g-4">
        @endif

        <div class="col-lg-4 col-md-6">
          <div class="prop-card h-100">
            {{-- Image --}}
            <div class="prop-card-img">
              <img src="{{ $imgUrl }}" alt="{{ $property->title }}" loading="lazy">
              <div class="prop-img-overlay"></div>

              {{-- Badges --}}
              <div class="prop-badges">
                @if(in_array($lookingFor, ['sale','sell','buy']))
                  <span class="prop-badge badge-sale">For Sale</span>
                @elseif($lookingFor === 'rent')
                  <span class="prop-badge badge-rent">For Rent</span>
                @elseif($lookingFor === 'pg')
                  <span class="prop-badge badge-pg">PG</span>
                @else
                  <span class="prop-badge badge-sale">{{ $property->looking_for }}</span>
                @endif
                @if($property->is_featured) <span class="prop-badge badge-featured">Featured</span> @endif
                @if($property->is_premium)  <span class="prop-badge badge-premium">Premium</span>   @endif
                @if($property->is_boosted)  <span class="prop-badge badge-boosted">Boosted</span>   @endif
                @if($property->builder_id)  <span class="prop-badge badge-builder">Builder</span>   @endif
                @if($isNew && !$property->is_featured) <span class="prop-badge badge-new">New</span> @endif
              </div>

              {{-- Wishlist --}}
              <button class="prop-wishlist-btn" title="Save to Wishlist">
                <i class="bi bi-heart"></i>
              </button>

              {{-- Price bar --}}
              <div class="prop-price-bar">
                <span class="prop-price">{{ $priceStr }}</span>
                @if($property->area && $property->price)
                  <span class="prop-price-per-sqft">
                    ₹{{ number_format($property->price / max($property->area, 1)) }}/sqft
                  </span>
                @endif
              </div>
            </div>

            {{-- Body --}}
            <div class="prop-card-body">
              @if($property->property_type)
                <span class="prop-type-tag">{{ $property->property_type }}</span>
              @endif
              <div class="prop-title">{{ $property->title }}</div>
              <div class="prop-addr">
                <i class="bi bi-geo-alt-fill"></i>
                {{ $property->locality ? $property->locality.', ' : '' }}{{ $property->city }}{{ $property->state ? ', '.$property->state : '' }}
              </div>

              {{-- Specs --}}
              <div class="prop-specs">
                @if($property->bedrooms)
                  <div class="prop-spec"><i class="bi bi-house-door"></i> {{ $property->bedrooms }} Bed</div>
                @endif
                @if($property->bathrooms)
                  <div class="prop-spec"><i class="bi bi-droplet"></i> {{ $property->bathrooms }} Bath</div>
                @endif
                @if($property->area)
                  <div class="prop-spec"><i class="bi bi-arrows-angle-expand"></i> {{ number_format($property->area) }} sqft</div>
                @endif
                @if($property->furnishing_status)
                  <div class="prop-spec"><i class="bi bi-lamp"></i> {{ $property->furnishing_status }}</div>
                @endif
              </div>

              {{-- Footer --}}
              <div class="prop-footer">
                <div class="prop-agent">
                  <img src="{{ $agentAvatar }}" alt="agent" class="prop-agent-avatar"
                       onerror="this.src='{{ asset('assets/img/real-estate/agent-1.webp') }}'">
                  <div style="min-width:0;">
                    <div class="prop-agent-name">{{ $agentName ?? config('app.name') }}</div>
                    <div class="prop-agent-type">{{ $agentType }}</div>
                  </div>
                </div>
                <a href="{{ route('property-details', $property) }}" class="btn-view-prop">
                  View <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
        </div>

        @if($loop->last)
          </div>{{-- /row --}}
        @endif

      @empty
        @include('frontend.partials.builder-projects-fallback')
      @endforelse
    </div>{{-- /view-grid --}}

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- LIST VIEW                                         --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="view-list" id="viewList" style="display:none;">
      @forelse($properties as $property)
        @php
          $imgUrl   = propImage($property, $fallbackImages);
          $priceStr = formatPrice($property->price);
          $lookingFor = strtolower($property->looking_for ?? '');
          $agentName  = $property->dealer?->name ?? ($property->builder?->company_name ?? $property->builder?->name ?? null);
          $agentPhone = config('app.contact_phone','7340753780');
        @endphp
        <div class="prop-list-card mb-3">
          <div class="prop-list-img">
            <img src="{{ $imgUrl }}" alt="{{ $property->title }}" loading="lazy">
            <div class="prop-badges" style="position:absolute;top:10px;left:10px;">
              @if(in_array($lookingFor, ['sale','sell','buy']))
                <span class="prop-badge badge-sale">For Sale</span>
              @elseif($lookingFor === 'rent')
                <span class="prop-badge badge-rent">For Rent</span>
              @elseif($lookingFor === 'pg')
                <span class="prop-badge badge-pg">PG</span>
              @else
                <span class="prop-badge badge-sale">{{ $property->looking_for }}</span>
              @endif
              @if($property->is_featured) <span class="prop-badge badge-featured">Featured</span> @endif
            </div>
          </div>
          <div class="prop-list-body">
            <div>
              <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
                <div>
                  @if($property->property_type)
                    <span class="prop-type-tag">{{ $property->property_type }}</span>
                    @if($property->bhk_type) <span class="prop-type-tag">{{ $property->bhk_type }}</span> @endif
                  @endif
                  <div class="prop-list-title mt-1">{{ $property->title }}</div>
                  <div class="prop-addr mt-1">
                    <i class="bi bi-geo-alt-fill"></i>
                    {{ $property->locality ? $property->locality.', ':'' }}{{ $property->city }}{{ $property->state ? ', '.$property->state:'' }}
                  </div>
                </div>
                <div class="prop-list-price">{{ $priceStr }}</div>
              </div>
              <div class="prop-list-specs mt-1">
                @if($property->bedrooms)  <div class="prop-list-spec"><i class="bi bi-house-door"></i> {{ $property->bedrooms }} Bed</div> @endif
                @if($property->bathrooms) <div class="prop-list-spec"><i class="bi bi-droplet"></i> {{ $property->bathrooms }} Bath</div> @endif
                @if($property->area)      <div class="prop-list-spec"><i class="bi bi-arrows-angle-expand"></i> {{ number_format($property->area) }} sqft</div> @endif
                @if($property->furnishing_status) <div class="prop-list-spec"><i class="bi bi-lamp"></i> {{ $property->furnishing_status }}</div> @endif
                @if($property->floor_number) <div class="prop-list-spec"><i class="bi bi-layers"></i> Floor {{ $property->floor_number }}</div> @endif
              </div>
              @if($agentName)
                <div class="prop-list-spec mt-2">
                  <i class="bi bi-person-circle" style="font-size:.95rem;"></i>
                  <span style="font-size:.82rem;">{{ $agentName }}</span>
                  @if($property->builder_id) <span class="ms-1 text-muted" style="font-size:.75rem;">(Builder)</span> @endif
                </div>
              @endif
            </div>
            <div class="prop-list-actions">
              @if($agentPhone)
                <a href="tel:+91{{ $agentPhone }}" class="btn-list-call">
                  <i class="bi bi-telephone me-1"></i>Call
                </a>
              @endif
              @if($agentPhone)
                <a href="https://wa.me/91{{ preg_replace('/\D/','',$agentPhone) }}" target="_blank"
                   class="btn-list-call" style="background:#dcfce7;color:#16a34a;">
                  <i class="bi bi-whatsapp me-1"></i>WhatsApp
                </a>
              @endif
              <a href="{{ route('property-details', $property) }}" class="btn-list-view">
                View Details <i class="bi bi-arrow-right ms-1"></i>
              </a>
            </div>
          </div>
        </div>
      @empty
        @include('frontend.partials.builder-projects-fallback')
      @endforelse
    </div>{{-- /view-list --}}

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- PAGINATION                                        --}}
    {{-- ══════════════════════════════════════════════════ --}}
    @if($properties->hasPages())
      <nav class="mt-5">
        <div class="row align-items-center">
          <div class="col-md-6 mb-2 mb-md-0">
            <p class="text-muted mb-0" style="font-size:.85rem;">
              Showing <strong>{{ $properties->firstItem() }}</strong>–<strong>{{ $properties->lastItem() }}</strong>
              of <strong>{{ $properties->total() }}</strong> properties
            </p>
          </div>
          <div class="col-md-6 d-flex justify-content-md-end">
            {{ $properties->links('pagination::bootstrap-5') }}
          </div>
        </div>
      </nav>
    @endif

  </div>
</main>

<script>
// View toggle (grid / list)
function setView(v, btn) {
  document.getElementById('viewGrid').style.display = (v === 'grid') ? 'block' : 'none';
  document.getElementById('viewList').style.display = (v === 'list') ? 'block' : 'none';
  document.querySelectorAll('#viewToggle .vbtn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  try { localStorage.setItem('propView', v); } catch(e){}
}

// Advanced filter toggle
function toggleAdv(btn) {
  var el = document.getElementById('advFilters');
  var chev = document.getElementById('advChevron');
  if (el.style.display === 'none' || el.style.display === '') {
    el.style.display = 'block';
    chev.classList.replace('bi-chevron-down','bi-chevron-up');
    btn.style.borderColor = '#1a6fc4';
    btn.style.color = '#1a6fc4';
  } else {
    el.style.display = 'none';
    chev.classList.replace('bi-chevron-up','bi-chevron-down');
    btn.style.borderColor = '';
    btn.style.color = '';
  }
}

// Improved form submission (SEO mapping disabled for this page)
//
// To restore earlier working behavior for /properties search,
// we keep results on the same page using query-string parameters.
//
// If you later want dedicated SEO landing pages, we can re-enable
// path-based redirects behind a flag.
document.getElementById('propSearchForm').addEventListener('submit', function(e) {
  // Allow normal form GET submission so results render on /properties
  // and match the expected earlier behavior.
  // (No preventDefault here.)
});


// Restore view preference
(function() {
  try {
    var saved = localStorage.getItem('propView');
    if (saved === 'list') {
      document.getElementById('viewGrid').style.display = 'none';
      document.getElementById('viewList').style.display = 'block';
      var btns = document.querySelectorAll('#viewToggle .vbtn');
      if (btns[0]) btns[0].classList.remove('active');
      if (btns[1]) btns[1].classList.add('active');
    }
  } catch(e){}
})();

// Auto-open advanced filters if any advanced filter is active
(function() {
  var adv = ['bedrooms','furnishing_status','bhk_type','sort_by','pet_friendly','gated_society','vastu_compliant'];
  var url  = new URLSearchParams(window.location.search);
  var hasAdv = adv.some(k => url.get(k));
  if (hasAdv) {
    var btn = document.querySelector('.btn-adv-toggle');
    if (btn) toggleAdv(btn);
  }
})();
</script>
