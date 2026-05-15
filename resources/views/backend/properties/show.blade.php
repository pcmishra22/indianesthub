@extends('backend.layout')
@section('title', 'Property Details')
@section('content')
<h1>Property Details</h1>

{{-- Views summary + link to viewer list --}}
@php
    $viewsTotal = \App\Models\PropertyView::where('property_id', $property->id)->count();
    $uniqueByVisitorToken = \App\Models\PropertyView::where('property_id', $property->id)
        ->whereNotNull('visitor_token')
        ->distinct('visitor_token')
        ->count('visitor_token');
@endphp

<div class="row g-2 mb-3">
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Views</div>
                <div class="fs-4 fw-bold text-primary">{{ number_format($viewsTotal) }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Unique Visitors (token)</div>
                <div class="fs-4 fw-bold text-success">{{ number_format($uniqueByVisitorToken) }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4 d-flex align-items-stretch">
        <div class="card shadow-sm w-100">
            <div class="card-body d-flex align-items-center">
                <a href="{{ route('admin.properties.viewers.index', $property->id) }}" class="btn btn-sm btn-outline-primary">
                    View all viewers
                </a>
            </div>
        </div>
    </div>
</div>
<div class="card">
	<div class="card-body">
		<table class="table table-bordered">
			<tr>
				<th>ID</th>
				<td>{{ $property->id }}</td>
			</tr>
			<tr>
				<th>Title</th>
				<td>{{ $property->title }}</td>
			</tr>
			<tr>
				<th>Type</th>
				<td>{{ $property->property_type }}</td>
			</tr>
			<tr>
				<th>City</th>
				<td>{{ $property->city }}</td>
			</tr>
			<tr>
				<th>Price</th>
				<td>{{ $property->price }}</td>
			</tr>
			<tr>
				<th>Status</th>
				<td>
					@if($property->status)
						<span class="badge bg-success">Active</span>
					@else
						<span class="badge bg-secondary">Inactive</span>
					@endif
				</td>
			</tr>
			<tr>
				<th>Created At</th>
				<td>{{ $property->created_at }}</td>
			</tr>
			<tr>
				<th>Updated At</th>
				<td>{{ $property->updated_at }}</td>
			</tr>
			<tr>
				<th>Valid Till</th>
				<td>{{ $property->expiry_date ?? '-' }}</td>
			</tr>
		</table>
	</div>
</div>
@endsection
