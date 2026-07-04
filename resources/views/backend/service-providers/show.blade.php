@extends('backend.layout')
@section('title', 'Service Provider Details')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0"><i class="fas fa-user-tie me-2 text-primary"></i>Service Provider</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.service-providers.edit', $provider) }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-edit me-1"></i>Edit
        </a>
        <form method="POST" action="{{ route('admin.service-providers.destroy', $provider) }}" class="d-inline">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this provider?')">
                <i class="fas fa-trash me-1"></i>Delete
            </button>
        </form>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    @if($provider->profile_photo)
                        <img src="{{ asset('storage/'.$provider->profile_photo) }}" style="width:72px;height:72px;object-fit:cover;" class="rounded-circle">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width:72px;height:72px;font-size:1.2rem;flex-shrink:0;">
                            {{ strtoupper(substr($provider->business_name ?: $provider->full_name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <div class="fs-5 fw-bold">{{ $provider->business_name ?: $provider->full_name }}</div>
                        <div class="text-muted small">{{ $provider->email }}</div>
                        <div class="text-muted small">{{ $provider->phone }}</div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <span class="text-muted">City</span>
                    <span class="fw-semibold">{{ $provider->city ?: '—' }}</span>
                </div>

                <div class="d-flex justify-content-between mt-2">
                    <span class="text-muted">Verified</span>
                    <span>
                        @if($provider->is_verified)
                            <span class="badge bg-success">Yes</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </span>
                </div>

                <div class="d-flex justify-content-between mt-2">
                    <span class="text-muted">Status</span>
                    <span>
                        @php
                            $sc = match($provider->status ?? 'pending') {
                                'approved' => 'success',
                                'pending' => 'warning',
                                'rejected' => 'danger',
                                'suspended' => 'secondary',
                                default => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $sc }}">{{ ucfirst($provider->status ?? 'pending') }}</span>
                    </span>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <span class="text-muted">Experience</span>
                    <span class="fw-semibold">{{ $provider->years_experience ?? '—' }}</span>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <span class="text-muted">Starting Price</span>
                    <span class="fw-semibold">
                        {{ $provider->starting_price ?? '—' }} {{ $provider->price_unit ?? '' }}
                    </span>
                </div>

                <hr>

                <div class="text-muted">Operating Areas</div>
                <div class="fw-semibold">
                    @if(is_array($provider->operating_areas) && count($provider->operating_areas))
                        @foreach($provider->operating_areas as $a)
                            <span class="badge bg-light text-dark border me-1 mb-1">{{ $a }}</span>
                        @endforeach
                    @else
                        —
                    @endif
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="fw-semibold text-muted small text-uppercase mb-2"><i class="fas fa-sticky-note me-1"></i>Bio</div>
                <div class="mb-4">{{ $provider->bio ?: '—' }}</div>

                <div class="fw-semibold text-muted small text-uppercase mb-2"><i class="fas fa-tags me-1"></i>Categories</div>
                <div>
                    @if($provider->categories && $provider->categories->count())
                        @foreach($provider->categories as $cat)
                            <span class="badge bg-primary me-1 mb-2">{{ $cat->name }}</span>
                        @endforeach
                    @else
                        —
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

