@extends('frontend.layout')

@section('title', 'Bidder KYC Verification | ' . config('app.name'))
@section('content')

<section class="py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">

        <div class="text-center mb-4">
          <h1 class="h4 fw-bold">Bidder KYC Verification</h1>
          <p class="text-muted">A one-time check so every auction stays free of fake bids.</p>
        </div>

        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card border-0 shadow-sm" style="border-radius:14px;">
          <div class="card-body p-4">

            @if($user->kyc_status === 'verified')
              <div class="text-center py-3">
                <i class="bi bi-patch-check-fill" style="font-size:2.5rem;color:#16a34a;"></i>
                <p class="mt-3 mb-0 fw-semibold">Your KYC is verified. You're ready to bid on any auction.</p>
                <a href="{{ route('auctions.index') }}" class="btn mt-3" style="background:#0078d4;color:#fff;">Browse Auctions</a>
              </div>
            @elseif($user->kyc_status === 'pending')
              <div class="text-center py-3">
                <i class="bi bi-hourglass-split" style="font-size:2.5rem;color:#f59e0b;"></i>
                <p class="mt-3 mb-0 fw-semibold">Your KYC is under review.</p>
                <p class="text-muted small">This usually takes a few hours. We'll notify you once it's verified.</p>
              </div>
            @else
              @if($user->kyc_status === 'rejected' && $user->kyc_rejection_reason)
                <div class="alert alert-danger small">{{ $user->kyc_rejection_reason }}</div>
              @endif
              <form action="{{ route('auctions.kyc.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label class="form-label small fw-semibold">PAN Number <span class="text-danger">*</span></label>
                <input type="text" name="pan_number" class="form-control mb-3" maxlength="10" style="text-transform:uppercase;" placeholder="ABCDE1234F" required>

                <label class="form-label small fw-semibold">ID Proof (PAN Card / Aadhaar) <span class="text-danger">*</span></label>
                <input type="file" name="id_proof" class="form-control mb-3" accept=".pdf,.jpg,.jpeg,.png" required>

                <button type="submit" class="btn w-100 fw-semibold" style="background:#0078d4;color:#fff;">
                  Submit for Verification
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
