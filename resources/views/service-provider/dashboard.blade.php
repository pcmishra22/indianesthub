<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard | {{ config('app.name') }}</title>
    <link rel="shortcut icon" href="/backend/img/icons/icon-48x48.png" />
    <link href="/backend/css/app.css" rel="stylesheet">
</head>

<body>
<main class="content">
    <div class="container-fluid p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Welcome, {{ $provider->display_name }}</h1>
                <p class="text-muted mb-0">
                    Status:
                    @if($provider->status === 'approved')
                        <span class="badge bg-success">Approved</span>
                    @elseif($provider->status === 'pending')
                        <span class="badge bg-warning text-dark">Pending Review</span>
                    @else
                        <span class="badge bg-secondary">{{ ucfirst($provider->status) }}</span>
                    @endif
                </p>
            </div>
            <form method="POST" action="{{ route('service-provider.logout') }}">
                @csrf
                <button class="btn btn-outline-danger btn-sm">Logout</button>
            </form>
        </div>

        @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if($provider->status === 'pending')
        <div class="alert alert-warning">
            <i class="bi bi-clock-history me-2"></i>
            Your profile is awaiting admin verification. Complete your profile below to speed up approval —
            verified profiles get shown to customers first.
        </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        @if($provider->profile_photo)
                            <img src="{{ asset('storage/'.$provider->profile_photo) }}" class="rounded-circle mb-3" style="width:110px;height:110px;object-fit:cover;">
                        @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width:110px;height:110px;">
                                <i class="bi bi-person fs-1 text-muted"></i>
                            </div>
                        @endif
                        <h5 class="mb-0">{{ $provider->display_name }}</h5>
                        <p class="text-muted small mb-2">{{ $provider->city }}</p>
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
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Profile Completeness</h6>
                        @php
                            $fields = ['bio','years_experience','starting_price','profile_photo'];
                            $filled = collect($fields)->filter(fn($f) => !empty($provider->$f))->count();
                            $pct = (int) round(($filled / count($fields)) * 100);
                        @endphp
                        <div class="progress mb-2" style="height:10px;">
                            <div class="progress-bar bg-primary" style="width:{{ $pct }}%"></div>
                        </div>
                        <p class="small text-muted mb-0">{{ $pct }}% complete — add a bio, experience, pricing and a profile photo to attract more leads.</p>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm text-center p-3">
                            <div class="fs-4 fw-bold text-primary">0</div>
                            <div class="small text-muted">Leads Received</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm text-center p-3">
                            <div class="fs-4 fw-bold text-primary">0</div>
                            <div class="small text-muted">Profile Views</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm text-center p-3">
                            <div class="fs-4 fw-bold text-primary">{{ $provider->categories->count() }}</div>
                            <div class="small text-muted">Services Listed</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm text-center p-3">
                            <div class="fs-4 fw-bold text-primary">{{ $provider->is_verified ? 'Yes' : 'No' }}</div>
                            <div class="small text-muted">Verified Badge</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
<script src="/backend/js/app.js"></script>
</body>
</html>
