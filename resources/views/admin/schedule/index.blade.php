@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Manage Scheduled Content</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary mb-3">Add Scheduled Content</a>
    <table class="table">
        <thead>
            <tr><th>Title</th><th>Description</th><th>Scheduled At</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($scheduledContents as $content)
                <tr>
                    <td>{{ $content->title }}</td>
                    <td>{{ $content->description }}</td>
                    <td>{{ $content->scheduled_at }}</td>
                    <td>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.dashboard') }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection