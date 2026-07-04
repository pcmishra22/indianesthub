@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/frontend/builders.css') }}">
@endpush

<main class="bl-page">

  {{-- ===== HEADER BANNER ===== --}}
  <div class="bl-banner">
    <div class="container">
      <div class="row align-items-center gy-3">
        <div class="col-lg-8">
          <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0" style="background:none;padding:0;font-size:.82rem;">
              <li class="breadcrumb-item"><a href="/" style="color:rgba(255,255,255,.8);text-decoration:none;">Home</a></li>
              <li class="breadcrumb-item active" style="color:#fff;">Builders & Developers</li>
            </ol>
          </nav>
          <h1><i class="bi bi-buildings-fill me-2" style="font-size:1.6rem;opacity:.85;"></i>Top Builders & Developers</h1>
          <p>Discover premium residential &amp; commercial projects from India's trusted builders. Direct from developer — no brokerage.</p>
          <div class="bl-stats-row">
            <div class="bl-stat">
              <div class="val">{{ $totalBuilders }}</div>
              <div class="lbl">Active Builders</div>
            </div>
            <div class="bl-stat">
              <div class="val">{{ $totalProjects }}</div>
              <div class="lbl">Total Projects</div>
            </div>
            <div class="bl-stat">
              <div class="val">{{ $verifiedCount }}</div>
              <div class="lbl">Verified Builders</div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 d-none d-lg-flex justify-content-end">
          <img src="/assets/img/real-estate/agent-10.webp" alt="Builders"
               style="height:220px;border-radius:16px;object-fit:cover;opacity:.9;box-shadow:0 8px 30px rgba(0,0,0,.25);">
        </div>
      </div>
    </div>
  </div>

  {{-- ===== SEARCH BAR ===== --}}
  <div class="bl-search-bar">
    <div class="container">
      <form action="{{ route('builders.index') }}" method="GET" class="bl-search-inner">
        <input type="text" name="search" class="bl-input"
               placeholder="Search by builder name, company, or city..."
               value="{{ request('search') }}">
        <input type="text" name="city" class="bl-input" style="max-width:180px;"
               placeholder="City..." value="{{ request('city') }}">
        <button type="submit" class="bl-search-btn">
          <i class="bi bi-search me-1"></i> Search
        </button>
        @if(request()->anyFilled(['search', 'city']))
        <a href="{{ route('builders.index') }}" class="bl-search-btn" style="background:#64748b;text-decoration:none;">
          <i class="bi bi-x-circle me-1"></i> Clear
        </a>
        @endif
      </form>
    </div>
  </div>

  <div class="container py-4">

    {{-- ===== RESULTS HEADER ===== --}}
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
      <div class="bl-section-title" style="margin-bottom:0;">
        @if(request('search') || request('city'))
          Search Results — {{ $builders->total() }} builder{{ $builders->total() != 1 ? 's' : '' }} found
        @else
          All Builders & Developers ({{ $builders->total() }})
        @endif
      </div>
      @if($builders->total() > 0)
      <span style="font-size:.8rem;color:#94a3b8;">
        Showing {{ $builders->firstItem() }}–{{ $builders->lastItem() }} of {{ $builders->total() }}
      </span>
      @endif
    </div>

    {{-- ===== BUILDER GRID ===== --}}
    @if($builders->count())
    <div class="row g-3 mb-4">
      @foreach($builders as $i => $builder)
      <div class="col-xl-3 col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($i % 4) * 80 }}">
        <div class="bl-card">

          {{-- Logo / Avatar --}}
          <div class="bl-card-photo">
            @if($builder->logo)
              <img src="{{ asset('storage/' . $builder->logo) }}" alt="{{ $builder->company_name ?: $builder->name }}">
            @else
              <div class="bl-avatar-fallback">
                {{ strtoupper(substr($builder->company_name ?: $builder->name, 0, 1)) }}
              </div>
            @endif

            {{-- Badges --}}
            @if($builder->is_verified)
              <span class="bl-badge verified"><i class="bi bi-patch-check-fill me-1"></i>Verified</span>
            @endif

            {{-- Project count pill --}}
            <span class="bl-proj-count">
              <i class="bi bi-diagram-3"></i>
              {{ $builder->projects_count }} {{ $builder->projects_count == 1 ? 'Project' : 'Projects' }}
            </span>
          </div>

          {{-- Body --}}
          <div class="bl-card-body">
            <h4 class="bl-builder-name">{{ $builder->company_name ?: $builder->name }}</h4>

            @if($builder->company_name && $builder->name && $builder->company_name !== $builder->name)
            <p class="bl-company-sub"><i class="bi bi-person"></i> {{ $builder->name }}</p>
            @endif

            @if($builder->city || $builder->cities_operating)
            <div class="bl-cities">
              <i class="bi bi-geo-alt-fill"></i>
              <span>{{ $builder->city }}{{ $builder->cities_operating ? ($builder->city ? ', ' : '') . $builder->cities_operating : '' }}</span>
            </div>
            @endif

            {{-- Type tags --}}
            <div class="bl-type-tags">
              @if($builder->established_year)
              <span class="bl-type-tag"><i class="bi bi-calendar3 me-1"></i>Est. {{ $builder->established_year }}</span>
              @endif
              @if($builder->rera_registration)
              <span class="bl-type-tag"><i class="bi bi-file-earmark-check me-1"></i>RERA</span>
              @endif
              @if($builder->total_delivered_projects > 0)
              <span class="bl-type-tag">{{ $builder->total_delivered_projects }}+ Delivered</span>
              @endif
            </div>

            {{-- Mini stats --}}
            <div class="bl-mini-stats">
              <div class="bl-mini-stat">
                <div class="ms-val">{{ $builder->projects_count }}</div>
                <div class="ms-lbl">Projects</div>
              </div>
              <div class="bl-mini-stat">
                <div class="ms-val">{{ $builder->total_delivered_projects ?? 0 }}</div>
                <div class="ms-lbl">Delivered</div>
              </div>
              <div class="bl-mini-stat">
                <div class="ms-val">
                  @if($builder->rating > 0)
                    {{ number_format($builder->rating, 1) }}★
                  @else
                    —
                  @endif
                </div>
                <div class="ms-lbl">Rating</div>
              </div>
            </div>

            {{-- Actions --}}
            <div class="bl-card-actions">
              <a href="{{ route('builders.show', $builder) }}" class="bl-btn-profile">
                <i class="bi bi-building-fill"></i> View Profile
              </a>
              <a href="tel:+91{{ config('app.contact_phone','7340753780') }}" class="bl-btn-call" title="Call">
                <i class="bi bi-telephone-fill"></i>
              </a>
              <a href="https://wa.me/91{{ config('app.contact_phone','7340753780') }}"
                 target="_blank" class="bl-btn-wa" title="WhatsApp">
                <i class="bi bi-whatsapp"></i>
              </a>
            </div>
          </div>

        </div>
      </div>
      @endforeach
    </div>

    {{-- Pagination --}}
    {{ $builders->links('vendor.pagination.indianesthub') }}

    @else
    <div class="bl-empty">
      <i class="bi bi-buildings"></i>
      <h4>No builders found</h4>
      <p>
        @if(request()->anyFilled(['search', 'city']))
          No builders match your search. Try different keywords.
        @else
          No active builders are registered yet.
        @endif
      </p>
      @if(request()->anyFilled(['search', 'city']))
      <a href="{{ route('builders.index') }}" class="btn btn-primary mt-3">View All Builders</a>
      @endif
    </div>
    @endif

  </div>

</main>
