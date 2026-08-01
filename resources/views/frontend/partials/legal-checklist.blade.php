{{-- =====================================================================
     AI Legal Checklist tool
     Include on legal SEO pages: @include('frontend.partials.legal-checklist', ['cityLabel' => $cityLabel ?? null])
     ===================================================================== --}}
@php
    $lc_city = $cityLabel ?? null;
@endphp

<div class="card border-0 shadow-sm mb-5" style="border-radius:14px; overflow:hidden;">
    <div class="p-4" style="background:linear-gradient(135deg,#3b0764,#6b21a8);">
        <h3 class="text-white fw-bold mb-1" style="font-size:1.2rem;">
            <i class="bi bi-clipboard2-check me-2"></i>AI Legal Checklist
        </h3>
        <p class="text-white-50 mb-0 small">Get an instant document &amp; step checklist for your property transaction.</p>
    </div>

    <div class="p-4">
        <div id="lc-form-state">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small mb-1">What do you need help with?</label>
                    <select id="lc-issue-type" class="form-select">
                        @foreach(\App\Models\LegalLead::issueTypeOptions() as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small mb-1">Property Type</label>
                    <select id="lc-property-type" class="form-select">
                        <option value="">Not specified</option>
                        <option value="Resale flat/house">Resale Flat / House</option>
                        <option value="New booking from builder">New Booking (Builder)</option>
                        <option value="Plot / Land">Plot / Land</option>
                        <option value="Rental property">Rental Property</option>
                        <option value="Commercial property">Commercial Property</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small mb-1">City</label>
                    <input type="text" id="lc-city" class="form-control" placeholder="Chandigarh / Mohali / Zirakpur"
                           value="{{ $lc_city }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small mb-1">You are the</label>
                    <select id="lc-buyer-type" class="form-select">
                        <option value="resident Indian">Resident Indian</option>
                        <option value="NRI">NRI</option>
                        <option value="first-time buyer">First-time Buyer</option>
                        <option value="seller">Seller</option>
                    </select>
                </div>
            </div>

            <button type="button" id="lc-generate-btn" class="btn w-100 fw-semibold"
                    style="background:linear-gradient(135deg,#3b0764,#6b21a8); color:#fff;">
                <i class="bi bi-stars me-1"></i> Generate My Checklist
            </button>
            <div id="lc-error" class="text-danger small mt-2" style="display:none;"></div>
        </div>

        <div id="lc-result" style="display:none;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0" id="lc-result-title" style="color:#6b21a8;"></h5>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('lc-form-state').style.display='block'; document.getElementById('lc-result').style.display='none';">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Start Over
                </button>
            </div>

            <div class="mb-3">
                <h6 class="fw-bold small text-uppercase" style="color:#6b21a8; letter-spacing:.03em;">
                    <i class="bi bi-file-earmark-text me-1"></i> Documents You'll Likely Need
                </h6>
                <ul class="list-unstyled mb-0" id="lc-documents"></ul>
            </div>

            <div class="mb-3">
                <h6 class="fw-bold small text-uppercase" style="color:#6b21a8; letter-spacing:.03em;">
                    <i class="bi bi-list-ol me-1"></i> Typical Steps
                </h6>
                <ol class="ps-3 mb-0" id="lc-steps"></ol>
            </div>

            <div class="mb-3">
                <h6 class="fw-bold small text-uppercase text-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i> Watch Out For
                </h6>
                <ul class="list-unstyled mb-0" id="lc-red-flags"></ul>
            </div>

            <div class="p-3 rounded-3 mt-4" style="background:#f7f0ff;">
                <p class="small mb-2 fw-semibold">Need help with these documents or a next step?</p>
                <button type="button" class="btn btn-sm fw-semibold" style="background:#6b21a8; color:#fff;"
                        onclick="openLegalModal(null, null, 'ai-legal-checklist')">
                    <i class="bi bi-chat-dots me-1"></i> Get a Free Consultation
                </button>
            </div>

            <p class="text-muted mt-3 mb-0" style="font-size:.72rem;">
                <i class="bi bi-info-circle me-1"></i>
                This is general educational guidance, not legal advice for your specific case. Requirements vary by property and situation — please verify with a qualified advocate before relying on this checklist.
            </p>
        </div>
    </div>
</div>

<script>
(function () {
    const generateBtn = document.getElementById('lc-generate-btn');
    if (!generateBtn) return; // partial not on this page

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const url = "{{ route('ai.legal-checklist') }}";

    const issueLabels = @json(\App\Models\LegalLead::issueTypeOptions());

    function renderList(el, items, icon) {
        el.innerHTML = items.map(function (item) {
            return '<li class="d-flex gap-2 mb-2"><i class="bi ' + icon + ' flex-shrink-0 mt-1"></i><span class="small">' + item + '</span></li>';
        }).join('');
    }

    function renderSteps(el, items) {
        el.innerHTML = items.map(function (item) {
            return '<li class="small mb-2">' + item + '</li>';
        }).join('');
    }

    generateBtn.addEventListener('click', function () {
        const issueType = document.getElementById('lc-issue-type').value;
        const propertyType = document.getElementById('lc-property-type').value;
        const city = document.getElementById('lc-city').value;
        const buyerType = document.getElementById('lc-buyer-type').value;
        const errorEl = document.getElementById('lc-error');

        errorEl.style.display = 'none';
        generateBtn.disabled = true;
        const originalHtml = generateBtn.innerHTML;
        generateBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Generating your checklist...';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                issue_type: issueType,
                property_type: propertyType,
                city: city,
                buyer_type: buyerType,
            }),
        })
        .then(res => res.json().then(data => ({ ok: res.ok, data })))
        .then(({ ok, data }) => {
            if (!ok || data.error) {
                errorEl.textContent = data.message || 'Could not generate a checklist. Please try again.';
                errorEl.style.display = 'block';
                return;
            }

            document.getElementById('lc-result-title').textContent = issueLabels[issueType] + ' — Checklist';
            renderList(document.getElementById('lc-documents'), data.documents, 'bi-check-circle-fill text-success');
            renderSteps(document.getElementById('lc-steps'), data.steps);
            renderList(document.getElementById('lc-red-flags'), data.red_flags, 'bi-exclamation-circle-fill text-danger');

            document.getElementById('lc-form-state').style.display = 'none';
            document.getElementById('lc-result').style.display = 'block';
        })
        .catch(() => {
            errorEl.textContent = 'Something went wrong. Please try again.';
            errorEl.style.display = 'block';
        })
        .finally(() => {
            generateBtn.disabled = false;
            generateBtn.innerHTML = originalHtml;
        });
    });
})();
</script>
