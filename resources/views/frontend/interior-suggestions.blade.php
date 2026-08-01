@extends('frontend.layout')

@section('title', 'AI Interior Design Suggestions | ' . config('app.name'))
@section('meta_description', 'Get instant AI-powered interior design suggestions for your home, plus real product recommendations from our Home Marketplace.')
@section('canonical', url()->current())

@section('content')
<section class="py-5" style="background:linear-gradient(135deg,#7c2d12,#ea580c);">
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-lg-8">
        <span class="badge mb-3" style="background:rgba(255,255,255,.15); color:#fff; font-size:.75rem; padding:6px 14px; border-radius:20px;">
          <i class="bi bi-stars me-1"></i> AI-Powered
        </span>
        <h1 class="fw-bold text-white mb-3" style="font-size:2rem;">Interior Design Suggestions</h1>
        <p class="text-white-50 mb-0">
          Tell us about your room and style — get practical ideas plus real products from our Home Marketplace.
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

            <div id="is-form-state">
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label fw-semibold small mb-1">Room</label>
                  <select id="is-room-type" class="form-select">
                    @foreach(['Living Room','Bedroom','Kitchen','Bathroom','Balcony','Kids Room','Home Office','Dining Room'] as $r)
                      <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold small mb-1">Style</label>
                  <select id="is-style" class="form-select">
                    @foreach(['Modern','Minimalist','Traditional Indian','Contemporary','Luxury','Boho','Industrial','Scandinavian'] as $s)
                      <option value="{{ $s }}">{{ $s }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold small mb-1">Budget Range</label>
                  <select id="is-budget" class="form-select">
                    <option value="Under ₹50,000">Under ₹50,000</option>
                    <option value="₹50,000 – ₹1,50,000">₹50,000 – ₹1,50,000</option>
                    <option value="₹1,50,000 – ₹3,00,000">₹1,50,000 – ₹3,00,000</option>
                    <option value="Above ₹3,00,000">Above ₹3,00,000</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold small mb-1">Home Size <span class="text-muted">(optional)</span></label>
                  <select id="is-bhk-type" class="form-select">
                    <option value="">Not specified</option>
                    @foreach(['1 BHK','2 BHK','3 BHK','4 BHK','5+ BHK'] as $bhk)
                      <option value="{{ $bhk }}">{{ $bhk }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <button type="button" id="is-generate-btn" class="btn w-100 fw-bold py-3"
                      style="background:linear-gradient(135deg,#7c2d12,#ea580c); color:#fff; border-radius:10px;">
                <i class="bi bi-magic me-2"></i> Get My Design Ideas
              </button>
              <div id="is-error" class="text-danger small mt-2 text-center" style="display:none;"></div>
            </div>

            <div id="is-result" style="display:none;">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0" style="color:#ea580c;"><i class="bi bi-palette me-2"></i>Your Design Ideas</h5>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('is-form-state').style.display='block'; document.getElementById('is-result').style.display='none';">
                  <i class="bi bi-arrow-counterclockwise me-1"></i> Start Over
                </button>
              </div>

              <div id="is-suggestions"></div>

              <div id="is-products-wrap" style="display:none;" class="mt-4">
                <h6 class="fw-bold small text-uppercase mb-3" style="color:#ea580c; letter-spacing:.03em;">
                  <i class="bi bi-bag-heart me-1"></i> Shop This Look
                </h6>
                <div class="row g-3" id="is-products"></div>
              </div>

              <p class="text-muted mt-4 mb-0" style="font-size:.72rem;">
                <i class="bi bi-info-circle me-1"></i>
                AI-generated suggestions for inspiration — always confirm measurements, structural feasibility, and final pricing before purchasing.
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
    const generateBtn = document.getElementById('is-generate-btn');
    if (!generateBtn) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const url = "{{ route('ai.interior-suggestions') }}";

    generateBtn.addEventListener('click', function () {
        const errorEl = document.getElementById('is-error');
        errorEl.style.display = 'none';
        generateBtn.disabled = true;
        const originalHtml = generateBtn.innerHTML;
        generateBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Designing your room...';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                room_type: document.getElementById('is-room-type').value,
                style: document.getElementById('is-style').value,
                budget: document.getElementById('is-budget').value,
                bhk_type: document.getElementById('is-bhk-type').value,
            }),
        })
        .then(res => res.json().then(data => ({ ok: res.ok, data })))
        .then(({ ok, data }) => {
            if (!ok || data.error) {
                errorEl.textContent = data.message || 'Could not generate suggestions. Please try again.';
                errorEl.style.display = 'block';
                return;
            }

            const suggWrap = document.getElementById('is-suggestions');
            suggWrap.innerHTML = data.suggestions.map(function (s, i) {
                return '<div class="d-flex gap-3 mb-3 p-3 rounded-3" style="background:#fff7ed;">'
                    + '<div class="fw-bold" style="color:#ea580c; font-size:1.1rem;">' + (i + 1) + '</div>'
                    + '<div><div class="fw-semibold small mb-1">' + s.title + '</div><div class="small text-muted">' + s.tip + '</div></div>'
                    + '</div>';
            }).join('');

            const productsWrap = document.getElementById('is-products-wrap');
            const productsEl = document.getElementById('is-products');
            if (data.products && data.products.length) {
                productsEl.innerHTML = data.products.map(function (p) {
                    const img = p.image
                        ? '<img src="' + p.image + '" class="w-100" style="height:120px; object-fit:cover; border-radius:8px 8px 0 0;" alt="' + p.name + '">'
                        : '<div class="d-flex align-items-center justify-content-center bg-light" style="height:120px; border-radius:8px 8px 0 0;"><i class="bi bi-image text-muted" style="font-size:1.5rem;"></i></div>';
                    return '<div class="col-6 col-md-4">'
                        + '<a href="' + p.url + '" class="text-decoration-none text-dark">'
                        + '<div class="border rounded-3 overflow-hidden h-100">'
                        + img
                        + '<div class="p-2">'
                        + '<div class="small fw-semibold text-truncate">' + p.name + '</div>'
                        + '<div class="small text-muted">' + p.price_label + '</div>'
                        + '</div></div></a></div>';
                }).join('');
                productsWrap.style.display = 'block';
            } else {
                productsWrap.style.display = 'none';
            }

            document.getElementById('is-form-state').style.display = 'none';
            document.getElementById('is-result').style.display = 'block';
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
