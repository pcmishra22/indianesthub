@extends('backend.layout')
@section('title', 'Property Management Lead — ' . $lead->name)
@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.property-management-leads.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i>
    </a>
    <h4 class="mb-0"><i class="fas fa-key me-2 text-primary"></i>Property Management Lead #{{ $lead->id }}</h4>
    <span class="badge bg-{{ $lead->statusBadge() }} ms-1 fs-6">
        {{ ucwords(str_replace('-', ' ', $lead->status)) }}
    </span>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">

    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold bg-transparent">
                <i class="fas fa-user me-2 text-primary"></i>Owner Details
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        <tr><td class="text-muted fw-medium ps-3" style="width:200px;">Name</td><td class="fw-semibold">{{ $lead->name }}</td></tr>
                        <tr><td class="text-muted fw-medium ps-3">Phone</td>
                            <td><a href="tel:{{ $lead->phone }}" class="text-primary fw-semibold">{{ $lead->phone }}</a>
                                <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $lead->phone) }}" target="_blank" class="btn btn-sm btn-success ms-2 py-0 px-2" style="font-size:0.72rem;">
                                    <i class="fab fa-whatsapp"></i> WhatsApp
                                </a>
                            </td>
                        </tr>
                        <tr><td class="text-muted fw-medium ps-3">Email</td><td>{{ $lead->email ?: '—' }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-header fw-semibold bg-transparent">
                <i class="fas fa-building me-2 text-success"></i>Property & Service Details
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        <tr><td class="text-muted fw-medium ps-3" style="width:200px;">Service Requested</td>
                            <td class="fw-bold text-primary">{{ $lead->serviceTypeLabel() }}</td></tr>
                        <tr><td class="text-muted fw-medium ps-3">Property Type</td><td>{{ $lead->property_type ?: '—' }}</td></tr>
                        <tr><td class="text-muted fw-medium ps-3">City</td><td>{{ $lead->city ?: '—' }}</td></tr>
                        <tr><td class="text-muted fw-medium ps-3">Number of Properties</td><td>{{ $lead->num_properties ?: '—' }}</td></tr>
                        <tr><td class="text-muted fw-medium ps-3">Currently Rented?</td>
                            <td>{{ $lead->currently_rented ? 'Yes' : 'No' }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-header fw-semibold bg-transparent">
                <i class="fas fa-map-marker-alt me-2 text-warning"></i>Source & Context
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        <tr><td class="text-muted fw-medium ps-3" style="width:200px;">Source</td>
                            <td><span class="badge bg-light text-dark border">{{ ucwords(str_replace('-', ' ', $lead->source)) }}</span></td></tr>
                        <tr><td class="text-muted fw-medium ps-3">Source Page</td>
                            <td><small class="text-muted">{{ $lead->source_page ?: '—' }}</small></td></tr>
                        @if($lead->property)
                        <tr><td class="text-muted fw-medium ps-3">Property</td>
                            <td><a href="{{ route('property-details', $lead->property->slug) }}" target="_blank" class="text-primary">
                                {{ $lead->property->title }}</a></td></tr>
                        @endif
                        @if($lead->builderProject)
                        <tr><td class="text-muted fw-medium ps-3">Builder Project</td>
                            <td>{{ $lead->builderProject->title }}</td></tr>
                        @endif
                        <tr><td class="text-muted fw-medium ps-3">IP Address</td>
                            <td><small>{{ $lead->ip_address ?: '—' }}</small></td></tr>
                        <tr><td class="text-muted fw-medium ps-3">Received</td>
                            <td>{{ $lead->created_at->format('d M Y, h:i A') }}
                                <small class="text-muted">({{ $lead->created_at->diffForHumans() }})</small></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">

        <div class="card shadow-sm mb-3">
            <div class="card-header fw-semibold bg-transparent">Update Status</div>
            <div class="card-body">
                <form action="{{ route('admin.property-management-leads.update-status', $lead->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium small">Status</label>
                        <select name="status" class="form-select">
                            @foreach(\App\Models\PropertyManagementLead::statusOptions() as $value => $label)
                                <option value="{{ $value }}" {{ $lead->status === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium small">Internal Notes</label>
                        <textarea name="notes" class="form-control" rows="4"
                                  placeholder="Add notes about this lead…">{{ $lead->notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-header fw-semibold bg-transparent">Quick Actions</div>
            <div class="card-body d-grid gap-2">
                <a href="tel:{{ $lead->phone }}" class="btn btn-outline-primary">
                    <i class="fas fa-phone-alt me-2"></i>Call Owner
                </a>
                <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $lead->phone) }}?text={{ urlencode('Hi ' . $lead->name . ', I am calling regarding your property management inquiry. Let me know a convenient time to connect.') }}"
                   target="_blank" class="btn btn-success">
                    <i class="fab fa-whatsapp me-2"></i>WhatsApp
                </a>
                @if($lead->email)
                <a href="mailto:{{ $lead->email }}?subject=Your Property Management Inquiry&body=Dear {{ $lead->name }},"
                   class="btn btn-outline-secondary">
                    <i class="fas fa-envelope me-2"></i>Send Email
                </a>
                @endif
            </div>
        </div>

        <div class="card shadow-sm border-danger">
            <div class="card-header fw-semibold text-danger bg-transparent">Danger Zone</div>
            <div class="card-body">
                <form action="{{ route('admin.property-management-leads.destroy', $lead->id) }}" method="POST"
                      onsubmit="return confirm('Delete this lead permanently?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="fas fa-trash me-2"></i>Delete This Lead
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
