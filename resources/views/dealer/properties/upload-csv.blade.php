@extends('dealer.layout')

@section('page-title', 'Bulk Property Upload')

@section('content')
<div class="container mt-4">
    <h4 class="fw-bold mb-3">Upload Properties via CSV</h4>
    <form action="{{ route('dealer.properties.uploadCsv') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="csv_file" class="form-label">CSV File</label>
            <input type="file" class="form-control @error('csv_file') is-invalid @enderror" id="csv_file" name="csv_file" required>
            @error('csv_file')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-success">Upload & Import</button>
    </form>
    <div class="mt-3">
        <p class="text-muted">CSV columns required: title, property_type, looking_for, address, city, state, country, price</p>
    </div>
</div>
@endsection
