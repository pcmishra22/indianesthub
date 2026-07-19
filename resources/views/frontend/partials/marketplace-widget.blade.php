{{--
  Home Marketplace widget for the property details page.
  Renders 3 product cards matched to the property's BHK and a row of
  category tiles for the rest of the marketplace.

  Required variables:
    $property              Property model
    $marketplaceProducts   Collection of MarketplaceProduct (with vendor + category)
    $marketplaceCategories Collection of MarketplaceCategory

  If either collection is empty the widget hides itself cleanly.
--}}

@if(($marketplaceProducts ?? collect())->count() > 0 || ($marketplaceCategories ?? collect())->count() > 0)
@push('styles')
<style>
  .marketplace-widget { background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 60%); border: 1px solid #bae6fd; }
  .marketplace-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; }
  .marketplace-card  { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; display:flex; flex-direction:column; transition: transform .15s ease, box-shadow .15s ease; }
  .marketplace-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(2, 132, 199, 0.12); }
  .mc-image-wrap { position: relative; aspect-ratio: 4/3; background:#f1f5f9; overflow:hidden; }
  .mc-image-wrap img { width:100%; height:100%; object-fit:cover; }
  .mc-image-placeholder { width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#94a3b8; font-size:2.4rem; }
  .mc-badge { position:absolute; top:8px; left:8px; background:linear-gradient(135deg,#f59e0b,#ea580c); color:#fff; font-size:.65rem; font-weight:700; padding:3px 8px; border-radius:20px; }
  .mc-body { padding:12px 12px 14px; display:flex; flex-direction:column; flex:1; }
  .mc-cat  { font-size:.65rem; text-transform:uppercase; letter-spacing:.5px; color:#0284c7; font-weight:700; margin-bottom:4px; }
  .mc-title { font-weight:700; color:#0f172a; font-size:.95rem; line-height:1.25; min-height:2.4em; }
  .mc-vendor { font-size:.78rem; color:#64748b; margin-top:4px; }
  .mc-verified { color:#16a34a; margin-left:3px; }
  .mc-price { font-size:.85rem; color:#0f172a; margin-top:6px; display:flex; align-items:center; justify-content:space-between; gap:6px; flex-wrap:wrap; }
  .mc-bhk { display:inline-flex; gap:3px; flex-wrap:wrap; }
  .mc-bhk-chip { background:#e0f2fe; color:#0369a1; font-size:.65rem; font-weight:700; padding:2px 6px; border-radius:10px; }

  .mc-cat-row { display:flex; flex-wrap:wrap; gap:8px; }
  .mc-cat-tile {
    display:inline-flex; align-items:center; gap:6px;
    padding:6px 10px; border-radius:20px; font-size:.78rem; font-weight:600;
    background:#f1f5f9; color:#64748b; border:1px dashed #cbd5e1;
  }
  .mc-cat-tile.disabled { opacity:.65; }
  .mc-cat-tile i { font-size:.9rem; }
</style>
@endpush
<div class="pd-card marketplace-widget" id="marketplace-widget" data-property-id="{{ $property->id }}">
  <div class="pd-card-title d-flex align-items-center justify-content-between flex-wrap gap-2">
    <span><i class="bi bi-shop text-primary"></i> Furnish This Home</span>
    <small class="text-muted fw-normal">Curated vendors from {{ $property->city ?? 'your city' }}</small>
  </div>

  <p class="text-muted small mb-3" style="margin-top:-4px;">
    Get free measurements and quotes from verified local shops.
    <span class="d-none d-sm-inline">Vendors will WhatsApp you within 2 hours.</span>
  </p>

  @if(($marketplaceProducts ?? collect())->count() > 0)
  <div class="marketplace-cards" data-bhk="{{ $property->bhk_type }}">
    @foreach($marketplaceProducts as $product)
      @php
        $vendor = $product->vendor;
      @endphp
      <div class="marketplace-card" data-product-id="{{ $product->id }}">
        <div class="mc-image-wrap">
          @if($product->cover_image || $product->images->count())
            <img src="{{ $product->cover_url }}" alt="{{ $product->name }}" loading="lazy">
          @else
            <div class="mc-image-placeholder"><i class="bi {{ $product->category?->icon ?? 'bi-shop' }}"></i></div>
          @endif
          @if($product->is_featured)
            <span class="mc-badge"><i class="bi bi-star-fill"></i> Featured</span>
          @endif
        </div>

        <div class="mc-body">
          <div class="mc-cat">{{ $product->category?->name }}</div>
          <div class="mc-title">{{ $product->name }}</div>

          <div class="mc-vendor">
            <i class="bi bi-shop"></i> {{ $vendor?->business_name ?? 'Verified Vendor' }}
            @if($vendor?->is_verified)
              <span class="mc-verified" title="Verified vendor"><i class="bi bi-patch-check-fill"></i></span>
            @endif
          </div>

          <div class="mc-price">
            <strong>{{ $product->price_label }}</strong>
            @if($product->bhk_fit)
              <span class="mc-bhk">
                @foreach($product->bhk_fit as $b)
                  <span class="mc-bhk-chip">{{ $b }}BHK</span>
                @endforeach
              </span>
            @endif
          </div>

          <button type="button"
                  class="btn btn-primary w-100 mt-2 mc-quote-btn"
                  style="border-radius:8px;font-weight:600;padding:8px;"
                  data-vendor-id="{{ $vendor?->id }}"
                  data-vendor-name="{{ $vendor?->business_name }}"
                  data-product-id="{{ $product->id }}"
                  data-product-name="{{ $product->name }}"
                  data-bs-toggle="modal"
                  data-bs-target="#marketplaceLeadModal">
            <i class="bi bi-whatsapp me-1"></i> Get Free Quote
          </button>
        </div>
      </div>
    @endforeach
  </div>
  @endif

  @if(($marketplaceCategories ?? collect())->count() > 0)
    @php
      $activeOthers = $marketplaceCategories->filter(fn($c) => $c->product_count === 0)->values();
    @endphp
    @if($activeOthers->count() > 0)
    <div class="marketplace-cats mt-3 pt-3" style="border-top:1px dashed #e2e8f0;">
      <div class="small text-muted mb-2 fw-semibold" style="text-transform:uppercase;letter-spacing:.4px;font-size:.72rem;">
        More categories — coming soon
      </div>
      <div class="mc-cat-row">
        @foreach($activeOthers as $cat)
          <div class="mc-cat-tile disabled" title="Launching soon">
            <i class="bi {{ $cat->icon ?? 'bi-grid' }}"></i>
            <span>{{ $cat->name }}</span>
          </div>
        @endforeach
      </div>
    </div>
    @endif
  @endif
</div>

{{-- Lead capture modal — one shared instance, populated by clicking any quote button --}}
<div class="modal fade" id="marketplaceLeadModal" tabindex="-1" aria-labelledby="marketplaceLeadModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:14px;border:none;">
      <div class="modal-header" style="background:linear-gradient(135deg,#0a2d5e 0%,#0078d4 100%);color:#fff;">
        <h5 class="modal-title" id="marketplaceLeadModalLabel">
          <i class="bi bi-whatsapp me-2"></i>Get Free Quote
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="marketplace-lead-form" method="POST" action="{{ route('marketplace.lead.submit') }}">
        @csrf
        <input type="hidden" name="property_id" value="{{ $property->id }}">
        <input type="hidden" name="vendor_id"   id="ml-vendor-id">
        <input type="hidden" name="product_id"  id="ml-product-id">
        <div class="modal-body">
          <div class="alert alert-light border mb-3 py-2" style="background:#f0f9ff;">
            <small class="text-muted">For</small>
            <div class="fw-semibold" id="ml-product-label">—</div>
            <small class="text-muted" id="ml-vendor-label"></small>
          </div>

          <div class="row g-2">
            <div class="col-6">
              <label class="form-label small fw-semibold mb-1">BHK</label>
              <select name="bhk_type" class="form-select form-select-sm">
                <option value="">Select</option>
                @for($i = 1; $i <= 5; $i++)
                  <option value="{{ $i }}BHK" {{ ($property->bhk_type === $i.'BHK') ? 'selected' : '' }}>{{ $i }}BHK</option>
                @endfor
                <option value="Studio">Studio</option>
                <option value="Villa">Villa</option>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label small fw-semibold mb-1">Windows (approx)</label>
              <input type="number" name="window_count" min="0" max="50" class="form-control form-control-sm" placeholder="e.g. 6">
            </div>
          </div>

          <div class="mt-2">
            <label class="form-label small fw-semibold mb-1">Fabric preference</label>
            <input type="text" name="fabric_preference" class="form-control form-control-sm" placeholder="e.g. eyelet, sheer, blackout">
          </div>

          <div class="row g-2 mt-1">
            <div class="col-12">
              <label class="form-label small fw-semibold mb-1">Full Name *</label>
              <input type="text" name="name" class="form-control form-control-sm" required>
            </div>
            <div class="col-6">
              <label class="form-label small fw-semibold mb-1">Phone *</label>
              <input type="tel" name="phone" class="form-control form-control-sm" required>
            </div>
            <div class="col-6">
              <label class="form-label small fw-semibold mb-1">Email</label>
              <input type="email" name="email" class="form-control form-control-sm">
            </div>
          </div>

          <div class="mt-2">
            <label class="form-label small fw-semibold mb-1">Anything else?</label>
            <textarea name="notes" class="form-control form-control-sm" rows="2" placeholder="Color, style, budget, timeline..."></textarea>
          </div>

          <div class="form-text mt-2">
            <i class="bi bi-shield-check text-success me-1"></i>
            Your details go directly to the vendor. IndiaNestHub may follow up to assist.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" id="ml-submit" class="btn btn-primary" style="border-radius:8px;font-weight:600;">
            <i class="bi bi-send-fill me-1"></i> Send to Vendor
          </button>
        </div>
        <div id="ml-success" class="alert alert-success mx-3 mb-3" style="display:none;border-radius:8px;"></div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const widget = document.getElementById('marketplace-widget');
  if (!widget) return;

  // Wire each "Get Free Quote" button to populate the shared modal
  widget.querySelectorAll('.mc-quote-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      document.getElementById('ml-vendor-id').value      = btn.dataset.vendorId || '';
      document.getElementById('ml-product-id').value     = btn.dataset.productId || '';
      document.getElementById('ml-product-label').textContent = btn.dataset.productName || 'Inquiry';
      const vendorLabel = document.getElementById('ml-vendor-label');
      vendorLabel.textContent = btn.dataset.vendorName ? 'Vendor: ' + btn.dataset.vendorName : '';
      document.getElementById('ml-success').style.display = 'none';
    });
  });

  // AJAX submit for the lead form
  const form = document.getElementById('marketplace-lead-form');
  if (!form) return;
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    const submit = document.getElementById('ml-submit');
    const success = document.getElementById('ml-success');
    const orig = submit.innerHTML;
    submit.disabled = true;
    submit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';

    fetch(form.action, {
      method: 'POST',
      body: new FormData(form),
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        success.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> ' + data.message;
        success.style.display = 'block';
        form.reset();
        setTimeout(() => {
          const modal = bootstrap.Modal.getInstance(document.getElementById('marketplaceLeadModal'));
          if (modal) modal.hide();
          submit.disabled = false;
          submit.innerHTML = orig;
        }, 2200);
      } else {
        submit.disabled = false;
        submit.innerHTML = orig;
        alert(data.message || 'Could not send. Please try again.');
      }
    })
    .catch(() => {
      submit.disabled = false;
      submit.innerHTML = orig;
      alert('Could not send. Please try again.');
    });
  });
});
</script>
@endpush
@endif
