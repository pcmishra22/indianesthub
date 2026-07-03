@extends('backend.layout')
@section('title', 'User Details')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
	<h4 class="mb-0"><i data-feather="user" class="me-2 text-primary"></i>User Details</h4>
	<a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
	<div class="card-body">
		<table class="table table-bordered">
			<tr>
				<th style="width:180px;">ID</th>
				<td>{{ $user->id }}</td>
			</tr>
			<tr>
				<th>Name</th>
				<td>{{ $user->name }}</td>
			</tr>
			<tr>
				<th>Email</th>
				<td>{{ $user->email }}</td>
			</tr>
			<tr>
				<th>Phone</th>
				<td>{{ $user->phone ?: '–' }}</td>
			</tr>
			<tr>
				<th>Status</th>
				<td>
					@if($user->status === 'active')
						<span class="badge bg-success">Active</span>
					@else
						<span class="badge bg-danger">Blocked</span>
					@endif
				</td>
			</tr>
			<tr>
				<th>Created At</th>
				<td>{{ $user->created_at }}</td>
			</tr>
			<tr>
				<th>Updated At</th>
				<td>{{ $user->updated_at }}</td>
			</tr>
		</table>

		<div class="d-flex gap-2">
			<form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST">
				@csrf
				<button type="submit" class="btn btn-sm {{ $user->status === 'active' ? 'btn-warning' : 'btn-success' }}"
						onclick="return confirm('{{ $user->status === 'active' ? 'Block' : 'Unblock' }} this user?')">
					{{ $user->status === 'active' ? 'Block User' : 'Unblock User' }}
				</button>
			</form>
			<form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete this user?')">
				@csrf
				@method('DELETE')
				<button type="submit" class="btn btn-sm btn-outline-danger">Delete User</button>
			</form>
		</div>
	</div>
</div>
@endsection
