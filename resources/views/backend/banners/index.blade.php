@extends('backend.layout')
@section('title', 'Banners')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0"><i data-feather="image" class="me-2 text-primary"></i>Banners</h4>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary btn-sm">
        <i data-feather="plus" style="width:14px;height:14px;" class="me-1"></i> Add Banner
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
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
                        <th>#</th>
                        <th style="width:100px;">Image</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th style="width:130px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($banners as $banner)
                    <tr>
                        <td class="text-muted small">{{ $banner->id }}</td>
                        <td>
                            @if($banner->image)
                                <img src="{{ asset('storage/' . $banner->image) }}" alt="banner"
                                     class="rounded" width="80" height="45" style="object-fit:cover;">
                            @else
                                <span class="text-muted small">No image</span>
                            @endif
                        </td>
                        <td class="fw-medium">{{ $banner->title }}</td>
                        <td>
                            @if(($banner->status ?? 'active') === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $banner->created_at?->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this banner?')">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No banners yet. <a href="{{ route('admin.banners.create') }}">Add one</a>.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($banners->hasPages())
    <div class="card-footer">{{ $banners->links() }}</div>
    @endif
</div>

@endsection
