@extends('backend.layout')
@section('title', 'Contact Details')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
	<h1 class="h3 mb-0">Contact Details</h1>
	<a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary btn-sm">
		<i class="align-middle" data-feather="arrow-left"></i> Back to Contacts
	</a>
</div>

<div class="card">
	<div class="card-body">
		<table class="table table-borderless mb-4">
			<tr>
				<th style="width:160px;">Name</th>
				<td>{{ $contact->name }}</td>
			</tr>
			<tr>
				<th>Email</th>
				<td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
			</tr>
			<tr>
				<th>Subject</th>
				<td>{{ $contact->subject }}</td>
			</tr>
			<tr>
				<th>Status</th>
				<td>
					@if($contact->status === 'new')
						<span class="badge bg-warning text-dark">New</span>
					@elseif($contact->status === 'replied')
						<span class="badge bg-success">Replied</span>
					@else
						<span class="badge bg-secondary">Read</span>
					@endif
				</td>
			</tr>
			<tr>
				<th>Received</th>
				<td>{{ $contact->created_at->format('d M Y, h:i A') }}</td>
			</tr>
		</table>

		<h6 class="text-muted">Message</h6>
		<div class="border rounded p-3 mb-4" style="white-space: pre-wrap;">{{ $contact->message }}</div>

		<div class="d-flex gap-2">
			<a href="mailto:{{ $contact->email }}?subject=RE: {{ $contact->subject }}" class="btn btn-primary btn-sm">
				<i class="align-middle" data-feather="mail"></i> Reply by Email
			</a>
			<form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST"
				  onsubmit="return confirm('Delete this contact message? This cannot be undone.');">
				@csrf
				@method('DELETE')
				<button type="submit" class="btn btn-outline-danger btn-sm">
					<i class="align-middle" data-feather="trash-2"></i> Delete
				</button>
			</form>
		</div>
	</div>
</div>
@endsection
