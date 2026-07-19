@extends('backend.layout')
@section('title', 'Marketplace Products')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0"><i class="fas fa-box me-2 text-primary"></i>Marketplace Products</h4>
    <a href="{{ route('admin.marketplace.products.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Add Product
    </a>
</div>

<form method="GET" action="{{ route('admin.marketplace.products.index') }}" class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search product name…">
            </div>
            <div class="col-md-3">
                <select name="vendor_id" class="form-select form-select-sm">
                    <option value="">All vendors</option>
                    @foreach($vendors as $v)
                        <option value="{{ $v->id }}" {{ request('vendor_id') == $v->id ? 'selected' : '' }}>{{ $v->business_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select form-select-sm">
                    <option value="">All categories</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
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
                    <th>Product</th>
                    <th>Vendor</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>BHK</th>
                    <th>Leads</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($p->cover_image)
                                <img src="{{ asset('storage/'.$p->cover_image) }}" alt="" style="width:42px;height:42px;object-fit:cover;border-radius:6px;">
                            @else
                                <div style="width:42px;height:42px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#94a3b8;">
                                    <i class="bi {{ $p->category?->icon ?? 'bi-box' }}"></i>
                                </div>
                            @endif
                            <div>
                                <div class="fw-semibold">{{ $p->name }}</div>
                                @if($p->is_featured)<span class="badge bg-warning text-dark" style="font-size:.65rem;">Featured</span>@endif
                            </div>
                        </div>
                    </td>
                    <td>{{ $p->vendor?->business_name }}</td>
                    <td>{{ $p->category?->name }}</td>
                    <td>{{ $p->price_label }}</td>
                    <td>
                        @if($p->bhk_fit)
                            @foreach($p->bhk_fit as $b)<span class="badge bg-light text-dark">{{ $b }}BHK</span>@endforeach
                        @else
                            <span class="text-muted small">All</span>
                        @endif
                    </td>
                    <td><span class="badge bg-primary-subtle text-primary">{{ $p->leads_count }}</span></td>
                    <td>
                        @if($p->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.marketplace.products.show', $p) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.marketplace.products.edit', $p) }}" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{ route('admin.marketplace.products.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Delete this product?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No products yet. <a href="{{ route('admin.marketplace.products.create') }}">Add the first one</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $products->links() }}</div>
</div>
@endsection
