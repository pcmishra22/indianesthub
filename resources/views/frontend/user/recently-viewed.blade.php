{{-- resources/views/frontend/user/recently-viewed.blade.php --}}
@extends('frontend.user.layout')

@section('page-title', 'Recently Viewed')

@section('user-content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-0">Recently Viewed</h4>
      <p class="text-muted mb-0">Properties you have browsed recently.</p>
    </div>
    @if($recentlyViewed->count() > 0)
      <span class="badge fs-6" style="background-color: #0d6efd;">{{ $recentlyViewed->total() }} Properties</span>
    @endif
  </div>

  @if($recentlyViewed->count() > 0)
    <div class="row g-4">
      @foreach($recentlyViewed as $item)
        @if($item->property)
          <div class="col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; overflow: hidden;">
              {{-- Property Image --}}
              @php
                $imgUrl = null;
                if ($item->property->cover_image) {
                    $imgUrl = asset('storage/' . $item->property->cover_image);
                } elseif ($item->property->images && $item->property->images->first()) {
                    $imgUrl = asset('storage/' . $item->property->images->first()->image_path);
                }
              @endphp
              <div style="position: relative;">
                <img src="{{ $imgUrl ?? asset('frontend/img/real-estate/property-exterior-2.webp') }}"
                     alt="{{ $item->property->title }}"
                     class="w-100"
                     style="height: 180px; object-fit: cover;">
                <div style="position: absolute; top: 10px; right: 10px;">
                  <span class="badge bg-primary">{{ $item->property->looking_for ?? 'Sale' }}</span>
                </div>
                <div style="position: absolute; bottom: 10px; left: 10px;">
                  <small class="text-white" style="background: rgba(0,0,0,0.55); border-radius: 4px; padding: 2px 8px;">
                    <i class="bi bi-clock me-1"></i>{{ $item->viewed_at->diffForHumans() }}
                  </small>
                </div>
              </div>
              {{-- Card Body --}}
              <div class="card-body pb-2">
                <h6 class="fw-bold mb-1">
                  <a href="{{ route('property-details', $item->property) }}" class="text-dark text-decoration-none">
                    {{ Str::limit($item->property->title, 45) }}
                  </a>
                </h6>
                <p class="text-muted mb-2" style="font-size: 13px;">
                  <i class="bi bi-geo-alt me-1"></i>
                  {{ $item->property->locality ?? '' }}{{ ($item->property->locality && $item->property->city) ? ', ' : '' }}{{ $item->property->city ?? '' }}
                </p>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="fw-bold" style="color: #077f46; font-size: 16px;">
                    ₹{{ $item->property->price ? number_format($item->property->price) : 'Price on Request' }}
                  </span>
                  <a href="{{ route('property-details', $item->property) }}" class="btn btn-sm btn-outline-success" style="border-radius: 6px; font-size: 12px;">
                    View Details
                  </a>
                </div>
              </div>
              @if($item->property->bhk || $item->property->area)
              <div class="card-footer bg-white border-top-0 pt-0 pb-3 px-3">
                <div class="d-flex gap-3" style="font-size: 12px; color: #666;">
                  @if($item->property->bhk)
                    <span><i class="bi bi-door-open me-1"></i>{{ $item->property->bhk }} BHK</span>
                  @endif
                  @if($item->property->area)
                    <span><i class="bi bi-arrows-angle-expand me-1"></i>{{ number_format($item->property->area) }} sq.ft</span>
                  @endif
                  @if($item->property->property_type)
                    <span><i class="bi bi-building me-1"></i>{{ $item->property->property_type }}</span>
                  @endif
                </div>
              </div>
              @endif
            </div>
          </div>
        @endif
      @endforeach
    </div>

    {{-- Pagination --}}
    @if($recentlyViewed->hasPages())
    <div class="d-flex justify-content-center mt-4">
      {{ $recentlyViewed->links() }}
    </div>
    @endif

  @else
    <div class="text-center py-5">
      <div class="mb-3">
        <i class="bi bi-clock-history" style="font-size: 64px; color: #dee2e6;"></i>
      </div>
      <h5 class="text-muted">No recently viewed properties</h5>
      <p class="text-muted mb-3">Start browsing properties — they'll appear here for easy access.</p>
      <a href="{{ route('properties') }}" class="btn btn-outline-success">
        <i class="bi bi-search me-1"></i> Browse Properties
      </a>
    </div>
  @endif
@endsection
