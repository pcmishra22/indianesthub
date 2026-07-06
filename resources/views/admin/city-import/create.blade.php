@extends('backend.layout')

@section('title', 'City Data Import')

@section('content')
<div class="container py-4" style="max-width: 640px;">
    <h1 class="h4 mb-3">Add City Data</h1>
    <p class="text-muted">
        Enter a city and choose what to look for. You'll review every result
        on the next screen and confirm once before anything is saved.
    </p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->has('discovery'))
        <div class="alert alert-danger">{{ $errors->first('discovery') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.city-import.discover') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control" placeholder="e.g. Zirakpur" required value="{{ old('city') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="type" class="form-select" required>
                <option value="builder">Builder</option>
                <option value="agent">Agent</option>
                <option value="property">Property</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <p class="text-muted small mt-4">
        Builders &amp; Agents are looked up via the Google Places API (real business
        directory data). Properties can't be auto-crawled from listing portals — that
        path is reserved for a manual/CSV or licensed-feed import instead.
    </p>
</div>
@endsection
