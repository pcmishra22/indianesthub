  <section id="featured-services" class="featured-services section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Featured Services</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-4">

          <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
            <div class="service-card">
              <div class="service-icon">
                <i class="bi bi-search"></i>
              </div>
              <div class="service-info">
                <h3><a href="service-details.html">Property Search</a></h3>
                <p>Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim id est laborum</p>
                <ul class="service-highlights">
                  <li><i class="bi bi-check-circle-fill"></i> Comprehensive Listings</li>
                  <li><i class="bi bi-check-circle-fill"></i> Advanced Filtering</li>
                  <li><i class="bi bi-check-circle-fill"></i> Virtual Tours</li>
                </ul>
                <a href="service-details.html" class="service-link">
                  <span>Explore Now</span>
                  <i class="bi bi-arrow-up-right"></i>
                </a>
              </div>
              <div class="service-visual">
                <img src="{{ asset('frontend/img/real-estate/property-interior-2.webp') }}" class="img-fluid" alt="Property Search" loading="lazy">
              </div>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
            <div class="service-card">
              <div class="service-icon">
                <i class="bi bi-calculator"></i>
              </div>
              <div class="service-info">
                <h3><a href="{{ route('price-estimator') }}">Property Valuation</a></h3>
                <p>Get an instant AI-powered price estimate based on real comparable listings in your area — free, no waiting.</p>
                <ul class="service-highlights">
                  <li><i class="bi bi-check-circle-fill"></i> Market Analysis</li>
                  <li><i class="bi bi-check-circle-fill"></i> Comparative Reports</li>
                  <li><i class="bi bi-check-circle-fill"></i> Investment Insights</li>
                </ul>
                <a href="{{ route('price-estimator') }}" class="service-link">
                  <span>Get Valuation</span>
                  <i class="bi bi-arrow-up-right"></i>
                </a>
              </div>
              <div class="service-visual">
                <img src="{{ asset('frontend/img/real-estate/property-exterior-1.webp') }}" class="img-fluid" alt="Property Valuation" loading="lazy">
              </div>
            </div>
          </div><!-- End Service Item -->

        </div>

        <div class="row g-4 mt-4">

          <div class="col-lg-6" data-aos="fade-right" data-aos-delay="400">
            <div class="service-card">
              <div class="service-icon">
                <i class="bi bi-key"></i>
              </div>
              <div class="service-info">
                <h3><a href="service-details.html">Property Rental</a></h3>
                <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque</p>
                <ul class="service-highlights">
                  <li><i class="bi bi-check-circle-fill"></i> Tenant Matching</li>
                  <li><i class="bi bi-check-circle-fill"></i> Lease Management</li>
                  <li><i class="bi bi-check-circle-fill"></i> Property Maintenance</li>
                </ul>
                <a href="service-details.html" class="service-link">
                  <span>Start Renting</span>
                  <i class="bi bi-arrow-up-right"></i>
                </a>
              </div>
              <div class="service-visual">
                <img src="{{ asset('frontend/img/real-estate/property-interior-8.webp') }}" class="img-fluid" alt="Property Rental" loading="lazy">
              </div>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-6" data-aos="fade-left" data-aos-delay="500">
            <div class="service-card">
              <div class="service-icon">
                <i class="bi bi-shield-check"></i>
              </div>
              <div class="service-info">
                <h3><a href="{{ route('investment-advisor') }}">Investment Advisory</a></h3>
                <p>See what your budget gets you and estimated rental yields in any area — instantly, based on real live listings.</p>
                <ul class="service-highlights">
                  <li><i class="bi bi-check-circle-fill"></i> Budget-Based Matching</li>
                  <li><i class="bi bi-check-circle-fill"></i> Rental Yield Estimates</li>
                  <li><i class="bi bi-check-circle-fill"></i> Real Market Data</li>
                </ul>
                <a href="{{ route('investment-advisor') }}" class="service-link">
                  <span>Get Insight</span>
                  <i class="bi bi-arrow-up-right"></i>
                </a>
              </div>
              <div class="service-visual">
                <img src="{{ asset('frontend/img/real-estate/property-exterior-4.webp') }}" class="img-fluid" alt="Investment Advisory" loading="lazy">
              </div>
            </div>
          </div><!-- End Service Item -->

        </div>

        <div class="text-center" data-aos="zoom-in" data-aos-delay="600">
          <a href="services.html" class="btn-view-all">
            <span>View All Services</span>
            <i class="bi bi-arrow-right"></i>
          </a>
        </div>

      </div>

    </section><!-- /Featured Services Section -->