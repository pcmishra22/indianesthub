<style>
/* ===== DEALER PROFILE PAGE ===== */
.dp-page { background: #f1f5f9; min-height: 100vh; }

/* Hero */
.dp-hero {
  background: linear-gradient(135deg, #1565c0 0%, #1f85de 60%, #0ea5e9 100%);
  padding: 40px 0 0;
  color: #fff;
  position: relative;
}
.dp-hero::after {
  content: '';
  display: block;
  height: 40px;
  background: #f1f5f9;
  margin-top: -1px;
  clip-path: ellipse(55% 100% at 50% 100%);
}
.dp-avatar-wrap {
  position: relative;
  display: inline-block;
}
.dp-avatar {
  width: 110px; height: 110px;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid rgba(255,255,255,.9);
  box-shadow: 0 4px 20px rgba(0,0,0,.25);
}
.dp-avatar-fallback {
  width: 110px; height: 110px;
  border-radius: 50%;
  background: rgba(255,255,255,.2);
  border: 4px solid rgba(255,255,255,.9);
  display: flex; align-items: center; justify-content: center;
  font-size: 2.8rem; font-weight: 800; color: #fff;
}
.dp-verified-dot {
  position: absolute; bottom: 6px; right: 6px;
  width: 24px; height: 24px; border-radius: 50%;
  background: #22c55e; border: 2px solid #fff;
  display: flex; align-items: center; justify-content: center;
  font-size: .65rem; color: #fff;
}
.dp-dealer-name { font-size: 1.75rem; font-weight: 800; margin: 0 0 4px; }
.dp-dealer-company { font-size: .92rem; opacity: .88; margin: 0 0 8px; }
.dp-dealer-meta {
  display: flex; gap: 16px; flex-wrap: wrap;
  font-size: .83rem; opacity: .9; margin-bottom: 18px;
}
.dp-dealer-meta span { display: flex; align-items: center; gap: 5px; }
.dp-hero-ctas { display: flex; gap: 10px; flex-wrap: wrap; padding-bottom: 48px; }
.dp-hero-ctas .btn {
  border-radius: 8px; font-weight: 600; font-size: .88rem; padding: 10px 20px;
  display: flex; align-items: center; gap: 7px;
}
.btn-wa-hero { background: #25d366; border-color: #25d366; color: #fff; }
.btn-wa-hero:hover { background: #1ebe5d; border-color: #1ebe5d; color: #fff; }

/* Stats bar */
.dp-stats-bar {
  background: #fff;
  border-bottom: 1px solid #e2e8f0;
  margin-bottom: 24px;
}
.dp-stats-inner {
  display: flex; flex-wrap: wrap;
}
.dp-stat-item {
  flex: 1; min-width: 130px;
  padding: 16px 20px;
  text-align: center;
  border-right: 1px solid #f1f5f9;
}
.dp-stat-item:last-child { border-right: none; }
.dp-stat-item .s-val {
  font-size: 1.55rem; font-weight: 800; color: #1565c0; line-height: 1;
}
.dp-stat-item .s-lbl {
  font-size: .72rem; color: #64748b; margin-top: 3px; text-transform: uppercase; letter-spacing: .5px;
}

/* Two-column layout */
.dp-layout { display: flex; gap: 20px; align-items: flex-start; }
.dp-main { flex: 1; min-width: 0; }
.dp-aside {
  width: 300px;
  flex-shrink: 0;
  position: sticky;
  top: 80px;
}

/* Cards */
.dp-card {
  background: #fff;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  padding: 20px 22px;
  margin-bottom: 16px;
}
.dp-card-title {
  font-size: 1rem; font-weight: 700; color: #1e293b;
  padding-bottom: 12px; margin-bottom: 16px;
  border-bottom: 2px solid #f1f5f9;
  display: flex; align-items: center; gap: 8px;
}
.dp-card-title i { color: #1f85de; }

/* Bio */
.dp-bio { font-size: .9rem; color: #475569; line-height: 1.75; }

/* Specializations */
.dp-spec-grid { display: flex; flex-wrap: wrap; gap: 8px; }
.dp-spec-chip {
  background: #f1f5f9; border: 1px solid #e2e8f0;
  color: #475569; font-size: .78rem; font-weight: 500;
  padding: 5px 12px; border-radius: 20px;
}

/* Cities */
.dp-cities-grid { display: flex; flex-wrap: wrap; gap: 8px; }
.dp-city-chip {
  background: #eff6ff; border: 1px solid #bfdbfe;
  color: #1d4ed8; font-size: .78rem; font-weight: 600;
  padding: 5px 12px; border-radius: 20px;
  display: flex; align-items: center; gap: 4px;
}

/* ===== PROPERTY CARDS GRID ===== */
.dp-prop-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px; }
.dp-prop-card {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  overflow: hidden;
  transition: box-shadow .22s, transform .22s;
  text-decoration: none;
  display: block;
}
.dp-prop-card:hover {
  box-shadow: 0 8px 24px rgba(31,133,222,.14);
  transform: translateY(-3px);
  text-decoration: none;
}
.dp-prop-img {
  position: relative;
  height: 175px;
  overflow: hidden;
}
.dp-prop-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
.dp-prop-card:hover .dp-prop-img img { transform: scale(1.05); }
.dp-prop-tag {
  position: absolute; top: 10px; left: 10px;
  font-size: .68rem; font-weight: 700; padding: 3px 9px;
  border-radius: 4px; text-transform: uppercase; letter-spacing: .4px;
}
.dp-prop-tag.sale { background: #22c55e; color: #fff; }
.dp-prop-tag.rent { background: #1f85de; color: #fff; }
.dp-prop-tag.featured { background: #f97316; color: #fff; }
.dp-prop-views {
  position: absolute; bottom: 8px; right: 8px;
  background: rgba(0,0,0,.6); color: #fff;
  font-size: .68rem; padding: 3px 8px; border-radius: 12px;
  display: flex; align-items: center; gap: 3px;
}
.dp-prop-body { padding: 14px 16px; }
.dp-prop-price {
  font-size: 1.1rem; font-weight: 800; color: #1565c0;
  margin: 0 0 4px;
}
.dp-prop-title {
  font-size: .85rem; font-weight: 600; color: #1e293b;
  margin: 0 0 6px; line-height: 1.35;
}
.dp-prop-location {
  font-size: .75rem; color: #64748b;
  display: flex; align-items: center; gap: 4px;
  margin-bottom: 10px;
}
.dp-prop-specs {
  display: flex; gap: 10px; flex-wrap: wrap;
  font-size: .72rem; color: #64748b;
  padding-top: 10px; border-top: 1px solid #f1f5f9;
}
.dp-prop-specs span { display: flex; align-items: center; gap: 3px; }

/* Filter/sort bar */
.dp-filter-bar {
  background: #fff; border: 1px solid #e2e8f0;
  border-radius: 10px; padding: 14px 16px;
  margin-bottom: 16px;
  display: flex; gap: 10px; align-items: center; flex-wrap: wrap;
}
.dp-filter-bar .dp-filter-label { font-size: .8rem; font-weight: 600; color: #64748b; white-space: nowrap; }
.dp-filter-bar .form-select {
  border: 1px solid #e2e8f0; border-radius: 7px;
  font-size: .82rem; padding: 7px 10px; color: #334155;
  max-width: 180px;
}

/* Sidebar contact card */
.dp-contact-card {
  background: linear-gradient(135deg, #1565c0 0%, #1f85de 100%);
  border-radius: 12px; padding: 20px;
  color: #fff; margin-bottom: 16px;
  box-shadow: 0 4px 15px rgba(31,133,222,.3);
}
.dp-contact-card h5 { font-size: .95rem; font-weight: 700; margin-bottom: 14px; }
.dp-contact-row { display: flex; flex-direction: column; gap: 8px; margin-bottom: 14px; }
.dp-contact-item {
  display: flex; align-items: center; gap: 8px;
  font-size: .82rem;
  background: rgba(255,255,255,.15);
  border-radius: 7px; padding: 9px 12px;
}
.dp-contact-item i { width: 16px; text-align: center; }
.dp-contact-btn { display: flex; flex-direction: column; gap: 8px; }
.dp-contact-btn .btn {
  border-radius: 7px; font-weight: 600; font-size: .85rem;
  padding: 10px; display: flex; align-items: center; justify-content: center; gap: 7px;
}

/* Inquiry form in aside */
.dp-inq-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 16px; }
.dp-inq-card h5 { font-size: .9rem; font-weight: 700; color: #1e293b; margin-bottom: 14px; }
.dp-inq-card .form-control {
  border: 1px solid #cbd5e1; border-radius: 7px;
  font-size: .82rem; padding: 9px 12px; color: #334155;
}
.dp-inq-card .form-control:focus { border-color: #1f85de; box-shadow: 0 0 0 3px rgba(31,133,222,.1); }

/* Empty state */
.dp-no-props {
  text-align: center; padding: 60px 20px;
  background: #fff; border-radius: 12px; border: 2px dashed #e2e8f0;
}
.dp-no-props i { font-size: 3rem; color: #cbd5e1; display: block; margin-bottom: 14px; }
.dp-no-props h5 { color: #64748b; margin: 0 0 6px; }
.dp-no-props p  { font-size: .85rem; color: #94a3b8; }

/* Pagination handled by vendor/pagination/indianesthub.blade.php */

@media(max-width:991px) {
  .dp-layout { flex-direction: column; }
  .dp-aside { width: 100%; position: static; }
  .dp-prop-grid { grid-template-columns: repeat(2, 1fr); }
}

@media(max-width:576px) {
  .dp-prop-grid { grid-template-columns: 1fr; }
  .dp-stats-inner { flex-wrap: wrap; }
  .dp-stat-item { min-width: 120px; }
}
</style>

<main class="dp-page">

  {{-- ===== HERO ===== --}}
  <div class="dp-hero">
    <div class="container">
      <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="background:none;padding:0;font-size:.82rem;">
          <li class="breadcrumb-item"><a href="/" style="color:rgba(255,255,255,.8);text-decoration:none;">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('agents') }}" style="color:rgba(255,255,255,.8);text-decoration:none;">Dealers</a></li>
          <li class="breadcrumb-item active" style="color:#fff;">
            {{ trim(($dealer->first_name ?? '') . ' ' . ($dealer->last_name ?? '')) ?: ($dealer->company_name ?? 'Dealer') }}
          </li>
        </ol>
      </nav>

      <div class="row align-items-end">
        <div class="col-lg-8">
          <div class="d-flex align-items-center gap-4 flex-wrap">
            {{-- Avatar --}}
            <div class="dp-avatar-wrap">
              @if($dealer->profile_photo)
                <img src="{{ asset('storage/' . $dealer->profile_photo) }}" class="dp-avatar" alt="{{ $dealer->full_name }}">
              @else
                <div class="dp-avatar-fallback">
                  {{ strtoupper(substr($dealer->first_name ?? $dealer->company_name ?? 'D', 0, 1)) }}
                </div>
              @endif
              @if($dealer->status === 'active')
              <div class="dp-verified-dot"><i class="bi bi-check-lg"></i></div>
              @endif
            </div>

            {{-- Info --}}
            <div>
              <h1 class="dp-dealer-name">
                {{ trim(($dealer->first_name ?? '') . ' ' . ($dealer->last_name ?? '')) ?: ($dealer->company_name ?? 'Dealer') }}
              </h1>
              @if($dealer->company_name)
              <p class="dp-dealer-company"><i class="bi bi-building me-1"></i>{{ $dealer->company_name }}</p>
              @endif
              <div class="dp-dealer-meta">
                @auth
                @if($dealer->phone)
                <span><i class="bi bi-telephone-fill"></i> {{ $dealer->phone }}</span>
                @endif
                @if($dealer->email)
                <span><i class="bi bi-envelope-fill"></i> {{ $dealer->email }}</span>
                @endif
                @if($dealer->operating_cities)
                <span><i class="bi bi-geo-alt-fill"></i> {{ $dealer->operating_cities }}</span>
                @endif
                @else
                <span><i class="bi bi-lock-fill"></i> Login to view contact details</span>
                @endauth
              </div>

              <div class="dp-hero-ctas">
                @if($dealer->phone)
                <a href="tel:{{ $dealer->phone }}" class="btn btn-light text-primary">
                  <i class="bi bi-telephone-fill"></i> Call Now
                </a>
                <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $dealer->phone) }}?text=Hi, I want to connect regarding your listed properties."
                   target="_blank" class="btn btn-wa-hero">
                  <i class="bi bi-whatsapp"></i> WhatsApp
                </a>
                @endif
                <a href="#properties" class="btn btn-outline-light">
                  <i class="bi bi-buildings"></i> View Properties ({{ $dealer->properties_count }})
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ===== STATS BAR ===== --}}
  <div class="dp-stats-bar">
    <div class="container">
      <div class="dp-stats-inner">
        <div class="dp-stat-item">
          <div class="s-val">{{ $dealer->properties_count }}</div>
          <div class="s-lbl">Total Listings</div>
        </div>
        <div class="dp-stat-item">
          <div class="s-val">{{ $dealer->properties()->whereNotIn('status', ['sold','rented','inactive','draft','expired'])->count() }}</div>
          <div class="s-lbl">Active Listings</div>
        </div>
        <div class="dp-stat-item">
          <div class="s-val">{{ number_format($totalViews) }}</div>
          <div class="s-lbl">Total Views</div>
        </div>
        <div class="dp-stat-item">
          <div class="s-val">{{ $citiesServed->count() }}</div>
          <div class="s-lbl">Cities Served</div>
        </div>
        <div class="dp-stat-item">
          <div class="s-val">{{ $dealer->properties()->where('is_featured', true)->count() }}</div>
          <div class="s-lbl">Featured Props</div>
        </div>
      </div>
    </div>
  </div>

  {{-- ===== MAIN CONTENT ===== --}}
  <div class="container pb-5">
    <div class="dp-layout">

      {{-- ===== LEFT: DEALER INFO + PROPERTIES ===== --}}
      <div class="dp-main">

        {{-- About / Bio --}}
        @if($dealer->bio)
        <div class="dp-card">
          <div class="dp-card-title"><i class="bi bi-person-lines-fill"></i> About This Dealer</div>
          <div class="dp-bio">{{ $dealer->bio }}</div>
        </div>
        @endif

        {{-- Specializations --}}
        @if($dealer->specializations)
        <div class="dp-card">
          <div class="dp-card-title"><i class="bi bi-stars"></i> Specializations</div>
          <div class="dp-spec-grid">
            @foreach(explode(',', $dealer->specializations) as $spec)
            <span class="dp-spec-chip"><i class="bi bi-check-circle-fill text-primary me-1"></i>{{ trim($spec) }}</span>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Cities Served --}}
        @if($citiesServed->count())
        <div class="dp-card">
          <div class="dp-card-title"><i class="bi bi-map-fill"></i> Cities Covered</div>
          <div class="dp-cities-grid">
            @foreach($citiesServed as $city)
            <span class="dp-city-chip"><i class="bi bi-geo-alt-fill"></i> {{ $city }}</span>
            @endforeach
          </div>
        </div>
        @endif

        {{-- ===== PROPERTIES GRID ===== --}}
        <div id="properties">
          <div class="dp-card-title" style="font-size:1rem;font-weight:700;color:#1e293b;margin-bottom:12px;">
            <i class="bi bi-buildings" style="color:#1f85de;"></i>
            Properties by {{ trim(($dealer->first_name ?? '') . ' ' . ($dealer->last_name ?? '')) ?: ($dealer->company_name ?? 'Dealer') }}
            <span style="font-size:.8rem;font-weight:400;color:#64748b;">({{ $properties->total() }} listings)</span>
          </div>

          @if($properties->count())
          <div class="dp-prop-grid mb-3">
            @foreach($properties as $property)
            @php
              if($property->images && $property->images->count()) {
                $thumb = url('storage/dealer/' . $property->property_dealer_id . '/' . $property->id . '/images/' . basename($property->images->first()->image_path));
              } elseif($property->cover_image) {
                $thumb = asset('storage/' . $property->cover_image);
              } else {
                $thumb = '/assets/img/real-estate/property-exterior-7.webp';
              }
            @endphp
            <a href="{{ route('property-details', $property) }}" class="dp-prop-card">
              <div class="dp-prop-img">
                <img src="{{ $thumb }}" alt="{{ $property->title }}">
                {{-- Tags --}}
                <span class="dp-prop-tag {{ strtolower($property->looking_for ?? '') == 'rent' ? 'rent' : 'sale' }}">
                  For {{ $property->looking_for ?? 'Sale' }}
                </span>
                @if($property->is_featured)
                <span class="dp-prop-tag featured" style="left:auto;right:10px;">Featured</span>
                @endif
                @if($property->views_count)
                <span class="dp-prop-views"><i class="bi bi-eye"></i> {{ $property->views_count }}</span>
                @endif
              </div>
              <div class="dp-prop-body">
                <div class="dp-prop-price">
                  ₹{{ number_format($property->price) }}
                  @if(strtolower($property->looking_for ?? '') == 'rent')
                  <span style="font-size:.75rem;font-weight:400;color:#64748b">/mo</span>
                  @endif
                  @if($property->price_per_sqft)
                  <span style="font-size:.7rem;font-weight:400;color:#64748b;margin-left:6px;">₹{{ number_format($property->price_per_sqft) }}/sqft</span>
                  @endif
                </div>
                <div class="dp-prop-title">{{ Str::limit($property->title, 55) }}</div>
                <div class="dp-prop-location">
                  <i class="bi bi-geo-alt-fill text-danger"></i>
                  {{ implode(', ', array_filter([$property->locality ?? null, $property->city])) }}
                </div>
                <div class="dp-prop-specs">
                  @if($property->bhk_type ?? $property->bedrooms)
                  <span><i class="bi bi-house-door"></i> {{ $property->bhk_type ?? ($property->bedrooms . ' BHK') }}</span>
                  @endif
                  @if($property->carpet_area ?? $property->area)
                  <span><i class="bi bi-arrows-angle-expand"></i> {{ number_format($property->carpet_area ?? $property->area) }} sqft</span>
                  @endif
                  @if($property->bathrooms)
                  <span><i class="bi bi-droplet"></i> {{ $property->bathrooms }} Bath</span>
                  @endif
                  @if($property->furnishing_status)
                  <span><i class="bi bi-lamp"></i> {{ $property->furnishing_status }}</span>
                  @endif
                </div>
              </div>
            </a>
            @endforeach
          </div>

          {{-- Pagination --}}
          <div class="dp-pagination">
            {{ $properties->links('vendor.pagination.indianesthub') }}
          </div>

          @else
          <div class="dp-no-props">
            <i class="bi bi-buildings"></i>
            <h5>No active properties listed yet</h5>
            <p>This dealer hasn't listed any active properties. Check back later.</p>
            <a href="{{ route('properties') }}" class="btn btn-primary mt-3">Browse All Properties</a>
          </div>
          @endif
        </div>

      </div>{{-- End dp-main --}}

      {{-- ===== RIGHT ASIDE ===== --}}
      <div class="dp-aside">

        {{-- Contact Card --}}
        <div class="dp-contact-card">
          <h5><i class="bi bi-person-badge-fill me-2"></i>Contact Dealer</h5>

          <div class="dp-contact-row">
            @auth
              @if($dealer->phone)
              <div class="dp-contact-item">
                <i class="bi bi-telephone-fill"></i> {{ $dealer->phone }}
              </div>
              @endif
              @if($dealer->email)
              <div class="dp-contact-item">
                <i class="bi bi-envelope-fill"></i> {{ $dealer->email }}
              </div>
              @endif
              @if($dealer->operating_cities)
              <div class="dp-contact-item">
                <i class="bi bi-geo-alt-fill"></i> {{ $dealer->operating_cities }}
              </div>
              @endif
            @else
              <div class="dp-contact-item">
                <i class="bi bi-lock-fill"></i> Login to view contact details
              </div>
            @endauth
          </div>

          <div class="dp-contact-btn">
            @auth
              @if($dealer->phone)
              <a href="tel:{{ $dealer->phone }}" class="btn btn-light text-success fw-bold">
                <i class="bi bi-telephone-fill"></i> Call Now
              </a>
              <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $dealer->phone) }}?text=Hi, I want to connect regarding your listed properties."
                 target="_blank" class="btn btn-success">
                <i class="bi bi-whatsapp"></i> WhatsApp
              </a>
              @endif
            @endauth
          </div>

          <div class="d-flex gap-6 mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,.2);">
            <div style="font-size:.72rem;opacity:.85;display:flex;align-items:center;gap:4px;">
              <i class="bi bi-shield-check-fill"></i> Verified Dealer
            </div>
          </div>
        </div>

        {{-- Quick Inquiry Form --}}
        <div class="dp-inq-card">
          @auth
            <h5><i class="bi bi-chat-quote-fill me-2 text-primary"></i>Send Inquiry</h5>
            <form action="{{ route('property.inquiry.submit') }}" method="POST" id="dealer-inquiry-form">
              @csrf
              <input type="hidden" name="property_id" value="{{ $properties->first()?->id ?? 0 }}">
              <div class="mb-2">
                <input type="text" name="name" class="form-control" placeholder="Your Name *" required>
              </div>
              <div class="mb-2">
                <input type="email" name="email" class="form-control" placeholder="Email *" required>
              </div>
              <div class="mb-2">
                <input type="tel" name="phone" class="form-control" placeholder="Phone Number">
              </div>
              <div class="mb-3">
                <textarea name="message" class="form-control" rows="3"
                  placeholder="I'm interested in your properties. Please contact me.">Hi, I am looking for a property. Please get in touch with me.</textarea>
              </div>
              <button type="submit" class="btn btn-primary w-100" style="border-radius:7px;font-weight:700;padding:10px;">
                <i class="bi bi-send-fill me-2"></i>Send Message
              </button>
            </form>
          @else
            <h5><i class="bi bi-lock-fill me-2 text-primary"></i>Login to Send Inquiry</h5>
            <p style="margin:0;color:#64748b;font-size:.88rem;">
              Please login to contact this dealer.
            </p>
          @endauth
        </div>

        {{-- Back to dealers --}}
        <a href="{{ route('agents') }}"
           class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2"
           style="border-radius:8px;font-weight:600;font-size:.85rem;padding:10px;">
          <i class="bi bi-arrow-left"></i> Back to All Dealers
        </a>

      </div>{{-- End dp-aside --}}

    </div>{{-- End dp-layout --}}
  </div>

</main>

<script>
document.getElementById('dealer-inquiry-form')?.addEventListener('submit', function(e) {
  e.preventDefault();
  const form = this;
  const btn  = form.querySelector('button[type="submit"]');
  const orig = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
  fetch(form.action, {
    method: 'POST',
    body: new FormData(form),
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(r => r.json())
  .then(() => {
    btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Sent!';
    btn.classList.replace('btn-primary', 'btn-success');
    form.reset();
    setTimeout(() => {
      btn.disabled = false;
      btn.innerHTML = orig;
      btn.classList.replace('btn-success', 'btn-primary');
    }, 4000);
  })
  .catch(() => {
    btn.disabled = false;
    btn.innerHTML = orig;
  });
});
</script>
