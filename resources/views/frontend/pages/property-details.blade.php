@extends('frontend.layout')
@section('title', $property->title ?? 'Property Details')
@section('content')
  <main class="main">

    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">{{ $property->title ?? 'Property Details' }}</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">Property Details</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Property Details Section -->
    <section id="property-details" class="property-details section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row">
          <div class="col-lg-7">

            <!-- Property Hero Section -->
            <div class="property-hero mb-5" data-aos="fade-up" data-aos-delay="200">
              <div class="hero-image-container">
                <div class="property-gallery-slider swiper init-swiper">
                  <script type="application/json" class="swiper-config">
                    {
                      "loop": true,
                      "speed": 600,
                      "autoplay": {
                        "delay": 5000
                      },
                      "navigation": {
                        "nextEl": ".swiper-button-next",
                        "prevEl": ".swiper-button-prev"
                      },
                      "thumbs": {
                        "swiper": ".property-thumbnails-slider"
                      }
                    }
                  </script>
                  <div class="swiper-wrapper">
                    @foreach($property->images ?? [] as $image)
                    <div class="swiper-slide">
                      <img src="{{ asset($image->image_url) }}" class="img-fluid hero-image" alt="Property Image">
                    </div>
                    @endforeach
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/property-interior-7.webp') }}" class="img-fluid hero-image" alt="Interior View">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/property-exterior-9.webp') }}" class="img-fluid hero-image" alt="Exterior View">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/features-5.webp') }}" class="img-fluid hero-image" alt="Features">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/property-interior-8.webp') }}" class="img-fluid hero-image" alt="More Photos">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/property-exterior-3.webp') }}" class="img-fluid hero-image" alt="Exterior Detail">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/property-interior-4.webp') }}" class="img-fluid hero-image" alt="Living Area">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/features-2.webp') }}" class="img-fluid hero-image" alt="Kitchen">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/property-interior-5.webp') }}" class="img-fluid hero-image" alt="Bedroom">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/property-exterior-1.webp') }}" class="img-fluid hero-image" alt="Building View">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/features-3.webp') }}" class="img-fluid hero-image" alt="Bathroom">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/property-interior-2.webp') }}" class="img-fluid hero-image" alt="Dining Area">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/property-exterior-8.webp') }}" class="img-fluid hero-image" alt="Balcony View">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/features-1.webp') }}" class="img-fluid hero-image" alt="Amenities">
                    </div>
                  </div>
                  <div class="swiper-button-next"></div>
                  <div class="swiper-button-prev"></div>
                </div>
              </div>

              <div class="thumbnail-gallery mt-3">
                <div class="property-thumbnails-slider swiper init-swiper">
                  <script type="application/json" class="swiper-config">
                    {
                      "loop": true,
                      "spaceBetween": 10,
                      "slidesPerView": 4,
                      "freeMode": true,
                      "watchSlidesProgress": true,
                      "breakpoints": {
                        "576": {
                          "slidesPerView": 5
                        },
                        "768": {
                          "slidesPerView": 6
                        }
                      }
                    }
                  </script>
                  <div class="swiper-wrapper">
                    @foreach($property->images ?? [] as $image)
                    <div class="swiper-slide">
                      <img src="{{ asset($image->image_url) }}" class="img-fluid thumbnail-img" alt="Property Thumbnail">
                    </div>
                    @endforeach
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/property-interior-7.webp') }}" class="img-fluid thumbnail-img" alt="Interior View">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/property-exterior-9.webp') }}" class="img-fluid thumbnail-img" alt="Exterior View">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/features-5.webp') }}" class="img-fluid thumbnail-img" alt="Features">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/property-interior-8.webp') }}" class="img-fluid thumbnail-img" alt="More Photos">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/property-exterior-3.webp') }}" class="img-fluid thumbnail-img" alt="Exterior Detail">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/property-interior-4.webp') }}" class="img-fluid thumbnail-img" alt="Living Area">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/features-2.webp') }}" class="img-fluid thumbnail-img" alt="Kitchen">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/property-interior-5.webp') }}" class="img-fluid thumbnail-img" alt="Bedroom">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/property-exterior-1.webp') }}" class="img-fluid thumbnail-img" alt="Building View">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/features-3.webp') }}" class="img-fluid thumbnail-img" alt="Bathroom">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/property-interior-2.webp') }}" class="img-fluid thumbnail-img" alt="Dining Area">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/property-exterior-8.webp') }}" class="img-fluid thumbnail-img" alt="Balcony View">
                    </div>
                    <div class="swiper-slide">
                      <img src="{{ asset('frontend/img/real-estate/features-1.webp') }}" class="img-fluid thumbnail-img" alt="Amenities">
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Property Hero -->

            <!-- Property Information -->
            <div class="property-info mb-5" data-aos="fade-up" data-aos-delay="300">
              <div class="property-header">
                <h1 class="property-title">{{ $property->title }}</h1>
                <div class="property-meta">
                  <span class="address"><i class="bi bi-geo-alt"></i> {{ $property->address }}, {{ $property->city }}, {{ $property->state }}</span>
                  <span class="listing-id">ID: #{{ $property->id }}</span>
                </div>
              </div>

              <div class="pricing-section">
                <div class="main-price">${{ number_format($property->price) }}@if($property->listing_type === 'rent')<span class="period">/month</span>@endif</div>
                <div class="price-breakdown">
                  @if($property->deposit)
                  <span class="deposit">Security Deposit: ${{ number_format($property->deposit) }}</span>
                  @endif
                  @if($property->possession_date)
                  <span class="available">Available from {{ $property->possession_date }}</span>
                  @endif
                </div>
              </div>

              <div class="quick-stats">
                <div class="stat-grid">
                  <div class="stat-card">
                    <div class="stat-icon">
                      <i class="bi bi-house"></i>
                    </div>
                    <div class="stat-content">
                      <span class="stat-number">{{ $property->bedrooms }}</span>
                      <span class="stat-label">Bedrooms</span>
                    </div>
                  </div>
                  <div class="stat-card">
                    <div class="stat-icon">
                      <i class="bi bi-droplet"></i>
                    </div>
                    <div class="stat-content">
                      <span class="stat-number">{{ $property->bathrooms }}</span>
                      <span class="stat-label">Bathrooms</span>
                    </div>
                  </div>
                  <div class="stat-card">
                    <div class="stat-icon">
                      <i class="bi bi-arrows-angle-expand"></i>
                    </div>
                    <div class="stat-content">
                      <span class="stat-number">{{ $property->area }}</span>
                      <span class="stat-label">Sq Ft</span>
                    </div>
                  </div>
                  <div class="stat-card">
                    <div class="stat-icon">
                      <i class="bi bi-car-front"></i>
                    </div>
                    <div class="stat-content">
                      <span class="stat-number">{{ $property->parking ?? 'N/A' }}</span>
                      <span class="stat-label">Parking</span>
                    </div>
                  </div>
                  <div class="stat-card">
                    <div class="stat-icon">
                      <i class="bi bi-building"></i>
                    </div>
                    <div class="stat-content">
                      <span class="stat-number">{{ $property->floor ?? 'N/A' }}</span>
                      <span class="stat-label">Floor</span>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Property Information -->

            <!-- Description & Features -->
            <div class="property-details mb-5" data-aos="fade-up" data-aos-delay="400">
              <h3>Property Description</h3>
              <p>{{ $property->description }}</p>
              @if($property->long_description)
              {!! $property->long_description !!}
              @endif

              <div class="features-grid mt-4">
                <div class="row">
                  <div class="col-md-6">
                    <h5>Interior Features</h5>
                    <ul class="feature-list">
                      @foreach($property->interior_features ?? [] as $feature)
                      <li><i class="bi bi-check2"></i> {{ $feature }}</li>
                      @endforeach
                    </ul>
                  </div>
                  <div class="col-md-6">
                    <h5>Building Amenities</h5>
                    <ul class="feature-list">
                      @foreach($property->building_amenities ?? [] as $amenity)
                      <li><i class="bi bi-check2"></i> {{ $amenity }}</li>
                      @endforeach
                    </ul>
                  </div>
                </div>
              </div>
            </div><!-- End Description & Features -->

            <!-- Floor Plan -->
            <div class="floor-plan-section mb-5" data-aos="fade-up" data-aos-delay="500">
              <h3>Floor Plan</h3>
              <div class="floor-plan-card">
                <img src="{{ asset('frontend/img/real-estate/property-interior-9.webp') }}" class="img-fluid" alt="Floor Plan">
                <div class="plan-details">
                  <h5>3 Bedroom Penthouse Layout</h5>
                  <p>Open concept living and dining area with private balcony access. Master suite features ensuite bathroom and city views.</p>
                </div>
              </div>
            </div><!-- End Floor Plan -->

          </div>

          <!-- Sidebar -->
          <div class="col-lg-5">
            <div class="sticky-sidebar">

              <!-- Quick Actions -->
              <div class="actions-card mb-4" data-aos="fade-up" data-aos-delay="250">
                <div class="action-buttons">
                  <button class="btn btn-primary btn-lg w-100 mb-3">
                    <i class="bi bi-calendar-check"></i>
                    Schedule Viewing
                  </button>
                  <div class="row g-2">
                    <div class="col-6">
                      <button id="save-property-btn" class="btn btn-outline-primary w-100" data-property-id="{{ $property->id }}">
                        <i class="bi bi-heart" id="save-property-icon"></i>
                        <span id="save-property-text">Save</span>
                      </button>
                    </div>
                    <div class="col-6">
                      <button id="share-property-btn" class="btn btn-outline-primary w-100" type="button">
                        <i class="bi bi-share"></i>
                        Share
                      </button>
                    </div>
                  </div>
                  <div id="save-property-message" class="my-2"></div>
                </div>
              </div><!-- End Quick Actions -->

              <!-- Agent Card -->
              <div class="agent-card mb-4" data-aos="fade-up" data-aos-delay="350">
                <div class="agent-header">
                  <div class="agent-avatar">
                    <img src="{{ asset($property->agent->photo ?? 'frontend/img/person/person-f-12.webp') }}" class="img-fluid" alt="Agent Photo">
                    <div class="online-status"></div>
                  </div>
                  <div class="agent-info">
                    <h4>{{ $property->agent->name ?? 'Agent Name' }}</h4>
                    <p class="agent-role">{{ $property->agent->role ?? 'Licensed Real Estate Agent' }}</p>
                    <div class="agent-rating">
                      <div class="stars">
                        @for($i=0; $i<($property->agent->rating ?? 5); $i++)
                        <i class="bi bi-star-fill"></i>
                        @endfor
                      </div>
                      <span class="rating-text">{{ $property->agent->rating ?? '5.0' }} ({{ $property->agent->reviews ?? '127' }} reviews)</span>
                    </div>
                  </div>
                </div>

                <div class="agent-contact">
                  <div class="contact-item">
                    <i class="bi bi-telephone"></i>
                    <span>{{ $property->agent->phone ?? '+1 (555) 234-5678' }}</span>
                  </div>
                  <div class="contact-item">
                    <i class="bi bi-envelope"></i>
                    <span>{{ $property->agent->email ?? 'agent@example.com' }}</span>
                  </div>
                </div>

                <div class="agent-actions mt-3">
                  <button class="btn btn-success w-100 mb-2">
                    <i class="bi bi-telephone"></i>
                    Call Now
                  </button>
                  <button class="btn btn-outline w-100">
                    <i class="bi bi-chat-dots"></i>
                    Send Message
                  </button>
                </div>
              </div><!-- End Agent Card -->

              <!-- Contact Form -->
              <div class="contact-form-card mb-4" data-aos="fade-up" data-aos-delay="450">
                <h4>Request Information</h4>
                @if(session('success'))
                  <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                  <div class="alert alert-danger">
                    <ul class="mb-0">
                      @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif
                <form action="{{ route('property.inquiry.submit') }}" method="POST" id="inquiry-form">
                  @csrf
                  <input type="hidden" name="property_id" value="{{ $property->id }}">
                  <div class="row">
                    <div class="col-12 mb-3">
                      <input type="text" name="name" class="form-control" placeholder="Full Name" required value="{{ old('name') }}">
                    </div>
                    <div class="col-12 mb-3">
                      <input type="email" name="email" class="form-control" placeholder="Email Address" required value="{{ old('email') }}">
                    </div>
                    <div class="col-12 mb-3">
                      <input type="tel" name="phone" class="form-control" placeholder="Phone Number" value="{{ old('phone') }}">
                    </div>
                    <div class="col-12 mb-3">
                      <select name="subject" class="form-select" required>
                        <option value="">I'm interested in...</option>
                        <option value="Scheduling a viewing" @if(old('subject')=='Scheduling a viewing') selected @endif>Scheduling a viewing</option>
                        <option value="Getting more information" @if(old('subject')=='Getting more information') selected @endif>Getting more information</option>
                        <option value="Submitting an application" @if(old('subject')=='Submitting an application') selected @endif>Submitting an application</option>
                      </select>
                    </div>
                    <div class="col-12 mb-3">
                      <textarea name="message" class="form-control" rows="4" placeholder="Additional questions or preferred viewing times...">{{ old('message') }}</textarea>
                    </div>
                  </div>
                  <div class="ajax-message my-2"></div>
                  <button type="submit" class="btn btn-primary w-100">Send Request</button>
                </form>
              </div><!-- End Contact Form -->

              <!-- Rental Calculator -->
              <div class="calculator-card mb-4" data-aos="fade-up" data-aos-delay="550">
                <h4>Monthly Cost Calculator</h4>
                <div class="calculator-content">
                  <div class="cost-item">
                    <span class="cost-label">Monthly Rent</span>
                    <span class="cost-value">${{ number_format($property->price) }}</span>
                  </div>
                  <div class="cost-item">
                    <span class="cost-label">Utilities (estimated)</span>
                    <span class="cost-value">$180</span>
                  </div>
                  <div class="cost-item">
                    <span class="cost-label">Parking</span>
                    <span class="cost-value">$250</span>
                  </div>
                  <div class="cost-item">
                    <span class="cost-label">Pet Fee</span>
                    <span class="cost-value">$50</span>
                  </div>
                  <div class="total-cost">
                    <span class="total-label">Total Monthly Cost</span>
                    <span class="total-value">${{ number_format(($property->price ?? 0) + 180 + 250 + 50) }}</span>
                  </div>
                </div>
              </div><!-- End Rental Calculator -->

              <!-- Similar Properties -->
              <div class="similar-properties" data-aos="fade-up" data-aos-delay="650">
                <h4>Similar Properties</h4>
                @if(!empty($similarProperties) && count($similarProperties) > 0)
                  @foreach($similarProperties as $similar)
                  <div class="similar-property-item">
                    <img src="{{ asset($similar->images->first()->image_url ?? 'frontend/img/real-estate/property-exterior-4.webp') }}" class="img-fluid" alt="Similar Property">
                    <div class="similar-info">
                      <h6>{{ $similar->title }}</h6>
                      <p class="similar-price">${{ number_format($similar->price) }}@if($similar->listing_type === 'rent')/month @endif</p>
                      <p class="similar-specs">{{ $similar->bedrooms }} bed • {{ $similar->bathrooms }} bath • {{ $similar->area }} sq ft</p>
                    </div>
                  </div>
                  @endforeach
                @else
                  <!-- Static fallback similar properties -->
                  <div class="similar-property-item">
                    <img src="{{ asset('frontend/img/real-estate/property-exterior-4.webp') }}" class="img-fluid" alt="Similar Property">
                    <div class="similar-info">
                      <h6>Luxury Apartment Downtown</h6>
                      <p class="similar-price">$4,200/month</p>
                      <p class="similar-specs">2 bed • 2 bath • 1,650 sq ft</p>
                    </div>
                  </div>
                  <div class="similar-property-item">
                    <img src="{{ asset('frontend/img/real-estate/property-interior-6.webp') }}" class="img-fluid" alt="Similar Property">
                    <div class="similar-info">
                      <h6>Modern Penthouse Suite</h6>
                      <p class="similar-price">$5,100/month</p>
                      <p class="similar-specs">3 bed • 2.5 bath • 1,920 sq ft</p>
                    </div>
                  </div>
                @endif
              </div><!-- End Similar Properties -->

            </div>
          </div><!-- End Sidebar -->

        </div>

        <!-- Location Section -->
        <div class="location-section mt-5" data-aos="fade-up" data-aos-delay="700">
          <h3>Location &amp; Neighborhood</h3>
          <div class="row">
            <div class="col-lg-8">
              <div class="map-wrapper">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3021.5!2d-73.935!3d40.796!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDDCsDQ3JzQ1LjYiTiA3M8KwNTYnMDYuMCJX!5e0!3m2!1sen!2sus!4v1234567890" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="neighborhood-info">
                <h5>Neighborhood Highlights</h5>
                <div class="poi-item">
                  <i class="bi bi-mortarboard"></i>
                  <div class="poi-content">
                    <span class="poi-name">Columbia University</span>
                    <span class="poi-distance">0.4 miles</span>
                  </div>
                </div>
                <div class="poi-item">
                  <i class="bi bi-cup-hot"></i>
                  <div class="poi-content">
                    <span class="poi-name">Local Coffee Shops</span>
                    <span class="poi-distance">2 min walk</span>
                  </div>
                </div>
                <div class="poi-item">
                  <i class="bi bi-tree"></i>
                  <div class="poi-content">
                    <span class="poi-name">Marcus Garvey Park</span>
                    <span class="poi-distance">0.3 miles</span>
                  </div>
                </div>
                <div class="poi-item">
                  <i class="bi bi-train-lightrail-front"></i>
                  <div class="poi-content">
                    <span class="poi-name">125th St Station</span>
                    <span class="poi-distance">0.6 miles</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div><!-- End Location Section -->

      </div>

    </section><!-- /Property Details Section -->

  </main>
  @endsection