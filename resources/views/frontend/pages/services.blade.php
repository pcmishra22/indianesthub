@extends('frontend.layout')
@section('title', 'Services')
@section('content')
<div class="page-title light-background">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <h1 class="mb-2 mb-lg-0">Services</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="{{ url('/') }}">Home</a></li>
        <li class="current">Services</li>
      </ol>
    </nav>
  </div>
</div>

<section id="services" class="services section">
  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <div class="row gy-5">
      <div class="col-xl-6" data-aos="fade-right" data-aos-delay="200">
        <div class="service-block">
          <div class="service-content">
            <div class="service-number">01</div>
            <div class="service-info">
              <h4>Buy Your Dream Home</h4>
              <p>Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Nulla quis lorem ut libero malesuada feugiat. Proin eget tortor risus.</p>
              <ul class="service-features">
                <li><i class="bi bi-check-circle"></i> Personalized Property Search</li>
                <li><i class="bi bi-check-circle"></i> Neighborhood Analysis</li>
                <li><i class="bi bi-check-circle"></i> Closing Assistance</li>
              </ul>
              <a href="#" class="service-btn">
                Start Your Search <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>
          <div class="service-image">
            <img src="{{ asset('frontend/img/real-estate/property-exterior-3.webp') }}" alt="Buy Dream Home" class="img-fluid">
            <div class="image-overlay">
              <i class="bi bi-house-heart"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-6" data-aos="fade-left" data-aos-delay="300">
        <div class="service-block">
          <div class="service-content">
            <div class="service-number">02</div>
            <div class="service-info">
              <h4>Sell at Maximum Value</h4>
              <p>Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Pellentesque habitant morbi tristique senectus et netus.</p>
              <ul class="service-features">
                <li><i class="bi bi-check-circle"></i> Professional Photography</li>
                <li><i class="bi bi-check-circle"></i> Strategic Marketing Plan</li>
                <li><i class="bi bi-check-circle"></i> Expert Negotiation</li>
              </ul>
              <a href="{{ route('price-estimator') }}" class="service-btn">
                Get Valuation <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>
          <div class="service-image">
            <img src="{{ asset('frontend/img/real-estate/property-exterior-7.webp') }}" alt="Sell Property" class="img-fluid">
            <div class="image-overlay">
              <i class="bi bi-currency-dollar"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-6" data-aos="fade-right" data-aos-delay="400">
        <div class="service-block">
          <div class="service-content">
            <div class="service-number">03</div>
            <div class="service-info">
              <h4>Premium Rentals</h4>
              <p>Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Donec sollicitudin molestie malesuada. Quisque velit nisi.</p>
              <ul class="service-features">
                <li><i class="bi bi-check-circle"></i> Tenant Screening</li>
                <li><i class="bi bi-check-circle"></i> Flexible Lease Terms</li>
                <li><i class="bi bi-check-circle"></i> 24/7 Support</li>
              </ul>
              <a href="#" class="service-btn">
                Browse Rentals <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>
          <div class="service-image">
            <img src="{{ asset('frontend/img/real-estate/property-interior-5.webp') }}" alt="Premium Rentals" class="img-fluid">
            <div class="image-overlay">
              <i class="bi bi-key"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-6" data-aos="fade-left" data-aos-delay="500">
        <div class="service-block">
          <div class="service-content">
            <div class="service-number">04</div>
            <div class="service-info">
              <h4>Investment Consulting</h4>
              <p>Sed porttitor lectus nibh. Cras ultricies ligula sed magna dictum porta. Vivamus magna justo, lacinia eget consectetur sed.</p>
              <ul class="service-features">
                <li><i class="bi bi-check-circle"></i> Market Analysis</li>
                <li><i class="bi bi-check-circle"></i> ROI Projections</li>
                <li><i class="bi bi-check-circle"></i> Portfolio Diversification</li>
              </ul>
              <a href="#" class="service-btn">
                Schedule Consultation <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>
          <div class="service-image">
            <img src="{{ asset('frontend/img/real-estate/features-2.webp') }}" alt="Investment Consulting" class="img-fluid">
            <div class="image-overlay">
              <i class="bi bi-graph-up-arrow"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12" data-aos="fade-up" data-aos-delay="600">
        <div class="cta-section">
          <div class="cta-content">
            <h3>Ready to Make Your Move?</h3>
            <p>Connect with our experienced team today and discover how we can help you achieve your real estate goals.</p>
            <div class="cta-buttons">
              <a href="{{ url('/contact') }}" class="btn-primary">Contact Us Today</a>
              <a href="tel:+1234567890" class="btn-secondary">
                <i class="bi bi-telephone"></i> Call Now
              </a>
            </div>
          </div>
          <div class="cta-stats">
            <div class="stat-item">
              <div class="stat-number">500+</div>
              <div class="stat-label">Happy Clients</div>
            </div>
            <div class="stat-item">
              <div class="stat-number">15+</div>
              <div class="stat-label">Years Experience</div>
            </div>
            <div class="stat-item">
              <div class="stat-number">98%</div>
              <div class="stat-label">Success Rate</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection