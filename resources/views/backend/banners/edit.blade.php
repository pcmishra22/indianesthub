@extends('backend.layout')
@section('title', 'Edit Banner')
@section('content')

<div class="d-flex align-items-center mb-3">
    <a href="{{ route('admin.banners.index') }}" class="btn btn-sm btn-outline-secondary me-3">
        <i data-feather="arrow-left" style="width:14px;height:14px;"></i> Back
    </a>
    <h4 class="mb-0">Edit Banner</h4>
</div>

<div class="card shadow-sm" style="max-width:600px;">
    <div class="card-body">
        <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title', $banner->title) }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Banner Image</label>
                @if($banner->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $banner->image) }}" alt="current banner"
                             class="rounded" style="max-height:120px;object-fit:cover;">
                        <small class="text-muted d-block mt-1">Current image — upload a new one to replace it.</small>
                    </div>
                @endif
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                <small class="text-muted">Max 2MB. Leave empty to keep current image.</small>
                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="active"   {{ ($banner->status ?? 'active') === 'active'   ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ ($banner->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Banner</button>
            <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>

@endsection
