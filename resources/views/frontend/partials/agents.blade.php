<style>
/* ===== DEALERS LIST PAGE ===== */
.dl-page { background: #f1f5f9; min-height: 100vh; }

/* Header banner */
.dl-banner {
  background: linear-gradient(135deg, #1565c0 0%, #1f85de 60%, #0ea5e9 100%);
  padding: 48px 0 40px;
  color: #fff;
}
.dl-banner h1 { font-size: 2rem; font-weight: 800; margin: 0 0 6px; }
.dl-banner p  { font-size: .95rem; opacity: .88; margin: 0; }
.dl-banner .dl-stats-row {
  display: flex; gap: 28px; flex-wrap: wrap; margin-top: 24px;
}
.dl-banner .dl-stat {
  background: rgba(255,255,255,.15);
  border: 1px solid rgba(255,255,255,.25);
  border-radius: 8px;
  padding: 10px 18px;
  text-align: center;
}
.dl-banner .dl-stat .val { font-size: 1.5rem; font-weight: 800; line-height: 1; }
.dl-banner .dl-stat .lbl { font-size: .72rem; opacity: .85; margin-top: 2px; }

/* Search / filter bar */
.dl-search-bar {
  background: #fff;
  border-bottom: 1px solid #e2e8f0;
  padding: 16px 0;
  position: sticky;
  top: 70px;
  z-index: 100;
  box-shadow: 0 2px 8px rgba(0,0,0,.06);
}
.dl-search-bar .dl-search-inner {
  display: flex; gap: 10px; align-items: center; flex-wrap: wrap;
}
.dl-search-bar .dl-input {
  flex: 1;
  min-width: 220px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  padding: 10px 14px 10px 38px;
  font-size: .88rem;
  color: #334155;
  outline: none;
  transition: border-color .2s;
  background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0'/%3E%3C/svg%3E") no-repeat 12px center;
}
.dl-search-bar .dl-input:focus { border-color: #1f85de; background-color: #fff; }
.dl-search-bar .dl-search-btn {
  background: #1f85de; color: #fff; border: none;
  border-radius: 8px; padding: 10px 22px;
  font-size: .88rem; font-weight: 600; cursor: pointer;
  transition: background .2s; white-space: nowrap;
}
.dl-search-bar .dl-search-btn:hover { background: #1565c0; }

/* Dealer card */
.dl-card {
  background: #fff;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  overflow: hidden;
  transition: box-shadow .22s, transform .22s;
  height: 100%;
  display: flex;
  flex-direction: column;
}
.dl-card:hover {
  box-shadow: 0 8px 24px rgba(31,133,222,.15);
  transform: translateY(-3px);
}

/* Card photo */
.dl-card-photo {
  position: relative;
  height: 180px;
  background: linear-gradient(135deg, #dbeafe 0%, #e0f2fe 100%);
  overflow: hidden;
  flex-shrink: 0;
}
.dl-card-photo img {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform .3s ease;
}
.dl-card:hover .dl-card-photo img { transform: scale(1.05); }
.dl-card-photo .dl-avatar-fallback {
  width: 100%; height: 100%;
  display: flex; align-items: center; justify-content: center;
  font-size: 3.5rem; color: #1f85de; font-weight: 800;
  background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
}
.dl-badge {
  position: absolute; top: 10px; left: 10px;
  font-size: .68rem; font-weight: 700; padding: 3px 9px;
  border-radius: 4px; text-transform: uppercase; letter-spacing: .4px;
}
.dl-badge.verified { background: #22c55e; color: #fff; }
.dl-badge.active   { background: #1f85de; color: #fff; }
.dl-badge.premium  { background: #7c3aed; color: #fff; }

.dl-prop-count {
  position: absolute; bottom: 10px; right: 10px;
  background: rgba(0,0,0,.65); color: #fff;
  font-size: .72rem; font-weight: 600;
  padding: 4px 10px; border-radius: 20px;
  display: flex; align-items: center; gap: 4px;
}

/* Card body */
.dl-card-body { padding: 18px; flex: 1; display: flex; flex-direction: column; }
.dl-dealer-name {
  font-size: 1.05rem; font-weight: 700; color: #1e293b;
  margin: 0 0 2px; line-height: 1.3;
}
.dl-company-name {
  font-size: .8rem; color: #64748b;
  margin: 0 0 10px;
  display: flex; align-items: center; gap: 4px;
}
.dl-cities {
  font-size: .78rem; color: #475569;
  display: flex; align-items: flex-start; gap: 5px;
  margin-bottom: 10px;
}
.dl-cities i { color: #ef4444; margin-top: 2px; flex-shrink: 0; }

/* Specialization tags */
.dl-spec-tags {
  display: flex; flex-wrap: wrap; gap: 5px;
  margin-bottom: 14px;
}
.dl-spec-tag {
  background: #f1f5f9; border: 1px solid #e2e8f0;
  color: #475569; font-size: .7rem; font-weight: 500;
  padding: 3px 9px; border-radius: 20px;
}

/* Mini stats */
.dl-mini-stats {
  display: flex; gap: 0;
  border-top: 1px solid #f1f5f9;
  border-bottom: 1px solid #f1f5f9;
  margin-bottom: 14px; padding: 10px 0;
}
.dl-mini-stat {
  flex: 1; text-align: center;
  border-right: 1px solid #f1f5f9;
}
.dl-mini-stat:last-child { border-right: none; }
.dl-mini-stat .ms-val { font-size: .95rem; font-weight: 700; color: #1e293b; }
.dl-mini-stat .ms-lbl { font-size: .65rem; color: #94a3b8; margin-top: 1px; }

/* Card actions */
.dl-card-actions { display: flex; gap: 8px; margin-top: auto; }
.dl-btn-profile {
  flex: 1;
  background: #1f85de; color: #fff;
  border: none; border-radius: 7px;
  padding: 10px; font-size: .85rem; font-weight: 600;
  cursor: pointer; text-align: center; text-decoration: none;
  transition: background .2s;
  display: flex; align-items: center; justify-content: center; gap: 6px;
}
.dl-btn-profile:hover { background: #1565c0; color: #fff; text-decoration: none; }
.dl-btn-call {
  width: 40px; height: 40px;
  background: #f0fdf4; color: #16a34a;
  border: 1px solid #bbf7d0; border-radius: 7px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1rem; text-decoration: none;
  transition: all .2s; flex-shrink: 0;
}
.dl-btn-call:hover { background: #16a34a; color: #fff; border-color: #16a34a; }
.dl-btn-wa {
  width: 40px; height: 40px;
  background: #f0fdf4; color: #25d366;
  border: 1px solid #bbf7d0; border-radius: 7px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1rem; text-decoration: none;
  transition: all .2s; flex-shrink: 0;
}
.dl-btn-wa:hover { background: #25d366; color: #fff; border-color: #25d366; }

/* Empty state */
.dl-empty {
  text-align: center; padding: 80px 20px;
  background: #fff; border-radius: 12px;
  border: 2px dashed #e2e8f0;
}
.dl-empty i { font-size: 3.5rem; color: #cbd5e1; margin-bottom: 16px; display: block; }
.dl-empty h4 { font-size: 1.1rem; color: #64748b; margin: 0 0 6px; }
.dl-empty p  { font-size: .88rem; color: #94a3b8; }

/* Section title */
.dl-section-title {
  font-size: .8rem; font-weight: 700;
  text-transform: uppercase; letter-spacing: .8px;
  color: #64748b; margin-bottom: 16px;
  display: flex; align-items: center; gap: 8px;
}
.dl-section-title::after {
  content: ''; flex: 1; height: 1px; background: #e2e8f0;
}

/* Pagination handled by vendor/pagination/indianesthub.blade.php */

@media(max-width:768px) {
  .dl-banner h1 { font-size: 1.45rem; }
  .dl-banner .dl-stats-row { gap: 12px; }
}
</style>

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
