{{-- resources/views/frontend/user/wishlist.blade.php --}}
@extends('frontend.user.layout')

@section('page-title', 'My Wishlist')

@section('user-content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-0">My Wishlist</h4>
      <p class="text-muted mb-0">Properties you have saved for later.</p>
    </div>
    @if(isset($wishlistProperties) && $wishlistProperties->count() > 0)
      <span class="badge bg-danger fs-6">{{ $wishlistProperties->total() ?? $wishlistProperties->count() }} Saved</span>
    @endif
  </div>

  @if(isset($wishlistProperties) && $wishlistProperties->count() > 0)
    <div class="row g-4">
      @foreach($wishlistProperties as $property)
      <div class="col-lg-6 col-md-6">
        @include('frontend.partials.property-card', ['property' => $property])
      </div>
      @endforeach
    </div>

    {{-- Pagination --}}
    @if($wishlistProperties->hasPages())
    <div class="d-flex justify-content-center mt-4">
      {{ $wishlistProperties->links() }}
    </div>
    @endif
  @else
    <div class="text-center py-5">
      <div class="mb-3">
        <i class="bi bi-heart" style="font-size: 64px; color: #dee2e6;"></i>
      </div>
      <h5 class="text-muted">Your wishlist is empty</h5>
      <p class="text-muted mb-3">Start saving properties you like by clicking the heart icon on any property.</p>
      <a href="{{ route('properties') }}" class="btn btn-outline-success">
        <i class="bi bi-search me-1"></i> Browse Properties
      </a>
    </div>
  @endif
@endsection
