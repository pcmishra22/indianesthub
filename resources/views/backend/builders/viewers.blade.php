@extends('backend.layout')

@section('title', 'Builder Viewers')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">👁️ Viewers for: {{ $builder->company_name ?: $builder->name }}</h4>
    <a href="{{ route('admin.builders.show', $builder->id) }}" class="btn btn-sm btn-outline-secondary">Back</a>
</div>

<div class="row g-2 mb-3">
    <div class="col-12 col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Page Views</div>
                <div class="fs-4 fw-bold text-primary">{{ number_format($builderViews->total()) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label small mb-0">From</label>
                <input type="date" name="from" class="form-control form-control-sm" value="{{ $from }}">
            </div>
            <div class="col-auto">
                <label class="form-label small mb-0">To</label>
                <input type="date" name="to" class="form-control form-control-sm" value="{{ $to }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-primary btn-sm">Apply</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Viewed At</th>
                        <th>IP</th>
                        <th>Device</th>
                        <th>Browser</th>
                        <th>Referrer</th>
                        <th>Session</th>
                        <th>User</th>
                        <th>Enquiry (Name/Phone)</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($builderViews as $v)
                    @php
                        $token = $v->visitor_token;
                        $leads = ($token && isset($leadsByToken[$token])) ? $leadsByToken[$token] : collect();
                        $firstLead = $leads->first();
                    @endphp
                    <tr>
                        <td class="text-nowrap">{{ $v->viewed_at?->format('d M Y H:i') }}</td>
                        <td><code>{{ $v->ip_address ?: '—' }}</code></td>
                        <td>{{ $v->device ?: '—' }}</td>
                        <td>{{ $v->browser ?: '—' }}</td>
                        <td class="small">
                            @if($v->referrer)
                                <span title="{{ $v->referrer }}">{{ \Str::limit($v->referrer, 30) }}</span>
                            @else
                                —
                            @endif
                        </td>
                        <td class="text-muted" style="font-size:0.75rem;">{{ substr($v->session_id ?? '', 0, 16) }}{{ $v->session_id ? '…' : '' }}</td>
                        <td>
                            @if($v->user_id)
                                <span class="badge bg-success">Logged In</span>
                            @else
                                <span class="badge bg-secondary">Guest</span>
                            @endif
                        </td>
                        <td>
                            @if($firstLead)
                                <div class="fw-medium">{{ $firstLead->name }}</div>
                                <div class="small text-muted">📞 {{ $firstLead->phone ?: '—' }} · {{ ucfirst($firstLead->lead_type) }}</div>
                            @else
                                <span class="text-muted">No enquiry</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">No viewers yet for this builder.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($builderViews->hasPages())
        <div class="mt-3">{{ $builderViews->links() }}</div>
        @endif
    </div>
</div>
@endsection
