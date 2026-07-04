@extends('backend.layout')
@section('title', 'Service Details')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0"><i class="fas fa-tools me-2 text-primary"></i>Service</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.services.edit', $category) }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-edit me-1"></i>Edit
        </a>
        <form method="POST" action="{{ route('admin.services.destroy', $category) }}" class="d-inline">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this service?')">
                <i class="fas fa-trash me-1"></i>Delete
            </button>
        </form>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="fw-bold fs-4">{{ $category->name }}</div>
                <div class="text-muted">Slug: <span class="fw-semibold">{{ $category->slug }}</span></div>

                <hr>

                <div class="d-flex justify-content-between">
                    <span class="text-muted">Status</span>
                    <span>
                        @if($category->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </span>
                </div>

                <div class="d-flex justify-content-between mt-2">
                    <span class="text-muted">Sort Order</span>
                    <span class="fw-semibold">{{ $category->sort_order }}</span>
                </div>

                <div class="d-flex justify-content-between mt-2">
                    <span class="text-muted">Icon</span>
                    <span class="fw-semibold">{{ $category->icon ?: '—' }}</span>
                </div>

                <hr>

                <div class="text-muted small">Description</div>
                <div class="fw-semibold">{{ $category->description ?: '—' }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="fw-semibold text-muted small text-uppercase mb-3"><i class="fas fa-users me-1"></i>Approved Providers</div>
                <div class="d-flex flex-wrap">
                    @if($category->providers && $category->providers->count())
                        @foreach($category->providers as $p)
                            <a href="{{ route('admin.service-providers.show', $p) }}" class="badge bg-primary text-decoration-none me-2 mb-2" style="cursor:pointer;">{{ $p->business_name ?: $p->full_name }}</a>
                        @endforeach
                    @else
                        <div class="text-muted">No approved providers assigned.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

