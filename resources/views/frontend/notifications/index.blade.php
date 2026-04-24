@extends('frontend.layout')
@section('content')
<div class="container mt-4">
    <h2>Notifications</h2>
    @if(count($notifications))
        <ul class="list-group">
            @foreach($notifications as $notification)
                <li class="list-group-item">{{ $notification }}</li>
            @endforeach
        </ul>
    @else
        <p>No notifications found.</p>
    @endif
</div>
@endsection
