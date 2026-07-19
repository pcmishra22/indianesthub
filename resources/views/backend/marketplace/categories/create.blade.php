@extends('backend.layout')
@section('title', 'Add Category')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0"><i class="fas fa-th-large me-2 text-primary"></i>Add Marketplace Category</h4>
    <a href="{{ route('admin.marketplace.categories.index') }}" class="btn btn-sm btn-light">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('admin.marketplace.categories.store') }}">
    @csrf
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @include('backend.marketplace.categories._form')
        </div>
        <div class="card-footer bg-white d-flex justify-content-end gap-2">
            <a href="{{ route('admin.marketplace.categories.index') }}" class="btn btn-light">Cancel</a>
            <button class="btn btn-primary"><i class="fas fa-save me-1"></i> Save Category</button>
        </div>
    </div>
</form>
@endsection
