@extends('backend.layout')

@section('title', 'City Data Import - Review')

@section('content')
<div class="container py-4">
    <h1 class="h4 mb-1">Review: {{ ucfirst($batch->type) }} results for {{ $batch->city }}</h1>
    <p class="text-muted">Nothing has been saved yet. Tick the rows to keep, then confirm once.</p>

    @if ($notice)
        <div class="alert alert-warning">{{ $notice }}</div>
    @endif

    @if (empty($candidates))
        <div class="alert alert-secondary">No candidates found.</div>
        <a href="{{ route('admin.city-import.create') }}" class="btn btn-secondary">Back</a>
    @else
        <form method="POST" action="{{ route('admin.city-import.confirm', $batch) }}">
            @csrf
            <table class="table table-striped align-middle">
                <thead>
                    @if ($batch->type === 'property')
                        <tr>
                            <th><input type="checkbox" onclick="document.querySelectorAll('.row-check').forEach(c => c.checked = this.checked)" checked></th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Purpose</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>Price</th>
                        </tr>
                    @else
                        <tr>
                            <th><input type="checkbox" onclick="document.querySelectorAll('.row-check').forEach(c => c.checked = this.checked)" checked></th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Website</th>
                            <th>Address</th>
                            <th>Rating</th>
                        </tr>
                    @endif
                </thead>
                <tbody>
                    @foreach ($candidates as $i => $c)
                        @if ($batch->type === 'property')
                            <tr>
                                <td><input class="row-check" type="checkbox" name="selected[]" value="{{ $i }}" checked></td>
                                <td>{{ $c['title'] ?? '—' }}</td>
                                <td>{{ $c['property_type'] ?? '—' }}</td>
                                <td>{{ $c['looking_for'] ?? '—' }}</td>
                                <td>{{ $c['address'] ?? '—' }}</td>
                                <td>{{ $c['city'] ?? '—' }}</td>
                                <td>{{ isset($c['price']) ? number_format((float) $c['price']) : '—' }}</td>
                            </tr>
                        @else
                            <tr>
                                <td><input class="row-check" type="checkbox" name="selected[]" value="{{ $i }}" checked></td>
                                <td>{{ $c['name'] ?? '—' }}</td>
                                <td>{{ $c['phone'] ?? '—' }}</td>
                                <td>{{ $c['website'] ?? '—' }}</td>
                                <td>{{ $c['address'] ?? '—' }}</td>
                                <td>{{ $c['rating'] ?? '—' }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-success">
                Confirm &amp; Insert Selected
            </button>
            <a href="{{ route('admin.city-import.create') }}" class="btn btn-link">Cancel</a>
        </form>

        <form method="POST" action="{{ route('admin.city-import.reject', $batch) }}" class="mt-2">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm">Discard this whole batch</button>
        </form>
    @endif
</div>
@endsection
