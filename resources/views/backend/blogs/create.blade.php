@extends('backend.layout')
@section('title', 'Create Blog')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
	<h1 class="h3 mb-0">Create Blog Post</h1>
	<a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary btn-sm">
		<i class="align-middle" data-feather="arrow-left"></i> Back to Blogs
	</a>
</div>

<form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data">
	@csrf
	@include('backend.blogs._form')
</form>
@endsection
