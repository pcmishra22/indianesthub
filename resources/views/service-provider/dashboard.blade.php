@extends('service-provider.layout')

@section('title', 'Dashboard')

@section('content')
<div class="p-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Welcome, {{ $provider->display_name }} 👋</h1>
            <p class="text-muted mb-0">
                Status:
                @if($provider->status === 'approved')
                    <span class="sp-status-pill sp-status-approved"><i class="bi bi-patch-check-fill me-1"></i>Approved</span>
                @elseif($provider->status === 'pending')
                    <span class="sp-status-pill sp-status-pending"><i class="bi bi-clock-history me-1"></i>Pending Review</span>
                @else
                    <span class="sp-status-pill sp-status-rejected">{{ ucfirst($provider->status) }}</span>
                @endif
            </p>
        </div>
        <a href="{{ route('service-provider.profile') }}" class="btn btn-primary">
            <i class="bi bi-pencil-square me-1"></i> Edit Profile
        </a>
    </div>

    @if($provider->status === 'pending')
    <div class="alert alert-warning d-flex align-items-start gap-2">
        <i class="bi bi-clock-history fs-5 mt-1"></i>
        <div>
            <strong>Your profile is awaiting admin verification.</strong><br>
            Complete your profile below to speed up approval — verified profiles get shown to customers first.
        </div>
    </div>
    @endif

    <div class="row g-4">
        {{-- Profile card --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    @if($provider->profile_photo)
                        <img src="{{ asset('storage/'.$provider->profile_photo) }}" class="rounded-circle mb-3" style="width:110px;height:110px;object-fit:cover;">
                    @else
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width:110px;height:110px;">
                            <i class="bi bi-person fs-1 text-muted"></i>
                        </div>
                    @endif
                    <h5 class="mb-0">{{ $provider->display_name }}</h5>
                    <p class="text-muted small mb-2"><i class="bi bi-geo-alt me-1"></i>{{ $provider->city }}</p>
                    <div class="d-flex flex-wrap justify-content-center gap-1 mb-3">
                        @forelse($provider->categories as $cat)
                            <span class="badge bg-light text-dark border"><i class="bi {{ $cat->icon }} me-1"></i>{{ $cat->name }}</span>
                        @empty
                            <span class="text-muted small">No services selected yet</span>
                        @endforelse
                    </div>
                    <a href="{{ route('service-provider.profile') }}" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-pencil-square me-1"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            {{-- Profile completeness --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0">Profile Completeness</h6>
                        @php
                            $fields = ['bio','years_experience','starting_price','profile_photo'];
                            $filled = collect($fields)->filter(fn($f) => !empty($provider->$f))->count();
                            $pct = (int) round(($filled / count($fields)) * 100);
                        @endphp
                        <span class="fw-bold {{ $pct == 100 ? 'text-success' : 'text-primary' }}">{{ $pct }}%</span>
                    </div>
                    <div class="progress mb-2" style="height:10px;">
                        <div class="progress-bar {{ $pct == 100 ? 'bg-success' : 'bg-primary' }}" style="width:{{ $pct }}%"></div>
                    </div>
                    <p class="small text-muted mb-0">Add a bio, experience, pricing and a profile photo to attract more leads.</p>
                </div>
            </div>

            {{-- Stats --}}
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <i class="bi bi-people fs-4 text-primary mb-1"></i>
                        <div class="fs-4 fw-bold">{{ $leadsReceived }}</div>
                        <div class="small text-muted">Leads Received</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <i class="bi bi-eye fs-4 text-primary mb-1"></i>
                        <div class="fs-4 fw-bold">{{ $provider->views_count }}</div>
                        <div class="small text-muted">Profile Views</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <i class="bi bi-star-fill fs-4 text-warning mb-1"></i>
                        <div class="fs-4 fw-bold">{{ $averageRating > 0 ? $averageRating : '—' }}</div>
                        <div class="small text-muted">{{ $reviewsCount }} {{ \Illuminate\Support\Str::plural('Review', $reviewsCount) }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm text-center p-3 h-100">
                        <i class="bi bi-images fs-4 text-primary mb-1"></i>
                        <div class="fs-4 fw-bold">{{ $portfolioCount }}</div>
                        <div class="small text-muted">Portfolio Items</div>
                    </div>
                </div>
            </div>

            {{-- Recent Reviews --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Recent Reviews</h6>
                        <a href="{{ route('service-provider.portfolio.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-images me-1"></i> Manage Portfolio
                        </a>
                    </div>
                    @forelse($recentReviews as $review)
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <div>
                                <div class="mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : ' text-muted' }}"></i>
                                    @endfor
                                </div>
                                <p class="small mb-0">{{ \Illuminate\Support\Str::limit($review->review_text, 120) }}</p>
                                <p class="small text-muted mb-0">— {{ $review->user->name ?? 'Anonymous' }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">No approved reviews yet. Reviews from customers will show here once approved.</p>
                    @endforelse
                </div>
            </div>

            {{-- Tips --}}
            <div class="card shadow-sm border-0" style="background:#f0f7ff;">
                <div class="card-body">
                    <h6 class="fw-bold mb-2"><i class="bi bi-lightbulb text-warning me-1"></i>Tips to Get More Leads</h6>
                    <ul class="small text-muted mb-0 ps-3">
                        <li>Add a clear profile photo — providers with photos get more trust from customers.</li>
                        <li>Write a detailed bio explaining your experience and specialties.</li>
                        <li>List accurate starting pricing so customers know what to expect.</li>
                        <li>Select every service category that applies to you for more visibility.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
