@extends('backend.layout')
@section('title', 'Property Details')
@section('content')
<h1>Property Details</h1>
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
