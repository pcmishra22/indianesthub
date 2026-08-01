@extends('frontend.layout')

@section('title', 'AI Property Investment Advisor | ' . config('app.name'))
@section('meta_description', 'Get instant AI-powered property investment insights for Chandigarh Tricity, based on real listings and rental data — not guesswork.')
@section('canonical', url()->current())

@section('content')
<section class="py-5" style="background:linear-gradient(135deg,#064e3b,#059669);">
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-lg-8">
        <span class="badge mb-3" style="background:rgba(255,255,255,.15); color:#fff; font-size:.75rem; padding:6px 14px; border-radius:20px;">
          <i class="bi bi-stars me-1"></i> AI-Powered
        </span>
        <h1 class="fw-bold text-white mb-3" style="font-size:2rem;">Property Investment Advisor</h1>
        <p class="text-white-50 mb-0">
          See what your budget gets you and estimated rental yields, based on real listings currently on IndianEstHub.
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

            <div id="ia-form-state">
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label fw-semibold small mb-1">City <span class="text-danger">*</span></label>
                  <input type="text" id="ia-city" class="form-control" placeholder="Chandigarh / Mohali / Zirakpur / Panchkula" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold small mb-1">Budget (₹) <span class="text-danger">*</span></label>
                  <input type="number" id="ia-budget" class="form-control" placeholder="e.g. 6000000" min="0" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold small mb-1">Preferred BHK <span class="text-muted">(optional)</span></label>
                  <select id="ia-bhk-type" class="form-select">
                    <option value="">Any</option>
                    @foreach(['1 BHK','2 BHK','3 BHK','4 BHK','5+ BHK'] as $bhk)
                      <option value="{{ $bhk }}">{{ $bhk }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold small mb-1">Investment Goal</label>
                  <select id="ia-goal" class="form-select">
                    <option value="both">Rental Income + Appreciation</option>
                    <option value="rental">Rental Income</option>
                    <option value="appreciation">Capital Appreciation</option>
                  </select>
                </div>
              </div>

              <button type="button" id="ia-generate-btn" class="btn w-100 fw-bold py-3"
                      style="background:linear-gradient(135deg,#064e3b,#059669); color:#fff; border-radius:10px;">
                <i class="bi bi-graph-up-arrow me-2"></i> Get Market Insight
              </button>
              <div id="ia-error" class="text-danger small mt-2 text-center" style="display:none;"></div>
            </div>

            <div id="ia-result" style="display:none;">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0" style="color:#059669;"><i class="bi bi-graph-up-arrow me-2"></i>Market Insight</h5>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('ia-form-state').style.display='block'; document.getElementById('ia-result').style.display='none';">
                  <i class="bi bi-arrow-counterclockwise me-1"></i> Start Over
                </button>
              </div>

              <div class="row g-2 mb-3 text-center">
                <div class="col-4">
                  <div class="p-2 rounded-3" style="background:#ecfdf5;">
                    <div class="fw-bold" id="ia-comp-count" style="color:#059669;">–</div>
                    <div class="small text-muted">Comparable Listings</div>
                  </div>
                </div>
                <div class="col-4">
                  <div class="p-2 rounded-3" style="background:#ecfdf5;">
                    <div class="fw-bold" id="ia-avg-price" style="color:#059669;">–</div>
                    <div class="small text-muted">Avg. Price in Range</div>
                  </div>
                </div>
                <div class="col-4">
                  <div class="p-2 rounded-3" style="background:#ecfdf5;">
                    <div class="fw-bold" id="ia-yield" style="color:#059669;">–</div>
                    <div class="small text-muted">Est. Rental Yield</div>
                  </div>
                </div>
              </div>

              <p class="p-3 rounded-3" style="background:#f7f9fc; font-size:.92rem;" id="ia-narrative"></p>

              <div id="ia-listings-wrap" style="display:none;">
                <h6 class="fw-bold small text-uppercase mb-2" style="color:#059669; letter-spacing:.03em;">
                  <i class="bi bi-house-check me-1"></i> Listings Within Your Budget
                </h6>
                <div id="ia-listings" class="list-group mb-3"></div>
              </div>

              <p class="text-muted mt-3 mb-0" style="font-size:.72rem;">
                <i class="bi bi-info-circle me-1"></i>
                This is general market information based on current listings, not personalized financial or investment advice. Please do your own due diligence or consult a financial advisor before investing.
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
    const generateBtn = document.getElementById('ia-generate-btn');
    if (!generateBtn) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const url = "{{ route('ai.investment-advisor') }}";
    const propertyUrlTemplate = "{{ route('property-details', ['slug' => '__SLUG__']) }}";

    function formatINR(n) {
        if (n === null || n === undefined) return '–';
        n = Math.round(n);
        if (n >= 10000000) return '₹' + (n / 10000000).toFixed(2).replace(/\.00$/, '') + ' Cr';
        if (n >= 100000) return '₹' + (n / 100000).toFixed(2).replace(/\.00$/, '') + ' L';
        return '₹' + n.toLocaleString('en-IN');
    }

    generateBtn.addEventListener('click', function () {
        const errorEl = document.getElementById('ia-error');
        const city = document.getElementById('ia-city').value.trim();
        const budget = document.getElementById('ia-budget').value;

        if (!city || !budget) {
            errorEl.textContent = 'Please enter a city and budget.';
            errorEl.style.display = 'block';
            return;
        }

        errorEl.style.display = 'none';
        generateBtn.disabled = true;
        const originalHtml = generateBtn.innerHTML;
        generateBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Analyzing the market...';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                city: city,
                budget: budget,
                bhk_type: document.getElementById('ia-bhk-type').value,
                goal: document.getElementById('ia-goal').value,
            }),
        })
        .then(res => res.json().then(data => ({ ok: res.ok, data })))
        .then(({ ok, data }) => {
            if (!ok || data.error) {
                errorEl.textContent = data.message || 'Could not generate an analysis. Please try again.';
                errorEl.style.display = 'block';
                return;
            }

            document.getElementById('ia-comp-count').textContent = data.comp_count;
            document.getElementById('ia-avg-price').textContent = formatINR(data.avg_sale_price);
            document.getElementById('ia-yield').textContent = data.rental_yield ? data.rental_yield + '%' : 'N/A';
            document.getElementById('ia-narrative').textContent = data.narrative || '';

            const listingsWrap = document.getElementById('ia-listings-wrap');
            const listingsEl = document.getElementById('ia-listings');
            if (data.matching_listings && data.matching_listings.length) {
                listingsEl.innerHTML = data.matching_listings.map(function (p) {
                    return '<a href="' + propertyUrlTemplate.replace('__SLUG__', p.slug) + '" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">'
                        + '<span>' + p.title + ' <span class="text-muted small">(' + (p.bhk_type || '') + ')</span></span>'
                        + '<span class="fw-semibold" style="color:#059669;">' + formatINR(p.price) + '</span>'
                        + '</a>';
                }).join('');
                listingsWrap.style.display = 'block';
            } else {
                listingsWrap.style.display = 'none';
            }

            document.getElementById('ia-form-state').style.display = 'none';
            document.getElementById('ia-result').style.display = 'block';
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
