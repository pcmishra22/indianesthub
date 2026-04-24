<style>
/* ===== BUILDER PROFILE PAGE ===== */
.bp-page { background: #f1f5f9; min-height: 100vh; }

/* Hero */
.bp-hero {
  background: linear-gradient(135deg, #0a2d5e 0%, #0f4c81 50%, #1565c0 100%);
  padding: 40px 0 0; color: #fff; position: relative;
}
.bp-hero::after {
  content: ''; display: block; height: 40px; background: #f1f5f9;
  margin-top: -1px; clip-path: ellipse(55% 100% at 50% 100%);
}
.bp-logo-wrap { position: relative; display: inline-block; }
.bp-logo {
  width: 110px; height: 110px; border-radius: 16px; object-fit: cover;
  border: 4px solid rgba(255,255,255,.9); box-shadow: 0 4px 20px rgba(0,0,0,.25); background: #fff;
}
.bp-logo-fallback {
  width: 110px; height: 110px; border-radius: 16px;
  background: rgba(255,255,255,.2); border: 4px solid rgba(255,255,255,.9);
  display: flex; align-items: center; justify-content: center;
  font-size: 2.8rem; font-weight: 800; color: #fff;
}
.bp-verified-dot {
  position: absolute; bottom: -6px; right: -6px;
  width: 28px; height: 28px; border-radius: 50%;
  background: #22c55e; border: 2px solid #fff;
  display: flex; align-items: center; justify-content: center;
  font-size: .7rem; color: #fff;
}
.bp-builder-name   { font-size: 1.75rem; font-weight: 800; margin: 0 0 4px; }
.bp-builder-sub    { font-size: .92rem; opacity: .88; margin: 0 0 8px; }
.bp-builder-meta   { display: flex; gap: 16px; flex-wrap: wrap; font-size: .83rem; opacity: .9; margin-bottom: 18px; }
.bp-builder-meta span { display: flex; align-items: center; gap: 5px; }
.bp-hero-ctas      { display: flex; gap: 10px; flex-wrap: wrap; padding-bottom: 48px; }
.bp-hero-ctas .btn { border-radius: 8px; font-weight: 600; font-size: .88rem; padding: 10px 20px; display: flex; align-items: center; gap: 7px; }
.btn-wa-hero { background: #25d366; border-color: #25d366; color: #fff; }
.btn-wa-hero:hover { background: #1ebe5d; border-color: #1ebe5d; color: #fff; }

/* Stats row */
.bp-stats-row { display: flex; flex-wrap: wrap; gap: 0; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 24px; }
.bp-stat { flex: 1; min-width: 120px; text-align: center; padding: 20px 10px; border-right: 1px solid #f1f5f9; }
.bp-stat:last-child { border-right: none; }
.bp-stat .val { font-size: 1.6rem; font-weight: 800; color: #0078d4; }
.bp-stat .lbl { font-size: .72rem; color: #94a3b8; margin-top: 3px; text-transform: uppercase; letter-spacing: .5px; }

/* Section title */
.bp-section-title { font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
.bp-section-title i { color: #0078d4; }

/* Info card */
.bp-card { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 22px; margin-bottom: 20px; }

/* Project card */
.bp-proj-card {
  background: #fff; border-radius: 12px; border: 1px solid #e2e8f0;
  overflow: hidden; transition: box-shadow .22s, transform .22s; height: 100%;
}
.bp-proj-card:hover { box-shadow: 0 8px 24px rgba(0,120,212,.12); transform: translateY(-3px); }
.bp-proj-img {
  height: 180px; background: linear-gradient(135deg, #dbeafe, #bfdbfe); position: relative; overflow: hidden;
}
.bp-proj-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
.bp-proj-card:hover .bp-proj-img img { transform: scale(1.05); }
.bp-proj-img .bp-status {
  position: absolute; top: 10px; left: 10px; font-size: .68rem; font-weight: 700;
  padding: 3px 10px; border-radius: 4px; text-transform: uppercase;
}
.bp-proj-img .bp-units-pill {
  position: absolute; bottom: 10px; right: 10px; background: rgba(0,0,0,.65); color: #fff;
  font-size: .72rem; font-weight: 600; padding: 4px 10px; border-radius: 20px;
}
.bp-proj-body { padding: 16px; }
.bp-proj-title { font-size: .98rem; font-weight: 700; color: #1e293b; margin: 0 0 4px; }
.bp-proj-city  { font-size: .78rem; color: #64748b; margin: 0 0 10px; display: flex; align-items: center; gap: 4px; }
.bp-proj-price { font-size: .88rem; font-weight: 600; color: #0078d4; margin: 0 0 12px; }
.bp-proj-btn {
  display: block; text-align: center; background: #0078d4; color: #fff;
  border-radius: 7px; padding: 9px; font-size: .82rem; font-weight: 600;
  text-decoration: none; transition: background .2s;
}
.bp-proj-btn:hover { background: #0a2d5e; color: #fff; }

/* RERA badge */
.bp-rera-badge {
  display: inline-flex; align-items: center; gap: 5px;
  background: #f0fdf4; border: 1px solid #86efac;
  color: #16a34a; font-size: .75rem; font-weight: 600;
  padding: 4px 10px; border-radius: 6px;
}

@media(max-width:768px) {
  .bp-builder-name { font-size: 1.3rem; }
  .bp-stat .val { font-size: 1.2rem; }
}
</style>

<main class="bp-page">

  {{-- ===== HERO ===== --}}
  <div class="bp-hero">
    <div class="container">
      <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="background:none;padding:0;font-size:.82rem;">
          <li class="breadcrumb-item"><a href="/" style="color:rgba(255,255,255,.8);text-decoration:none;">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('builders.index') }}" style="color:rgba(255,255,255,.8);text-decoration:none;">Builders</a></li>
          <li class="breadcrumb-item active" style="color:#fff;">{{ $builder->company_name ?: $builder->name }}</li>
        </ol>
      </nav>

      <div class="row align-items-end gy-3">
        <div class="col-lg-8">
          <div class="d-flex align-items-start gap-4 mb-3">
            <div class="bp-logo-wrap">
              @if($builder->logo)
                <img src="{{ asset('storage/' . $builder->logo) }}" alt="{{ $builder->company_name }}" class="bp-logo">
              @else
                <div class="bp-logo-fallback">
                  {{ strtoupper(substr($builder->company_name ?: $builder->name, 0, 1)) }}
                </div>
              @endif
              @if($builder->is_verified)
                <div class="bp-verified-dot" title="Verified Builder"><i class="bi bi-check-lg"></i></div>
              @endif
            </div>
            <div>
              <h1 class="bp-builder-name">
                {{ $builder->company_name ?: $builder->name }}
                @if($builder->is_verified)
                  <i class="bi bi-patch-check-fill ms-2" style="font-size:1.2rem;color:#4ade80;"></i>
                @endif
              </h1>
              @if($builder->company_name && $builder->name && $builder->company_name !== $builder->name)
              <p class="bp-builder-sub"><i class="bi bi-person me-1"></i>{{ $builder->name }}</p>
              @endif
              <div class="bp-builder-meta">
                @if($builder->city)
                  <span><i class="bi bi-geo-alt-fill"></i> {{ $builder->city }}</span>
                @endif
                @if($builder->established_year)
                  <span><i class="bi bi-calendar3"></i> Est. {{ $builder->established_year }}</span>
                @endif
                @if($builder->rera_registration)
                  <span><i class="bi bi-file-earmark-check"></i> RERA Registered</span>
                @endif
                @if($builder->website)
                  <span><i class="bi bi-globe2"></i> <a href="{{ $builder->website }}" target="_blank" style="color:rgba(255,255,255,.9);">Website</a></span>
                @endif
              </div>
            </div>
          </div>
          <div class="bp-hero-ctas">
            @if($builder->phone)
            <a href="tel:{{ $builder->phone }}" class="btn btn-light">
              <i class="bi bi-telephone-fill"></i> {{ $builder->phone }}
            </a>
            <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $builder->phone) }}" target="_blank" class="btn btn-wa-hero">
              <i class="bi bi-whatsapp"></i> WhatsApp
            </a>
            @endif
            @if($builder->email)
            <a href="mailto:{{ $builder->email }}" class="btn btn-outline-light">
              <i class="bi bi-envelope"></i> Email
            </a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container" style="margin-top:-10px;">

    {{-- ===== STATS ROW ===== --}}
    <div class="bp-stats-row">
      <div class="bp-stat">
        <div class="val">{{ $builder->projects_count }}</div>
        <div class="lbl">Total Projects</div>
      </div>
      <div class="bp-stat">
        <div class="val">{{ $builder->total_delivered_projects ?? 0 }}</div>
        <div class="lbl">Delivered</div>
      </div>
      <div class="bp-stat">
        <div class="val">{{ number_format($totalUnits ?? 0) }}</div>
        <div class="lbl">Total Units</div>
      </div>
      <div class="bp-stat">
        <div class="val">{{ $citiesServed->count() }}</div>
        <div class="lbl">Cities</div>
      </div>
      @if($builder->rating > 0)
      <div class="bp-stat">
        <div class="val">{{ number_format($builder->rating, 1) }}★</div>
        <div class="lbl">Rating</div>
      </div>
      @endif
    </div>

    <div class="row gy-4">

      {{-- ===== LEFT COLUMN ===== --}}
      <div class="col-lg-8">

        {{-- About --}}
        @if($builder->description)
        <div class="bp-card mb-4">
          <div class="bp-section-title"><i class="bi bi-info-circle-fill"></i> About {{ $builder->company_name ?: $builder->name }}</div>
          <p style="color:#475569;line-height:1.8;margin:0;">{{ $builder->description }}</p>
        </div>
        @endif

        {{-- Projects Grid --}}
        <div class="bp-section-title"><i class="bi bi-diagram-3-fill"></i> Projects ({{ $projects->total() }})</div>

        @if($projects->count())
        <div class="row g-3 mb-3">
          @foreach($projects as $project)
          <div class="col-md-6">
            <div class="bp-proj-card">
              <div class="bp-proj-img">
                @if($project->cover_image)
                  <img src="{{ asset('storage/' . $project->cover_image) }}" alt="{{ $project->title }}">
                @else
                  <div style="height:100%;display:flex;align-items:center;justify-content:center;font-size:2.5rem;color:#0078d4;">
                    <i class="bi bi-buildings-fill"></i>
                  </div>
                @endif
                {{-- Status badge --}}
                @php
                  $statusColors = [
                    'Upcoming' => 'background:#3b82f6;color:#fff;',
                    'Under Construction' => 'background:#f59e0b;color:#fff;',
                    'Ready to Move' => 'background:#22c55e;color:#fff;',
                    'Completed' => 'background:#6366f1;color:#fff;',
                  ];
                  $sc = $statusColors[$project->status] ?? 'background:#64748b;color:#fff;';
                @endphp
                <span class="bp-status" style="{{ $sc }}">{{ $project->status }}</span>
                <span class="bp-units-pill"><i class="bi bi-grid-3x3-gap"></i> {{ $project->total_units ?? 0 }} Units</span>
              </div>
              <div class="bp-proj-body">
                <h5 class="bp-proj-title">{{ $project->title }}</h5>
                @if($project->city)
                <p class="bp-proj-city"><i class="bi bi-geo-alt"></i> {{ $project->city }}{{ $project->state ? ', ' . $project->state : '' }}</p>
                @endif
                @if($project->price_from || $project->price_to)
                <p class="bp-proj-price">
                  ₹{{ $project->price_from ? number_format($project->price_from / 100000, 1) . 'L' : '' }}
                  @if($project->price_from && $project->price_to) – @endif
                  {{ $project->price_to ? '₹' . number_format($project->price_to / 10000000, 2) . 'Cr' : '' }}
                </p>
                @endif
                <a href="{{ route('projects.show', $project) }}" class="bp-proj-btn">
                  <i class="bi bi-eye me-1"></i> View Project
                </a>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        {{ $projects->links('vendor.pagination.indianesthub') }}
        @else
        <div style="text-align:center;padding:40px;background:#fff;border-radius:12px;border:2px dashed #e2e8f0;">
          <i class="bi bi-buildings" style="font-size:2.5rem;color:#cbd5e1;display:block;margin-bottom:12px;"></i>
          <p style="color:#64748b;margin:0;">No projects listed yet.</p>
        </div>
        @endif

      </div>

      {{-- ===== RIGHT SIDEBAR ===== --}}
      <div class="col-lg-4">

        {{-- Quick Contact --}}
        <div class="bp-card mb-4">
          <div class="bp-section-title"><i class="bi bi-chat-dots-fill"></i> Quick Contact</div>
          @if($builder->phone)
          <a href="tel:{{ $builder->phone }}" class="btn w-100 mb-2"
             style="background:#0078d4;color:#fff;border-radius:8px;font-weight:600;">
            <i class="bi bi-telephone-fill me-2"></i>{{ $builder->phone }}
          </a>
          <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $builder->phone) }}" target="_blank"
             class="btn w-100 mb-2" style="background:#25d366;color:#fff;border-radius:8px;font-weight:600;">
            <i class="bi bi-whatsapp me-2"></i>WhatsApp
          </a>
          @endif
          @if($builder->email)
          <a href="mailto:{{ $builder->email }}" class="btn w-100 btn-outline-secondary mb-2" style="border-radius:8px;">
            <i class="bi bi-envelope me-2"></i>{{ $builder->email }}
          </a>
          @endif
          @if($builder->website)
          <a href="{{ $builder->website }}" target="_blank" class="btn w-100 btn-outline-secondary" style="border-radius:8px;">
            <i class="bi bi-globe2 me-2"></i>Visit Website
          </a>
          @endif
        </div>

        {{-- RERA & Compliance --}}
        @if($builder->rera_registration)
        <div class="bp-card mb-4">
          <div class="bp-section-title"><i class="bi bi-shield-check-fill"></i> Compliance</div>
          <div class="bp-rera-badge mb-2">
            <i class="bi bi-file-earmark-check-fill"></i> RERA Registered
          </div>
          <p style="font-size:.8rem;color:#64748b;margin:8px 0 0;">
            Reg. No: <strong>{{ $builder->rera_registration }}</strong>
          </p>
        </div>
        @endif

        {{-- Cities Operating --}}
        @if($citiesServed->count())
        <div class="bp-card mb-4">
          <div class="bp-section-title"><i class="bi bi-map-fill"></i> Cities Operating</div>
          <div class="d-flex flex-wrap gap-2">
            @foreach($citiesServed as $city)
            <span style="background:#eff6ff;border:1px solid #bfdbfe;color:#1d4ed8;font-size:.75rem;font-weight:500;padding:4px 10px;border-radius:20px;">
              <i class="bi bi-geo-alt me-1"></i>{{ $city }}
            </span>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Overview --}}
        <div class="bp-card">
          <div class="bp-section-title"><i class="bi bi-info-circle-fill"></i> Overview</div>
          <table style="width:100%;font-size:.84rem;color:#475569;border-collapse:collapse;">
            @if($builder->established_year)
            <tr>
              <td style="padding:6px 0;color:#94a3b8;">Established</td>
              <td style="padding:6px 0;font-weight:600;color:#1e293b;">{{ $builder->established_year }}</td>
            </tr>
            @endif
            <tr>
              <td style="padding:6px 0;color:#94a3b8;">Total Projects</td>
              <td style="padding:6px 0;font-weight:600;color:#1e293b;">{{ $builder->projects_count }}</td>
            </tr>
            @if($builder->total_delivered_projects)
            <tr>
              <td style="padding:6px 0;color:#94a3b8;">Delivered</td>
              <td style="padding:6px 0;font-weight:600;color:#1e293b;">{{ $builder->total_delivered_projects }}</td>
            </tr>
            @endif
            @if($builder->rating > 0)
            <tr>
              <td style="padding:6px 0;color:#94a3b8;">Rating</td>
              <td style="padding:6px 0;font-weight:600;color:#1e293b;">{{ number_format($builder->rating, 1) }} / 5 ★</td>
            </tr>
            @endif
          </table>
        </div>

      </div>
    </div>
  </div>

  <div style="height:48px;"></div>
</main>
