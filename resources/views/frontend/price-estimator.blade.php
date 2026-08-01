@extends('frontend.layout')

@section('title', 'AI Property Price Estimator | ' . config('app.name'))
@section('meta_description', 'Get an instant AI-powered property price estimate for Chandigarh, Mohali, Zirakpur & Panchkula, based on real comparable listings.')
@section('canonical', url()->current())

@section('content')
<section class="py-5" style="background:linear-gradient(135deg,#0a2d5e,#0078d4);">
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-lg-8">
        <span class="badge mb-3" style="background:rgba(255,255,255,.15); color:#fff; font-size:.75rem; padding:6px 14px; border-radius:20px;">
          <i class="bi bi-stars me-1"></i> AI-Powered
        </span>
        <h1 class="fw-bold text-white mb-3" style="font-size:2rem;">Property Price Estimator</h1>
        <p class="text-white-50 mb-0">
          Get an instant estimate based on real comparable listings currently on IndianEstHub — not guesswork.
        </p>
      </div>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">

        <div class="card border-0 shadow-sm" style="border-radius:14px; overflow:hidden;">
          <div class="p-4 p-md-5">

            <div id="pe-form-state">
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label fw-semibold small mb-1">City <span class="text-danger">*</span></label>
                  <input type="text" id="pe-city" class="form-control" placeholder="Chandigarh / Mohali / Zirakpur / Panchkula" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold small mb-1">Locality <span class="text-muted">(optional, improves accuracy)</span></label>
                  <input type="text" id="pe-locality" class="form-control" placeholder="e.g. Sector 20, Kharar Road">
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold small mb-1">Property Type</label>
                  <select id="pe-property-type" class="form-select">
                    <option value="">Any</option>
                    @foreach(['Residential','Commercial','Office','Retail Shop','Showroom','Warehouse','Plot','Farm House','Pentahouse','Studio','Villa','Independent Floor','Duplex'] as $t)
                      <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold small mb-1">BHK</label>
                  <select id="pe-bhk-type" class="form-select">
                    <option value="">Any</option>
                    @foreach(['1 RK','1 BHK','2 BHK','3 BHK','4 BHK','5 BHK','5+ BHK'] as $bhk)
                      <option value="{{ $bhk }}">{{ $bhk }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold small mb-1">Built-up Area <span class="text-muted">(optional)</span></label>
                  <input type="number" id="pe-area" class="form-control" placeholder="e.g. 1200" min="0">
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold small mb-1">Area Unit</label>
                  <select id="pe-area-unit" class="form-select">
                    <option value="sq.ft.">sq.ft.</option>
                    <option value="sq.yd.">sq.yd.</option>
                    <option value="sq.m.">sq.m.</option>
                  </select>
                </div>
              </div>

              <button type="button" id="pe-generate-btn" class="btn w-100 fw-bold py-3"
                      style="background:linear-gradient(135deg,#0a2d5e,#0078d4); color:#fff; border-radius:10px;">
                <i class="bi bi-calculator me-2"></i> Get My Price Estimate
              </button>
              <div id="pe-error" class="text-danger small mt-2 text-center" style="display:none;"></div>
            </div>

            <div id="pe-result" style="display:none;" class="text-center">
              <div class="text-muted small mb-1">Estimated Market Value</div>
              <div class="fw-bold mb-2" style="font-size:2rem; color:#0a2d5e;" id="pe-range"></div>
              <div class="small text-muted mb-4" id="pe-meta"></div>

              <p class="text-start p-3 rounded-3" style="background:#f7f9fc; font-size:.92rem;" id="pe-explanation"></p>

              <div class="p-3 rounded-3 mt-3" style="background:#eef6ff;">
                <p class="small mb-2 fw-semibold">Want a formal valuation report or ready to list your property?</p>
                <a href="{{ route('dealer.register') }}" class="btn btn-sm fw-semibold me-2" style="background:#0a2d5e; color:#fff;">
                  <i class="bi bi-house-add me-1"></i> List My Property
                </a>
                <a href="https://wa.me/91{{ config('app.whatsapp_number','7340753780') }}?text=Hi%2C%20I%20got%20a%20price%20estimate%20and%20want%20to%20talk%20to%20an%20expert." target="_blank" class="btn btn-sm btn-outline-primary fw-semibold">
                  <i class="bi bi-whatsapp me-1"></i> Talk to an Expert
                </a>
              </div>

              <button type="button" class="btn btn-link btn-sm mt-3" onclick="document.getElementById('pe-form-state').style.display='block'; document.getElementById('pe-result').style.display='none';">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Estimate another property
              </button>

              <p class="text-muted mt-3 mb-0" style="font-size:.72rem;">
                <i class="bi bi-info-circle me-1"></i>
                This estimate is based on <span id="pe-comp-count-inline"></span> comparable listings currently on IndianEstHub and is indicative only — not a certified valuation.
              </p>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<script>
(function () {
    const generateBtn = document.getElementById('pe-generate-btn');
    if (!generateBtn) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const url = "{{ route('ai.price-estimate') }}";

    function formatINR(n) {
        n = Math.round(n);
        if (n >= 10000000) return '₹' + (n / 10000000).toFixed(2).replace(/\.00$/, '') + ' Cr';
        if (n >= 100000) return '₹' + (n / 100000).toFixed(2).replace(/\.00$/, '') + ' L';
        return '₹' + n.toLocaleString('en-IN');
    }

    generateBtn.addEventListener('click', function () {
        const errorEl = document.getElementById('pe-error');
        const city = document.getElementById('pe-city').value.trim();

        if (!city) {
            errorEl.textContent = 'Please enter a city.';
            errorEl.style.display = 'block';
            return;
        }

        errorEl.style.display = 'none';
        generateBtn.disabled = true;
        const originalHtml = generateBtn.innerHTML;
        generateBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Analyzing comparable listings...';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                city: city,
                locality: document.getElementById('pe-locality').value.trim(),
                property_type: document.getElementById('pe-property-type').value,
                bhk_type: document.getElementById('pe-bhk-type').value,
                area: document.getElementById('pe-area').value,
                area_unit: document.getElementById('pe-area-unit').value,
            }),
        })
        .then(res => res.json().then(data => ({ ok: res.ok, data })))
        .then(({ ok, data }) => {
            if (!ok || data.error) {
                errorEl.textContent = data.message || 'Could not generate an estimate. Please try again.';
                errorEl.style.display = 'block';
                return;
            }

            document.getElementById('pe-range').textContent = formatINR(data.estimated_low) + ' – ' + formatINR(data.estimated_high);
            document.getElementById('pe-meta').textContent = data.avg_price_per_sqft
                ? 'Avg. ₹' + Math.round(data.avg_price_per_sqft).toLocaleString('en-IN') + '/sq.ft. · based on ' + data.comp_count + ' comparable listings (' + data.match_level + ')'
                : 'Based on ' + data.comp_count + ' comparable listings (' + data.match_level + ')';
            document.getElementById('pe-comp-count-inline').textContent = data.comp_count;
            document.getElementById('pe-explanation').textContent = data.explanation || '';
            document.getElementById('pe-explanation').style.display = data.explanation ? 'block' : 'none';

            document.getElementById('pe-form-state').style.display = 'none';
            document.getElementById('pe-result').style.display = 'block';
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
@endsection
