@extends('backend.layout')
@section('title', 'Contacts')
@section('content')
<h1>Contacts List</h1>
<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered table-striped align-middle mb-0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Email</th>
						<th>Subject</th>
						<th>Status</th>
						<th>Message</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
				@forelse($contacts as $contact)
					<tr>
						<td>{{ $contact->id }}</td>
						<td>{{ $contact->name }}</td>
						<td>{{ $contact->email }}</td>
						<td>{{ $contact->subject }}</td>
						<td>
							@if($contact->status)
								<span class="badge bg-success">Active</span>
							@else
								<span class="badge bg-secondary">Inactive</span>
							@endif
						</td>
						<td>{{ $contact->message }}</td>
						<td><!-- Actions --></td>
					</tr>
				@empty
					<tr>
						<td colspan="7" class="text-center">No contacts found.</td>
					</tr>
				@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection
