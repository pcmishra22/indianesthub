@extends('backend.layout')

@section('title', 'Property Viewers')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">👁️ Viewers for: {{ $property->title }}</h4>
    <a href="{{ route('admin.properties.show', $property) }}" class="btn btn-sm btn-outline-secondary">Back</a>
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
                        <th>Inquiry (Name/Phone)</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($propertyViews as $v)
                    @php
                        $token = $v->visitor_token;
                        $inqs = ($token && isset($inquiriesByToken[$token])) ? $inquiriesByToken[$token] : collect();
                        $firstInq = $inqs->first();
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
                            @if($firstInq)
                                <div class="fw-medium">{{ $firstInq->name }}</div>
                                <div class="small text-muted">📞 {{ $firstInq->phone ?: '—' }}</div>
                                <a href="{{ route('admin.properties.viewers.index', ['property'=>$property->id]) }}?token={{ $token }}" class="small">Linked inquiry</a>
                            @else
                                <span class="text-muted">No inquiry</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">No viewers yet for this property.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

