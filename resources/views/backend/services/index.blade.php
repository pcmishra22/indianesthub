@extends('backend.layout')
@section('title', 'Services')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0"><i class="fas fa-tools me-2 text-primary"></i>Services</h4>
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Add Service
    </a>
</div>

<div class="row g-3 mb-4">
    @foreach([
        ['total',    'Total',    'dark', 'fas fa-layer-group'],
        ['active',   'Active',  'success', 'fas fa-check-circle'],
        ['inactive', 'Inactive','secondary', 'fas fa-pause-circle'],
    ] as [$key, $label, $color, $icon])
    <div class="col-6 col-md">
        <div class="card border-0 shadow-sm text-center py-3" style="border-left:3px solid var(--bs-{{ $color }})!important;">
            <div class="fw-bold fs-4 text-{{ $color }}">{{ $stats[$key] ?? 0 }}</div>
            <div class="small text-muted"><i class="{{ $icon }} me-1"></i>{{ $label }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small mb-1">Active</label>
                <select name="is_active" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="1" {{ request('is_active')==='1' ? 'selected':'' }}>Active</option>
                    <option value="0" {{ request('is_active')==='0' ? 'selected':'' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Sort By</label>
                <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="newest" {{ request('sort','newest')==='newest' ? 'selected':'' }}>Newest</option>
                    <option value="oldest" {{ request('sort')==='oldest' ? 'selected':'' }}>Oldest</option>
                    <option value="name" {{ request('sort')==='name' ? 'selected':'' }}>Name A→Z</option>
                    <option value="sort" {{ request('sort')==='sort' ? 'selected':'' }}>Sort Order</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label small mb-1">Search</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Name, slug, icon…" value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
            @if(request()->anyFilled(['is_active','sort','search']))
            <div class="col-md-12">
                <a href="{{ route('admin.services.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="fas fa-times me-1"></i> Clear
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:.85rem;">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Service</th>
                    <th>Icon</th>
                    <th>Sort</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td class="text-muted">{{ $category->id }}</td>
                        <td>
                            <div class="fw-semibold">{{ $category->name }}</div>
                            <div class="text-muted small">{{ $category->slug }}</div>
                        </td>
                        <td>
                            @if($category->icon)
                                <span class="badge bg-light text-dark border">{{ $category->icon }}</span>
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $category->sort_order }}</td>
                        <td>
                            @if($category->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $category->created_at?->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.services.show', $category) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.services.edit', $category) }}" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{ route('admin.services.destroy', $category) }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete this service?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="fas fa-tools fa-2x mb-2 d-block opacity-25"></i>
                            No services found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $categories->links() }}</div>
@endsection

