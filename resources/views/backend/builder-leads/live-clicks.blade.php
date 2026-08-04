@extends('backend.layout')
@section('title', 'Live Call Clicks')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="mb-0"><i data-feather="phone-call" class="me-2 text-danger"></i>Live Call Clicks</h4>
        <p class="text-muted small mb-0">Anyone who tapped Call or WhatsApp in the last while, newest first. Check this when the office phone rings to see who's likely calling and about which property.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-danger fs-6">{{ $leads->total() }} awaiting contact</span>
        <a href="{{ route('admin.builder-leads.index') }}" class="btn btn-outline-secondary btn-sm">All Leads</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-3">
    @forelse($leads as $lead)
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm h-100 {{ $lead->lead_type === 'call_click' ? 'border-danger' : 'border-success' }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge {{ $lead->lead_type === 'call_click' ? 'bg-danger' : 'bg-success' }}">
                        <i class="align-middle" data-feather="{{ $lead->lead_type === 'call_click' ? 'phone' : 'message-circle' }}" style="width:12px;height:12px;"></i>
                        {{ $lead->lead_type === 'call_click' ? 'Call Click' : 'WhatsApp Click' }}
                    </span>
                    <small class="text-muted" title="{{ $lead->created_at }}">{{ $lead->created_at?->diffForHumans() }}</small>
                </div>

                <div class="mb-2">
                    <div class="fw-semibold small text-muted">Property</div>
                    @if($lead->property)
                        <a href="{{ route('admin.properties.show', $lead->property_id) }}" class="text-decoration-none">
                            {{ \Str::limit($lead->property->title, 40) }}
                        </a>
                    @else
                        <span class="text-muted">–</span>
                    @endif
                </div>

                <div class="mb-2">
                    <div class="fw-semibold small text-muted">Builder</div>
                    @if($lead->builder)
                        {{ $lead->builder->company_name ?? $lead->builder->name }}
                    @else
                        <span class="text-muted">–</span>
                    @endif
                </div>

                <div class="row g-1 mb-2 small text-muted">
                    <div class="col-6"><i data-feather="smartphone" style="width:12px;height:12px;"></i> {{ ucfirst($lead->device ?? 'unknown') }}</div>
                    <div class="col-6"><code style="font-size:0.7rem;">{{ $lead->ip_address ?: '–' }}</code></div>
                </div>

                @if($lead->name || $lead->phone)
                    <div class="alert alert-light border py-1 px-2 mb-2 small">
                        Known: {{ $lead->name ?: 'Unnamed' }} @if($lead->phone) — {{ $lead->phone }} @endif
                    </div>
                @endif

                <form action="{{ route('admin.builder-leads.update', $lead->id) }}" method="POST" class="mt-2">
                    @csrf
                    @method('PUT')
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text">Name</span>
                        <input type="text" name="name" class="form-control" value="{{ $lead->name }}" placeholder="Caller name">
                    </div>
                    <div class="input-group input-group-sm mb-2">
                        <span class="input-group-text">Phone</span>
                        <input type="text" name="phone" class="form-control" value="{{ $lead->phone }}" placeholder="Caller phone">
                    </div>
                    <div class="d-flex gap-1">
                        <button type="submit" class="btn btn-sm btn-primary flex-grow-1">Save &amp; Mark Contacted</button>
                        <a href="{{ route('admin.builder-leads.show', $lead->id) }}" class="btn btn-sm btn-outline-secondary">Details</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body text-center text-muted py-5">
                No pending call/WhatsApp clicks right now. New ones will show up here the moment someone taps Call on a builder's property.
            </div>
        </div>
    </div>
    @endforelse
</div>

@if($leads->hasPages())
<div class="mt-3">
    {{ $leads->links() }}
</div>
@endif

@endsection

@push('scripts')
<script>
    // Auto-refresh so staff always see the latest clicks without manually
    // reloading — this page is meant to be left open on a spare monitor or
    // tab next to the phone.
    setTimeout(function () {
        window.location.reload();
    }, 30000);
</script>
@endpush
