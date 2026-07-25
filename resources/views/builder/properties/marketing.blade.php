@extends('builder.layout')

@section('title', 'Marketing Studio')

@section('content')
<div class="container-fluid p-0">

    <div class="d-flex align-items-center gap-3 px-3 pt-3 mb-3">
        <a href="{{ route('builder.projects.show', $project) }}" class="btn btn-outline-secondary btn-sm">
            <i data-feather="arrow-left" style="width:14px;height:14px;"></i> Back to Project
        </a>
        <div>
            <h1 class="h3 mb-0 fw-bold">Marketing Studio</h1>
            <small class="text-muted">{{ $project->title }} &middot; {{ $property->title }}</small>
        </div>
    </div>

    <div class="px-3 pb-4">
        <div class="row">

            {{-- Brochure Generator --}}
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <i class="align-middle mb-2" data-feather="file-text" style="width:32px;height:32px;color:#1e3a5f;"></i>
                        <h5 class="card-title">Brochure Generator</h5>
                        <p class="card-text text-muted small flex-grow-1">
                            Auto-generate a branded PDF brochure from this unit's photos, price, specs and your contact details.
                        </p>
                        <a href="{{ route('builder.projects.properties.marketing.brochure', [$project, $property]) }}" class="btn btn-primary btn-sm">
                            <i class="align-middle" data-feather="download"></i> Generate &amp; Download
                        </a>
                    </div>
                </div>
            </div>

            {{-- WhatsApp Share (quick link, no message template yet) --}}
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <i class="align-middle mb-2" data-feather="message-circle" style="width:32px;height:32px;color:#25D366;"></i>
                        <h5 class="card-title">WhatsApp Message</h5>
                        <p class="card-text text-muted small flex-grow-1">
                            Share a quick message with this unit's link on WhatsApp.
                        </p>
                        @if($publicUrl)
                            @php
                                $waText = rawurlencode("Check out this property: {$property->title}\n{$publicUrl}");
                            @endphp
                            <a href="https://wa.me/?text={{ $waText }}" target="_blank" class="btn btn-success btn-sm">
                                <i class="align-middle" data-feather="share-2"></i> Share on WhatsApp
                            </a>
                        @else
                            <span class="badge bg-secondary align-self-start">Save unit to enable</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Social Media Post (coming soon) --}}
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <i class="align-middle mb-2" data-feather="image" style="width:32px;height:32px;color:#1e3a5f;"></i>
                        <h5 class="card-title">Social Media Post</h5>
                        <p class="card-text text-muted small flex-grow-1">
                            Auto-generate an Instagram/Facebook-ready post image with caption for this unit.
                        </p>
                        <a href="{{ route('builder.projects.properties.marketing.social-post', [$project, $property]) }}" class="btn btn-primary btn-sm">
                            <i class="align-middle" data-feather="edit-3"></i> Create Post
                        </a>
                    </div>
                </div>
            </div>

            {{-- EDM (coming soon) --}}
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <i class="align-middle mb-2" data-feather="mail" style="width:32px;height:32px;color:#1e3a5f;"></i>
                        <h5 class="card-title">EDM (Email Blast)</h5>
                        <p class="card-text text-muted small flex-grow-1">
                            Send a designed email campaign for this unit to your contact list.
                        </p>
                        <a href="{{ route('builder.projects.properties.marketing.edm', [$project, $property]) }}" class="btn btn-primary btn-sm">
                            <i class="align-middle" data-feather="send"></i> Compose Campaign
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
