@extends('builder.layout')

@section('title', 'Social Media Lead Capture — Builder Panel')

@section('content')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-0">Social Media Lead Capture</h1>
            <p class="text-muted mb-0">Connect your Facebook Page so leads from your Facebook &amp; Instagram ad campaigns land straight in your Leads dashboard.</p>
        </div>
        <a href="{{ route('builder.leads.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2">
            <i class="bi bi-people-fill"></i> View Leads
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(!$fbConfigured)
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-1"></i>
        Facebook integration isn't configured on the server yet. Please contact support.
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">

            @forelse($connections as $conn)
            <div class="card mb-3" style="border-radius:12px;border:1px solid #e2e8f0;">
                <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-facebook" style="color:#1877f2;font-size:1.4rem;"></i>
                        </div>
                        <div>
                            <div class="fw-bold">{{ $conn->page_name ?? $conn->page_id }}</div>
                            <div class="small text-muted">
                                {{ $conn->page_category ?? 'Facebook Page' }}
                                @if($conn->ig_username)
                                    &middot; <i class="bi bi-instagram"></i> {{ '@' . $conn->ig_username }}
                                @endif
                            </div>
                            <div class="small mt-1">
                                @if($conn->leadgen_subscribed)
                                    <span class="badge bg-success">Active — receiving leads automatically</span>
                                @else
                                    <span class="badge bg-warning text-dark">Connected, but lead notifications aren't enabled</span>
                                @endif
                                @if($conn->last_lead_at)
                                    <span class="text-muted ms-2">Last lead: {{ $conn->last_lead_at->diffForHumans() }}</span>
                                @endif
                            </div>
                            @if($conn->last_error)
                            <div class="small text-danger mt-1"><i class="bi bi-exclamation-circle"></i> {{ $conn->last_error }}</div>
                            @endif
                        </div>
                    </div>
                    <form action="{{ route('builder.social.disconnect', $conn->id) }}" method="POST" onsubmit="return confirm('Disconnect this Facebook Page? Leads from it will stop flowing into your dashboard.');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-x-circle me-1"></i> Disconnect
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="card mb-3" style="border-radius:12px;border:1px solid #e2e8f0;">
                <div class="card-body text-center py-5">
                    <i class="bi bi-facebook" style="font-size:2.5rem;color:#cbd5e1;"></i>
                    <h5 class="text-muted mt-3">No Facebook Page connected yet</h5>
                    <p class="text-muted small mb-4" style="max-width:440px;margin:0 auto;">
                        Connect the Facebook Page you run your project ads from. Once connected, every lead form
                        submission from your Facebook or Instagram ads will automatically appear in your Leads dashboard —
                        no manual export from Meta needed.
                    </p>
                    @if($fbConfigured)
                    <a href="{{ route('builder.social.connect') }}" class="btn btn-primary">
                        <i class="bi bi-facebook me-1"></i> Connect Facebook Page
                    </a>
                    @endif
                </div>
            </div>
            @endforelse

            @if($connections->count())
            <a href="{{ route('builder.social.connect') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Connect Another Page
            </a>
            @endif

        </div>

        <div class="col-lg-4">
            <div class="card" style="border-radius:12px;border:1px solid #e2e8f0;">
                <div class="card-body">
                    <h6 class="fw-bold mb-2"><i class="bi bi-info-circle me-1"></i> How this works</h6>
                    <ol class="small text-muted ps-3 mb-0">
                        <li class="mb-2">Connect the Facebook Page you run project ads from.</li>
                        <li class="mb-2">Create a Lead Ads campaign in Meta Ads Manager as usual, using your Page.</li>
                        <li class="mb-2">When someone submits your Facebook or Instagram lead form, it's captured here automatically — no manual export.</li>
                        <li>Manage it exactly like any other lead: call, WhatsApp, notes, follow-up, status, hot score.</li>
                    </ol>
                </div>
            </div>
            <div class="card mt-3" style="border-radius:12px;border:1px solid #e2e8f0;">
                <div class="card-body">
                    <h6 class="fw-bold mb-2 small text-muted text-uppercase">Note</h6>
                    <p class="small text-muted mb-0">
                        We connect to your Page's lead forms only — we don't create, edit, or run ad campaigns on your behalf.
                        Campaign creation and ad spend still happen in Meta Ads Manager.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
