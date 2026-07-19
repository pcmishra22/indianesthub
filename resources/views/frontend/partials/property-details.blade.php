@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/frontend/properties.css') }}">
@endpush

<main class="pd-page">

  {{-- ===== BREADCRUMB ===== --}}
  <div class="pd-breadcrumb">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-fill me-1"></i>Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('properties') }}">Properties</a></li>
          @if($property->city)<li class="breadcrumb-item"><a href="{{ route('properties') }}?city={{ $property->city }}">{{ $property->city }}</a></li>@endif
          <li class="breadcrumb-item active">{{ Str::limit($property->title, 40) }}</li>
        </ol>
      </nav>
    </div>
  </div>

  {{-- ===== PROPERTY HEADER BAR ===== --}}
  <div class="pd-header-bar">
    <div class="container">
      <div class="row align-items-center gy-3">
        <div class="col-lg-7">
          {{-- Title + Badges --}}
          <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
            <span class="pd-status-tag {{ strtolower($property->looking_for) == 'rent' ? 'rent' : 'sale' }}">
              For {{ $property->looking_for }}
            </span>
            @if($property->is_featured)<span class="pd-status-tag" style="background:#fff7ed;color:#c2410c;">Featured</span>@endif
            @if($property->is_premium)<span class="pd-status-tag" style="background:#f5f3ff;color:#6d28d9;">Premium</span>@endif
            @if($property->rera_id)
            <span class="rera-badge"><i class="bi bi-patch-check-fill"></i>RERA: {{ $property->rera_id }}</span>
            @endif
          </div>

          <h1 class="pd-title">{{ $property->title }}</h1>

          <div class="pd-subtitle">
            <i class="bi bi-geo-alt-fill text-danger"></i>
            <span>{{ $property->address }}@if($property->locality), {{ $property->locality }}@endif, {{ $property->city }}, {{ $property->state }}@if($property->pincode) – {{ $property->pincode }}@endif</span>
            <span class="text-muted mx-1">·</span>
            <span><i class="bi bi-eye me-1"></i>{{ $property->views_count ?? 0 }} views</span>
            <span class="text-muted mx-1">·</span>
            <span>ID #{{ $property->id }}</span>
          </div>

          {{-- ===== SOCIAL PROOF STRIP ===== --}}
          <div class="pd-social-proof-strip mt-2">
            @if(isset($viewsThisWeek) && $viewsThisWeek > 0)
            <span class="pd-sp-badge sp-views">
              <i class="bi bi-graph-up-arrow"></i>
              {{ $viewsThisWeek }} views this week
            </span>
            @endif
            @if(isset($inquiriesThisWeek) && $inquiriesThisWeek > 0)
            <span class="pd-sp-badge sp-inquiries">
              <i class="bi bi-chat-dots-fill"></i>
              {{ $inquiriesThisWeek }} {{ Str::plural('enquiry', $inquiriesThisWeek) }} this week
            </span>
            @endif
            @if($property->dealer)
            <span class="pd-sp-badge sp-verified">
              <i class="bi bi-patch-check-fill"></i>
              Verified Dealer
            </span>
            @endif
            @if($property->rera_verified || $property->rera_id)
            <span class="pd-sp-badge sp-rera">
              <i class="bi bi-shield-fill-check"></i>
              RERA Verified
            </span>
            @endif
            @if($property->builder && $property->builder->is_verified)
            <span class="pd-sp-badge sp-builder-verified">
              <i class="bi bi-building-check"></i>
              Verified Builder
            </span>
            @endif
          </div>

        </div>

        <div class="col-lg-5">
          <div class="d-flex align-items-start justify-content-lg-end gap-3 flex-wrap">
            <div class="text-lg-end">
              @if($property->looking_for == 'Rent')
                <div class="pd-price-hero">
                  ₹{{ $property->monthly_rent ? number_format($property->monthly_rent) : number_format($property->price) }}
                  <span style="font-size:.95rem;font-weight:500;color:#64748b">/month</span>
                </div>
                <div class="pd-price-sub d-flex gap-2 flex-wrap justify-content-lg-end">
                  @if($property->security_deposit)<span><i class="bi bi-shield-check text-success me-1"></i>Deposit: {{ $property->security_deposit }}</span>@endif
                  @if($property->maintenance_charges)<span><i class="bi bi-tools text-muted me-1"></i>Maint: ₹{{ number_format($property->maintenance_charges) }}/mo</span>@endif
                </div>
              @else
                <div class="pd-price-hero">₹{{ number_format($property->price) }}</div>
                <div class="pd-price-sub d-flex gap-2 flex-wrap justify-content-lg-end">
                  @if($property->price_per_sqft)<span>₹{{ number_format($property->price_per_sqft) }}/sqft</span>@endif
                  @if($property->negotiable)<span class="text-success fw-semibold"><i class="bi bi-check-circle me-1"></i>Negotiable</span>@endif
                </div>
              @endif
            </div>
            <div class="pd-share-save align-self-start mt-1">
              <button class="pd-icon-btn" title="Save"><i class="bi bi-heart"></i></button>
              <button class="pd-icon-btn" title="Share" onclick="navigator.share ? navigator.share({title:'{{$property->title}}',url:window.location.href}) : alert('Copy: ' + window.location.href)"><i class="bi bi-share"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container py-3">
    <div class="row g-3">

      {{-- ===== LEFT MAIN CONTENT ===== --}}
      <div class="col-lg-8">

        {{-- ===== PHOTO GALLERY ===== --}}
        @php
          $images = collect();
          if($property->images && count($property->images)) {
            foreach($property->images as $img) {
              $images->push(url('storage/dealer/' . $property->property_dealer_id . '/' . $property->id . '/images/' . basename($img->image_path)));
            }
          } elseif(!empty($property->cover_image)) {
            $images->push(asset('storage/' . $property->cover_image));
          } else {
            $images->push('/assets/img/real-estate/property-exterior-7.webp');
            $images->push('/assets/img/real-estate/property-interior-7.webp');
            $images->push('/assets/img/real-estate/property-exterior-9.webp');
            $images->push('/assets/img/real-estate/features-5.webp');
          }
          $totalPhotos = $images->count();
        @endphp

        {{-- Desktop gallery grid --}}
        <div class="pd-gallery-section rounded overflow-hidden mb-2 d-none d-md-block" data-bs-toggle="modal" data-bs-target="#galleryModal" style="cursor:pointer;">
          @if($totalPhotos >= 3)
          <div class="pd-gallery-grid" style="height:420px;">
            <div class="gal-main">
              <img src="{{ $images[0] }}" alt="Main photo" style="height:100%;width:100%;object-fit:cover;">
              <div class="gal-overlay-badge">
                <span class="badge-tag {{ strtolower($property->looking_for) == 'rent' ? '' : 'sale' }}">For {{ $property->looking_for }}</span>
                @if($property->is_featured)<span class="badge-tag featured">Featured</span>@endif
              </div>
            </div>
            <div class="gal-side gal-side-1">
              <img src="{{ $images[1] }}" alt="Photo 2" style="height:100%;width:100%;object-fit:cover;">
            </div>
            <div class="gal-side" style="position:relative;">
              <img src="{{ $images[2] }}" alt="Photo 3" style="height:100%;width:100%;object-fit:cover;">
              @if($totalPhotos > 3)
              <div class="gal-last-overlay">
                <span><i class="bi bi-images me-2"></i>+{{ $totalPhotos - 3 }} More</span>
              </div>
              @endif
              <div class="gal-photo-count" style="bottom:10px;right:10px;">
                <i class="bi bi-camera"></i> {{ $totalPhotos }} Photos
              </div>
            </div>
          </div>
          @elseif($totalPhotos == 2)
          <div style="display:grid;grid-template-columns:1fr 1fr;height:380px;gap:4px;">
            <img src="{{ $images[0] }}" style="width:100%;height:100%;object-fit:cover;" alt="Photo 1">
            <img src="{{ $images[1] }}" style="width:100%;height:100%;object-fit:cover;" alt="Photo 2">
          </div>
          @else
          <div style="height:380px;">
            <img src="{{ $images[0] }}" style="width:100%;height:100%;object-fit:cover;" alt="Main photo">
          </div>
          @endif
        </div>

        {{-- Mobile swiper gallery --}}
        <div class="d-md-none mb-2">
          <div class="pd-swiper-gallery swiper init-swiper" style="border-radius:var(--pd-radius);overflow:hidden;">
            <script type="application/json" class="swiper-config">{"loop":true,"speed":600,"autoplay":{"delay":5000},"navigation":{"nextEl":".swiper-button-next","prevEl":".swiper-button-prev"},"thumbs":{"swiper":".pd-mob-thumb-slider"}}</script>
            <div class="swiper-wrapper">
              @foreach($images as $imgUrl)
              <div class="swiper-slide"><img src="{{ $imgUrl }}" class="hero-image" alt="Property photo" style="height:260px;width:100%;object-fit:cover;"></div>
              @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
          </div>
          @if($totalPhotos > 1)
          <div class="pd-mob-thumb-slider swiper init-swiper mt-2" style="padding:4px 0;">
            <script type="application/json" class="swiper-config">{"spaceBetween":6,"slidesPerView":5,"freeMode":true,"watchSlidesProgress":true}</script>
            <div class="swiper-wrapper">
              @foreach($images as $imgUrl)
              <div class="swiper-slide"><img src="{{ $imgUrl }}" class="thumbnail-img" alt="thumb" style="height:55px;width:100%;object-fit:cover;border-radius:4px;opacity:.7;"></div>
              @endforeach
            </div>
          </div>
          @endif
        </div>

        {{-- ===== QUICK HIGHLIGHTS ===== --}}
        <div class="pd-highlights">
          <div class="pd-highlights-inner">
            @if($property->bhk_type || $property->bedrooms)
            <div class="pd-hl-item">
              <div class="hl-icon"><i class="bi bi-house-door"></i></div>
              <div class="hl-value">{{ $property->bhk_type ?? $property->bedrooms . ' BHK' }}</div>
              <div class="hl-label">Configuration</div>
            </div>
            @endif
            @if($property->carpet_area || $property->builtup_area || $property->area)
            <div class="pd-hl-item">
              <div class="hl-icon"><i class="bi bi-arrows-angle-expand"></i></div>
              <div class="hl-value">{{ number_format($property->carpet_area ?? $property->builtup_area ?? $property->area) }}</div>
              <div class="hl-label">{{ $property->area_unit ?? 'Sqft' }}</div>
            </div>
            @endif
            @if($property->bathrooms)
            <div class="pd-hl-item">
              <div class="hl-icon"><i class="bi bi-droplet-half"></i></div>
              <div class="hl-value">{{ $property->bathrooms }}</div>
              <div class="hl-label">Bathrooms</div>
            </div>
            @endif
            @if($property->balconies)
            <div class="pd-hl-item">
              <div class="hl-icon"><i class="bi bi-columns-gap"></i></div>
              <div class="hl-value">{{ $property->balconies }}</div>
              <div class="hl-label">Balconies</div>
            </div>
            @endif
            @if($property->facing)
            <div class="pd-hl-item">
              <div class="hl-icon"><i class="bi bi-compass"></i></div>
              <div class="hl-value">{{ $property->facing }}</div>
              <div class="hl-label">Facing</div>
            </div>
            @endif
            @if($property->floor_number || $property->floor)
            <div class="pd-hl-item">
              <div class="hl-icon"><i class="bi bi-building-up"></i></div>
              <div class="hl-value">{{ $property->floor_number ?? $property->floor }}</div>
              <div class="hl-label">Floor</div>
            </div>
            @endif
            @if($property->covered_parking || $property->open_parking)
            <div class="pd-hl-item">
              <div class="hl-icon"><i class="bi bi-car-front"></i></div>
              <div class="hl-value">{{ ($property->covered_parking ?? 0) + ($property->open_parking ?? 0) }}</div>
              <div class="hl-label">Parking</div>
            </div>
            @endif
            @if($property->furnishing_status)
            <div class="pd-hl-item">
              <div class="hl-icon"><i class="bi bi-lamp"></i></div>
              <div class="hl-value" style="font-size:.85rem;">{{ $property->furnishing_status }}</div>
              <div class="hl-label">Furnishing</div>
            </div>
            @endif
            @if($property->possession_status)
            <div class="pd-hl-item">
              <div class="hl-icon"><i class="bi bi-calendar-check"></i></div>
              <div class="hl-value" style="font-size:.8rem;">{{ Str::limit($property->possession_status, 10) }}</div>
              <div class="hl-label">Possession</div>
            </div>
            @endif
          </div>
        </div>

        {{-- ===== ABOUT THIS PROPERTY ===== --}}
        @if($property->description)
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-info-circle-fill"></i> About This Property</div>
          <div class="pd-description">
            <div class="pd-description-clamp" id="descClamp" style="max-height:120px;">
              {!! $property->description !!}
            </div>
            <button class="pd-readmore-btn" id="descToggle" onclick="toggleDesc()">Read More <i class="bi bi-chevron-down"></i></button>
          </div>
        </div>
        @endif

        {{-- ===== PROPERTY OVERVIEW ===== --}}
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-list-columns-reverse"></i> Property Overview</div>
          <div class="pd-overview-grid">
            <div class="pd-ov-item">
              <div class="ov-label">Property Type</div>
              <div class="ov-value">{{ $property->property_type }}</div>
            </div>
            @if($property->bhk_type)
            <div class="pd-ov-item">
              <div class="ov-label">BHK Type</div>
              <div class="ov-value">{{ $property->bhk_type }}</div>
            </div>
            @endif
            @if($property->option_type)
            <div class="pd-ov-item">
              <div class="ov-label">Property Sub-type</div>
              <div class="ov-value">{{ $property->option_type }}</div>
            </div>
            @endif
            <div class="pd-ov-item">
              <div class="ov-label">Listed For</div>
              <div class="ov-value">{{ $property->looking_for }}</div>
            </div>
            @if($property->bedrooms)
            <div class="pd-ov-item">
              <div class="ov-label">Bedrooms</div>
              <div class="ov-value">{{ $property->bedrooms }}</div>
            </div>
            @endif
            @if($property->bathrooms)
            <div class="pd-ov-item">
              <div class="ov-label">Bathrooms</div>
              <div class="ov-value">{{ $property->bathrooms }}</div>
            </div>
            @endif
            @if($property->balconies)
            <div class="pd-ov-item">
              <div class="ov-label">Balconies</div>
              <div class="ov-value">{{ $property->balconies }}</div>
            </div>
            @endif
            @if($property->total_floors)
            <div class="pd-ov-item">
              <div class="ov-label">Total Floors</div>
              <div class="ov-value">{{ $property->total_floors }}</div>
            </div>
            @endif
            @if($property->floor_number)
            <div class="pd-ov-item">
              <div class="ov-label">Floor Number</div>
              <div class="ov-value">{{ $property->floor_number }}</div>
            </div>
            @endif
            @if($property->facing)
            <div class="pd-ov-item">
              <div class="ov-label">Facing</div>
              <div class="ov-value">{{ $property->facing }}</div>
            </div>
            @endif
            @if($property->property_age)
            <div class="pd-ov-item">
              <div class="ov-label">Property Age</div>
              <div class="ov-value">{{ $property->property_age }}</div>
            </div>
            @endif
            @if($property->furnishing_status)
            <div class="pd-ov-item">
              <div class="ov-label">Furnishing</div>
              <div class="ov-value">{{ $property->furnishing_status }}</div>
            </div>
            @endif
            @if($property->possession_status)
            <div class="pd-ov-item">
              <div class="ov-label">Possession</div>
              <div class="ov-value">{{ $property->possession_status }}</div>
            </div>
            @endif
            @if($property->possession_date)
            <div class="pd-ov-item">
              <div class="ov-label">Possession Date</div>
              <div class="ov-value">{{ $property->possession_date }}</div>
            </div>
            @endif
            @if($property->ownership_type)
            <div class="pd-ov-item">
              <div class="ov-label">Ownership</div>
              <div class="ov-value">{{ $property->ownership_type }}</div>
            </div>
            @endif
          </div>
        </div>

        {{-- ===== AREA DETAILS ===== --}}
        @if($property->super_builtup_area || $property->builtup_area || $property->carpet_area || $property->plot_area)
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-rulers"></i> Area Details</div>
          <div class="pd-overview-grid">
            @if($property->super_builtup_area)
            <div class="pd-ov-item">
              <div class="ov-label">Super Built-up Area</div>
              <div class="ov-value">{{ number_format($property->super_builtup_area) }} {{ $property->area_unit ?? 'sqft' }}</div>
            </div>
            @endif
            @if($property->builtup_area)
            <div class="pd-ov-item">
              <div class="ov-label">Built-up Area</div>
              <div class="ov-value">{{ number_format($property->builtup_area) }} {{ $property->area_unit ?? 'sqft' }}</div>
            </div>
            @endif
            @if($property->carpet_area)
            <div class="pd-ov-item">
              <div class="ov-label">Carpet Area</div>
              <div class="ov-value">{{ number_format($property->carpet_area) }} {{ $property->area_unit ?? 'sqft' }}</div>
            </div>
            @endif
            @if($property->plot_area)
            <div class="pd-ov-item">
              <div class="ov-label">Plot Area</div>
              <div class="ov-value">{{ number_format($property->plot_area) }} {{ $property->area_unit ?? 'sqft' }}</div>
            </div>
            @endif
            @if($property->plot_length)
            <div class="pd-ov-item">
              <div class="ov-label">Plot Length</div>
              <div class="ov-value">{{ $property->plot_length }} {{ $property->area_unit ?? 'ft' }}</div>
            </div>
            @endif
            @if($property->plot_breadth)
            <div class="pd-ov-item">
              <div class="ov-label">Plot Breadth</div>
              <div class="ov-value">{{ $property->plot_breadth }} {{ $property->area_unit ?? 'ft' }}</div>
            </div>
            @endif
          </div>
        </div>
        @endif

        {{-- ===== PRICING DETAILS ===== --}}
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-currency-rupee"></i> Price Details</div>
          <div class="pd-overview-grid">
            <div class="pd-ov-item">
              <div class="ov-label">{{ $property->looking_for == 'Rent' ? 'Monthly Rent' : 'Asking Price' }}</div>
              <div class="ov-value" style="color:var(--pd-primary-dark);font-size:1rem;">₹{{ number_format($property->price) }}</div>
            </div>
            @if($property->expected_price && $property->expected_price != $property->price)
            <div class="pd-ov-item">
              <div class="ov-label">Expected Price</div>
              <div class="ov-value">₹{{ number_format($property->expected_price) }}</div>
            </div>
            @endif
            @if($property->price_per_sqft)
            <div class="pd-ov-item">
              <div class="ov-label">Price per Sqft</div>
              <div class="ov-value">₹{{ number_format($property->price_per_sqft) }}</div>
            </div>
            @endif
            <div class="pd-ov-item">
              <div class="ov-label">Negotiable</div>
              <div class="ov-value" style="{{ $property->negotiable ? 'color:var(--pd-green)' : '' }}">{{ $property->negotiable ? '✓ Yes' : 'No' }}</div>
            </div>
            @if($property->booking_amount)
            <div class="pd-ov-item">
              <div class="ov-label">Booking Amount</div>
              <div class="ov-value">₹{{ number_format($property->booking_amount) }}</div>
            </div>
            @endif
            @if($property->security_deposit)
            <div class="pd-ov-item">
              <div class="ov-label">Security Deposit</div>
              <div class="ov-value">{{ $property->security_deposit }}</div>
            </div>
            @endif
            @if($property->maintenance_charges)
            <div class="pd-ov-item">
              <div class="ov-label">Maintenance</div>
              <div class="ov-value">₹{{ number_format($property->maintenance_charges) }}/mo</div>
            </div>
            @endif
            @if($property->monthly_rent && $property->looking_for == 'Rent')
            <div class="pd-ov-item">
              <div class="ov-label">Monthly Rent</div>
              <div class="ov-value" style="color:var(--pd-primary-dark);">₹{{ number_format($property->monthly_rent) }}</div>
            </div>
            @endif
            @if($property->lease_duration)
            <div class="pd-ov-item">
              <div class="ov-label">Lease Duration</div>
              <div class="ov-value">{{ $property->lease_duration }}</div>
            </div>
            @endif
          </div>
        </div>

        {{-- ===== AMENITIES ===== --}}
        @php
          $amenitiesArr = [];
          if (!empty($property->amenities)) {
            $amenitiesArr = is_array($property->amenities) ? $property->amenities : json_decode($property->amenities, true);
            if (!is_array($amenitiesArr)) $amenitiesArr = [];
          }
          $amenityIcons = [
            'lift' => 'bi-arrow-up-square', 'elevator' => 'bi-arrow-up-square',
            'gym' => 'bi-activity', 'fitness' => 'bi-activity',
            'pool' => 'bi-water', 'swimming' => 'bi-water',
            'parking' => 'bi-car-front', 'car' => 'bi-car-front',
            'security' => 'bi-shield-check', 'guard' => 'bi-shield-check', '24/7' => 'bi-shield-check',
            'power' => 'bi-lightning-charge', 'backup' => 'bi-lightning-charge',
            'garden' => 'bi-tree', 'park' => 'bi-tree', 'lawn' => 'bi-tree',
            'wifi' => 'bi-wifi', 'internet' => 'bi-wifi',
            'club' => 'bi-house-heart', 'clubhouse' => 'bi-house-heart',
            'play' => 'bi-people', 'children' => 'bi-people',
            'cctv' => 'bi-camera-video', 'camera' => 'bi-camera-video',
            'waste' => 'bi-trash3', 'disposal' => 'bi-trash3',
            'fire' => 'bi-fire', 'firefighting' => 'bi-fire',
            'maintenance' => 'bi-tools', 'management' => 'bi-tools',
            'rainwater' => 'bi-cloud-rain', 'water' => 'bi-droplet',
            'solar' => 'bi-sun', 'gas' => 'bi-flame',
            'intercom' => 'bi-telephone', 'concierge' => 'bi-person-badge',
            'pet' => 'bi-heart', 'vastu' => 'bi-compass',
          ];
          function getAmenityIcon($amenity, $icons) {
            $lower = strtolower($amenity);
            foreach ($icons as $key => $icon) {
              if (str_contains($lower, $key)) return $icon;
            }
            return 'bi-check-circle';
          }
        @endphp
        @php
          $features = [];
          if ($property->gated_society) $features[] = ['name' => 'Gated Society', 'icon' => 'bi-shield-lock'];
          if ($property->corner_property) $features[] = ['name' => 'Corner Property', 'icon' => 'bi-geo'];
          if ($property->vastu_compliant) $features[] = ['name' => 'Vastu Compliant', 'icon' => 'bi-compass'];
          if ($property->wheelchair_friendly) $features[] = ['name' => 'Wheelchair Friendly', 'icon' => 'bi-person-wheelchair'];
          if ($property->overlooking_park) $features[] = ['name' => 'Overlooking Park', 'icon' => 'bi-tree'];
          if ($property->overlooking_road) $features[] = ['name' => 'Overlooking Road', 'icon' => 'bi-signpost'];
          if ($property->pet_friendly) $features[] = ['name' => 'Pet Friendly', 'icon' => 'bi-heart'];
        @endphp

        @if(!empty($amenitiesArr) || !empty($features))
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-stars"></i> Amenities & Features</div>
          @if(!empty($amenitiesArr))
          <h6 class="text-muted fw-semibold mb-3" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px;">Amenities</h6>
          <div class="pd-amenities-grid mb-4">
            @foreach($amenitiesArr as $amenity)
            <div class="pd-amenity-chip">
              <i class="bi {{ getAmenityIcon($amenity, $amenityIcons) }}"></i>
              <span>{{ $amenity }}</span>
            </div>
            @endforeach
          </div>
          @endif
          @if(!empty($features))
          <h6 class="text-muted fw-semibold mb-3" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px;">Property Features</h6>
          <div class="pd-amenities-grid">
            @foreach($features as $feature)
            <div class="pd-amenity-chip">
              <i class="bi {{ $feature['icon'] }}"></i>
              <span>{{ $feature['name'] }}</span>
            </div>
            @endforeach
          </div>
          @endif
        </div>
        @endif

        {{-- ===== PARKING & UTILITIES ===== --}}
        @if($property->covered_parking || $property->open_parking || $property->water_supply || $property->electricity_status || $property->gas_pipeline !== null || $property->drainage !== null)
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-plug-fill"></i> Parking & Utilities</div>
          <div class="pd-overview-grid">
            @if($property->covered_parking)<div class="pd-ov-item"><div class="ov-label">Covered Parking</div><div class="ov-value">{{ $property->covered_parking }}</div></div>@endif
            @if($property->open_parking)<div class="pd-ov-item"><div class="ov-label">Open Parking</div><div class="ov-value">{{ $property->open_parking }}</div></div>@endif
            @if($property->water_supply)<div class="pd-ov-item"><div class="ov-label">Water Supply</div><div class="ov-value">{{ $property->water_supply }}</div></div>@endif
            @if($property->electricity_status)<div class="pd-ov-item"><div class="ov-label">Electricity</div><div class="ov-value">{{ $property->electricity_status }}</div></div>@endif
            @if($property->gas_pipeline !== null)<div class="pd-ov-item"><div class="ov-label">Gas Pipeline</div><div class="ov-value">{{ $property->gas_pipeline ? 'Available' : 'Not Available' }}</div></div>@endif
            @if($property->drainage !== null)<div class="pd-ov-item"><div class="ov-label">Drainage</div><div class="ov-value">{{ $property->drainage ? 'Available' : 'Not Available' }}</div></div>@endif
          </div>
        </div>
        @endif

        {{-- ===== LEGAL & RERA ===== --}}
        @if($property->rera_id || $property->property_approval || $property->occupancy_certificate || $property->completion_certificate || $property->legal_clearance_status)
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-patch-check-fill"></i> Legal & Approvals</div>
          <div class="pd-overview-grid">
            @if($property->rera_id)
            <div class="pd-ov-item">
              <div class="ov-label">RERA ID</div>
              <div class="ov-value">{{ $property->rera_id }} @if($property->rera_verified)<span class="badge bg-success ms-1" style="font-size:.65rem;">Verified</span>@endif</div>
            </div>
            @endif
            @if($property->ownership_type)<div class="pd-ov-item"><div class="ov-label">Ownership</div><div class="ov-value">{{ $property->ownership_type }}</div></div>@endif
            @if($property->property_approval)<div class="pd-ov-item"><div class="ov-label">Approval</div><div class="ov-value">{{ $property->property_approval }}</div></div>@endif
            @if($property->occupancy_certificate)<div class="pd-ov-item"><div class="ov-label">OC Certificate</div><div class="ov-value">{{ $property->occupancy_certificate }}</div></div>@endif
            @if($property->completion_certificate)<div class="pd-ov-item"><div class="ov-label">CC Certificate</div><div class="ov-value">{{ $property->completion_certificate }}</div></div>@endif
            @if($property->legal_clearance_status)<div class="pd-ov-item"><div class="ov-label">Legal Clearance</div><div class="ov-value">{{ $property->legal_clearance_status }}</div></div>@endif
          </div>
        </div>
        @endif

        {{-- ===== FLOOR PLAN ===== --}}
        @if(!empty($property->floor_plan_images) || !empty($property->floor_plan))
        <div class="pd-card pd-floorplan">
          <div class="pd-card-title"><i class="bi bi-diagram-3"></i> Floor Plan</div>
          @if(!empty($property->floor_plan_images) && is_array($property->floor_plan_images))
            @foreach($property->floor_plan_images as $img)
            <img src="{{ asset('storage/' . $img) }}" class="img-fluid mb-3" alt="Floor Plan">
            @endforeach
          @elseif(!empty($property->floor_plan))
            <img src="{{ asset('storage/' . $property->floor_plan) }}" class="img-fluid" alt="Floor Plan">
          @endif
          @if(!empty($property->floor_plan_details))
          <p class="mt-3 text-muted" style="font-size:.85rem;">{!! nl2br(e($property->floor_plan_details)) !!}</p>
          @endif
        </div>
        @endif

        {{-- ===== VIDEO TOUR ===== --}}
        @if($property->video_url)
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-camera-reels-fill"></i> Video Tour</div>
          <video width="100%" controls style="max-height:380px;border-radius:8px;">
            <source src="{{ asset('storage/' . $property->video_url) }}" type="video/mp4">
            Your browser does not support the video tag.
          </video>
        </div>
        @endif

        {{-- ===== VIRTUAL TOUR & BROCHURE ===== --}}
        @if($property->virtual_tour_url || $property->brochure_pdf)
        <div class="pd-card">
          @if($property->virtual_tour_url || $property->brochure_pdf)
          <div class="pd-card-title"><i class="bi bi-box-arrow-up-right"></i> Downloads & Tours</div>
          <div class="d-flex gap-3 flex-wrap">
            @if($property->virtual_tour_url)
            <a href="{{ $property->virtual_tour_url }}" target="_blank" class="btn btn-outline-primary" style="border-radius:7px;font-weight:600;">
              <i class="bi bi-camera-reels me-2"></i>Virtual Tour
            </a>
            @endif
            @if($property->brochure_pdf)
            <a href="{{ asset('storage/' . $property->brochure_pdf) }}" target="_blank" class="btn btn-outline-danger" style="border-radius:7px;font-weight:600;">
              <i class="bi bi-file-earmark-pdf me-2"></i>Download Brochure
            </a>
            @endif
          </div>
          @endif
        </div>
        @endif

        {{-- ===== MAP LOCATION ===== --}}
        @if($property->latitude && $property->longitude)
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-map-fill"></i> Location</div>
          <p class="text-muted mb-3" style="font-size:.85rem;"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $property->address }}@if($property->locality), {{ $property->locality }}@endif, {{ $property->city }}</p>
          <div class="pd-map-wrapper">
            <iframe src="{{ $property->map_embed_url }}" width="100%" height="360" style="border:0;" allowfullscreen loading="lazy"></iframe>
          </div>
        </div>
        @elseif($property->map_url)
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-map-fill"></i> Location</div>
          <p class="text-muted mb-3" style="font-size:.85rem;"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $property->address }}, {{ $property->city }}</p>
          <a href="{{ $property->map_url }}" target="_blank" class="btn btn-outline-primary" style="border-radius:7px;font-weight:600;">
            <i class="bi bi-geo-alt me-2"></i>View on Google Maps
          </a>
        </div>
        @endif

        {{-- ===== BUILDER / PROJECT CARD ===== --}}
        @if($property->builder)
        <div class="pd-card pd-builder-card">
          <div class="pd-card-title"><i class="bi bi-building"></i> Builder & Project Info</div>
          <div class="pd-builder-profile">
            <div class="pd-builder-logo-wrap">
              @if($property->builder->logo)
                <img src="{{ asset('storage/' . $property->builder->logo) }}" alt="{{ $property->builder->display_name }}" class="pd-builder-logo">
              @else
                <div class="pd-builder-logo-placeholder"><i class="bi bi-building-fill"></i></div>
              @endif
            </div>
            <div class="pd-builder-info">
              <div class="pd-builder-name">{{ $property->builder->display_name }}</div>
              @if($property->builder->established_year)
              <div class="pd-builder-meta"><i class="bi bi-calendar3 me-1"></i>Est. {{ $property->builder->established_year }}</div>
              @endif
              @if($property->builder->is_verified)
              <span class="pd-builder-verified"><i class="bi bi-patch-check-fill me-1"></i>Verified Builder</span>
              @endif
            </div>
          </div>

          <div class="pd-builder-stats">
            <div class="pd-bstat">
              <div class="pd-bstat-num">{{ $builderTotalProjects }}</div>
              <div class="pd-bstat-label">Total Projects</div>
            </div>
            @if($property->builder->total_delivered_projects)
            <div class="pd-bstat">
              <div class="pd-bstat-num">{{ $property->builder->total_delivered_projects }}</div>
              <div class="pd-bstat-label">Delivered</div>
            </div>
            @endif
            @if($property->builder->rating)
            <div class="pd-bstat">
              <div class="pd-bstat-num">{{ number_format($property->builder->rating, 1) }}<span style="font-size:.75rem;">★</span></div>
              <div class="pd-bstat-label">Rating</div>
            </div>
            @endif
          </div>

          @if($property->builder->description)
          <p class="pd-builder-desc">{{ Str::limit($property->builder->description, 160) }}</p>
          @endif

          @if($property->builderProject)
          <div class="pd-project-info">
            <div class="pd-project-label"><i class="bi bi-layers me-1"></i>Project</div>
            <div class="pd-project-name">{{ $property->builderProject->title }}</div>
            @if($property->builderProject->status)
            <span class="pd-project-status {{ $property->builderProject->status_badge_class ?? 'bg-info' }}">{{ $property->builderProject->status }}</span>
            @endif
            @if($property->builderProject->rera_id)
            <div class="pd-project-rera"><i class="bi bi-shield-fill-check text-success me-1"></i>RERA: {{ $property->builderProject->rera_id }}</div>
            @endif
            @if($property->builderProject->possession_date)
            <div class="pd-project-meta"><i class="bi bi-calendar-check me-1"></i>Possession: {{ $property->builderProject->possession_date->format('M Y') }}</div>
            @endif
          </div>
          @endif

          @if($property->builder->slug)
          <a href="{{ route('builders.show', $property->builder->slug) }}" class="btn btn-outline-primary btn-sm w-100 mt-3" style="border-radius:7px;font-weight:600;">
            <i class="bi bi-arrow-right-circle me-1"></i>View All by {{ $property->builder->display_name }}
          </a>
          @endif
        </div>
        @endif

        {{-- ===== OTHER PROPERTIES BY SAME BUILDER ===== --}}
        @if(isset($builderProperties) && $builderProperties->count())
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-building-fill"></i> More from {{ $property->builder->display_name ?? 'Builder' }}</div>
          <div class="pd-similar-grid">
            @foreach($builderProperties as $bp)
            <a href="{{ route('property-details', $bp) }}" class="pd-similar-card">
              @if($bp->images && $bp->images->count())
                <img src="{{ url('storage/dealer/' . $bp->property_dealer_id . '/' . $bp->id . '/images/' . basename($bp->images->first()->image_path)) }}" alt="{{ $bp->title }}">
              @elseif($bp->cover_image)
                <img src="{{ asset('storage/' . $bp->cover_image) }}" alt="{{ $bp->title }}">
              @else
                <img src="/assets/img/real-estate/property-exterior-4.webp" alt="{{ $bp->title }}">
              @endif
              <div class="similar-body">
                <div class="sim-price">₹{{ number_format($bp->price) }}@if($bp->looking_for == 'Rent')<span style="font-size:.75rem;font-weight:500;color:#64748b">/mo</span>@endif</div>
                <div class="sim-title">{{ Str::limit($bp->title, 45) }}</div>
                <div class="sim-specs">
                  @if($bp->bedrooms)<span><i class="bi bi-house-door"></i> {{ $bp->bedrooms }} Bed</span>@endif
                  @if($bp->area)<span><i class="bi bi-arrows-angle-expand"></i> {{ number_format($bp->area) }} sqft</span>@endif
                </div>
              </div>
            </a>
            @endforeach
          </div>
        </div>
        @endif

        {{-- ===== HOME MARKETPLACE — FURNISH THIS HOME ===== --}}
        @include('frontend.partials.marketplace-widget')

        {{-- ===== WHY BUY HERE? ===== --}}
        @if($property->city || $property->locality || $property->nearby_schools || $property->nearby_hospitals)
        <div class="pd-card pd-why-buy-card">
          <div class="pd-card-title"><i class="bi bi-star-fill" style="color:#f59e0b;"></i>
            Why Buy in {{ $property->locality ?? $property->city }}?
          </div>
          <div class="pd-why-buy-grid">
            @if($property->nearby_schools)
            <div class="pd-why-item">
              <div class="pd-why-icon" style="background:#eff6ff;color:#1d4ed8;"><i class="bi bi-mortarboard-fill"></i></div>
              <div>
                <div class="pd-why-label">Schools Nearby</div>
                <div class="pd-why-val">{{ Str::limit($property->nearby_schools, 60) }}</div>
              </div>
            </div>
            @endif
            @if($property->nearby_hospitals)
            <div class="pd-why-item">
              <div class="pd-why-icon" style="background:#fef2f2;color:#dc2626;"><i class="bi bi-hospital-fill"></i></div>
              <div>
                <div class="pd-why-label">Hospitals Nearby</div>
                <div class="pd-why-val">{{ Str::limit($property->nearby_hospitals, 60) }}</div>
              </div>
            </div>
            @endif
            @if($property->nearby_metro)
            <div class="pd-why-item">
              <div class="pd-why-icon" style="background:#ecfdf5;color:#059669;"><i class="bi bi-train-lightrail-front-fill"></i></div>
              <div>
                <div class="pd-why-label">Metro / Transit</div>
                <div class="pd-why-val">{{ Str::limit($property->nearby_metro, 60) }}</div>
              </div>
            </div>
            @endif
            @if($property->nearby_malls)
            <div class="pd-why-item">
              <div class="pd-why-icon" style="background:#fffbeb;color:#d97706;"><i class="bi bi-shop-window"></i></div>
              <div>
                <div class="pd-why-label">Shopping</div>
                <div class="pd-why-val">{{ Str::limit($property->nearby_malls, 60) }}</div>
              </div>
            </div>
            @endif
            @if($property->gated_society)
            <div class="pd-why-item">
              <div class="pd-why-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-shield-lock-fill"></i></div>
              <div>
                <div class="pd-why-label">Gated Community</div>
                <div class="pd-why-val">24×7 Security & Access Control</div>
              </div>
            </div>
            @endif
            @if($property->vastu_compliant)
            <div class="pd-why-item">
              <div class="pd-why-icon" style="background:#fff7ed;color:#ea580c;"><i class="bi bi-compass-fill"></i></div>
              <div>
                <div class="pd-why-label">Vastu Compliant</div>
                <div class="pd-why-val">Designed as per Vastu Shastra</div>
              </div>
            </div>
            @endif
            @if($property->rera_id)
            <div class="pd-why-item">
              <div class="pd-why-icon" style="background:#eff6ff;color:#2563eb;"><i class="bi bi-patch-check-fill"></i></div>
              <div>
                <div class="pd-why-label">RERA Registered</div>
                <div class="pd-why-val">{{ $property->rera_id }} — Legally Secured</div>
              </div>
            </div>
            @endif
            @if($property->possession_status)
            <div class="pd-why-item">
              <div class="pd-why-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-key-fill"></i></div>
              <div>
                <div class="pd-why-label">Possession</div>
                <div class="pd-why-val">{{ $property->possession_status }}@if($property->possession_date) · {{ \Carbon\Carbon::parse($property->possession_date)->format('M Y') }}@endif</div>
              </div>
            </div>
            @endif
          </div>
        </div>
        @endif

        {{-- ===== SIMILAR PROPERTIES ===== --}}
        @if(isset($similarProperties) && $similarProperties->count())
        <div class="pd-card">
          <div class="pd-card-title"><i class="bi bi-grid-3x2-gap-fill"></i> Similar Properties
            @if($property->bhk_type)<small class="text-muted fw-normal ms-1">· {{ $property->bhk_type }} BHK in {{ $property->city }}</small>@endif
          </div>
          <div class="pd-similar-grid">
            @foreach($similarProperties as $similar)
            <a href="{{ route('property-details', $similar) }}" class="pd-similar-card">
              @if($similar->images && $similar->images->count())
                <img src="{{ url('storage/dealer/' . $similar->property_dealer_id . '/' . $similar->id . '/images/' . basename($similar->images->first()->image_path)) }}" alt="{{ $similar->title }}">
              @elseif($similar->cover_image)
                <img src="{{ asset('storage/' . $similar->cover_image) }}" alt="{{ $similar->title }}">
              @else
                <img src="/assets/img/real-estate/property-exterior-4.webp" alt="{{ $similar->title }}">
              @endif
              <div class="similar-body">
                <div class="sim-price">₹{{ number_format($similar->price) }}@if($similar->looking_for == 'Rent')<span style="font-size:.75rem;font-weight:500;color:#64748b">/mo</span>@endif</div>
                <div class="sim-title">{{ Str::limit($similar->title, 45) }}</div>
                <div class="sim-specs">
                  @if($similar->bedrooms)<span><i class="bi bi-house-door"></i> {{ $similar->bedrooms }} Bed</span>@endif
                  @if($similar->bathrooms)<span><i class="bi bi-droplet"></i> {{ $similar->bathrooms }} Bath</span>@endif
                  @if($similar->area)<span><i class="bi bi-arrows-angle-expand"></i> {{ number_format($similar->area) }} sqft</span>@endif
                </div>
              </div>
            </a>
            @endforeach
          </div>
        </div>
        @endif

      </div>{{-- End col-lg-8 --}}

      {{-- ===== RIGHT SIDEBAR ===== --}}
      <div class="col-lg-4">
        <div class="pd-sidebar">

          {{-- Price Card --}}
          <div class="pd-price-card">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
              <div>
                @if($property->looking_for == 'Rent')
                  <div class="pc-price">₹{{ $property->monthly_rent ? number_format($property->monthly_rent) : number_format($property->price) }}<span class="pc-period">/mo</span></div>
                @else
                  <div class="pc-price">₹{{ number_format($property->price) }}</div>
                @endif
                @if($property->price_per_sqft)<div class="pc-sub">₹{{ number_format($property->price_per_sqft) }}/sqft</div>@endif
              </div>
              <span style="background:rgba(255,255,255,.2);border-radius:4px;padding:4px 10px;font-size:.72rem;font-weight:700;">For {{ $property->looking_for }}</span>
            </div>

            <div class="pc-badges">
              @if($property->negotiable)<span class="pc-badge"><i class="bi bi-check-lg me-1"></i>Negotiable</span>@endif
              @if($property->is_featured)<span class="pc-badge"><i class="bi bi-star me-1"></i>Featured</span>@endif
              @if($property->rera_id)<span class="pc-badge"><i class="bi bi-patch-check me-1"></i>RERA</span>@endif
              @if($property->is_premium)<span class="pc-badge"><i class="bi bi-gem me-1"></i>Premium</span>@endif
            </div>

            <div class="pc-cta-row">
              @auth
              <a href="tel:+91{{ config('app.contact_phone','7340753780') }}" class="btn btn-light text-primary">
                <i class="bi bi-telephone-fill me-1"></i>Call
              </a>
              <a href="https://wa.me/91{{ config('app.contact_phone','7340753780') }}?text=Hi, I'm interested in {{ urlencode($property->title) }}" target="_blank" class="btn btn-whatsapp">
                <i class="bi bi-whatsapp me-1"></i>WhatsApp
              </a>
              <button class="btn btn-light text-primary" onclick="document.getElementById('inquiry-form-sidebar').scrollIntoView({behavior:'smooth'})">
                <i class="bi bi-chat-dots-fill me-1"></i>Enquire
              </button>
              @endauth
              @guest
              <a href="{{ route('login') }}" class="btn btn-light text-primary w-100">
                <i class="bi bi-lock-fill me-1"></i> Login to Contact
              </a>
              @endguest
            </div>
          </div>

          @php
            $canViewContact = auth()->check() || (!empty($property->public_contact_enabled));
          @endphp

          {{-- Dealer / Agent Card --}}
          @if($property->dealer)
          <div class="pd-dealer-card">
            <div class="pd-dealer-header">
              @if($property->dealer->profile_image)
                <img src="{{ asset('storage/' . $property->dealer->profile_image) }}" class="pd-dealer-avatar" alt="Dealer">
              @else
                <img src="/assets/img/person/person-f-12.webp" class="pd-dealer-avatar" alt="Dealer">
              @endif
              <div>
                <p class="pd-dealer-name">{{ $property->dealer->name ?? 'Property Dealer' }}</p>
                @if($property->dealer->company_name)<p class="pd-dealer-role">{{ $property->dealer->company_name }}</p>@endif
                <span class="pd-dealer-verified"><i class="bi bi-patch-check-fill"></i> Verified Dealer</span>
              </div>
            </div>

            @if($canViewContact)
            <div class="pd-contact-row">
              <div class="pd-contact-detail">
                <i class="bi bi-telephone-fill"></i>
                <span>+91 {{ config('app.contact_phone','7340753780') }}</span>
              </div>
              <div class="pd-contact-detail">
                <i class="bi bi-envelope-fill"></i>
                <span>{{ config('app.contact_email','admin@indianesthub.com') }}</span>
              </div>
            </div>

            <div class="pd-dealer-btns">
              <a href="tel:+91{{ config('app.contact_phone','7340753780') }}" class="btn btn-success">
                <i class="bi bi-telephone-fill"></i> Call Now
              </a>
              <a href="https://wa.me/91{{ config('app.contact_phone','7340753780') }}?text=Hi, I'm interested in {{ urlencode($property->title) }}" target="_blank" class="btn btn-whatsapp">
                <i class="bi bi-whatsapp"></i> WhatsApp
              </a>
              <button class="btn btn-outline-primary" onclick="document.getElementById('schedule-visit-card').scrollIntoView({behavior:'smooth'})">
                <i class="bi bi-calendar2-check"></i> Request Site Visit
              </button>
            </div>
            @else
            <div class="alert alert-info py-2 mb-0" style="font-size: .85rem;">
              <i class="bi bi-info-circle me-1"></i> Please <a href="{{ route('login') }}" class="fw-bold">Login</a> to view contact details.
            </div>
            @endif

            <div class="pd-trust-row mt-3">
              <span class="pd-trust-badge"><i class="bi bi-shield-check-fill"></i> Safe & Verified</span>
              <span class="pd-trust-badge"><i class="bi bi-clock-fill" style="color:#f59e0b;"></i> Responds Quickly</span>
            </div>
          </div>
          @endif

          {{-- Inquiry Form --}}
          @if($canViewContact)
          <div class="pd-inquiry-card" id="inquiry-form-sidebar">

            <h5><i class="bi bi-envelope-fill me-2 text-primary"></i>Send Your Inquiry</h5>
            <form action="{{ route('property.inquiry.submit') }}" method="POST" id="property-inquiry-form">
              @csrf
              <input type="hidden" name="property_id" value="{{ $property->id }}">
              <div class="mb-3">
                <input type="text" name="name" class="form-control" placeholder="Full Name *" required>
              </div>
              <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email Address *" required>
              </div>
              <div class="mb-3">
                <input type="tel" name="phone" class="form-control" placeholder="Phone Number">
              </div>
              <div class="mb-3">
                <textarea name="message" class="form-control" rows="3" placeholder="I'm interested in this property..." required>Hi, I am interested in this property. Please share more details.</textarea>
              </div>
              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="needs_loan" id="needs_loan_sidebar" value="1">
                <label class="form-check-label small" for="needs_loan_sidebar" style="cursor:pointer;">
                  🏦 I need home loan assistance
                </label>
              </div>
              <button type="submit" class="btn btn-primary w-100" style="border-radius:7px;font-weight:700;padding:11px;">
                <i class="bi bi-send-fill me-2"></i>Send Inquiry
              </button>
            </form>
          </div>
          @else
          <div class="alert alert-info py-2 mb-0" style="font-size: .85rem;">
            <i class="bi bi-info-circle me-1"></i> Please <a href="{{ route('login') }}" class="fw-bold">Login</a> to send an inquiry.
          </div>
          @endif


          {{-- Schedule a Site Visit --}}
          @if($property->property_dealer_id)
          <div class="pd-inquiry-card" id="schedule-visit-card" style="background:linear-gradient(135deg,#f0f9ff 0%,#e0f2fe 100%);border:1.5px solid #bae6fd;">
            <h5><i class="bi bi-calendar2-check-fill me-2" style="color:#0284c7;"></i>Schedule a Site Visit</h5>
            @auth
            <form id="schedule-viewing-form">
              @csrf
              <input type="hidden" name="property_id" value="{{ $property->id }}">
              <input type="hidden" name="dealer_id" value="{{ $property->property_dealer_id }}">
              <div class="row g-2 mb-2">
                <div class="col-6">
                  <label class="form-label small fw-semibold mb-1">Preferred Date *</label>
                  <input type="date" name="date" class="form-control form-control-sm" required
                         min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                </div>
                <div class="col-6">
                  <label class="form-label small fw-semibold mb-1">Preferred Time *</label>
                  <select name="time" class="form-control form-control-sm" required>
                    <option value="">Select</option>
                    <option value="09:00">9:00 AM</option>
                    <option value="10:00">10:00 AM</option>
                    <option value="11:00">11:00 AM</option>
                    <option value="12:00">12:00 PM</option>
                    <option value="14:00">2:00 PM</option>
                    <option value="15:00">3:00 PM</option>
                    <option value="16:00">4:00 PM</option>
                    <option value="17:00">5:00 PM</option>
                  </select>
                </div>
              </div>
              <div class="mb-2">
                <input type="text" name="name" class="form-control form-control-sm" placeholder="Your Name *" required>
              </div>
              <div class="row g-2 mb-2">
                <div class="col-6">
                  <input type="email" name="email" class="form-control form-control-sm" placeholder="Email *" required>
                </div>
                <div class="col-6">
                  <input type="tel" name="phone" class="form-control form-control-sm" placeholder="Phone">
                </div>
              </div>

              {{-- Loan assistance toggle --}}
              <div class="p-2 rounded mb-2" style="background:#fff;border:1px dashed #0284c7;">
                <div class="d-flex align-items-center justify-content-between">
                  <label class="small fw-semibold mb-0" style="color:#0369a1;">
                    🏦 Need home loan assistance?
                  </label>
                  <div class="d-flex gap-2">
                    <button type="button" id="loanHelpYes"
                            onclick="toggleScheduleLoan(true)"
                            class="btn btn-sm px-3 py-1" style="font-size:.75rem;border-radius:20px;background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;">Yes</button>
                    <button type="button" id="loanHelpNo"
                            onclick="toggleScheduleLoan(false)"
                            class="btn btn-sm px-3 py-1" style="font-size:.75rem;border-radius:20px;background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0;">No</button>
                  </div>
                </div>
                <div id="scheduleLoanInfo" style="display:none;" class="mt-2">
                  <p class="mb-1 small" style="color:#0369a1;">✅ A loan expert will contact you after confirming your visit.</p>
                  <input type="hidden" name="needs_loan" id="schedule_needs_loan" value="0">
                </div>
              </div>

              <button type="submit" id="scheduleVisitBtn" class="btn w-100 fw-semibold"
                      style="background:#0284c7;color:#fff;border-radius:7px;padding:10px;">
                <i class="bi bi-calendar-check me-2"></i>Confirm Site Visit
              </button>
              <div id="scheduleVisitSuccess" style="display:none;" class="py-2">
                <div class="text-center mb-2">
                  <i class="bi bi-check-circle-fill text-success d-block mb-1" style="font-size:1.8rem;"></i>
                  <strong class="small">Visit confirmed! The dealer will call you to confirm.</strong>
                </div>
                <div class="mt-2 p-2 rounded" style="background:#f0fdf4;border:1px dashed #16a34a;">
                  <div class="small fw-semibold text-success mb-1">🛡️ Protect your new home?</div>
                  <div class="small text-muted mb-2">Get home insurance from ₹2,000/year before possession.</div>
                  <button type="button" class="btn btn-sm w-100 fw-semibold"
                          style="background:#16a34a;color:#fff;border-radius:6px;"
                          onclick="openInsuranceModal({{ $property->id }}, null, 'post-visit')">
                    Get Free Quote <i class="bi bi-arrow-right ms-1"></i>
                  </button>
                </div>
              </div>
            </form>
            @else
            <div class="text-center py-2">
              <p class="small text-muted mb-2">Login to schedule a visit with the dealer.</p>
              <a href="{{ route('login') }}" class="btn btn-sm btn-primary">Login Now</a>
            </div>
            @endauth
          </div>
          @endif

          {{-- EMI Calculator --}}
          @if($property->looking_for != 'Rent')
          @php
            $price = $property->price ?? 0;
            $downPayment = round($price * 0.20);
            $loanAmount = $price - $downPayment;
            $rate = 8.5 / 12 / 100;
            $tenure = 240; // 20 years
            $emi = $rate > 0 ? round($loanAmount * $rate * pow(1+$rate, $tenure) / (pow(1+$rate, $tenure) - 1)) : 0;
          @endphp
          <div class="pd-emi-card">
            <h5><i class="bi bi-calculator-fill me-2 text-primary"></i>EMI Calculator</h5>
            <div class="emi-row">
              <span class="emi-label">Property Price</span>
              <span class="emi-val">₹{{ number_format($price) }}</span>
            </div>
            <div class="emi-row">
              <span class="emi-label">Down Payment (20%)</span>
              <span class="emi-val">₹{{ number_format($downPayment) }}</span>
            </div>
            <div class="emi-row">
              <span class="emi-label">Loan Amount</span>
              <span class="emi-val">₹{{ number_format($loanAmount) }}</span>
            </div>
            <div class="emi-row">
              <span class="emi-label">Interest Rate</span>
              <span class="emi-val">8.5% p.a.</span>
            </div>
            <div class="emi-row">
              <span class="emi-label">Loan Tenure</span>
              <span class="emi-val">20 Years</span>
            </div>
            <div class="emi-total">
              <span class="emi-label">Est. Monthly EMI</span>
              <span class="emi-val">₹{{ number_format($emi) }}</span>
            </div>
            <p class="text-muted mt-2 mb-0" style="font-size:.72rem;">*Estimates are indicative. Actual EMI may vary based on lender.</p>
            <button type="button" class="btn btn-success w-100 mt-3 fw-semibold"
                    onclick="openLoanModal({{ $property->id }}, null, 'property-page')">
              <i class="bi bi-bank me-2"></i> Apply for Home Loan →
            </button>
          </div>
          @endif

          {{-- 🛡️ Insurance CTA card (only for sale properties) --}}
          @if($property->looking_for != 'Rent')
          <div class="pd-inquiry-card"
               style="background:linear-gradient(135deg,#f0fdf4 0%,#dcfce7 100%);border:1.5px solid #86efac;">
            <div class="d-flex align-items-start gap-3">
              <div style="background:#16a34a;width:42px;height:42px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-shield-check-fill text-white" style="font-size:1.1rem;"></i>
              </div>
              <div>
                <h6 class="fw-bold mb-1" style="color:#15803d;">Protect Your Home from Day 1 🛡️</h6>
                <p class="text-muted small mb-2">
                  Home insurance from <strong>₹{{ number_format(max(2000, (int)(($property->price ?? 5000000) * 0.0007))) }}/year</strong>.
                  Compare 10+ insurers — HDFC ERGO, Bajaj, Tata AIG &amp; more.
                </p>
                <button type="button" class="btn btn-sm fw-semibold w-100"
                        style="background:#16a34a;color:#fff;border-radius:20px;"
                        onclick="openInsuranceModal({{ $property->id }}, null, 'property-page')">
                  <i class="bi bi-shield-check me-1"></i> Get Free Insurance Quote
                </button>
              </div>
            </div>
          </div>
          @endif

          {{-- Nearby Places --}}
          @if($property->nearby_schools || $property->nearby_hospitals || $property->nearby_malls || $property->nearby_metro || $property->nearby_bus_stand)
          <div class="pd-dealer-card">
            <div class="pd-card-title" style="border-bottom:none;padding-bottom:0;margin-bottom:14px;"><i class="bi bi-geo-alt-fill"></i> Nearby Places</div>
            <div class="pd-nearby-list">
              @if($property->nearby_schools)
              <div class="pd-nearby-item">
                <div class="nearby-icon" style="background:#eff6ff;color:#1d4ed8;"><i class="bi bi-mortarboard"></i></div>
                <div>
                  <div class="nearby-label">Schools</div>
                  <div class="nearby-value">{{ $property->nearby_schools }}</div>
                </div>
              </div>
              @endif
              @if($property->nearby_hospitals)
              <div class="pd-nearby-item">
                <div class="nearby-icon" style="background:#fef2f2;color:#dc2626;"><i class="bi bi-hospital"></i></div>
                <div>
                  <div class="nearby-label">Hospitals</div>
                  <div class="nearby-value">{{ $property->nearby_hospitals }}</div>
                </div>
              </div>
              @endif
              @if($property->nearby_malls)
              <div class="pd-nearby-item">
                <div class="nearby-icon" style="background:#fffbeb;color:#d97706;"><i class="bi bi-shop"></i></div>
                <div>
                  <div class="nearby-label">Malls</div>
                  <div class="nearby-value">{{ $property->nearby_malls }}</div>
                </div>
              </div>
              @endif
              @if($property->nearby_metro)
              <div class="pd-nearby-item">
                <div class="nearby-icon" style="background:#ecfdf5;color:#059669;"><i class="bi bi-train-lightrail-front"></i></div>
                <div>
                  <div class="nearby-label">Metro Station</div>
                  <div class="nearby-value">{{ $property->nearby_metro }}</div>
                </div>
              </div>
              @endif
              @if($property->nearby_bus_stand)
              <div class="pd-nearby-item">
                <div class="nearby-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-bus-front"></i></div>
                <div>
                  <div class="nearby-label">Bus Stand</div>
                  <div class="nearby-value">{{ $property->nearby_bus_stand }}</div>
                </div>
              </div>
              @endif
              @if(is_array($property->distance_metrics ?? null))
                @foreach($property->distance_metrics as $place => $distance)
                <div class="pd-nearby-item">
                  <div class="nearby-icon" style="background:#f8fafc;color:#64748b;"><i class="bi bi-geo-alt"></i></div>
                  <div>
                    <div class="nearby-label">{{ ucfirst($place) }}</div>
                    <div class="nearby-value">{{ $distance }}</div>
                  </div>
                </div>
                @endforeach
              @endif
            </div>
          </div>
          @endif

        </div>
      </div>{{-- End col-lg-4 sidebar --}}

    </div>{{-- End row --}}
  </div>{{-- End container --}}

  {{-- ════════════════════════════════════════════
       INTERNAL LINKING STRIP — Explore More
  ════════════════════════════════════════════ --}}
  <div style="background:#eef5fb; border-top:1px solid #bee3f8; padding:24px 0;">
    <div class="container">
      <p class="fw-700 mb-2" style="color:#0a2d5e;font-size:.9rem;">
        <i class="bi bi-arrow-right-circle me-2" style="color:#0078d4;"></i>Explore More Properties
      </p>
      <div class="d-flex flex-wrap gap-2">
        @if($property->city)
          <a href="{{ route('properties.location', strtolower(str_replace(' ','-',$property->city))) }}"
             class="btn btn-sm btn-outline-primary">
            All Properties in {{ $property->city }}
          </a>
          @php
            $citySlug = strtolower(str_replace(' ', '-', $property->city));
            $seoLandingCities = ['zirakpur','mohali','chandigarh','panchkula','kharar','derabassi','mullanpur','patiala','ambala'];
          @endphp
          @if(in_array($citySlug, $seoLandingCities))
            <a href="{{ url('/flats-in-'.$citySlug) }}" class="btn btn-sm btn-outline-primary">Flats in {{ $property->city }}</a>
            <a href="{{ url('/new-projects-in-'.$citySlug) }}" class="btn btn-sm btn-outline-primary">New Projects in {{ $property->city }}</a>
            @if($property->bhk_type)
              @php $bhkNum = (int) $property->bhk_type; @endphp
              @if($bhkNum >= 1 && $bhkNum <= 5)
                <a href="{{ url('/'.$bhkNum.'bhk-flats-in-'.$citySlug) }}" class="btn btn-sm btn-outline-primary">
                  {{ $property->bhk_type }} Flats in {{ $property->city }}
                </a>
              @endif
            @endif
          @endif
        @endif
        @if($property->looking_for === 'Rent' || $property->looking_for === 'rent')
          @php $rCity = strtolower(str_replace(' ','-',$property->city ?? '')); @endphp
          @if(in_array($rCity, ['zirakpur','mohali','chandigarh','panchkula','kharar']))
            <a href="{{ url('/rent-flats-in-'.$rCity) }}" class="btn btn-sm btn-outline-primary">Rent Flats in {{ $property->city }}</a>
          @endif
        @endif
        <a href="{{ route('properties') }}" class="btn btn-sm btn-primary">All Properties</a>
      </div>
    </div>
  </div>
  {{-- ════════════════════════════════════════════ --}}

