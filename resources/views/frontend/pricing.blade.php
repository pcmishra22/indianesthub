@extends('frontend.layout')

{{-- ════════════════════════ SEO META ════════════════════════ --}}
@section('title', 'Pricing & Plans | ' . config('app.name') . ' – Post Property in Chandigarh Tricity')
@section('meta_description', 'Simple, transparent pricing for property owners, dealers & builders in Chandigarh Tricity. Free for individual owners. Affordable plans for agents. Premium packages for builders.')
@section('canonical', url('/pricing'))
@section('robots', 'index, follow')
@section('og_title', 'Pricing & Plans | ' . config('app.name') . ' Real Estate')
@section('og_description', 'List your property on ' . config('app.name') . ' — free for owners, flexible plans for agents, project packages for builders in Chandigarh, Mohali, Zirakpur.')

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Pricing","item":"{{ url('/pricing') }}"}
  ]
}
</script>
@endsection

@section('head')
<style>
/* ══════════════════════════════════════════
   PRICING PAGE STYLES
══════════════════════════════════════════ */

/* Hero */
.pricing-hero {
  background: linear-gradient(135deg, #0a2d5e 0%, #0f4c81 55%, #1565c0 100%);
  padding: 72px 0 60px;
  color: #fff;
  position: relative;
  overflow: hidden;
}
.pricing-hero::before {
  content: '';
  position: absolute;
  top: -60px; right: -60px;
  width: 300px; height: 300px;
  background: radial-gradient(circle, rgba(80,230,255,.12) 0%, transparent 70%);
  border-radius: 50%;
}
.pricing-hero::after {
  content: '';
  position: absolute;
  bottom: -80px; left: -40px;
  width: 250px; height: 250px;
  background: radial-gradient(circle, rgba(0,120,212,.18) 0%, transparent 70%);
  border-radius: 50%;
}
.pricing-hero h1 {
  font-size: 2.4rem;
  font-weight: 800;
  line-height: 1.2;
}
.pricing-hero .hero-sub {
  font-size: 1.1rem;
  opacity: .85;
  margin: 1rem 0 1.8rem;
  max-width: 560px;
}
.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(255,255,255,.12);
  border: 1px solid rgba(255,255,255,.2);
  border-radius: 20px;
  padding: 5px 14px;
  font-size: .8rem;
  font-weight: 600;
  letter-spacing: .5px;
  margin-bottom: 20px;
  color: #50e6ff;
}

