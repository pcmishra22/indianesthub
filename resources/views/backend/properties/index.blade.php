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
							<div class="d-flex align-items-center gap-2">
								<form method="POST" action="{{ route('admin.properties.togglePublicContact', $property->id) }}">
									@csrf
									<input type="hidden" name="enabled" value="0">
									<label class="form-check-label mb-0" style="cursor:pointer;" title="Guest users will see dealer phone & enquiry when enabled">
										<input class="form-check-input" type="checkbox" name="enabled" value="1" {{ $property->public_contact_enabled ? 'checked' : '' }} onchange="this.form.submit()">
									</label>
								</form>
								<a href="{{ route('admin.properties.show', $property->id) }}" class="btn btn-sm btn-primary">View</a>
							</div>
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
