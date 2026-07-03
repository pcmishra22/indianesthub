<style>
/* ===== PROJECT DETAIL PAGE ===== */
.pd-page { background: #f1f5f9; min-height: 100vh; }

/* Hero banner */
.pd-hero {
  background: linear-gradient(135deg, #0a2d5e 0%, #0f4c81 60%, #1565c0 100%);
  padding: 40px 0; color: #fff;
}
.pd-status-badge {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: .72rem; font-weight: 700; padding: 4px 12px;
  border-radius: 20px; text-transform: uppercase; letter-spacing: .4px; margin-bottom: 10px;
}
.pd-title { font-size: 1.9rem; font-weight: 800; margin: 0 0 8px; line-height: 1.2; }
.pd-meta  { display: flex; flex-wrap: wrap; gap: 16px; font-size: .85rem; opacity: .9; margin-bottom: 18px; }
.pd-meta span { display: flex; align-items: center; gap: 5px; }
.pd-hero-stats {
  display: flex; flex-wrap: wrap; gap: 0;
  background: rgba(255,255,255,.12); border-radius: 10px; overflow: hidden;
  border: 1px solid rgba(255,255,255,.2); width: fit-content;
}
.pd-hero-stat { text-align: center; padding: 14px 22px; border-right: 1px solid rgba(255,255,255,.15); }
.pd-hero-stat:last-child { border-right: none; }
.pd-hero-stat .val { font-size: 1.3rem; font-weight: 800; line-height: 1; }
.pd-hero-stat .lbl { font-size: .68rem; opacity: .8; margin-top: 3px; }

/* Section title */
.pd-section-title { font-size: 1.05rem; font-weight: 700; color: #1e293b; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; padding-bottom: 8px; border-bottom: 2px solid #bfdbfe; }
.pd-section-title i { color: #0078d4; }

/* Card */
.pd-card { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 20px; }

/* Gallery */
.pd-gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 8px; }
.pd-gallery-item {
  border-radius: 8px; overflow: hidden; aspect-ratio: 4/3; cursor: pointer;
  transition: transform .2s, box-shadow .2s;
}
.pd-gallery-item:hover { transform: scale(1.03); box-shadow: 0 4px 16px rgba(0,0,0,.15); }
.pd-gallery-item img { width: 100%; height: 100%; object-fit: cover; }

/* Cover image */
.pd-cover {
  width: 100%; border-radius: 12px; overflow: hidden;
  height: 380px; background: linear-gradient(135deg, #dbeafe, #bfdbfe);
  display: flex; align-items: center; justify-content: center; margin-bottom: 20px;
}
.pd-cover img { width: 100%; height: 100%; object-fit: cover; }
.pd-cover .pd-cover-fallback { font-size: 5rem; color: #0078d4; opacity: .4; }

/* Amenities */
.pd-amenity-chip {
  display: inline-flex; align-items: center; gap: 6px;
  background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8;
  font-size: .78rem; font-weight: 500; padding: 5px 12px;
  border-radius: 20px; margin: 3px;
}
.pd-amenity-chip i { font-size: .9rem; }
.pd-cat-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #94a3b8; margin: 12px 0 6px; }

/* Unit table */
.pd-unit-table { width: 100%; border-collapse: collapse; font-size: .84rem; }
.pd-unit-table thead th { background: #f8fafc; padding: 10px 14px; text-align: left; font-size: .72rem; text-transform: uppercase; letter-spacing: .5px; color: #64748b; border-bottom: 2px solid #e2e8f0; white-space: nowrap; }
.pd-unit-table tbody td { padding: 12px 14px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
.pd-unit-table tbody tr:last-child td { border-bottom: none; }
.pd-unit-table tbody tr:hover td { background: #eff6ff; }

/* Lead form */
.pd-lead-form { background: linear-gradient(135deg, #0078d4 0%, #1565c0 100%); border-radius: 12px; padding: 24px; color: #fff; position: sticky; top: 80px; }
.pd-lead-form h5 { font-size: 1.05rem; font-weight: 700; margin-bottom: 4px; }
.pd-lead-form p  { font-size: .82rem; opacity: .85; margin-bottom: 16px; }
.pd-lead-form .form-control, .pd-lead-form .form-select {
  background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3);
  color: #fff; border-radius: 8px; padding: 10px 14px; font-size: .85rem;
  margin-bottom: 10px;
}
.pd-lead-form .form-control::placeholder { color: rgba(255,255,255,.6); }
.pd-lead-form .form-control:focus, .pd-lead-form .form-select:focus {
  background: rgba(255,255,255,.2); border-color: rgba(255,255,255,.6);
  color: #fff; box-shadow: none;
}
.pd-lead-form .form-select option { background: #1565c0; color: #fff; }
.pd-lead-form label { font-size: .78rem; opacity: .85; margin-bottom: 3px; }
.pd-lead-btn {
  width: 100%; background: #fff; color: #0078d4; border: none;
  border-radius: 8px; padding: 12px; font-size: .9rem; font-weight: 700;
  cursor: pointer; transition: all .2s; margin-top: 4px;
}
.pd-lead-btn:hover { background: #eff6ff; transform: translateY(-1px); }
.pd-lead-tabs { display: flex; gap: 6px; margin-bottom: 14px; flex-wrap: wrap; }
.pd-lead-tab {
  flex: 1; min-width: 70px; text-align: center; padding: 7px 6px;
  background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
  border-radius: 7px; cursor: pointer; font-size: .72rem; font-weight: 600;
  transition: all .2s; color: rgba(255,255,255,.85);
}
.pd-lead-tab.active, .pd-lead-tab:hover { background: rgba(255,255,255,.3); color: #fff; }
.pd-lead-tab input[type="radio"] { display: none; }

/* Location info */
.pd-location-item { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 12px; }
.pd-location-item i { color: #0078d4; font-size: 1.1rem; flex-shrink: 0; margin-top: 2px; }
.pd-location-item .li-label { font-size: .72rem; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; }
.pd-location-item .li-val   { font-size: .88rem; color: #334155; font-weight: 500; }

/* Related projects */
.pd-related-card {
  background: #fff; border-radius: 10px; border: 1px solid #e2e8f0;
  padding: 14px; display: flex; gap: 12px; align-items: center;
  transition: box-shadow .2s; text-decoration: none;
}
.pd-related-card:hover { box-shadow: 0 4px 16px rgba(0,120,212,.1); text-decoration: none; }
.pd-related-card .thumb {
  width: 70px; height: 70px; border-radius: 8px; flex-shrink: 0; overflow: hidden;
  background: linear-gradient(135deg, #dbeafe, #bfdbfe);
  display: flex; align-items: center; justify-content: center;
}
.pd-related-card .thumb img { width: 100%; height: 100%; object-fit: cover; }

/* Lightbox overlay */
#pd-lightbox {
  display: none; position: fixed; inset: 0; background: rgba(0,0,0,.92);
  z-index: 9999; align-items: center; justify-content: center;
}
#pd-lightbox.open { display: flex; }
#pd-lightbox img { max-width: 90vw; max-height: 90vh; border-radius: 8px; }
#pd-lightbox-close {
  position: absolute; top: 20px; right: 24px;
  color: #fff; font-size: 2rem; cursor: pointer; line-height: 1;
}

@media(max-width:768px) {
  .pd-title { font-size: 1.4rem; }
  .pd-hero-stats { width: 100%; }
  .pd-cover { height: 220px; }
}
/* ===== TIMELINE ===== */
.pd-timeline-track { display:flex; align-items:flex-start; gap:0; overflow-x:auto; padding:4px 0; }
.pd-tl-item { display:flex; flex-direction:column; align-items:center; flex-shrink:0; width:90px; text-align:center; }
.pd-tl-dot { width:34px; height:34px; border-radius:50%; border:2px solid; display:flex; align-items:center; justify-content:center; margin-bottom:6px; }
.pd-tl-item.done .pd-tl-label { font-weight:700; color:#1e293b; }
.pd-tl-label { font-size:.68rem; color:#94a3b8; line-height:1.2; }
.pd-tl-line { flex:1; height:2px; background:#e2e8f0; margin-top:17px; min-width:20px; }
.pd-tl-line.done { background:#22c55e; }

/* ===== TRUST SEALS ===== */
.pd-trust-seals-card { background:linear-gradient(135deg,#f0fdf4 0%,#fff 70%); }
.pd-trust-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.pd-trust-item { display:flex; align-items:flex-start; gap:9px; padding:10px; background:rgba(255,255,255,.8); border-radius:8px; border:1px solid #e2e8f0; }
.pd-ti-icon { width:32px; height:32px; border-radius:8px; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:.85rem; }
.pd-ti-label { font-size:.72rem; font-weight:700; color:#334155; }
.pd-ti-val   { font-size:.68rem; color:#64748b; margin-top:1px; }

/* ===== STICKY PROJECT BAR ===== */
#proj-sticky-bar {
  position:fixed; bottom:0; left:0; right:0; z-index:1050;
  background:linear-gradient(90deg,#0a2d5e 0%,#0078d4 100%);
  color:#fff; padding:10px 0;
  transform:translateY(110%); transition:transform .35s cubic-bezier(.4,0,.2,1);
}
#proj-sticky-bar .psb-inner { display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; }
#proj-sticky-bar .psb-title { font-size:.88rem; font-weight:700; opacity:.95; }
#proj-sticky-bar .psb-price { font-size:1.1rem; font-weight:800; }
#proj-sticky-bar .psb-units { font-size:.72rem; opacity:.8; }
@media(max-width:576px) { .pd-trust-grid { grid-template-columns:1fr; } }
</style>

{{-- Lightbox --}}
<div id="pd-lightbox">
  <span id="pd-lightbox-close">&times;</span>
  <img id="pd-lightbox-img" src="" alt="Gallery">
</div>

<main class="pd-page">

  {{-- ===== HERO ===== --}}
  <div class="pd-hero">
    <div class="container">
      <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="background:none;padding:0;font-size:.82rem;">
          <li class="breadcrumb-item"><a href="/" style="color:rgba(255,255,255,.7);text-decoration:none;">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('builders.index') }}" style="color:rgba(255,255,255,.7);text-decoration:none;">Builders</a></li>
          <li class="breadcrumb-item"><a href="{{ route('builders.show', $project->builder) }}" style="color:rgba(255,255,255,.7);text-decoration:none;">{{ $project->builder->company_name ?: $project->builder->name }}</a></li>
          <li class="breadcrumb-item active" style="color:#fff;">{{ $project->title }}</li>
        </ol>
      </nav>

      @php
        $statusColors = [
          'Upcoming'           => 'background:#3b82f6;color:#fff;',
          'Under Construction' => 'background:#f59e0b;color:#1f2937;',
          'Ready to Move'      => 'background:#22c55e;color:#fff;',
          'Completed'          => 'background:#6366f1;color:#fff;',
        ];
        $sc = $statusColors[$project->status] ?? 'background:#64748b;color:#fff;';
      @endphp
      <span class="pd-status-badge" style="{{ $sc }}">
        <i class="bi bi-circle-fill" style="font-size:.4rem;"></i> {{ $project->status }}
      </span>

      <h1 class="pd-title">{{ $project->title }}</h1>

      <div class="pd-meta">
        <span><i class="bi bi-geo-alt-fill"></i>
          {{ collect([$project->city, $project->state])->filter()->implode(', ') ?: 'Location TBD' }}
        </span>
        <span><i class="bi bi-building"></i> {{ $project->project_type }}</span>
        @if($project->rera_id)
          <span><i class="bi bi-file-earmark-check-fill"></i> RERA: {{ $project->rera_id }}</span>
        @endif
        @if($project->builder->is_verified)
          <span><i class="bi bi-patch-check-fill" style="color:#4ade80;"></i> Verified Builder</span>
        @endif
      </div>

      <div class="pd-hero-stats">
        <div class="pd-hero-stat">
          <div class="val">{{ $project->total_units ?? '—' }}</div>
          <div class="lbl">Total Units</div>
        </div>
        <div class="pd-hero-stat">
          <div class="val">{{ $project->available_units ?? '—' }}</div>
          <div class="lbl">Available</div>
        </div>
        @if($minPrice)
        <div class="pd-hero-stat">
          <div class="val">
            ₹{{ $minPrice >= 10000000 ? number_format($minPrice / 10000000, 2) . 'Cr' : number_format($minPrice / 100000, 1) . 'L' }}
            @if($maxPrice && $maxPrice != $minPrice)
              – ₹{{ $maxPrice >= 10000000 ? number_format($maxPrice / 10000000, 2) . 'Cr' : number_format($maxPrice / 100000, 1) . 'L' }}
            @endif
          </div>
          <div class="lbl">Price Range</div>
        </div>
        @endif
        @if($project->possession_date)
        <div class="pd-hero-stat">
          <div class="val">{{ \Carbon\Carbon::parse($project->possession_date)->format('M Y') }}</div>
          <div class="lbl">Possession</div>
        </div>
        @endif
        @if($project->total_towers)
        <div class="pd-hero-stat">
          <div class="val">{{ $project->total_towers }}</div>
          <div class="lbl">Towers</div>
        </div>
        @endif
      </div>
    </div>
  </div>

  <div class="container py-4">
    <div class="row gy-4">

      {{-- ===== LEFT / MAIN CONTENT ===== --}}
      <div class="col-lg-8">

        {{-- Cover Image --}}
        <div class="pd-cover mb-3">
          @if($project->cover_image)
            <img src="{{ asset('storage/' . $project->cover_image) }}" alt="{{ $project->title }}"
                 style="cursor:zoom-in;" onclick="openLightbox('{{ asset('storage/' . $project->cover_image) }}')">
          @else
            <div class="pd-cover-fallback"><i class="bi bi-buildings-fill"></i></div>
          @endif
        </div>

        {{-- Quick action buttons --}}
        <div class="d-flex flex-wrap gap-2 mb-4">
          @if($project->brochure)
          <a href="{{ asset('storage/' . $project->brochure) }}" target="_blank"
             class="btn btn-sm" style="background:#eff6ff;color:#0078d4;border:1px solid #bfdbfe;border-radius:7px;font-size:.82rem;font-weight:600;">
            <i class="bi bi-file-earmark-pdf me-1"></i> Download Brochure
          </a>
          @endif
          @if($project->virtual_tour_url)
          <a href="{{ $project->virtual_tour_url }}" target="_blank"
             class="btn btn-sm" style="background:#eff6ff;color:#0078d4;border:1px solid #bfdbfe;border-radius:7px;font-size:.82rem;font-weight:600;">
            <i class="bi bi-badge-vr me-1"></i> Virtual Tour
          </a>
          @endif
          @if($project->video_url)
          <a href="{{ $project->video_url }}" target="_blank"
             class="btn btn-sm" style="background:#eff6ff;color:#0078d4;border:1px solid #bfdbfe;border-radius:7px;font-size:.82rem;font-weight:600;">
            <i class="bi bi-play-circle me-1"></i> Watch Video
          </a>
          @endif
        </div>

        {{-- Description --}}
        @if($project->description)
        <div class="pd-card">
          <div class="pd-section-title"><i class="bi bi-file-text-fill"></i> About This Project</div>
          <p style="color:#475569;line-height:1.85;margin:0;">{{ $project->description }}</p>
        </div>
        @endif

        {{-- Available Units --}}
        @if($project->properties->count())
        <div class="pd-card">
          <div class="pd-section-title"><i class="bi bi-grid-3x3-gap-fill"></i> Available Units ({{ $project->properties->count() }})</div>
          <div class="table-responsive">
            <table class="pd-unit-table">
              <thead>
                <tr>
                  <th>Unit Name</th>
                  <th>Type</th>
                  <th>BHK</th>
                  <th>Area</th>
                  <th>Price</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach($project->properties as $unit)
                <tr>
                  <td style="font-weight:600;color:#1e293b;">{{ $unit->title }}</td>
                  <td>{{ $unit->property_type ?? '—' }}</td>
                  <td>{{ $unit->bhk_type ?? '—' }}</td>
                  <td>{{ $unit->area ? number_format($unit->area) . ' sq.ft' : '—' }}</td>
                  <td style="font-weight:600;color:#0078d4;">
                    {{ $unit->price ? '₹' . ($unit->price >= 10000000 ? number_format($unit->price / 10000000, 2) . 'Cr' : number_format($unit->price / 100000, 1) . 'L') : '—' }}
                  </td>
                  <td>
                    @php
                      $us = match(strtolower($unit->status ?? 'available')) {
                        'booked'  => ['Booked',    '#fef3c7', '#92400e'],
                        'sold'    => ['Sold',      '#fce7f3', '#9d174d'],
                        default   => ['Available', '#f0fdf4', '#166534'],
                      };
                    @endphp
                    <span style="background:{{ $us[1] }};color:{{ $us[2] }};font-size:.72rem;font-weight:600;padding:3px 9px;border-radius:4px;">
                      {{ $us[0] }}
                    </span>
                  </td>
                  <td>
                    <button type="button" class="btn btn-sm" style="background:#0078d4;color:#fff;border-radius:6px;font-size:.72rem;padding:4px 10px;"
                      onclick="document.getElementById('lead-form-section').scrollIntoView({behavior:'smooth'})">
                      Enquire
                    </button>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        @endif

        {{-- Amenities --}}
        @if($amenitiesByCategory->count())
        <div class="pd-card">
          <div class="pd-section-title"><i class="bi bi-star-fill"></i> Amenities</div>
          @foreach($amenitiesByCategory as $category => $items)
          <div class="pd-cat-label">{{ $category }}</div>
          <div class="mb-2">
            @foreach($items as $amenity)
            <span class="pd-amenity-chip">
              <i class="{{ $amenity->icon ?? 'bi bi-check-circle' }}"></i>
              {{ $amenity->name }}
            </span>
            @endforeach
          </div>
          @endforeach
        </div>
        @endif

        {{-- Master Plan --}}
        @if($project->master_plan)
        <div class="pd-card">
          <div class="pd-section-title"><i class="bi bi-map-fill"></i> Master Plan</div>
          <img src="{{ asset('storage/' . $project->master_plan) }}" alt="Master Plan"
               class="img-fluid rounded" style="cursor:zoom-in;max-height:400px;width:100%;object-fit:contain;"
               onclick="openLightbox('{{ asset('storage/' . $project->master_plan) }}')">
        </div>
        @endif

        {{-- Gallery --}}
        @if($project->gallery_images && count($project->gallery_images))
        <div class="pd-card">
          <div class="pd-section-title"><i class="bi bi-images"></i> Gallery ({{ count($project->gallery_images) }} Photos)</div>
          <div class="pd-gallery-grid">
            @foreach($project->gallery_images as $img)
            <div class="pd-gallery-item" onclick="openLightbox('{{ asset('storage/' . $img) }}')">
              <img src="{{ asset('storage/' . $img) }}" alt="Gallery photo" loading="lazy">
            </div>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Location Intelligence --}}
        @if($project->nearby_schools || $project->nearby_hospitals || $project->metro_distance || $project->future_infra)
        <div class="pd-card">
          <div class="pd-section-title"><i class="bi bi-pin-map-fill"></i> Location & Connectivity</div>
          <div class="row gy-3">
            @if($project->metro_distance)
            <div class="col-md-6">
              <div class="pd-location-item">
                <i class="bi bi-train-front-fill"></i>
                <div><div class="li-label">Metro Distance</div><div class="li-val">{{ $project->metro_distance }}</div></div>
              </div>
            </div>
            @endif
            @if($project->connectivity_score)
            <div class="col-md-6">
              <div class="pd-location-item">
                <i class="bi bi-bar-chart-fill"></i>
                <div><div class="li-label">Connectivity Score</div><div class="li-val">{{ $project->connectivity_score }}/10</div></div>
              </div>
            </div>
            @endif
            @if($project->nearby_schools)
            <div class="col-12">
              <div class="pd-location-item">
                <i class="bi bi-mortarboard-fill"></i>
                <div><div class="li-label">Nearby Schools</div><div class="li-val">{{ $project->nearby_schools }}</div></div>
              </div>
            </div>
            @endif
            @if($project->nearby_hospitals)
            <div class="col-12">
              <div class="pd-location-item">
                <i class="bi bi-hospital-fill"></i>
                <div><div class="li-label">Nearby Hospitals</div><div class="li-val">{{ $project->nearby_hospitals }}</div></div>
              </div>
            </div>
            @endif
            @if($project->future_infra)
            <div class="col-12">
              <div class="pd-location-item">
                <i class="bi bi-building-up"></i>
                <div><div class="li-label">Future Infrastructure</div><div class="li-val">{{ $project->future_infra }}</div></div>
              </div>
            </div>
            @endif
            @if($project->latitude && $project->longitude)
            <div class="col-12">
              <div style="border-radius:10px;overflow:hidden;border:1px solid #e2e8f0;">
                <iframe
                  src="https://maps.google.com/maps?q={{ $project->latitude }},{{ $project->longitude }}&z=15&output=embed"
                  width="100%" height="260" style="border:0;display:block;" loading="lazy"></iframe>
              </div>
            </div>
            @endif
          </div>
        </div>
        @endif

        {{-- ===== POSSESSION TIMELINE ===== --}}
        @if($project->possession_date || $project->status)
        <div class="pd-card">
          <div class="pd-section-title"><i class="bi bi-calendar2-range-fill"></i> Project Timeline</div>
          <div class="pd-timeline">
            @php
              $milestones = [
                ['label' => 'Project Launched',      'icon' => 'bi-flag-fill',            'done' => true,  'color' => '#22c55e'],
                ['label' => 'Construction Started',  'icon' => 'bi-building-fill',         'done' => in_array($project->status, ['Under Construction', 'Ready to Move', 'Completed']), 'color' => '#3b82f6'],
                ['label' => 'Structure Complete',    'icon' => 'bi-buildings-fill',        'done' => in_array($project->status, ['Ready to Move', 'Completed']), 'color' => '#8b5cf6'],
                ['label' => 'Ready to Move',         'icon' => 'bi-key-fill',              'done' => in_array($project->status, ['Ready to Move', 'Completed']), 'color' => '#f59e0b'],
                ['label' => $project->possession_date ? 'Possession: ' . \Carbon\Carbon::parse($project->possession_date)->format('M Y') : 'Possession', 'icon' => 'bi-house-check-fill', 'done' => $project->status === 'Completed', 'color' => '#0078d4'],
              ];
            @endphp
            <div class="pd-timeline-track">
              @foreach($milestones as $i => $ms)
              <div class="pd-tl-item {{ $ms['done'] ? 'done' : '' }}">
                <div class="pd-tl-dot" style="background:{{ $ms['done'] ? $ms['color'] : '#e2e8f0' }};border-color:{{ $ms['done'] ? $ms['color'] : '#cbd5e1' }};">
                  <i class="bi {{ $ms['icon'] }}" style="font-size:.65rem;color:{{ $ms['done'] ? '#fff' : '#94a3b8' }};"></i>
                </div>
                <div class="pd-tl-label">{{ $ms['label'] }}</div>
              </div>
              @if($i < count($milestones)-1)
              <div class="pd-tl-line {{ ($milestones[$i+1]['done'] ?? false) ? 'done' : '' }}"></div>
              @endif
              @endforeach
            </div>
          </div>
        </div>
        @endif

        {{-- ===== TRUST SEALS ===== --}}
        <div class="pd-card pd-trust-seals-card">
          <div class="pd-section-title"><i class="bi bi-shield-fill-check" style="color:#22c55e;"></i> Why Trust This Project</div>
          <div class="pd-trust-grid">
            @if($project->rera_id)
            <div class="pd-trust-item">
              <div class="pd-ti-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-patch-check-fill"></i></div>
              <div>
                <div class="pd-ti-label">RERA Registered</div>
                <div class="pd-ti-val">{{ $project->rera_id }}</div>
              </div>
            </div>
            @endif
            @if($project->builder->is_verified)
            <div class="pd-trust-item">
              <div class="pd-ti-icon" style="background:#eff6ff;color:#1d4ed8;"><i class="bi bi-building-check"></i></div>
              <div>
                <div class="pd-ti-label">Verified Builder</div>
                <div class="pd-ti-val">{{ $project->builder->company_name ?: $project->builder->name }}</div>
              </div>
            </div>
            @endif
            @if($project->total_units)
            <div class="pd-trust-item">
              <div class="pd-ti-icon" style="background:#fff7ed;color:#c2410c;"><i class="bi bi-people-fill"></i></div>
              <div>
                <div class="pd-ti-label">Total Units</div>
                <div class="pd-ti-val">{{ number_format($project->total_units) }} homes planned</div>
              </div>
            </div>
            @endif
            @if($project->builder->total_delivered_projects)
            <div class="pd-trust-item">
              <div class="pd-ti-icon" style="background:#ecfdf5;color:#059669;"><i class="bi bi-trophy-fill"></i></div>
              <div>
                <div class="pd-ti-label">Track Record</div>
                <div class="pd-ti-val">{{ $project->builder->total_delivered_projects }} projects delivered</div>
              </div>
            </div>
            @endif
            <div class="pd-trust-item">
              <div class="pd-ti-icon" style="background:#f5f3ff;color:#7c3aed;"><i class="bi bi-headset"></i></div>
              <div>
                <div class="pd-ti-label">Free Consultation</div>
                <div class="pd-ti-val">No brokerage · No hidden charges</div>
              </div>
            </div>
            <div class="pd-trust-item">
              <div class="pd-ti-icon" style="background:#fff1f2;color:#e11d48;"><i class="bi bi-bank2"></i></div>
              <div>
                <div class="pd-ti-label">Home Loan Available</div>
                <div class="pd-ti-val">All major banks supported</div>
              </div>
            </div>
          </div>
        </div>

        {{-- Related Projects --}}
        @if($relatedProjects->count())
        <div class="pd-card">
          <div class="pd-section-title"><i class="bi bi-diagram-3-fill"></i> More Projects by {{ $project->builder->company_name ?: $project->builder->name }}</div>
          <div class="d-flex flex-column gap-2">
            @foreach($relatedProjects as $rp)
            <a href="{{ route('projects.show', $rp) }}" class="pd-related-card">
              <div class="thumb">
                @if($rp->cover_image)
                  <img src="{{ asset('storage/' . $rp->cover_image) }}" alt="{{ $rp->title }}">
                @else
                  <i class="bi bi-buildings-fill" style="font-size:1.8rem;color:#0078d4;opacity:.5;"></i>
                @endif
              </div>
              <div>
                <div style="font-size:.9rem;font-weight:700;color:#1e293b;">{{ $rp->title }}</div>
                <div style="font-size:.78rem;color:#64748b;margin-top:2px;">
                  {{ $rp->city }} · {{ $rp->status }}
                </div>
                <div style="font-size:.78rem;color:#0078d4;margin-top:2px;">
                  {{ $rp->properties_count }} units listed
                </div>
              </div>
              <i class="bi bi-chevron-right ms-auto" style="color:#cbd5e1;"></i>
            </a>
            @endforeach
          </div>
        </div>
        @endif

      </div>

      {{-- ===== RIGHT SIDEBAR ===== --}}
      <div class="col-lg-4">

        {{-- Lead capture form --}}
        <div id="lead-form-section">
          <div class="pd-lead-form">
            <h5><i class="bi bi-chat-dots-fill me-2"></i>Interested? Get in Touch</h5>
            <p>Free consultation — No brokerage</p>

            {{-- Success message --}}
            @if(session('success'))
            <div style="background:rgba(255,255,255,.2);border-radius:8px;padding:12px;margin-bottom:12px;font-size:.85rem;">
              <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
            @endif

            {{-- Errors --}}
            @if($errors->any())
            <div style="background:rgba(255,80,80,.25);border-radius:8px;padding:12px;margin-bottom:12px;font-size:.82rem;">
              @foreach($errors->all() as $error)<div>⚠ {{ $error }}</div>@endforeach
            </div>
            @endif

            <form id="leadForm" action="{{ route('projects.lead', $project) }}" method="POST">
              @csrf
              <input type="hidden" name="lead_type" id="lead_type_input" value="general">

              {{-- Lead type tabs --}}
              <div class="pd-lead-tabs">
                <label class="pd-lead-tab active" onclick="setLeadType('general', this)" title="General enquiry">
                  <i class="bi bi-chat-dots d-block mb-1" style="font-size:1.1rem;"></i>Enquire
                </label>
                <label class="pd-lead-tab" onclick="setLeadType('visit', this)" title="Schedule site visit">
                  <i class="bi bi-calendar-check d-block mb-1" style="font-size:1.1rem;"></i>Site Visit
                </label>
                <label class="pd-lead-tab" onclick="setLeadType('callback', this)" title="Request callback">
                  <i class="bi bi-telephone-inbound d-block mb-1" style="font-size:1.1rem;"></i>Callback
                </label>
                <label class="pd-lead-tab" onclick="setLeadType('brochure', this)" title="Request brochure">
                  <i class="bi bi-file-earmark-pdf d-block mb-1" style="font-size:1.1rem;"></i>Brochure
                </label>
                <label class="pd-lead-tab" onclick="setLeadType('loan', this)" title="Check home loan eligibility" style="background:rgba(255,255,255,.15);">
                  <i class="bi bi-bank d-block mb-1" style="font-size:1.1rem;"></i>Loan
                </label>
              </div>

              <div>
                <label>Your Name *</label>
                <input type="text" name="name" class="form-control" placeholder="Full Name" value="{{ old('name') }}" required>
              </div>
              <div>
                <label>Phone Number *</label>
                <input type="tel" name="phone" class="form-control" placeholder="+91 XXXXX XXXXX" value="{{ old('phone') }}" required>
              </div>
              <div>
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="your@email.com" value="{{ old('email') }}">
              </div>
              <div>
                <label>Message (optional)</label>
                <textarea name="message" class="form-control" rows="3" placeholder="Any specific requirements?">{{ old('message') }}</textarea>
              </div>

              <button type="submit" class="pd-lead-btn" id="leadSubmitBtn">
                <i class="bi bi-send-fill me-2"></i> Submit Enquiry
              </button>
            </form>

            {{-- WhatsApp quick button --}}
            <a href="https://wa.me/91{{ config('app.contact_phone','7340753780') }}?text={{ urlencode('Hi! I am interested in ' . $project->title . '. Please share more details.') }}"
               target="_blank"
               style="display:block;margin-top:10px;text-align:center;background:#25d366;color:#fff;border-radius:8px;padding:10px;font-size:.85rem;font-weight:600;text-decoration:none;transition:all .2s;">
              <i class="bi bi-whatsapp me-2"></i>Chat on WhatsApp
            </a>
          </div>
        </div>

        {{-- Project overview --}}
        <div class="pd-card mt-4">
          <div class="pd-section-title" style="font-size:.95rem;"><i class="bi bi-info-circle-fill"></i> Project Overview</div>
          <table style="width:100%;font-size:.83rem;color:#475569;border-collapse:collapse;">
            @if($project->project_type)
            <tr>
              <td style="padding:7px 0;color:#94a3b8;">Type</td>
              <td style="padding:7px 0;font-weight:600;color:#1e293b;text-align:right;">{{ $project->project_type }}</td>
            </tr>
            @endif
            <tr>
              <td style="padding:7px 0;color:#94a3b8;">Status</td>
              <td style="padding:7px 0;font-weight:600;color:#1e293b;text-align:right;">{{ $project->status }}</td>
            </tr>
            @if($project->total_towers)
            <tr>
              <td style="padding:7px 0;color:#94a3b8;">Towers</td>
              <td style="padding:7px 0;font-weight:600;color:#1e293b;text-align:right;">{{ $project->total_towers }}</td>
            </tr>
            @endif
            @if($project->floors_per_tower)
            <tr>
              <td style="padding:7px 0;color:#94a3b8;">Floors/Tower</td>
              <td style="padding:7px 0;font-weight:600;color:#1e293b;text-align:right;">{{ $project->floors_per_tower }}</td>
            </tr>
            @endif
            @if($project->total_units)
            <tr>
              <td style="padding:7px 0;color:#94a3b8;">Total Units</td>
              <td style="padding:7px 0;font-weight:600;color:#1e293b;text-align:right;">{{ number_format($project->total_units) }}</td>
            </tr>
            @endif
            @if($project->available_units !== null)
            <tr>
              <td style="padding:7px 0;color:#94a3b8;">Available</td>
              <td style="padding:7px 0;font-weight:600;color:#22c55e;text-align:right;">{{ number_format($project->available_units) }}</td>
            </tr>
            @endif
            @if($project->possession_date)
            <tr>
              <td style="padding:7px 0;color:#94a3b8;">Possession</td>
              <td style="padding:7px 0;font-weight:600;color:#1e293b;text-align:right;">{{ \Carbon\Carbon::parse($project->possession_date)->format('M Y') }}</td>
            </tr>
            @endif
            @if($project->rera_id)
            <tr>
              <td style="padding:7px 0;color:#94a3b8;">RERA ID</td>
              <td style="padding:7px 0;font-weight:600;color:#1e293b;text-align:right;font-size:.75rem;">{{ $project->rera_id }}</td>
            </tr>
            @endif
          </table>
        </div>

        {{-- 🛡️ Insurance mini-banner --}}
        <div class="pd-card mt-4" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1.5px solid #86efac;">
          <div class="d-flex align-items-center gap-3">
            <div style="background:#16a34a;width:42px;height:42px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <i class="bi bi-shield-check-fill text-white" style="font-size:1.1rem;"></i>
            </div>
            <div class="flex-grow-1">
              <div class="fw-bold small" style="color:#15803d;">Home Insurance from ₹{{ $project->price_from ? number_format((int)($project->price_from * 0.0007)) : '2,000' }}/year</div>
              <div class="text-muted" style="font-size:.73rem;">Protect your investment · 10+ insurers</div>
            </div>
            <button type="button" class="btn btn-sm fw-semibold"
                    style="background:#16a34a;color:#fff;border-radius:20px;white-space:nowrap;"
                    onclick="openInsuranceModal(null, {{ $project->id }}, 'project-page')">
              Get Quote
            </button>
          </div>
        </div>

        {{-- Builder info mini card --}}
        <div class="pd-card mt-4">
          <div class="pd-section-title" style="font-size:.95rem;"><i class="bi bi-building-fill"></i> Builder</div>
          <div class="d-flex align-items-center gap-3">
            @if($project->builder->logo)
              <img src="{{ asset('storage/' . $project->builder->logo) }}" alt="{{ $project->builder->company_name }}"
                   style="width:48px;height:48px;border-radius:8px;object-fit:cover;border:1px solid #e2e8f0;">
            @else
              <div style="width:48px;height:48px;border-radius:8px;background:#dbeafe;display:flex;align-items:center;justify-content:center;font-size:1.2rem;font-weight:700;color:#0078d4;">
                {{ strtoupper(substr($project->builder->company_name ?: $project->builder->name, 0, 1)) }}
              </div>
            @endif
            <div>
              <div style="font-size:.9rem;font-weight:700;color:#1e293b;">{{ $project->builder->company_name ?: $project->builder->name }}</div>
              @if($project->builder->is_verified)
                <div style="font-size:.72rem;color:#16a34a;"><i class="bi bi-patch-check-fill me-1"></i>Verified Builder</div>
              @endif
            </div>
          </div>
          <a href="{{ route('builders.show', $project->builder) }}"
             style="display:block;margin-top:12px;text-align:center;background:#eff6ff;color:#0078d4;border:1px solid #bfdbfe;border-radius:7px;padding:8px;font-size:.82rem;font-weight:600;text-decoration:none;">
            <i class="bi bi-person-lines-fill me-1"></i>View Builder Profile
          </a>
        </div>

      </div>
    </div>
  </div>

  <div style="height:48px;"></div>
</main>

{{-- ===== LOAN ELIGIBILITY MODAL ===== --}}
@include('frontend.partials.loan-eligibility-modal', [
    'property_id'        => null,
    'builder_project_id' => $project->id,
    'source'             => 'project-page',
    'source_page'        => request()->path(),
    'prefill_loan_amount'=> null,
])

{{-- ===== INSURANCE MODAL ===== --}}
@include('frontend.partials.insurance-modal', [
    'property_id'        => null,
    'builder_project_id' => $project->id,
    'source'             => 'project-page',
    'source_page'        => request()->path(),
    'prefill_value'      => $project->price_from ?? null,
    'prefill_city'       => $project->location   ?? null,
    'prefill_type'       => $project->project_type ?? null,
])

<script>
function setLeadType(type, el) {
  // Loan tab → open loan modal instead of submitting lead form
  if (type === 'loan') {
    document.querySelectorAll('.pd-lead-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    openLoanModal(null, {{ $project->id }}, 'project-page');
    return;
  }

  document.getElementById('lead_type_input').value = type;
  document.querySelectorAll('.pd-lead-tab').forEach(t => t.classList.remove('active'));
  el.classList.add('active');

  // Update button label
  const labels = {
    general:  'Submit Enquiry',
    visit:    'Schedule Site Visit',
    callback: 'Request Callback',
    brochure: 'Request Brochure',
  };
  const btn = document.getElementById('leadSubmitBtn');
  if (btn) btn.innerHTML = '<i class="bi bi-send-fill me-2"></i>' + (labels[type] || 'Submit');
}

function openLightbox(src) {
  document.getElementById('pd-lightbox-img').src = src;
  document.getElementById('pd-lightbox').classList.add('open');
  document.body.style.overflow = 'hidden';
}
document.getElementById('pd-lightbox-close').addEventListener('click', function() {
  document.getElementById('pd-lightbox').classList.remove('open');
  document.body.style.overflow = '';
});
document.getElementById('pd-lightbox').addEventListener('click', function(e) {
  if (e.target === this) { this.classList.remove('open'); document.body.style.overflow = ''; }
});

// AJAX form submission
document.getElementById('leadForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const btn = document.getElementById('leadSubmitBtn');
  btn.disabled = true;
  btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Submitting...';

  fetch(this.action, {
    method: 'POST',
    body: new FormData(this),
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      this.innerHTML = `<div style="text-align:center;padding:20px 0;">
        <i class="bi bi-check-circle-fill d-block mb-2" style="font-size:2.5rem;color:#4ade80;"></i>
        <strong>${data.message}</strong>
      </div>`;
    } else {
      alert(data.message || 'Something went wrong. Please try again.');
      btn.disabled = false;
      btn.innerHTML = '<i class="bi bi-send-fill me-2"></i>Submit Enquiry';
    }
  })
  .catch((err) => {
    console.error('Enquiry error:', err);
    alert('Unable to send enquiry. Please try again or contact us directly.');
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-send-fill me-2"></i>Submit Enquiry';
  });
});

// Sticky project bar
(function() {
  const bar = document.getElementById('proj-sticky-bar');
  if (!bar) return;
  window.addEventListener('scroll', function() {
    bar.style.transform = window.scrollY > 500 ? 'translateY(0)' : 'translateY(110%)';
  }, { passive: true });
})();
</script>

{{-- ===== STICKY PROJECT BOTTOM BAR ===== --}}
<div id="proj-sticky-bar">
  <div class="container">
    <div class="psb-inner">
      <div>
        <div class="psb-title">{{ Str::limit($project->title, 50) }}</div>
        <div class="psb-units">
          @if($project->available_units) {{ $project->available_units }} units available · @endif
          {{ $project->city }}
        </div>
      </div>
      @if($minPrice)
      <div class="psb-price">
        ₹{{ $minPrice >= 10000000 ? number_format($minPrice/10000000,2).'Cr' : number_format($minPrice/100000,1).'L' }}
        @if($maxPrice && $maxPrice != $minPrice)
          – ₹{{ $maxPrice >= 10000000 ? number_format($maxPrice/10000000,2).'Cr' : number_format($maxPrice/100000,1).'L' }}
        @endif
      </div>
      @endif
      <div class="d-flex gap-2">
        <a href="tel:+91{{ config('app.contact_phone','7340753780') }}" class="btn btn-light text-primary" style="border-radius:8px;font-weight:700;font-size:.85rem;">
          <i class="bi bi-telephone-fill me-1"></i>Call
        </a>
        <a href="https://wa.me/91{{ config('app.contact_phone','7340753780') }}?text={{ urlencode('Hi! I am interested in ' . $project->title . '. Please share more details.') }}"
           target="_blank" class="btn" style="background:#25d366;color:#fff;border-radius:8px;font-weight:700;font-size:.85rem;">
          <i class="bi bi-whatsapp me-1"></i>WhatsApp
        </a>
        <button class="btn btn-warning" style="border-radius:8px;font-weight:700;font-size:.85rem;color:#1e293b;"
                onclick="document.getElementById('lead-form-section').scrollIntoView({behavior:'smooth'})">
          <i class="bi bi-calendar-check me-1"></i>Book Site Visit
        </button>
      </div>
    </div>
  </div>
</div>
