@extends('backend.layout')
@section('title', 'Blogs')
@section('content')
<h1>Blogs List</h1>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($blogs as $blog)
                    <tr>
                        <td>{{ $blog->id }}</td>
                        <td>{{ $blog->title ?? '-' }}</td>
                        <td><!-- Actions --></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No blogs found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
