@extends('backend.layout')
@section('title', 'Users')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0"><i data-feather="users" class="me-2 text-primary"></i>Users</h4>
    <span class="badge bg-primary fs-6">{{ $users->total() }} total</span>
</div>

{{-- Search --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-8">
                <label class="form-label small mb-1">Search</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control"
                           placeholder="Name or email…"
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i data-feather="search" style="width:14px;height:14px;"></i></button>
                </div>
            </div>
            @if(request()->filled('search'))
            <div class="col-md-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i data-feather="x" style="width:14px;height:14px;" class="me-1"></i>Clear
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
	<div class="card-body p-0">
		<div class="table-responsive">
			<table class="table table-striped align-middle mb-0">
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
					@forelse($users as $user)
					<tr>
						<td>{{ $user->id }}</td>
						<td>{{ $user->name }}</td>
						<td>{{ $user->email }}</td>
						<td>
							@if(($user->status ?? 'active') === 'active')
								<span class="badge bg-success">Active</span>
							@else
								<span class="badge bg-danger">Blocked</span>
							@endif
						</td>
						<td>
							<div class="d-flex gap-1 flex-wrap">
								<a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-info">View</a>

								<form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="d-inline">
									@csrf
									<button type="submit" class="btn btn-sm {{ ($user->status ?? 'active') === 'active' ? 'btn-outline-warning' : 'btn-outline-success' }}"
											onclick="return confirm('Toggle status for this user?')">
										{{ ($user->status ?? 'active') === 'active' ? 'Block' : 'Unblock' }}
									</button>
								</form>

								<form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
									@csrf
									@method('DELETE')
									<button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this user?')">Delete</button>
								</form>
							</div>
						</td>
					</tr>
					@empty
					<tr>
						<td colspan="5" class="text-center text-muted py-4">No users found.</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
	@if($users->hasPages())
	<div class="card-footer d-flex justify-content-between align-items-center">
		<small class="text-muted">Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }}</small>
		{{ $users->links() }}
	</div>
	@endif
</div>
@endsection
