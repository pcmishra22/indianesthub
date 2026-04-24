@extends('backend.layout')
@section('title', 'Add Banner')
@section('content')

<div class="d-flex align-items-center mb-3">
    <a href="{{ route('admin.banners.index') }}" class="btn btn-sm btn-outline-secondary me-3">
        <i data-feather="arrow-left" style="width:14px;height:14px;"></i> Back
    </a>
    <h4 class="mb-0">Add Banner</h4>
</div>

<div class="card shadow-sm" style="max-width:600px;">
    <div class="card-body">
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title') }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Banner Image <span class="text-danger">*</span></label>
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                       accept="image/*" required>
                <small class="text-muted">Max 2MB. Recommended size: 1920×600px.</small>
                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="active" selected>Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save Banner</button>
            <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>

@endsection
