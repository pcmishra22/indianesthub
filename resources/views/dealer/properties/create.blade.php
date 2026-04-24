@extends('dealer.layout')

@section('title', 'Add Property')

@section('content')
<div class="row justify-content-center mt-12">
    <div class="col-12 col-lg-12">
        <div class="card mt-12">
            <div class="card-header">
                <h3 class="card-title mb-0">Add New Property</h3>
            </div>
            <form method="POST" action="{{ route('dealer.properties.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    @include('dealer.properties.form')
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
