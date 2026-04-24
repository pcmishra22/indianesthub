@extends('frontend.layout')
@section('content')
<div class="container mt-4">
    <h2>Security & Compliance</h2>
    @if(count($compliance))
        <ul class="list-group">
            @foreach($compliance as $item)
                <li class="list-group-item">{{ $item }}</li>
            @endforeach
        </ul>
    @else
        <p>No security compliance data found.</p>
    @endif
</div>
@endsection
