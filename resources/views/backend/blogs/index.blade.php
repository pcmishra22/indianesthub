@extends('backend.layout')
@section('title', 'Blogs')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
	<h1 class="h3 mb-0">Blog Posts</h1>
	<a href="{{ route('admin.blog.create') }}" class="btn btn-primary">
		<i class="align-middle" data-feather="plus"></i> New Blog Post
	</a>
</div>

@if(session('success'))
	<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Published</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($blogs as $blog)
                    <tr>
                        <td>{{ $blog->id }}</td>
                        <td>
                            @if($blog->featured_image)
                                <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="" style="width:56px;height:40px;object-fit:cover;border-radius:4px;">
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>{{ $blog->title ?? '-' }}</td>
                        <td>{{ $blog->category ?? '—' }}</td>
                        <td>
                            @if($blog->status === 'published')
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                        <td>{{ $blog->views_count ?? 0 }}</td>
                        <td>{{ $blog->published_at?->format('d M Y') ?? '—' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.blog.show', $blog->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="align-middle" data-feather="eye"></i>
                                </a>
                                <a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="align-middle" data-feather="edit-2"></i>
                                </a>
                                <form action="{{ route('admin.blog.destroy', $blog->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this blog post? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="align-middle" data-feather="trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No blogs found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($blogs, 'links'))
            <div class="mt-3">{{ $blogs->links() }}</div>
        @endif
    </div>
</div>
@endsection
