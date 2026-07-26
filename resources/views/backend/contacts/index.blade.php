@extends('backend.layout')
@section('title', 'Contacts')
@section('content')
<h1 class="h3 mb-3">Contacts</h1>

@if(session('success'))
	<div class="alert alert-success">{{ session('success') }}</div>
@endif

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
						<th>Received</th>
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
							@if($contact->status === 'new')
								<span class="badge bg-warning text-dark">New</span>
							@elseif($contact->status === 'replied')
								<span class="badge bg-success">Replied</span>
							@else
								<span class="badge bg-secondary">Read</span>
							@endif
						</td>
						<td>{{ \Illuminate\Support\Str::limit($contact->message, 60) }}</td>
						<td>{{ $contact->created_at->format('d M Y, h:i A') }}</td>
						<td>
							<div class="d-flex gap-2">
								<a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-sm btn-outline-primary" title="View">
									<i class="align-middle" data-feather="eye"></i>
								</a>
								@if($contact->status === 'new')
									<form action="{{ route('admin.contacts.mark-read', $contact->id) }}" method="POST" class="d-inline">
										@csrf
										<button type="submit" class="btn btn-sm btn-outline-secondary" title="Mark as Read">
											<i class="align-middle" data-feather="check"></i>
										</button>
									</form>
								@endif
								<form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" class="d-inline"
									  onsubmit="return confirm('Delete this contact message? This cannot be undone.');">
									@csrf
									@method('DELETE')
									<button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
										<i class="align-middle" data-feather="trash-2"></i>
									</button>
								</form>
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="8" class="text-center">No contacts found.</td>
					</tr>
				@endforelse
				</tbody>
			</table>
		</div>
		@if(method_exists($contacts, 'links'))
			<div class="mt-3">{{ $contacts->links() }}</div>
		@endif
	</div>
</div>
@endsection
