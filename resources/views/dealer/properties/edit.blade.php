@extends('dealer.layout')

@section('title', 'Edit Property')

@section('content')
<div class="row justify-content-center mt-12">
    <div class="col-12 col-lg-12">
        <div class="card mt-12">
            <div class="card-header">
                <h3 class="card-title mb-0">Edit Property</h3>
            </div>
            <form method="POST" action="{{ route('dealer.properties.update', $property->slug) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @include('dealer.properties.form', ['property' => $property])
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">Update Property</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
