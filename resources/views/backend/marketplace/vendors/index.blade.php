@extends('backend.layout')
@section('title', 'Marketplace Vendors')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0"><i class="fas fa-store me-2 text-primary"></i>Marketplace Vendors</h4>
    <a href="{{ route('admin.marketplace.vendors.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Add Vendor
    </a>
</div>

<form method="GET" action="{{ route('admin.marketplace.vendors.index') }}" class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <div class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search by name, owner, city…">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All status</option>
                    <option value="active"   {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-sm btn-primary w-100"><i class="fas fa-filter me-1"></i>Filter</button>
            </div>
        </div>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Business</th>
                    <th>Owner</th>
                    <th>City</th>
                    <th>Phone</th>
                    <th>Commission</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vendors as $v)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $v->business_name }}</div>
                        @if($v->is_verified)
                            <span class="badge bg-success-subtle text-success"><i class="bi bi-patch-check-fill"></i> Verified</span>
                        @endif
                    </td>
                    <td>{{ $v->owner_name ?? '—' }}</td>
                    <td>{{ $v->city ?? '—' }}</td>
                    <td><a href="tel:{{ $v->phone }}">{{ $v->phone }}</a></td>
                    <td>{{ number_format($v->commission_pct, 1) }}%</td>
                    <td><span class="badge bg-light text-dark">{{ $v->products_count }}</span></td>
                    <td>
                        @if($v->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.marketplace.vendors.show', $v) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.marketplace.vendors.edit', $v) }}" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{ route('admin.marketplace.vendors.destroy', $v) }}" class="d-inline" onsubmit="return confirm('Delete this vendor? Their products will also be removed.')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No vendors yet. <a href="{{ route('admin.marketplace.vendors.create') }}">Add the first one</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $vendors->links() }}</div>
</div>
@endsection
