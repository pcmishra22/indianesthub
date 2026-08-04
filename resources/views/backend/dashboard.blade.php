@extends('backend.layout')
@section('title', 'Dashboard')

@section('head')
<style>
    .kpi-card { border-left: 4px solid; border-radius: 8px; transition: transform .12s ease, box-shadow .12s ease; }
    .kpi-card.blue    { border-color: #3b82f6; }
    .kpi-card.green   { border-color: #10b981; }
    .kpi-card.amber   { border-color: #f59e0b; }
    .kpi-card.purple  { border-color: #8b5cf6; }
    .kpi-card.teal    { border-color: #14b8a6; }
    .kpi-card.red     { border-color: #ef4444; }
    .kpi-card:hover   { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,.08) !important; }
    .kpi-val   { font-size: 1.9rem; font-weight: 700; line-height: 1; }
    .kpi-sub   { font-size: .8rem; color: #6b7280; }
    .kpi-today { font-size: .76rem; }
    .section-title { font-size: 1rem; font-weight: 600; color: #1e3a5f; border-bottom: 2px solid #dbeafe; padding-bottom: 6px; margin-bottom: 14px; }
    .activity-item { display: flex; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
    .activity-item:last-child { border-bottom: none; }
    .activity-icon { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .activity-icon i { width: 16px; height: 16px; }
    .pending-item { display: flex; justify-content: space-between; align-items: center; padding: 9px 0; border-bottom: 1px solid #f1f5f9; }
    .pending-item:last-child { border-bottom: none; }
    a.plain-link { color: inherit; text-decoration: none; }
    a.plain-link:hover { color: #3b82f6; }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0"><strong>Analytics</strong> Dashboard</h1>
        <small class="text-muted">{{ now()->format('d M Y, H:i') }}</small>
    </div>

    {{-- ── Totals (all-time) ──────────────────────────────────────────────── --}}
    <div class="row g-3 mb-2">
        <div class="col-6 col-lg">
            <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
                <div class="card kpi-card blue h-100 shadow-sm">
                    <div class="card-body py-3">
                        <div class="kpi-sub">Users</div>
                        <div class="kpi-val text-primary">{{ number_format($totals['users']) }}</div>
                        <div class="kpi-today text-success">+{{ $today_counts['users'] }} today</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg">
            <a href="{{ route('admin.properties.index') }}" class="text-decoration-none">
                <div class="card kpi-card purple h-100 shadow-sm">
                    <div class="card-body py-3">
                        <div class="kpi-sub">Properties</div>
                        <div class="kpi-val" style="color:#8b5cf6;">{{ number_format($totals['properties']) }}</div>
                        <div class="kpi-today text-success">+{{ $today_counts['properties'] }} today</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg">
            <a href="{{ route('admin.builders.index') }}" class="text-decoration-none">
                <div class="card kpi-card amber h-100 shadow-sm">
                    <div class="card-body py-3">
                        <div class="kpi-sub">Builders</div>
                        <div class="kpi-val text-warning">{{ number_format($totals['builders']) }}</div>
                        <div class="kpi-today text-success">+{{ $today_counts['builders'] }} today</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg">
            <a href="{{ route('admin.dealers.index') }}" class="text-decoration-none">
                <div class="card kpi-card green h-100 shadow-sm">
                    <div class="card-body py-3">
                        <div class="kpi-sub">Dealers</div>
                        <div class="kpi-val text-success">{{ number_format($totals['dealers']) }}</div>
                        <div class="kpi-today text-success">+{{ $today_counts['dealers'] }} today</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg">
            <a href="{{ route('admin.service-providers.index') }}" class="text-decoration-none">
                <div class="card kpi-card teal h-100 shadow-sm">
                    <div class="card-body py-3">
                        <div class="kpi-sub">Service Providers</div>
                        <div class="kpi-val" style="color:#14b8a6;">{{ number_format($totals['service_providers']) }}</div>
                        <div class="kpi-today text-success">+{{ $today_counts['service_providers'] }} today</div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- ── Leads / inquiries today ────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <a href="{{ route('admin.inquiries.index', ['type' => 'property']) }}" class="text-decoration-none">
                <div class="card kpi-card blue h-100 shadow-sm">
                    <div class="card-body py-3">
                        <div class="kpi-sub">Property Inquiries Today</div>
                        <div class="kpi-val text-primary">{{ number_format($today_counts['inquiries']) }}</div>
                        <div class="kpi-today text-muted">{{ number_format($week_counts['inquiries']) }} this week &middot; {{ number_format($month_counts['inquiries']) }} this month</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a href="{{ route('admin.builder-leads.index') }}" class="text-decoration-none">
                <div class="card kpi-card green h-100 shadow-sm">
                    <div class="card-body py-3">
                        <div class="kpi-sub">Builder Leads Today</div>
                        <div class="kpi-val text-success">{{ number_format($today_counts['builder_leads']) }}</div>
                        <div class="kpi-today text-muted">{{ number_format($week_counts['builder_leads']) }} this week &middot; {{ number_format($month_counts['builder_leads']) }} this month</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a href="{{ route('admin.leads-report.index') }}" class="text-decoration-none">
                <div class="card kpi-card purple h-100 shadow-sm">
                    <div class="card-body py-3">
                        <div class="kpi-sub">Full Leads &amp; Analytics Report</div>
                        <div class="kpi-val" style="color:#8b5cf6; font-size:1.1rem;">View traffic, views &amp; visitor breakdown <i data-feather="arrow-right" style="width:16px;height:16px;"></i></div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a href="{{ route('admin.properties.index') }}" class="text-decoration-none">
                <div class="card kpi-card amber h-100 shadow-sm">
                    <div class="card-body py-3">
                        <div class="kpi-sub">Properties Added</div>
                        <div class="kpi-val text-warning" style="font-size:1.3rem;">{{ number_format($week_counts['properties']) }} <small class="text-muted" style="font-size:.7rem;">this week</small></div>
                        <div class="kpi-today text-muted">{{ number_format($month_counts['properties']) }} this month</div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-3">
        {{-- ── Recent Activity ────────────────────────────────────────────── --}}
        <div class="col-xl-7">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="section-title">Recent Activity</div>
                    @forelse($recentActivity as $item)
                        <a href="{{ $item['url'] }}" class="plain-link">
                            <div class="activity-item">
                                <div class="activity-icon bg-{{ $item['color'] }} bg-opacity-10 text-{{ $item['color'] }}">
                                    <i data-feather="{{ $item['icon'] }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small">{{ $item['title'] }}</div>
                                    <div class="text-muted" style="font-size:.72rem;">{{ $item['created_at']?->diffForHumans() }}</div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="text-muted small mb-0">No recent activity yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ── Needs Attention ────────────────────────────────────────────── --}}
        <div class="col-xl-5">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="section-title">Needs Attention</div>

                    <div class="pending-item">
                        <span><i data-feather="user-check" style="width:15px;height:15px;" class="me-2 text-muted"></i>Service providers pending approval</span>
                        <a href="{{ route('admin.service-providers.index', ['status' => 'pending']) }}" class="badge bg-{{ $pending['service_providers'] > 0 ? 'warning text-dark' : 'light text-dark' }}">{{ $pending['service_providers'] }}</a>
                    </div>
                    <div class="pending-item">
                        <span><i data-feather="star" style="width:15px;height:15px;" class="me-2 text-muted"></i>Reviews pending approval</span>
                        <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="badge bg-{{ $pending['reviews'] > 0 ? 'warning text-dark' : 'light text-dark' }}">{{ $pending['reviews'] }}</a>
                    </div>
                    <div class="pending-item">
                        <span><i data-feather="message-square" style="width:15px;height:15px;" class="me-2 text-muted"></i>Unread contact messages</span>
                        <a href="{{ route('admin.contacts.index') }}" class="badge bg-{{ $pending['contacts_new'] > 0 ? 'info text-dark' : 'light text-dark' }}">{{ $pending['contacts_new'] }}</a>
                    </div>
                    <div class="pending-item">
                        <span><i data-feather="credit-card" style="width:15px;height:15px;" class="me-2 text-muted"></i>Payments awaiting approval</span>
                        <a href="{{ route('admin.payments.index') }}" class="badge bg-{{ $pending['payments_pending'] > 0 ? 'danger' : 'light text-dark' }}">{{ $pending['payments_pending'] }}</a>
                    </div>

                    <div class="mt-4">
                        <div class="section-title" style="border:none; margin-bottom:8px;">Quick Links</div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.properties.create') }}" class="btn btn-sm btn-outline-primary"><i data-feather="plus" style="width:13px;height:13px;"></i> Property</a>
                            <a href="{{ route('admin.leads-report.index') }}" class="btn btn-sm btn-outline-secondary"><i data-feather="bar-chart-2" style="width:13px;height:13px;"></i> Leads Report</a>
                            <a href="{{ route('admin.inquiries.index') }}" class="btn btn-sm btn-outline-secondary"><i data-feather="inbox" style="width:13px;height:13px;"></i> All Inquiries</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    if (typeof feather !== 'undefined') { feather.replace(); }
</script>
@endsection
