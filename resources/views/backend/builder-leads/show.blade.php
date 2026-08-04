@extends('backend.layout')
@section('title', 'Lead Details')
@section('content')

<div class="d-flex align-items-center mb-3">
    <a href="{{ route('admin.builder-leads.index') }}" class="btn btn-sm btn-outline-secondary me-3">
        <i data-feather="arrow-left" style="width:14px;height:14px;"></i> Back
    </a>
    <h4 class="mb-0"><i data-feather="user-plus" class="me-2 text-primary"></i>Lead Details</h4>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@php $ltColors = ['general'=>'primary','visit'=>'success','callback'=>'warning','brochure'=>'info','whatsapp'=>'secondary','call_click'=>'danger','whatsapp_click'=>'success']; @endphp

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">{{ $lead->name ?: 'Caller details not captured yet' }}</h5>
                    <span class="badge bg-{{ $ltColors[$lead->lead_type] ?? 'secondary' }} fs-6">{{ ucfirst(str_replace('_', ' ', $lead->lead_type)) }}</span>
                </div>

                <table class="table table-bordered">
                    <tr><th style="width:150px;">Name</th><td>{{ $lead->name ?: '– not captured yet –' }}</td></tr>
                    <tr><th>Phone</th><td>
                        @if($lead->phone)
                            <a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a>
                        @else – not captured yet – @endif
                    </td></tr>
                    <tr><th>Email</th><td>{{ $lead->email ?: '–' }}</td></tr>
                    <tr><th>Message</th><td style="white-space:pre-wrap;">{{ $lead->message ?: '–' }}</td></tr>
                    <tr><th>Property</th><td>
                        @if($lead->property)
                            <a href="{{ route('admin.properties.show', $lead->property_id) }}">
                                {{ $lead->property->title }}
                            </a>
                        @else – @endif
                    </td></tr>
                    <tr><th>Project</th><td>
                        @if($lead->project)
                            <a href="{{ route('admin.builder-projects.show', $lead->builder_project_id) }}">
                                {{ $lead->project->title }}
                            </a>
                        @else – @endif
                    </td></tr>
                    <tr><th>Builder</th><td>
                        @if($lead->builder)
                            <a href="{{ route('admin.builders.show', $lead->builder_id) }}">
                                {{ $lead->builder->company_name ?? $lead->builder->name }}
                            </a>
                        @else – @endif
                    </td></tr>
                    <tr><th>Lead Type</th><td><span class="badge bg-{{ $ltColors[$lead->lead_type] ?? 'secondary' }}">{{ ucfirst(str_replace('_', ' ', $lead->lead_type)) }}</span></td></tr>
                    <tr><th>Source</th><td>{{ $lead->source }}</td></tr>
                    <tr><th>Notes</th><td style="white-space:pre-wrap;">{{ $lead->notes ?: '–' }}</td></tr>
                    <tr><th>IP Address</th><td><code>{{ $lead->ip_address ?: '–' }}</code></td></tr>
                    <tr><th>User Agent</th><td style="word-break:break-all;font-size:0.78rem;">{{ $lead->user_agent ?: '–' }}</td></tr>
                    <tr><th>Current Status</th><td>
                        @if($lead->status === 'new') <span class="badge bg-info text-dark">New</span>
                        @elseif($lead->status === 'contacted') <span class="badge bg-warning text-dark">Contacted</span>
                        @elseif($lead->status === 'converted') <span class="badge bg-success">Converted</span>
                        @else <span class="badge bg-danger">Lost</span>
                        @endif
                    </td></tr>
                    <tr><th>Received At</th><td>{{ $lead->created_at?->format('d M Y, H:i A') }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        {{-- Complete Caller Details (for click-tracked leads with no captured identity yet) --}}
        @if(in_array($lead->lead_type, ['call_click', 'whatsapp_click']))
        <div class="card shadow-sm mb-3 border-danger">
            <div class="card-body">
                <h6 class="fw-semibold mb-1">Log This Call</h6>
                <p class="text-muted small mb-3">Fill this in right after speaking to the caller — this is the record that proves the lead came through IndianEstHub.</p>
                <form action="{{ route('admin.builder-leads.update', $lead->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Caller Name</label>
                        <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', $lead->name) }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Phone Number</label>
                        <input type="text" name="phone" class="form-control form-control-sm" value="{{ old('phone', $lead->phone) }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control form-control-sm" value="{{ old('email', $lead->email) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Notes</label>
                        <textarea name="notes" class="form-control form-control-sm" rows="3" placeholder="What did they want, next steps, etc.">{{ old('notes', $lead->notes) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-danger btn-sm w-100">Save Caller Details</button>
                </form>
            </div>
        </div>
        @endif

        {{-- Update Status --}}
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Update Status</h6>
                <form action="{{ route('admin.builder-leads.update-status', $lead->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">New Status</label>
                        <select name="status" class="form-select">
                            @foreach(['new','contacted','converted','lost'] as $s)
                                <option value="{{ $s }}" {{ $lead->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
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
                <form action="{{ route('admin.builder-leads.destroy', $lead->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm w-100"
                            onclick="return confirm('Delete this lead permanently?')">
                        Delete Lead
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
