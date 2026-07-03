@extends('backend.layout')
@section('title', 'Properties')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0"><i data-feather="home" class="me-2 text-primary"></i>Properties</h4>
    <span class="badge bg-primary fs-6">{{ $properties->total() }} total</span>
</div>

{{-- Search --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-8">
                <label class="form-label small mb-1">Search</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control"
                           placeholder="Title, city, type, address…"
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i data-feather="search" style="width:14px;height:14px;"></i></button>
                </div>
            </div>
            @if(request()->filled('search'))
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
								<a href="{{ route('admin.properties.viewers.index', $property->id) }}" class="btn btn-sm btn-outline-info">Viewers</a>

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
						<td colspan="7" class="text-center">No properties found.</td>
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
