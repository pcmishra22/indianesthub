@extends('backend.layout')
@section('title', 'Leads & Analytics Report')

@section('head')
<style>
    .kpi-card { border-left: 4px solid; border-radius: 8px; }
    .kpi-card.blue   { border-color: #3b82f6; }
    .kpi-card.green  { border-color: #10b981; }
    .kpi-card.purple { border-color: #8b5cf6; }
    .kpi-card.orange { border-color: #f59e0b; }
    .kpi-val  { font-size: 2rem; font-weight: 700; line-height: 1; }
    .kpi-sub  { font-size: 0.78rem; color: #6b7280; }
    .section-title { font-size: 1rem; font-weight: 600; color: #1e3a5f; border-bottom: 2px solid #dbeafe; padding-bottom: 6px; margin-bottom: 14px; }
    .badge-device { font-size: 0.72rem; padding: 3px 8px; border-radius: 20px; }
    .badge-mobile  { background:#dbeafe; color:#1d4ed8; }
    .badge-tablet  { background:#fce7f3; color:#be185d; }
    .badge-desktop { background:#d1fae5; color:#065f46; }
    .badge-other   { background:#f3f4f6; color:#6b7280; }
    .visit-row td { font-size: 0.82rem; }
    .filter-bar { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px 18px; margin-bottom: 20px; }
    .progress-bar-custom { height: 8px; border-radius: 4px; }
    .chart-wrap { position: relative; height: 220px; }
    .tab-pane .table th { font-size: 0.78rem; text-transform: uppercase; color: #6b7280; }
    .tab-pane .table td { font-size: 0.83rem; vertical-align: middle; }
    .jump-to-tab { display: block; cursor: pointer; }
    .jump-to-tab .card { transition: transform .12s ease, box-shadow .12s ease; }
    .jump-to-tab:hover .card { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,.08) !important; }
</style>
@endsection

@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0"><i data-feather="bar-chart-2" class="me-2 text-primary"></i>Leads &amp; Analytics Report</h4>
    <small class="text-muted">Period: {{ $from->format('d M Y') }} – {{ $to->format('d M Y') }}</small>
</div>

{{-- ── Filter Bar ─────────────────────────────────────────────────────────── --}}
<div class="filter-bar">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-auto">
            <label class="form-label mb-0 small fw-semibold">From</label>
            <input type="date" name="from" class="form-control form-control-sm" value="{{ request('from', $from->format('Y-m-d')) }}">
        </div>
        <div class="col-auto">
            <label class="form-label mb-0 small fw-semibold">To</label>
            <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to', $to->format('Y-m-d')) }}">
        </div>
        <div class="col-auto">
            <button class="btn btn-primary btn-sm px-3">Apply</button>
            <a href="{{ route('admin.leads-report.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
        </div>
        {{-- Quick shortcuts --}}
        <div class="col-auto ms-auto d-flex gap-1 flex-wrap">
            <a href="?from={{ now()->format('Y-m-d') }}&to={{ now()->format('Y-m-d') }}" class="btn btn-outline-secondary btn-sm">Today</a>
            <a href="?from={{ now()->startOfWeek()->format('Y-m-d') }}&to={{ now()->format('Y-m-d') }}" class="btn btn-outline-secondary btn-sm">This Week</a>
            <a href="?from={{ now()->startOfMonth()->format('Y-m-d') }}&to={{ now()->format('Y-m-d') }}" class="btn btn-outline-secondary btn-sm">This Month</a>
            <a href="?from={{ now()->subDays(29)->format('Y-m-d') }}&to={{ now()->format('Y-m-d') }}" class="btn btn-outline-secondary btn-sm">Last 30d</a>
        </div>
    </form>
</div>

{{-- ── KPI Cards ────────────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card kpi-card blue h-100 shadow-sm">
            <div class="card-body py-3">
                <div class="kpi-sub">Property Inquiries</div>
                <div class="kpi-val text-primary">{{ number_format($kpi['inquiries_total']) }}</div>
                <small class="text-muted">Today: <strong>{{ $kpi['inquiries_today'] }}</strong></small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card kpi-card green h-100 shadow-sm">
            <div class="card-body py-3">
                <div class="kpi-sub">Builder Leads</div>
                <div class="kpi-val text-success">{{ number_format($kpi['builder_leads_total']) }}</div>
                <small class="text-muted">Today: <strong>{{ $kpi['builder_leads_today'] }}</strong></small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <a href="#tab-visitor-log" class="text-decoration-none jump-to-tab" data-tab="#tab-visitor-log">
            <div class="card kpi-card purple h-100 shadow-sm">
                <div class="card-body py-3">
                    <div class="kpi-sub">Property Views <i data-feather="arrow-down-circle" style="width:12px;height:12px;" class="text-muted"></i></div>
                    <div class="kpi-val" style="color:#8b5cf6;">{{ number_format($kpi['property_views']) }}</div>
                    <small class="text-muted">Today: <strong>{{ $kpi['property_views_today'] }}</strong> &middot; click to see the pages</small>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a href="#tab-visitor-log" class="text-decoration-none jump-to-tab" data-tab="#tab-visitor-log">
            <div class="card kpi-card orange h-100 shadow-sm">
                <div class="card-body py-3">
                    <div class="kpi-sub">Unique Visitors <i data-feather="arrow-down-circle" style="width:12px;height:12px;" class="text-muted"></i></div>
                    <div class="kpi-val text-warning">{{ number_format($kpi['unique_visitors']) }}</div>
                    <small class="text-muted">By session &middot; click to see the list</small>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- ── Chart Row ────────────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="section-title">Daily Activity (Last 14 Days)</div>
                <div class="chart-wrap">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="section-title">Device Breakdown</div>
                <div class="chart-wrap" style="height:180px;">
                    <canvas id="deviceChart"></canvas>
                </div>
                <div class="mt-3">
                    @foreach($deviceBreakdown as $device => $count)
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="badge-device badge-{{ strtolower($device ?: 'other') }}">{{ ucfirst($device ?: 'Unknown') }}</span>
                        <div class="flex-grow-1 mx-2">
                            <div class="progress progress-bar-custom">
                                <div class="progress-bar bg-primary" style="width:{{ $kpi['property_views'] > 0 ? round($count/$kpi['property_views']*100) : 0 }}%"></div>
                            </div>
                        </div>
                        <span class="small text-muted">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Secondary Stats Row ──────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    {{-- Browser Breakdown --}}
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="section-title">Browser Breakdown</div>
                @forelse($browserBreakdown as $browser => $count)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-medium">{{ $browser ?: 'Unknown' }}</span>
                    <div class="flex-grow-1 mx-2">
                        <div class="progress" style="height:6px;border-radius:3px;">
                            <div class="progress-bar bg-info" style="width:{{ $kpi['property_views'] > 0 ? round($count/$kpi['property_views']*100) : 0 }}%"></div>
                        </div>
                    </div>
                    <span class="badge bg-light text-dark">{{ $count }}</span>
                </div>
                @empty
                <p class="text-muted small">No data yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Lead Type Breakdown (Builder) --}}
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="section-title">Builder Lead Types</div>
                @php
                $ltColors = ['general'=>'primary','visit'=>'success','callback'=>'warning','brochure'=>'info','whatsapp'=>'secondary'];
                @endphp
                @forelse($leadTypeBreakdown as $type => $count)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-{{ $ltColors[$type] ?? 'secondary' }}">{{ ucfirst($type) }}</span>
                    <div class="flex-grow-1 mx-2">
                        <div class="progress" style="height:6px;border-radius:3px;">
                            <div class="progress-bar bg-{{ $ltColors[$type] ?? 'secondary' }}"
                                 style="width:{{ $kpi['builder_leads_total'] > 0 ? round($count/$kpi['builder_leads_total']*100) : 0 }}%">
                            </div>
                        </div>
                    </div>
                    <span class="badge bg-light text-dark">{{ $count }}</span>
                </div>
                @empty
                <p class="text-muted small">No builder leads yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Top Cities --}}
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="section-title">Top Cities by Inquiries</div>
                @forelse($topCities as $i => $row)
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-primary me-2">{{ $i+1 }}</span>
                    <span class="flex-grow-1 small">{{ $row->city ?: 'Unknown' }}</span>
                    <span class="badge bg-light text-dark">{{ $row->total }}</span>
                </div>
                @empty
                <p class="text-muted small">No data yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ── Most Viewed Properties ───────────────────────────────────────────────── --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="section-title">🔥 Most Viewed Properties</div>
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Property</th>
                        <th>City</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Views (period)</th>
                        <th>Total Views</th>
                        <th>Inquiries</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($topProperties as $i => $prop)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>
                            <a href="{{ route('property-details', $prop->slug) }}" target="_blank" class="text-decoration-none fw-medium">
                                {{ \Str::limit($prop->title, 40) }}
                            </a>
                        </td>
                        <td>{{ $prop->city }}</td>
                        <td><span class="badge bg-secondary">{{ $prop->property_type }}</span></td>
                        <td>₹{{ number_format($prop->price) }}</td>
                        <td>
                            <a href="{{ route('admin.properties.viewers.index', ['property' => $prop->id, 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}"
                               class="text-decoration-none fw-bold text-primary"
                               title="See who viewed this property">
                                {{ number_format($prop->view_count) }}
                            </a>
                        </td>
                        <td>{{ number_format($prop->views_count) }}</td>
                        <td>{{ number_format($prop->inquiries_count) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">No data yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── Tabs: Inquiries / Builder Leads / Visitor Log ───────────────────────── --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <ul class="nav nav-tabs mb-3" id="leadTabs">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-inquiries">
                    <i data-feather="mail" style="width:14px;height:14px;" class="me-1"></i>
                    Property Inquiries
                    <span class="badge bg-primary ms-1">{{ $kpi['inquiries_total'] }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-builder-leads">
                    <i data-feather="layers" style="width:14px;height:14px;" class="me-1"></i>
                    Builder Leads
                    <span class="badge bg-success ms-1">{{ $kpi['builder_leads_total'] }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-visitor-log">
                    <i data-feather="eye" style="width:14px;height:14px;" class="me-1"></i>
                    Visitor Log
                    <span class="badge bg-secondary ms-1">{{ $kpi['property_views'] }}</span>
                </button>
            </li>
        </ul>

        <div class="tab-content">

            {{-- ── Property Inquiries ──────────────────────────────────────── --}}
            <div class="tab-pane fade show active" id="tab-inquiries">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-2">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Property</th>
                                <th>Message</th>
                                <th>IP</th>
                                <th>Source</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($inquiries as $inq)
                            <tr>
                                <td>{{ $inq->id }}</td>
                                <td class="fw-medium">{{ $inq->name }}</td>
                                <td>{{ $inq->phone ?: '–' }}</td>
                                <td>{{ $inq->email }}</td>
                                <td>
                                    @if($inq->property)
                                        <a href="{{ route('property-details', $inq->property->slug) }}" target="_blank" class="text-decoration-none">
                                            {{ \Str::limit($inq->property->title, 30) }}
                                        </a>
                                    @else
                                        <span class="text-muted">–</span>
                                    @endif
                                </td>
                                <td>{{ \Str::limit($inq->message, 40) }}</td>
                                <td><code>{{ $inq->ip_address ?: '–' }}</code></td>
                                <td><span class="badge bg-light text-dark">{{ $inq->source ?: 'website' }}</span></td>
                                <td>
                                    @php $s = $inq->status ?: 'new'; @endphp
                                    @if($s === 'new') <span class="badge bg-info text-dark">New</span>
                                    @elseif($s === 'contacted') <span class="badge bg-warning text-dark">Contacted</span>
                                    @elseif($s === 'converted') <span class="badge bg-success">Converted</span>
                                    @else <span class="badge bg-secondary">{{ ucfirst($s) }}</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">{{ $inq->created_at?->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center text-muted">No inquiries found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $inquiries->appends(request()->query())->links() }}
            </div>

            {{-- ── Builder Leads ───────────────────────────────────────────── --}}
            <div class="tab-pane fade" id="tab-builder-leads">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-2">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Project</th>
                                <th>Builder</th>
                                <th>Lead Type</th>
                                <th>Source</th>
                                <th>IP</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($builderLeads as $lead)
                            <tr>
                                <td>{{ $lead->id }}</td>
                                <td class="fw-medium">{{ $lead->name }}</td>
                                <td>{{ $lead->phone }}</td>
                                <td>{{ $lead->email ?: '–' }}</td>
                                <td>{{ $lead->project?->title ?? '–' }}</td>
                                <td>{{ $lead->builder?->company_name ?? $lead->builder?->name ?? '–' }}</td>
                                <td>
                                    @php $ltColors = ['general'=>'primary','visit'=>'success','callback'=>'warning','brochure'=>'info','whatsapp'=>'secondary']; @endphp
                                    <span class="badge bg-{{ $ltColors[$lead->lead_type] ?? 'secondary' }}">{{ ucfirst($lead->lead_type) }}</span>
                                </td>
                                <td><span class="badge bg-light text-dark">{{ $lead->source }}</span></td>
                                <td><code>{{ $lead->ip_address ?: '–' }}</code></td>
                                <td>
                                    @if($lead->status === 'new') <span class="badge bg-info text-dark">New</span>
                                    @elseif($lead->status === 'contacted') <span class="badge bg-warning text-dark">Contacted</span>
                                    @elseif($lead->status === 'converted') <span class="badge bg-success">Converted</span>
                                    @elseif($lead->status === 'lost') <span class="badge bg-danger">Lost</span>
                                    @else <span class="badge bg-secondary">{{ ucfirst($lead->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">{{ $lead->created_at?->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="11" class="text-center text-muted">No builder leads found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $builderLeads->appends(request()->query())->links() }}
            </div>

            {{-- ── Visitor Log ─────────────────────────────────────────────── --}}
            <div class="tab-pane fade" id="tab-visitor-log">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle mb-0 visit-row">
                        <thead class="table-light">
                            <tr>
                                <th>Time</th>
                                <th>Property</th>
                                <th>IP Address</th>
                                <th>Device</th>
                                <th>Browser</th>
                                <th>Referrer</th>
                                <th>User</th>
                                <th>Session</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($recentVisits as $visit)
                            <tr>
                                <td class="text-nowrap">{{ $visit->viewed_at?->format('d M H:i') }}</td>
                                <td>
                                    @if($visit->property)
                                        <a href="{{ route('property-details', $visit->property->slug) }}" target="_blank" class="text-decoration-none">
                                            {{ \Str::limit($visit->property->title, 35) }}
                                        </a>
                                    @else
                                        <span class="text-muted">–</span>
                                    @endif
                                </td>
                                <td><code>{{ $visit->ip_address ?: '–' }}</code></td>
                                <td>
                                    @php $d = strtolower($visit->device ?: 'other'); @endphp
                                    <span class="badge-device badge-{{ $d }}">{{ ucfirst($visit->device ?: 'Unknown') }}</span>
                                </td>
                                <td>{{ $visit->browser ?: '–' }}</td>
                                <td>
                                    @if($visit->referrer)
                                        <span title="{{ $visit->referrer }}">{{ \Str::limit($visit->referrer, 30) }}</span>
                                    @else
                                        <span class="text-muted">Direct</span>
                                    @endif
                                </td>
                                <td>
                                    @if($visit->user_id)
                                        <span class="badge bg-success">Logged In</span>
                                    @else
                                        <span class="badge bg-light text-dark">Guest</span>
                                    @endif
                                </td>
                                <td><code class="text-muted" style="font-size:0.68rem;">{{ substr($visit->session_id ?? '', 0, 12) }}…</code></td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted">No visitor data yet. Visit a property page to start tracking.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>{{-- end tab-content --}}
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── Jump-to-tab stat cards: activate the right tab and scroll it into view ──
function activateReportTab(targetSelector) {
    const btn = document.querySelector(`#leadTabs button[data-bs-target="${targetSelector}"]`);
    if (!btn) return;
    bootstrap.Tab.getOrCreateInstance(btn).show();
    document.querySelector('#leadTabs').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

document.querySelectorAll('.jump-to-tab').forEach(function (el) {
    el.addEventListener('click', function (e) {
        e.preventDefault();
        activateReportTab(this.dataset.tab);
    });
});

// Support deep-linking / bookmarking a direct link to a tab, e.g. ...#tab-visitor-log
if (window.location.hash) {
    activateReportTab(window.location.hash);
}

// ── Activity Line Chart ────────────────────────────────────────────────────
const ctx1 = document.getElementById('activityChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [
            {
                label: 'Property Views',
                data: @json($chartViews),
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139,92,246,0.1)',
                tension: 0.3,
                fill: true,
                pointRadius: 3,
            },
            {
                label: 'Inquiries',
                data: @json($chartInqs),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.1)',
                tension: 0.3,
                fill: true,
                pointRadius: 3,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'top', labels: { font: { size: 11 } } } },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0, font: { size: 10 } } },
            x: { ticks: { font: { size: 10 } } }
        }
    }
});

// ── Device Doughnut Chart ─────────────────────────────────────────────────
@php
    $devLabels = $deviceBreakdown->keys()->map(fn($d) => ucfirst($d ?: 'Unknown'))->values()->toArray();
    $devData   = $deviceBreakdown->values()->toArray();
@endphp
const ctx2 = document.getElementById('deviceChart').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: @json($devLabels),
        datasets: [{
            data: @json($devData),
            backgroundColor: ['#3b82f6','#f59e0b','#10b981','#6b7280'],
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 10 }, padding: 8 } }
        },
        cutout: '65%',
    }
});

// Re-init feather icons for tab content
document.querySelectorAll('[data-bs-toggle="tab"]').forEach(el => {
    el.addEventListener('shown.bs.tab', () => { if (typeof feather !== 'undefined') feather.replace(); });
});
</script>
@endsection
