@extends('backend.layout')
@section('title', 'Insurance Lead #' . $lead->id)
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <a href="{{ route('admin.insurance-leads.index') }}" class="btn btn-sm btn-light me-2">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        <span class="fs-5 fw-bold"><i class="fas fa-shield-alt me-2 text-success"></i>Insurance Lead #{{ $lead->id }}</span>
    </div>
    <span class="badge bg-{{ $lead->statusBadge() }} px-3 py-2" style="font-size:.85rem;">
        {{ \App\Models\InsuranceLead::statusOptions()[$lead->status] ?? $lead->status }}
    </span>
</div>

<div class="row g-4">

    {{-- Left: Details --}}
    <div class="col-lg-8">

        {{-- Applicant Info --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold border-0 pb-0">
                <i class="fas fa-user me-2 text-primary"></i>Applicant Details
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0" style="font-size:.88rem;">
                    <tr><td class="text-muted" width="170">Name</td><td class="fw-semibold">{{ $lead->name }}</td></tr>
                    <tr><td class="text-muted">Phone</td>
                        <td>
                            <a href="tel:{{ $lead->phone }}" class="fw-semibold text-dark">{{ $lead->phone }}</a>
                            <a href="https://wa.me/91{{ preg_replace('/[^0-9]/','',$lead->phone) }}?text={{ urlencode('Hi '.$lead->name.', this is regarding your home insurance enquiry on our platform.') }}"
                               target="_blank" class="btn btn-sm btn-success ms-2 py-0 px-2" style="font-size:.72rem;">
                                <i class="bi bi-whatsapp"></i> WhatsApp
                            </a>
                        </td>
                    </tr>
                    <tr><td class="text-muted">Email</td><td>{{ $lead->email ?? '—' }}</td></tr>
                    <tr><td class="text-muted">City</td><td>{{ $lead->property_city ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Source</td>
                        <td><span class="badge bg-light text-dark">{{ ucwords(str_replace('-',' ',$lead->source)) }}</span></td>
                    </tr>
                    <tr><td class="text-muted">IP Address</td><td class="text-muted small">{{ $lead->ip_address ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Submitted</td><td>{{ $lead->created_at->format('d M Y, h:i A') }}</td></tr>
                </table>
            </div>
        </div>

        {{-- Insurance Details --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold border-0 pb-0">
                <i class="fas fa-shield-alt me-2 text-success"></i>Insurance Details
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0" style="font-size:.88rem;">
                    <tr>
                        <td class="text-muted" width="170">Insurance Type</td>
                        <td>
                            <span class="badge px-3 py-1"
                                  style="background:
                                    @if($lead->insurance_type==='both') #7c3aed
                                    @elseif($lead->insurance_type==='content') #0369a1
                                    @elseif($lead->insurance_type==='fire') #dc2626
                                    @else #16a34a @endif;
                                    color:#fff;">
                                {{ $lead->insuranceTypeLabel() }}
                            </span>
                        </td>
                    </tr>
                    <tr><td class="text-muted">Property Type</td><td>{{ $lead->property_type ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Possession</td>
                        <td>{{ $lead->possession_status === 'ready' ? '✅ Ready to Move' : '🏗️ Under Construction' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Property Value</td>
                        <td class="fw-semibold">{{ $lead->formattedPropertyValue() ?: '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Coverage Amount</td>
                        <td class="fw-semibold">
                            @if($lead->coverage_amount)
                                {{ $lead->coverage_amount >= 10000000 ? '₹'.number_format($lead->coverage_amount/10000000,2).'Cr' : '₹'.number_format($lead->coverage_amount/100000,1).'L' }}
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Preferred Insurer</td>
                        <td>{{ $lead->preferred_insurer ?? 'No preference' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Est. Premium</td>
                        <td class="fw-semibold text-success">
                            @if($lead->estimatedPremium())
                                ~₹{{ number_format($lead->estimatedPremium()) }}/year
                            @else —
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Premium Quoted</td>
                        <td>
                            @if($lead->premium_quoted)
                                <span class="fw-bold text-success">₹{{ number_format($lead->premium_quoted) }}/year</span>
                            @else
                                <span class="text-muted">Not yet quoted</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Commission Earned</td>
                        <td>
                            @if($lead->commission_earned)
                                <span class="fw-bold text-success">₹{{ number_format($lead->commission_earned) }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Bundled Loan Lead --}}
        @if($lead->loanLead)
        <div class="card border-0 shadow-sm mb-4" style="border-left:3px solid #3b82f6!important;">
            <div class="card-header bg-white fw-semibold border-0 pb-0">
                <i class="fas fa-landmark me-2 text-primary"></i>Bundled Loan Lead
            </div>
            <div class="card-body">
                <p class="mb-2 small text-muted">This insurance was requested together with a home loan.</p>
                <table class="table table-sm table-borderless mb-0" style="font-size:.88rem;">
                    <tr><td class="text-muted" width="170">Loan Lead #</td>
                        <td><a href="{{ route('admin.loan-leads.show', $lead->loanLead) }}" class="text-primary fw-semibold">#{{ $lead->loanLead->id }}</a></td></tr>
                    <tr><td class="text-muted">Loan Amount</td>
                        <td>{{ $lead->loanLead->loan_amount ? '₹'.number_format($lead->loanLead->loan_amount) : '—' }}</td></tr>
                    <tr><td class="text-muted">Loan Status</td>
                        <td><span class="badge bg-{{ $lead->loanLead->statusBadge() }}">{{ $lead->loanLead->status }}</span></td></tr>
                </table>
            </div>
        </div>
        @endif

        {{-- Property / Project context --}}
        @if($lead->property || $lead->builderProject)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold border-0 pb-0">
                <i class="fas fa-home me-2 text-warning"></i>Property Context
            </div>
            <div class="card-body" style="font-size:.88rem;">
                @if($lead->property)
                <div>Property: <a href="{{ route('admin.properties.show', $lead->property) }}" class="text-primary">{{ $lead->property->title }}</a></div>
                @endif
                @if($lead->builderProject)
                <div>Project: <span class="text-info">{{ $lead->builderProject->title }}</span></div>
                @endif
                @if($lead->source_page)
                <div class="text-muted small mt-1">Source Page: {{ $lead->source_page }}</div>
                @endif
            </div>
        </div>
        @endif

    </div>

    {{-- Right: Actions --}}
    <div class="col-lg-4">

        {{-- Status + Revenue update --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold border-0 pb-0">
                <i class="fas fa-edit me-2 text-primary"></i>Update Status &amp; Revenue
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.insurance-leads.update-status', $lead) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            @foreach(\App\Models\InsuranceLead::statusOptions() as $val => $label)
                            <option value="{{ $val }}" {{ $lead->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Premium Quoted (₹/year)</label>
                        <input type="number" name="premium_quoted" class="form-control form-control-sm"
                               placeholder="e.g. 3500" value="{{ $lead->premium_quoted }}" min="0" step="100">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Commission Earned (₹)</label>
                        <input type="number" name="commission_earned" class="form-control form-control-sm"
                               placeholder="e.g. 1750" value="{{ $lead->commission_earned }}" min="0" step="50">
                        <div class="form-text">~50% of annual premium</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Notes</label>
                        <textarea name="notes" class="form-control form-control-sm" rows="3"
                                  placeholder="Insurer contacted, quote details, follow-up date...">{{ $lead->notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold border-0 pb-0">
                <i class="fas fa-bolt me-2 text-warning"></i>Quick Actions
            </div>
            <div class="card-body d-grid gap-2">
                <a href="tel:{{ $lead->phone }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-phone me-2"></i>Call {{ $lead->name }}
                </a>
                <a href="https://wa.me/91{{ preg_replace('/[^0-9]/','',$lead->phone) }}?text={{ urlencode('Hi '.$lead->name.'! This is regarding your home insurance enquiry. We have great quotes from HDFC ERGO, Bajaj Allianz and more. When is a good time to discuss?') }}"
                   target="_blank" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-whatsapp me-2"></i>WhatsApp
                </a>
                @if($lead->email)
                <a href="mailto:{{ $lead->email }}?subject=Your Home Insurance Quote&body=Dear {{ $lead->name }},%0D%0A%0D%0AThank you for enquiring about home insurance. We have compared quotes from 10+ insurers for your {{ $lead->property_type ?? 'property' }}.%0D%0A%0D%0APlease let us know a convenient time to discuss."
                   class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-envelope me-2"></i>Send Email
                </a>
                @endif
            </div>
        </div>

        {{-- Revenue Summary --}}
        <div class="card border-0 shadow-sm mb-3" style="background:#f0fdf4;">
            <div class="card-body py-3">
                <div class="fw-semibold small text-muted mb-2">💰 Revenue Potential</div>
                <div class="d-flex justify-content-between small mb-1">
                    <span class="text-muted">Est. Annual Premium</span>
                    <span class="fw-semibold">₹{{ number_format($lead->estimatedPremium() ?: 0) }}/yr</span>
                </div>
                <div class="d-flex justify-content-between small mb-1">
                    <span class="text-muted">Est. Commission (~50%)</span>
                    <span class="fw-semibold text-success">₹{{ number_format(($lead->estimatedPremium() ?: 0) * 0.5) }}</span>
                </div>
                @if($lead->commission_earned)
                <div class="d-flex justify-content-between small border-top pt-2 mt-1">
                    <span class="fw-bold">Actual Commission</span>
                    <span class="fw-bold text-success">₹{{ number_format($lead->commission_earned) }}</span>
                </div>
                @endif
                <div class="small text-muted mt-2">
                    <i class="bi bi-arrow-repeat text-success me-1"></i>
                    Insurance renews every year — recurring revenue!
                </div>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="card border-0 shadow-sm" style="border-top:3px solid #ef4444!important;">
            <div class="card-body py-3">
                <p class="small text-muted mb-2">Permanently delete this lead.</p>
                <form method="POST" action="{{ route('admin.insurance-leads.destroy', $lead) }}"
                      onsubmit="return confirm('Delete this insurance lead? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                        <i class="fas fa-trash me-1"></i> Delete Lead
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
