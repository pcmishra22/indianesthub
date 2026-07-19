@extends('backend.layout')
@section('title', 'Marketplace Categories')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0"><i class="fas fa-th-large me-2 text-primary"></i>Marketplace Categories</h4>
    <a href="{{ route('admin.marketplace.categories.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Add Category
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Icon</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Tagline</th>
                    <th>Sort</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $c)
                <tr>
                    <td class="text-muted">{{ $c->id }}</td>
                    <td><i class="bi {{ $c->icon ?? 'bi-shop' }} fs-5 text-primary"></i></td>
                    <td class="fw-semibold">{{ $c->name }}</td>
                    <td><code class="small">{{ $c->slug }}</code></td>
                    <td class="text-muted small">{{ $c->tagline }}</td>
                    <td>{{ $c->sort_order }}</td>
                    <td><span class="badge bg-light text-dark">{{ $c->products_count }}</span></td>
                    <td>
                        <form method="POST" action="{{ route('admin.marketplace.categories.toggle-active', $c) }}" class="d-inline">
                            @csrf
                            <button class="btn btn-sm p-0 border-0 bg-transparent" title="Click to toggle">
                                @if($c->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </button>
                        </form>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('marketplace.category', $c) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="View live"><i class="fas fa-external-link-alt"></i></a>
                        <a href="{{ route('admin.marketplace.categories.edit', $c) }}" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{ route('admin.marketplace.categories.destroy', $c) }}" class="d-inline" onsubmit="return confirm('Delete this category?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Delete" {{ $c->products_count > 0 ? 'disabled' : '' }}><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-4">No categories yet. <a href="{{ route('admin.marketplace.categories.create') }}">Add the first one</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
