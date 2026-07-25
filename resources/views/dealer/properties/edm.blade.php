@extends('dealer.layout')

@section('title', 'Email Campaign')

@section('content')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0"><strong>Email Campaign (EDM)</strong></h1>
            <p class="text-muted mb-0">{{ $property->title }}</p>
        </div>
        <a href="{{ route('dealer.properties.marketing', $property->slug) }}" class="btn btn-outline-secondary btn-sm">
            <i class="align-middle" data-feather="arrow-left"></i> Back to Marketing Studio
        </a>
    </div>

    @include('marketing._edm-composer')

</div>
@endsection
