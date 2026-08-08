@extends('frontend.layout')

@section('title', 'List Your Property for Auction | ' . config('app.name'))
@section('content')

<section class="py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-7">

        <div class="text-center mb-4">
          <h1 class="h4 fw-bold">List Your Property for Auction</h1>
          <p class="text-muted">Set a reserve price you're comfortable with — you'll never sell for less than that.</p>
        </div>

        @if(session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
          </div>
        @endif

        <div class="card border-0 shadow-sm" style="border-radius:14px;">
          <div class="card-body p-4">

            @if($eligibleProperties->isEmpty())
              <div class="text-center py-4">
                <i class="bi bi-house-x" style="font-size:2rem;opacity:.3;"></i>
                <p class="mt-3 text-muted">You don't have any properties listed under your account yet that are eligible for auction.</p>
                <a href="{{ url('/post-property') }}" class="btn" style="background:#0078d4;color:#fff;">Add a Property First</a>
              </div>
            @else
              <form action="{{ route('auctions.submit.store') }}" method="POST">
                @csrf

                <label class="form-label small fw-semibold">Select Property <span class="text-danger">*</span></label>
                <select name="property_id" class="form-select mb-3" required>
                  <option value="">Choose a property you own…</option>
                  @foreach($eligibleProperties as $property)
                    <option value="{{ $property->id }}" @selected(old('property_id') == $property->id)>
                      {{ $property->title }} — {{ $property->city }}
                    </option>
                  @endforeach
                </select>

                <div class="row g-3 mb-3">
                  <div class="col-md-6">
                    <label class="form-label small fw-semibold">Reserve Price (₹) <span class="text-danger">*</span></label>
                    <input type="number" name="reserve_price" class="form-control" min="1" value="{{ old('reserve_price') }}" required>
                    <div class="form-text">The minimum you're willing to accept. <strong>Never shown to bidders</strong> — they only see "Reserve Met / Not Met."</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label small fw-semibold">Starting Bid (₹) <span class="text-danger">*</span></label>
                    <input type="number" name="starting_bid" class="form-control" min="0" value="{{ old('starting_bid') }}" required>
                    <div class="form-text">Must be ≤ reserve price. This is what bidders see as the opening amount.</div>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label small fw-semibold">Minimum Bid Increment (₹)</label>
                  <input type="number" name="bid_increment" class="form-control" min="1000" value="{{ old('bid_increment', 10000) }}">
                </div>

                <div class="row g-3 mb-3">
                  <div class="col-md-6">
                    <label class="form-label small fw-semibold">Auction Duration <span class="text-danger">*</span></label>
                    <select name="duration_days_requested" class="form-select" required>
                      @foreach([3,5,7,10,14] as $days)
                        <option value="{{ $days }}" @selected(old('duration_days_requested', 7) == $days)>{{ $days }} days</option>
                      @endforeach
                    </select>
                    <div class="form-text">Our team confirms the exact start time when approving.</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label small fw-semibold">Bidder Deposit / EMD (₹)</label>
                    <input type="number" name="emd_amount" class="form-control" min="1000" placeholder="Auto-set to 1% of reserve if left blank" value="{{ old('emd_amount') }}">
                    <div class="form-text">Refundable amount bidders pay to unlock bidding — deters fake bids.</div>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label small fw-semibold">Why are you selling urgently? <span class="text-muted">(optional)</span></label>
                  <input type="text" name="sale_reason" class="form-control" maxlength="255" placeholder="e.g. Relocating for work, need funds urgently" value="{{ old('sale_reason') }}">
                  <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="sale_reason_public" value="1" id="reasonPublic" @checked(old('sale_reason_public'))>
                    <label class="form-check-label small" for="reasonPublic">Show this reason publicly on the auction page (optional — never required)</label>
                  </div>
                </div>

                <div class="alert alert-light border small">
                  <i class="bi bi-info-circle me-1"></i>
                  After you submit, you'll upload your sale deed, ownership proof, loan NOC (if applicable) and ID.
                  Our team verifies everything before the auction goes live — usually within 24–48 hours.
                </div>

                <button type="submit" class="btn w-100 fw-semibold py-2" style="background:#0078d4;color:#fff;border-radius:10px;">
                  Continue to Document Upload <i class="bi bi-arrow-right ms-1"></i>
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
