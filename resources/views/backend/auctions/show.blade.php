@extends('backend.layout')
@section('title', 'Review Auction')
@section('content')

@php $p = $auction->property; @endphp

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">{{ $p->title ?? 'Property' }}</h1>
  <span class="badge bg-secondary fs-6">{{ $auction->statusLabel() }}</span>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

<div class="row g-3">

  {{-- Documents --}}
  <div class="col-lg-7">
    <div class="card mb-3">
      <div class="card-header fw-bold">Verification Checklist</div>
      <div class="card-body">
        @foreach($auction->verificationChecklist() as $i => $item)
          <div class="d-flex justify-content-between align-items-center border-bottom py-2">
            <span>
              <i class="bi {{ $item['done'] ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }} me-1"></i>
              {{ $item['label'] }}
            </span>
            @if(in_array($item['label'], ['Property Tax Checked', 'Site Verification', 'Legal Report Available']) && !$item['done'])
              @php
                $flag = ['Property Tax Checked' => 'property_tax', 'Site Verification' => 'site', 'Legal Report Available' => 'legal'][$item['label']];
              @endphp
              <form action="{{ route('admin.auctions.verification', $auction) }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="flag" value="{{ $flag }}">
                <button class="btn btn-sm btn-outline-success">Mark Done</button>
              </form>
            @endif
          </div>
        @endforeach
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-header fw-bold">Documents</div>
      <div class="card-body">
        @if($auction->documents->isEmpty())
          <p class="text-muted mb-0">No documents uploaded yet.</p>
        @else
          @foreach($auction->documents as $doc)
            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
              <div>
                <div class="fw-semibold">{{ $doc->typeLabel() }}</div>
                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="small">View File</a>
                @if($doc->admin_remarks)
                  <div class="text-muted small">Remarks: {{ $doc->admin_remarks }}</div>
                @endif
              </div>
              <div class="d-flex align-items-center gap-2">
                <span class="badge
                  @if($doc->status === 'approved') bg-success
                  @elseif($doc->status === 'rejected') bg-danger
                  @else bg-secondary @endif">
                  {{ ucfirst($doc->status) }}
                </span>
                @if($doc->status === 'pending')
                  <form action="{{ route('admin.auction-documents.approve', $doc) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-outline-success">Approve</button>
                  </form>
                  <button class="btn btn-sm btn-outline-danger" data-bs-toggle="collapse" data-bs-target="#reject-{{ $doc->id }}">Reject</button>
                @endif
              </div>
            </div>
            @if($doc->status === 'pending')
              <div class="collapse" id="reject-{{ $doc->id }}">
                <form action="{{ route('admin.auction-documents.reject', $doc) }}" method="POST" class="d-flex gap-2 py-2">
                  @csrf
                  <input type="text" name="remarks" class="form-control form-control-sm" placeholder="Reason for rejection…" required>
                  <button class="btn btn-sm btn-danger">Submit</button>
                </form>
              </div>
            @endif
          @endforeach
        @endif
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-header fw-bold">Bids ({{ $auction->bids->count() }})</div>
      <div class="card-body">
        @if($auction->bids->isEmpty())
          <p class="text-muted mb-0">No bids yet.</p>
        @else
          <table class="table table-sm mb-0">
            <thead><tr><th>Bidder</th><th>Amount</th><th>Time</th></tr></thead>
            <tbody>
              @foreach($auction->bids as $bid)
                <tr @if($bid->is_winning) class="table-success" @endif>
                  <td>{{ $bid->bidder->name ?? '—' }}</td>
                  <td>₹{{ number_format($bid->amount) }}</td>
                  <td>{{ $bid->created_at->format('d M, h:i A') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @endif
      </div>
    </div>

    <div class="card">
      <div class="card-header fw-bold">Deposits</div>
      <div class="card-body">
        @if($auction->deposits->isEmpty())
          <p class="text-muted mb-0">No deposits submitted yet.</p>
        @else
          <table class="table table-sm mb-0">
            <thead><tr><th>User</th><th>Amount</th><th>Txn ID</th><th>Status</th><th></th></tr></thead>
            <tbody>
              @foreach($auction->deposits as $deposit)
                <tr>
                  <td>{{ $deposit->user->name ?? '—' }}</td>
                  <td>₹{{ number_format($deposit->amount) }}</td>
                  <td>{{ $deposit->transaction_id }}</td>
                  <td><span class="badge bg-{{ $deposit->status === 'completed' ? 'success' : ($deposit->status === 'failed' ? 'danger' : 'secondary') }}">{{ ucfirst($deposit->status) }}</span></td>
                  <td>
                    @if($deposit->status === 'pending')
                      <form action="{{ route('admin.payments.approve', $deposit) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-outline-success">Verify</button>
                      </form>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @endif
      </div>
    </div>
  </div>

  {{-- Actions --}}
  <div class="col-lg-5">
    <div class="card mb-3">
      <div class="card-header fw-bold">Auction Details</div>
      <div class="card-body">
        <p class="mb-1"><strong>Seller:</strong> {{ $auction->sellerUser->name ?? '—' }} ({{ $auction->sellerUser->phone ?? '—' }})</p>
        <p class="mb-1"><strong>Reserve Price:</strong> ₹{{ number_format($auction->reserve_price) }} <span class="text-muted small">(never shown to bidders)</span></p>
        <p class="mb-1"><strong>Starting Bid:</strong> ₹{{ number_format($auction->starting_bid) }}</p>
        <p class="mb-1"><strong>Bid Increment:</strong> ₹{{ number_format($auction->bid_increment) }}</p>
        <p class="mb-1"><strong>EMD / Deposit:</strong> ₹{{ number_format($auction->emdAmount()) }}</p>
        @if($auction->duration_days_requested)<p class="mb-1"><strong>Seller Requested Duration:</strong> {{ $auction->duration_days_requested }} days</p>@endif
        @if($auction->sale_reason)<p class="mb-1"><strong>Reason:</strong> {{ $auction->sale_reason }} {!! $auction->sale_reason_public ? '<span class="badge bg-info">Public</span>' : '<span class="badge bg-secondary">Private</span>' !!}</p>@endif
        @if($auction->start_at)<p class="mb-1"><strong>Scheduled Start:</strong> {{ $auction->start_at->format('d M Y, h:i A') }}</p>@endif
        @if($auction->end_at)<p class="mb-1"><strong>Ends:</strong> {{ $auction->end_at->format('d M Y, h:i A') }}</p>@endif
        @if($auction->seller_decision)<p class="mb-1"><strong>Seller Decision:</strong> <span class="badge bg-dark">{{ ucfirst(str_replace('_',' ',$auction->seller_decision)) }}</span></p>@endif
        <p class="mb-0"><strong>Extensions used:</strong> {{ $auction->extension_count }} / {{ $auction->max_extensions }}</p>
      </div>
    </div>

    @if(in_array($auction->status, ['submitted','under_review','changes_requested']) || ($auction->status === 'approved' && !$auction->start_at))
      <div class="card mb-3">
        <div class="card-header fw-bold">Approve &amp; Schedule</div>
        <div class="card-body">
          @if(!$auction->documentsAllApproved())
            <p class="text-warning small"><i class="bi bi-exclamation-triangle"></i> Approve sale deed, ownership proof &amp; identity proof first.</p>
          @endif
          <form action="{{ route('admin.auctions.approve', $auction) }}" method="POST">
            @csrf
            <label class="form-label small">Start Date/Time</label>
            <input type="datetime-local" name="start_at" class="form-control mb-2" required>
            <label class="form-label small">Duration (hours)</label>
            <input type="number" name="duration_hours" class="form-control mb-2" value="{{ $auction->duration_days_requested ? $auction->duration_days_requested * 24 : 72 }}" min="1" required>
            <label class="form-label small">EMD / Deposit Amount (₹)</label>
            <input type="number" name="emd_amount" class="form-control mb-2" value="{{ $auction->emd_amount ?? '' }}" placeholder="Defaults to 1% of reserve if left blank" min="1000">
            <label class="form-label small">Admin Notes</label>
            <textarea name="admin_notes" class="form-control mb-3" rows="2"></textarea>
            <button class="btn btn-success w-100" {{ $auction->documentsAllApproved() ? '' : 'disabled' }}>Approve &amp; Schedule Auction</button>
          </form>
        </div>
      </div>

      <div class="card">
        <div class="card-header fw-bold">Reject Auction</div>
        <div class="card-body">
          <form action="{{ route('admin.auctions.reject', $auction) }}" method="POST">
            @csrf
            <textarea name="rejection_reason" class="form-control mb-2" rows="2" placeholder="Reason…" required></textarea>
            <button class="btn btn-danger w-100">Reject</button>
          </form>
        </div>
      </div>
    @endif

    @if($auction->status === 'live')
      <div class="card mb-3">
        <div class="card-header fw-bold">Manual Controls</div>
        <div class="card-body d-grid gap-2">
          <form action="{{ route('admin.auctions.force-end', $auction) }}" method="POST" onsubmit="return confirm('End this auction immediately?');">
            @csrf
            <button class="btn btn-outline-danger w-100">Force End Now</button>
          </form>
          <form action="{{ route('admin.auctions.cancel', $auction) }}" method="POST" onsubmit="return confirm('Cancel this auction?');">
            @csrf
            <button class="btn btn-outline-secondary w-100">Cancel Auction</button>
          </form>
        </div>
      </div>
    @endif

    @if($auction->status === 'pending_seller_decision')
      <div class="card mb-3">
        <div class="card-header fw-bold">Awaiting Seller</div>
        <div class="card-body">
          <p class="text-muted small mb-0">
            <i class="bi bi-hourglass-split me-1"></i>
            Auction has ended — seller needs to Accept, Negotiate, Reject, or request a Re-auction from their dashboard.
            No action needed here unless they contact support directly.
          </p>
        </div>
      </div>
    @endif

    @if($auction->status === 'winner_confirmed')
      <div class="card">
        <div class="card-header fw-bold">Finalize</div>
        <div class="card-body">
          <p class="text-muted small">Seller has accepted the winning bid. Once registration/handover is complete offline, mark this auction as fully completed.</p>
          <form action="{{ route('admin.auctions.mark-completed', $auction) }}" method="POST">
            @csrf
            <button class="btn btn-success w-100">Mark as Completed</button>
          </form>
        </div>
      </div>
    @endif
  </div>
</div>

@endsection
