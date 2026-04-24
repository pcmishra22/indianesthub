@extends('backend.layout')
@section('title', 'Properties')
@section('content')
<h1>Properties List</h1>
<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered table-striped align-middle mb-0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Title</th>
						<th>Type</th>
						<th>City</th>
						<th>Price</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
				@forelse($properties as $property)
					<tr>
						<td>{{ $property->id }}</td>
						<td>{{ $property->title }}</td>
						<td>{{ $property->property_type }}</td>
						<td>{{ $property->city }}</td>
						<td>{{ $property->price }}</td>
						<td>
							@if($property->status)
								<span class="badge bg-success">Active</span>
							@else
								<span class="badge bg-secondary">Inactive</span>
							@endif
						</td>
						<td>
							<a href="{{ route('admin.properties.show', $property->id) }}" class="btn btn-sm btn-primary">View</a>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="7" class="text-center">No properties found.</td>
					</tr>
				@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection
