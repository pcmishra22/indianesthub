@extends('frontend.layout')
@section('content')
<div class="container mt-4">
    <h2>All Reviews</h2>
    @if($reviews->count())
        <div class="list-group">
            @foreach($reviews as $review)
                <div class="list-group-item mb-2">
                    <h5>{{ $review->user->name ?? 'User' }}
                        <small class="text-muted">on {{ $review->property->title ?? 'Property' }}</small>
                    </h5>
                    <div>Rating: {{ $review->rating }} / 5</div>
                    <p>{{ $review->review_text }}</p>
                </div>
            @endforeach
        </div>
        <div class="mt-3">
            {{ $reviews->links() }}
        </div>
    @else
        <p>No reviews found.</p>
    @endif
</div>
@endsection
