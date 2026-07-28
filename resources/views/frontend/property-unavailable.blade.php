@extends('frontend.layout')

@section('title', 'Listing No Longer Available | ' . config('app.name'))
@section('meta_description', 'This property listing is no longer available. Browse similar active listings instead.')
@section('robots', 'noindex, follow')
@section('canonical', url()->current())

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <i class="fas fa-home text-muted mb-3" style="font-size: 48px; opacity: 0.4;"></i>
            <h1 class="h3 mb-3">This listing is no longer available</h1>
            <p class="text-muted mb-4">
                "{{ $property->title }}" has been removed — it may have been sold, rented out, or taken down by the lister.
            </p>
            <a href="{{ route('properties') }}" class="btn btn-primary">
                Browse All Properties
            </a>
        </div>
    </div>

    @if($similar->isNotEmpty())
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="h5 mb-4 text-center">You might be interested in these similar listings</h2>
            </div>
            @foreach($similar as $item)
                <div class="col-md-6 col-lg-3 mb-4">
                    <a href="{{ route('property-details', $item->slug) }}" class="text-decoration-none text-dark">
                        <div class="card h-100 shadow-sm">
                            @if($item->cover_image)
                                <img src="{{ asset('storage/' . $item->cover_image) }}" class="card-img-top" style="height:160px;object-fit:cover;" alt="{{ $item->title }}">
                            @endif
                            <div class="card-body">
                                <h3 class="h6 card-title mb-1">{{ \Illuminate\Support\Str::limit($item->title, 45) }}</h3>
                                <p class="text-muted small mb-0">{{ collect([$item->locality, $item->city])->filter()->implode(', ') }}</p>
                                @if($item->price)
                                    <p class="fw-bold mb-0 mt-1">₹{{ number_format($item->price) }}</p>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
