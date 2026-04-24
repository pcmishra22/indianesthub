@extends('backend.layout')
@section('title', 'Loan Lead — ' . $lead->name)
@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.loan-leads.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i>
    </a>
    <h4 class="mb-0"><i class="fas fa-landmark me-2 text-primary"></i>Loan Lead #{{ $lead->id }}</h4>
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

    {{-- Left: Lead Details --}}
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold bg-transparent">
                <i class="fas fa-user me-2 text-primary"></i>Applicant Details
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
                        <tr><td class="text-muted fw-medium ps-3">Employment</td><td>{{ $lead->employmentLabel() }}</td></tr>
                        <tr><td class="text-muted fw-medium ps-3">Monthly Income</td>
                            <td>{{ $lead->monthly_income ? '₹' . number_format($lead->monthly_income) : '—' }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-header fw-semibold bg-transparent">
                <i class="fas fa-rupee-sign me-2 text-success"></i>Loan Details
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        <tr><td class="text-muted fw-medium ps-3" style="width:200px;">Loan Amount</td>
                            <td class="fw-bold text-primary fs-5">{{ $lead->formattedLoanAmount() }}</td></tr>
                        <tr><td class="text-muted fw-medium ps-3">Property Value</td>
                            <td>{{ $lead->property_value ? '₹' . number_format($lead->property_value) : '—' }}</td></tr>
                        <tr><td class="text-muted fw-medium ps-3">LTV Ratio</td>
                            <td>
                                @if($lead->loan_amount && $lead->property_value && $lead->property_value > 0)
                                    {{ round(($lead->loan_amount / $lead->property_value) * 100, 1) }}%
                                @else — @endif
                            </td></tr>
                        <tr><td class="text-muted fw-medium ps-3">Loan Tenure</td>
                            <td>{{ $lead->loan_tenure ? $lead->loan_tenure . ' Years' : '—' }}</td></tr>
                        <tr><td class="text-muted fw-medium ps-3">Loan Purpose</td>
                            <td><span class="badge bg-secondary">{{ ucwords(str_replace('-', ' ', $lead->loan_purpose)) }}</span></td></tr>
                        @if($lead->monthly_income && $lead->loan_amount)
                        <tr><td class="text-muted fw-medium ps-3">Est. Eligible Amount</td>
                            <td class="text-success fw-semibold">
                                ₹{{ number_format(min($lead->monthly_income * 60, $lead->loan_amount)) }}
                                <small class="text-muted">(~60× income)</small>
                            </td></tr>
                        @endif
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

    {{-- Right: Actions --}}
    <div class="col-lg-4">

        {{-- Update Status & Notes --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header fw-semibold bg-transparent">Update Status</div>
            <div class="card-body">
                <form action="{{ route('admin.loan-leads.update-status', $lead->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium small">Status</label>
                        <select name="status" class="form-select">
                            @foreach(\App\Models\LoanLead::statusOptions() as $value => $label)
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

        {{-- Quick Actions --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header fw-semibold bg-transparent">Quick Actions</div>
            <div class="card-body d-grid gap-2">
                <a href="tel:{{ $lead->phone }}" class="btn btn-outline-primary">
                    <i class="fas fa-phone-alt me-2"></i>Call Applicant
                </a>
                <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $lead->phone) }}?text={{ urlencode('Hi ' . $lead->name . ', I am calling regarding your home loan inquiry. Let me know a convenient time to connect.') }}"
                   target="_blank" class="btn btn-success">
                    <i class="fab fa-whatsapp me-2"></i>WhatsApp
                </a>
                @if($lead->email)
                <a href="mailto:{{ $lead->email }}?subject=Your Home Loan Application&body=Dear {{ $lead->name }},"
                   class="btn btn-outline-secondary">
                    <i class="fas fa-envelope me-2"></i>Send Email
                </a>
                @endif
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="card shadow-sm border-danger">
            <div class="card-header fw-semibold text-danger bg-transparent">Danger Zone</div>
            <div class="card-body">
                <form action="{{ route('admin.loan-leads.destroy', $lead->id) }}" method="POST"
                      onsubmit="return confirm('Delete this loan lead permanently?')">
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
