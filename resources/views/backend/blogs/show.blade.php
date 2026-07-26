@extends('backend.layout')
@section('title', $blog->title)
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
	<h1 class="h3 mb-0">{{ $blog->title }}</h1>
	<div class="d-flex gap-2">
		<a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn btn-warning btn-sm">
			<i class="align-middle" data-feather="edit-2"></i> Edit
		</a>
		<a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary btn-sm">
			<i class="align-middle" data-feather="arrow-left"></i> Back
		</a>
	</div>
</div>

<div class="card">
	<div class="card-body">
		@if($blog->featured_image)
			<img src="{{ asset('storage/' . $blog->featured_image) }}" class="img-fluid rounded mb-3" style="max-height:320px;object-fit:cover;width:100%;" alt="">
		@endif

		<div class="d-flex gap-3 mb-3 text-muted small">
			<span>
				@if($blog->status === 'published')
					<span class="badge bg-success">Published</span>
				@else
					<span class="badge bg-secondary">Draft</span>
				@endif
			</span>
			@if($blog->category)<span>Category: {{ $blog->category }}</span>@endif
			<span>Views: {{ $blog->views_count ?? 0 }}</span>
			@if($blog->published_at)<span>Published: {{ $blog->published_at->format('d M Y') }}</span>@endif
		</div>

		@if($blog->excerpt)
			<p class="lead">{{ $blog->excerpt }}</p>
		@endif

		<div>{!! $blog->content !!}</div>
	</div>
</div>
@endsection
