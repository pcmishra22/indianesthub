@extends('backend.layout')

@section('title', 'Bulk Email')

@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3"><strong>New Bulk Email</strong></h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.bulk-email.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Send To <span class="text-danger">*</span></label>
                    <select name="audience" class="form-select" required>
                        @foreach($audiences as $key => $a)
                            <option value="{{ $key }}" {{ old('audience') === $key ? 'selected' : '' }}>
                                {{ $a['label'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('audience')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                    <input type="text" name="subject" class="form-control" maxlength="150"
                           value="{{ old('subject') }}" required>
                    @error('subject')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                    <textarea name="body" class="form-control" rows="8" maxlength="5000" required>{{ old('body') }}</textarea>
                    <div class="text-muted small mt-1">
                        Emails are queued asynchronously to everyone in the selected audience who has a valid email address.
                    </div>
                    @error('body')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Save Draft
                </button>

                <a href="{{ route('admin.users.bulk-email.index') }}" class="btn btn-outline-secondary ms-2">
                    Back to Bulk Emails
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
