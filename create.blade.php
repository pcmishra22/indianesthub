@extends('backend.layout')

@section('title', 'Create User Bulk Email')

@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3"><strong>Create User Bulk Email</strong></h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.bulk-email.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                    <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required>
                    @error('subject') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                    <textarea name="body" class="form-control" rows="8" required>{{ old('body') }}</textarea>
                    <div class="text-muted small mt-1">
                        This message will be queued for all registered customers.
                    </div>
                    @error('body') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Save Draft
                </button>

                <a href="{{ route('admin.users.bulk-email.index') }}" class="btn btn-outline-secondary ms-2">
                    Back
                </a>
            </form>
        </div>
    </div>
</div>
@endsection