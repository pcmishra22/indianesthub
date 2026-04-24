@extends('builder.layout')

@section('title', 'Add Unit to ' . $project->title)

@section('content')
<div class="container-fluid p-0">

    <div class="d-flex align-items-center gap-3 px-3 pt-3 mb-3">
        <a href="{{ route('builder.projects.show', $project) }}" class="btn btn-outline-secondary btn-sm">
            <i data-feather="arrow-left" style="width:14px;height:14px;"></i> Back to Project
        </a>
        <div>
            <h1 class="h3 mb-0 fw-bold">Add Unit</h1>
            <small class="text-muted">Project: {{ $project->title }}</small>
        </div>
    </div>

    <div class="px-3 pb-4">
        <form action="{{ route('builder.projects.properties.store', $project) }}" method="POST" enctype="multipart/form-data">
            @csrf

            @include('builder.properties._form')

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i data-feather="save" style="width:16px;height:16px;"></i> Add Unit
                </button>
                <a href="{{ route('builder.projects.show', $project) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>

</div>
@endsection
