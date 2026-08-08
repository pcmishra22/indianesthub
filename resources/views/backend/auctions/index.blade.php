@extends('backend.layout')
@section('title', 'Auctions')
@section('content')

<h1 class="h3 mb-3">Auctions</h1>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

<div class="mb-3 d-flex gap-2 flex-wrap">
  @php
    $statuses = ['' => 'All', 'submitted' => 'Submitted', 'under_review' => 'Under Review', 'changes_requested' => 'Changes Requested', 'approved' => 'Approved', 'live' => 'Live', 'pending_seller_decision' => 'Awaiting Seller', 'winner_confirmed' => 'Winner Confirmed', 'completed' => 'Completed', 'ended_unsold' => 'Unsold', 'cancelled' => 'Cancelled'];
  @endphp
  @foreach($statuses as $key => $label)
    <a href="{{ route('admin.auctions.index', $key ? ['status' => $key] : []) }}"
       class="btn btn-sm {{ request('status') === $key ? 'btn-primary' : 'btn-outline-secondary' }}">
      {{ $label }} @if($key && ($statusCounts[$key] ?? 0)) <span class="badge bg-light text-dark">{{ $statusCounts[$key] }}</span> @endif
    </a>
  @endforeach
</div>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle mb-0">
        <thead>
          <tr>
            <th>ID</th><th>Property</th><th>Seller</th><th>Status</th>
            <th>Reserve</th><th>Highest Bid</th><th>Ends</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
        @forelse($auctions as $auction)
          <tr>
            <td>{{ $auction->id }}</td>
            <td>{{ \Illuminate\Support\Str::limit($auction->property->title ?? '—', 30) }}</td>
            <td>{{ $auction->sellerUser->name ?? '—' }}</td>
            <td><span class="badge bg-secondary">{{ $auction->statusLabel() }}</span></td>
            <td>₹{{ number_format($auction->reserve_price) }}</td>
            <td>{{ $auction->current_highest_bid ? '₹'.number_format($auction->current_highest_bid) : '—' }}</td>
            <td>{{ $auction->end_at?->format('d M, h:i A') ?? '—' }}</td>
            <td><a href="{{ route('admin.auctions.show', $auction) }}" class="btn btn-sm btn-primary">Review</a></td>
          </tr>
        @empty
          <tr><td colspan="8" class="text-center text-muted py-4">No auctions found.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="mt-3">{{ $auctions->links() }}</div>

@endsection
