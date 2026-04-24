@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Site Banners</h1>
    <div class="banner-list">
        @foreach($banners as $banner)
            <div class="banner-item">
                <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner">
                <p>{{ $banner->title }}</p>
            </div>
        @endforeach
    </div>
</div>
@endsection