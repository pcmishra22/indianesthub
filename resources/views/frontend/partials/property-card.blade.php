{{-- resources/views/frontend/partials/property-card.blade.php --}}
{{-- Reusable property card partial - receives $property --}}
<div class="property-item">
  <a href="{{ route('property-details', $property) }}" class="property-link">
    <div class="property-image-wrapper">
      @php
        $imageUrl = null;
        if ($property->cover_image) {
            $imageUrl = asset('storage/' . $property->cover_image);
        } elseif ($property->images && $property->images->first()) {
            $imageUrl = asset('storage/' . $property->images->first()->image_path);
        } elseif ($property->gallery_images && is_array($property->gallery_images) && count($property->gallery_images) > 0) {
            $imageUrl = asset('storage/' . $property->gallery_images[0]);
        }
      @endphp
      <img src="{{ $imageUrl ?? asset('frontend/img/real-estate/property-exterior-2.webp') }}" alt="{{ $property->title }}" class="img-fluid" loading="lazy">
      <div class="property-status">
        @if($property->featured)
          <span class="status-badge featured">Featured</span>
        @endif
        @if($property->looking_for)
          <span class="status-badge sale">For {{ $property->looking_for }}</span>
        @endif
      </div>
      <div class="property-actions">
        @auth
        <button class="action-btn favorite-btn wishlist-toggle" data-property-id="{{ $property->id }}" data-toggle="tooltip" title="Add to Wishlist" onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $property->id }}, this);">
          <i class="bi bi-heart"></i>
        </button>
        @endauth
        @guest
        <a href="{{ route('login') }}" class="action-btn favorite-btn" data-toggle="tooltip" title="Login to Wishlist" onclick="event.stopPropagation();">
          <i class="bi bi-heart"></i>
        </a>
        @endguest
      </div>
    </div>
  </a>
  <div class="property-details">
    <a href="{{ route('property-details', $property) }}" class="property-link">
      <div class="property-header">
        <div class="property-price">
          @if($property->price)
            @php
              $price = $property->price;
              if ($price >= 10000000) {
                  $formatted = number_format($price / 10000000, 2) . ' Cr';
              } elseif ($price >= 100000) {
                  $formatted = number_format($price / 100000, 2) . ' L';
              } else {
                  $formatted = number_format($price);
              }
            @endphp
            <i class="bi bi-currency-rupee"></i>{{ $formatted }}
          @else
            Price on Request
          @endif
        </div>
        <div class="property-type">{{ $property->property_type ?? 'Property' }}</div>
      </div>
      <h4 class="property-title">{{ Str::limit($property->title, 45) }}</h4>
      @if($property->bhk_type)
        <span class="badge bg-light text-dark mb-1" style="font-size: 11px;">{{ $property->bhk_type }}</span>
      @endif
      <p class="property-address">
        <i class="bi bi-geo-alt"></i>
        {{ $property->city ?? '' }}{{ $property->city && $property->state ? ', ' : '' }}{{ $property->state ?? '' }}
      </p>
      <div class="property-specs">
        @if($property->bedrooms)
        <div class="spec-item">
          <i class="bi bi-house-door"></i>
          <span>{{ $property->bedrooms }} Bed{{ $property->bedrooms > 1 ? 's' : '' }}</span>
        </div>
        @endif
        @if($property->bathrooms)
        <div class="spec-item">
          <i class="bi bi-droplet"></i>
          <span>{{ $property->bathrooms }} Bath{{ $property->bathrooms > 1 ? 's' : '' }}</span>
        </div>
        @endif
        @if($property->area)
        <div class="spec-item">
          <i class="bi bi-arrows-angle-expand"></i>
          <span>{{ number_format($property->area) }} {{ $property->area_unit ?? 'sq ft' }}</span>
        </div>
        @endif
      </div>
    </a>
    <div class="property-agent-info">
      <a href="{{ route('property-details', $property) }}" class="property-link">
        <div class="agent-avatar">
          @if($property->dealer && $property->dealer->profile_photo)
            <img src="{{ asset('storage/' . $property->dealer->profile_photo) }}" alt="{{ $property->dealer->name ?? 'Dealer' }}">
          @else
            <img src="{{ asset('frontend/img/real-estate/agent-2.webp') }}" alt="Dealer">
          @endif
        </div>
        <div class="agent-details">
          <strong>{{ $property->contact_name ?? ($property->dealer?->name ?? ($property->builder?->company_name ?? 'N/A')) }}</strong>
          <span>{{ $property->company_name ?? ($property->dealer?->agency ?? ($property->builder ? 'Developer' : '')) }}</span>
        </div>
      </a>
      <div class="agent-contact">
        @php $contactPhone = config('app.contact_phone','7340753780'); @endphp
        <a href="tel:+91{{ $contactPhone }}" class="contact-btn" onclick="event.stopPropagation();">
          <i class="bi bi-telephone"></i>
        </a>
      </div>
    </div>
  </div>
</div>
