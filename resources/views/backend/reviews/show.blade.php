@extends('backend.layout')
@section('title', 'Review #' . $review->id)
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-light me-2">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        <span class="fs-5 fw-bold"><i class="fas fa-star me-2 text-warning"></i>Review #{{ $review->id }}</span>
    </div>
    @php
        $badge = match($review->status) { 'approved'=>'success','rejected'=>'danger',default=>'warning text-dark' };
    @endphp
    <span class="badge bg-{{ $badge }} px-3 py-2" style="font-size:.85rem;">
        {{ ucfirst($review->status ?? 'pending') }}
    </span>
</div>

<div class="row g-4">
    <div class="col-lg-8">

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star fa-lg" style="color:{{ $i <= $review->rating ? '#f59e0b' : '#e2e8f0' }};"></i>
                    @endfor
                    <span class="fw-bold fs-5 ms-1">{{ $review->rating }}/5</span>
                </div>
                <blockquote class="blockquote mb-0 p-3 rounded" style="background:#f8fafc;border-left:4px solid #f59e0b;">
                    <p class="mb-0" style="font-size:.95rem;line-height:1.7;">{{ $review->review_text }}</p>
                </blockquote>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold border-0 pb-0">
                <i class="fas fa-user me-2 text-primary"></i>Reviewer
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0" style="font-size:.88rem;">
                    <tr><td class="text-muted" width="150">Name</td>
                        <td class="fw-semibold">{{ $review->user?->name ?? 'User #'.$review->user_id }}</td></tr>
                    <tr><td class="text-muted">Email</td>
                        <td>{{ $review->user?->email ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Submitted</td>
                        <td>{{ $review->created_at->format('d M Y, h:i A') }}</td></tr>
                </table>
            </div>
        </div>

        @if($review->property)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold border-0 pb-0">
                <i class="fas fa-home me-2 text-info"></i>Property Reviewed
            </div>
            <div class="card-body">
                <a href="{{ route('admin.properties.show', $review->property) }}" class="text-primary fw-semibold" target="_blank">
                    {{ $review->property->title }}
                </a>
                <div class="text-muted small mt-1">{{ $review->property->city ?? '' }}</div>
            </div>
        </div>
        @endif

    </div>

    <div class="col-lg-4">

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold border-0 pb-0">
                <i class="fas fa-gavel me-2 text-primary"></i>Moderation
            </div>
            <div class="card-body d-grid gap-2">
                @if($review->status !== 'approved')
                <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                    @csrf
                    <button class="btn btn-success w-100">
                        <i class="fas fa-check-circle me-2"></i> Approve & Publish
                    </button>
                </form>
                @endif
                @if($review->status !== 'rejected')
                <form method="POST" action="{{ route('admin.reviews.reject', $review) }}">
                    @csrf
                    <button class="btn btn-warning w-100">
                        <i class="fas fa-ban me-2"></i> Reject Review
                    </button>
                </form>
                @endif
                @if($review->status === 'approved')
                <div class="alert alert-success py-2 mb-0 small">
                    <i class="fas fa-check-circle me-1"></i> This review is live on the site.
                </div>
                @elseif($review->status === 'rejected')
                <div class="alert alert-danger py-2 mb-0 small">
                    <i class="fas fa-ban me-1"></i> Hidden from visitors.
                </div>
                @else
                <div class="alert alert-warning py-2 mb-0 small">
                    <i class="fas fa-clock me-1"></i> Awaiting moderation.
                </div>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-top:3px solid #ef4444!important;">
            <div class="card-body py-3">
                <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}"
                      onsubmit="return confirm('Delete this review permanently?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                        <i class="fas fa-trash me-1"></i> Delete Review
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
