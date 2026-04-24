{{--
  Insurance Lead Capture Modal
  Variables (all optional — pass via @include or set defaults here):
    $property_id         : int|null
    $builder_project_id  : int|null
    $loan_lead_id        : int|null   — for bundle
    $source              : string     — 'property-page' / 'project-page' / 'loan-bundle' / 'post-visit'
    $source_page         : string     — request path
    $prefill_value       : float|null — property value pre-fill
    $prefill_city        : string|null
    $prefill_type        : string|null — property type
--}}
@php
    $ins_property_id        = $property_id        ?? null;
    $ins_project_id         = $builder_project_id ?? null;
    $ins_loan_lead_id       = $loan_lead_id       ?? null;
    $ins_source             = $source             ?? 'website';
    $ins_source_page        = $source_page        ?? request()->path();
    $ins_prefill_value      = $prefill_value      ?? null;
    $ins_prefill_city       = $prefill_city       ?? null;
    $ins_prefill_type       = $prefill_type       ?? null;
@endphp

<!-- Insurance Lead Modal -->
<div class="modal fade" id="insuranceModal" tabindex="-1" aria-labelledby="insuranceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0" style="border-radius:16px;overflow:hidden;">

      {{-- Header --}}
      <div class="modal-header border-0 py-4 px-4"
           style="background:linear-gradient(135deg,#0a2d5e 0%,#0078d4 50%,#0f4c81 100%);">
        <div>
          <h5 class="modal-title fw-bold text-white mb-1" id="insuranceModalLabel">
            🛡️ Get Home Insurance Quote
          </h5>
          <p class="text-white mb-0" style="opacity:.85;font-size:.83rem;">
            Protect your home from day 1 &nbsp;·&nbsp; Compare 10+ insurers &nbsp;·&nbsp; 100% Free
          </p>
          {{-- Insurer logos --}}
          <div class="d-flex align-items-center gap-2 mt-2 flex-wrap">
            @foreach(['HDFC ERGO','Bajaj Allianz','Tata AIG','ICICI Lombard','New India'] as $ins)
            <span class="badge" style="background:rgba(255,255,255,.18);color:#fff;font-size:.65rem;font-weight:600;padding:3px 8px;border-radius:12px;">
              {{ $ins }}
            </span>
            @endforeach
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
      </div>

      {{-- Body --}}
      <div class="modal-body px-4 pt-3 pb-4">

        {{-- Trust bar --}}
        <div class="d-flex gap-3 mb-3 flex-wrap">
          <span class="small text-muted"><i class="bi bi-shield-check-fill me-1" style="color:#0078d4;"></i> IRDAI Regulated</span>
          <span class="small text-muted"><i class="bi bi-lightning-charge-fill text-warning me-1"></i> Instant Quote</span>
          <span class="small text-muted"><i class="bi bi-cash-coin me-1" style="color:#0078d4;"></i> From ₹2,000/year</span>
          <span class="small text-muted"><i class="bi bi-telephone-fill text-primary me-1"></i> Expert Call in 2 hrs</span>
        </div>

        {{-- Form state --}}
        <div id="ins-form-state">
          <form id="insurance-lead-form">
            @csrf
            <input type="hidden" name="property_id"        value="{{ $ins_property_id }}">
            <input type="hidden" name="builder_project_id" value="{{ $ins_project_id }}">
            <input type="hidden" name="loan_lead_id"       value="{{ $ins_loan_lead_id }}">
            <input type="hidden" name="source"             value="{{ $ins_source }}">
            <input type="hidden" name="source_page"        value="{{ $ins_source_page }}">

            <div class="row g-3">

              {{-- Personal info --}}
              <div class="col-md-6">
                <label class="form-label fw-semibold small mb-1">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Rahul Sharma" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small mb-1">Mobile Number <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">+91</span>
                  <input type="tel" name="phone" class="form-control" placeholder="9XXXXXXXXX"
                         pattern="[0-9]{10}" required>
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small mb-1">Email Address <span class="text-muted">(optional)</span></label>
                <input type="email" name="email" class="form-control" placeholder="your@email.com">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small mb-1">Property City</label>
                <input type="text" name="property_city" class="form-control"
                       placeholder="Chandigarh / Mohali / Zirakpur"
                       value="{{ $ins_prefill_city }}">
              </div>

              {{-- Property details --}}
              <div class="col-md-6">
                <label class="form-label fw-semibold small mb-1">Property Type</label>
                <select name="property_type" class="form-select">
                  <option value="">Select type</option>
                  @foreach(['Flat/Apartment','Independent House','Villa','Row House','Builder Floor','Penthouse'] as $pt)
                  <option value="{{ $pt }}" {{ $ins_prefill_type === $pt ? 'selected' : '' }}>{{ $pt }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small mb-1">Possession Status</label>
                <select name="possession_status" class="form-select">
                  <option value="ready">Ready to Move</option>
                  <option value="under-construction">Under Construction</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small mb-1">Property Value (₹)</label>
                <input type="number" name="property_value" class="form-control"
                       placeholder="e.g. 5000000"
                       value="{{ $ins_prefill_value ? (int)$ins_prefill_value : '' }}"
                       min="100000" step="50000">
                <div class="form-text">Used to calculate coverage amount</div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small mb-1">Coverage Amount (₹)</label>
                <input type="number" name="coverage_amount" id="ins_coverage_amount" class="form-control"
                       placeholder="e.g. 4500000"
                       value="{{ $ins_prefill_value ? (int)($ins_prefill_value * 0.9) : '' }}"
                       min="100000" step="50000">
                <div class="form-text">Usually 90% of property value</div>
              </div>

              {{-- Insurance type --}}
              <div class="col-12">
                <label class="form-label fw-semibold small mb-2">What do you want to cover?</label>
                <div class="d-flex gap-2 flex-wrap" id="ins-type-group">
                  @foreach([
                      'home'    => ['🏠 Home Structure',  'Covers building structure damage'],
                      'content' => ['📦 Home Contents',   'Covers furniture, appliances, valuables'],
                      'both'    => ['🏠📦 Home + Contents','Best value — full protection'],
                      'fire'    => ['🔥 Fire & Allied',   'Fire, earthquake, flood coverage'],
                  ] as $val => [$label, $desc])
                  <label class="ins-type-card" style="cursor:pointer;border:2px solid #e2e8f0;border-radius:10px;padding:10px 14px;transition:all .15s;min-width:130px;">
                    <input type="radio" name="insurance_type" value="{{ $val }}"
                           {{ $val === 'home' ? 'checked' : '' }}
                           style="display:none;" onchange="selectInsType(this.closest('label'))">
                    <div class="fw-semibold small">{{ $label }}</div>
                    <div style="font-size:.7rem;color:#64748b;margin-top:2px;">{{ $desc }}</div>
                  </label>
                  @endforeach
                </div>
              </div>

              {{-- Preferred insurer --}}
              <div class="col-md-6">
                <label class="form-label fw-semibold small mb-1">Preferred Insurer <span class="text-muted">(optional)</span></label>
                <select name="preferred_insurer" class="form-select">
                  <option value="">No preference — show best</option>
                  @foreach(['HDFC ERGO','Bajaj Allianz','Tata AIG','ICICI Lombard','New India Assurance','Oriental Insurance','National Insurance'] as $ins)
                  <option value="{{ $ins }}">{{ $ins }}</option>
                  @endforeach
                </select>
              </div>

              {{-- Estimated premium teaser --}}
              <div class="col-md-6 d-flex align-items-end">
                <div class="rounded p-3 w-100" style="background:#eff6ff;border:1px solid #bfdbfe;">
                  <div class="small text-muted mb-1">Estimated Annual Premium</div>
                  <div id="ins-est-premium" class="fw-bold" style="font-size:1.2rem;color:#0078d4;">
                    @if($ins_prefill_value)
                      ₹{{ number_format((int)($ins_prefill_value * 0.0007)) }}/yr
                    @else
                      Fill value to see estimate
                    @endif
                  </div>
                  <div class="small text-muted">Based on ~0.07% of property value</div>
                </div>
              </div>

            </div>{{-- /row --}}

            {{-- Submit --}}
            <button type="submit" id="ins-submit-btn"
                    class="btn w-100 fw-bold mt-4 py-3"
                    style="background:linear-gradient(135deg,#0a2d5e,#0078d4);color:#fff;border-radius:10px;font-size:1rem;letter-spacing:.2px;">
              <i class="bi bi-shield-check me-2"></i> Get Free Insurance Quote
            </button>
            <p class="text-center text-muted mt-2 mb-0" style="font-size:.72rem;">
              🔒 100% Safe &amp; Free &nbsp;·&nbsp; No hidden charges &nbsp;·&nbsp; No spam calls
            </p>
          </form>
        </div>

        {{-- Success state --}}
        <div id="ins-success-state" style="display:none;" class="text-center py-4">
          <div style="width:70px;height:70px;background:#eff6ff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <i class="bi bi-shield-check-fill" style="font-size:2rem;color:#0078d4;"></i>
          </div>
          <h5 class="fw-bold mb-2" style="color:#0a2d5e;">Insurance Quote Requested! 🎉</h5>
          <p class="text-muted mb-3">Our insurance expert will call you within 2 hours with personalised quotes from top insurers.</p>
          <div class="d-flex gap-2 justify-content-center flex-wrap">
            <div class="rounded px-3 py-2 text-center" style="background:#eff6ff;border:1px solid #bfdbfe;min-width:100px;">
              <div class="fw-bold small" style="color:#0078d4;">10+</div>
              <div style="font-size:.7rem;color:#64748b;">Insurers</div>
            </div>
            <div class="rounded px-3 py-2 text-center" style="background:#eff6ff;border:1px solid #bfdbfe;min-width:100px;">
              <div class="fw-bold small text-primary">₹2k+</div>
              <div style="font-size:.7rem;color:#64748b;">Avg Saving</div>
            </div>
            <div class="rounded px-3 py-2 text-center" style="background:#fefce8;border:1px solid #fef08a;min-width:100px;">
              <div class="fw-bold small" style="color:#854d0e;">2 hrs</div>
              <div style="font-size:.7rem;color:#64748b;">Expert Call</div>
            </div>
          </div>
          <button type="button" class="btn mt-3" style="border:2px solid #0078d4;color:#0078d4;" data-bs-dismiss="modal">Close</button>
        </div>

      </div>{{-- /modal-body --}}
    </div>
  </div>
</div>

<script>
// Highlight selected insurance type card
function selectInsType(el) {
  document.querySelectorAll('.ins-type-card').forEach(c => {
    c.style.borderColor = '#e2e8f0';
    c.style.background  = '';
  });
  el.style.borderColor = '#0078d4';
  el.style.background  = '#eff6ff';
}
// Init first card
document.addEventListener('DOMContentLoaded', function() {
  const first = document.querySelector('.ins-type-card input[checked], .ins-type-card input:checked');
  if (first) selectInsType(first.closest('label'));

  // Live premium estimate from property value input
  const pvInput = document.querySelector('input[name="property_value"]');
  const estEl   = document.getElementById('ins-est-premium');
  const covEl   = document.getElementById('ins_coverage_amount');
  if (pvInput && estEl) {
    pvInput.addEventListener('input', function() {
      const v = parseFloat(this.value) || 0;
      if (v > 0) {
        const premium = Math.round(v * 0.0007);
        estEl.textContent = '₹' + premium.toLocaleString('en-IN') + '/yr';
        if (covEl && !covEl.value) covEl.value = Math.round(v * 0.9);
      } else {
        estEl.textContent = 'Fill value to see estimate';
      }
    });
  }
});

// Global open function
function openInsuranceModal(propertyId, projectId, source, loanLeadId) {
  const modal = document.getElementById('insuranceModal');
  if (!modal) return;
  if (propertyId) modal.querySelector('input[name="property_id"]').value  = propertyId;
  if (projectId)  modal.querySelector('input[name="builder_project_id"]').value = projectId;
  if (source)     modal.querySelector('input[name="source"]').value       = source;
  if (loanLeadId) modal.querySelector('input[name="loan_lead_id"]').value = loanLeadId;

  // Reset states
  document.getElementById('ins-form-state').style.display   = 'block';
  document.getElementById('ins-success-state').style.display = 'none';
  const btn = document.getElementById('ins-submit-btn');
  if (btn) { btn.disabled = false; btn.innerHTML = '<i class="bi bi-shield-check me-2"></i> Get Free Insurance Quote'; }

  new bootstrap.Modal(modal).show();
}

// AJAX submit
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('insurance-lead-form');
  if (!form) return;
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('ins-submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';

    fetch('{{ route("insurance.lead.store") }}', {
      method: 'POST',
      body: new FormData(form),
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        document.getElementById('ins-form-state').style.display    = 'none';
        document.getElementById('ins-success-state').style.display = 'block';
      } else {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-shield-check me-2"></i> Get Free Insurance Quote';
        alert(data.message || 'Something went wrong. Please try again.');
      }
    })
    .catch(() => {
      btn.disabled = false;
      btn.innerHTML = '<i class="bi bi-shield-check me-2"></i> Get Free Insurance Quote';
      alert('Network error. Please check your connection.');
    });
  });
});
</script>