</main>

{{-- ===== LOAN ELIGIBILITY MODAL ===== --}}
@include('frontend.partials.loan-eligibility-modal', [
    'property_id'         => $property->id,
    'builder_project_id'  => null,
    'source'              => 'property-page',
    'source_page'         => request()->path(),
    'prefill_loan_amount' => isset($loanAmount) ? $loanAmount : null,
])

{{-- ===== INSURANCE MODAL ===== --}}
@include('frontend.partials.insurance-modal', [
    'property_id'        => $property->id,
    'builder_project_id' => null,
    'source'             => 'property-page',
    'source_page'        => request()->path(),
    'prefill_value'      => $property->price ?? null,
    'prefill_city'       => $property->city  ?? null,
    'prefill_type'       => $property->property_type ?? null,
])

{{-- ===== FULL GALLERY MODAL ===== --}}
<div class="modal fade pd-photo-modal" id="galleryModal" tabindex="-1" aria-label="Photo Gallery" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:940px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-images me-2"></i>{{ $property->title }} — All Photos ({{ $totalPhotos }})
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-2">
        <div class="property-gallery-slider swiper init-swiper">
          <script type="application/json" class="swiper-config">
            {"loop":true,"speed":500,"navigation":{"nextEl":".swiper-button-next","prevEl":".swiper-button-prev"},"thumbs":{"swiper":".gallery-modal-thumbs"}}
          </script>
          <div class="swiper-wrapper">
            @foreach($images as $imgUrl)
            <div class="swiper-slide text-center">
              <img src="{{ $imgUrl }}" class="hero-image" alt="Property Photo" style="height:520px;width:100%;object-fit:contain;">
            </div>
            @endforeach
          </div>
          <div class="swiper-button-next" style="color:#fff;"></div>
          <div class="swiper-button-prev" style="color:#fff;"></div>
        </div>
        <div class="gallery-modal-thumbs swiper init-swiper mt-2">
          <script type="application/json" class="swiper-config">
            {"spaceBetween":6,"slidesPerView":8,"freeMode":true,"watchSlidesProgress":true}
          </script>
          <div class="swiper-wrapper">
            @foreach($images as $imgUrl)
            <div class="swiper-slide">
              <img src="{{ $imgUrl }}" class="thumbnail-img" alt="thumb" style="height:60px;width:100%;object-fit:cover;border-radius:3px;opacity:.65;cursor:pointer;">
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// ─── Visitor interaction tracking (call / WhatsApp clicks) ──────────────────
// Uses event delegation so it works for every tel:/wa.me link on this page
// without needing to edit each button individually. Fire-and-forget beacon
// that never blocks the actual call/chat navigation.
document.addEventListener('click', function (e) {
  const link = e.target.closest('a[href^="tel:"], a[href*="wa.me"]');
  if (!link) return;

  const eventType = link.href.indexOf('wa.me') !== -1 ? 'whatsapp_click' : 'call_click';
  const payload = JSON.stringify({
    entity_type: 'property',
    entity_id: {{ $property->id }},
    event_type: eventType,
    _token: '{{ csrf_token() }}'
  });

  try {
    if (navigator.sendBeacon) {
      const blob = new Blob([payload], { type: 'application/json' });
      navigator.sendBeacon('{{ route('track.interaction') }}', blob);
    } else {
      fetch('{{ route('track.interaction') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: payload,
        keepalive: true
      });
    }
  } catch (err) { /* tracking must never break the click */ }
}, true);
</script>

