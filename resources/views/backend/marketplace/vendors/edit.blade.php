@extends('backend.layout')
@section('title', 'Edit Vendor')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0"><i class="fas fa-store me-2 text-primary"></i>Edit Vendor — {{ $vendor->business_name }}</h4>
    <a href="{{ route('admin.marketplace.vendors.index') }}" class="btn btn-sm btn-light">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('admin.marketplace.vendors.update', $vendor) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @include('backend.marketplace.vendors._form')
        </div>
        <div class="card-footer bg-white d-flex justify-content-end gap-2">
            <a href="{{ route('admin.marketplace.vendors.show', $vendor) }}" class="btn btn-light">Cancel</a>
            <button class="btn btn-primary"><i class="fas fa-save me-1"></i> Save Changes</button>
        </div>
    </div>
</form>
@endsection
