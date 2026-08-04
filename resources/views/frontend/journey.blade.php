@extends('frontend.layout')

@section('title', 'Your Complete Real Estate Journey | ' . config('app.name'))
@section('meta_description', 'From finding a property to moving in — home loan, legal verification, registration, interior design, furniture, and property management, all in one place.')
@section('canonical', route('journey'))

@push('styles')
<style>
  .jny-hero { background:linear-gradient(135deg,#0a2d5e 0%,#0078d4 100%); color:#fff; padding:64px 0 48px; text-align:center; }
  .jny-timeline { position:relative; max-width:820px; margin:0 auto; padding:50px 0; }
  .jny-timeline::before {
    content:''; position:absolute; left:31px; top:0; bottom:0; width:3px;
    background:linear-gradient(180deg,#0078d4 0%,#0a2d5e 100%); border-radius:2px;
  }
  @media (max-width: 767px) { .jny-timeline::before { left:23px; } }
  .jny-step { position:relative; display:flex; gap:22px; padding-bottom:38px; }
  .jny-step:last-child { padding-bottom:0; }
  .jny-num {
    flex-shrink:0; width:64px; height:64px; border-radius:50%; background:#fff;
    border:3px solid #0078d4; color:#0a2d5e; display:flex; align-items:center; justify-content:center;
    font-size:1.5rem; font-weight:800; z-index:1; box-shadow:0 4px 12px rgba(10,45,94,.12);
  }
  @media (max-width: 767px) { .jny-num { width:48px; height:48px; font-size:1.1rem; } }
  .jny-step.done .jny-num { background:#0a2d5e; color:#fff; }
  .jny-card { background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:22px 24px; flex:1; }
  .jny-card h3 { font-weight:800; color:#0a2d5e; font-size:1.15rem; margin-bottom:6px; }
  .jny-card p { color:#64748b; font-size:.9rem; margin-bottom:12px; }
  .jny-badge { display:inline-block; background:#dcfce7; color:#166534; font-size:.7rem; font-weight:700; padding:3px 10px; border-radius:20px; margin-bottom:8px; }
  .jny-cta { color:#0078d4; font-weight:700; font-size:.88rem; text-decoration:none; }
  .jny-cta:hover { text-decoration:underline; }
</style>
@endpush

@section('content')

<div class="jny-hero">
  <div class="container">
    <h1 style="font-weight:800;font-size:2rem;max-width:700px;margin:0 auto;">
      Not Just a Listing Site. Your Complete Real Estate Journey.
    </h1>
    <p style="opacity:.9;max-width:600px;margin:16px auto 0;font-size:1.05rem;">
      From finding the right property to moving into a fully furnished home — every step, handled in one place.
    </p>
  </div>
</div>

<section style="padding:20px 0 70px;">
  <div class="container">
    <div class="jny-timeline">

      <div class="jny-step done">
        <div class="jny-num">1</div>
        <div class="jny-card">
          <span class="jny-badge">Core Platform</span>
          <h3>Find Your Property</h3>
          <p>Search thousands of verified listings by city, budget, and property type across the Tricity region.</p>
          <a href="{{ route('properties') }}" class="jny-cta">Browse Properties &rarr;</a>
        </div>
      </div>

      <div class="jny-step done">
        <div class="jny-num">2</div>
        <div class="jny-card">
          <span class="jny-badge">Core Platform</span>
          <h3>Talk to a Dealer or Builder</h3>
          <p>Connect directly with verified dealers and builders — inquire, schedule a viewing, and negotiate.</p>
          <a href="{{ route('properties') }}" class="jny-cta">Start Browsing &rarr;</a>
        </div>
      </div>

      <div class="jny-step done">
        <div class="jny-num">3</div>
        <div class="jny-card">
          <span class="jny-badge">Home Loan</span>
          <h3>Get a Home Loan</h3>
          <p>Compare home loan options and get connected with lenders — no branch visits needed.</p>
          <a href="{{ route('seo.loan') }}" class="jny-cta">Explore Home Loans &rarr;</a>
        </div>
      </div>

      <div class="jny-step done">
        <div class="jny-num">4</div>
        <div class="jny-card">
          <span class="jny-badge">Legal Verification</span>
          <h3>Verify Title &amp; Legal Documents</h3>
          <p>Get your property's title, ownership, and documents verified by legal experts before you commit.</p>
          <a href="{{ route('seo.legal') }}" class="jny-cta">Get Legal Help &rarr;</a>
        </div>
      </div>

      <div class="jny-step done">
        <div class="jny-num">5</div>
        <div class="jny-card">
          <span class="jny-badge">Registration</span>
          <h3>Sale Deed &amp; Registration</h3>
          <p>Sale deed drafting, stamp duty guidance, and registration support handled by our legal partners.</p>
          <a href="{{ route('seo.legal') }}" class="jny-cta">Learn More &rarr;</a>
        </div>
      </div>

      <div class="jny-step done">
        <div class="jny-num">6</div>
        <div class="jny-card">
          <span class="jny-badge">Interior Design</span>
          <h3>Design Your Interiors</h3>
          <p>Connect with rated, reviewed interior designers and architects for your new home.</p>
          <a href="{{ route('services.category', 'interior-designers') }}" class="jny-cta">Find Interior Designers &rarr;</a>
        </div>
      </div>

      <div class="jny-step done">
        <div class="jny-num">7</div>
        <div class="jny-card">
          <span class="jny-badge">Marketplace</span>
          <h3>Furniture &amp; Modular Kitchens</h3>
          <p>Browse furniture, modular kitchens, and home decor from verified local vendors.</p>
          <a href="{{ route('marketplace.category', 'furniture') }}" class="jny-cta">Shop Furniture &rarr;</a>
        </div>
      </div>

      <div class="jny-step done">
        <div class="jny-num">8</div>
        <div class="jny-card">
          <span class="jny-badge">Moving</span>
          <h3>Packers &amp; Movers</h3>
          <p>Book verified, reviewed packers and movers for a hassle-free relocation.</p>
          <a href="{{ route('services.category', 'packers-movers') }}" class="jny-cta">Find Packers &amp; Movers &rarr;</a>
        </div>
      </div>

      <div class="jny-step done">
        <div class="jny-num">9</div>
        <div class="jny-card">
          <span class="jny-badge">Home Insurance</span>
          <h3>Protect Your Home</h3>
          <p>Get your new home and belongings covered with the right home insurance policy.</p>
          <a href="{{ route('seo.insurance') }}" class="jny-cta">Explore Home Insurance &rarr;</a>
        </div>
      </div>

      <div class="jny-step done">
        <div class="jny-num">10</div>
        <div class="jny-card">
          <span class="jny-badge">Property Management</span>
          <h3>Manage It Long-Term</h3>
          <p>Own a rental property? Tenant finding, rent collection, and maintenance — handled for you.</p>
          <a href="{{ route('property-management.index') }}" class="jny-cta">Explore Property Management &rarr;</a>
        </div>
      </div>

    </div>

    <div class="text-center mt-5">
      <a href="{{ route('properties') }}" class="btn btn-primary btn-lg px-5 py-3" style="border-radius:10px;font-weight:700;">
        Start Your Journey — Browse Properties
      </a>
    </div>
  </div>
</section>

@endsection
