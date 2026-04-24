{{--
  Legal Help Lead Capture Modal
  Variables (all optional — pass via @include or set defaults here):
    $property_id         : int|null
    $builder_project_id  : int|null
    $source              : string     — 'property-page' / 'project-page' / 'footer' / 'website'
    $source_page         : string     — request path
--}}
@php
    $leg_property_id  = $legal_property_id  ?? null;
    $leg_project_id   = $legal_project_id   ?? null;
    $leg_source       = $legal_source       ?? 'website';
    $leg_source_page  = $legal_source_page  ?? request()->path();
@endphp

<!-- Legal Help Modal -->
<div class="modal fade" id="legalModal" tabindex="-1" aria-labelledby="legalModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0" style="border-radius:16px;overflow:hidden;">

      {{-- Header --}}
      <div class="modal-header border-0 py-4 px-4"
           style="background:linear-gradient(135deg,#1a0533 0%,#6b21a8 50%,#4c1d95 100%);">
        <div>
          <h5 class="modal-title fw-bold text-white mb-1" id="legalModalLabel">
            ⚖️ Get Legal Help for Your Property
          </h5>
          <p class="text-white mb-0" style="opacity:.85;font-size:.83rem;">
            Expert legal advice &nbsp;·&nbsp; Title checks &nbsp;·&nbsp; Sale deed &amp; agreements &nbsp;·&nbsp; Free consultation
          </p>
          <div class="d-flex align-items-center gap-2 mt-2 flex-wrap">
            @foreach(['Title Verification','Sale Deed','Property Dispute','Rental Agreement','Will & Succession'] as $s)
            <span class="badge" style="background:rgba(255,255,255,.18);color:#fff;font-size:.65rem;font-weight:600;padding:3px 8px;border-radius:12px;">
              {{ $s }}
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
          <span class="small text-muted"><i class="bi bi-patch-check-fill me-1" style="color:#6b21a8;"></i> Verified Lawyers</span>
          <span class="small text-muted"><i class="bi bi-clock-fill text-warning me-1"></i> Response in 24 hrs</span>
          <span class="small text-muted"><i class="bi bi-chat-dots-fill me-1" style="color:#6b21a8;"></i> Free First Consultation</span>
          <span class="small text-muted"><i class="bi bi-shield-lock-fill text-success me-1"></i> 100% Confidential</span>
        </div>

        {{-- Form state --}}
        <div id="leg-form-state">
          <form id="legal-lead-form">
            @csrf
            <input type="hidden" name="property_id"        value="{{ $leg_property_id }}">
            <input type="hidden" name="builder_project_id" value="{{ $leg_project_id }}">
            <input type="hidden" name="source"             value="{{ $leg_source }}">
            <input type="hidden" name="source_page"        value="{{ $leg_source_page }}">

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
                <label class="form-label fw-semibold small mb-1">City</label>
                <input type="text" name="city" class="form-control" placeholder="Chandigarh / Mohali / Zirakpur">
              </div>

              {{-- Issue type --}}
              <div class="col-12">
                <label class="form-label fw-semibold small mb-2">What legal help do you need? <span class="text-danger">*</span></label>
                <div class="d-flex gap-2 flex-wrap" id="leg-type-group">
                  @foreach([
                      'title_verification' => ['🔍 Title Verification', 'Check ownership & encumbrances'],
                      'sale_deed'          => ['📝 Sale Deed / Registry', 'Registration & agreement help'],
                      'rental_agreement'   => ['🏠 Rental Agreement', 'Draft or review rent deed'],
                      'property_dispute'   => ['⚖️ Property Dispute', 'Resolve ownership conflicts'],
                      'will_registration'  => ['📜 Will / Succession', 'Will drafting & succession'],
                      'court_case'         => ['🏛️ Court Case', 'Legal representation'],
                      'other'              => ['💬 Other', 'Any other legal matter'],
                  ] as $val => [$label, $desc])
                  <label class="leg-type-card" style="cursor:pointer;border:2px solid #e2e8f0;border-radius:10px;padding:10px 14px;transition:all .15s;min-width:120px;">
                    <input type="radio" name="legal_issue_type" value="{{ $val }}"
                           {{ $val === 'title_verification' ? 'checked' : '' }}
                           style="display:none;" onchange="selectLegType(this.closest('label'))">
                    <div class="fw-semibold small">{{ $label }}</div>
                    <div style="font-size:.7rem;color:#64748b;margin-top:2px;">{{ $desc }}</div>
                  </label>
                  @endforeach
                </div>
              </div>

              {{-- Preferred date --}}
              <div class="col-md-6">
                <label class="form-label fw-semibold small mb-1">Preferred Consultation Date <span class="text-muted">(optional)</span></label>
                <input type="date" name="preferred_date" class="form-control"
                       min="{{ date('Y-m-d') }}">
              </div>

              {{-- Description --}}
              <div class="col-md-6">
                <label class="form-label fw-semibold small mb-1">Brief Description <span class="text-muted">(optional)</span></label>
                <textarea name="description" class="form-control" rows="3"
                          placeholder="Briefly describe your legal issue or question…" style="resize:none;"></textarea>
              </div>

            </div>{{-- /row --}}

            {{-- Submit --}}
            <button type="submit" id="leg-submit-btn"
                    class="btn w-100 fw-bold mt-4 py-3"
                    style="background:linear-gradient(135deg,#1a0533,#6b21a8);color:#fff;border-radius:10px;font-size:1rem;letter-spacing:.2px;">
              <i class="bi bi-send me-2"></i> Request Free Legal Consultation
            </button>
            <p class="text-center text-muted mt-2 mb-0" style="font-size:.72rem;">
              🔒 100% Confidential &nbsp;·&nbsp; No upfront fees &nbsp;·&nbsp; Expert callback in 24 hrs
            </p>
          </form>
        </div>

        {{-- Success state --}}
        <div id="leg-success-state" style="display:none;" class="text-center py-4">
          <div style="width:70px;height:70px;background:#f5f3ff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <i class="bi bi-patch-check-fill" style="font-size:2rem;color:#6b21a8;"></i>
          </div>
          <h5 class="fw-bold mb-2" style="color:#1a0533;">Legal Request Submitted! ⚖️</h5>
          <p class="text-muted mb-3">Our legal expert will contact you within 24 hours for a free consultation.</p>
          <div class="d-flex gap-2 justify-content-center flex-wrap">
            <div class="rounded px-3 py-2 text-center" style="background:#f5f3ff;border:1px solid #d8b4fe;min-width:100px;">
              <div class="fw-bold small" style="color:#6b21a8;">Free</div>
              <div style="font-size:.7rem;color:#64748b;">First Consult</div>
            </div>
            <div class="rounded px-3 py-2 text-center" style="background:#f5f3ff;border:1px solid #d8b4fe;min-width:100px;">
              <div class="fw-bold small" style="color:#6b21a8;">24 hrs</div>
              <div style="font-size:.7rem;color:#64748b;">Expert Call</div>
            </div>
            <div class="rounded px-3 py-2 text-center" style="background:#f0fdf4;border:1px solid #bbf7d0;min-width:100px;">
              <div class="fw-bold small text-success">100%</div>
              <div style="font-size:.7rem;color:#64748b;">Confidential</div>
            </div>
          </div>
          <button type="button" class="btn mt-3" style="border:2px solid #6b21a8;color:#6b21a8;" data-bs-dismiss="modal">Close</button>
        </div>

      </div>{{-- /modal-body --}}
    </div>
  </div>
