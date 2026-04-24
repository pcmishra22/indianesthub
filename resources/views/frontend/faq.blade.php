@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Frequently Asked Questions</h1>
    <div class="faq-list">
        @foreach($faqs as $faq)
            <div class="faq-item">
                <h4>{{ $faq->question }}</h4>
                <p>{{ $faq->answer }}</p>
            </div>
        @endforeach
    </div>
</div>
@endsection