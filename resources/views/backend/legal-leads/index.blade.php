@extends('backend.layout')
@section('title', 'Legal Help Leads')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0"><i class="fas fa-balance-scale me-2 text-primary"></i>Legal Help Leads</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.legal-leads.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
           class="btn btn-sm btn-success">
            <i class="fas fa-download me-1"></i> Export CSV
        </a>
        <span class="badge bg-primary fs-6">{{ $leads->total() }} total</span>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    @php
        $statCards = [
            ['label' => 'Total',       'value' => $stats['total'],     'color' => 'primary', 'icon' => 'users'],
            ['label' => 'New',         'value' => $stats['new'],       'color' => 'info',    'icon' => 'bell'],
            ['label' => 'Contacted',   'value' => $stats['contacted'], 'color' => 'warning', 'icon' => 'phone'],
            ['label' => 'Scheduled',   'value' => $stats['scheduled'], 'color' => 'primary', 'icon' => 'calendar'],
            ['label' => 'Resolved',    'value' => $stats['resolved'],  'color' => 'success', 'icon' => 'check-circle'],
        ];
    @endphp
    @foreach($statCards as $sc)
    <div class="col-6 col-sm-4 col-lg-2-4">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="card-body p-2">
                <div class="fw-bold fs-4 text-{{ $sc['color'] }}">{{ $sc['value'] }}</div>
                <div class="text-muted small">{{ $sc['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filter Bar --}}
<div class="card shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">

            <div class="col-auto">
                <label class="form-label mb-1 small fw-semibold">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    @foreach(\App\Models\LegalLead::statusOptions() as $val => $label)
                        <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto">
                <label class="form-label mb-1 small fw-semibold">Issue Type</label>
                <select name="legal_issue_type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    @foreach(\App\Models\LegalLead::issueTypeOptions() as $val => $label)
                        <option value="{{ $val }}" {{ request('legal_issue_type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto">
                <label class="form-label mb-1 small fw-semibold">From Date</label>
                <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
            </div>

            <div class="col-auto">
                <label class="form-label mb-1 small fw-semibold">To Date</label>
                <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
            </div>

            <div class="col-auto">
                <label class="form-label mb-1 small fw-semibold">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Name / Phone / City" value="{{ request('search') }}">
            </div>

            <div class="col-auto d-flex gap-1">
                <button class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('admin.legal-leads.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">#</th>
                    <th>Applicant</th>
                    <th>Issue Type</th>
                    <th>City</th>
                    <th>Preferred Date</th>
                    <th>Source</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                <tr>
                    <td class="ps-3 text-muted small">{{ $lead->id }}</td>
                    <td>
                        <div class="fw-semibold">{{ $lead->name }}</div>
                        <a href="tel:{{ $lead->phone }}" class="text-primary small">
                            <i class="fas fa-phone-alt me-1" style="font-size:0.7rem;"></i>{{ $lead->phone }}
                        </a>
                        @if($lead->email)
                            <div class="text-muted small">{{ $lead->email }}</div>
                        @endif
                        @if($lead->property)
                            <div class="mt-1"><span class="badge bg-light text-dark border small">{{ Str::limit($lead->property->title, 30) }}</span></div>
                        @elseif($lead->builderProject)
                            <div class="mt-1"><span class="badge bg-light text-dark border small">{{ Str::limit($lead->builderProject->title, 30) }}</span></div>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ $lead->issueTypeBadgeColor() }}">{{ $lead->issueTypeLabel() }}</span>
                        @if($lead->description)
                            <div class="text-muted small mt-1" style="max-width:180px;">{{ Str::limit($lead->description, 60) }}</div>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $lead->city ?? '—' }}</td>
                    <td class="text-muted small text-nowrap">
                        {{ $lead->preferred_date ? $lead->preferred_date->format('d M Y') : '—' }}
                    </td>
                    <td>
                        <span class="badge bg-light text-secondary border small">{{ ucwords(str_replace('-', ' ', $lead->source)) }}</span>
                    </td>
                    <td>
                        <form action="{{ route('admin.legal-leads.update-status', $lead->id) }}" method="POST">
                            @csrf
                            <select name="status" class="form-select form-select-sm border-0 p-1 fw-semibold
                                @switch($lead->status)
                                    @case('new')                    text-primary @break
                                    @case('contacted')              text-warning @break
                                    @case('consultation_scheduled') text-info    @break
                                    @case('resolved')               text-success @break
                                    @case('closed')                 text-secondary @break
                                @endswitch
                            " style="min-width:160px;" onchange="this.form.submit()">
                                @foreach(\App\Models\LegalLead::statusOptions() as $val => $label)
                                    <option value="{{ $val }}" {{ $lead->status === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="text-muted small text-nowrap">
                        {{ $lead->created_at->format('d M Y') }}<br>
                        {{ $lead->created_at->format('H:i') }}
                    </td>
                    <td class="text-end pe-3">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.legal-leads.show', $lead->id) }}"
                               class="btn btn-sm btn-outline-primary" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.legal-leads.destroy', $lead->id) }}" method="POST"
                                  onsubmit="return confirm('Delete this legal lead?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-5">
                        <i class="fas fa-balance-scale fa-2x mb-2 d-block opacity-25"></i>
                        No legal help requests found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($leads->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">Showing {{ $leads->firstItem() }}–{{ $leads->lastItem() }} of {{ $leads->total() }}</small>
        {{ $leads->links() }}
    </div>
    @endif
</div>

@endsection
