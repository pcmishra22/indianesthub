@extends('backend.layout')

@section('title', 'Edit Bulk Email')

@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3"><strong>Edit Bulk Email</strong></h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.dealers.bulk-email.update', $email->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                    <input type="text" name="subject" class="form-control" maxlength="150"
                           value="{{ old('subject', $email->subject) }}" required>
                    @error('subject')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                    <textarea name="body" class="form-control" rows="8" maxlength="5000" required>{{ old('body', $email->body) }}</textarea>
                    @error('body')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        Update Draft
                    </button>

                    <a href="{{ route('admin.dealers.bulk-email.index') }}" class="btn btn-outline-secondary ms-2">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection