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
            <div class="col-md-6">
                <label class="form-label small mb-1">Search</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control"
                           placeholder="Name, email, phone…"
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i data-feather="search" style="width:14px;height:14px;"></i></button>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Status</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All ({{ $counts['active'] + $counts['blocked'] }})</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active ({{ $counts['active'] }})</option>
                    <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Blocked ({{ $counts['blocked'] }})</option>
                </select>
            </div>
            @if(request()->filled('search') || request()->filled('status'))
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

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th style="width:220px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="text-muted small">{{ $user->id }}</td>
                        <td class="fw-semibold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?: '–' }}</td>
                        <td>
                            @if($user->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Blocked</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $user->created_at?->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                   class="btn btn-sm btn-outline-primary">View</a>

                                {{-- Enable / Disable --}}
                                <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $user->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                            onclick="return confirm('{{ $user->status === 'active' ? 'Block' : 'Unblock' }} this user?')">
                                        {{ $user->status === 'active' ? 'Block' : 'Unblock' }}
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
                        <td colspan="7" class="text-center text-muted py-4">No users found.</td>
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
