@extends('backend.layout')
@section('title', 'Edit Scheduled Content')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
	<h1 class="h3 mb-0">Edit Scheduled Content</h1>
	<a href="{{ route('admin.schedule.index') }}" class="btn btn-outline-secondary btn-sm">
		<i class="align-middle" data-feather="arrow-left"></i> Back
	</a>
</div>

@if($errors->any())
	<div class="alert alert-danger">
		<ul class="mb-0">
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif

<div class="card">
	<div class="card-body">
		<form method="POST" action="{{ route('admin.schedule.update', $content->id) }}">
			@csrf
			@method('PUT')
			<div class="mb-3">
				<label class="form-label">Title</label>
				<input type="text" name="title" class="form-control" value="{{ old('title', $content->title) }}" required>
			</div>
			<div class="mb-3">
				<label class="form-label">Description</label>
				<textarea name="description" rows="5" class="form-control" required>{{ old('description', $content->description) }}</textarea>
			</div>
			<div class="mb-3">
				<label class="form-label">Scheduled At</label>
				<input type="datetime-local" name="scheduled_at" class="form-control"
					   value="{{ old('scheduled_at', \Illuminate\Support\Carbon::parse($content->scheduled_at)->format('Y-m-d\TH:i')) }}" required>
			</div>
			<button type="submit" class="btn btn-primary">
				<i class="align-middle" data-feather="save"></i> Update
			</button>
		</form>
	</div>
</div>
@endsection
