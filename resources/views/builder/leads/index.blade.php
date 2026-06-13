@extends('builder.layout')
@section('title', 'Leads & Enquiries – Builder Panel')

@section('head')
<style>
/* ── Page ───────────────────────────────────────────────────────────── */
.bl-kpi-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:22px; }
@media(max-width:900px){ .bl-kpi-grid { grid-template-columns:repeat(2,1fr); } }
@media(max-width:540px){ .bl-kpi-grid { grid-template-columns:1fr 1fr; } }

.bl-kpi {
  background:#fff; border-radius:12px; padding:18px 16px;
  border:1px solid #e2e8f0; display:flex; align-items:center; gap:14px;
  box-shadow:0 1px 4px rgba(0,0,0,.05);
}
.bl-kpi-icon { width:46px; height:46px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0; }
.bl-kpi-num  { font-size:1.7rem; font-weight:800; line-height:1; color:#1e293b; }
.bl-kpi-lbl  { font-size:.72rem; color:#94a3b8; margin-top:2px; text-transform:uppercase; letter-spacing:.4px; }

/* ── Filter bar ─────────────────────────────────────────────────────── */
.bl-filters { background:#fff; border-radius:12px; border:1px solid #e2e8f0; padding:14px 18px; margin-bottom:16px; }

/* ── Table ──────────────────────────────────────────────────────────── */
.bl-table-wrap { background:#fff; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,.05); }
.bl-table { width:100%; border-collapse:collapse; font-size:.84rem; }
.bl-table thead th { background:#f8fafc; padding:11px 14px; font-size:.7rem; text-transform:uppercase; letter-spacing:.5px; color:#64748b; border-bottom:2px solid #e2e8f0; white-space:nowrap; font-weight:700; }
.bl-table tbody td { padding:13px 14px; border-bottom:1px solid #f1f5f9; vertical-align:middle; color:#334155; }
.bl-table tbody tr:last-child td { border-bottom:none; }
.bl-table tbody tr:hover td { background:#f8fafc; }

/* ── Heat badges ────────────────────────────────────────────────────── */
.heat-badge { display:inline-flex; align-items:center; gap:4px; font-size:.7rem; font-weight:700; padding:3px 9px; border-radius:20px; white-space:nowrap; }
.heat-hot  { background:#fef2f2; color:#dc2626; }
.heat-warm { background:#fff7ed; color:#c2410c; }
.heat-cold { background:#f1f5f9; color:#64748b; }

/* ── Type badges ────────────────────────────────────────────────────── */
.type-badge { display:inline-flex; align-items:center; gap:4px; font-size:.72rem; font-weight:600; padding:3px 8px; border-radius:6px; }

/* ── Action buttons ─────────────────────────────────────────────────── */
.bl-action-btn {
  width:30px; height:30px; border-radius:7px; display:inline-flex;
  align-items:center; justify-content:center; font-size:.82rem;
  border:1px solid #e2e8f0; background:#f8fafc; cursor:pointer;
  transition:all .15s; text-decoration:none; color:#475569;
}
.bl-action-btn:hover { background:#eff6ff; color:#0078d4; border-color:#bfdbfe; }
.bl-action-btn.danger:hover { background:#fef2f2; color:#dc2626; border-color:#fecaca; }
.bl-action-btn.success { background:#f0fdf4; color:#16a34a; border-color:#86efac; }
.bl-action-btn.success:hover { background:#dcfce7; }

/* ── Lead drawer (slide-in panel) ───────────────────────────────────── */
#lead-drawer {
  position:fixed; top:0; right:0; height:100%; width:420px; max-width:100vw;
  background:#fff; box-shadow:-4px 0 30px rgba(0,0,0,.15); z-index:9999;
  transform:translateX(100%); transition:transform .3s cubic-bezier(.4,0,.2,1);
  overflow-y:auto; display:flex; flex-direction:column;
}
#lead-drawer.open { transform:translateX(0); }
#lead-drawer-backdrop {
  display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:9998;
}
#lead-drawer-backdrop.open { display:block; }
.ld-header { background:linear-gradient(135deg,#0a2d5e,#0078d4); color:#fff; padding:20px; flex-shrink:0; }
.ld-body { padding:20px; flex:1; }
.ld-section { margin-bottom:20px; }
.ld-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#94a3b8; margin-bottom:6px; }
.ld-value { font-size:.88rem; color:#1e293b; font-weight:500; }

/* heat ring */
.ld-heat-ring { width:56px; height:56px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.9rem; font-weight:800; border:3px solid; }
.ring-hot  { border-color:#ef4444; background:#fef2f2; color:#dc2626; }
.ring-warm { border-color:#f59e0b; background:#fff7ed; color:#c2410c; }
.ring-cold { border-color:#94a3b8; background:#f1f5f9; color:#64748b; }

/* call log */
.call-log-item { border-left:3px solid #dbeafe; padding:6px 10px; margin-bottom:8px; background:#f8fafc; border-radius:0 6px 6px 0; }
.call-log-time { font-size:.68rem; color:#94a3b8; }
.call-log-note { font-size:.82rem; color:#334155; margin-top:2px; }

/* follow-up overdue highlight */
.overdue-chip { background:#fef2f2; color:#dc2626; font-size:.7rem; font-weight:600; padding:2px 7px; border-radius:4px; }
.upcoming-chip { background:#fff7ed; color:#c2410c; font-size:.7rem; font-weight:600; padding:2px 7px; border-radius:4px; }
.done-chip     { background:#f0fdf4; color:#16a34a; font-size:.7rem; font-weight:600; padding:2px 7px; border-radius:4px; }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">

  {{-- Page header --}}
  <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
      <h1 class="h3 mb-1">Leads & Enquiries</h1>
      <p class="text-muted mb-0">Track, score, and follow up on every lead from your projects</p>
    </div>
    <a href="{{ route('builder.leads.export', request()->query()) }}"
       class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2">
      <i class="bi bi-download"></i> Export CSV
    </a>
  </div>

  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  {{-- ── KPI Cards ─────────────────────────────────────────────────────── --}}
  <div class="bl-kpi-grid">
    <div class="bl-kpi">
      <div class="bl-kpi-icon" style="background:#eff6ff;"><i class="bi bi-people-fill" style="color:#3b82f6;"></i></div>
      <div>
        <div class="bl-kpi-num">{{ $stats['total'] }}</div>
        <div class="bl-kpi-lbl">Total Leads</div>
      </div>
    </div>
    <div class="bl-kpi">
      <div class="bl-kpi-icon" style="background:#fef2f2;"><i class="bi bi-fire" style="color:#ef4444;"></i></div>
      <div>
        <div class="bl-kpi-num" style="color:#ef4444;">{{ $stats['hot'] }}</div>
        <div class="bl-kpi-lbl">Hot Leads</div>
      </div>
    </div>
    <div class="bl-kpi">
      <div class="bl-kpi-icon" style="background:#f0fdf4;"><i class="bi bi-check-circle-fill" style="color:#22c55e;"></i></div>
      <div>
        <div class="bl-kpi-num" style="color:#22c55e;">{{ $stats['converted'] }}</div>
        <div class="bl-kpi-lbl">Converted</div>
      </div>
    </div>
    <div class="bl-kpi">
      <div class="bl-kpi-icon" style="background:#fff7ed;"><i class="bi bi-clock-history" style="color:#f59e0b;"></i></div>
      <div>
        <div class="bl-kpi-num" style="color:#f59e0b;">{{ $stats['overdue'] }}</div>
        <div class="bl-kpi-lbl">Overdue Follow-ups</div>
      </div>
    </div>
    <div class="bl-kpi">
      <div class="bl-kpi-icon" style="background:#eff6ff;"><i class="bi bi-envelope-open-fill" style="color:#3b82f6;"></i></div>
      <div>
        <div class="bl-kpi-num">{{ $stats['new'] }}</div>
        <div class="bl-kpi-lbl">New / Unread</div>
      </div>
    </div>
    <div class="bl-kpi">
      <div class="bl-kpi-icon" style="background:#fdf4ff;"><i class="bi bi-telephone-fill" style="color:#a855f7;"></i></div>
      <div>
        <div class="bl-kpi-num">{{ $stats['contacted'] }}</div>
        <div class="bl-kpi-lbl">Contacted</div>
      </div>
    </div>
    <div class="bl-kpi">
      <div class="bl-kpi-icon" style="background:#ecfdf5;"><i class="bi bi-calendar-check" style="color:#059669;"></i></div>
      <div>
        <div class="bl-kpi-num">{{ $stats['today'] }}</div>
        <div class="bl-kpi-lbl">Today</div>
      </div>
    </div>
    <div class="bl-kpi">
      <div class="bl-kpi-icon" style="background:#f5f3ff;"><i class="bi bi-graph-up-arrow" style="color:#7c3aed;"></i></div>
      <div>
        <div class="bl-kpi-num">{{ $stats['this_week'] }}</div>
        <div class="bl-kpi-lbl">This Week</div>
      </div>
    </div>
  </div>

  {{-- ── Filters ─────────────────────────────────────────────────────────── --}}
  <div class="bl-filters">
    <form action="{{ route('builder.leads.index') }}" method="GET" class="row g-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label form-label-sm mb-1">Search</label>
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Name / phone / email…" value="{{ request('search') }}">
      </div>
      <div class="col-md-2">
        <label class="form-label form-label-sm mb-1">Project</label>
        <select name="project_id" class="form-select form-select-sm">
          <option value="">All Projects</option>
          @foreach($projects as $proj)
          <option value="{{ $proj->id }}" {{ request('project_id') == $proj->id ? 'selected' : '' }}>{{ $proj->title }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label form-label-sm mb-1">Type</label>
        <select name="lead_type" class="form-select form-select-sm">
          <option value="">All Types</option>
          <option value="general"  {{ request('lead_type') === 'general'  ? 'selected' : '' }}>General</option>
          <option value="visit"    {{ request('lead_type') === 'visit'    ? 'selected' : '' }}>Site Visit</option>
          <option value="callback" {{ request('lead_type') === 'callback' ? 'selected' : '' }}>Callback</option>
          <option value="brochure" {{ request('lead_type') === 'brochure' ? 'selected' : '' }}>Brochure</option>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label form-label-sm mb-1">Status</label>
        <select name="status" class="form-select form-select-sm">
          <option value="">All Status</option>
          <option value="new"       {{ request('status') === 'new'       ? 'selected' : '' }}>New</option>
          <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
          <option value="converted" {{ request('status') === 'converted' ? 'selected' : '' }}>Converted</option>
          <option value="lost"      {{ request('status') === 'lost'      ? 'selected' : '' }}>Lost</option>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label form-label-sm mb-1">Heat</label>
        <select name="heat" class="form-select form-select-sm">
          <option value="">All</option>
          <option value="hot"  {{ request('heat') === 'hot'  ? 'selected' : '' }}>🔥 Hot (80+)</option>
          <option value="warm" {{ request('heat') === 'warm' ? 'selected' : '' }}>🟡 Warm (50–79)</option>
          <option value="cold" {{ request('heat') === 'cold' ? 'selected' : '' }}>🔵 Cold (&lt;50)</option>
        </select>
      </div>
      <div class="col-md-1 d-flex gap-1">
        <button type="submit" class="btn btn-primary btn-sm px-3 w-100"><i class="bi bi-funnel"></i></button>
        @if(request()->anyFilled(['search','status','lead_type','project_id','heat']))
        <a href="{{ route('builder.leads.index') }}" class="btn btn-outline-secondary btn-sm px-2"><i class="bi bi-x"></i></a>
        @endif
      </div>
    </form>
  </div>

  {{-- ── Table ─────────────────────────────────────────────────────────────── --}}
  <div class="bl-table-wrap">
    @if($leads->count())
    <div class="table-responsive">
      <table class="bl-table">
        <thead>
          <tr>
            <th>Heat</th>
            <th>Lead</th>
            <th>Contact</th>
            <th>Project</th>
            <th>Type</th>
            <th>Follow-up</th>
            <th>Status</th>
            <th>Received</th>
            <th style="text-align:right;padding-right:18px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($leads as $lead)
          @php
            $heat = $lead->hotLabel();
            $heatConfig = [
              'hot'  => ['🔥', 'heat-hot',  'Hot'],
              'warm' => ['🟡', 'heat-warm', 'Warm'],
              'cold' => ['🔵', 'heat-cold', 'Cold'],
            ][$heat];
            $statusConfig = [
              'new'       => ['New',       'primary'],
              'contacted' => ['Contacted', 'warning'],
              'converted' => ['Converted', 'success'],
              'lost'      => ['Lost',      'secondary'],
            ];
            $sc = $statusConfig[$lead->status] ?? ['Unknown', 'secondary'];
            $typeIcons = [
              'general'  => ['bi-chat-dots',          '#3b82f6', 'General'],
              'visit'    => ['bi-calendar-check',     '#22c55e', 'Site Visit'],
              'callback' => ['bi-telephone-inbound',  '#f59e0b', 'Callback'],
              'brochure' => ['bi-file-earmark-pdf',   '#ef4444', 'Brochure'],
              'whatsapp' => ['bi-whatsapp',           '#25d366', 'WhatsApp'],
            ];
            $ti = $typeIcons[$lead->lead_type] ?? ['bi-question-circle', '#94a3b8', ucfirst($lead->lead_type)];
            $callCount = count($lead->call_log ?? []);
          @endphp
          <tr>
            {{-- Heat score --}}
            <td>
              <span class="heat-badge {{ $heatConfig[1] }}" title="Score: {{ $lead->hot_score }}">
                {{ $heatConfig[0] }} {{ $heatConfig[2] }}
              </span>
              <div style="font-size:.65rem;color:#94a3b8;margin-top:2px;text-align:center;">{{ $lead->hot_score }}/100</div>
            </td>

            {{-- Name + message --}}
            <td>
              <div style="font-weight:700;color:#1e293b;">{{ $lead->name }}</div>
              @if($lead->message)
              <div style="font-size:.74rem;color:#94a3b8;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $lead->message }}">
                {{ $lead->message }}
              </div>
              @endif
              @if($callCount)
              <div style="font-size:.68rem;color:#7c3aed;margin-top:2px;">
                <i class="bi bi-telephone-fill me-1"></i>{{ $callCount }} {{ Str::plural('call', $callCount) }} logged
              </div>
              @endif
            </td>

            {{-- Contact --}}
            <td>
              <a href="tel:{{ $lead->phone }}" class="d-block text-decoration-none fw-500" style="color:#1e293b;font-size:.83rem;">
                <i class="bi bi-telephone me-1 text-success"></i>{{ $lead->phone }}
              </a>
              @if($lead->email)
              <a href="mailto:{{ $lead->email }}" class="d-block text-decoration-none" style="color:#64748b;font-size:.75rem;margin-top:2px;">
                <i class="bi bi-envelope me-1"></i>{{ $lead->email }}
              </a>
              @endif
            </td>

            {{-- Project --}}
            <td>
              @if($lead->project)
              <span style="font-size:.8rem;color:#6366f1;font-weight:500;">{{ Str::limit($lead->project->title, 28) }}</span>
              @else
              <span class="text-muted" style="font-size:.8rem;">General</span>
              @endif
            </td>

            {{-- Type --}}
            <td>
              <span class="type-badge" style="background:{{ $ti[1] }}18;color:{{ $ti[1] }};">
                <i class="bi {{ $ti[0] }}"></i> {{ $ti[2] }}
              </span>
            </td>

            {{-- Follow-up --}}
            <td>
              @if($lead->follow_up_at)
                @if($lead->follow_up_at->isPast())
                  <span class="overdue-chip"><i class="bi bi-exclamation-triangle me-1"></i>Overdue</span>
                @elseif($lead->follow_up_at->isToday())
                  <span class="upcoming-chip"><i class="bi bi-bell me-1"></i>Today</span>
                @else
                  <span class="done-chip"><i class="bi bi-calendar me-1"></i>{{ $lead->follow_up_at->format('d M') }}</span>
                @endif
              @else
                <span style="font-size:.75rem;color:#cbd5e1;">—</span>
              @endif
            </td>

            {{-- Status --}}
            <td>
              <select class="form-select form-select-sm status-changer" data-lead-id="{{ $lead->id }}"
                      style="font-size:.75rem;min-width:110px;border-radius:6px;">
                <option value="new"       {{ $lead->status === 'new'       ? 'selected' : '' }}>🔵 New</option>
                <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>🟡 Contacted</option>
                <option value="converted" {{ $lead->status === 'converted' ? 'selected' : '' }}>🟢 Converted</option>
                <option value="lost"      {{ $lead->status === 'lost'      ? 'selected' : '' }}>⚫ Lost</option>
              </select>
            </td>

            {{-- Date --}}
            <td style="white-space:nowrap;">
              <span style="font-size:.78rem;color:#64748b;">{{ $lead->created_at->format('d M Y') }}</span><br>
              <span style="font-size:.7rem;color:#94a3b8;">{{ $lead->created_at->diffForHumans() }}</span>
            </td>

            {{-- Actions --}}
            <td style="text-align:right;padding-right:14px;white-space:nowrap;">
              {{-- Open drawer --}}
              <button type="button" class="bl-action-btn open-drawer-btn"
                      data-id="{{ $lead->id }}"
                      data-name="{{ $lead->name }}"
                      data-phone="{{ $lead->phone }}"
                      data-email="{{ $lead->email ?? '' }}"
                      data-project="{{ $lead->project->title ?? 'General' }}"
                      data-type="{{ $lead->lead_type }}"
                      data-status="{{ $lead->status }}"
                      data-score="{{ $lead->hot_score }}"
                      data-heat="{{ $heat }}"
                      data-message="{{ $lead->message ?? '' }}"
                      data-notes="{{ $lead->notes ?? '' }}"
                      data-followup="{{ $lead->follow_up_at?->format('Y-m-d\TH:i') ?? '' }}"
                      data-calllog='@json($lead->call_log ?? [])'
                      data-received="{{ $lead->created_at->format('d M Y, h:i A') }}"
                      data-notes-url="{{ route('builder.leads.save-notes', $lead) }}"
                      data-calllog-url="{{ route('builder.leads.add-call-log', $lead) }}"
                      title="View Details">
                <i class="bi bi-eye"></i>
              </button>
              {{-- Call --}}
              <a href="tel:{{ $lead->phone }}" class="bl-action-btn success" title="Call {{ $lead->name }}">
                <i class="bi bi-telephone-fill"></i>
              </a>
              {{-- WhatsApp --}}
              <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $lead->phone) }}"
                 target="_blank" class="bl-action-btn" style="color:#25d366;border-color:#86efac;" title="WhatsApp">
                <i class="bi bi-whatsapp"></i>
              </a>
              {{-- Delete --}}
              <form action="{{ route('builder.leads.destroy', $lead) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Delete this lead permanently?')">
                @csrf @method('DELETE')
                <button type="submit" class="bl-action-btn danger" title="Delete">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="px-4 py-3 border-top">
      {{ $leads->links() }}
    </div>

    @else
    <div class="text-center py-5">
      <i class="bi bi-inbox" style="font-size:3rem;color:#cbd5e1;display:block;margin-bottom:12px;"></i>
      <h5 class="text-muted">No leads yet</h5>
      <p class="text-muted" style="font-size:.88rem;">
        @if(request()->anyFilled(['search','status','lead_type','project_id','heat']))
          No leads match your filters. <a href="{{ route('builder.leads.index') }}">Clear filters</a>
        @else
          Leads from your project pages will appear here.
        @endif
      </p>
    </div>
    @endif
  </div>

</div>

{{-- ════════════════════════════════════════════════════════════════════════
     LEAD DETAIL DRAWER
════════════════════════════════════════════════════════════════════════ --}}
<div id="lead-drawer-backdrop"></div>
<div id="lead-drawer">

  <div class="ld-header">
    <div class="d-flex align-items-center justify-content-between mb-1">
      <span id="ld-name" style="font-size:1.1rem;font-weight:700;"></span>
      <button onclick="closeDrawer()" style="background:none;border:none;color:rgba(255,255,255,.8);font-size:1.4rem;cursor:pointer;line-height:1;">&times;</button>
    </div>
    <div style="font-size:.78rem;opacity:.8;" id="ld-meta"></div>
    <div class="d-flex align-items-center gap-10 mt-3" style="gap:10px;">
      <div id="ld-heat-ring" class="ld-heat-ring"></div>
      <div>
        <div style="font-size:.72rem;opacity:.8;">Lead Heat Score</div>
        <div id="ld-score-bar" style="width:140px;height:6px;background:rgba(255,255,255,.2);border-radius:3px;margin-top:4px;">
          <div id="ld-score-fill" style="height:100%;border-radius:3px;transition:width .5s;"></div>
        </div>
      </div>
    </div>
  </div>

  <div class="ld-body">

    {{-- Quick CTAs --}}
    <div class="d-flex gap-2 mb-4">
      <a id="ld-call-link" href="#" class="btn btn-sm btn-success flex-fill" style="border-radius:8px;font-weight:600;">
        <i class="bi bi-telephone-fill me-1"></i>Call
      </a>
      <a id="ld-wa-link" href="#" target="_blank" class="btn btn-sm flex-fill" style="background:#25d366;color:#fff;border-radius:8px;font-weight:600;">
        <i class="bi bi-whatsapp me-1"></i>WhatsApp
      </a>
      <a id="ld-email-link" href="#" class="btn btn-sm btn-outline-secondary flex-fill" style="border-radius:8px;font-weight:600;">
        <i class="bi bi-envelope me-1"></i>Email
      </a>
    </div>

    {{-- Lead info grid --}}
    <div class="row g-3 mb-4">
      <div class="col-6">
        <div class="ld-label">Project</div>
        <div class="ld-value" id="ld-project">—</div>
      </div>
      <div class="col-6">
        <div class="ld-label">Lead Type</div>
        <div class="ld-value" id="ld-type">—</div>
      </div>
      <div class="col-12" id="ld-message-wrap">
        <div class="ld-label">Message</div>
        <div class="ld-value" id="ld-message" style="font-size:.82rem;color:#475569;background:#f8fafc;padding:8px 10px;border-radius:7px;"></div>
      </div>
      <div class="col-6">
        <div class="ld-label">Received</div>
        <div class="ld-value" style="font-size:.78rem;" id="ld-received">—</div>
      </div>
      <div class="col-6">
        <div class="ld-label">Status</div>
        <div class="ld-value" id="ld-status-display">—</div>
      </div>
    </div>

    {{-- Notes + Follow-up --}}
    <div class="ld-section">
      <div class="ld-label">Notes & Follow-up Date</div>
      <textarea id="ld-notes-input" class="form-control form-control-sm mb-2" rows="3" placeholder="Add notes about this lead…" style="font-size:.83rem;"></textarea>
      <input type="datetime-local" id="ld-followup-input" class="form-control form-control-sm mb-2" style="font-size:.83rem;">
      <button onclick="saveNotes()" class="btn btn-sm btn-primary w-100" style="border-radius:7px;font-weight:600;" id="ld-save-notes-btn">
        <i class="bi bi-floppy me-1"></i>Save Notes & Follow-up
      </button>
    </div>

    {{-- Log a Call --}}
    <div class="ld-section">
      <div class="ld-label">Log a Call</div>
      <div class="d-flex gap-2 mb-2">
        <input type="text" id="ld-call-note-input" class="form-control form-control-sm" placeholder="What happened on this call?" style="font-size:.83rem;">
        <button onclick="logCall()" class="btn btn-sm btn-outline-primary" style="white-space:nowrap;font-weight:600;border-radius:7px;">
          <i class="bi bi-plus-circle me-1"></i>Log
        </button>
      </div>
      <div id="ld-call-log-list"></div>
    </div>

  </div>
</div>

<script>
// ── CSRF token helper ──────────────────────────────────────────────────────
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

// ── Status change via AJAX ─────────────────────────────────────────────────
document.querySelectorAll('.status-changer').forEach(function(sel) {
  sel.addEventListener('change', function() {
    const leadId = this.dataset.leadId;
    const status = this.value;
    fetch(`/builder/leads/${leadId}/status`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
      body: JSON.stringify({ status })
    }).then(r => r.json()).then(data => {
      if (data.success) {
        const row = this.closest('tr');
        row.style.background = '#f0fdf4';
        setTimeout(() => row.style.background = '', 1200);
      }
    });
  });
});

// ── Drawer state ───────────────────────────────────────────────────────────
let currentLeadId   = null;
let currentNotesUrl = null;
let currentCallUrl  = null;

function openDrawer(btn) {
  currentLeadId   = btn.dataset.id;
  currentNotesUrl = btn.dataset.notesUrl;
  currentCallUrl  = btn.dataset.calllogUrl;

  const score = parseInt(btn.dataset.score) || 0;
  const heat  = btn.dataset.heat;
  const heatLabel = { hot:'🔥 Hot', warm:'🟡 Warm', cold:'🔵 Cold' }[heat] ?? 'Cold';
  const ringClass  = { hot:'ring-hot', warm:'ring-warm', cold:'ring-cold' }[heat] ?? 'ring-cold';
  const fillColor  = { hot:'#ef4444', warm:'#f59e0b', cold:'#94a3b8' }[heat] ?? '#94a3b8';

  document.getElementById('ld-name').textContent   = btn.dataset.name;
  document.getElementById('ld-meta').textContent   = btn.dataset.phone + (btn.dataset.email ? ' · ' + btn.dataset.email : '');
  document.getElementById('ld-project').textContent  = btn.dataset.project;
  document.getElementById('ld-type').textContent     = btn.dataset.type;
  document.getElementById('ld-received').textContent = btn.dataset.received;
  document.getElementById('ld-status-display').textContent = btn.dataset.status.charAt(0).toUpperCase() + btn.dataset.status.slice(1);

  const msg = btn.dataset.message;
  const msgWrap = document.getElementById('ld-message-wrap');
  document.getElementById('ld-message').textContent = msg;
  msgWrap.style.display = msg ? '' : 'none';

  document.getElementById('ld-notes-input').value   = btn.dataset.notes ?? '';
  document.getElementById('ld-followup-input').value = btn.dataset.followup ?? '';

  // Heat ring
  const ring = document.getElementById('ld-heat-ring');
  ring.className = 'ld-heat-ring ' + ringClass;
  ring.textContent = score;

  // Score bar
  document.getElementById('ld-score-fill').style.width      = score + '%';
  document.getElementById('ld-score-fill').style.background = fillColor;

  // Quick-dial links
  const phone = btn.dataset.phone.replace(/[^0-9]/g, '');
  document.getElementById('ld-call-link').href  = 'tel:' + btn.dataset.phone;
  document.getElementById('ld-wa-link').href    = 'https://wa.me/91' + phone;
  document.getElementById('ld-email-link').href = btn.dataset.email ? 'mailto:' + btn.dataset.email : '#';
  document.getElementById('ld-email-link').style.opacity = btn.dataset.email ? '1' : '.4';

  // Call log
  renderCallLog(JSON.parse(btn.dataset.calllog || '[]'));

  document.getElementById('lead-drawer').classList.add('open');
  document.getElementById('lead-drawer-backdrop').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeDrawer() {
  document.getElementById('lead-drawer').classList.remove('open');
  document.getElementById('lead-drawer-backdrop').classList.remove('open');
  document.body.style.overflow = '';
}

document.getElementById('lead-drawer-backdrop').addEventListener('click', closeDrawer);

document.querySelectorAll('.open-drawer-btn').forEach(function(btn) {
  btn.addEventListener('click', function() { openDrawer(this); });
});

// ── Render call log ────────────────────────────────────────────────────────
function renderCallLog(entries) {
  const list = document.getElementById('ld-call-log-list');
  if (!entries || !entries.length) {
    list.innerHTML = '<p style="font-size:.75rem;color:#94a3b8;margin:0;">No calls logged yet.</p>';
    return;
  }
  list.innerHTML = entries.slice().reverse().map(function(e) {
    const d = new Date(e.at);
    const timeStr = d.toLocaleDateString('en-IN', { day:'2-digit', month:'short', hour:'2-digit', minute:'2-digit' });
    const dur = e.duration ? ' · ' + Math.floor(e.duration/60) + 'm ' + (e.duration%60) + 's' : '';
    return `<div class="call-log-item">
      <div class="call-log-time"><i class="bi bi-telephone me-1"></i>${timeStr}${dur}</div>
      <div class="call-log-note">${e.note}</div>
    </div>`;
  }).join('');
}

// ── Save notes ─────────────────────────────────────────────────────────────
function saveNotes() {
  if (!currentNotesUrl) return;
  const btn = document.getElementById('ld-save-notes-btn');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving…';

  fetch(currentNotesUrl, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify({
      notes:        document.getElementById('ld-notes-input').value,
      follow_up_at: document.getElementById('ld-followup-input').value || null,
    })
  })
  .then(r => r.json())
  .then(data => {
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Saved!';
    setTimeout(() => { btn.innerHTML = '<i class="bi bi-floppy me-1"></i>Save Notes & Follow-up'; }, 2000);
  })
  .catch(() => {
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-floppy me-1"></i>Save Notes & Follow-up';
  });
}

// ── Log a call ─────────────────────────────────────────────────────────────
function logCall() {
  if (!currentCallUrl) return;
  const input = document.getElementById('ld-call-note-input');
  const note = input.value.trim();
  if (!note) { input.focus(); return; }

  fetch(currentCallUrl, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify({ note })
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      input.value = '';
      renderCallLog(data.call_log);
      // Update hot score on ring
      const ring = document.getElementById('ld-heat-ring');
      ring.textContent = data.hot_score;
      document.getElementById('ld-score-fill').style.width = data.hot_score + '%';
    }
  });
}
</script>
@endsection
