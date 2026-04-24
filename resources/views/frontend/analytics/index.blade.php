@extends('frontend.layout')
@section('content')
<div class="container mt-4">
    <h2>Analytics Reporting</h2>
    @if(count($analytics))
        <ul class="list-group">
            @foreach($analytics as $item)
                <li class="list-group-item">{{ $item }}</li>
            @endforeach
        </ul>
    @else
        <p>No analytics data found.</p>
    @endif
</div>
@endsection
