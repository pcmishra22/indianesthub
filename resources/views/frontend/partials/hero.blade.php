
<section class="hs-hero">
  <div class="hero-blob hero-blob-1"></div>
  <div class="hero-blob hero-blob-2"></div>
  <div class="container" style="position:relative;z-index:2;">
    <div class="row align-items-center g-4">

      {{-- LEFT --}}
      <div class="col-lg-7">
        <div class="hero-label"><i class="bi bi-geo-alt-fill"></i> India's Trusted Property Platform</div>
        <h1>Buy, Sell & Rent Properties in <span>Top Cities</span></h1>
        <p class="hero-sub">Search <strong style="color:#50e6ff;">{{ number_format($totalProperties ?? 323) }}+</strong> verified flats, villas, plots & new projects across Chandigarh Tricity, Pune, Bangalore, Hyderabad & Delhi NCR — all in one place.</p>

        {{-- Search Card --}}
        <div class="hero-search-card">
          <div class="hero-tab-strip">
            <button class="hero-tab active" onclick="switchHeroTab('buy',this)"><i class="bi bi-house-check"></i> Buy</button>
            <button class="hero-tab" onclick="switchHeroTab('rent',this)"><i class="bi bi-key"></i> Rent</button>
            <button class="hero-tab" onclick="switchHeroTab('pg',this)"><i class="bi bi-people"></i> PG</button>
            <button class="hero-tab" onclick="switchHeroTab('new',this)"><i class="bi bi-building-add"></i> New Projects</button>
          </div>

          {{-- Buy --}}
          <div class="hero-tab-panel active" id="htab-buy">
            <form action="{{ route('properties') }}" method="GET">
              <input type="hidden" name="looking_for" value="Sale">
              <div class="hero-search-row">
                <div class="hero-field" style="flex:2;min-width:180px;">
                  <label><i class="bi bi-geo-alt me-1"></i>Location</label>
                  <input type="text" name="keyword" placeholder="City, locality, project…">
                </div>
                <div class="hero-field">
                  <label><i class="bi bi-building me-1"></i>Type</label>
                  <select name="property_type">
                    <option value="">All Types</option>
                    <option value="Apartment">Apartment / Flat</option>
                    <option value="Villa">Villa / House</option>
                    <option value="Plot">Plot / Land</option>
                    <option value="Commercial">Commercial</option>
                    <option value="Penthouse">Penthouse</option>
                  </select>
                </div>
                <div class="hero-field">
                  <label><i class="bi bi-currency-rupee me-1"></i>Budget</label>
                  <select name="max_price">
                    <option value="">Any Budget</option>
                    <option value="2500000">Under ₹25 L</option>
                    <option value="5000000">Under ₹50 L</option>
                    <option value="10000000">Under ₹1 Cr</option>
                    <option value="20000000">Under ₹2 Cr</option>
                    <option value="50000000">Under ₹5 Cr</option>
                  </select>
                </div>
                <button type="submit" class="btn-hero-search"><i class="bi bi-search"></i> Search</button>
              </div>
            </form>
            <div class="popular-tags">
              <span>Popular:</span>
              <a href="{{ route('properties', ['city'=>'Mumbai','looking_for'=>'Sale']) }}" class="popular-tag">Mumbai</a>
              <a href="{{ route('properties', ['city'=>'Bengaluru','looking_for'=>'Sale']) }}" class="popular-tag">Bengaluru</a>
              <a href="{{ route('properties', ['city'=>'Gurugram','looking_for'=>'Sale']) }}" class="popular-tag">Gurugram</a>
              <a href="{{ route('properties', ['property_type'=>'Villa','looking_for'=>'Sale']) }}" class="popular-tag">Villas</a>
              <a href="{{ route('properties', ['bhk_type'=>'2 BHK','looking_for'=>'Sale']) }}" class="popular-tag">2 BHK</a>
            </div>
          </div>

          {{-- Rent --}}
          <div class="hero-tab-panel" id="htab-rent">
            <form action="{{ route('properties') }}" method="GET">
              <input type="hidden" name="looking_for" value="Rent">
              <div class="hero-search-row">
                <div class="hero-field" style="flex:2;min-width:180px;">
                  <label><i class="bi bi-geo-alt me-1"></i>Location</label>
                  <input type="text" name="keyword" placeholder="Enter city or locality…">
                </div>
                <div class="hero-field">
                  <label><i class="bi bi-grid-3x3 me-1"></i>BHK</label>
                  <select name="bhk_type">
                    <option value="">Any BHK</option>
                    <option value="1 BHK">1 BHK</option>
                    <option value="2 BHK">2 BHK</option>
                    <option value="3 BHK">3 BHK</option>
                    <option value="Studio">Studio</option>
                  </select>
                </div>
                <div class="hero-field">
                  <label><i class="bi bi-currency-rupee me-1"></i>Max Rent</label>
                  <select name="max_price">
                    <option value="">Any</option>
                    <option value="10000">Under ₹10k</option>
                    <option value="20000">Under ₹20k</option>
                    <option value="50000">Under ₹50k</option>
                    <option value="100000">Under ₹1L</option>
                  </select>
                </div>
                <button type="submit" class="btn-hero-search"><i class="bi bi-search"></i> Search</button>
              </div>
            </form>
            <div class="popular-tags">
              <span>Popular:</span>
              <a href="{{ route('properties', ['looking_for'=>'Rent','city'=>'Zirakpur']) }}" class="popular-tag">Zirakpur</a>
              <a href="{{ route('properties', ['looking_for'=>'Rent','city'=>'Mohali']) }}" class="popular-tag">Mohali</a>
              <a href="{{ route('properties', ['looking_for'=>'Rent','bhk_type'=>'2 BHK']) }}" class="popular-tag">2 BHK Rent</a>
              <a href="{{ route('properties', ['looking_for'=>'Rent','furnishing_status'=>'Furnished']) }}" class="popular-tag">Furnished</a>
            </div>
          </div>

          {{-- PG --}}
          <div class="hero-tab-panel" id="htab-pg">
            <form action="{{ route('properties') }}" method="GET">
              <input type="hidden" name="looking_for" value="PG">
              <div class="hero-search-row">
                <div class="hero-field" style="flex:2;min-width:180px;">
                  <label><i class="bi bi-geo-alt me-1"></i>Location</label>
                  <input type="text" name="keyword" placeholder="Enter city or area…">
                </div>
                <div class="hero-field">
                  <label><i class="bi bi-currency-rupee me-1"></i>Budget</label>
                  <select name="max_price">
                    <option value="">Any Budget</option>
                    <option value="5000">Under ₹5k/mo</option>
                    <option value="10000">Under ₹10k/mo</option>
                    <option value="15000">Under ₹15k/mo</option>
                    <option value="25000">Under ₹25k/mo</option>
                  </select>
                </div>
                <button type="submit" class="btn-hero-search"><i class="bi bi-search"></i> Search</button>
              </div>
            </form>
            <div class="popular-tags">
              <span>Popular:</span>
              <a href="{{ route('properties', ['looking_for'=>'PG','city'=>'Zirakpur']) }}" class="popular-tag">PG in Zirakpur</a>
              <a href="{{ route('properties', ['looking_for'=>'PG','city'=>'Chandigarh']) }}" class="popular-tag">PG in Chandigarh</a>
            </div>
          </div>

          {{-- New Projects --}}
          <div class="hero-tab-panel" id="htab-new">
            <form action="{{ route('builders.index') }}" method="GET">
              <div class="hero-search-row">
                <div class="hero-field" style="flex:2;min-width:180px;">
                  <label><i class="bi bi-geo-alt me-1"></i>City</label>
                  <input type="text" name="city" placeholder="Enter city for new projects…">
                </div>
                <div class="hero-field">
                  <label><i class="bi bi-building-add me-1"></i>Status</label>
                  <select name="status">
                    <option value="">All Status</option>
                    <option value="Upcoming">Upcoming</option>
                    <option value="Under Construction">Under Construction</option>
                    <option value="Ready to Move">Ready to Move</option>
                  </select>
                </div>
                <button type="submit" class="btn-hero-search"><i class="bi bi-search"></i> Explore</button>
              </div>
            </form>
            <div class="popular-tags">
              <span>Popular:</span>
              <a href="{{ route('builders.index') }}" class="popular-tag">All Builders</a>
              @foreach(($newLaunches ?? collect())->take(3) as $nl)
                <a href="{{ route('projects.show', $nl->slug) }}" class="popular-tag">{{ Str::limit($nl->title, 25) }}</a>
              @endforeach
            </div>
          </div>
        </div>{{-- /search card --}}

        {{-- Stats --}}
        <div class="hero-stats">
          <div class="hero-stat">
            <div class="hero-stat-num"><span data-purecounter-start="0" data-purecounter-end="{{ $totalProperties ?? 323 }}" data-purecounter-duration="1" class="purecounter"></span>+</div>
            <div class="hero-stat-label">Properties</div>
          </div>
          <div class="hero-stat">
            <div class="hero-stat-num"><span data-purecounter-start="0" data-purecounter-end="{{ $totalCities ?? 14 }}" data-purecounter-duration="1" class="purecounter"></span>+</div>
            <div class="hero-stat-label">Cities</div>
          </div>
          <div class="hero-stat">
            <div class="hero-stat-num"><span data-purecounter-start="0" data-purecounter-end="{{ $totalDealers ?? 20 }}" data-purecounter-duration="1" class="purecounter"></span>+</div>
            <div class="hero-stat-label">Dealers</div>
          </div>
          <div class="hero-stat">
            <div class="hero-stat-num"><span data-purecounter-start="0" data-purecounter-end="{{ $totalBuilders ?? 6 }}" data-purecounter-duration="1" class="purecounter"></span>+</div>
            <div class="hero-stat-label">Builders</div>
          </div>
          <div class="hero-stat">
            <div class="hero-stat-num"><span data-purecounter-start="0" data-purecounter-end="{{ $satisfactionRate ?? 96 }}" data-purecounter-duration="1" class="purecounter"></span>%</div>
            <div class="hero-stat-label">Satisfaction</div>
          </div>
        </div>
      </div>

      {{-- RIGHT: image mosaic --}}
      <div class="col-lg-5 d-none d-lg-block">
        <div class="hero-right-grid">
          <div class="hero-img-card tall">
            <img src="/assets/img/real-estate/property-exterior-8.webp" alt="Premium Villa Zirakpur" loading="eager">
            <span class="hero-verified-badge"><i class="bi bi-patch-check-fill me-1"></i>Verified</span>
            <div class="hero-img-card-label">
              <div class="price">₹1.39 Cr</div>
              <div class="loc"><i class="bi bi-geo-alt me-1"></i>4 BHK Builder Floor · Zirakpur</div>
            </div>
          </div>
          <div class="hero-img-card">
            <img src="/assets/img/real-estate/property-interior-5.webp" alt="Independent Floor Panchkula" loading="eager">
            <div class="hero-img-card-label">
              <div class="price">₹88.69 L</div>
              <div class="loc"><i class="bi bi-geo-alt me-1"></i>3 BHK · Panchkula</div>
            </div>
          </div>
          <div class="hero-img-card">
            <img src="/assets/img/real-estate/property-exterior-3.webp" alt="Villa Mohali" loading="eager">
            <div class="hero-img-card-label">
              <div class="price">₹2.07 Cr</div>
              <div class="loc"><i class="bi bi-geo-alt me-1"></i>3 BHK Villa · Chandigarh</div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<script>
function switchHeroTab(tab, btn) {
  document.querySelectorAll('.hero-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.hero-tab-panel').forEach(p => p.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('htab-' + tab).classList.add('active');
}
</script>
