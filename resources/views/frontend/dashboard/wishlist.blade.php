@extends('frontend.layout')
@section('content')
<div class="container mt-4">
    <h2>Your Wishlist</h2>
    @if($wishlists->count())
        <div class="row">
            @foreach($wishlists as $wishlist)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $wishlist->property->title ?? 'Property' }}</h5>
                            <p class="card-text">{{ $wishlist->property->description ?? '' }}</p>
                            <p class="card-text"><strong>Price:</strong> {{ $wishlist->property->price ?? 'N/A' }}</p>
                            <a href="{{ route('property-details', $wishlist->property->slug ?? $wishlist->property->id) }}" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-3">
            {{ $wishlists->links() }}
        </div>
    @else
        <p>No properties in your wishlist.</p>
    @endif
</div>
@endsection
