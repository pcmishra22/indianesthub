@extends('frontend.layout')

@section('title', 'Auction Decision | ' . config('app.name'))
@section('content')

@php
  $fmt = fn ($v) => $v === null ? '—' : '₹' . number_format($v);
  $options = $auction->availableSellerDecisions();
@endphp

<section class="py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-7">

        <div class="text-center mb-4">
          <h1 class="h4 fw-bold">{{ $auction->property->title ?? 'Property' }}</h1>
          <p class="text-muted mb-0">Your auction has ended — here's the outcome and your options.</p>
        </div>

        <div class="card border-0 shadow-sm mb-4" style="border-radius:14px;">
          <div class="card-body p-4 text-center">
            <div class="text-muted small">Highest Bid Received</div>
            <div class="fw-bold" style="font-size:2rem;color:#0078d4;">{{ $fmt($auction->current_highest_bid) }}</div>
            <span class="badge" style="background:{{ $auction->reserveMet() ? '#16a34a' : '#dc2626' }};">
              {{ $auction->reserveStatusLabel() }}
            </span>
            <p class="text-muted small mt-3 mb-0">Your reserve price was ₹{{ number_format($auction->reserve_price) }} — only visible to you.</p>
          </div>
        </div>

        @if(!$auction->current_highest_bid)
          <div class="alert alert-warning text-center">
            No bids were received on this auction. You can request a re-auction below.
          </div>
        @endif

        <div class="card border-0 shadow-sm" style="border-radius:14px;">
          <div class="card-body p-4">
            <h2 class="h6 fw-bold mb-3">What would you like to do?</h2>

            <form action="{{ route('auctions.decision.store', $auction) }}" method="POST">
              @csrf
              <div class="d-grid gap-3">

                @if(in_array(\App\Models\Auction::DECISION_ACCEPTED, $options))
                  <button type="submit" name="decision" value="accepted" class="btn text-start p-3" style="background:#f0fdf4;border:1.5px solid #16a34a;border-radius:12px;">
                    <div class="fw-bold" style="color:#166534;"><i class="bi bi-check-circle-fill me-1"></i> Accept the Winning Bid</div>
                    <div class="small text-muted">Confirm the sale to the highest bidder — their contact details unlock and you can proceed to sale agreement & registration.</div>
                  </button>
                @endif

                @if(in_array(\App\Models\Auction::DECISION_NEGOTIATING, $options))
                  <button type="submit" name="decision" value="negotiating" class="btn text-start p-3" style="background:#eff6ff;border:1.5px solid #0078d4;border-radius:12px;">
                    <div class="fw-bold" style="color:#0078d4;"><i class="bi bi-chat-dots-fill me-1"></i> Negotiate With Highest Bidder</div>
                    <div class="small text-muted">Their contact details unlock so you can discuss a final price directly, without formally accepting yet.</div>
                  </button>
                @endif

                @if(in_array(\App\Models\Auction::DECISION_REAUCTION, $options))
                  <button type="submit" name="decision" value="reauction_requested" class="btn text-start p-3" style="background:#fff7ed;border:1.5px solid #ea580c;border-radius:12px;">
                    <div class="fw-bold" style="color:#c2410c;"><i class="bi bi-arrow-repeat me-1"></i> Request a Re-auction</div>
                    <div class="small text-muted">Our team will review and schedule a fresh bidding window — useful if you'd like to adjust your reserve or try again.</div>
                  </button>
                @endif

                @if(in_array(\App\Models\Auction::DECISION_REJECTED, $options))
                  <button type="submit" name="decision" value="rejected" class="btn text-start p-3" style="background:#fef2f2;border:1.5px solid #dc2626;border-radius:12px;">
                    <div class="fw-bold" style="color:#b91c1c;"><i class="bi bi-x-circle-fill me-1"></i> Close Without Selling</div>
                    <div class="small text-muted">End this auction as unsold. You can always list the property again later.</div>
                  </button>
                @endif

              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
@endsection
