@extends('builder.layout')

@section('title', 'Email Campaign')

@section('content')
<div class="container-fluid p-0">

    <div class="d-flex align-items-center gap-3 px-3 pt-3 mb-3">
        <a href="{{ route('builder.projects.properties.marketing', [$project, $property]) }}" class="btn btn-outline-secondary btn-sm">
            <i data-feather="arrow-left" style="width:14px;height:14px;"></i> Back to Marketing Studio
        </a>
        <div>
            <h1 class="h3 mb-0 fw-bold">Email Campaign (EDM)</h1>
            <small class="text-muted">{{ $project->title }} &middot; {{ $property->title }}</small>
        </div>
    </div>

    <div class="px-3 pb-4">
        @include('marketing._edm-composer')
    </div>

</div>
@endsection
