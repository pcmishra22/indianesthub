@extends('frontend.layout')

@section('title', 'Pay Bid Deposit | ' . config('app.name'))
@section('content')

<section class="py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">

        <div class="text-center mb-4">
          <h1 class="h4 fw-bold">Refundable Bid Deposit</h1>
          <p class="text-muted">{{ $auction->property->title ?? 'Property' }}</p>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius:14px;">
          <div class="card-body p-4">

            @if($existing && $existing->status === 'pending')
              <div class="text-center py-3">
                <i class="bi bi-hourglass-split" style="font-size:2.5rem;color:#f59e0b;"></i>
                <p class="mt-3 mb-0 fw-semibold">Deposit submitted — awaiting verification.</p>
                <p class="text-muted small">Transaction ID: {{ $existing->transaction_id }}</p>
              </div>
            @elseif($existing && $existing->status === 'completed')
              <div class="text-center py-3">
                <i class="bi bi-patch-check-fill" style="font-size:2.5rem;color:#16a34a;"></i>
                <p class="mt-3 mb-0 fw-semibold">Deposit verified. You can bid on this auction now.</p>
                <a href="{{ route('auctions.show', $auction) }}" class="btn mt-3" style="background:#0078d4;color:#fff;">Go Bid</a>
              </div>
            @else
              <div class="text-center mb-4">
                <div class="text-muted small">Deposit Required</div>
                <div class="fw-bold" style="font-size:2rem;color:#0078d4;">₹{{ number_format($depositAmount) }}</div>
                <p class="text-muted small mb-0">Fully refundable if you don't win the auction, or after the sale completes.</p>
              </div>

              <div class="text-center mb-4">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($upiUrl) }}" alt="UPI QR" style="border-radius:10px;">
                <p class="small text-muted mt-2 mb-0">Scan with any UPI app, or <a href="{{ $upiUrl }}">tap to pay on mobile</a></p>
              </div>

              <form action="{{ route('auctions.deposit.store', $auction) }}" method="POST">
                @csrf
                <label class="form-label small fw-semibold">UPI Transaction / Reference ID <span class="text-danger">*</span></label>
                <input type="text" name="transaction_id" class="form-control mb-3" placeholder="e.g. 123456789012" required>
                <button type="submit" class="btn w-100 fw-semibold" style="background:#0078d4;color:#fff;">
                  I've Paid — Submit for Verification
                </button>
              </form>
            @endif

          </div>
        </div>

      </div>
    </div>
  </div>
</section>
@endsection
