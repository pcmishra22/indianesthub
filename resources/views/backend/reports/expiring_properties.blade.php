@extends('backend.layout')
@section('title', 'Expiring Properties Report')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>{{ $reportTitle ?? 'Expiring Properties Report' }}</h1>
    <div>
        <a href="{{ route('admin.reports.expiring-tomorrow') }}" class="btn btn-outline-primary btn-sm">Expire Tomorrow</a>
        <a href="{{ route('admin.reports.expiring-in-a-week') }}" class="btn btn-outline-primary btn-sm">Expire in a Week</a>
    </div>
</div>
<h5 class="mb-4">{{ $reportDate ?? '' }}</h5>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>City</th>
                        <th>Dealer</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($properties as $property)
                    <tr>
                        <td>{{ $property->id }}</td>
                        <td>{{ $property->title }}</td>
                        <td>{{ $property->city }}</td>
                        <td>{{ $property->dealer_id ?? '-' }}</td>
                        <td>{{ $property->expiry_date }}</td>
                        <td>
                            @if($property->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No properties expiring tomorrow.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
