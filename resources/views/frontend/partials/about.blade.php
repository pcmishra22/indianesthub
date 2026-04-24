<main class="main">
    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">About {{ config('app.name') }}</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="/">Home</a></li>
            <li class="current">About</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row justify-content-center">
          <div class="col-lg-10">
            <div class="hero-content text-center" data-aos="zoom-in" data-aos-delay="200">
              <h2>Chandigarh Tricity's Most Trusted Real Estate Portal</h2>
              <p class="hero-description">{{ config('app.name') }} was built to make property search in Chandigarh, Mohali, Zirakpur, and Panchkula transparent, fast, and stress-free. We connect genuine buyers, sellers, and renters with verified listings — no spam, no fake listings, just real properties from real dealers.</p>
            </div>
            <div class="dual-image-layout" data-aos="fade-up" data-aos-delay="300">
              <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                  <div class="primary-image-wrap">
                    <img src="/assets/img/real-estate/property-exterior-4.webp" alt="Premium Properties in Chandigarh Tricity" class="img-fluid">
                    <div class="floating-badge" data-aos="zoom-in" data-aos-delay="400">
                      <div class="badge-content">
                        <i class="bi bi-award"></i>
                        <span>Verified Listings Only</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="secondary-image-wrap">
                    <img src="/assets/img/real-estate/agent-3.webp" alt="Trusted Real Estate Agents Tricity" class="img-fluid">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Our Story --}}
        <div class="row justify-content-center mt-5" data-aos="fade-up" data-aos-delay="250">
          <div class="col-lg-9">
            <h3 class="text-center mb-4" style="color:#0a2d5e; font-weight:700;">Our Story</h3>
            <p class="text-muted" style="line-height:1.9; font-size:1.02rem;">
              {{ config('app.name') }} started with a vision to transform how people buy and rent property in Chandigarh Tricity. The real estate market here was dominated by word-of-mouth and unverified listings — buyers wasted days visiting wrong properties, and genuine dealers struggled to reach serious buyers.
            </p>
            <p class="text-muted" style="line-height:1.9; font-size:1.02rem;">
              We built {{ config('app.name') }} to fix that. Every listing on our platform is verified by our team before it goes live. Every dealer is registered and vetted. We cover over <strong>25 localities</strong> across Chandigarh, Mohali, Zirakpur, Panchkula, Kharar, Derabassi, and Mullanpur — from affordable plots in Derabassi to luxury villas in Chandigarh.
            </p>
            <p class="text-muted" style="line-height:1.9; font-size:1.02rem;">
              Today, thousands of families across Tricity trust {{ config('app.name') }} to find their dream home. Our mission is simple: <strong>connect the right buyer with the right property at the right price.</strong>
            </p>
          </div>
        </div>

        <div class="features-showcase" data-aos="fade-up" data-aos-delay="350">
          <div class="row gy-4">
            <div class="col-lg-3 col-md-6">
              <div class="feature-box" data-aos="flip-up" data-aos-delay="400">
                <div class="feature-icon">
                  <i class="bi bi-house-door"></i>
                </div>
                <div class="feature-content">
                  <h4>Verified Residential Listings</h4>
                  <p>Browse flats, villas, plots and builder floors across Chandigarh Tricity — every listing is verified before publishing.</p>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6">
              <div class="feature-box" data-aos="flip-up" data-aos-delay="450">
                <div class="feature-icon">
                  <i class="bi bi-building"></i>
                </div>
                <div class="feature-content">
                  <h4>New Builder Projects</h4>
                  <p>Discover upcoming and under-construction projects by top builders in Mohali, Zirakpur and Mullanpur with RERA details.</p>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6">
              <div class="feature-box" data-aos="flip-up" data-aos-delay="500">
                <div class="feature-icon">
                  <i class="bi bi-people"></i>
                </div>
                <div class="feature-content">
                  <h4>Verified Agents & Dealers</h4>
                  <p>Connect directly with registered, background-checked real estate agents and dealers in your target locality.</p>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6">
              <div class="feature-box" data-aos="flip-up" data-aos-delay="550">
                <div class="feature-icon">
                  <i class="bi bi-shield-check"></i>
                </div>
                <div class="feature-content">
                  <h4>Free Home Loan Assistance</h4>
                  <p>Get free guidance on home loan eligibility, documentation, and the best rates from leading banks for your property purchase.</p>
                </div>
              </div>
            </div>
          </div>
        </div><!-- End Features Showcase -->

        <div class="metrics-section" data-aos="fade-up" data-aos-delay="400">
          <div class="row justify-content-center">
            <div class="col-lg-10">
              <div class="metrics-wrapper">
                <div class="row text-center">
                  <div class="col-lg-3 col-6">
                    <div class="metric-item" data-aos="zoom-in" data-aos-delay="450">
                      <div class="metric-icon">
                        <i class="bi bi-houses"></i>
                      </div>
                      <div class="metric-value">
                        <span data-purecounter-start="0" data-purecounter-end="500" data-purecounter-duration="2" class="purecounter"></span>+
                      </div>
                      <div class="metric-label">Verified Properties</div>
                    </div>
                  </div>
                  <div class="col-lg-3 col-6">
                    <div class="metric-item" data-aos="zoom-in" data-aos-delay="500">
                      <div class="metric-icon">
                        <i class="bi bi-people"></i>
                      </div>
                      <div class="metric-value">
                        <span data-purecounter-start="0" data-purecounter-end="200" data-purecounter-duration="2" class="purecounter"></span>+
                      </div>
                      <div class="metric-label">Registered Dealers</div>
                    </div>
                  </div>
                  <div class="col-lg-3 col-6">
                    <div class="metric-item" data-aos="zoom-in" data-aos-delay="550">
                      <div class="metric-icon">
                        <i class="bi bi-geo-alt"></i>
                      </div>
                      <div class="metric-value">
                        <span data-purecounter-start="0" data-purecounter-end="25" data-purecounter-duration="2" class="purecounter"></span>+
                      </div>
                      <div class="metric-label">Localities Covered</div>
                    </div>
                  </div>
                  <div class="col-lg-3 col-6">
                    <div class="metric-item" data-aos="zoom-in" data-aos-delay="600">
                      <div class="metric-icon">
                        <i class="bi bi-star-fill"></i>
                      </div>
                      <div class="metric-value">4.8</div>
                      <div class="metric-label">User Rating</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div><!-- End Metrics Section -->

        {{-- Why Choose Us --}}
        <div class="row justify-content-center mt-5 mb-4" data-aos="fade-up" data-aos-delay="400">
          <div class="col-lg-9">
            <h3 class="text-center mb-4" style="color:#0a2d5e; font-weight:700;">Why Buyers & Sellers Choose {{ config('app.name') }}</h3>
            <div class="row gy-3">
              <div class="col-md-6">
                <div class="d-flex gap-3 align-items-start">
                  <div style="background:#e8f3fe; border-radius:8px; padding:10px 12px; flex-shrink:0;">
                    <i class="bi bi-check2-circle text-primary fs-5"></i>
                  </div>
                  <div>
                    <h6 class="fw-700 mb-1">Zero Fake Listings</h6>
                    <p class="text-muted small mb-0">Every property is manually verified by our team before it appears on the site.</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="d-flex gap-3 align-items-start">
                  <div style="background:#e8f3fe; border-radius:8px; padding:10px 12px; flex-shrink:0;">
                    <i class="bi bi-telephone-fill text-primary fs-5"></i>
                  </div>
                  <div>
                    <h6 class="fw-700 mb-1">Direct Agent Contact</h6>
                    <p class="text-muted small mb-0">Connect directly with verified agents — no middlemen, no spam calls.</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="d-flex gap-3 align-items-start">
                  <div style="background:#e8f3fe; border-radius:8px; padding:10px 12px; flex-shrink:0;">
                    <i class="bi bi-geo-alt-fill text-primary fs-5"></i>
                  </div>
                  <div>
                    <h6 class="fw-700 mb-1">Hyperlocal Search</h6>
                    <p class="text-muted small mb-0">Search by sector, road, or neighbourhood — find properties within 5 km of your target location.</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="d-flex gap-3 align-items-start">
                  <div style="background:#e8f3fe; border-radius:8px; padding:10px 12px; flex-shrink:0;">
                    <i class="bi bi-camera-fill text-primary fs-5"></i>
                  </div>
                  <div>
                    <h6 class="fw-700 mb-1">Real Photos & Floor Plans</h6>
                    <p class="text-muted small mb-0">Listings include actual photos, layout plans and nearby amenities for informed decisions.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="cta-section" data-aos="fade-up" data-aos-delay="500">
          <div class="row justify-content-center text-center">
            <div class="col-lg-8">
              <h3>Ready to Find Your Dream Property in Tricity?</h3>
              <p>Whether you're buying your first home, investing in a plot, or looking for a rental flat — {{ config('app.name') }} has the best verified listings in Chandigarh, Mohali, Zirakpur and Panchkula.</p>
              <div class="action-buttons">
                <a href="/properties" class="btn btn-primary">Browse Properties</a>
                <a href="/contact" class="btn btn-secondary">Talk to an Expert</a>
              </div>
            </div>
          </div>
        </div><!-- End CTA Section -->
      </div>
    </section><!-- /About Section -->
</main>
