@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Scheduled Content</h1>
    <div class="schedule-list">
        @foreach($scheduledContents as $content)
            <div class="schedule-item">
                <h4>{{ $content->title }}</h4>
                <p>{{ $content->description }}</p>
                <small>Scheduled for: {{ $content->scheduled_at }}</small>
            </div>
        @endforeach
    </div>
</div>
@endsection