@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/frontend/builders.css') }}">
@endpush

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
            <a href="tel:+91{{ config('app.contact_phone','7340753780') }}" class="btn btn-light">
              <i class="bi bi-telephone-fill"></i> +91 {{ config('app.contact_phone','7340753780') }}
            </a>
            <a href="https://wa.me/91{{ config('app.contact_phone','7340753780') }}" target="_blank" class="btn btn-wa-hero">
              <i class="bi bi-whatsapp"></i> WhatsApp
            </a>
            <a href="mailto:{{ config('app.contact_email','admin@indianesthub.com') }}" class="btn btn-outline-light">
              <i class="bi bi-envelope"></i> Email
            </a>
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

        {{-- Trust Timeline --}}
        @if($builder->established_year || $builder->total_delivered_projects)
        <div class="bp-card mb-4">
          <div class="bp-section-title"><i class="bi bi-clock-history"></i> Builder Track Record</div>
          <div class="bp-trust-timeline">
            @if($builder->established_year)
            <div class="bp-tt-item">
              <div class="bp-tt-dot"></div>
              <div class="bp-tt-year">{{ $builder->established_year }}</div>
              <div class="bp-tt-text">Company founded · {{ date('Y') - $builder->established_year }} years of experience</div>
            </div>
            @endif
            @if($builder->total_delivered_projects)
            <div class="bp-tt-item">
              <div class="bp-tt-dot" style="background:#22c55e;box-shadow:0 0 0 2px #dcfce7;"></div>
              <div class="bp-tt-year">Projects Delivered</div>
              <div class="bp-tt-text">{{ $builder->total_delivered_projects }} completed projects — {{ number_format($totalUnits ?? 0) }} families housed</div>
            </div>
            @endif
            @if($builder->rera_registration)
            <div class="bp-tt-item">
              <div class="bp-tt-dot" style="background:#7c3aed;box-shadow:0 0 0 2px #ede9fe;"></div>
              <div class="bp-tt-year">RERA Registered</div>
              <div class="bp-tt-text">Reg. {{ $builder->rera_registration }} — Legally compliant & government-approved</div>
            </div>
            @endif
            <div class="bp-tt-item">
              <div class="bp-tt-dot" style="background:#f59e0b;box-shadow:0 0 0 2px #fef3c7;"></div>
              <div class="bp-tt-year">Active Projects</div>
              <div class="bp-tt-text">{{ $builder->projects_count - ($builder->total_delivered_projects ?? 0) }} ongoing projects across {{ $citiesServed->count() }} {{ Str::plural('city', $citiesServed->count()) }}</div>
            </div>
          </div>
        </div>
        @endif

        {{-- Projects Grid with filter tabs --}}
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
          <div class="bp-section-title mb-0"><i class="bi bi-diagram-3-fill"></i> Projects ({{ $projects->total() }})</div>
        </div>

        {{-- Status filter tabs --}}
        <div class="bp-filter-tabs">
          <span class="bp-filter-tab active" onclick="filterProjects('all', this)">All</span>
          <span class="bp-filter-tab" onclick="filterProjects('Under Construction', this)">Under Construction</span>
          <span class="bp-filter-tab" onclick="filterProjects('Ready to Move', this)">Ready to Move</span>
          <span class="bp-filter-tab" onclick="filterProjects('Upcoming', this)">Upcoming</span>
          <span class="bp-filter-tab" onclick="filterProjects('Completed', this)">Completed</span>
        </div>

        @if($projects->count())
        <div class="row g-3 mb-3" id="bp-projects-grid">
          @foreach($projects as $project)
          <div class="col-md-6 bp-proj-item" data-status="{{ $project->status }}">
            <div class="bp-proj-card">
              <div class="bp-proj-img">
                @if($project->cover_image)
                  <img src="{{ asset('storage/' . $project->cover_image) }}" alt="{{ $project->title }}">
                @else
                  <div style="height:100%;display:flex;align-items:center;justify-content:center;font-size:2.5rem;color:#0078d4;">
                    <i class="bi bi-buildings-fill"></i>
                  </div>
                @endif
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
                  @if($project->price_from) ₹{{ number_format($project->price_from/100000,1) }}L @endif
                  @if($project->price_from && $project->price_to) – @endif
                  @if($project->price_to) ₹{{ number_format($project->price_to/10000000,2) }}Cr @endif
                </p>
                @endif
                <a href="{{ $project->slug ? route('projects.show', $project->slug) : route('projects.show.by-id', $project->id) }}" class="bp-proj-btn">
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

        {{-- ★ Lead capture form (NEW) --}}
        <div class="bp-lead-form mb-4">
          <h5><i class="bi bi-chat-dots-fill me-2"></i>Talk to {{ $builder->company_name ?: $builder->name }}</h5>
          <p>Free consultation · No brokerage · Respond within 2 hours</p>

          <div id="bp-lead-success" style="display:none;text-align:center;padding:16px 0;">
            <i class="bi bi-check-circle-fill d-block mb-2" style="font-size:2.5rem;color:#4ade80;"></i>
            <strong>We'll contact you shortly!</strong>
          </div>

          <form id="bp-lead-form-el" action="{{ route('builders.inquiry', $builder) }}" method="POST">
            @csrf
            <input type="hidden" name="lead_type" value="general">
            <input type="text"  name="name"  class="form-control" placeholder="Your Name *" required>
            <input type="tel"   name="phone" class="form-control" placeholder="Phone Number *" required>
            <input type="email" name="email" class="form-control" placeholder="Email (optional)">
            <select name="lead_type" class="form-control" style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);color:#fff;border-radius:8px;padding:9px 12px;font-size:.83rem;margin-bottom:8px;">
              <option value="general" style="color:#1e293b;background:#fff;">General Enquiry</option>
              <option value="visit"   style="color:#1e293b;background:#fff;">Schedule Site Visit</option>
              <option value="callback" style="color:#1e293b;background:#fff;">Request Callback</option>
              <option value="brochure" style="color:#1e293b;background:#fff;">Get Brochure</option>
            </select>
            <button type="submit" class="bp-lead-btn" id="bp-lead-submit-btn">
              <i class="bi bi-send-fill me-2"></i>Send Enquiry
            </button>
          </form>

          <a href="https://wa.me/91{{ config('app.contact_phone','7340753780') }}"
             target="_blank"
             style="display:block;margin-top:10px;text-align:center;background:#25d366;color:#fff;border-radius:8px;padding:10px;font-size:.85rem;font-weight:600;text-decoration:none;">
            <i class="bi bi-whatsapp me-2"></i>Chat on WhatsApp
          </a>
        </div>

        {{-- Quick Contact --}}
        <div class="bp-card mb-4">
          <div class="bp-section-title"><i class="bi bi-chat-dots-fill"></i> Quick Contact</div>
          <a href="tel:+91{{ config('app.contact_phone','7340753780') }}" class="btn w-100 mb-2"
             style="background:#0078d4;color:#fff;border-radius:8px;font-weight:600;">
            <i class="bi bi-telephone-fill me-2"></i>+91 {{ config('app.contact_phone','7340753780') }}
          </a>
          <a href="https://wa.me/91{{ config('app.contact_phone','7340753780') }}" target="_blank"
             class="btn w-100 mb-2" style="background:#25d366;color:#fff;border-radius:8px;font-weight:600;">
            <i class="bi bi-whatsapp me-2"></i>WhatsApp
          </a>
          <a href="mailto:{{ config('app.contact_email','admin@indianesthub.com') }}" class="btn w-100 btn-outline-secondary mb-2" style="border-radius:8px;">
            <i class="bi bi-envelope me-2"></i>{{ config('app.contact_email','admin@indianesthub.com') }}
          </a>
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

<script>
// Project status filter
function filterProjects(status, el) {
  document.querySelectorAll('.bp-filter-tab').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
  document.querySelectorAll('.bp-proj-item').forEach(function(item) {
    if (status === 'all' || item.dataset.status === status) {
      item.style.display = '';
    } else {
      item.style.display = 'none';
    }
  });
}

// Builder lead form AJAX
const bpForm = document.getElementById('bp-lead-form-el');
if (bpForm) {
  bpForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('bp-lead-submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
    const formData = new FormData(this);
    fetch(this.action, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value || ''
      }
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        bpForm.style.display = 'none';
        document.getElementById('bp-lead-success').style.display = 'block';
      } else {
        alert(data.message || 'Something went wrong. Please try again.');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-send-fill me-2"></i>Send Enquiry';
      }
    })
    .catch((err) => {
      console.error('Enquiry error:', err);
      alert('Unable to send enquiry. Please try again or call us directly.');
      btn.disabled = false;
      btn.innerHTML = '<i class="bi bi-send-fill me-2"></i>Send Enquiry';
    });
  });
}
</script>