<script>
function toggleDesc() {
  const el = document.getElementById('descClamp');
  const btn = document.getElementById('descToggle');
  if (el.style.maxHeight === 'none') {
    el.style.maxHeight = '120px';
    btn.innerHTML = 'Read More <i class="bi bi-chevron-down"></i>';
  } else {
    el.style.maxHeight = 'none';
    btn.innerHTML = 'Show Less <i class="bi bi-chevron-up"></i>';
  }
}

// ─── Schedule Viewing Form ────────────────────────────────────────────────────
function toggleScheduleLoan(yes) {
  const info = document.getElementById('scheduleLoanInfo');
  const input = document.getElementById('schedule_needs_loan');
  const yesBtn = document.getElementById('loanHelpYes');
  const noBtn  = document.getElementById('loanHelpNo');
  if (!info) return;
  if (yes) {
    info.style.display = 'block';
    input.value = '1';
    yesBtn.style.background = '#0284c7';
    yesBtn.style.color = '#fff';
    noBtn.style.background = '#f1f5f9';
    noBtn.style.color = '#64748b';
  } else {
    info.style.display = 'none';
    input.value = '0';
    noBtn.style.background = '#94a3b8';
    noBtn.style.color = '#fff';
    yesBtn.style.background = '#e0f2fe';
    yesBtn.style.color = '#0369a1';
  }
}

