@extends('backend.layout')
@section('title', 'Inquiries')
@section('content')
<h1>Inquiries List</h1>
<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered table-striped align-middle mb-0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Email</th>
						<th>Phone</th>
						<th>Property</th>
						<th>Status</th>
						<th>Message</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
				@forelse($inquiries as $inquiry)
					<tr>
						<td>{{ $inquiry->id }}</td>
						<td>{{ $inquiry->name }}</td>
						<td>{{ $inquiry->email }}</td>
						<td>{{ $inquiry->phone }}</td>
						<td>{{ $inquiry->property_id }}</td>
						<td>
							@if($inquiry->status)
								<span class="badge bg-success">Active</span>
							@else
								<span class="badge bg-secondary">Inactive</span>
							@endif
						</td>
						<td>{{ $inquiry->message }}</td>
						<td><!-- Actions --></td>
					</tr>
				@empty
					<tr>
						<td colspan="8" class="text-center">No inquiries found.</td>
					</tr>
				@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection
