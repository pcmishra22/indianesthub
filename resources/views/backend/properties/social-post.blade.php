@extends('backend.layout')

@section('title', 'Social Media Post')

@section('content')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0"><strong>Social Media Post</strong></h1>
            <p class="text-muted mb-0">{{ $property->title }}</p>
        </div>
        <a href="{{ route('admin.properties.marketing', $property->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="align-middle" data-feather="arrow-left"></i> Back to Marketing Studio
        </a>
    </div>

    @include('marketing._social-post-editor')

</div>
@endsection
