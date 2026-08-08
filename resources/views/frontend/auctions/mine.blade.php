@extends('frontend.layout')

@section('title', 'My Auctions | ' . config('app.name'))
@section('content')

<section class="py-5">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h4 fw-bold mb-0">My Auctions</h1>
      <a href="{{ route('auctions.submit.create') }}" class="btn btn-sm" style="background:#0078d4;color:#fff;">
        <i class="bi bi-plus-circle me-1"></i> New Auction
      </a>
    </div>

    @if($auctions->isEmpty())
      <div class="text-center py-5 text-muted">
        <i class="bi bi-hammer" style="font-size:2.5rem;opacity:.3;"></i>
        <p class="mt-3 mb-0">You haven't submitted any properties for auction yet.</p>
      </div>
    @else
      <div class="table-responsive">
        <table class="table align-middle">
          <thead><tr><th>Property</th><th>Status</th><th>Reserve</th><th>Highest Bid</th><th>Ends</th><th></th></tr></thead>
          <tbody>
            @foreach($auctions as $auction)
              <tr>
                <td>{{ $auction->property->title ?? '—' }}</td>
                <td><span class="badge bg-secondary">{{ $auction->statusLabel() }}</span></td>
                <td>₹{{ number_format($auction->reserve_price) }}</td>
                <td>{{ $auction->current_highest_bid ? '₹'.number_format($auction->current_highest_bid) : '—' }}</td>
                <td>{{ $auction->end_at?->format('d M, h:i A') ?? '—' }}</td>
                <td>
                  @if(in_array($auction->status, ['submitted','under_review','changes_requested']))
                    <a href="{{ route('auctions.submit.documents', $auction) }}" class="btn btn-sm btn-outline-primary">Documents</a>
                  @elseif($auction->status === 'pending_seller_decision')
                    <a href="{{ route('auctions.decision', $auction) }}" class="btn btn-sm" style="background:#f59e0b;color:#fff;">Decision Needed</a>
                  @else
                    <a href="{{ route('auctions.show', $auction) }}" class="btn btn-sm btn-outline-primary">View</a>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      {{ $auctions->links() }}
    @endif
  </div>
</section>
@endsection
