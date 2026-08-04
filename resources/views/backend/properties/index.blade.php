@extends('backend.layout')
@section('title', 'Properties')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0"><i data-feather="home" class="me-2 text-primary"></i>Properties</h4>
    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-primary fs-6">{{ $properties->total() }} total</span>
        <a href="{{ route('admin.properties.create') }}" class="btn btn-primary btn-sm">
            <i class="align-middle" data-feather="plus"></i> Add Property
        </a>
    </div>
</div>

{{-- Search --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label small mb-1">Search</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control"
                           placeholder="Title, city, type, address…"
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i data-feather="search" style="width:14px;height:14px;"></i></button>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Purpose</label>
                <select name="looking_for" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="Sale" {{ request('looking_for') == 'Sale' ? 'selected' : '' }}>Sale</option>
                    <option value="Rent" {{ request('looking_for') == 'Rent' ? 'selected' : '' }}>Rent</option>
                    <option value="PG" {{ request('looking_for') == 'PG' ? 'selected' : '' }}>PG</option>
                    <option value="Renovate" {{ request('looking_for') == 'Renovate' ? 'selected' : '' }}>Renovate</option>
                </select>
            </div>
            @if(request()->filled('search') || request()->filled('looking_for'))
            <div class="col-md-2">
                <a href="{{ route('admin.properties.index') }}" class="btn btn-sm btn-outline-secondary w-100">
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
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered table-striped align-middle mb-0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Title</th>
						<th>Type</th>
						<th>Purpose</th>
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
						<td>
							@php
								$lf = $property->looking_for ?? '';
								$lfBadge = match($lf) {
									'Rent' => 'bg-info',
									'PG' => 'bg-warning text-dark',
									'Renovate' => 'bg-danger',
									default => 'bg-success',
								};
							@endphp
							<span class="badge {{ $lfBadge }}">{{ $lf ?: '—' }}</span>
						</td>
						<td>{{ $property->city }}</td>
						<td>{{ $property->price }}</td>
						<td>
							@if($property->status === 'inactive')
								<span class="badge bg-danger">Disabled</span>
							@elseif($property->status === 'active')
								<span class="badge bg-success">Active</span>
							@else
								<span class="badge bg-secondary">{{ ucfirst($property->status) }}</span>
							@endif
						</td>
						<td>
							<div class="d-flex align-items-center gap-2 flex-wrap">
								<a href="{{ route('admin.properties.show', $property->id) }}" class="btn btn-sm btn-primary">View</a>
								<a href="{{ route('admin.properties.edit', $property->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
								<a href="{{ route('admin.properties.viewers.index', $property->id) }}" class="btn btn-sm btn-outline-info">Viewers</a>
								<a href="{{ route('admin.properties.marketing', $property->id) }}" class="btn btn-sm btn-outline-primary">Share</a>
								<form method="POST" action="{{ route('admin.properties.destroy', $property->id) }}" class="d-inline"
									  onsubmit="return confirm('Delete this property? This cannot be undone from the admin panel.')">
									@csrf
									@method('DELETE')
									<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
								</form>

								<form method="POST" action="{{ route('admin.properties.toggle-status', $property->id) }}" class="d-inline">
									@csrf
									<button type="submit" class="btn btn-sm {{ $property->status === 'inactive' ? 'btn-outline-success' : 'btn-outline-warning' }}"
											onclick="return confirm('{{ $property->status === 'inactive' ? 'Enable' : 'Disable' }} this property?')">
										{{ $property->status === 'inactive' ? 'Enable' : 'Disable' }}
									</button>
								</form>

								<form method="POST" action="{{ route('admin.properties.togglePublicContact', $property->id) }}" class="d-inline">
									@csrf
									<input type="hidden" name="enabled" value="0">
									<label class="form-check-label mb-0 small" style="cursor:pointer;" title="Guest users will see dealer phone & enquiry when enabled">
										<input class="form-check-input" type="checkbox" name="enabled" value="1" {{ $property->public_contact_enabled ? 'checked' : '' }} onchange="this.form.submit()">
										Public contact
									</label>
								</form>
							</div>
						</td>
					</tr>

				@empty
					<tr>
						<td colspan="8" class="text-center">No properties found.</td>
					</tr>
				@endforelse
				</tbody>
			</table>
		</div>
	</div>
	@if($properties->hasPages())
	<div class="card-footer d-flex justify-content-between align-items-center">
		<small class="text-muted">Showing {{ $properties->firstItem() }}–{{ $properties->lastItem() }} of {{ $properties->total() }}</small>
		{{ $properties->links() }}
	</div>
	@endif
</div>
@endsection
