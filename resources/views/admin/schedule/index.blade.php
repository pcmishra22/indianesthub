@extends('backend.layout')
@section('title', 'Scheduled Content')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
	<h1 class="h3 mb-0">Scheduled Content</h1>
	<a href="{{ route('admin.schedule.create') }}" class="btn btn-primary">
		<i class="align-middle" data-feather="plus"></i> Add Scheduled Content
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
						<th>Title</th>
						<th>Description</th>
						<th>Scheduled At</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					@forelse($scheduledContents as $content)
						<tr>
							<td>{{ $content->title }}</td>
							<td>{{ \Illuminate\Support\Str::limit($content->description, 60) }}</td>
							<td>{{ \Illuminate\Support\Carbon::parse($content->scheduled_at)->format('d M Y, h:i A') }}</td>
							<td>
								@if($content->status)
									<span class="badge bg-success">Active</span>
								@else
									<span class="badge bg-secondary">Inactive</span>
								@endif
							</td>
							<td>
								<div class="d-flex gap-2">
									<a href="{{ route('admin.schedule.edit', $content->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
										<i class="align-middle" data-feather="edit-2"></i>
									</a>
									<form action="{{ route('admin.schedule.destroy', $content->id) }}" method="POST" class="d-inline"
										  onsubmit="return confirm('Delete this scheduled content?');">
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
							<td colspan="5" class="text-center">No scheduled content found.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection
