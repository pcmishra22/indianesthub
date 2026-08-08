@extends('frontend.layout')

@section('title', 'Upload Auction Documents | ' . config('app.name'))
@section('content')

<section class="py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">

        <div class="mb-4">
          <h1 class="h4 fw-bold mb-1">{{ $auction->property->title ?? 'Property' }}</h1>
          <span class="badge" style="background:#94a3b8;">{{ $auction->statusLabel() }}</span>
        </div>

        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row g-4">
          <div class="col-md-5">
            <div class="card border-0 shadow-sm" style="border-radius:14px;">
              <div class="card-body p-4">
                <h2 class="h6 fw-bold mb-3">Upload a Document</h2>
                <form action="{{ route('auctions.submit.documents.store', $auction) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <label class="form-label small fw-semibold">Document Type <span class="text-danger">*</span></label>
                  <select name="document_type" class="form-select mb-3" required>
                    @foreach($documentTypes as $key => $label)
                      <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                  </select>

                  <label class="form-label small fw-semibold">File (PDF/JPG/PNG, max 10MB) <span class="text-danger">*</span></label>
                  <input type="file" name="file" class="form-control mb-3" accept=".pdf,.jpg,.jpeg,.png" required>

                  <button type="submit" class="btn w-100 fw-semibold" style="background:#0078d4;color:#fff;">
                    <i class="bi bi-upload me-1"></i> Upload
                  </button>
                </form>
              </div>
            </div>
            <div class="alert alert-light border small mt-3">
              <strong>Required:</strong> Sale Deed, Ownership Proof, Identity Proof.<br>
              <strong>If applicable:</strong> Loan NOC, Encumbrance Certificate.
            </div>
          </div>

          <div class="col-md-7">
            <div class="card border-0 shadow-sm" style="border-radius:14px;">
              <div class="card-body p-4">
                <h2 class="h6 fw-bold mb-3">Uploaded Documents</h2>
                @if($auction->documents->isEmpty())
                  <p class="text-muted small mb-0">No documents uploaded yet.</p>
                @else
                  @foreach($auction->documents as $doc)
                    <div class="d-flex justify-content-between align-items-center p-2 mb-2" style="background:#f8fafc;border-radius:8px;">
                      <div>
                        <div class="fw-semibold small">{{ $doc->typeLabel() }}</div>
                        @if($doc->status === 'rejected' && $doc->admin_remarks)
                          <div class="text-danger small">{{ $doc->admin_remarks }}</div>
                        @endif
                      </div>
                      <span class="badge
                        @if($doc->status === 'approved') bg-success
                        @elseif($doc->status === 'rejected') bg-danger
                        @else bg-secondary @endif">
                        {{ ucfirst($doc->status) }}
                      </span>
                    </div>
                  @endforeach
                @endif
              </div>
            </div>

            @if($auction->status === \App\Models\Auction::STATUS_UNDER_REVIEW || $auction->status === \App\Models\Auction::STATUS_SUBMITTED)
              <div class="alert alert-info small mt-3 mb-0">
                <i class="bi bi-hourglass-split me-1"></i> Our team will review your documents and get back to you within 24–48 hours.
              </div>
            @endif
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
@endsection
