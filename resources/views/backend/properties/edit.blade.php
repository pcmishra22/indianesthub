@extends('backend.layout')

@section('title', 'Edit Property')

@section('content')
<div class="row justify-content-center mt-12">
    <div class="col-12 col-lg-12">
        <div class="card mt-12">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Edit Property</h3>
                <a href="{{ route('admin.properties.show', $property->id) }}" class="btn btn-outline-secondary btn-sm">
                    <i data-feather="arrow-left" style="width:14px;height:14px;"></i> Back to Property
                </a>
            </div>
            <form method="POST" action="{{ route('admin.properties.update', $property->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @include('backend.properties.form', ['property' => $property])
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">Update Property</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @stack('form-scripts')
@endpush
