@extends('frontend.layout')
@section('content')
<div class="container mt-4">
    <h2>Chat Messaging</h2>
    @if(count($messages))
        <ul class="list-group">
            @foreach($messages as $message)
                <li class="list-group-item">{{ $message }}</li>
            @endforeach
        </ul>
    @else
        <p>No chat messages found.</p>
    @endif
</div>
@endsection
