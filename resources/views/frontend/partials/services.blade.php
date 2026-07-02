@php
  $contactUrl = url('/contact');
  $propertiesUrl = url('/properties');
@endphp

{{-- ═══════════════════════════════════════════════════════
     PAGE HERO
═══════════════════════════════════════════════════════ --}}
<div class="page-title" style="background:linear-gradient(135deg,#0369a1 0%,#0284c7 50%,#0ea5e9 100%) !important;padding:48px 0 36px;">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <div>
      <h1 class="mb-1" style="color:#fff !important;font-weight:800;font-size:2rem;">Our Services</h1>
      <p style="color:rgba(255,255,255,.85) !important;margin:0;font-size:.95rem;">Everything you need — from discovery to keys in hand</p>
    </div>
    <nav class="breadcrumbs mt-3 mt-lg-0">
      <ol style="background:rgba(255,255,255,.15);border-radius:30px;padding:6px 18px;display:flex;gap:6px;align-items:center;list-style:none;margin:0;">
        <li><a href="{{ url('/') }}" style="color:rgba(255,255,255,.85);text-decoration:none;">Home</a></li>
        <li style="color:#fff;font-weight:600;">Services</li>
      </ol>
    </nav>
  </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     INTRO STRIP
═══════════════════════════════════════════════════════ --}}
<section style="background:linear-gradient(135deg,#dbeafe 0%,#bfdbfe 100%);padding:44px 0 36px;border-bottom:1px solid #93c5fd;">
  <div class="container text-center">
    <p style="max-width:720px;margin:0 auto;color:#1e3a5f;font-size:1.08rem;line-height:1.85;font-weight:500;">
      {{ config('app.name') }} provides end-to-end real estate services — whether you're buying your first home, selling an investment property, looking for a rental, or need financial assistance like home loans and insurance. Our experts are with you every step of the way.
    </p>
    <div class="row g-3 justify-content-center mt-4">
      @php
        $quickStats = [
          ['icon'=>'bi-house-check','num'=>'15,000+','label'=>'Transactions Done'],
          ['icon'=>'bi-bank','num'=>'25+','label'=>'Bank Partners'],
          ['icon'=>'bi-people','num'=>'8,000+','label'=>'Happy Clients'],
          ['icon'=>'bi-shield-check','num'=>'100%','label'=>'RERA Compliant'],
        ];
      @endphp
      @foreach($quickStats as $s)
      <div class="col-6 col-md-3">
        <div style="background:rgba(255,255,255,0.80);backdrop-filter:blur(6px);border:1px solid rgba(255,255,255,0.9);border-radius:14px;padding:20px 12px;box-shadow:0 4px 16px rgba(10,45,94,.10);">
          <i class="bi {{ $s['icon'] }}" style="font-size:1.8rem;color:#0078d4;"></i>
          <div style="font-size:1.5rem;font-weight:800;color:#0a2d5e;margin-top:6px;">{{ $s['num'] }}</div>
          <div style="font-size:.82rem;color:#1e3a5f;font-weight:500;margin-top:2px;">{{ $s['label'] }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     MAIN SERVICES – 6 CORE CARDS (2 cols on lg, 3 on xl)
═══════════════════════════════════════════════════════ --}}
<section style="padding:60px 0 20px;">
  <div class="container">
    <div class="text-center mb-5">
      <span style="background:#dbeafe;color:#1d4ed8;font-size:.78rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;padding:4px 14px;border-radius:20px;">Core Services</span>
      <h2 style="font-size:1.9rem;font-weight:800;color:#0a2d5e;margin-top:12px;">What We Offer</h2>
    </div>

    <div class="row g-4">

      {{-- 1. Buy Property --}}
      <div class="col-lg-4 col-md-6">
        <div class="svc-card h-100" style="background:#fff;border-radius:18px;overflow:hidden;box-shadow:0 4px 20px rgba(10,45,94,.09);transition:transform .25s,box-shadow .25s;" onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 12px 32px rgba(10,45,94,.15)'" onmouseout="this.style.transform='';this.style.boxShadow='0 4px 20px rgba(10,45,94,.09)'">
          <div style="height:180px;overflow:hidden;position:relative;">
            <img src="{{ asset('assets/img/real-estate/property-exterior-3.webp') }}" alt="Buy Property" style="width:100%;height:100%;object-fit:cover;">
            <div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 40%,rgba(10,45,94,.7));"></div>
            <div style="position:absolute;bottom:14px;left:16px;background:#0078d4;color:#fff;font-size:.75rem;font-weight:700;padding:4px 12px;border-radius:20px;">BUYING</div>
          </div>
          <div style="padding:22px;">
            <div style="width:46px;height:46px;background:#dbeafe;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:14px;">
              <i class="bi bi-house-heart" style="font-size:1.4rem;color:#1d4ed8;"></i>
            </div>
            <h4 style="font-weight:700;color:#0a2d5e;margin-bottom:8px;">Buy Property</h4>
            <p style="color:#64748b;font-size:.9rem;line-height:1.7;margin-bottom:14px;">Find verified homes, apartments, villas and plots across India. Our agents guide you from shortlisting to registration.</p>
            <ul style="list-style:none;padding:0;margin-bottom:18px;">
              @foreach(['Verified listings across 50+ cities','Home visit & inspection support','Legal title clearance assistance','Price negotiation with seller'] as $f)
              <li style="font-size:.85rem;color:#475569;padding:3px 0;display:flex;gap:8px;align-items:flex-start;">
                <i class="bi bi-check-circle-fill" style="color:#16a34a;margin-top:2px;flex-shrink:0;"></i> {{ $f }}
              </li>
              @endforeach
            </ul>
            <a href="{{ url('/properties?looking_for=Sale') }}" style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#0a2d5e,#0078d4);color:#fff;padding:9px 20px;border-radius:8px;font-size:.85rem;font-weight:600;text-decoration:none;">
              Browse Properties <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>

      {{-- 2. Sell Property --}}
      <div class="col-lg-4 col-md-6">
        <div class="svc-card h-100" style="background:#fff;border-radius:18px;overflow:hidden;box-shadow:0 4px 20px rgba(10,45,94,.09);transition:transform .25s,box-shadow .25s;" onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 12px 32px rgba(10,45,94,.15)'" onmouseout="this.style.transform='';this.style.boxShadow='0 4px 20px rgba(10,45,94,.09)'">
          <div style="height:180px;overflow:hidden;position:relative;">
            <img src="{{ asset('assets/img/real-estate/property-exterior-7.webp') }}" alt="Sell Property" style="width:100%;height:100%;object-fit:cover;">
            <div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 40%,rgba(10,45,94,.7));"></div>
            <div style="position:absolute;bottom:14px;left:16px;background:#16a34a;color:#fff;font-size:.75rem;font-weight:700;padding:4px 12px;border-radius:20px;">SELLING</div>
          </div>
          <div style="padding:22px;">
            <div style="width:46px;height:46px;background:#dcfce7;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:14px;">
              <i class="bi bi-currency-rupee" style="font-size:1.4rem;color:#16a34a;"></i>
            </div>
            <h4 style="font-weight:700;color:#0a2d5e;margin-bottom:8px;">Sell Property</h4>
            <p style="color:#64748b;font-size:.9rem;line-height:1.7;margin-bottom:14px;">Get maximum value for your property. We market it to thousands of verified buyers and handle the entire sale process.</p>
            <ul style="list-style:none;padding:0;margin-bottom:18px;">
              @foreach(['Free property valuation report','Professional photography & listing','Targeted ads to 10L+ buyers','Complete paperwork support'] as $f)
              <li style="font-size:.85rem;color:#475569;padding:3px 0;display:flex;gap:8px;align-items:flex-start;">
                <i class="bi bi-check-circle-fill" style="color:#16a34a;margin-top:2px;flex-shrink:0;"></i> {{ $f }}
              </li>
              @endforeach
            </ul>
            <a href="{{ url('/contact') }}" style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#15803d,#22c55e);color:#fff;padding:9px 20px;border-radius:8px;font-size:.85rem;font-weight:600;text-decoration:none;">
              List Your Property <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>

      {{-- 3. Rent / Lease --}}
      <div class="col-lg-4 col-md-6">
        <div class="svc-card h-100" style="background:#fff;border-radius:18px;overflow:hidden;box-shadow:0 4px 20px rgba(10,45,94,.09);transition:transform .25s,box-shadow .25s;" onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 12px 32px rgba(10,45,94,.15)'" onmouseout="this.style.transform='';this.style.boxShadow='0 4px 20px rgba(10,45,94,.09)'">
          <div style="height:180px;overflow:hidden;position:relative;">
            <img src="{{ asset('assets/img/real-estate/property-interior-5.webp') }}" alt="Rent" style="width:100%;height:100%;object-fit:cover;">
            <div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 40%,rgba(10,45,94,.7));"></div>
            <div style="position:absolute;bottom:14px;left:16px;background:#7c3aed;color:#fff;font-size:.75rem;font-weight:700;padding:4px 12px;border-radius:20px;">RENTING</div>
          </div>
          <div style="padding:22px;">
            <div style="width:46px;height:46px;background:#ede9fe;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:14px;">
              <i class="bi bi-key" style="font-size:1.4rem;color:#7c3aed;"></i>
            </div>
            <h4 style="font-weight:700;color:#0a2d5e;margin-bottom:8px;">Rent & Lease</h4>
            <p style="color:#64748b;font-size:.9rem;line-height:1.7;margin-bottom:14px;">Find your perfect rental — fully furnished apartments, PG accommodations, or long-term leases across all major cities.</p>
            <ul style="list-style:none;padding:0;margin-bottom:18px;">
              @foreach(['Flats, villas, PG & co-living','Verified landlords & tenants','Rental agreement drafting','Zero brokerage options available'] as $f)
              <li style="font-size:.85rem;color:#475569;padding:3px 0;display:flex;gap:8px;align-items:flex-start;">
                <i class="bi bi-check-circle-fill" style="color:#16a34a;margin-top:2px;flex-shrink:0;"></i> {{ $f }}
              </li>
              @endforeach
            </ul>
            <a href="{{ url('/properties?looking_for=Rent') }}" style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#6d28d9,#8b5cf6);color:#fff;padding:9px 20px;border-radius:8px;font-size:.85rem;font-weight:600;text-decoration:none;">
              Find Rentals <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>

      {{-- 4. Home Loan --}}
      <div class="col-lg-4 col-md-6">
        <div class="svc-card h-100" style="background:#fff;border-radius:18px;overflow:hidden;box-shadow:0 4px 20px rgba(10,45,94,.09);transition:transform .25s,box-shadow .25s;" onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 12px 32px rgba(10,45,94,.15)'" onmouseout="this.style.transform='';this.style.boxShadow='0 4px 20px rgba(10,45,94,.09)'">
          <div style="height:180px;overflow:hidden;position:relative;">
            <img src="{{ asset('assets/img/real-estate/features-2.webp') }}" alt="Home Loan" style="width:100%;height:100%;object-fit:cover;">
            <div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 40%,rgba(10,45,94,.7));"></div>
            <div style="position:absolute;bottom:14px;left:16px;background:#b45309;color:#fff;font-size:.75rem;font-weight:700;padding:4px 12px;border-radius:20px;">HOME LOAN</div>
          </div>
          <div style="padding:22px;">
            <div style="width:46px;height:46px;background:#fef3c7;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:14px;">
              <i class="bi bi-bank" style="font-size:1.4rem;color:#d97706;"></i>
            </div>
            <h4 style="font-weight:700;color:#0a2d5e;margin-bottom:8px;">Home Loan Assistance</h4>
            <p style="color:#64748b;font-size:.9rem;line-height:1.7;margin-bottom:14px;">Get the best home loan rates from 25+ banks and NBFCs. Our loan advisors compare offers and handle your entire application.</p>
            <ul style="list-style:none;padding:0;margin-bottom:18px;">
              @foreach(['Compare rates from SBI, HDFC, ICICI & more','Instant eligibility check (in 2 mins)','Loan up to ₹10 Cr at 8.35%* p.a.','Doorstep document collection'] as $f)
              <li style="font-size:.85rem;color:#475569;padding:3px 0;display:flex;gap:8px;align-items:flex-start;">
                <i class="bi bi-check-circle-fill" style="color:#16a34a;margin-top:2px;flex-shrink:0;"></i> {{ $f }}
              </li>
              @endforeach
            </ul>
            <a href="{{ url('/contact') }}" style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#92400e,#d97706);color:#fff;padding:9px 20px;border-radius:8px;font-size:.85rem;font-weight:600;text-decoration:none;">
              Check Eligibility <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>

      {{-- 5. Property Insurance --}}
      <div class="col-lg-4 col-md-6">
        <div class="svc-card h-100" style="background:#fff;border-radius:18px;overflow:hidden;box-shadow:0 4px 20px rgba(10,45,94,.09);transition:transform .25s,box-shadow .25s;" onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 12px 32px rgba(10,45,94,.15)'" onmouseout="this.style.transform='';this.style.boxShadow='0 4px 20px rgba(10,45,94,.09)'">
          <div style="height:180px;overflow:hidden;position:relative;">
            <img src="{{ asset('assets/img/real-estate/features-1.webp') }}" alt="Insurance" style="width:100%;height:100%;object-fit:cover;">
            <div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 40%,rgba(10,45,94,.7));"></div>
            <div style="position:absolute;bottom:14px;left:16px;background:#0f766e;color:#fff;font-size:.75rem;font-weight:700;padding:4px 12px;border-radius:20px;">INSURANCE</div>
          </div>
          <div style="padding:22px;">
            <div style="width:46px;height:46px;background:#ccfbf1;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:14px;">
              <i class="bi bi-shield-lock" style="font-size:1.4rem;color:#0f766e;"></i>
            </div>
            <h4 style="font-weight:700;color:#0a2d5e;margin-bottom:8px;">Property Insurance</h4>
            <p style="color:#64748b;font-size:.9rem;line-height:1.7;margin-bottom:14px;">Protect your property and belongings with comprehensive home insurance. Covers structure, contents, natural disasters and more.</p>
            <ul style="list-style:none;padding:0;margin-bottom:18px;">
              @foreach(['Structure & content insurance','Fire, flood & earthquake cover','Theft & burglary protection','Instant online policy issuance'] as $f)
              <li style="font-size:.85rem;color:#475569;padding:3px 0;display:flex;gap:8px;align-items:flex-start;">
                <i class="bi bi-check-circle-fill" style="color:#16a34a;margin-top:2px;flex-shrink:0;"></i> {{ $f }}
              </li>
              @endforeach
            </ul>
            <a href="{{ url('/contact') }}" style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#0d5c57,#0f766e);color:#fff;padding:9px 20px;border-radius:8px;font-size:.85rem;font-weight:600;text-decoration:none;">
              Get a Quote <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>

      {{-- 6. Legal & Documentation --}}
      <div class="col-lg-4 col-md-6">
        <div class="svc-card h-100" style="background:#fff;border-radius:18px;overflow:hidden;box-shadow:0 4px 20px rgba(10,45,94,.09);transition:transform .25s,box-shadow .25s;" onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 12px 32px rgba(10,45,94,.15)'" onmouseout="this.style.transform='';this.style.boxShadow='0 4px 20px rgba(10,45,94,.09)'">
          <div style="height:180px;overflow:hidden;position:relative;">
            <img src="{{ asset('assets/img/real-estate/features-3.webp') }}" alt="Legal" style="width:100%;height:100%;object-fit:cover;">
            <div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 40%,rgba(10,45,94,.7));"></div>
            <div style="position:absolute;bottom:14px;left:16px;background:#be185d;color:#fff;font-size:.75rem;font-weight:700;padding:4px 12px;border-radius:20px;">LEGAL</div>
          </div>
          <div style="padding:22px;">
            <div style="width:46px;height:46px;background:#fce7f3;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:14px;">
              <i class="bi bi-file-earmark-text" style="font-size:1.4rem;color:#be185d;"></i>
            </div>
            <h4 style="font-weight:700;color:#0a2d5e;margin-bottom:8px;">Legal & Documentation</h4>
            <p style="color:#64748b;font-size:.9rem;line-height:1.7;margin-bottom:14px;">Our empanelled legal experts handle all property documentation — from title verification to sale deed registration and RERA compliance.</p>
            <ul style="list-style:none;padding:0;margin-bottom:18px;">
              @foreach(['Title search & encumbrance check','Sale deed & agreement drafting','Property registration assistance','RERA & legal compliance check'] as $f)
              <li style="font-size:.85rem;color:#475569;padding:3px 0;display:flex;gap:8px;align-items:flex-start;">
                <i class="bi bi-check-circle-fill" style="color:#16a34a;margin-top:2px;flex-shrink:0;"></i> {{ $f }}
              </li>
              @endforeach
            </ul>
            <a href="{{ url('/contact') }}" style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#9d174d,#be185d);color:#fff;padding:9px 20px;border-radius:8px;font-size:.85rem;font-weight:600;text-decoration:none;">
              Talk to Expert <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>

    </div>{{-- /row --}}
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     ADDITIONAL SERVICES – ICON GRID
═══════════════════════════════════════════════════════ --}}
<section style="background:#f8faff;padding:60px 0;">
  <div class="container">
    <div class="text-center mb-5">
      <span style="background:#ede9fe;color:#7c3aed;font-size:.78rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;padding:4px 14px;border-radius:20px;">More Services</span>
      <h2 style="font-size:1.9rem;font-weight:800;color:#0a2d5e;margin-top:12px;">Complete Real Estate Support</h2>
      <p style="color:#64748b;max-width:580px;margin:8px auto 0;">Beyond buying and selling, we offer a full suite of value-added services to make your real estate journey seamless.</p>
    </div>

    <div class="row g-3">
      @php
        $addons = [
          ['icon'=>'bi-graph-up-arrow','bg'=>'#dbeafe','ic'=>'#1d4ed8','title'=>'Property Valuation',
           'desc'=>'Get an accurate market valuation report for your property within 24 hours, backed by comparable sales data and local market intelligence.'],
          ['icon'=>'bi-globe','bg'=>'#fef3c7','ic'=>'#d97706','title'=>'NRI Services',
           'desc'=>'Specialized support for Non-Resident Indians — property purchase, POA execution, rental income management and repatriation of funds.'],
          ['icon'=>'bi-buildings','bg'=>'#dcfce7','ic'=>'#16a34a','title'=>'Commercial Real Estate',
           'desc'=>'Find office spaces, retail shops, warehouses and co-working spaces. We help businesses scale with the right commercial property.'],
          ['icon'=>'bi-house-gear','bg'=>'#fce7f3','ic'=>'#be185d','title'=>'Property Management',
           'desc'=>'Let us manage your rental property — tenant sourcing, rent collection, maintenance coordination and monthly reports.'],
          ['icon'=>'bi-palette','bg'=>'#ccfbf1','ic'=>'#0f766e','title'=>'Interior Design',
           'desc'=>'Transform your new home with our empanelled interior designers. From modular kitchens to complete home interiors at competitive prices.'],
          ['icon'=>'bi-calculator','bg'=>'#ede9fe','ic'=>'#7c3aed','title'=>'Investment Advisory',
           'desc'=>'Data-driven investment advice — identify high-growth micro-markets, compare ROI projections and build a diversified property portfolio.'],
          ['icon'=>'bi-camera-video','bg'=>'#fef9c3','ic'=>'#ca8a04','title'=>'Virtual Tours',
           'desc'=>'Visit properties from anywhere in the world with immersive 360° virtual tours and live video walkthroughs with agents.'],
          ['icon'=>'bi-hammer','bg'=>'#fee2e2','ic'=>'#dc2626','title'=>'Home Renovation',
           'desc'=>'Connect with verified contractors for painting, plumbing, electrical and full home renovation at transparent, pre-agreed rates.'],
        ];
      @endphp
      @foreach($addons as $a)
      <div class="col-lg-3 col-md-6">
        <div style="background:#fff;border-radius:14px;padding:22px;height:100%;box-shadow:0 2px 12px rgba(10,45,94,.07);border:1px solid #e8eef6;transition:box-shadow .2s,transform .2s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 24px rgba(10,45,94,.14)'" onmouseout="this.style.transform='';this.style.boxShadow='0 2px 12px rgba(10,45,94,.07)'">
          <div style="width:48px;height:48px;background:{{ $a['bg'] }};border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:14px;">
            <i class="bi {{ $a['icon'] }}" style="font-size:1.4rem;color:{{ $a['ic'] }};"></i>
          </div>
          <h5 style="font-weight:700;color:#0a2d5e;margin-bottom:8px;font-size:.95rem;">{{ $a['title'] }}</h5>
          <p style="color:#64748b;font-size:.83rem;line-height:1.65;margin:0;">{{ $a['desc'] }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     HOME LOAN BANNER (HIGHLIGHTED)
═══════════════════════════════════════════════════════ --}}
<section style="padding:60px 0;background:#fff;">
  <div class="container">
    <div style="background:linear-gradient(135deg,#0a2d5e 0%,#0f4c81 50%,#1565c0 100%);border-radius:20px;overflow:hidden;position:relative;">
      <div style="position:absolute;top:-40px;right:-40px;width:250px;height:250px;background:rgba(255,255,255,.04);border-radius:50%;"></div>
      <div style="position:absolute;bottom:-60px;left:-60px;width:300px;height:300px;background:rgba(255,255,255,.03);border-radius:50%;"></div>
      <div class="row g-0 align-items-center">
        <div class="col-lg-7" style="padding:40px 48px;">
          <span style="background:rgba(255,255,255,.12);color:#50e6ff;font-size:.78rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;padding:4px 14px;border-radius:20px;">Home Loan Calculator</span>
          <h2 style="color:#fff;font-weight:800;font-size:2rem;margin:16px 0 8px;">How Much Can You Borrow?</h2>
          <p style="color:rgba(255,255,255,.75);line-height:1.7;margin-bottom:24px;">Check your home loan eligibility in 2 minutes. We partner with SBI, HDFC, ICICI, Kotak, Axis, LIC Housing and 20+ other lenders to give you the best rate.</p>
          <div class="row g-3 mb-28">
            @php
              $lenders = [
                ['name'=>'SBI','rate'=>'8.50%'],['name'=>'HDFC','rate'=>'8.70%'],
                ['name'=>'ICICI','rate'=>'8.75%'],['name'=>'Kotak','rate'=>'8.85%'],
              ];
            @endphp
            @foreach($lenders as $l)
            <div class="col-6 col-md-3">
              <div style="background:rgba(255,255,255,.1);border-radius:10px;padding:10px;text-align:center;backdrop-filter:blur(4px);">
                <div style="color:#50e6ff;font-weight:800;font-size:1.1rem;">{{ $l['rate'] }}</div>
                <div style="color:rgba(255,255,255,.65);font-size:.75rem;">{{ $l['name'] }} p.a.</div>
              </div>
            </div>
            @endforeach
          </div>
          <a href="{{ url('/contact') }}" style="display:inline-flex;align-items:center;gap:8px;background:#50e6ff;color:#0a2d5e;padding:12px 28px;border-radius:10px;font-weight:700;font-size:.95rem;text-decoration:none;margin-top:20px;">
            <i class="bi bi-bank"></i> Check My Eligibility Free
          </a>
        </div>
        <div class="col-lg-5 d-none d-lg-block">
          <img src="{{ asset('assets/img/real-estate/property-exterior-4.webp') }}" alt="Home Loan" style="width:100%;height:340px;object-fit:cover;opacity:.6;">
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     HOW IT WORKS
═══════════════════════════════════════════════════════ --}}
<section style="background:#eef5fb;padding:60px 0;">
  <div class="container">
    <div class="text-center mb-5">
      <span style="background:#dbeafe;color:#1d4ed8;font-size:.78rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;padding:4px 14px;border-radius:20px;">Process</span>
      <h2 style="font-size:1.9rem;font-weight:800;color:#0a2d5e;margin-top:12px;">How We Work</h2>
    </div>
    <div class="row g-4 justify-content-center">
      @php
        $steps = [
          ['num'=>'01','icon'=>'bi-chat-dots','title'=>'Tell Us Your Need','desc'=>'Share what you\'re looking for — buying, selling, renting, loan or any other service. Our team reaches out within 30 minutes.'],
          ['num'=>'02','icon'=>'bi-person-check','title'=>'Get Matched with Expert','desc'=>'We assign a dedicated relationship manager who specialises in your requirement and local market.'],
          ['num'=>'03','icon'=>'bi-search','title'=>'Explore Options','desc'=>'Your expert curates the best properties or service options matching your budget, timeline and preferences.'],
          ['num'=>'04','icon'=>'bi-patch-check','title'=>'Close with Confidence','desc'=>'We handle negotiations, paperwork and coordination so you can close the deal hassle-free.'],
        ];
      @endphp
      @foreach($steps as $i => $step)
      <div class="col-lg-3 col-md-6 text-center">
        <div style="position:relative;">
          @if($i < count($steps)-1)
          <div class="d-none d-lg-block" style="position:absolute;top:34px;right:-12%;width:24%;height:2px;background:linear-gradient(90deg,#0078d4,#50e6ff);z-index:0;"></div>
          @endif
          <div style="width:68px;height:68px;background:linear-gradient(135deg,#0a2d5e,#0078d4);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;position:relative;z-index:1;box-shadow:0 4px 16px rgba(0,120,212,.3);">
            <i class="bi {{ $step['icon'] }}" style="font-size:1.6rem;color:#fff;"></i>
          </div>
          <div style="font-size:.7rem;color:#0078d4;font-weight:700;letter-spacing:1px;margin-bottom:6px;">STEP {{ $step['num'] }}</div>
          <h5 style="font-weight:700;color:#0a2d5e;margin-bottom:8px;">{{ $step['title'] }}</h5>
          <p style="color:#64748b;font-size:.85rem;line-height:1.65;max-width:220px;margin:0 auto;">{{ $step['desc'] }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     FAQ
═══════════════════════════════════════════════════════ --}}
<section style="padding:60px 0;background:#fff;">
  <div class="container">
    <div class="row">
      <div class="col-lg-5 mb-4 mb-lg-0">
        <span style="background:#dbeafe;color:#1d4ed8;font-size:.78rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;padding:4px 14px;border-radius:20px;">FAQ</span>
        <h2 style="font-size:1.9rem;font-weight:800;color:#0a2d5e;margin:14px 0 12px;">Frequently Asked Questions</h2>
        <p style="color:#64748b;line-height:1.75;margin-bottom:24px;">Have a question about our services? Browse common queries or reach out to our team directly.</p>
        <a href="{{ url('/contact') }}" style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#0a2d5e,#0078d4);color:#fff;padding:11px 24px;border-radius:10px;font-weight:600;font-size:.9rem;text-decoration:none;">
          <i class="bi bi-headset"></i> Talk to Support
        </a>
      </div>
      <div class="col-lg-7">
        <div class="accordion" id="svcFaq">
          @php
            $faqs = [
              ['q'=>'How do I get a free property valuation?','a'=>'Contact our team via the form or call us. Our experts visit your property and provide a detailed market valuation report — considering recent comparable sales, location, amenities and current demand — usually within 24–48 hours.'],
              ['q'=>'What documents are needed to sell a property?','a'=>'Typically: original sale deed / title deed, Encumbrance Certificate (EC), Property Tax receipts (last 3 years), Khata certificate, NOC from society (if apartment), PAN card, Aadhar and latest utility bills. Our legal team guides you through each step.'],
              ['q'=>'How does the home loan assistance work?','a'=>'Share your income details and the property you want to buy. We compare offers from 25+ lenders and recommend the best rate. Our loan executive handles the application, document submission and follow-up — you don\'t have to visit any bank.'],
              ['q'=>'Is property insurance mandatory for a home loan?','a'=>'While not legally mandatory, most banks require basic home insurance when sanctioning a loan. It protects both you and the lender. We help you get the right cover at the best premium.'],
              ['q'=>'Do you handle NRI property transactions?','a'=>'Yes. We have a dedicated NRI desk. Services include property purchase/sale under FEMA guidelines, Power of Attorney execution, rental management, and fund repatriation assistance. We work with your NRE/NRO accounts.'],
              ['q'=>'What is your fee for property management?','a'=>'Our property management fee is typically 8–10% of the monthly rent. This covers tenant sourcing, rent collection, maintenance coordination, periodic inspections and monthly reporting to the owner.'],
            ];
          @endphp
          @foreach($faqs as $i => $faq)
          <div class="accordion-item" style="border:1px solid #e2e8f0;border-radius:12px;margin-bottom:10px;overflow:hidden;">
            <h2 class="accordion-header">
              <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $i }}" style="background:#fff;color:#0a2d5e;font-weight:600;font-size:.9rem;box-shadow:none;padding:16px 20px;">
                {{ $faq['q'] }}
              </button>
            </h2>
            <div id="faq{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}" data-bs-parent="#svcFaq">
              <div class="accordion-body" style="color:#64748b;font-size:.88rem;line-height:1.75;padding:0 20px 18px;">
                {{ $faq['a'] }}
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     CTA – BOTTOM (Contact Form)
═══════════════════════════════════════════════════════ --}}
<section style="padding:60px 0;background:#eef5fb;">
  <div class="container">
    <div style="background:linear-gradient(135deg,#0a2d5e,#1565c0);border-radius:20px;padding:48px 40px;position:relative;overflow:hidden;">
      <div style="position:absolute;top:-50px;left:50%;transform:translateX(-50%);width:500px;height:500px;background:rgba(255,255,255,.03);border-radius:50%;pointer-events:none;"></div>

      <div class="row align-items-center g-5" style="position:relative;z-index:1;">

        {{-- Left: Heading + info --}}
        <div class="col-lg-5 text-white">
          <i class="bi bi-telephone-fill" style="font-size:2.4rem;color:#50e6ff;"></i>
          <h2 style="font-weight:800;font-size:2rem;margin:12px 0 10px;">Ready to Get Started?</h2>
          <p style="color:rgba(255,255,255,.78);line-height:1.8;margin-bottom:24px;">Our team of 200+ real estate professionals is available 6 days a week. Drop us a message and we'll reach out within 30 minutes.</p>
          <div class="d-flex flex-column gap-3">
            <div class="d-flex align-items-center gap-3">
              <div style="width:42px;height:42px;background:rgba(255,255,255,.1);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-telephone-fill" style="color:#50e6ff;"></i>
              </div>
              <div>
                <div style="font-size:.78rem;color:rgba(255,255,255,.55);text-transform:uppercase;letter-spacing:.5px;">Call Us</div>
                <a href="tel:+91{{ config('app.contact_phone','7340753780') }}" style="color:#fff;font-weight:600;text-decoration:none;">+91 {{ config('app.contact_phone','7340753780') }}</a>
              </div>
            </div>
            <div class="d-flex align-items-center gap-3">
              <div style="width:42px;height:42px;background:rgba(255,255,255,.1);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-envelope-fill" style="color:#50e6ff;"></i>
              </div>
              <div>
                <div style="font-size:.78rem;color:rgba(255,255,255,.55);text-transform:uppercase;letter-spacing:.5px;">Email Us</div>
                <a href="mailto:{{ config('app.contact_email','admin@indianesthub.com') }}" style="color:#fff;font-weight:600;text-decoration:none;">{{ config('app.contact_email','admin@indianesthub.com') }}</a>
              </div>
            </div>
          </div>
        </div>

        {{-- Right: Contact Form --}}
        <div class="col-lg-7">
          <div style="background:rgba(255,255,255,.07);backdrop-filter:blur(10px);border-radius:16px;padding:32px;border:1px solid rgba(255,255,255,.12);">

            @if(session('success'))
              <div class="alert mb-3 py-2 px-3" style="background:rgba(80,230,255,.15);border:1px solid rgba(80,230,255,.3);border-radius:10px;color:#50e6ff;font-size:.9rem;">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
              </div>
            @endif

            <h5 style="color:#fff;font-weight:700;margin-bottom:20px;"><i class="bi bi-chat-dots me-2" style="color:#50e6ff;"></i>Send Us a Message</h5>

            <form action="{{ route('contact.store') }}" method="POST">
              @csrf
              <div class="row g-3">

                <div class="col-md-6">
                  <label style="color:rgba(255,255,255,.7);font-size:.82rem;font-weight:600;margin-bottom:6px;display:block;">Your Name <span style="color:#f87171;">*</span></label>
                  <input type="text" name="name" value="{{ old('name') }}"
                    placeholder="John Doe"
                    required
                    style="width:100%;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:8px;padding:10px 14px;color:#fff;font-size:.9rem;outline:none;"
                    onfocus="this.style.borderColor='#50e6ff';this.style.background='rgba(255,255,255,.15)'"
                    onblur="this.style.borderColor='rgba(255,255,255,.2)';this.style.background='rgba(255,255,255,.1)'">
                  @error('name')<div style="color:#fca5a5;font-size:.78rem;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                  <label style="color:rgba(255,255,255,.7);font-size:.82rem;font-weight:600;margin-bottom:6px;display:block;">Email Address <span style="color:#f87171;">*</span></label>
                  <input type="email" name="email" value="{{ old('email') }}"
                    placeholder="you@example.com"
                    required
                    style="width:100%;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:8px;padding:10px 14px;color:#fff;font-size:.9rem;outline:none;"
                    onfocus="this.style.borderColor='#50e6ff';this.style.background='rgba(255,255,255,.15)'"
                    onblur="this.style.borderColor='rgba(255,255,255,.2)';this.style.background='rgba(255,255,255,.1)'">
                  @error('email')<div style="color:#fca5a5;font-size:.78rem;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                  <label style="color:rgba(255,255,255,.7);font-size:.82rem;font-weight:600;margin-bottom:6px;display:block;">Subject <span style="color:#f87171;">*</span></label>
                  <input type="text" name="subject" value="{{ old('subject') }}"
                    placeholder="e.g. I need help finding a property"
                    required
                    style="width:100%;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:8px;padding:10px 14px;color:#fff;font-size:.9rem;outline:none;"
                    onfocus="this.style.borderColor='#50e6ff';this.style.background='rgba(255,255,255,.15)'"
                    onblur="this.style.borderColor='rgba(255,255,255,.2)';this.style.background='rgba(255,255,255,.1)'">
                  @error('subject')<div style="color:#fca5a5;font-size:.78rem;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                  <label style="color:rgba(255,255,255,.7);font-size:.82rem;font-weight:600;margin-bottom:6px;display:block;">Message <span style="color:#f87171;">*</span></label>
                  <textarea name="message" rows="4"
                    placeholder="Tell us how we can help you…"
                    required
                    style="width:100%;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:8px;padding:10px 14px;color:#fff;font-size:.9rem;outline:none;resize:vertical;"
                    onfocus="this.style.borderColor='#50e6ff';this.style.background='rgba(255,255,255,.15)'"
                    onblur="this.style.borderColor='rgba(255,255,255,.2)';this.style.background='rgba(255,255,255,.1)'">{{ old('message') }}</textarea>
                  @error('message')<div style="color:#fca5a5;font-size:.78rem;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                  <button type="submit"
                    style="background:#50e6ff;color:#0a2d5e;padding:12px 32px;border-radius:10px;font-weight:700;font-size:.95rem;border:none;cursor:pointer;transition:opacity .2s;width:100%;"
                    onmouseover="this.style.opacity='.88'"
                    onmouseout="this.style.opacity='1'">
                    <i class="bi bi-send me-2"></i>Send Message
                  </button>
                </div>

              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
