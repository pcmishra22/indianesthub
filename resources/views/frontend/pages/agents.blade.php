@extends('frontend.layout')
@section('title', 'Properties')
@section('content')
  <main class="main">

    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Agents</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">Agents</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Agents Section -->
    <section id="agents" class="agents section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="agent-card">
              <div class="agent-image">
                <img src="{{ asset('frontend/img/real-estate/agent-1.webp') }}" alt="Agent" class="img-fluid">
                <div class="badge-overlay">
                  <span class="top-seller-badge">Top Seller</span>
                </div>
              </div>
              <div class="agent-info">
                <h4>Sarah Martinez</h4>
                <span class="role">Senior Property Advisor</span>
                <p class="location"><i class="bi bi-geo-alt"></i>vDowntown Miami</p>
                <div class="specialties">
                  <span class="specialty-tag">Luxury Homes</span>
                  <span class="specialty-tag">Condos</span>
                </div>
                <div class="contact-links">
                  <a href="tel:+15551234567"><i class="bi bi-telephone"></i></a>
                  <a href="mailto:sarah@example.com"><i class="bi bi-envelope"></i></a>
                  <a href="#"><i class="bi bi-whatsapp"></i></a>
                  <a href="#"><i class="bi bi-linkedin"></i></a>
                </div>
                <a href="#" class="view-listings-btn">View Listings</a>
              </div>
            </div>
          </div><!-- End Agent Card -->

          <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="agent-card">
              <div class="agent-image">
                <img src="{{ asset('frontend/img/real-estate/agent-2.webp') }}" alt="Agent" class="img-fluid">
                <div class="badge-overlay">
                  <span class="verified-badge">Verified</span>
                </div>
              </div>
              <div class="agent-info">
                <h4>Michael Thompson</h4>
                <span class="role">Commercial Specialist</span>
                <p class="location"><i class="bi bi-geo-alt"></i>vBrickell Avenue</p>
                <div class="specialties">
                  <span class="specialty-tag">Commercial</span>
                  <span class="specialty-tag">Investment</span>
                </div>
                <div class="contact-links">
                  <a href="tel:+15551234568"><i class="bi bi-telephone"></i></a>
                  <a href="mailto:michael@example.com"><i class="bi bi-envelope"></i></a>
                  <a href="#"><i class="bi bi-whatsapp"></i></a>
                  <a href="#"><i class="bi bi-instagram"></i></a>
                </div>
                <a href="#" class="view-listings-btn">View Listings</a>
              </div>
            </div>
          </div><!-- End Agent Card -->

          <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="agent-card">
              <div class="agent-image">
                <img src="{{ asset('frontend/img/real-estate/agent-3.webp') }}" alt="Agent" class="img-fluid">
                <div class="badge-overlay">
                  <span class="new-agent-badge">New Agent</span>
                </div>
              </div>
              <div class="agent-info">
                <h4>Emma Rodriguez</h4>
                <span class="role">Residential Expert</span>
                <p class="location"><i class="bi bi-geo-alt"></i>vCoral Gables</p>
                <div class="specialties">
                  <span class="specialty-tag">First-Time Buyers</span>
                  <span class="specialty-tag">Families</span>
                </div>
                <div class="contact-links">
                  <a href="tel:+15551234569"><i class="bi bi-telephone"></i></a>
                  <a href="mailto:emma@example.com"><i class="bi bi-envelope"></i></a>
                  <a href="#"><i class="bi bi-whatsapp"></i></a>
                  <a href="#"><i class="bi bi-facebook"></i></a>
                </div>
                <a href="#" class="view-listings-btn">View Listings</a>
              </div>
            </div>
          </div><!-- End Agent Card -->

          <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="agent-card">
              <div class="agent-image">
                <img src="{{ asset('frontend/img/real-estate/agent-4.webp') }}" alt="Agent" class="img-fluid">
                <div class="badge-overlay">
                  <span class="awarded-badge">Award Winner</span>
                </div>
              </div>
              <div class="agent-info">
                <h4>James Wilson</h4>
                <span class="role">Luxury Property Consultant</span>
                <p class="location"><i class="bi bi-geo-alt"></i>vSouth Beach</p>
                <div class="specialties">
                  <span class="specialty-tag">Waterfront</span>
                  <span class="specialty-tag">Penthouses</span>
                </div>
                <div class="contact-links">
                  <a href="tel:+15551234570"><i class="bi bi-telephone"></i></a>
                  <a href="mailto:james@example.com"><i class="bi bi-envelope"></i></a>
                  <a href="#"><i class="bi bi-whatsapp"></i></a>
                  <a href="#"><i class="bi bi-twitter"></i></a>
                </div>
                <a href="#" class="view-listings-btn">View Listings</a>
              </div>
            </div>
          </div><!-- End Agent Card -->

          <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="agent-card">
              <div class="agent-image">
                <img src="{{ asset('frontend/img/real-estate/agent-5.webp') }}" alt="Agent" class="img-fluid">
                <div class="badge-overlay">
                  <span class="top-seller-badge">Top Seller</span>
                </div>
              </div>
              <div class="agent-info">
                <h4>Lisa Chen</h4>
                <span class="role">International Sales Manager</span>
                <p class="location"><i class="bi bi-geo-alt"></i>vDesign District</p>
                <div class="specialties">
                  <span class="specialty-tag">International</span>
                  <span class="specialty-tag">Mandarin</span>
                </div>
                <div class="contact-links">
                  <a href="tel:+15551234571"><i class="bi bi-telephone"></i></a>
                  <a href="mailto:lisa@example.com"><i class="bi bi-envelope"></i></a>
                  <a href="#"><i class="bi bi-whatsapp"></i></a>
                  <a href="#"><i class="bi bi-wechat"></i></a>
                </div>
                <a href="#" class="view-listings-btn">View Listings</a>
              </div>
            </div>
          </div><!-- End Agent Card -->

          <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="agent-card">
              <div class="agent-image">
                <img src="{{ asset('frontend/img/real-estate/agent-6.webp') }}" alt="Agent" class="img-fluid">
                <div class="badge-overlay">
                  <span class="verified-badge">Verified</span>
                </div>
              </div>
              <div class="agent-info">
                <h4>David Garcia</h4>
                <span class="role">Property Investment Advisor</span>
                <p class="location"><i class="bi bi-geo-alt"></i>vAventura</p>
                <div class="specialties">
                  <span class="specialty-tag">Investment</span>
                  <span class="specialty-tag">Spanish</span>
                </div>
                <div class="contact-links">
                  <a href="tel:+15551234572"><i class="bi bi-telephone"></i></a>
                  <a href="mailto:david@example.com"><i class="bi bi-envelope"></i></a>
                  <a href="#"><i class="bi bi-whatsapp"></i></a>
                  <a href="#"><i class="bi bi-linkedin"></i></a>
                </div>
                <a href="#" class="view-listings-btn">View Listings</a>
              </div>
            </div>
          </div><!-- End Agent Card -->

          <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="agent-card">
              <div class="agent-image">
                <img src="{{ asset('frontend/img/real-estate/agent-7.webp') }}" alt="Agent" class="img-fluid">
                <div class="badge-overlay">
                  <span class="awarded-badge">Award Winner</span>
                </div>
              </div>
              <div class="agent-info">
                <h4>Rachel Porter</h4>
                <span class="role">Rental Specialist</span>
                <p class="location"><i class="bi bi-geo-alt"></i>vMidtown Miami</p>
                <div class="specialties">
                  <span class="specialty-tag">Rentals</span>
                  <span class="specialty-tag">Young Professionals</span>
                </div>
                <div class="contact-links">
                  <a href="tel:+15551234573"><i class="bi bi-telephone"></i></a>
                  <a href="mailto:rachel@example.com"><i class="bi bi-envelope"></i></a>
                  <a href="#"><i class="bi bi-whatsapp"></i></a>
                  <a href="#"><i class="bi bi-instagram"></i></a>
                </div>
                <a href="#" class="view-listings-btn">View Listings</a>
              </div>
            </div>
          </div><!-- End Agent Card -->

          <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="agent-card">
              <div class="agent-image">
                <img src="{{ asset('frontend/img/real-estate/agent-8.webp') }}" alt="Agent" class="img-fluid">
                <div class="badge-overlay">
                  <span class="new-agent-badge">New Agent</span>
                </div>
              </div>
              <div class="agent-info">
                <h4>Alexa Johnson</h4>
                <span class="role">New Construction Specialist</span>
                <p class="location"><i class="bi bi-geo-alt"></i>vWynwood</p>
                <div class="specialties">
                  <span class="specialty-tag">New Construction</span>
                  <span class="specialty-tag">Modern Homes</span>
                </div>
                <div class="contact-links">
                  <a href="tel:+15551234574"><i class="bi bi-telephone"></i></a>
                  <a href="mailto:alex@example.com"><i class="bi bi-envelope"></i></a>
                  <a href="#"><i class="bi bi-whatsapp"></i></a>
                  <a href="#"><i class="bi bi-twitter"></i></a>
                </div>
                <a href="#" class="view-listings-btn">View Listings</a>
              </div>
            </div>
          </div><!-- End Agent Card -->

        </div>

        <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="500">
          <a href="#" class="btn-view-all-agents">View All Agents</a>
        </div>

      </div>

    </section><!-- /Agents Section -->

  </main>
@endsection