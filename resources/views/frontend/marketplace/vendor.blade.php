@extends('frontend.layout')

@section('title', $vendor->business_name . ' | ' . config('app.name'))
@section('meta_description', Str::limit(strip_tags($vendor->description ?? ''), 155))
@section('canonical', route('marketplace.vendor', $vendor))

@push('styles')
<style>
  .mvp-hero { background:linear-gradient(135deg,#0a2d5e 0%,#0078d4 100%); color:#fff; padding:40px 0; }
  .mvp-logo { width:88px; height:88px; border-radius:16px; object-fit:cover; background:#fff; box-shadow:0 6px 20px rgba(0,0,0,.2); }
  .mvp-card { background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:22px; }
  .mvp-badge { background:#eef4fb; color:#0a2d5e; padding:5px 13px; border-radius:20px; font-size:.76rem; font-weight:700; display:inline-flex; align-items:center; gap:5px; }
  .mvp-star-input { cursor:pointer; }
  .mvp-portfolio-img { aspect-ratio:1; object-fit:cover; border-radius:10px; width:100%; }
</style>
@endpush

@section('content')

<div class="mvp-hero">
  <div class="container">
    <div class="d-flex flex-column flex-md-row gap-3 align-items-start align-items-md-center">
      @if($vendor->logo)
        <img src="{{ asset('storage/' . $vendor->logo) }}" class="mvp-logo" alt="{{ $vendor->business_name }}">
      @else
        <div class="mvp-logo d-flex align-items-center justify-content-center" style="font-size:2rem;font-weight:800;color:#0a2d5e;">
          {{ Str::substr($vendor->business_name, 0, 1) }}
        </div>
      @endif
      <div>
        <h1 style="font-weight:800;font-size:1.5rem;margin-bottom:4px;">
          {{ $vendor->business_name }}
          @if($vendor->is_verified)
            <span class="badge bg-light text-primary ms-1" style="font-size:.7rem;"><i class="bi bi-patch-check-fill"></i> Verified</span>
          @endif
        </h1>
        <p class="mb-2" style="opacity:.9;"><i class="bi bi-geo-alt-fill"></i> {{ $vendor->area ? $vendor->area . ', ' : '' }}{{ $vendor->city }}</p>
        @if($vendor->reviews_count > 0)
          <p class="mb-0">
            @for($i = 1; $i <= 5; $i++)
              <i class="bi bi-star{{ $i <= round($vendor->average_rating) ? '-fill' : '' }}" style="color:#ffd166;"></i>
            @endfor
            <strong class="ms-1">{{ $vendor->average_rating }}</strong>
            <span style="opacity:.85;">({{ $vendor->reviews_count }} {{ Str::plural('review', $vendor->reviews_count) }})</span>
          </p>
        @endif
      </div>
    </div>
  </div>
</div>

<section style="padding:36px 0 60px;">
  <div class="container">
    <div class="row g-4">

      {{-- LEFT: about, portfolio, reviews, products --}}
      <div class="col-lg-8">

        <div class="mvp-card mb-4">
          <h5 class="fw-bold mb-3">About</h5>
          <p style="color:#475569;line-height:1.7;">{{ $vendor->description }}</p>
          <div class="d-flex flex-wrap gap-2 mt-2">
            @if($vendor->years_in_business)
              <span class="mvp-badge"><i class="bi bi-briefcase"></i> {{ $vendor->years_in_business }}+ years in business</span>
            @endif
            @if($vendor->gst_number)
              <span class="mvp-badge"><i class="bi bi-file-earmark-check"></i> GST: {{ $vendor->gst_number }}</span>
            @endif
          </div>
        </div>

        @if($vendor->portfolios->isNotEmpty())
          <div class="mvp-card mb-4">
            <h5 class="fw-bold mb-3">Work Done</h5>
            <div class="row g-2">
              @foreach($vendor->portfolios as $item)
                <div class="col-4 col-md-3">
                  <img src="{{ asset('storage/' . $item->image) }}" class="mvp-portfolio-img" alt="{{ $item->title }}" title="{{ $item->title }}">
                </div>
              @endforeach
            </div>
          </div>
        @endif

        @if($vendor->has_map_location)
          <div class="mvp-card mb-4">
            <h5 class="fw-bold mb-3">Location</h5>
            <div class="ratio ratio-16x9" style="border-radius:10px;overflow:hidden;">
              <iframe
                src="https://maps.google.com/maps?q={{ $vendor->latitude }},{{ $vendor->longitude }}&z=15&output=embed"
                loading="lazy" referrerpolicy="no-referrer-when-downgrade" style="border:0;"></iframe>
            </div>
          </div>
        @endif

        <div class="mvp-card mb-4">
          <h5 class="fw-bold mb-3">Products by {{ $vendor->business_name }}</h5>
          <div class="row g-3">
            @forelse($products as $product)
              <div class="col-6 col-md-4">
                <a href="{{ route('marketplace.product', ['category' => $product->category, 'product' => $product]) }}" class="mkt-prod-card d-block">
                  <div class="mkt-prod-img"><img src="{{ $product->cover_url }}" alt="{{ $product->name }}"></div>
                  <div class="mkt-prod-body">
                    <div class="small fw-semibold">{{ Str::limit($product->name, 40) }}</div>
                    <div class="small text-primary fw-bold">{{ $product->price_label }}</div>
                  </div>
                </a>
              </div>
            @empty
              <p class="text-muted small mb-0">No products listed yet.</p>
            @endforelse
          </div>
          @if($products->hasPages())
            <div class="mt-3">{{ $products->links() }}</div>
          @endif
        </div>

        <div class="mvp-card">
          <h5 class="fw-bold mb-3">Reviews ({{ $vendor->reviews_count }})</h5>

          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          @auth
            <form method="POST" action="{{ route('review.store') }}" class="mb-4 p-3" style="background:#f8fafc;border-radius:8px;">
              @csrf
              <input type="hidden" name="marketplace_vendor_id" value="{{ $vendor->id }}">
              <label class="form-label small fw-bold">Your Rating</label>
              <div class="mb-2">
                @for($i = 1; $i <= 5; $i++)
                  <label class="me-1 mvp-star-input">
                    <input type="radio" name="rating" value="{{ $i }}" class="d-none" required>
                    <i class="bi bi-star fs-5 mvp-star" data-value="{{ $i }}"></i>
                  </label>
                @endfor
              </div>
              <textarea name="review_text" class="form-control mb-2" rows="3" placeholder="Share your experience with this vendor..." required></textarea>
              <button type="submit" class="btn btn-primary btn-sm">Submit Review</button>
            </form>
          @else
            <p class="text-muted small mb-4"><a href="{{ route('login') }}">Log in</a> to write a review.</p>
          @endauth

          @forelse($reviews as $review)
            <div class="border-bottom py-3">
              <div class="mb-1">
                @for($i = 1; $i <= 5; $i++)
                  <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : ' text-muted' }}"></i>
                @endfor
                <strong class="ms-2">{{ $review->user->name ?? 'Anonymous' }}</strong>
                <span class="text-muted small ms-1">{{ $review->created_at->format('d M Y') }}</span>
              </div>
              <p class="mb-0" style="color:#475569;">{{ $review->review_text }}</p>
            </div>
          @empty
            <p class="text-muted small mb-0">No reviews yet — be the first to review this vendor.</p>
          @endforelse

          {{ $reviews->links() }}
        </div>

      </div>

      {{-- RIGHT: contact card --}}
      <div class="col-lg-4">
        <div class="mkt-quote-card">
          <h6 class="fw-bold mb-3">Contact {{ $vendor->business_name }}</h6>
          <a href="https://wa.me/91{{ preg_replace('/\D/','',$vendor->whatsapp ?: $vendor->phone) }}?text={{ urlencode('Hi, I found you on ' . config('app.name') . '.') }}"
             target="_blank" class="btn btn-success w-100 mb-2 contact-track" data-method="whatsapp">
            <i class="bi bi-whatsapp"></i> WhatsApp
          </a>
          <a href="tel:+91{{ preg_replace('/\D/','',$vendor->phone) }}" class="btn w-100 text-white contact-track" data-method="call" style="background:#0a2d5e;">
            <i class="bi bi-telephone"></i> Call Now
          </a>
        </div>
      </div>

    </div>
  </div>
</section>

<script>
document.querySelectorAll('.contact-track').forEach(function (el) {
  el.addEventListener('click', function () {
    fetch('{{ route("marketplace.vendor.contact-click", $vendor) }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      },
      body: JSON.stringify({ contact_method: el.dataset.method }),
      keepalive: true,
    });
  });
});

document.querySelectorAll('.mvp-star').forEach(function (star) {
  star.addEventListener('click', function () {
    var value = parseInt(this.dataset.value, 10);
    document.querySelectorAll('.mvp-star').forEach(function (s) {
      var active = parseInt(s.dataset.value, 10) <= value;
      s.classList.toggle('bi-star-fill', active);
      s.classList.toggle('bi-star', !active);
      s.classList.toggle('text-warning', active);
    });
    this.closest('label').querySelector('input').checked = true;
  });
});
</script>
@endsection
