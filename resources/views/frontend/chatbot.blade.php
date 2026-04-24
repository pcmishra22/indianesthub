@extends('frontend.layout')
@section('content')
<div class="container mt-4">
    <h2>Chatbot for Leads</h2>
    @if(isset($messages) && count($messages))
        <ul class="list-group">
            @foreach($messages as $msg)
                <li class="list-group-item">{{ $msg }}</li>
            @endforeach
        </ul>
    @else
        <p>No chatbot messages found.</p>
    @endif
</div>
@endsection