@extends('backend.layout')
@section('title', 'Bidder KYC Review')
@section('content')

<h1 class="h3 mb-3">Bidder KYC Review</h1>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle mb-0">
        <thead>
          <tr><th>Name</th><th>Phone</th><th>PAN</th><th>ID Proof</th><th>Submitted</th><th>Actions</th></tr>
        </thead>
        <tbody>
        @forelse($pending as $user)
          <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->phone }}</td>
            <td>{{ $user->pan_number }}</td>
            <td>
              @if($user->kyc_id_proof_path)
                <a href="{{ asset('storage/' . $user->kyc_id_proof_path) }}" target="_blank">View</a>
              @else — @endif
            </td>
            <td>{{ $user->kyc_submitted_at?->format('d M, h:i A') }}</td>
            <td>
              <form action="{{ route('admin.auction-kyc.approve', $user) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-sm btn-outline-success">Approve</button>
              </form>
              <button class="btn btn-sm btn-outline-danger" data-bs-toggle="collapse" data-bs-target="#kyc-reject-{{ $user->id }}">Reject</button>
              <div class="collapse mt-2" id="kyc-reject-{{ $user->id }}">
                <form action="{{ route('admin.auction-kyc.reject', $user) }}" method="POST" class="d-flex gap-2">
                  @csrf
                  <input type="text" name="reason" class="form-control form-control-sm" placeholder="Reason…" required>
                  <button class="btn btn-sm btn-danger">Submit</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center text-muted py-4">No pending KYC requests.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="mt-3">{{ $pending->links() }}</div>

@endsection
