{{-- =====================================================================
     Loan Eligibility Modal
     Include once per page layout: @include('frontend.partials.loan-eligibility-modal')
     Trigger:  openLoanModal(propertyId, projectId, source)
     ===================================================================== --}}

<div class="modal fade" id="loanEligibilityModal" tabindex="-1" aria-labelledby="loanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">

            <!-- Header -->
            <div class="modal-header border-0 px-4 pt-4 pb-0" style="background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);">
                <div class="d-flex align-items-start gap-3 w-100 pb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:48px;height:48px;background:rgba(255,255,255,0.15);">
                        <i class="fas fa-landmark text-white fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-1 text-white fw-bold" id="loanModalLabel">Check Home Loan Eligibility</h5>
                        <p class="text-white-50 mb-0 small">Get pre-approved in minutes · Best rates from top banks</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Bank logos strip -->
                <div class="d-flex align-items-center gap-3 px-1 pb-3 flex-wrap">
                    @foreach(['SBI','HDFC','ICICI','Axis','PNB','Kotak'] as $bank)
                        <span class="badge rounded-pill bg-white text-dark fw-normal small px-2">{{ $bank }}</span>
                    @endforeach
                    <span class="text-white-50 small ms-1">+20 more banks</span>
                </div>
            </div>

            <!-- Body -->
            <div class="modal-body p-4">

                <!-- Success state (hidden initially) -->
                <div id="loan-success-state" class="text-center py-4 d-none">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:72px;height:72px;background:rgba(0,120,212,0.1);">
                        <i class="fas fa-check-circle" style="font-size:2rem;color:#0078d4;"></i>
                    </div>
                    <h5 class="fw-bold mb-2" style="color:#0a2d5e;">Application Submitted!</h5>
                    <p class="text-muted mb-3">
                        Our loan expert will contact you within <strong>2 hours</strong> with personalized loan options.
                    </p>
                    {{-- Insurance upsell (shown when insurance bundle NOT checked) --}}
                    <div id="loan-insurance-upsell" class="mb-3 p-3 rounded-2" style="background:#eff6ff;border:1px solid #bfdbfe;display:none;">
                        <div class="fw-semibold small mb-1" style="color:#0078d4;">🛡️ Also protect your home with insurance?</div>
                        <div class="text-muted small mb-2">Get quotes from HDFC ERGO, Bajaj Allianz and more. From ₹2,000/year.</div>
                        <button type="button" class="btn btn-sm fw-semibold" style="background:#0078d4;color:#fff;"
                                onclick="openInsuranceModal(
                                    document.getElementById('loan-property-id').value,
                                    document.getElementById('loan-project-id').value,
                                    'loan-bundle'
                                )">
                            <i class="bi bi-shield-check me-1"></i> Get Free Insurance Quote
                        </button>
                    </div>
                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                        <div class="text-center px-3">
                            <div class="fw-bold text-primary fs-5">3–5%</div>
                            <div class="text-muted small">Processing Fee</div>
                        </div>
                        <div class="vr"></div>
                        <div class="text-center px-3">
                            <div class="fw-bold text-primary fs-5">8.4%+</div>
                            <div class="text-muted small">Best Rate</div>
                        </div>
                        <div class="vr"></div>
                        <div class="text-center px-3">
                            <div class="fw-bold text-primary fs-5">30 Yrs</div>
                            <div class="text-muted small">Max Tenure</div>
                        </div>
                    </div>
                </div>

                <!-- Form state -->
                <div id="loan-form-state">
                    <form id="loanLeadForm" novalidate>
                        @csrf
                        <input type="hidden" name="property_id" id="loan-property-id">
                        <input type="hidden" name="builder_project_id" id="loan-project-id">
                        <input type="hidden" name="source" id="loan-source" value="website">
                        <input type="hidden" name="source_page" value="{{ url()->current() }}">

                        <div class="row g-3">

                            <!-- Name & Phone -->
                            <div class="col-sm-6">
                                <label class="form-label fw-medium small">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control"
                                       placeholder="Your full name" required>
                                <div class="invalid-feedback">Please enter your name.</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-medium small">Mobile Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text text-muted small">+91</span>
                                    <input type="tel" name="phone" class="form-control"
                                           placeholder="10-digit mobile" maxlength="10" required
                                           oninput="this.value=this.value.replace(/\D/g,'')">
                                </div>
                                <div class="invalid-feedback">Please enter a valid 10-digit mobile number.</div>
                            </div>

                            <!-- Email & Employment -->
                            <div class="col-sm-6">
                                <label class="form-label fw-medium small">Email Address</label>
                                <input type="email" name="email" class="form-control"
                                       placeholder="you@example.com">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-medium small">Employment Type <span class="text-danger">*</span></label>
                                <div class="d-flex gap-2 flex-wrap mt-1">
                                    @foreach(['salaried'=>'Salaried','self-employed'=>'Self-Employed','business'=>'Business Owner'] as $val=>$label)
                                        <div class="form-check form-check-inline m-0">
                                            <input class="form-check-input" type="radio" name="employment_type"
                                                   id="emp_{{ $val }}" value="{{ $val }}"
                                                   {{ $val === 'salaried' ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="emp_{{ $val }}">{{ $label }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Monthly Income & Loan Amount -->
                            <div class="col-sm-6">
                                <label class="form-label fw-medium small">Monthly Income (₹)</label>
                                <input type="number" name="monthly_income" class="form-control"
                                       placeholder="e.g. 75000" min="0">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-medium small">Loan Amount Required (₹) <span class="text-danger">*</span></label>
                                <input type="number" name="loan_amount" id="modal-loan-amount" class="form-control"
                                       placeholder="e.g. 5000000" min="0" required>
                                <div class="invalid-feedback">Please enter a loan amount.</div>
                            </div>

                            <!-- Property Value & Tenure -->
                            <div class="col-sm-6">
                                <label class="form-label fw-medium small">Property Value (₹)</label>
                                <input type="number" name="property_value" id="modal-property-value" class="form-control"
                                       placeholder="Total property cost">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-medium small">Loan Tenure</label>
                                <select name="loan_tenure" class="form-select">
                                    <option value="">Select tenure</option>
                                    @foreach([5,10,15,20,25,30] as $yr)
                                        <option value="{{ $yr }}" {{ $yr === 20 ? 'selected' : '' }}>{{ $yr }} Years</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Loan Purpose -->
                            <div class="col-12">
                                <label class="form-label fw-medium small">Loan Purpose</label>
                                <div class="d-flex gap-2 flex-wrap mt-1">
                                    @foreach(['purchase'=>'🏠 Purchase','construction'=>'🏗️ Construction','renovation'=>'🔨 Renovation','balance-transfer'=>'🔄 Balance Transfer'] as $val=>$label)
                                        <div class="form-check form-check-inline m-0">
                                            <input class="form-check-input" type="radio" name="loan_purpose"
                                                   id="lp_{{ $val }}" value="{{ $val }}"
                                                   {{ $val === 'purchase' ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="lp_{{ $val }}">{{ $label }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div><!-- /row -->

                        <!-- Trust bar -->
                        <div class="d-flex gap-3 align-items-center mt-3 p-3 rounded-2" style="background:#eff6ff;border:1px solid #bfdbfe;">
                            <i class="fas fa-shield-alt" style="color:#0078d4;"></i>
                            <small class="text-muted">
                                <strong>100% Safe & Free</strong> · Your information is secure.
                                No hidden charges · No credit score impact · Expert guidance
                            </small>
                        </div>

                        <!-- 🛡️ Insurance Bundle Upsell -->
                        <div class="mt-3 p-3 rounded-2" style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border:1.5px solid #bfdbfe;">
                            <div class="d-flex align-items-start gap-2">
                                <div class="form-check mb-0 flex-shrink-0 mt-1">
                                    <input class="form-check-input" type="checkbox" id="bundle_insurance" name="bundle_insurance" value="1">
                                </div>
                                <label for="bundle_insurance" class="mb-0" style="cursor:pointer;">
                                    <div class="fw-semibold small" style="color:#0078d4;">
                                        🛡️ Also get Home Insurance quote — FREE
                                    </div>
                                    <div class="text-muted" style="font-size:.73rem;margin-top:2px;">
                                        Banks often require insurance. Bundle today &amp; protect your home from ₹2,000/year.
                                        <strong style="color:#0a2d5e;">Tick to get a free quote alongside your loan.</strong>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mt-3 fw-semibold" id="loan-submit-btn">
                            <i class="fas fa-check-circle me-2"></i> Check My Eligibility
                        </button>
                    </form>
                </div>

            </div><!-- /modal-body -->

        </div>
    </div>
</div>

<script>
// ── Open modal helper ─────────────────────────────────────────────────────
function openLoanModal(propertyId, projectId, source) {
    document.getElementById('loan-property-id').value = propertyId || '';
    document.getElementById('loan-project-id').value  = projectId  || '';
    document.getElementById('loan-source').value      = source     || 'website';

    // Reset form state
    document.getElementById('loan-form-state').classList.remove('d-none');
    document.getElementById('loan-success-state').classList.add('d-none');
    document.getElementById('loanLeadForm').reset();

    // Pre-fill loan amount from EMI calculator if available
    const emiRange = document.getElementById('emi-loan-range');
    if (emiRange) {
        document.getElementById('modal-loan-amount').value = Math.round(emiRange.value * 0.8);
    }

    // Pre-fill property value if available
    @if(isset($property) && $property->price)
    document.getElementById('modal-property-value').value = '{{ $property->price }}';
    @elseif(isset($builderProject) && $builderProject->price_from)
    document.getElementById('modal-property-value').value = '{{ $builderProject->price_from }}';
    @endif

    const modal = new bootstrap.Modal(document.getElementById('loanEligibilityModal'));
    modal.show();
}

// ── Form submission ───────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const form    = document.getElementById('loanLeadForm');
    const btn     = document.getElementById('loan-submit-btn');

    if (!form) return;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        // Phone validation
        const phone = form.querySelector('[name="phone"]').value.trim();
        if (phone.length < 10) {
            form.querySelector('[name="phone"]').setCustomValidity('Please enter a 10-digit mobile number.');
            form.classList.add('was-validated');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting…';

        try {
            const resp = await fetch('{{ route("loan.lead.store") }}', {
                method:  'POST',
                body:    new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });
            const data = await resp.json();

            if (data.success) {
                const bundleInsurance = form.querySelector('[name="bundle_insurance"]');
                const wantedInsurance = bundleInsurance && bundleInsurance.checked;

                document.getElementById('loan-form-state').classList.add('d-none');
                document.getElementById('loan-success-state').classList.remove('d-none');

                if (wantedInsurance) {
                    // Open insurance modal immediately, pre-fill with loan lead ID from response
                    const loanLeadId = data.loan_lead_id || null;
                    const propId     = document.getElementById('loan-property-id').value;
                    const projId     = document.getElementById('loan-project-id').value;
                    setTimeout(function() {
                        openInsuranceModal(propId || null, projId || null, 'loan-bundle', loanLeadId);
                    }, 600);
                } else {
                    // Show the upsell button in success state
                    const upsell = document.getElementById('loan-insurance-upsell');
                    if (upsell) upsell.style.display = 'block';
                }
            } else {
                alert(data.message || 'Something went wrong. Please try again.');
            }
        } catch (err) {
            alert('Network error. Please check your connection and try again.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check-circle me-2"></i> Check My Eligibility';
        }
    });
});
</script>
