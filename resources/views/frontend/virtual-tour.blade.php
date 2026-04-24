@extends('frontend.layout')
@section('content')
<div class="container mt-4">
    <h2>Virtual Tours & AR</h2>
    @if(isset($tours) && count($tours))
        <ul class="list-group">
            @foreach($tours as $tour)
                <li class="list-group-item">{{ $tour }}</li>
            @endforeach
        </ul>
    @else
        <p>No virtual tours available.</p>
    @endif
</div>
@endsection