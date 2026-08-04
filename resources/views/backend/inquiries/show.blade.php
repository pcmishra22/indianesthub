@extends('backend.layout')
@section('title', 'Inquiry Details')
@section('content')

<div class="d-flex align-items-center mb-3">
    <a href="{{ route('admin.inquiries.index') }}" class="btn btn-sm btn-outline-secondary me-3">
        <i data-feather="arrow-left" style="width:14px;height:14px;"></i> Back
    </a>
    <h4 class="mb-0"><i data-feather="mail" class="me-2 text-primary"></i>Inquiry Details</h4>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">{{ $inquiry->name }}</h5>
                    @php $s = strtolower($inquiry->status ?: 'new'); @endphp
                    @if($s === 'new') <span class="badge bg-info text-dark fs-6">New</span>
                    @elseif($s === 'contacted') <span class="badge bg-warning text-dark fs-6">Contacted</span>
                    @elseif($s === 'converted') <span class="badge bg-success fs-6">Converted</span>
                    @else <span class="badge bg-secondary fs-6">{{ ucfirst($s) }}</span>
                    @endif
                </div>

                <table class="table table-bordered">
                    <tr><th style="width:150px;">Name</th><td>{{ $inquiry->name }}</td></tr>
                    <tr><th>Phone</th><td>
                        @if($inquiry->phone)
                            <a href="tel:{{ $inquiry->phone }}">{{ $inquiry->phone }}</a>
                        @else – @endif
                    </td></tr>
                    <tr><th>Email</th><td>
                        @if($inquiry->email)
                            <a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a>
                        @else – @endif
                    </td></tr>
                    <tr><th>Message</th><td style="white-space:pre-wrap;">{{ $inquiry->message ?: '–' }}</td></tr>
                    <tr><th>Property</th><td>
                        @if($inquiry->property)
                            <a href="{{ route('property-details', $inquiry->property->slug) }}" target="_blank">
                                {{ $inquiry->property->title }}
                            </a>
                            <a href="{{ route('admin.properties.show', $inquiry->property->id) }}" class="ms-2 small">(admin view)</a>
                        @else – @endif
                    </td></tr>
                    <tr><th>Broker / Dealer</th><td>
                        @if($inquiry->broker)
                            <a href="{{ route('admin.dealers.show', $inquiry->broker->id) }}">
                                {{ $inquiry->broker->company_name ?: trim($inquiry->broker->first_name.' '.$inquiry->broker->last_name) }}
                            </a>
                        @else – @endif
                    </td></tr>
                    <tr><th>Source</th><td>{{ $inquiry->source ?: 'website' }}</td></tr>
                    <tr><th>Lead Type</th><td>{{ $inquiry->lead_type ? ucfirst($inquiry->lead_type) : '–' }}</td></tr>
                    <tr><th>Notes</th><td style="white-space:pre-wrap;">{{ $inquiry->notes ?: '–' }}</td></tr>
                    <tr><th>IP Address</th><td><code>{{ $inquiry->ip_address ?: '–' }}</code></td></tr>
                    <tr><th>User Agent</th><td style="word-break:break-all;font-size:0.78rem;">{{ $inquiry->user_agent ?: '–' }}</td></tr>
                    <tr><th>Received At</th><td>{{ $inquiry->created_at?->format('d M Y, H:i A') }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        {{-- Update Status --}}
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Update Status</h6>
                <form action="{{ route('admin.inquiries.update-status', $inquiry->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">New Status</label>
                        <select name="status" class="form-select">
                            @foreach(['new','contacted','converted','lost'] as $opt)
                                <option value="{{ $opt }}" {{ $s === $opt ? 'selected' : '' }}>{{ ucfirst($opt) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </form>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="card border-danger shadow-sm">
            <div class="card-body">
                <h6 class="text-danger mb-2">Danger Zone</h6>
                <form action="{{ route('admin.inquiries.destroy', $inquiry->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm w-100"
                            onclick="return confirm('Delete this inquiry permanently?')">
                        Delete Inquiry
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
