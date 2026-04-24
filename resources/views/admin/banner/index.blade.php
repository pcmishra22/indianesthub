@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Manage Banners</h1>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary mb-3">Add Banner</a>
    <table class="table">
        <thead>
            <tr><th>Title</th><th>Image</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($banners as $banner)
                <tr>
                    <td>{{ $banner->title }}</td>
                    <td><img src="{{ asset('storage/' . $banner->image) }}" alt="Banner" width="100"></td>
                    <td>
                        <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" style="display:inline;">
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