const scheduleForm = document.getElementById('schedule-viewing-form');
if (scheduleForm) {
  scheduleForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('scheduleVisitBtn');
    const successDiv = document.getElementById('scheduleVisitSuccess');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Confirming...';

    fetch('{{ route("property.schedule.viewing") }}', {
      method: 'POST',
      body: new FormData(this),
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        // If needs_loan, also open loan modal to capture more details
        const needsLoan = document.getElementById('schedule_needs_loan');
        if (needsLoan && needsLoan.value === '1') {
          openLoanModal({{ $property->id }}, null, 'schedule-form');
        }
        scheduleForm.style.display = 'none';
        successDiv.style.display = 'block';
      } else {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-calendar-check me-2"></i>Confirm Site Visit';
      }
    })
    .catch(() => {
      btn.disabled = false;
      btn.innerHTML = '<i class="bi bi-calendar-check me-2"></i>Confirm Site Visit';
    });
  });
}

// Inquiry form AJAX submission
document.getElementById('property-inquiry-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const form = this;
  const btn = form.querySelector('button[type="submit"]');
  const origText = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
  fetch(form.action, {
    method: 'POST',
    body: new FormData(form),
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(r => r.json())
  .then(data => {
    btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Inquiry Sent!';
    btn.classList.remove('btn-primary');
    btn.classList.add('btn-success');
    form.reset();
    setTimeout(() => {
      btn.disabled = false;
      btn.innerHTML = origText;
      btn.classList.remove('btn-success');
      btn.classList.add('btn-primary');
    }, 4000);
  })
  .catch(() => {
    btn.disabled = false;
    btn.innerHTML = origText;
    alert('Something went wrong. Please try again.');
  });
});

// ── Sticky bottom urgency bar (desktop) ───────────────────────────────────
(function() {
  const bar = document.getElementById('pd-sticky-bar');
  if (!bar) return;
  window.addEventListener('scroll', function() {
    const scrolled = window.scrollY > 400;
    bar.style.transform = scrolled ? 'translateY(0)' : 'translateY(110%)';
  }, { passive: true });
})();

// ── Exit-intent popup ──────────────────────────────────────────────────────
(function() {
  let shown = false;
  const popup = document.getElementById('pd-exit-popup');
  if (!popup) return;
  document.addEventListener('mouseleave', function(e) {
    if (!shown && e.clientY < 10) {
      shown = true;
      popup.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }
  });
  document.getElementById('pd-exit-popup-close')?.addEventListener('click', function() {
    popup.style.display = 'none';
    document.body.style.overflow = '';
  });
  popup.addEventListener('click', function(e) {
    if (e.target === popup) { popup.style.display = 'none'; document.body.style.overflow = ''; }
  });
})();
</script>

{{-- ═══════════════════════════════════════════════════════════════════
     STICKY BOTTOM BAR (desktop, appears after scrolling 400px)
════════════════════════════════════════════════════════════════════ --}}

<div id="pd-sticky-bar">
  <div class="container">
    <div class="sb-inner">
      <div>
        <div class="sb-title">{{ Str::limit($property->title, 50) }}</div>
          <div class="sb-meta">
              <i class="bi bi-geo-alt me-1"></i>

              {{ collect([
                  $property->city,
                  $property->bhk_type ? $property->bhk_type . ' BHK' : null,
                  $property->area ? number_format($property->area) . ' sq.ft' : null,
              ])->filter()->implode(' · ') }}
          </div>
      </div>
      <div class="d-flex align-items-center gap-3">
        <div>
          <div class="sb-price">₹{{ number_format($property->price) }}</div>
          @if(isset($inquiriesThisWeek) && $inquiriesThisWeek > 0)
          <div class="sb-urgency">
            <i class="bi bi-fire"></i> {{ $inquiriesThisWeek }} {{ Str::plural('enquiry', $inquiriesThisWeek) }} this week
          </div>
          @endif
        </div>
        <div class="sb-actions">
          <a href="tel:+91{{ config('app.contact_phone','7340753780') }}" class="btn btn-success" style="border-radius:8px;font-weight:700;">
            <i class="bi bi-telephone-fill me-1"></i>Call Now
          </a>
          <button class="btn btn-primary" style="border-radius:8px;font-weight:700;"
                  onclick="document.getElementById('schedule-visit-card').scrollIntoView({behavior:'smooth'})">
            <i class="bi bi-calendar-check me-1"></i>Request Site Visit
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Mobile floating CTA --}}
<div id="pd-mobile-cta">
  <a href="tel:+91{{ config('app.contact_phone','7340753780') }}" class="btn btn-success">
    <i class="bi bi-telephone-fill me-1"></i>Call
  </a>
  <a href="https://wa.me/91{{ config('app.contact_phone','7340753780') }}?text=Hi, I'm interested in {{ urlencode($property->title) }}"
     target="_blank" class="btn btn-whatsapp" style="background:#25d366;color:#fff;">
    <i class="bi bi-whatsapp me-1"></i>WhatsApp
  </a>
  <button class="btn btn-primary"
          onclick="document.getElementById('inquiry-form-sidebar').scrollIntoView({behavior:'smooth'})">
    <i class="bi bi-send me-1"></i>Enquire
  </button>
