@extends('backend.layout')
@section('title', 'User Details')
@section('content')
<h1>User Details</h1>
<div class="card">
	<div class="card-body">
		<table class="table table-bordered">
			<tr>
				<th>ID</th>
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
				<td>{{ $user->phone }}</td>
			</tr>
			<tr>
				<th>Status</th>
				<td>
					@if($user->status)
						<span class="badge bg-success">Active</span>
					@else
						<span class="badge bg-secondary">Inactive</span>
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
	</div>
</div>
@endsection
