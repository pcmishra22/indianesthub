@extends('frontend.layout')

@section('title', $product->name . ' – ' . $product->price_label . ' | ' . config('app.name'))
@section('meta_description', Str::limit(strip_tags($product->description ?? $category->tagline), 155))
@section('canonical', route('marketplace.product', ['category' => $category, 'product' => $product]))

@section('og_image', $product->cover_url)

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "Product",
  "name": {!! json_encode($product->name) !!},
  "image": {!! json_encode($product->cover_url) !!},
  "description": {!! json_encode(Str::limit(strip_tags($product->description ?? ''), 300)) !!},
  "offers": {
    "@type": "Offer",
    "priceCurrency": "INR",
    "price": "{{ $product->price_min ?: $product->price_max ?: 0 }}",
    "availability": "https://schema.org/InStock",
    "seller": { "@type": "Organization", "name": {!! json_encode($product->vendor?->business_name) !!} }
  }
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org","@type":"BreadcrumbList",
  "itemListElement":[
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Marketplace","item":"{{ route('marketplace.index') }}"},
    {"@type":"ListItem","position":3,"name":"{{ $category->name }}","item":"{{ route('marketplace.category', $category) }}"},
    {"@type":"ListItem","position":4,"name":"{{ $product->name }}","item":"{{ route('marketplace.product', ['category' => $category, 'product' => $product]) }}"}
  ]
}
</script>
@endsection

@push('styles')
<style>
  .mkt-gallery-main { aspect-ratio:4/3; background:#f1f5f9; border-radius:14px; overflow:hidden; }
  .mkt-gallery-main img { width:100%; height:100%; object-fit:cover; }
  .mkt-gallery-thumbs { display:flex; gap:8px; margin-top:8px; flex-wrap:wrap; }
  .mkt-gallery-thumbs img { width:64px; height:64px; object-fit:cover; border-radius:8px; cursor:pointer; border:2px solid transparent; }
  .mkt-gallery-thumbs img.active { border-color:#0078d4; }
  .mkt-vendor-card { background:#f8faff; border:1px solid #e2e8f0; border-radius:14px; padding:18px; }
  .mkt-quote-card { background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:20px; position:sticky; top:20px; }
  .mkt-bhk-chip { background:#e0f2fe; color:#0369a1; font-size:.72rem; font-weight:700; padding:3px 9px; border-radius:12px; margin-right:4px; }
  .mkt-tag { background:#f1f5f9; color:#475569; font-size:.72rem; font-weight:600; padding:3px 10px; border-radius:12px; margin-right:4px; display:inline-block; margin-bottom:4px; }
  .mkt-prod-card { background:#fff; border:1px solid #e2e8f0; border-radius:14px; overflow:hidden; height:100%; display:flex; flex-direction:column; text-decoration:none; color:inherit; }
  .mkt-prod-card:hover { color:inherit; box-shadow:0 8px 22px rgba(2,132,199,.12); }
  .mkt-prod-img { aspect-ratio:4/3; background:#f1f5f9; overflow:hidden; }
  .mkt-prod-img img { width:100%; height:100%; object-fit:cover; }
  .mkt-prod-body { padding:12px 14px 14px; }
</style>
@endpush

@section('content')
<section style="padding:32px 0 60px;">
  <div class="container">
    <nav class="small text-muted mb-4">
      <a href="{{ url('/') }}">Home</a> /
      <a href="{{ route('marketplace.index') }}">Marketplace</a> /
      <a href="{{ route('marketplace.category', $category) }}">{{ $category->name }}</a> /
      {{ $product->name }}
    </nav>

    <div class="row g-5">
      {{-- Gallery + description --}}
      <div class="col-lg-7">
        <div class="mkt-gallery-main">
          <img id="mkt-main-img" src="{{ $product->cover_url }}" alt="{{ $product->name }}">
        </div>
        @if($product->images->count() > 1)
        <div class="mkt-gallery-thumbs">
          @foreach($product->images as $i => $img)
            <img src="{{ $img->url }}" class="{{ $i === 0 ? 'active' : '' }}" onclick="document.getElementById('mkt-main-img').src=this.src; document.querySelectorAll('.mkt-gallery-thumbs img').forEach(t=>t.classList.remove('active')); this.classList.add('active');">
          @endforeach
        </div>
        @endif

        <div class="mt-4">
          <div class="mkt-prod-cat" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;color:#0284c7;font-weight:700;">{{ $category->name }}</div>
          <h1 style="font-size:1.6rem;font-weight:800;color:#0a2d5e;margin:6px 0;">{{ $product->name }}</h1>
          <div style="font-size:1.15rem;font-weight:800;color:#0a2d5e;">{{ $product->price_label }} <span class="small text-muted fw-normal">{{ $product->price_unit }}</span></div>

          @if($product->bhk_fit)
            <div class="mt-2">
              @foreach($product->bhk_fit as $b)<span class="mkt-bhk-chip">{{ $b }}BHK</span>@endforeach
            </div>
          @endif

          @if($product->description)
            <p class="mt-3" style="color:#475569;line-height:1.7;">{{ $product->description }}</p>
          @endif

          @if($product->tags)
            <div class="mt-2">
              @foreach($product->tags as $tag)<span class="mkt-tag">{{ $tag }}</span>@endforeach
            </div>
          @endif
        </div>

        {{-- Vendor card --}}
        <div class="mkt-vendor-card mt-4">
          <a href="{{ $product->vendor ? route('marketplace.vendor', $product->vendor) : '#' }}" class="d-flex align-items-center gap-3 text-decoration-none">
            @if($product->vendor?->logo)
              <img src="{{ asset('storage/'.$product->vendor->logo) }}" style="width:52px;height:52px;border-radius:50%;object-fit:cover;">
            @else
              <div style="width:52px;height:52px;border-radius:50%;background:#e0f2fe;display:flex;align-items:center;justify-content:center;"><i class="bi bi-shop text-primary fs-5"></i></div>
            @endif
            <div>
              <div style="font-weight:700;color:#0a2d5e;">
                {{ $product->vendor?->business_name }}
                @if($product->vendor?->is_verified)<i class="bi bi-patch-check-fill text-success" title="Verified vendor"></i>@endif
              </div>
              <div class="small text-muted">
                <i class="bi bi-geo-alt-fill"></i> {{ $product->vendor?->area ? $product->vendor->area . ', ' : '' }}{{ $product->vendor?->city }}
                @if($product->vendor?->years_in_business)
                  &middot; {{ $product->vendor->years_in_business }}+ yrs in business
                @endif
              </div>
              @if($product->vendor && $product->vendor->reviews_count > 0)
                <div class="small">
                  @for($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star{{ $i <= round($product->vendor->average_rating) ? '-fill' : '' }}" style="color:#ffd166;font-size:.7rem;"></i>
                  @endfor
                  <span class="text-muted">{{ $product->vendor->average_rating }} ({{ $product->vendor->reviews_count }})</span>
                </div>
              @endif
            </div>
          </a>
          @if($product->vendor?->description)
            <p class="small text-muted mt-2 mb-0">{{ Str::limit($product->vendor->description, 160) }}</p>
          @endif
          @if($product->vendor)
            <a href="{{ route('marketplace.vendor', $product->vendor) }}" class="small fw-semibold d-inline-block mt-2" style="color:#0078d4;">
              View full profile &amp; reviews <i class="bi bi-arrow-right"></i>
            </a>
          @endif
        </div>
      </div>

      {{-- Quote form --}}
      <div class="col-lg-5">
        <div class="mkt-quote-card">
          <h5 style="font-weight:800;color:#0a2d5e;"><i class="bi bi-whatsapp text-success me-1"></i> Get a Free Quote</h5>
          <p class="small text-muted">Vendor typically responds on WhatsApp within 2 hours.</p>

          <form id="marketplace-lead-form" method="POST" action="{{ route('marketplace.lead.submit') }}">
            @csrf
            <input type="hidden" name="vendor_id" value="{{ $product->vendor?->id }}">
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="row g-2">
              <div class="col-6">
                <label class="form-label small fw-semibold mb-1">BHK</label>
                <select name="bhk_type" class="form-select form-select-sm">
                  <option value="">Select</option>
                  @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}BHK">{{ $i }}BHK</option>
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
              <label class="form-label small fw-semibold mb-1">Preference / notes</label>
              <input type="text" name="fabric_preference" class="form-control form-control-sm" placeholder="e.g. color, style, budget">
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
              <textarea name="notes" class="form-control form-control-sm" rows="2"></textarea>
            </div>

            <button type="submit" id="ml-submit" class="btn btn-primary w-100 mt-3" style="border-radius:8px;font-weight:700;">
              <i class="bi bi-send-fill me-1"></i> Send to {{ $product->vendor?->business_name ?? 'Vendor' }}
            </button>

            <div class="form-text mt-2"><i class="bi bi-shield-check text-success me-1"></i>Your details go directly to the vendor.</div>
            <div id="ml-success" class="alert alert-success mt-3 mb-0" style="display:none;border-radius:8px;"></div>
          </form>
        </div>
      </div>
    </div>

    {{-- Related products --}}
    @if($relatedProducts->count())
    <div class="mt-5 pt-4" style="border-top:1px solid #e2e8f0;">
      <h4 style="font-weight:800;color:#0a2d5e;margin-bottom:20px;">More in {{ $category->name }}</h4>
      <div class="row g-4">
        @foreach($relatedProducts as $rp)
          <div class="col-lg-3 col-md-4 col-6">
            <a href="{{ route('marketplace.product', ['category' => $category, 'product' => $rp]) }}" class="mkt-prod-card d-block">
              <div class="mkt-prod-img">
                @if($rp->cover_image || $rp->images->count())
                  <img src="{{ $rp->cover_url }}" alt="{{ $rp->name }}" loading="lazy">
                @endif
              </div>
              <div class="mkt-prod-body">
                <div style="font-weight:700;font-size:.9rem;color:#0f172a;">{{ $rp->name }}</div>
                <div class="small text-muted mt-1">{{ $rp->price_label }}</div>
              </div>
            </a>
          </div>
        @endforeach
      </div>
    </div>
    @endif
  </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
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
      submit.disabled = false;
      submit.innerHTML = orig;
      if (data.success) {
        success.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> ' + data.message;
        success.style.display = 'block';
        form.reset();
      } else {
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
@endsection
