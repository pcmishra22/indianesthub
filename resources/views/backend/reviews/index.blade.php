@extends('backend.layout')
@section('title', 'Reviews')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0"><i class="fas fa-star me-2 text-warning"></i>Reviews & Ratings</h4>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fw-bold fs-4">{{ $stats['total'] }}</div>
            <div class="small text-muted">Total</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center py-3" style="border-left:3px solid #f59e0b!important;">
            <div class="fw-bold fs-4 text-warning">{{ $stats['pending'] }}</div>
            <div class="small text-muted">Pending</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center py-3" style="border-left:3px solid #22c55e!important;">
            <div class="fw-bold fs-4 text-success">{{ $stats['approved'] }}</div>
            <div class="small text-muted">Approved</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center py-3" style="border-left:3px solid #ef4444!important;">
            <div class="fw-bold fs-4 text-danger">{{ $stats['rejected'] }}</div>
            <div class="small text-muted">Rejected</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center py-3" style="border-left:3px solid #f59e0b!important;">
            <div class="fw-bold fs-4" style="color:#f59e0b;">
                {{ $stats['avg_rating'] }} <i class="fas fa-star" style="font-size:.7em;"></i>
            </div>
            <div class="small text-muted">Avg Rating</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small mb-1">Status</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">Rating</label>
                <select name="rating" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Ratings</option>
                    @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Stars</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label small mb-1">Search</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Review text or user name"
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
            @if(request()->anyFilled(['status','rating','search']))
            <div class="col-md-2">
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="fas fa-times me-1"></i> Clear
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" style="font-size:.85rem;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Reviewer</th>
                        <th>Property</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td class="text-muted">{{ $review->id }}</td>
                    <td>
                        @if($review->user)
                        <div class="fw-semibold">{{ $review->user->name }}</div>
                        <div class="text-muted small">{{ $review->user->email }}</div>
                        @else
                        <span class="text-muted small">User #{{ $review->user_id }}</span>
                        @endif
                    </td>
                    <td>
                        @if($review->property)
                        <a href="{{ route('admin.properties.show', $review->property) }}"
                           class="text-primary small" target="_blank">
                            {{ Str::limit($review->property->title, 30) }}
                        </a>
                        @else
                        <span class="text-muted small">Property #{{ $review->property_id }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star"
                               style="font-size:.75rem;color:{{ $i <= $review->rating ? '#f59e0b' : '#e2e8f0' }};"></i>
                            @endfor
                        </div>
                        <div class="small text-muted mt-1">{{ $review->rating }}/5</div>
                    </td>
                    <td style="max-width:220px;">
                        <div style="overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                            {{ $review->review_text }}
                        </div>
                    </td>
                    <td>
                        @php
                            $badge = match($review->status) {
                                'approved' => 'success',
                                'rejected' => 'danger',
                                default    => 'warning text-dark',
                            };
                        @endphp
                        <span class="badge bg-{{ $badge }}">
                            {{ ucfirst($review->status ?? 'pending') }}
                        </span>
                    </td>
                    <td class="text-muted small">{{ $review->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            @if($review->status !== 'approved')
                            <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                                @csrf
                                <button class="btn btn-sm btn-success" title="Approve">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                            @if($review->status !== 'rejected')
                            <form method="POST" action="{{ route('admin.reviews.reject', $review) }}">
                                @csrf
                                <button class="btn btn-sm btn-warning" title="Reject">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('admin.reviews.show', $review) }}"
                               class="btn btn-sm btn-outline-primary" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}"
                                  onsubmit="return confirm('Delete this review permanently?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-star fa-2x mb-2 d-block opacity-25"></i>
                        No reviews found.
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $reviews->links() }}</div>

@endsection
