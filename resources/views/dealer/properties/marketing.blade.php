@extends('dealer.layout')

@section('title', 'Marketing Studio')

@section('content')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0"><strong>Marketing Studio</strong></h1>
            <p class="text-muted mb-0">{{ $property->title }}</p>
        </div>
        <a href="{{ route('dealer.properties.show', $property->slug) }}" class="btn btn-outline-secondary btn-sm">
            <i class="align-middle" data-feather="arrow-left"></i> Back to Property
        </a>
    </div>

    <div class="row">

        {{-- Brochure Generator --}}
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <i class="align-middle mb-2" data-feather="file-text" style="width:32px;height:32px;color:#1e3a5f;"></i>
                    <h5 class="card-title">Brochure Generator</h5>
                    <p class="card-text text-muted small flex-grow-1">
                        Auto-generate a branded PDF brochure from this listing's photos, price, specs and your contact details.
                    </p>
                    <a href="{{ route('dealer.properties.marketing.brochure', $property->slug) }}" class="btn btn-primary btn-sm">
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
                        Share a quick message with this listing's link on WhatsApp.
                    </p>
                    @if($publicUrl)
                        @php
                            $waText = rawurlencode("Check out this property: {$property->title}\n{$publicUrl}");
                        @endphp
                        <a href="https://wa.me/?text={{ $waText }}" target="_blank" class="btn btn-success btn-sm">
                            <i class="align-middle" data-feather="share-2"></i> Share on WhatsApp
                        </a>
                    @else
                        <span class="badge bg-secondary align-self-start">Save property to enable</span>
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
                        Auto-generate an Instagram/Facebook-ready post image with caption for this listing.
                    </p>
                    <a href="{{ route('dealer.properties.marketing.social-post', $property->slug) }}" class="btn btn-primary btn-sm">
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
                        Send a designed email campaign for this listing to your contact list.
                    </p>
                    <a href="{{ route('dealer.properties.marketing.edm', $property->slug) }}" class="btn btn-primary btn-sm">
                        <i class="align-middle" data-feather="send"></i> Compose Campaign
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