</div>

<script>
function selectLegType(el) {
  document.querySelectorAll('.leg-type-card').forEach(c => {
    c.style.borderColor = '#e2e8f0';
    c.style.background  = '';
  });
  el.style.borderColor = '#6b21a8';
  el.style.background  = '#f5f3ff';
}

document.addEventListener('DOMContentLoaded', function() {
  // Highlight default selected card
  const checked = document.querySelector('.leg-type-card input:checked');
  if (checked) selectLegType(checked.closest('label'));

  // AJAX submit
  const form = document.getElementById('legal-lead-form');
  if (!form) return;

  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('leg-submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';

    fetch('{{ route("legal.lead.store") }}', {
      method: 'POST',
      body: new FormData(form),
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        document.getElementById('leg-form-state').style.display    = 'none';
        document.getElementById('leg-success-state').style.display = 'block';
      } else {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-send me-2"></i> Request Free Legal Consultation';
        alert(data.message || 'Something went wrong. Please try again.');
      }
    })
    .catch(() => {
      btn.disabled = false;
      btn.innerHTML = '<i class="bi bi-send me-2"></i> Request Free Legal Consultation';
      alert('Network error. Please check your connection.');
    });
  });
});

// Global open function — call from anywhere on the page
function openLegalModal(propertyId, projectId, source) {
  const modal = document.getElementById('legalModal');
  if (!modal) return;
  if (propertyId) modal.querySelector('input[name="property_id"]').value        = propertyId;
  if (projectId)  modal.querySelector('input[name="builder_project_id"]').value = projectId;
  if (source)     modal.querySelector('input[name="source"]').value             = source;

  // Reset states
  document.getElementById('leg-form-state').style.display    = 'block';
  document.getElementById('leg-success-state').style.display = 'none';
  const btn = document.getElementById('leg-submit-btn');
  if (btn) { btn.disabled = false; btn.innerHTML = '<i class="bi bi-send me-2"></i> Request Free Legal Consultation'; }

  new bootstrap.Modal(modal).show();
}
</script>