/* Segment selector tabs */
.segment-tabs {
  display: flex;
  gap: 8px;
  justify-content: center;
  margin: 48px 0 0;
  flex-wrap: wrap;
}
.seg-tab {
  padding: 10px 28px;
  border-radius: 30px;
  font-size: .92rem;
  font-weight: 700;
  cursor: pointer;
  border: 2px solid #dee2e6;
  background: #fff;
  color: #64748b;
  transition: all .2s;
  display: flex;
  align-items: center;
  gap: 7px;
}
.seg-tab.active, .seg-tab:hover {
  background: linear-gradient(135deg, #0a2d5e, #0078d4);
  border-color: transparent;
  color: #fff;
}
.seg-tab i { font-size: 1rem; }

/* Section titles */
.pricing-section-title {
  text-align: center;
  margin-bottom: 40px;
}
.pricing-section-title .seg-label {
  display: inline-block;
  background: #e8f3fe;
  color: #0078d4;
  font-size: .78rem;
  font-weight: 700;
  letter-spacing: 1px;
  text-transform: uppercase;
  padding: 4px 14px;
  border-radius: 20px;
  margin-bottom: 12px;
}
.pricing-section-title h2 {
  font-size: 1.9rem;
  font-weight: 800;
  color: #0a2d5e;
  margin-bottom: .5rem;
}
.pricing-section-title p {
  color: #64748b;
  font-size: 1rem;
  max-width: 540px;
  margin: 0 auto;
}

/* Plan Cards */
.plan-card {
  background: #fff;
  border-radius: 16px;
  border: 2px solid #e2e8f0;
  padding: 32px 28px;
  transition: all .25s;
  position: relative;
  height: 100%;
  display: flex;
  flex-direction: column;
}
.plan-card:hover {
  border-color: #0078d4;
  box-shadow: 0 12px 40px rgba(0,120,212,.14);
  transform: translateY(-4px);
}
.plan-card.featured {
  border-color: #0078d4;
  background: linear-gradient(160deg, #f0f8ff 0%, #fff 100%);
  box-shadow: 0 8px 32px rgba(0,120,212,.18);
}
.plan-card.popular-badge::before {
  content: 'MOST POPULAR';
  position: absolute;
  top: -13px; left: 50%; transform: translateX(-50%);
  background: linear-gradient(135deg, #0a2d5e, #0078d4);
  color: #fff;
  font-size: .68rem;
  font-weight: 800;
  letter-spacing: 1.2px;
  padding: 4px 18px;
  border-radius: 20px;
  white-space: nowrap;
}
.plan-icon {
  width: 52px; height: 52px;
  border-radius: 14px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.4rem;
  margin-bottom: 16px;
  background: #e8f3fe;
  color: #0078d4;
}
.plan-card.featured .plan-icon { background: #0078d4; color: #fff; }
.plan-name { font-size: 1rem; font-weight: 700; color: #0a2d5e; margin-bottom: 4px; }
.plan-tagline { font-size: .82rem; color: #94a3b8; margin-bottom: 18px; }
.plan-price {
  display: flex;
  align-items: flex-end;
  gap: 4px;
  margin-bottom: 6px;
}
.plan-price .currency { font-size: 1.1rem; font-weight: 700; color: #0a2d5e; line-height: 1.8; }
.plan-price .amount   { font-size: 2.4rem; font-weight: 800; color: #0a2d5e; line-height: 1; }
.plan-price .period   { font-size: .8rem; color: #94a3b8; line-height: 1.8; padding-bottom: 4px; }
.plan-price-note { font-size: .78rem; color: #94a3b8; margin-bottom: 20px; }
.plan-divider { border: 0; border-top: 1px solid #f1f5f9; margin: 16px 0; }
.plan-features { list-style: none; padding: 0; margin: 0 0 24px; flex: 1; }
.plan-features li {
  display: flex; align-items: flex-start; gap: 9px;
  font-size: .88rem; color: #475569;
  padding: 5px 0;
}
.plan-features li i { color: #22c55e; font-size: .9rem; flex-shrink: 0; margin-top: 2px; }
.plan-features li.muted i { color: #cbd5e1; }
.plan-features li.muted { color: #cbd5e1; }
.plan-cta {
  display: block; width: 100%; padding: 11px;
  text-align: center; font-weight: 700; font-size: .9rem;
  border-radius: 10px; text-decoration: none;
  transition: all .2s;
}
.plan-cta-primary {
  background: linear-gradient(135deg, #0a2d5e, #0078d4);
  color: #fff; border: none;
}
.plan-cta-primary:hover { background: linear-gradient(135deg, #0a2d5e, #005ba1); color: #fff; opacity:.95; }
.plan-cta-outline {
  background: transparent;
  color: #0078d4;
  border: 2px solid #0078d4;
}
.plan-cta-outline:hover { background: #0078d4; color: #fff; }
.plan-cta-green { background: #22c55e; color: #fff; border: none; }
.plan-cta-green:hover { background: #16a34a; color: #fff; }
.plan-cta-free { background: #f1f5f9; color: #0a2d5e; border: none; }
.plan-cta-free:hover { background: #e2e8f0; color: #0a2d5e; }

/* Free card */
.free-card {
  background: linear-gradient(135deg, #f0fdf4, #fff);
  border: 2px solid #86efac;
}
.free-card .plan-price .amount { color: #16a34a; }
.free-card .plan-icon { background: #dcfce7; color: #16a34a; }

/* Add-ons grid */
.addon-card {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  padding: 22px 20px;
  transition: all .2s;
  height: 100%;
}
.addon-card:hover {
  border-color: #0078d4;
  box-shadow: 0 6px 24px rgba(0,120,212,.1);
  transform: translateY(-2px);
}
.addon-card .addon-icon {
  width: 44px; height: 44px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.2rem; margin-bottom: 12px;
}
.addon-card h5 { font-size: .92rem; font-weight: 700; color: #0a2d5e; margin-bottom: 4px; }
.addon-card p  { font-size: .8rem; color: #64748b; margin-bottom: 10px; }
.addon-price   { font-size: 1.05rem; font-weight: 800; color: #0078d4; }
.addon-price span { font-size: .75rem; font-weight: 500; color: #94a3b8; }

/* Builder pricing */
.builder-card {
  border-radius: 16px;
  padding: 36px 30px;
  color: #fff;
  position: relative;
  overflow: hidden;
  height: 100%;
  display: flex; flex-direction: column;
}
.builder-card::after {
  content: '';
  position: absolute; top: -40px; right: -40px;
  width: 140px; height: 140px;
  background: rgba(255,255,255,.07);
  border-radius: 50%;
}
.builder-card-basic    { background: linear-gradient(135deg, #0a2d5e, #1e4b8a); }
.builder-card-premium  { background: linear-gradient(135deg, #7c3aed, #a855f7); }
.builder-card-spotlight { background: linear-gradient(135deg, #b45309, #f59e0b); }
.builder-card .bc-icon {
  font-size: 2rem; margin-bottom: 16px; opacity: .9;
}
.builder-card .bc-name { font-size: 1.1rem; font-weight: 800; margin-bottom: 4px; }
.builder-card .bc-price-range { font-size: 1.4rem; font-weight: 800; margin-bottom: 4px; }
.builder-card .bc-price-note { font-size: .78rem; opacity: .75; margin-bottom: 18px; }
.builder-card .bc-features { list-style: none; padding: 0; margin: 0 0 24px; flex: 1; }
.builder-card .bc-features li {
  font-size: .85rem; padding: 5px 0;
  display: flex; gap: 8px; align-items: flex-start;
  opacity: .9;
}
.builder-card .bc-features li i { flex-shrink: 0; margin-top: 2px; }
.builder-cta {
  display: block; padding: 11px;
  text-align: center; font-weight: 700; font-size: .9rem;
  border-radius: 10px; text-decoration: none;
  background: rgba(255,255,255,.2); color: #fff;
  border: 1px solid rgba(255,255,255,.35);
  transition: all .2s;
}
.builder-cta:hover { background: rgba(255,255,255,.32); color: #fff; }

/* Compare toggle */
.compare-toggle-wrap { text-align: center; margin: 16px 0 32px; }
.compare-toggle { background: none; border: none; color: #0078d4; font-size: .88rem; font-weight: 600; cursor: pointer; }
.compare-toggle i { transition: transform .2s; }
.compare-toggle.open i { transform: rotate(180deg); }

/* FAQ */
.pricing-faq .accordion-button {
  font-weight: 600; color: #0a2d5e; font-size: .92rem;
}
.pricing-faq .accordion-button:not(.collapsed) { color: #0078d4; background: #f0f8ff; }

/* Guarantee badge */
.guarantee-strip {
  background: linear-gradient(135deg, #0a2d5e, #0078d4);
  border-radius: 16px;
  padding: 32px;
  color: #fff;
}
.guarantee-strip h4 { font-size: 1.2rem; font-weight: 800; }
.guarantee-strip p  { opacity: .85; font-size: .92rem; margin: 0; }
.guarantee-icon { font-size: 2.5rem; opacity: .85; }

/* Segment section spacing */
.segment-section { padding: 64px 0; }
.segment-section + .segment-section { border-top: 1px solid #f1f5f9; }
.segment-section:last-of-type { border-bottom: 1px solid #f1f5f9; }

/* Mobile improvements */
@@media (max-width: 576px) {
  .pricing-hero h1 { font-size: 1.7rem; }
  .plan-card { padding: 24px 18px; }
  .builder-card { padding: 26px 20px; }
}
</style>
@endsection

@section('content')
<main class="main">

  {{-- ══════════════════════════════════════
       HERO
  ══════════════════════════════════════ --}}
  <div class="pricing-hero">
    <div class="container" style="position:relative; z-index:2;">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <span class="hero-badge"><i class="bi bi-tag-fill"></i> Simple, Transparent Pricing</span>
          <h1>List Your Property.<br>Reach Real Buyers.</h1>
          <p class="hero-sub">From individual home owners to top builders — {{ config('app.name') }} has a plan that fits your needs in Chandigarh Tricity. <strong>Free for individuals.</strong> Powerful for pros.</p>
          <div class="d-flex flex-wrap gap-3">
            <a href="{{ url('/pricing#owners') }}" class="btn btn-warning px-4 py-2 fw-700">
              <i class="bi bi-house me-1"></i> Individual Owner
            </a>
            <a href="{{ url('/pricing#dealers') }}" class="btn btn-outline-light px-4 py-2 fw-700">
              <i class="bi bi-person-badge me-1"></i> Dealer / Agent
            </a>
            <a href="{{ url('/pricing#builders') }}" class="btn btn-outline-light px-4 py-2 fw-700">
              <i class="bi bi-buildings me-1"></i> Builder
            </a>
          </div>
        </div>
        <div class="col-lg-5 d-none d-lg-flex justify-content-end align-items-center">
          {{-- Stats widget --}}
          <div class="d-flex flex-column gap-3">
            <div style="background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.2); border-radius:14px; padding:18px 24px; min-width:220px;">
              <div style="font-size:2rem; font-weight:800; color:#50e6ff;">3,000+</div>
              <div style="font-size:.82rem; opacity:.8;">Verified Listings Live</div>
            </div>
            <div style="background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.2); border-radius:14px; padding:18px 24px;">
              <div style="font-size:2rem; font-weight:800; color:#fbbf24;">340+</div>
              <div style="font-size:.82rem; opacity:.8;">Active Dealers on Platform</div>
            </div>
            <div style="background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.2); border-radius:14px; padding:18px 24px;">
              <div style="font-size:2rem; font-weight:800; color:#86efac;">Free</div>
              <div style="font-size:.82rem; opacity:.8;">For Individual Home Owners</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ══════════════════════════════════════
       TRUST BAR
  ══════════════════════════════════════ --}}
  <div style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:14px 0;">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-center gap-4 align-items-center" style="font-size:.83rem; color:#64748b; font-weight:600;">
        <span><i class="bi bi-check-circle-fill text-success me-1"></i> No hidden charges</span>
        <span><i class="bi bi-check-circle-fill text-success me-1"></i> Cancel anytime</span>
        <span><i class="bi bi-check-circle-fill text-success me-1"></i> Verified leads only</span>
        <span><i class="bi bi-check-circle-fill text-success me-1"></i> Dedicated support in Tricity</span>
        <span><i class="bi bi-check-circle-fill text-success me-1"></i> Live in under 24 hours</span>
      </div>
    </div>
  </div>

  {{-- ══════════════════════════════════════
       SECTION 1 — INDIVIDUAL OWNERS
  ══════════════════════════════════════ --}}
  <section id="owners" class="segment-section" style="background:#fff;">
    <div class="container">
      <div class="pricing-section-title">
        <span class="seg-label"><i class="bi bi-house me-1"></i> Individual Owners</span>
        <h2>Selling or Renting Your Own Property?</h2>
        <p>It's free to list. Pay only if you want faster results — featured placement or a boost gets you 5× more enquiries.</p>
      </div>

      <div class="row gy-4 justify-content-center">

        {{-- FREE --}}
        <div class="col-lg-3 col-md-6 d-flex">
          <div class="plan-card free-card w-100">
            <div class="plan-icon"><i class="bi bi-house-door"></i></div>
            <div class="plan-name">Free Listing</div>
            <div class="plan-tagline">Perfect for 1–2 properties</div>
            <div class="plan-price">
              <span class="currency">₹</span>
              <span class="amount">0</span>
              <span class="period">/ forever</span>
            </div>
            <div class="plan-price-note">No credit card needed</div>
            <hr class="plan-divider">
            <ul class="plan-features">
              <li><i class="bi bi-check-lg"></i> 1 active listing at a time</li>
              <li><i class="bi bi-check-lg"></i> Photos & description</li>
              <li><i class="bi bi-check-lg"></i> Buyer / tenant contact requests</li>
              <li><i class="bi bi-check-lg"></i> WhatsApp inquiry button</li>
              <li><i class="bi bi-check-lg"></i> Listed on {{ config('app.name') }}</li>
              <li class="muted"><i class="bi bi-x-lg"></i> Featured placement</li>
              <li class="muted"><i class="bi bi-x-lg"></i> Urgent / Boost tag</li>
            </ul>
            <a href="{{ route('register') }}" class="plan-cta plan-cta-free">Post Free Now</a>
          </div>
        </div>

        {{-- FEATURED LISTING --}}
        <div class="col-lg-3 col-md-6 d-flex">
          <div class="plan-card w-100">
            <div class="plan-icon"><i class="bi bi-star"></i></div>
            <div class="plan-name">Featured Listing</div>
            <div class="plan-tagline">Get 3× more visibility</div>
            <div class="plan-price">
              <span class="currency">₹</span>
              <span class="amount">299</span>
              <span class="period">/ 30 days</span>
            </div>
            <div class="plan-price-note">Per listing · one-time payment</div>
            <hr class="plan-divider">
            <ul class="plan-features">
              <li><i class="bi bi-check-lg"></i> Everything in Free</li>
              <li><i class="bi bi-check-lg"></i> "Featured" gold badge</li>
              <li><i class="bi bi-check-lg"></i> Top of search results</li>
              <li><i class="bi bi-check-lg"></i> Homepage carousel display</li>
              <li><i class="bi bi-check-lg"></i> Priority in city pages</li>
              <li><i class="bi bi-check-lg"></i> Email & SMS alerts to buyers</li>
              <li class="muted"><i class="bi bi-x-lg"></i> Urgent tag</li>
            </ul>
            <a href="https://wa.me/91{{ config('app.whatsapp_number','7340753780') }}?text=I%20want%20to%20feature%20my%20property%20listing%20on%20{{ urlencode(config('app.name')) }}."
               target="_blank" class="plan-cta plan-cta-outline">Get Featured →</a>
          </div>
        </div>

        {{-- BOOST --}}
        <div class="col-lg-3 col-md-6 d-flex">
          <div class="plan-card popular-badge featured w-100">
            <div class="plan-icon"><i class="bi bi-lightning-charge"></i></div>
            <div class="plan-name">Boost + Urgent</div>
            <div class="plan-tagline">Sell / rent in days, not weeks</div>
            <div class="plan-price">
              <span class="currency">₹</span>
              <span class="amount">499</span>
              <span class="period">/ 30 days</span>
            </div>
            <div class="plan-price-note">Per listing · includes featured</div>
            <hr class="plan-divider">
            <ul class="plan-features">
              <li><i class="bi bi-check-lg"></i> Everything in Featured</li>
              <li><i class="bi bi-check-lg"></i> "Urgent Sale / Rent" red tag</li>
              <li><i class="bi bi-check-lg"></i> #1 position in locality search</li>
              <li><i class="bi bi-check-lg"></i> WhatsApp blast to buyer pool</li>
              <li><i class="bi bi-check-lg"></i> Social media story post</li>
              <li><i class="bi bi-check-lg"></i> Direct leads shared via email</li>
              <li><i class="bi bi-check-lg"></i> 5× average inquiry rate</li>
            </ul>
            <a href="https://wa.me/91{{ config('app.whatsapp_number','7340753780') }}?text=I%20want%20to%20boost%20my%20property%20listing%20urgently."
               target="_blank" class="plan-cta plan-cta-primary">Boost My Listing →</a>
          </div>
        </div>

        {{-- 2 LISTINGS --}}
        <div class="col-lg-3 col-md-6 d-flex">
          <div class="plan-card w-100">
            <div class="plan-icon"><i class="bi bi-houses"></i></div>
            <div class="plan-name">Multi-Property Pack</div>
            <div class="plan-tagline">Own 2–5 properties</div>
            <div class="plan-price">
              <span class="currency">₹</span>
              <span class="amount">999</span>
              <span class="period">/ 90 days</span>
            </div>
            <div class="plan-price-note">Up to 5 listings · best value</div>
            <hr class="plan-divider">
            <ul class="plan-features">
              <li><i class="bi bi-check-lg"></i> Up to 5 active listings</li>
              <li><i class="bi bi-check-lg"></i> 2 featured slots included</li>
              <li><i class="bi bi-check-lg"></i> All property types (Flat/Plot/Villa)</li>
              <li><i class="bi bi-check-lg"></i> Analytics dashboard</li>
              <li><i class="bi bi-check-lg"></i> Priority customer support</li>
              <li><i class="bi bi-check-lg"></i> Lead management inbox</li>
              <li><i class="bi bi-check-lg"></i> 90-day visibility guarantee</li>
            </ul>
            <a href="https://wa.me/91{{ config('app.whatsapp_number','7340753780') }}?text=I%20want%20the%20Multi-Property%20pack%20on%20{{ urlencode(config('app.name')) }}."
               target="_blank" class="plan-cta plan-cta-outline">Get Pack →</a>
          </div>
        </div>

      </div>{{-- /row --}}

      <p class="text-center text-muted mt-4" style="font-size:.84rem;">
        <i class="bi bi-info-circle me-1"></i>
        All owner plans include: WhatsApp inquiry, photos (up to 10), map location, and area/price details.
        Payments processed securely via UPI / Razorpay.
      </p>
    </div>
  </section>

  {{-- ══════════════════════════════════════
       SECTION 2 — DEALERS / AGENTS
  ══════════════════════════════════════ --}}
  <section id="dealers" class="segment-section" style="background:#f8fafc;">
    <div class="container">
      <div class="pricing-section-title">
        <span class="seg-label"><i class="bi bi-person-badge me-1"></i> Dealers &amp; Agents</span>
        <h2>Your Main Revenue Engine</h2>
        <p>Subscription plans for professional agents managing multiple properties. Pick a monthly pack or pay per listing — whatever fits your workflow.</p>
      </div>

      {{-- Tab: Subscription vs Pay-per-listing --}}
      <div class="d-flex justify-content-center mb-5">
        <div style="background:#e2e8f0; border-radius:30px; padding:5px; display:inline-flex; gap:4px;">
          <button class="btn btn-sm px-4 py-2 fw-700 active" id="dealer-sub-btn"
                  style="border-radius:25px; background:linear-gradient(135deg,#0a2d5e,#0078d4); color:#fff; border:none; font-size:.88rem;"
                  onclick="showDealerPlans('subscription', this)">
            <i class="bi bi-calendar-check me-1"></i> Subscription
          </button>
          <button class="btn btn-sm px-4 py-2 fw-700" id="dealer-ppl-btn"
                  style="border-radius:25px; background:transparent; color:#64748b; border:none; font-size:.88rem;"
                  onclick="showDealerPlans('perlisting', this)">
            <i class="bi bi-tag me-1"></i> Pay Per Listing
          </button>
        </div>
      </div>

      {{-- Subscription Plans --}}
      <div id="dealer-subscription">
        <div class="row gy-4 justify-content-center">

          {{-- Basic --}}
          <div class="col-lg-4 col-md-6 d-flex">
            <div class="plan-card w-100">
              <div class="plan-icon" style="background:#f0fdf4; color:#16a34a;"><i class="bi bi-person"></i></div>
              <div class="plan-name">Basic</div>
              <div class="plan-tagline">Starting out in Tricity</div>
              <div class="plan-price">
                <span class="currency">₹</span>
                <span class="amount">999</span>
                <span class="period">/ month</span>
              </div>
              <div class="plan-price-note">Billed monthly · no lock-in</div>
              <hr class="plan-divider">
              <ul class="plan-features">
                <li><i class="bi bi-check-lg"></i> <strong>20 listings</strong> per month</li>
                <li><i class="bi bi-check-lg"></i> Standard visibility</li>
                <li><i class="bi bi-check-lg"></i> Buyer contact access</li>
                <li><i class="bi bi-check-lg"></i> Verified dealer badge</li>
                <li><i class="bi bi-check-lg"></i> Public agent profile page</li>
                <li><i class="bi bi-check-lg"></i> WhatsApp lead alerts</li>
                <li class="muted"><i class="bi bi-x-lg"></i> Featured listing slots</li>
                <li class="muted"><i class="bi bi-x-lg"></i> Lead priority boost</li>
              </ul>
              <a href="{{ route('dealer.register') }}" class="plan-cta plan-cta-outline">Start Basic →</a>
            </div>
          </div>

          {{-- Pro --}}
          <div class="col-lg-4 col-md-6 d-flex">
            <div class="plan-card popular-badge featured w-100">
              <div class="plan-icon"><i class="bi bi-person-check"></i></div>
              <div class="plan-name">Pro</div>
              <div class="plan-tagline">For serious agents</div>
              <div class="plan-price">
                <span class="currency">₹</span>
                <span class="amount">2,499</span>
                <span class="period">/ month</span>
              </div>
              <div class="plan-price-note">Save 20% vs per-listing pricing</div>
              <hr class="plan-divider">
              <ul class="plan-features">
                <li><i class="bi bi-check-lg"></i> <strong>50 listings</strong> per month</li>
                <li><i class="bi bi-check-lg"></i> <strong>5 featured listing slots</strong></li>
                <li><i class="bi bi-check-lg"></i> Higher search ranking</li>
                <li><i class="bi bi-check-lg"></i> Verified + Pro badge</li>
                <li><i class="bi bi-check-lg"></i> Lead dashboard & analytics</li>
                <li><i class="bi bi-check-lg"></i> Priority buyer alerts (SMS + WA)</li>
                <li><i class="bi bi-check-lg"></i> Area sponsorship slot</li>
                <li class="muted"><i class="bi bi-x-lg"></i> Dedicated account manager</li>
              </ul>
              <a href="{{ route('dealer.register') }}" class="plan-cta plan-cta-primary">Get Pro →</a>
            </div>
          </div>

          {{-- Elite --}}
          <div class="col-lg-4 col-md-6 d-flex">
            <div class="plan-card w-100">
              <div class="plan-icon" style="background:#fef9c3; color:#b45309;"><i class="bi bi-crown"></i></div>
              <div class="plan-name">Elite</div>
              <div class="plan-tagline">Unlimited, priority everything</div>
              <div class="plan-price">
                <span class="currency">₹</span>
                <span class="amount">5,999</span>
                <span class="period">/ month</span>
              </div>
              <div class="plan-price-note">Best ROI · dedicated support</div>
              <hr class="plan-divider">
              <ul class="plan-features">
                <li><i class="bi bi-check-lg"></i> <strong>Unlimited listings</strong></li>
                <li><i class="bi bi-check-lg"></i> <strong>Unlimited featured slots</strong></li>
                <li><i class="bi bi-check-lg"></i> #1 position in your localities</li>
                <li><i class="bi bi-check-lg"></i> Dedicated account manager</li>
                <li><i class="bi bi-check-lg"></i> CRM-style lead inbox</li>
                <li><i class="bi bi-check-lg"></i> Custom agent microsite</li>
                <li><i class="bi bi-check-lg"></i> Verified Elite badge</li>
                <li><i class="bi bi-check-lg"></i> Homepage agent spotlight</li>
              </ul>
              <a href="https://wa.me/91{{ config('app.whatsapp_number','7340753780') }}?text=I%20want%20Elite%20plan%20for%20my%20agency%20on%20{{ urlencode(config('app.name')) }}."
                 target="_blank" class="plan-cta plan-cta-outline">Talk to Sales →</a>
            </div>
          </div>

        </div>{{-- /row subscription --}}

        {{-- Comparison table --}}
        <div class="compare-toggle-wrap mt-4">
          <button class="compare-toggle" id="cmpToggle" onclick="toggleCompare()">
            <i class="bi bi-chevron-down me-1"></i> View full plan comparison
          </button>
        </div>
        <div id="compareTable" style="display:none; overflow-x:auto; margin-top:12px;">
          <table class="table table-bordered table-sm" style="font-size:.85rem; min-width:600px;">
            <thead style="background:#0a2d5e; color:#fff;">
              <tr>
                <th style="width:40%">Feature</th>
                <th class="text-center">Basic<br><small>₹999/mo</small></th>
                <th class="text-center" style="background:#1565c0;">Pro<br><small>₹2,499/mo</small></th>
                <th class="text-center">Elite<br><small>₹5,999/mo</small></th>
              </tr>
            </thead>
            <tbody>
              <tr><td>Listings / month</td><td class="text-center">20</td><td class="text-center fw-700">50</td><td class="text-center fw-700">Unlimited</td></tr>
              <tr><td>Featured listing slots</td><td class="text-center text-muted">—</td><td class="text-center">5</td><td class="text-center fw-700">Unlimited</td></tr>
              <tr><td>Verified dealer badge</td><td class="text-center text-success">✔</td><td class="text-center text-success">✔</td><td class="text-center text-success">✔</td></tr>
              <tr><td>Lead dashboard</td><td class="text-center text-muted">Basic</td><td class="text-center">Full</td><td class="text-center fw-700">CRM</td></tr>
              <tr><td>WhatsApp lead alerts</td><td class="text-center text-success">✔</td><td class="text-center text-success">✔</td><td class="text-center text-success">✔</td></tr>
              <tr><td>Priority buyer alerts (SMS)</td><td class="text-center text-muted">—</td><td class="text-center text-success">✔</td><td class="text-center text-success">✔</td></tr>
              <tr><td>Area sponsorship</td><td class="text-center text-muted">—</td><td class="text-center">1 area</td><td class="text-center fw-700">3 areas</td></tr>
              <tr><td>Agent microsite</td><td class="text-center text-muted">—</td><td class="text-center text-muted">—</td><td class="text-center text-success">✔</td></tr>
              <tr><td>Dedicated account manager</td><td class="text-center text-muted">—</td><td class="text-center text-muted">—</td><td class="text-center text-success">✔</td></tr>
              <tr><td>Homepage agent spotlight</td><td class="text-center text-muted">—</td><td class="text-center text-muted">—</td><td class="text-center text-success">✔</td></tr>
            </tbody>
          </table>
        </div>
      </div>{{-- /subscription --}}

      {{-- Pay Per Listing --}}
      <div id="dealer-perlisting" style="display:none;">
        <div class="row gy-4 justify-content-center">
          <div class="col-lg-3 col-md-6 d-flex">
            <div class="plan-card w-100 text-center">
              <div class="plan-icon mx-auto"><i class="bi bi-list-ul"></i></div>
              <div class="plan-name">Normal Listing</div>
              <div class="plan-tagline">Standard placement</div>
              <div class="plan-price justify-content-center">
                <span class="currency">₹</span>
                <span class="amount">99</span>
                <span class="period">/ listing</span>
              </div>
              <div class="plan-price-note">Valid 30 days</div>
              <hr class="plan-divider">
              <ul class="plan-features">
                <li><i class="bi bi-check-lg"></i> Photos + description</li>
                <li><i class="bi bi-check-lg"></i> Buyer contact access</li>
                <li><i class="bi bi-check-lg"></i> Standard search rank</li>
                <li><i class="bi bi-check-lg"></i> WhatsApp inquiry</li>
              </ul>
              <a href="{{ route('dealer.register') }}" class="plan-cta plan-cta-outline">Post Now</a>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 d-flex">
            <div class="plan-card popular-badge featured w-100 text-center">
              <div class="plan-icon mx-auto"><i class="bi bi-star"></i></div>
              <div class="plan-name">Featured Listing</div>
              <div class="plan-tagline">3× more enquiries</div>
              <div class="plan-price justify-content-center">
                <span class="currency">₹</span>
                <span class="amount">299</span>
                <span class="period">/ listing</span>
              </div>
              <div class="plan-price-note">Valid 30 days</div>
              <hr class="plan-divider">
              <ul class="plan-features">
                <li><i class="bi bi-check-lg"></i> Everything in Normal</li>
                <li><i class="bi bi-check-lg"></i> Featured gold badge</li>
                <li><i class="bi bi-check-lg"></i> Top of search results</li>
                <li><i class="bi bi-check-lg"></i> Homepage carousel</li>
              </ul>
              <a href="{{ route('dealer.register') }}" class="plan-cta plan-cta-primary">Get Featured</a>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 d-flex">
            <div class="plan-card w-100 text-center">
              <div class="plan-icon mx-auto" style="background:#fef9c3; color:#b45309;"><i class="bi bi-trophy"></i></div>
              <div class="plan-name">Top Placement</div>
              <div class="plan-tagline">Absolute #1 position</div>
              <div class="plan-price justify-content-center">
                <span class="currency">₹</span>
                <span class="amount">499</span>
                <span class="period">/ listing</span>
              </div>
              <div class="plan-price-note">Valid 30 days</div>
              <hr class="plan-divider">
              <ul class="plan-features">
                <li><i class="bi bi-check-lg"></i> Everything in Featured</li>
                <li><i class="bi bi-check-lg"></i> #1 position in city page</li>
                <li><i class="bi bi-check-lg"></i> Urgent / Hot tag</li>
                <li><i class="bi bi-check-lg"></i> Buyer blast notification</li>
              </ul>
              <a href="https://wa.me/91{{ config('app.whatsapp_number','7340753780') }}?text=I%20want%20top%20placement%20for%20my%20listing."
                 target="_blank" class="plan-cta plan-cta-outline">Get Top Spot</a>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 d-flex">
            <div class="plan-card w-100 text-center" style="border-color:#86efac; background:linear-gradient(135deg,#f0fdf4,#fff);">
              <div class="plan-icon mx-auto" style="background:#dcfce7; color:#16a34a;"><i class="bi bi-arrow-repeat"></i></div>
              <div class="plan-name">Bulk Pack — 10 Listings</div>
              <div class="plan-tagline">Best value for active agents</div>
              <div class="plan-price justify-content-center">
                <span class="currency">₹</span>
                <span class="amount">799</span>
                <span class="period">/ 10 listings</span>
              </div>
              <div class="plan-price-note">₹79 per listing · save 20%</div>
              <hr class="plan-divider">
              <ul class="plan-features">
                <li><i class="bi bi-check-lg"></i> 10 normal listings</li>
                <li><i class="bi bi-check-lg"></i> 60-day validity</li>
                <li><i class="bi bi-check-lg"></i> Use anytime in 60 days</li>
                <li><i class="bi bi-check-lg"></i> 20% saving vs per-listing</li>
              </ul>
              <a href="{{ route('dealer.register') }}" class="plan-cta plan-cta-green">Buy Pack</a>
            </div>
          </div>
        </div>
      </div>{{-- /perlisting --}}

    </div>
  </section>

  {{-- ══════════════════════════════════════
       SECTION 3 — BUILDERS / PROJECTS
  ══════════════════════════════════════ --}}
  <section id="builders" class="segment-section" style="background:#fff;">
    <div class="container">
      <div class="pricing-section-title">
        <span class="seg-label"><i class="bi bi-buildings me-1"></i> Builders &amp; Projects</span>
        <h2>Project Visibility, Not Per-Unit Pricing</h2>
        <p>Builders pay for project-level exposure — one package covers your entire project from launch to possession. Get leads, microsites, and brand recall.</p>
      </div>

      <div class="row gy-4 justify-content-center">

        {{-- Project Basic --}}
        <div class="col-lg-4 col-md-6 d-flex">
          <div class="builder-card builder-card-basic w-100">
            <i class="bi bi-building bc-icon"></i>
            <div class="bc-name">Project Basic</div>
            <div class="bc-price-range">₹10,000 – ₹25,000</div>
            <div class="bc-price-note">Pricing based on project size &amp; duration</div>
            <ul class="bc-features">
              <li><i class="bi bi-check-circle"></i> Dedicated project listing page</li>
              <li><i class="bi bi-check-circle"></i> Up to 30 unit types listed</li>
              <li><i class="bi bi-check-circle"></i> Photo gallery &amp; floor plans</li>
              <li><i class="bi bi-check-circle"></i> RERA ID display</li>
              <li><i class="bi bi-check-circle"></i> Buyer inquiry form</li>
              <li><i class="bi bi-check-circle"></i> Listed in "New Projects" section</li>
              <li><i class="bi bi-check-circle"></i> 90-day campaign</li>
            </ul>
            <a href="https://wa.me/91{{ config('app.whatsapp_number','7340753780') }}?text=I%27m%20a%20builder%20and%20want%20Project%20Basic%20package."
               target="_blank" class="builder-cta">Enquire Now →</a>
          </div>
        </div>

        {{-- Premium --}}
        <div class="col-lg-4 col-md-6 d-flex">
          <div class="builder-card builder-card-premium w-100">
            <i class="bi bi-gem bc-icon"></i>
            <div class="bc-name">Project Premium</div>
            <div class="bc-price-range">₹50,000</div>
            <div class="bc-price-note">6-month campaign · full branding</div>
            <ul class="bc-features">
              <li><i class="bi bi-check-circle"></i> Everything in Basic</li>
              <li><i class="bi bi-check-circle"></i> Homepage banner slot</li>
              <li><i class="bi bi-check-circle"></i> Priority leads &amp; WhatsApp blasts</li>
              <li><i class="bi bi-check-circle"></i> Brochure PDF upload</li>
              <li><i class="bi bi-check-circle"></i> Video tour embed</li>
              <li><i class="bi bi-check-circle"></i> Lead dashboard access</li>
              <li><i class="bi bi-check-circle"></i> Social media promotion posts</li>
            </ul>
            <a href="https://wa.me/91{{ config('app.whatsapp_number','7340753780') }}?text=I%27m%20a%20builder%20and%20want%20Project%20Premium%20package."
               target="_blank" class="builder-cta">Get Premium →</a>
          </div>
        </div>

        {{-- Spotlight --}}
        <div class="col-lg-4 col-md-6 d-flex">
          <div class="builder-card builder-card-spotlight w-100">
            <i class="bi bi-stars bc-icon"></i>
            <div class="bc-name">Project Spotlight</div>
            <div class="bc-price-range">₹1,00,000+</div>
            <div class="bc-price-note">1-year campaign · maximum reach</div>
            <ul class="bc-features">
              <li><i class="bi bi-check-circle"></i> Everything in Premium</li>
              <li><i class="bi bi-check-circle"></i> Custom project microsite</li>
              <li><i class="bi bi-check-circle"></i> Homepage hero banner</li>
              <li><i class="bi bi-check-circle"></i> "Top Builder in [City]" badge</li>
              <li><i class="bi bi-check-circle"></i> CRM integration for leads</li>
              <li><i class="bi bi-check-circle"></i> Dedicated campaign manager</li>
              <li><i class="bi bi-check-circle"></i> Monthly lead &amp; analytics report</li>
            </ul>
            <a href="https://wa.me/91{{ config('app.whatsapp_number','7340753780') }}?text=I%27m%20interested%20in%20the%20Project%20Spotlight%20package."
               target="_blank" class="builder-cta">Talk to Us →</a>
          </div>
        </div>

      </div>{{-- /row --}}

      {{-- Builder features table --}}
      <div class="row justify-content-center mt-5">
        <div class="col-lg-10">
          <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
              <h6 class="fw-800 text-center mb-3" style="color:#0a2d5e; font-size:.92rem; text-transform:uppercase; letter-spacing:.5px;">All Builder Plans Include</h6>
              @php
              $builderFeatures = [
                ['bi-file-earmark-check','RERA Compliance Display'],
                ['bi-camera','Photo & Video Gallery'],
                ['bi-map','Location & Amenities Map'],
                ['bi-chat-left-dots','Buyer Inquiry Form'],
                ['bi-bar-chart','Lead Dashboard'],
                ['bi-whatsapp','WhatsApp Lead Alerts'],
              ];
              @endphp
              <div class="row gy-2 text-center">
                @foreach($builderFeatures as $f)
                <div class="col-lg-2 col-md-4 col-6">
                  <div style="background:#f8fafc; border-radius:10px; padding:14px 8px;">
                    <i class="bi bi-{{ $f[0] }} text-primary" style="font-size:1.3rem; display:block; margin-bottom:6px;"></i>
                    <div style="font-size:.75rem; font-weight:600; color:#475569;">{{ $f[1] }}</div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

  {{-- ══════════════════════════════════════
       SECTION 4 — PREMIUM ADD-ONS
  ══════════════════════════════════════ --}}
  <section class="segment-section" style="background:#f8fafc;">
    <div class="container">
      <div class="pricing-section-title">
        <span class="seg-label"><i class="bi bi-plus-circle me-1"></i> Premium Add-Ons</span>
        <h2>Supercharge Any Plan</h2>
        <p>Add-ons work with any subscription or per-listing purchase. This is where {{ config('app.name') }} earns serious revenue — and where you get serious results.</p>
      </div>

      <div class="row gy-3">
        @php
        $addons = [
          ['icon'=>'bi-star-fill',     'bg'=>'#fef9c3', 'color'=>'#b45309', 'title'=>'Featured Listing',    'desc'=>'Gold badge + top of search results in your city.',   'price'=>'₹299', 'per'=>'per listing · 30 days'],
          ['icon'=>'bi-house-heart',   'bg'=>'#fee2e2', 'color'=>'#dc2626', 'title'=>'Homepage Banner',      'desc'=>'Your property or project featured on the homepage.',   'price'=>'₹1,999', 'per'=>'per week'],
          ['icon'=>'bi-geo-alt-fill',  'bg'=>'#e0f2fe', 'color'=>'#0369a1', 'title'=>'Area Sponsorship',    'desc'=>'"Top Agent in Zirakpur" label in city/location page.', 'price'=>'₹4,999', 'per'=>'per month · per city'],
          ['icon'=>'bi-patch-check',   'bg'=>'#dcfce7', 'color'=>'#16a34a', 'title'=>'Verified Badge',       'desc'=>'Blue tick on your profile/listing — builds buyer trust.','price'=>'₹499', 'per'=>'per year'],
          ['icon'=>'bi-play-btn',      'bg'=>'#ede9fe', 'color'=>'#7c3aed', 'title'=>'Video Tour',           'desc'=>'Embed a YouTube or uploaded video in your listing.',   'price'=>'₹299', 'per'=>'per listing · permanent'],
          ['icon'=>'bi-robot',         'bg'=>'#f0fdf4', 'color'=>'#0a2d5e', 'title'=>'AI Description',       'desc'=>'Auto-generate SEO-optimised property descriptions.',   'price'=>'₹99', 'per'=>'per listing (coming soon)'],
          ['icon'=>'bi-megaphone',     'bg'=>'#fef3c7', 'color'=>'#d97706', 'title'=>'WhatsApp Blast',       'desc'=>'Notify 500+ active buyers in your target area.',      'price'=>'₹999', 'per'=>'per blast · per city'],
          ['icon'=>'bi-graph-up',      'bg'=>'#f0f9ff', 'color'=>'#0078d4', 'title'=>'Insights Dashboard',   'desc'=>'Views, clicks, saves & lead source analytics.',       'price'=>'₹299', 'per'=>'per month'],
        ];
        @endphp

        @foreach($addons as $addon)
        <div class="col-lg-3 col-md-6 d-flex">
          <div class="addon-card w-100">
            <div class="addon-icon" style="background:{{ $addon['bg'] }}; color:{{ $addon['color'] }};">
              <i class="{{ $addon['icon'] }}"></i>
            </div>
            <h5>{{ $addon['title'] }}</h5>
            <p>{{ $addon['desc'] }}</p>
            <div class="addon-price">{{ $addon['price'] }} <span>{{ $addon['per'] }}</span></div>
          </div>
        </div>
        @endforeach
      </div>

      <p class="text-center mt-4 text-muted" style="font-size:.84rem;">
        <i class="bi bi-info-circle me-1"></i>
        Add-ons can be purchased directly from your listing management dashboard or by contacting our team.
      </p>
    </div>
  </section>

  {{-- ══════════════════════════════════════
       GUARANTEE + CTA STRIP
  ══════════════════════════════════════ --}}
  <section class="segment-section" style="padding:40px 0;">
    <div class="container">
      <div class="guarantee-strip">
        <div class="row align-items-center gy-3">
          <div class="col-auto">
            <i class="bi bi-shield-fill-check guarantee-icon"></i>
          </div>
          <div class="col">
            <h4>30-Day Money-Back Guarantee</h4>
            <p>Not happy with the results in 30 days? We'll refund your subscription — no questions asked. We're that confident in our platform.</p>
          </div>
          <div class="col-lg-auto">
            <a href="{{ route('dealer.register') }}" class="btn btn-warning btn-lg fw-800 px-5">
              <i class="bi bi-rocket-takeoff me-2"></i>Get Started Free
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- ══════════════════════════════════════
       FAQ
  ══════════════════════════════════════ --}}
  <section class="segment-section" style="background:#f8fafc; padding:56px 0;">
    <div class="container">
      <div class="pricing-section-title mb-4">
        <h2>Frequently Asked Questions</h2>
      </div>
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="accordion pricing-faq" id="pricingFaq">

            @php
            $faqs = [
              ['q'=>'Can I list my property for free as an individual owner?',
               'a'=>'Yes! Individual property owners can list 1 property absolutely free on ' . config('app.name') . '. No credit card required. You only pay if you want featured placement or boost — which gets you 3–5× more enquiries.'],
              ['q'=>'How is the Dealer subscription different from per-listing pricing?',
               'a'=>'Subscription gives you a monthly pool of listings at a much lower per-listing cost — ideal if you list 10+ properties/month. Pay-per-listing is better for occasional listings. Most agents save 40–60% by switching to the Basic or Pro subscription.'],
              ['q'=>'Do builders pay per flat/unit or per project?',
               'a'=>'Builders pay per project, not per unit. One package covers your entire project — all unit types, floor plans, and leads. This makes it extremely cost-effective compared to listing each flat individually.'],
              ['q'=>'What happens after my subscription expires?',
               'a'=>'Your listings remain visible but are moved to standard placement. You will receive a renewal reminder 7 days before expiry. No auto-renewal without your explicit approval.'],
              ['q'=>'Are the leads verified? Will I get spam enquiries?',
               'a'=>config('app.name') . ' requires phone number verification for all buyers/tenants submitting enquiries. We filter out duplicate and bot submissions. Paid plans also get priority access to high-intent verified buyers.'],
              ['q'=>'How do I pay? Is UPI / credit card accepted?',
               'a'=>'We accept UPI (Google Pay, PhonePe, BHIM), credit/debit cards, net banking and bank transfer. All payments are processed securely via Razorpay. Receipts are sent immediately to your registered email.'],
              ['q'=>'Can I upgrade my plan mid-month?',
               'a'=>'Yes. You can upgrade at any time. The remaining balance from your current plan is pro-rated and adjusted against the new plan price. Downgrading takes effect from the next billing cycle.'],
            ];
            @endphp

            @foreach($faqs as $i => $faq)
            <div class="accordion-item border-0 mb-2 rounded-3 overflow-hidden shadow-sm">
              <h2 class="accordion-header">
                <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }} rounded-3"
                        type="button" data-bs-toggle="collapse"
                        data-bs-target="#faq{{ $i }}" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                  {{ $faq['q'] }}
                </button>
              </h2>
              <div id="faq{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}" data-bs-parent="#pricingFaq">
                <div class="accordion-body text-muted" style="font-size:.9rem; line-height:1.7;">
                  {{ $faq['a'] }}
                </div>
              </div>
            </div>
            @endforeach

          </div>

          <div class="text-center mt-5">
            <p class="text-muted mb-3">Still have questions? Our team is available on WhatsApp.</p>
            <a href="https://wa.me/91{{ config('app.whatsapp_number','7340753780') }}?text=Hi%2C%20I%20have%20a%20question%20about%20{{ urlencode(config('app.name')) }}%20pricing."
               target="_blank" rel="noopener"
               class="btn btn-success px-5 py-2 fw-700">
              <i class="bi bi-whatsapp me-2"></i> Chat with Us on WhatsApp
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>
@endsection

@section('scripts')
<script>
function showDealerPlans(type, btn) {
  document.getElementById('dealer-subscription').style.display = type === 'subscription' ? 'block' : 'none';
  document.getElementById('dealer-perlisting').style.display  = type === 'perlisting'   ? 'block' : 'none';
  // Reset buttons
  document.getElementById('dealer-sub-btn').style.background = 'transparent';
  document.getElementById('dealer-sub-btn').style.color = '#64748b';
  document.getElementById('dealer-ppl-btn').style.background = 'transparent';
  document.getElementById('dealer-ppl-btn').style.color = '#64748b';
  // Activate current
  btn.style.background = 'linear-gradient(135deg,#0a2d5e,#0078d4)';
  btn.style.color = '#fff';
}

function toggleCompare() {
  const tbl = document.getElementById('compareTable');
  const btn = document.getElementById('cmpToggle');
  if (tbl.style.display === 'none') {
    tbl.style.display = 'block';
    btn.innerHTML = '<i class="bi bi-chevron-up me-1"></i> Hide comparison';
    btn.classList.add('open');
  } else {
    tbl.style.display = 'none';
    btn.innerHTML = '<i class="bi bi-chevron-down me-1"></i> View full plan comparison';
    btn.classList.remove('open');
  }
}

// Smooth scroll to section on anchor click
document.querySelectorAll('a[href^="{{ url('/pricing') }}#"]').forEach(a => {
  a.addEventListener('click', e => {
    const id = a.getAttribute('href').split('#')[1];
    const el = document.getElementById(id);
    if (el) {
      e.preventDefault();
      el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});
</script>
@endsection