</div>

{{-- Exit-Intent Popup --}}
<div id="pd-exit-popup">
  <div class="pd-exit-card">
    <span id="pd-exit-popup-close">&times;</span>
    <div class="pd-exit-icon">🏠</div>
    <div class="pd-exit-title">Wait! Before you go…</div>
    <div class="pd-exit-sub">
      Get a <strong>free callback</strong> from our property expert on
      <strong>{{ $property->title }}</strong>.
      No obligation. No brokerage.
    </div>
    <a href="tel:+91{{ config('app.contact_phone','7340753780') }}" class="btn btn-success w-100 mb-2" style="border-radius:8px;font-weight:700;padding:13px;">
      <i class="bi bi-telephone-fill me-2"></i>Call Now — +91 {{ config('app.contact_phone','7340753780') }}
    </a>
    <button class="btn btn-primary w-100" style="border-radius:8px;font-weight:700;padding:12px;"
            onclick="document.getElementById('pd-exit-popup').style.display='none';document.body.style.overflow='';document.getElementById('inquiry-form-sidebar').scrollIntoView({behavior:'smooth'})">
      <i class="bi bi-send me-2"></i>Send Quick Enquiry
    </button>
    <div class="mt-3" style="font-size:.72rem;color:#94a3b8;">
      <i class="bi bi-shield-check me-1 text-success"></i>100% free · No brokerage · Safe & secure
    </div>
  </div>
</div>
