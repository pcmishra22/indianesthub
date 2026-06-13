@extends('backend.layout')

@section('title', 'Edit User Bulk Email')

@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3"><strong>Edit User Bulk Email Draft</strong></h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.bulk-email.update', $email->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                    <input type="text" name="subject" class="form-control" value="{{ old('subject', $email->subject) }}" required>
                    @error('subject') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                    <textarea name="body" class="form-control" rows="8" required>{{ old('body', $email->body) }}</textarea>
                    <div class="text-muted small mt-1">
                        Editing this draft will update the content for all customers when queued.
                    </div>
                    @error('body') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sync me-2"></i>Update Draft
                </button>

                <a href="{{ route('admin.users.bulk-email.index') }}" class="btn btn-outline-secondary ms-2">
                    Back
                </a>
            </form>
        </div>
    </div>
</div>
@endsection