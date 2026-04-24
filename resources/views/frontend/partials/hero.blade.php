<style>
/* ── Hero ───────────────────────────────────────────────── */
.hs-hero {
  position: relative;
  min-height: 580px;
  display: flex; align-items: center;
  background:
    linear-gradient(135deg, rgba(10,45,94,0.90) 0%, rgba(15,76,129,0.82) 50%, rgba(21,101,192,0.78) 100%),
    url('/assets/img/real-estate/property-exterior-9.webp') center/cover no-repeat;
  padding: 70px 0 100px;
  overflow: hidden;
}
.hs-hero::after {
  content:''; position:absolute; bottom:-2px; left:0; right:0; height:70px;
  background:#f4f6f9;
  clip-path: ellipse(55% 100% at 50% 100%);
}
.hero-blob { position:absolute; border-radius:50%; opacity:.06; pointer-events:none; background:#fff; }
.hero-blob-1 { width:450px; height:450px; top:-120px; right:-120px; }
.hero-blob-2 { width:280px; height:280px; bottom:60px; left:-80px; }

.hs-hero .hero-label {
  display:inline-flex; align-items:center; gap:7px;
  background:rgba(255,255,255,.15); backdrop-filter:blur(6px);
  border:1px solid rgba(255,255,255,.25); border-radius:50px;
  padding:6px 18px; color:#fff; font-size:.82rem; font-weight:700;
  letter-spacing:.5px; margin-bottom:18px;
}
.hs-hero h1 {
  font-size:2.8rem; font-weight:900; color:#fff; line-height:1.18;
  margin-bottom:12px; letter-spacing:-0.5px;
}
.hs-hero h1 span { color:#50e6ff; }
.hs-hero .hero-sub { font-size:1.05rem; color:rgba(255,255,255,.82); margin-bottom:28px; max-width:540px; }

/* ── Search card ─────────────────────────────────────────── */
.hero-search-card { background:#fff; border-radius:20px; box-shadow:0 20px 60px rgba(0,0,0,.22); overflow:hidden; }
.hero-tab-strip { display:flex; background:#f8faff; border-bottom:1px solid #e8f0fe; }
.hero-tab {
  padding:14px 22px; font-size:.88rem; font-weight:700; color:#64748b;
  cursor:pointer; border:none; background:none; border-bottom:3px solid transparent;
  transition:all .2s; display:flex; align-items:center; gap:6px;
}
.hero-tab.active, .hero-tab:hover { color:#0a2d5e; border-bottom-color:#0078d4; background:#fff; }
.hero-tab-panel { display:none; padding:20px 22px 16px; }
.hero-tab-panel.active { display:block; }
.hero-search-row { display:flex; gap:10px; align-items:flex-end; flex-wrap:wrap; }
.hero-field { flex:1; min-width:150px; }
.hero-field label { display:block; font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.6px; color:#94a3b8; margin-bottom:4px; }
.hero-field input, .hero-field select {
  width:100%; border:1.5px solid #e4e8f0; border-radius:10px;
  padding:10px 14px; font-size:.875rem; color:#1e293b;
  outline:none; transition:border-color .2s; background:#fafbff;
}
.hero-field input:focus, .hero-field select:focus { border-color:#0078d4; background:#fff; box-shadow:0 0 0 3px rgba(0,120,212,.1); }
.btn-hero-search {
  background:linear-gradient(135deg,#0a2d5e,#0078d4); color:#fff; border:none;
  border-radius:12px; padding:11px 26px; font-size:.95rem; font-weight:800;
  cursor:pointer; transition:opacity .2s; white-space:nowrap;
  display:flex; align-items:center; gap:8px;
}
.btn-hero-search:hover { opacity:.88; }
.popular-tags { margin-top:12px; display:flex; flex-wrap:wrap; gap:7px; align-items:center; }
.popular-tags > span { font-size:.75rem; color:#94a3b8; font-weight:700; }
.popular-tag { background:#f1f5f9; color:#475569; border-radius:50px; padding:4px 12px; font-size:.75rem; font-weight:600; text-decoration:none; transition:all .2s; }
.popular-tag:hover { background:#0078d4; color:#fff; }

/* ── Stats row ───────────────────────────────────────────── */
.hero-stats { display:flex; gap:6px; margin-top:24px; flex-wrap:wrap; }
.hero-stat {
  flex:1; min-width:100px; text-align:center; padding:14px 8px;
  background:rgba(255,255,255,.12); backdrop-filter:blur(6px);
  border:1px solid rgba(255,255,255,.18); border-radius:14px; transition:background .2s;
}
.hero-stat:hover { background:rgba(255,255,255,.2); }
.hero-stat-num { font-size:1.5rem; font-weight:900; color:#fff; line-height:1; }
.hero-stat-label { font-size:.68rem; color:rgba(255,255,255,.7); font-weight:600; margin-top:3px; text-transform:uppercase; letter-spacing:.4px; }

/* ── Right grid ──────────────────────────────────────────── */
.hero-right-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; height:350px; }
.hero-img-card { border-radius:16px; overflow:hidden; position:relative; box-shadow:0 8px 30px rgba(0,0,0,.3); }
.hero-img-card img { width:100%; height:100%; object-fit:cover; }
.hero-img-card.tall { grid-row:span 2; }
.hero-img-card-label {
  position:absolute; bottom:0; left:0; right:0;
  background:linear-gradient(to top, rgba(0,0,0,.75) 0%, transparent 100%);
  padding:20px 12px 10px;
}
.hero-img-card-label .price { font-size:1rem; color:#fff; font-weight:900; }
.hero-img-card-label .loc { font-size:.72rem; color:rgba(255,255,255,.8); }
.hero-verified-badge {
  position:absolute; top:10px; right:10px;
  background:rgba(255,255,255,.9); border-radius:50px;
  padding:3px 9px; font-size:.68rem; font-weight:700; color:#16a34a;
}

@media(max-width:991px) {
  .hs-hero h1 { font-size:2rem; }
  .hero-right-grid { display:none; }
  .hs-hero { min-height:auto; padding:50px 0 90px; }
}
@media(max-width:576px) {
  .hero-tab { padding:10px 12px; font-size:.78rem; }
  .hero-search-row { flex-direction:column; }
  .hero-field { min-width:100%; }
}
</style>

<section class="hs-hero">
  <div class="hero-blob hero-blob-1"></div>
  <div class="hero-blob hero-blob-2"></div>
  <div class="container" style="position:relative;z-index:2;">
    <div class="row align-items-center g-4">

      {{-- LEFT --}}
      <div class="col-lg-7">
        <div class="hero-label"><i class="bi bi-shield-check"></i> India's Trusted Property Platform</div>
        <h1>Find Your <span>Dream</span><br>Property in India</h1>
        <p class="hero-sub">Search from <strong style="color:#50e6ff;">{{ number_format($totalProperties ?? 323) }}+</strong> verified listings across {{ $totalCities ?? 14 }} cities. Buy, Rent or Invest — all in one place.</p>

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
              <a href="{{ route('properties', ['looking_for'=>'Rent','city'=>'Bengaluru']) }}" class="popular-tag">Bengaluru</a>
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
              <a href="{{ route('properties', ['looking_for'=>'PG','city'=>'Bengaluru']) }}" class="popular-tag">PG in Bengaluru</a>
              <a href="{{ route('properties', ['looking_for'=>'PG','city'=>'Mumbai']) }}" class="popular-tag">PG in Mumbai</a>
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
                <a href="{{ route('projects.show', $nl->id) }}" class="popular-tag">{{ Str::limit($nl->title, 25) }}</a>
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
            <div class="hero-stat-num"><span data-purecounter-start="0" data-purecounter-end="96" data-purecounter-duration="1" class="purecounter"></span>%</div>
            <div class="hero-stat-label">Satisfaction</div>
          </div>
        </div>
      </div>

      {{-- RIGHT: image mosaic --}}
      <div class="col-lg-5 d-none d-lg-block">
        <div class="hero-right-grid">
          <div class="hero-img-card tall">
            <img src="/assets/img/real-estate/property-exterior-8.webp" alt="Premium Property" loading="eager">
            <span class="hero-verified-badge"><i class="bi bi-patch-check-fill me-1"></i>Verified</span>
            <div class="hero-img-card-label">
              <div class="price">₹2.40 Cr</div>
              <div class="loc"><i class="bi bi-geo-alt me-1"></i>4 BHK Villa · Bengaluru</div>
            </div>
          </div>
          <div class="hero-img-card">
            <img src="/assets/img/real-estate/property-interior-5.webp" alt="Interior" loading="eager">
            <div class="hero-img-card-label">
              <div class="price">₹85 L</div>
              <div class="loc"><i class="bi bi-geo-alt me-1"></i>3 BHK · Mumbai</div>
            </div>
          </div>
          <div class="hero-img-card">
            <img src="/assets/img/real-estate/property-exterior-3.webp" alt="Modern Home" loading="eager">
            <div class="hero-img-card-label">
              <div class="price">₹55 L</div>
              <div class="loc"><i class="bi bi-geo-alt me-1"></i>2 BHK · Gurugram</div>
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
