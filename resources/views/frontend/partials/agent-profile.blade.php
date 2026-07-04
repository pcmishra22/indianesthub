@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/frontend/agents.css') }}">
@endpush

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
                <span><i class="bi bi-telephone-fill"></i> +91 {{ config('app.contact_phone','7340753780') }}</span>
                <span><i class="bi bi-envelope-fill"></i> {{ config('app.contact_email','admin@indianesthub.com') }}</span>
                @if($dealer->operating_cities)
                <span><i class="bi bi-geo-alt-fill"></i> {{ $dealer->operating_cities }}</span>
                @endif
                @else
                <span><i class="bi bi-lock-fill"></i> Login to view contact details</span>
                @endauth
              </div>

              <div class="dp-hero-ctas">
                <a href="tel:+91{{ config('app.contact_phone','7340753780') }}" class="btn btn-light text-primary">
                  <i class="bi bi-telephone-fill"></i> Call Now
                </a>
                <a href="https://wa.me/91{{ config('app.contact_phone','7340753780') }}?text=Hi, I want to connect regarding your listed properties."
                   target="_blank" class="btn btn-wa-hero">
                  <i class="bi bi-whatsapp"></i> WhatsApp
                </a>
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
              <div class="dp-contact-item">
                <i class="bi bi-telephone-fill"></i> +91 {{ config('app.contact_phone','7340753780') }}
              </div>
              <div class="dp-contact-item">
                <i class="bi bi-envelope-fill"></i> {{ config('app.contact_email','admin@indianesthub.com') }}
              </div>
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
              <a href="tel:+91{{ config('app.contact_phone','7340753780') }}" class="btn btn-light text-success fw-bold">
                <i class="bi bi-telephone-fill"></i> Call Now
              </a>
              <a href="https://wa.me/91{{ config('app.contact_phone','7340753780') }}?text=Hi, I want to connect regarding your listed properties."
                 target="_blank" class="btn btn-success">
                <i class="bi bi-whatsapp"></i> WhatsApp
              </a>
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
