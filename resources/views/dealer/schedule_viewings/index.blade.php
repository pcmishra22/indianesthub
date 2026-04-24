@extends('dealer.layout')
@section('title', 'Schedule Viewings')
@section('content')
<div class="container-fluid">
  <h1 class="mb-4">Scheduled Viewings</h1>
  <div class="card">
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Property</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Date</th>
            <th>Time</th>
            <th>Message</th>
            <th>Created At</th>
          </tr>
        </thead>
        <tbody>
          @forelse($viewings as $v)
            <tr>
              <td>{{ $v->id }}</td>
              <td>
                @if($v->property)
                  <a href="{{ route('dealer.properties.show', $v->property->slug) }}">{{ $v->property->title }}</a>
                @else
                  N/A
                @endif
              </td>
              <td>{{ $v->name }}</td>
              <td>{{ $v->email }}</td>
              <td>{{ $v->phone }}</td>
              <td>{{ $v->date }}</td>
              <td>{{ $v->time }}</td>
              <td>{{ $v->message }}</td>
              <td>{{ $v->created_at->format('Y-m-d H:i') }}</td>
            </tr>
          @empty
            <tr><td colspan="9" class="text-center">No scheduled viewings found.</td></tr>
          @endforelse
        </tbody>
      </table>
      <div class="mt-3">{{ $viewings->links() }}</div>
    </div>
  </div>
</div>
@endsection
