@extends('backend.layout')
@section('title', 'Users')
@section('content')
<h1>Users List</h1>
<div class="card">
	<div class="card-body">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Email</th>
					<th>Status</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($users as $user)
				<tr>
					<td>{{ $user->id }}</td>
					<td>{{ $user->name }}</td>
					<td>{{ $user->email }}</td>
					<td>{{ $user->status ?? 'active' }}</td>
					<td>
						<a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info">View</a>
						<form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block">
							@csrf
							@method('DELETE')
							<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</button>
						</form>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection
