@extends('frontend.layout')

@section('title', ($auction->property->title ?? 'Property Auction') . ' | ' . config('app.name'))
@section('meta_description', 'Bid on ' . ($auction->property->title ?? 'this property') . ' — document-verified auction on ' . config('app.name') . '.')
@section('canonical', url()->current())

@section('content')

@php
  $p = $auction->property;
  $fmt = function ($price) {
    if ($price === null) return '—';
    return '₹' . number_format($price, 0);
  };
  $img = function ($property) {
    if ($property && !empty($property->cover_image) && file_exists(storage_path('app/public/' . $property->cover_image)))
      return asset('storage/' . $property->cover_image);
    if ($property && $property->images && $property->images->isNotEmpty())
      return asset('storage/' . $property->images->first()->image_path);
    return asset('assets/img/real-estate/property-1.webp');
  };
  $minBid = $auction->minimumNextBid();
@endphp

<section class="py-4" style="background:#f8fafc;">
  <div class="container">

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-4">

      {{-- ── Left: property + documents ───────────────── --}}
      <div class="col-lg-7">
        <div class="card border-0 shadow-sm mb-4" style="border-radius:14px;overflow:hidden;">
          <img src="{{ $img($p) }}" class="w-100" style="height:360px;object-fit:cover;" alt="{{ $p->title ?? '' }}">
          <div class="card-body p-4">
            <span class="badge mb-2" style="background:{{ $auction->status === 'live' ? '#dc2626' : '#94a3b8' }};">
              {{ $auction->statusLabel() }}
            </span>
            <h1 class="h4 fw-bold mb-2">{{ $p->title ?? 'Property' }}</h1>
            <p class="text-muted mb-3"><i class="bi bi-geo-alt me-1"></i>{{ $p->address ?? '' }}{{ $p->city ? ', '.$p->city : '' }}</p>
            @if($auction->sale_reason_public && $auction->sale_reason)
              <div class="alert alert-light border small mb-3"><i class="bi bi-info-circle me-1"></i>Seller's note: {{ $auction->sale_reason }}</div>
            @endif
            <p class="mb-0" style="color:#475569;line-height:1.7;">{{ \Illuminate\Support\Str::limit($p->description ?? '', 500) }}</p>
          </div>
        </div>

        <div class="card border-0 shadow-sm mb-4" style="border-radius:14px;">
          <div class="card-body p-4">
            <h2 class="h6 fw-bold mb-3"><i class="bi bi-patch-check-fill me-1" style="color:#16a34a;"></i> Verification</h2>
            <div class="row g-2">
              @foreach($auction->verificationChecklist() as $item)
                <div class="col-6">
                  <div class="d-flex align-items-center gap-2 p-2" style="background:{{ $item['done'] ? '#f0fdf4' : '#f8fafc' }};border-radius:8px;">
                    <i class="bi {{ $item['done'] ? 'bi-check-circle-fill' : 'bi-circle' }}" style="color:{{ $item['done'] ? '#16a34a' : '#cbd5e1' }};"></i>
                    <span class="small fw-semibold" style="color:{{ $item['done'] ? '#166534' : '#94a3b8' }};">{{ $item['label'] }}</span>
                  </div>
                </div>
              @endforeach
            </div>
            <p class="text-muted small mt-3 mb-0">
              <i class="bi bi-info-circle me-1"></i>Each item above is independently checked by our team — we don't claim
              "100% legally verified," only exactly what's been confirmed.
            </p>
          </div>
        </div>

        <div class="card border-0 shadow-sm mb-4" style="border-radius:14px;">
          <div class="card-body p-4">
            <h2 class="h6 fw-bold mb-3"><i class="bi bi-clock-history me-1"></i> Bid History</h2>
            @if($bids->isEmpty())
              <p class="text-muted small mb-0">No bids yet — be the first.</p>
            @else
              <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                  <thead><tr><th>Bidder</th><th>Amount</th><th>Time</th></tr></thead>
                  <tbody>
                    @foreach($bids as $bid)
                      <tr @if($bid->is_winning) style="background:#f0fdf4;" @endif>
                        <td>{{ $bid->anonymousLabel() }} {{ $bid->is_winning ? '👑' : '' }}</td>
                        <td class="fw-semibold">{{ $fmt($bid->amount) }}</td>
                        <td class="text-muted small">{{ $bid->created_at->diffForHumans() }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <p class="text-muted small mt-2 mb-0"><i class="bi bi-shield-lock me-1"></i>Bidder identities are kept anonymous until the seller accepts a bid or opts to negotiate.</p>
            @endif
          </div>
        </div>

        @if($revealContact)
          <div class="card border-0 shadow-sm mb-4" style="border-radius:14px;border:1.5px solid #16a34a;">
            <div class="card-body p-4">
              <h2 class="h6 fw-bold mb-3" style="color:#16a34a;"><i class="bi bi-person-check-fill me-1"></i> Seller Contact</h2>
              <p class="mb-1"><strong>{{ $auction->sellerUser->name ?? '' }}</strong></p>
              <p class="mb-0 text-muted small">{{ $auction->sellerUser->phone ?? '' }} · {{ $auction->sellerUser->email ?? '' }}</p>
            </div>
          </div>
        @endif

      </div>

      {{-- ── Right: bidding panel ─────────────────────── --}}
      <div class="col-lg-5">
        <div class="card border-0 shadow-sm" style="border-radius:14px;position:sticky;top:90px;">
          <div class="card-body p-4">

            <div class="text-center mb-3">
              <div class="text-muted small">Current Highest Bid</div>
              <div class="fw-bold" style="font-size:2rem;color:#0078d4;">{{ $fmt($auction->current_highest_bid ?? $auction->starting_bid) }}</div>
              <div class="text-muted small">Starting Bid: {{ $fmt($auction->starting_bid) }}
                @if($auction->current_highest_bid !== null)
                  <span class="badge ms-1" style="background:{{ $auction->reserveMet() ? '#16a34a' : '#94a3b8' }};">{{ $auction->reserveStatusLabel() }}</span>
                @endif
              </div>
            </div>

            @if($auction->end_at)
              <div id="auction-countdown" class="text-center p-3 mb-3" style="background:#0a2340;border-radius:12px;color:#fff;" data-end="{{ $auction->end_at->toIso8601String() }}">
                <div class="small text-white-50 mb-1">
                  @if($auction->status === 'live') Auction Ends In @else Starts In @endif
                </div>
                <div class="fw-bold" style="font-size:1.4rem;letter-spacing:1px;" id="countdown-value">--:--:--:--</div>
              </div>
            @endif

            @if($canBid)
              <form action="{{ route('auctions.bid', $auction) }}" method="POST">
                @csrf
                <label class="form-label small fw-semibold">Your Bid (minimum ₹{{ number_format($minBid) }})</label>
                <div class="input-group mb-2">
                  <span class="input-group-text">₹</span>
                  <input type="number" step="1" min="{{ $minBid }}" name="amount" class="form-control" required>
                </div>
                <button type="submit" class="btn w-100 fw-semibold py-2" style="background:#0078d4;color:#fff;border-radius:10px;">
                  <i class="bi bi-hammer me-1"></i> Place Bid
                </button>
              </form>
            @else
              <div class="p-3 text-center" style="background:#f8fafc;border-radius:10px;">
                @if($blockReason === 'login')
                  <p class="small mb-2">Log in to place a bid on this auction.</p>
                  <a href="{{ route('login') }}" class="btn btn-sm" style="background:#0078d4;color:#fff;">Log In</a>
                @elseif($blockReason === 'self')
                  <p class="small mb-0 text-muted"><i class="bi bi-info-circle me-1"></i>This is your own auction listing.</p>
                @elseif($blockReason === 'kyc')
                  <p class="small mb-2"><i class="bi bi-person-badge me-1"></i>Complete KYC verification to bid.</p>
                  <a href="{{ route('auctions.kyc') }}" class="btn btn-sm" style="background:#0078d4;color:#fff;">Complete KYC</a>
                @elseif($blockReason === 'deposit')
                  <p class="small mb-2"><i class="bi bi-wallet2 me-1"></i>Pay a refundable EMD of {{ $fmt($auction->emdAmount()) }} to unlock bidding on this auction.</p>
                  <a href="{{ route('auctions.deposit', $auction) }}" class="btn btn-sm" style="background:#0078d4;color:#fff;">Pay Deposit</a>
                @else
                  <p class="small mb-0 text-muted">Bidding isn't open for this auction right now.</p>
                @endif
              </div>
            @endif

            <div class="mt-3 small text-muted">
              <i class="bi bi-shield-check me-1"></i> All bidders are KYC-verified and have paid a refundable deposit — no fake bids.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
(function () {
  var el = document.getElementById('auction-countdown');
  if (!el) return;
  var end = new Date(el.dataset.end).getTime();
  var valueEl = document.getElementById('countdown-value');
  function tick() {
    var diff = end - Date.now();
    if (diff <= 0) {
      valueEl.textContent = 'Ended';
      return;
    }
    var d = Math.floor(diff / 86400000);
    var h = Math.floor((diff % 86400000) / 3600000);
    var m = Math.floor((diff % 3600000) / 60000);
    var s = Math.floor((diff % 60000) / 1000);
    valueEl.textContent = d + 'd ' + String(h).padStart(2,'0') + ':' + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
    setTimeout(tick, 1000);
  }
  tick();
})();
</script>
@endsection
