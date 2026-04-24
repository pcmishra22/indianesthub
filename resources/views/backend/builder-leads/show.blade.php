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

@php $ltColors = ['general'=>'primary','visit'=>'success','callback'=>'warning','brochure'=>'info','whatsapp'=>'secondary']; @endphp

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">{{ $lead->name }}</h5>
                    <span class="badge bg-{{ $ltColors[$lead->lead_type] ?? 'secondary' }} fs-6">{{ ucfirst($lead->lead_type) }}</span>
                </div>

                <table class="table table-bordered">
                    <tr><th style="width:150px;">Name</th><td>{{ $lead->name }}</td></tr>
                    <tr><th>Phone</th><td><a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a></td></tr>
                    <tr><th>Email</th><td>{{ $lead->email ?: '–' }}</td></tr>
                    <tr><th>Message</th><td style="white-space:pre-wrap;">{{ $lead->message ?: '–' }}</td></tr>
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
                    <tr><th>Lead Type</th><td><span class="badge bg-{{ $ltColors[$lead->lead_type] ?? 'secondary' }}">{{ ucfirst($lead->lead_type) }}</span></td></tr>
                    <tr><th>Source</th><td>{{ $lead->source }}</td></tr>
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
