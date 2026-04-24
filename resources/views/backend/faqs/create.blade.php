@extends('backend.layout')
@section('title', 'Add FAQ')
@section('content')

<div class="d-flex align-items-center mb-3">
    <a href="{{ route('admin.faqs.index') }}" class="btn btn-sm btn-outline-secondary me-3">
        <i data-feather="arrow-left" style="width:14px;height:14px;"></i> Back
    </a>
    <h4 class="mb-0">Add FAQ</h4>
</div>

<div class="card shadow-sm" style="max-width:700px;">
    <div class="card-body">
        <form action="{{ route('admin.faqs.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Question <span class="text-danger">*</span></label>
                <textarea name="question" rows="2" class="form-control @error('question') is-invalid @enderror"
                          required>{{ old('question') }}</textarea>
                @error('question')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Answer <span class="text-danger">*</span></label>
                <textarea name="answer" rows="5" class="form-control @error('answer') is-invalid @enderror"
                          required>{{ old('answer') }}</textarea>
                @error('answer')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="active" selected>Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save FAQ</button>
            <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>

@endsection
