@extends('backend.layout')
@section('title', 'Insurance Leads')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0"><i class="fas fa-shield-alt me-2 text-success"></i>Insurance Leads</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.insurance-leads.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
           class="btn btn-sm btn-success">
            <i class="fas fa-download me-1"></i> Export CSV
        </a>
    </div>
</div>

{{-- Stats bar --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fw-bold fs-4 text-dark">{{ $stats['total'] }}</div>
            <div class="small text-muted">Total</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center py-3" style="border-left:3px solid #3b82f6!important;">
            <div class="fw-bold fs-4 text-primary">{{ $stats['new'] }}</div>
            <div class="small text-muted">New</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center py-3" style="border-left:3px solid #f59e0b!important;">
            <div class="fw-bold fs-4 text-warning">{{ $stats['contacted'] }}</div>
            <div class="small text-muted">Contacted</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center py-3" style="border-left:3px solid #06b6d4!important;">
            <div class="fw-bold fs-4 text-info">{{ $stats['quoted'] }}</div>
            <div class="small text-muted">Quoted</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center py-3" style="border-left:3px solid #22c55e!important;">
            <div class="fw-bold fs-4 text-success">{{ $stats['converted'] }}</div>
            <div class="small text-muted">Converted</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center py-3" style="border-left:3px solid #16a34a!important;">
            <div class="fw-bold fs-4 text-success">
                ₹{{ number_format($stats['revenue']) }}
            </div>
            <div class="small text-muted">Commission</div>
        </div>
    </div>
</div>

{{-- Filter bar --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.insurance-leads.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-2">
                <label class="form-label small mb-1">Status</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    @foreach(\App\Models\InsuranceLead::statusOptions() as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label small mb-1">Insurance Type</label>
                <select name="insurance_type" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Types</option>
                    @foreach(\App\Models\InsuranceLead::insuranceTypeOptions() as $val => $label)
                    <option value="{{ $val }}" {{ request('insurance_type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label small mb-1">Source</label>
                <select name="source" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Sources</option>
                    @foreach(['website','property-page','project-page','loan-bundle','post-visit','possession'] as $src)
                    <option value="{{ $src }}" {{ request('source') === $src ? 'selected' : '' }}>{{ ucwords(str_replace('-',' ',$src)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small mb-1">From</label>
                <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small mb-1">To</label>
                <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label small mb-1">Search</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Name / Phone / City"
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" style="font-size:.85rem;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Applicant</th>
                        <th>Property Value</th>
                        <th>Insurance Type</th>
                        <th>Source</th>
                        <th>Bundle</th>
                        <th>Premium</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($leads as $lead)
                <tr>
                    <td class="text-muted">{{ $lead->id }}</td>
                    <td>
                        <div class="fw-semibold">{{ $lead->name }}</div>
                        <div class="text-muted small">{{ $lead->phone }}</div>
                        @if($lead->email)
                        <div class="text-muted small">{{ $lead->email }}</div>
                        @endif
                        @if($lead->property_city)
                        <span class="badge bg-light text-dark mt-1" style="font-size:.68rem;">
                            📍 {{ $lead->property_city }}
                        </span>
                        @endif
                        @if($lead->property)
                        <div><a href="{{ route('admin.properties.show', $lead->property) }}" class="small text-primary" target="_blank">
                            🏠 {{ Str::limit($lead->property->title, 30) }}</a></div>
                        @elseif($lead->builderProject)
                        <div><span class="small text-info">🏗️ {{ Str::limit($lead->builderProject->title, 30) }}</span></div>
                        @endif
                    </td>
                    <td>
                        @if($lead->property_value)
                        <div class="fw-semibold">{{ $lead->formattedPropertyValue() }}</div>
                        @if($lead->property_type)
                        <div class="text-muted small">{{ $lead->property_type }}</div>
                        @endif
                        @else
                        <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge"
                            style="background:
                            @if($lead->insurance_type==='both') #7c3aed
                            @elseif($lead->insurance_type==='content') #0369a1
                            @elseif($lead->insurance_type==='fire') #dc2626
                            @else #16a34a @endif;
                            color:#fff;font-size:.72rem;">
                            {{ $lead->insuranceTypeLabel() }}
                        </span>
                        @if($lead->coverage_amount)
                        <div class="text-muted small mt-1">
                            Cover: {{ $lead->coverage_amount >= 10000000 ? '₹'.number_format($lead->coverage_amount/10000000,2).'Cr' : '₹'.number_format($lead->coverage_amount/100000,1).'L' }}
                        </div>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-light text-dark" style="font-size:.72rem;">
                            {{ ucwords(str_replace('-',' ',$lead->source)) }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($lead->loan_lead_id)
                        <span class="badge bg-primary" style="font-size:.7rem;" title="Bundled with loan lead #{{ $lead->loan_lead_id }}">
                            🏦 Loan Bundle
                        </span>
                        @else
                        <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td>
                        @if($lead->premium_quoted)
                        <div class="fw-semibold text-success">₹{{ number_format($lead->premium_quoted) }}/yr</div>
                        @elseif($lead->property_value)
                        <div class="text-muted small">~₹{{ number_format($lead->estimatedPremium()) }}/yr</div>
                        @else
                        <span class="text-muted">—</span>
                        @endif
                        @if($lead->commission_earned)
                        <div class="small text-success fw-semibold">+₹{{ number_format($lead->commission_earned) }}</div>
                        @endif
                    </td>
                    <td>
                        <form method="POST"
                              action="{{ route('admin.insurance-leads.update-status', $lead) }}"
                              class="d-inline">
                            @csrf
                            <select name="status" class="form-select form-select-sm"
                                    style="min-width:120px;font-size:.78rem;"
                                    onchange="this.form.submit()">
                                @foreach(\App\Models\InsuranceLead::statusOptions() as $val => $label)
                                <option value="{{ $val }}" {{ $lead->status === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="text-muted small">{{ $lead->created_at->format('d M Y') }}<br>{{ $lead->created_at->format('h:i A') }}</td>
                    <td>
                        <a href="{{ route('admin.insurance-leads.show', $lead) }}"
                           class="btn btn-sm btn-outline-primary">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">
                        <i class="fas fa-shield-alt fa-2x mb-2 d-block opacity-25"></i>
                        No insurance leads found.
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pagination --}}
<div class="mt-3">
    {{ $leads->links() }}
</div>

@endsection
