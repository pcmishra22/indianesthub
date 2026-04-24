@extends('builder.layout')
@section('title', 'Leads – Builder Panel')

@section('content')
<div class="container-fluid p-0">

  {{-- Page Header --}}
  <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
      <h1 class="h3 mb-1">Leads & Enquiries</h1>
      <p class="text-muted mb-0">Manage all incoming leads from your project pages</p>
    </div>
  </div>

  {{-- Flash message --}}
  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  {{-- Stats cards --}}
  <div class="row g-3 mb-4">
    @php
      $statCards = [
        ['label' => 'Total Leads',  'val' => $stats['total'],     'icon' => 'bi-people-fill',          'color' => '#6366f1'],
        ['label' => 'New',          'val' => $stats['new'],        'icon' => 'bi-envelope-open-fill',   'color' => '#3b82f6'],
        ['label' => 'Contacted',    'val' => $stats['contacted'],  'icon' => 'bi-telephone-fill',       'color' => '#f59e0b'],
        ['label' => 'Converted',    'val' => $stats['converted'],  'icon' => 'bi-check-circle-fill',    'color' => '#22c55e'],
      ];
    @endphp
    @foreach($statCards as $s)
    <div class="col-xl-3 col-sm-6">
      <div class="card border-0 shadow-sm">
        <div class="card-body d-flex align-items-center gap-3">
          <div style="width:48px;height:48px;border-radius:12px;background:{{ $s['color'] }}20;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi {{ $s['icon'] }}" style="font-size:1.3rem;color:{{ $s['color'] }};"></i>
          </div>
          <div>
            <div style="font-size:1.5rem;font-weight:800;color:#1e293b;line-height:1;">{{ $s['val'] }}</div>
            <div style="font-size:.78rem;color:#94a3b8;margin-top:2px;">{{ $s['label'] }}</div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>

  {{-- Filters --}}
  <div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
      <form action="{{ route('builder.leads.index') }}" method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
          <label class="form-label form-label-sm mb-1">Project</label>
          <select name="project_id" class="form-select form-select-sm">
            <option value="">All Projects</option>
            @foreach($projects as $proj)
            <option value="{{ $proj->id }}" {{ request('project_id') == $proj->id ? 'selected' : '' }}>
              {{ $proj->title }}
            </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label form-label-sm mb-1">Lead Type</label>
          <select name="lead_type" class="form-select form-select-sm">
            <option value="">All Types</option>
            <option value="general"  {{ request('lead_type') === 'general'  ? 'selected' : '' }}>General Enquiry</option>
            <option value="visit"    {{ request('lead_type') === 'visit'    ? 'selected' : '' }}>Site Visit</option>
            <option value="callback" {{ request('lead_type') === 'callback' ? 'selected' : '' }}>Callback</option>
            <option value="brochure" {{ request('lead_type') === 'brochure' ? 'selected' : '' }}>Brochure</option>
            <option value="whatsapp" {{ request('lead_type') === 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label form-label-sm mb-1">Status</label>
          <select name="status" class="form-select form-select-sm">
            <option value="">All Status</option>
            <option value="new"       {{ request('status') === 'new'       ? 'selected' : '' }}>New</option>
            <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
            <option value="converted" {{ request('status') === 'converted' ? 'selected' : '' }}>Converted</option>
            <option value="lost"      {{ request('status') === 'lost'      ? 'selected' : '' }}>Lost</option>
          </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
          <button type="submit" class="btn btn-primary btn-sm w-100">
            <i class="bi bi-funnel me-1"></i>Filter
          </button>
          @if(request()->anyFilled(['status','lead_type','project_id']))
          <a href="{{ route('builder.leads.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-x"></i>
          </a>
          @endif
        </div>
      </form>
    </div>
  </div>

  {{-- Leads Table --}}
  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      @if($leads->count())
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem;">
          <thead style="background:#f8fafc;">
            <tr>
              <th class="px-4 py-3" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">Lead</th>
              <th class="py-3" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">Contact</th>
              <th class="py-3" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">Project</th>
              <th class="py-3" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">Type</th>
              <th class="py-3" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">Date</th>
              <th class="py-3" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">Status</th>
              <th class="py-3" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;color:#64748b;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($leads as $lead)
            @php
              $statusConfig = [
                'new'       => ['New',       'primary'],
                'contacted' => ['Contacted', 'warning'],
                'converted' => ['Converted', 'success'],
                'lost'      => ['Lost',      'secondary'],
              ];
              $sc = $statusConfig[$lead->status] ?? ['Unknown', 'secondary'];
              $typeIcons = [
                'general'  => ['bi-chat-dots',           'General'],
                'visit'    => ['bi-calendar-check',      'Site Visit'],
                'callback' => ['bi-telephone-inbound',   'Callback'],
                'brochure' => ['bi-file-earmark-pdf',    'Brochure'],
                'whatsapp' => ['bi-whatsapp',            'WhatsApp'],
              ];
              $ti = $typeIcons[$lead->lead_type] ?? ['bi-question-circle', ucfirst($lead->lead_type)];
            @endphp
            <tr>
              <td class="px-4 py-3">
                <div style="font-weight:600;color:#1e293b;">{{ $lead->name }}</div>
                @if($lead->message)
                <div style="font-size:.75rem;color:#94a3b8;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                     title="{{ $lead->message }}">
                  {{ $lead->message }}
                </div>
                @endif
              </td>
              <td class="py-3">
                @if($lead->phone)
                <a href="tel:{{ $lead->phone }}" class="d-block text-decoration-none" style="color:#1e293b;font-size:.82rem;">
                  <i class="bi bi-telephone me-1 text-success"></i>{{ $lead->phone }}
                </a>
                @endif
                @if($lead->email)
                <a href="mailto:{{ $lead->email }}" class="d-block text-decoration-none" style="color:#64748b;font-size:.78rem;">
                  <i class="bi bi-envelope me-1"></i>{{ $lead->email }}
                </a>
                @endif
              </td>
              <td class="py-3">
                @if($lead->project)
                <a href="{{ route('builder.projects.show', $lead->builder_project_id) }}"
                   style="font-size:.82rem;color:#6366f1;font-weight:500;text-decoration:none;">
                  {{ $lead->project->title }}
                </a>
                @else
                <span class="text-muted" style="font-size:.82rem;">General</span>
                @endif
              </td>
              <td class="py-3">
                <span style="display:inline-flex;align-items:center;gap:5px;font-size:.78rem;font-weight:500;color:#475569;">
                  <i class="bi {{ $ti[0] }}"></i> {{ $ti[1] }}
                </span>
              </td>
              <td class="py-3">
                <span style="font-size:.78rem;color:#94a3b8;">
                  {{ $lead->created_at->format('d M Y') }}<br>
                  <span style="font-size:.72rem;">{{ $lead->created_at->format('h:i A') }}</span>
                </span>
              </td>
              <td class="py-3">
                <form action="{{ route('builder.leads.update-status', $lead) }}" method="POST">
                  @csrf @method('PATCH')
                  <select name="status" class="form-select form-select-sm" style="font-size:.78rem;min-width:110px;"
                          onchange="this.form.submit()">
                    <option value="new"       {{ $lead->status === 'new'       ? 'selected' : '' }}>🔵 New</option>
                    <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>🟡 Contacted</option>
                    <option value="converted" {{ $lead->status === 'converted' ? 'selected' : '' }}>🟢 Converted</option>
                    <option value="lost"      {{ $lead->status === 'lost'      ? 'selected' : '' }}>⚫ Lost</option>
                  </select>
                </form>
              </td>
              <td class="py-3">
                @if($lead->phone)
                <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $lead->phone) }}"
                   target="_blank" class="btn btn-sm btn-success me-1" title="WhatsApp" style="width:32px;height:32px;padding:0;display:inline-flex;align-items:center;justify-content:center;">
                  <i class="bi bi-whatsapp" style="font-size:.85rem;"></i>
                </a>
                @endif
                <form action="{{ route('builder.leads.destroy', $lead) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Delete this lead?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger"
                          style="width:32px;height:32px;padding:0;display:inline-flex;align-items:center;justify-content:center;">
                    <i class="bi bi-trash" style="font-size:.85rem;"></i>
                  </button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      <div class="px-4 py-3 border-top">
        {{ $leads->links() }}
      </div>

      @else
      <div class="text-center py-5">
        <i class="bi bi-inbox" style="font-size:3rem;color:#cbd5e1;display:block;margin-bottom:12px;"></i>
        <h5 class="text-muted">No leads yet</h5>
        <p class="text-muted" style="font-size:.88rem;">
          @if(request()->anyFilled(['status','lead_type','project_id']))
            No leads match your filters.
            <a href="{{ route('builder.leads.index') }}">Clear filters</a>
          @else
            Leads from your public project pages will appear here.
          @endif
        </p>
      </div>
      @endif
    </div>
  </div>

</div>
@endsection
