@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/frontend/agents.css') }}">
@endpush

<main class="dl-page">

  {{-- ===== HEADER BANNER ===== --}}
  <div class="dl-banner">
    <div class="container">
      <div class="row align-items-center gy-3">
        <div class="col-lg-7">
          <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0" style="background:none;padding:0;font-size:.82rem;">
              <li class="breadcrumb-item"><a href="/" style="color:rgba(255,255,255,.8);text-decoration:none;">Home</a></li>
              <li class="breadcrumb-item active" style="color:#fff;">Dealers</li>
            </ol>
          </nav>
          <h1>Find Verified Property Dealers</h1>
          <p>Connect with trusted dealers near you. Browse listings, check reviews, and contact directly.</p>
          <div class="dl-stats-row">
            <div class="dl-stat">
              <div class="val">{{ $dealers->total() }}</div>
              <div class="lbl">Active Dealers</div>
            </div>
            <div class="dl-stat">
              <div class="val">{{ \App\Models\Property::whereNotIn('status', ['sold','rented','inactive','draft','expired'])->count() }}</div>
              <div class="lbl">Live Properties</div>
            </div>
            <div class="dl-stat">
              <div class="val">{{ \App\Models\Property::distinct('city')->whereNotNull('city')->count('city') }}+</div>
              <div class="lbl">Cities Covered</div>
            </div>
          </div>
        </div>
        <div class="col-lg-5 d-none d-lg-flex justify-content-end">
          <img src="/assets/img/real-estate/agent-10.webp" alt="Dealers"
               style="height:220px;border-radius:16px;object-fit:cover;opacity:.9;box-shadow:0 8px 30px rgba(0,0,0,.25);">
        </div>
      </div>
    </div>
  </div>

  {{-- ===== SEARCH BAR ===== --}}
  <div class="dl-search-bar">
    <div class="container">
      <form action="{{ route('agents') }}" method="GET" class="dl-search-inner">
        <input
          type="text"
          name="search"
          class="dl-input"
          placeholder="Search by name, company, or city..."
          value="{{ request('search') }}"
        >
        <button type="submit" class="dl-search-btn">
          <i class="bi bi-search me-1"></i> Search Dealers
        </button>
        @if(request('search'))
        <a href="{{ route('agents') }}" class="dl-search-btn" style="background:#64748b;text-decoration:none;">
          <i class="bi bi-x-circle me-1"></i> Clear
        </a>
        @endif
      </form>
    </div>
  </div>

  <div class="container py-4">

    {{-- ===== RESULTS HEADER ===== --}}
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
      <div class="dl-section-title" style="margin-bottom:0;">
        @if(request('search'))
          Results for "{{ request('search') }}" — {{ $dealers->total() }} dealer{{ $dealers->total() != 1 ? 's' : '' }} found
        @else
          All Verified Dealers ({{ $dealers->total() }})
        @endif
      </div>
      <span style="font-size:.8rem;color:#94a3b8;">
        Showing {{ $dealers->firstItem() }}–{{ $dealers->lastItem() }} of {{ $dealers->total() }}
      </span>
    </div>

    {{-- ===== DEALER GRID ===== --}}
    @if($dealers->count())
    <div class="row g-3 mb-4">
      @foreach($dealers as $i => $dealer)
      <div class="col-xl-3 col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($i % 4) * 80 }}">
        <div class="dl-card">

          {{-- Photo / Avatar --}}
          <div class="dl-card-photo">
            @if($dealer->profile_photo)
              <img src="{{ asset('storage/' . $dealer->profile_photo) }}" alt="{{ $dealer->full_name }}">
            @else
              <div class="dl-avatar-fallback">
                {{ strtoupper(substr($dealer->first_name ?? $dealer->company_name ?? 'D', 0, 1)) }}
              </div>
            @endif

            {{-- Status badge --}}
            @if($dealer->status === 'active')
              <span class="dl-badge verified"><i class="bi bi-patch-check-fill me-1"></i>Verified</span>
            @endif

            {{-- Property count pill --}}
            <span class="dl-prop-count">
              <i class="bi bi-buildings"></i> {{ $dealer->properties_count }} {{ $dealer->properties_count == 1 ? 'Property' : 'Properties' }}
            </span>
          </div>

          {{-- Body --}}
          <div class="dl-card-body">
            <h4 class="dl-dealer-name">
              {{ trim(($dealer->first_name ?? '') . ' ' . ($dealer->last_name ?? '')) ?: ($dealer->company_name ?? 'Dealer') }}
            </h4>

            @if($dealer->company_name)
            <p class="dl-company-name">
              <i class="bi bi-building"></i> {{ $dealer->company_name }}
            </p>
            @endif

            @if($dealer->operating_cities)
            <div class="dl-cities">
              <i class="bi bi-geo-alt-fill"></i>
              <span>{{ $dealer->operating_cities }}</span>
            </div>
            @endif

            {{-- Specializations --}}
            @if($dealer->specializations)
            <div class="dl-spec-tags">
              @foreach(array_slice(explode(',', $dealer->specializations), 0, 3) as $spec)
              <span class="dl-spec-tag">{{ trim($spec) }}</span>
              @endforeach
            </div>
            @endif

            {{-- Mini stats --}}
            <div class="dl-mini-stats">
              <div class="dl-mini-stat">
                <div class="ms-val">{{ $dealer->properties_count }}</div>
                <div class="ms-lbl">Listings</div>
              </div>
              <div class="dl-mini-stat">
                <div class="ms-val">{{ $dealer->properties()->sum('views_count') }}</div>
                <div class="ms-lbl">Views</div>
              </div>
              <div class="dl-mini-stat">
                <div class="ms-val">
                  {{ $dealer->properties()->whereNotNull('city')->distinct()->count('city') }}
                </div>
                <div class="ms-lbl">Cities</div>
              </div>
            </div>

            {{-- Actions --}}
            <div class="dl-card-actions">
              <a href="{{ route('agent-profile', $dealer) }}" class="dl-btn-profile">
                <i class="bi bi-person-lines-fill"></i> View Profile
              </a>
              <a href="tel:+91{{ config('app.contact_phone','7340753780') }}" class="dl-btn-call" title="Call">
                <i class="bi bi-telephone-fill"></i>
              </a>
              <a href="https://wa.me/91{{ config('app.contact_phone','7340753780') }}" target="_blank" class="dl-btn-wa" title="WhatsApp">
                <i class="bi bi-whatsapp"></i>
              </a>
            </div>
          </div>

        </div>
      </div>
      @endforeach
    </div>

    {{-- Pagination --}}
    {{ $dealers->links('vendor.pagination.indianesthub') }}

    @else
    {{-- Empty state --}}
    <div class="dl-empty">
      <i class="bi bi-people"></i>
      <h4>No dealers found</h4>
      <p>
        @if(request('search'))
          No dealers match "{{ request('search') }}". Try a different keyword.
        @else
          No active dealers are registered yet.
        @endif
      </p>
      @if(request('search'))
      <a href="{{ route('agents') }}" class="btn btn-primary mt-3">View All Dealers</a>
      @endif
    </div>
    @endif

  </div>

</main>
