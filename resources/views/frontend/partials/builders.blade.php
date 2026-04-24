<style>
/* ===== BUILDERS LIST PAGE ===== */
.bl-page { background: #f1f5f9; min-height: 100vh; }

/* Header banner */
.bl-banner {
  background: linear-gradient(135deg, #0a2d5e 0%, #0f4c81 60%, #1565c0 100%);
  padding: 48px 0 40px;
  color: #fff;
}
.bl-banner h1 { font-size: 2rem; font-weight: 800; margin: 0 0 6px; }
.bl-banner p  { font-size: .95rem; opacity: .88; margin: 0; }
.bl-banner .bl-stats-row {
  display: flex; gap: 28px; flex-wrap: wrap; margin-top: 24px;
}
.bl-banner .bl-stat {
  background: rgba(255,255,255,.15);
  border: 1px solid rgba(255,255,255,.25);
  border-radius: 8px; padding: 10px 18px; text-align: center;
}
.bl-banner .bl-stat .val { font-size: 1.5rem; font-weight: 800; line-height: 1; }
.bl-banner .bl-stat .lbl { font-size: .72rem; opacity: .85; margin-top: 2px; }

/* Search bar */
.bl-search-bar {
  background: #fff; border-bottom: 1px solid #e2e8f0;
  padding: 16px 0; position: sticky; top: 70px; z-index: 100;
  box-shadow: 0 2px 8px rgba(0,0,0,.06);
}
.bl-search-bar .bl-search-inner { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
.bl-search-bar .bl-input {
  flex: 1; min-width: 220px;
  border: 1px solid #cbd5e1; border-radius: 8px;
  padding: 10px 14px 10px 38px; font-size: .88rem; color: #334155; outline: none;
  background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0'/%3E%3C/svg%3E") no-repeat 12px center;
  transition: border-color .2s;
}
.bl-search-bar .bl-input:focus { border-color: #0078d4; background-color: #fff; }
.bl-search-bar .bl-search-btn {
  background: #0078d4; color: #fff; border: none;
  border-radius: 8px; padding: 10px 22px;
  font-size: .88rem; font-weight: 600; cursor: pointer; white-space: nowrap;
  transition: background .2s;
}
.bl-search-bar .bl-search-btn:hover { background: #0a2d5e; }

/* Builder card */
.bl-card {
  background: #fff; border-radius: 12px; border: 1px solid #e2e8f0;
  overflow: hidden; transition: box-shadow .22s, transform .22s;
  height: 100%; display: flex; flex-direction: column;
}
.bl-card:hover { box-shadow: 0 8px 24px rgba(0,120,212,.12); transform: translateY(-3px); }

/* Card photo */
.bl-card-photo {
  position: relative; height: 180px;
  background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
  overflow: hidden; flex-shrink: 0;
}
.bl-card-photo img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
.bl-card:hover .bl-card-photo img { transform: scale(1.05); }
.bl-card-photo .bl-avatar-fallback {
  width: 100%; height: 100%;
  display: flex; align-items: center; justify-content: center;
  font-size: 3.5rem; color: #0078d4; font-weight: 800;
  background: linear-gradient(135deg, #dbeafe 0%, #93c5fd 100%);
}
.bl-badge {
  position: absolute; top: 10px; left: 10px;
  font-size: .68rem; font-weight: 700; padding: 3px 9px;
  border-radius: 4px; text-transform: uppercase; letter-spacing: .4px;
}
.bl-badge.verified { background: #22c55e; color: #fff; }
.bl-badge.featured { background: #f59e0b; color: #fff; }
.bl-proj-count {
  position: absolute; bottom: 10px; right: 10px;
  background: rgba(0,0,0,.65); color: #fff;
  font-size: .72rem; font-weight: 600;
  padding: 4px 10px; border-radius: 20px;
  display: flex; align-items: center; gap: 4px;
}

/* Card body */
.bl-card-body { padding: 18px; flex: 1; display: flex; flex-direction: column; }
.bl-builder-name { font-size: 1.05rem; font-weight: 700; color: #1e293b; margin: 0 0 2px; line-height: 1.3; }
.bl-company-sub  { font-size: .8rem; color: #64748b; margin: 0 0 10px; display: flex; align-items: center; gap: 4px; }
.bl-cities       { font-size: .78rem; color: #475569; display: flex; align-items: flex-start; gap: 5px; margin-bottom: 10px; }
.bl-cities i     { color: #ef4444; margin-top: 2px; flex-shrink: 0; }

/* Project type tags */
.bl-type-tags    { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 14px; }
.bl-type-tag {
  background: #eff6ff; border: 1px solid #bfdbfe;
  color: #1d4ed8; font-size: .7rem; font-weight: 500;
  padding: 3px 9px; border-radius: 20px;
}

/* Mini stats */
.bl-mini-stats {
  display: flex; gap: 0;
  border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9;
  margin-bottom: 14px; padding: 10px 0;
}
.bl-mini-stat   { flex: 1; text-align: center; border-right: 1px solid #f1f5f9; }
.bl-mini-stat:last-child { border-right: none; }
.bl-mini-stat .ms-val { font-size: .95rem; font-weight: 700; color: #1e293b; }
.bl-mini-stat .ms-lbl { font-size: .65rem; color: #94a3b8; margin-top: 1px; }

/* Card actions */
.bl-card-actions { display: flex; gap: 8px; margin-top: auto; }
.bl-btn-profile {
  flex: 1; background: #0078d4; color: #fff;
  border: none; border-radius: 7px; padding: 10px;
  font-size: .85rem; font-weight: 600; cursor: pointer;
  text-align: center; text-decoration: none;
  transition: background .2s; display: flex; align-items: center; justify-content: center; gap: 6px;
}
.bl-btn-profile:hover { background: #0a2d5e; color: #fff; text-decoration: none; }
.bl-btn-call {
  width: 40px; height: 40px; background: #f0fdf4; color: #16a34a;
  border: 1px solid #bbf7d0; border-radius: 7px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1rem; text-decoration: none; transition: all .2s; flex-shrink: 0;
}
.bl-btn-call:hover { background: #16a34a; color: #fff; border-color: #16a34a; }
.bl-btn-wa {
  width: 40px; height: 40px; background: #f0fdf4; color: #25d366;
  border: 1px solid #bbf7d0; border-radius: 7px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1rem; text-decoration: none; transition: all .2s; flex-shrink: 0;
}
.bl-btn-wa:hover { background: #25d366; color: #fff; border-color: #25d366; }

/* Empty / section */
.bl-empty { text-align: center; padding: 80px 20px; background: #fff; border-radius: 12px; border: 2px dashed #e2e8f0; }
.bl-empty i { font-size: 3.5rem; color: #cbd5e1; margin-bottom: 16px; display: block; }
.bl-empty h4 { font-size: 1.1rem; color: #64748b; margin: 0 0 6px; }
.bl-empty p  { font-size: .88rem; color: #94a3b8; }
.bl-section-title {
  font-size: .8rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .8px; color: #64748b; margin-bottom: 16px;
  display: flex; align-items: center; gap: 8px;
}
.bl-section-title::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }

@media(max-width:768px) {
  .bl-banner h1 { font-size: 1.45rem; }
  .bl-banner .bl-stats-row { gap: 12px; }
}
</style>

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
              @if($builder->phone)
              <a href="tel:{{ $builder->phone }}" class="bl-btn-call" title="Call">
                <i class="bi bi-telephone-fill"></i>
              </a>
              <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $builder->phone) }}"
                 target="_blank" class="bl-btn-wa" title="WhatsApp">
                <i class="bi bi-whatsapp"></i>
              </a>
              @endif
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
