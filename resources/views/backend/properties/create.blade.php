@extends('backend.layout')

@section('title', 'Add Property')

@section('content')
<div class="row justify-content-center mt-12">
    <div class="col-12 col-lg-12">
        <div class="card mt-12">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Add New Property</h3>
                <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i data-feather="arrow-left" style="width:14px;height:14px;"></i> Back to Properties
                </a>
            </div>
            <form method="POST" action="{{ route('admin.properties.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    @include('backend.properties.form')
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">Add Property</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @stack('form-scripts')
@endpush
