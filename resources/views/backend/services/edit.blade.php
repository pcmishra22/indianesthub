@extends('backend.layout')
@section('title', 'Edit Service')
@section('content')

<div class="mb-4">
    <a href="{{ route('admin.services.index') }}" class="btn btn-sm btn-light me-2">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
    <span class="fs-5 fw-bold"><i class="fas fa-edit me-2 text-primary"></i>Edit Service</span>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show mb-4">
    <i class="fas fa-exclamation-circle me-2"></i>
    <strong>Please fix the errors below:</strong>
    <ul class="mb-0 mt-1 small">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.services.update', $category) }}">
                    @csrf
                    @method('PUT')

                    <div class="fw-semibold text-muted small text-uppercase mb-3 pb-1" style="border-bottom:1px solid #e2e8f0;">
                        <i class="fas fa-info-circle me-1"></i> Service Information
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $category->slug) }}" required>
                            @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Icon Class</label>
                            <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon', $category->icon) }}" placeholder="bi-lightning-charge">
                            @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $category->sort_order) }}" min="0">
                            @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $category->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="is_active" class="form-select @error('is_active') is-invalid @enderror" required>
                                <option value="1" {{ old('is_active', $category->is_active) ? 'selected':'' }}>Active</option>
                                <option value="0" {{ old('is_active', $category->is_active) ? '' : 'selected':'' }}>Inactive</option>
                            </select>
                            @error('is_active')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-5 fw-semibold"><i class="fas fa-save me-2"></i>Update Service</button>
                        <a href="{{ route('admin.services.show', $category) }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

