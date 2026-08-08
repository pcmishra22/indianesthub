@extends('frontend.layout')

@section('title', 'Property Auctions – Fair Price Discovery | ' . config('app.name'))
@section('meta_description', 'Browse live and upcoming property auctions on ' . config('app.name') . '. Verified sellers, transparent bidding, no distress-sale lowballing.')
@section('canonical', url()->current())

@section('content')

@php
  $fmt = function ($price) {
    if ($price === null) return '—';
    if ($price >= 10000000) return '₹' . number_format($price / 10000000, 2) . ' Cr';
    if ($price >= 100000)   return '₹' . number_format($price / 100000, 2) . ' L';
    return '₹' . number_format($price);
  };
  $img = function ($property) {
    if ($property && !empty($property->cover_image) && file_exists(storage_path('app/public/' . $property->cover_image)))
      return asset('storage/' . $property->cover_image);
    if ($property && $property->images && $property->images->isNotEmpty())
      return asset('storage/' . $property->images->first()->image_path);
    return asset('assets/img/real-estate/property-1.webp');
  };
@endphp

<section class="py-5" style="background:linear-gradient(135deg,#0a2d5e,#0078d4);">
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-lg-8">
        <span class="badge mb-3" style="background:rgba(255,255,255,.15); color:#fff; font-size:.75rem; padding:6px 14px; border-radius:20px;">
          <i class="bi bi-hammer me-1"></i> Property Auctions
        </span>
        <h1 class="fw-bold text-white mb-3" style="font-size:2rem;">Sell Urgently? Get a Fair Price — Not a Lowball.</h1>
        <p class="text-white-50 mb-4">
          Every listing here is document-verified by our team before it goes live. Bidders are KYC-checked with a refundable deposit,
          so sellers get real, competitive offers instead of a single dealer's take-it-or-leave-it price.
        </p>
        <a href="{{ route('auctions.submit.create') }}" class="btn btn-light fw-semibold px-4 py-2" style="border-radius:10px;">
          <i class="bi bi-plus-circle me-1"></i> List Your Property for Auction
        </a>
      </div>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container">

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h2 class="h4 fw-bold mb-4">Live &amp; Upcoming Auctions</h2>

    @if($auctions->isEmpty())
      <div class="text-center py-5 text-muted">
        <i class="bi bi-hammer" style="font-size:2.5rem;opacity:.3;"></i>
        <p class="mt-3 mb-0">No auctions are live right now. Check back soon, or list your own property above.</p>
      </div>
    @else
      <div class="row g-4">
        @foreach($auctions as $auction)
          @php $p = $auction->property; @endphp
          <div class="col-lg-4 col-md-6">
            <a href="{{ route('auctions.show', $auction) }}" class="text-decoration-none">
              <div class="card h-100 border-0 shadow-sm" style="border-radius:14px;overflow:hidden;">
                <div class="position-relative">
                  <img src="{{ $img($p) }}" class="w-100" style="height:190px;object-fit:cover;" alt="{{ $p->title ?? 'Property' }}">
                  <span class="position-absolute top-0 start-0 m-2 badge" style="background:{{ $auction->status === 'live' ? '#dc2626' : '#0078d4' }};">
                    @if($auction->status === 'live')
                      <i class="bi bi-broadcast me-1"></i> Live
                    @else
                      Starts {{ $auction->start_at?->format('d M, h:i A') }}
                    @endif
                  </span>
                </div>
                <div class="card-body">
                  <h3 class="h6 fw-bold text-dark mb-1">{{ \Illuminate\Support\Str::limit($p->title ?? 'Property', 45) }}</h3>
                  <p class="text-muted small mb-2"><i class="bi bi-geo-alt me-1"></i>{{ $p->city ?? '' }}</p>
                  <div class="d-flex justify-content-between align-items-end">
                    <div>
                      <div class="text-muted small">Current Bid</div>
                      <div class="fw-bold" style="color:#0078d4;">{{ $fmt($auction->current_highest_bid ?? $auction->starting_bid) }}</div>
                    </div>
                    <div class="text-end">
                      <div class="text-muted small">Ends</div>
                      <div class="fw-semibold small">{{ $auction->end_at?->diffForHumans() ?? '—' }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </a>
          </div>
        @endforeach
      </div>
      <div class="mt-4">{{ $auctions->links() }}</div>
    @endif

    @if($endedAuctions->isNotEmpty())
      <h2 class="h5 fw-bold mt-5 mb-4">Recently Sold</h2>
      <div class="row g-3">
        @foreach($endedAuctions as $auction)
          @php $p = $auction->property; @endphp
          <div class="col-lg-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm" style="border-radius:12px;overflow:hidden;">
              <img src="{{ $img($p) }}" class="w-100" style="height:100px;object-fit:cover;">
              <div class="p-2">
                <div class="small fw-semibold text-truncate">{{ $p->title ?? 'Property' }}</div>
                <div class="small" style="color:#16a34a;font-weight:700;">{{ $fmt($auction->current_highest_bid) }}</div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif

  </div>
</section>
@endsection
