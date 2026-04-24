@extends('frontend.layout')
@section('content')
<div class="container mt-4">
    <h2>AI Property Recommendations</h2>
    @if(isset($recommendations) && count($recommendations))
        <ul class="list-group">
            @foreach($recommendations as $rec)
                <li class="list-group-item">{{ $rec }}</li>
            @endforeach
        </ul>
    @else
        <p>No recommendations available.</p>
    @endif
</div>
@endsection