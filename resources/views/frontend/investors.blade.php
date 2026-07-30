@extends('frontend.layout')

{{-- ════════════════════════ SEO META ════════════════════════ --}}
@section('title', 'Investor Relations | ' . config('app.name') . ' – Investment Opportunity')
@section('meta_description', 'Explore an investment opportunity with ' . config('app.name') . ', a growing real estate platform in Chandigarh Tricity. View our vision, traction, business model and funding requirement.')
@section('canonical', route('investors'))
@section('robots', 'index, follow')
@section('og_title', 'Invest in ' . config('app.name') . ' | Investor Relations')
@section('og_description', 'We are inviting angel investors, VCs and strategic partners to join ' . config('app.name') . ' on our growth journey in the Indian real estate market.')

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Investors","item":"{{ route('investors') }}"}
  ]
}
</script>
@endsection

@section('content')
<main class="main">

  {{-- ══════════════════════════════════════
       HERO
  ══════════════════════════════════════ --}}
  <div style="background:linear-gradient(135deg,#0a2d5e 0%,#0078d4 100%); padding:90px 0 70px; position:relative; overflow:hidden;">
    <div class="container" style="position:relative; z-index:2;">
      <div class="row align-items-center">
        <div class="col-lg-8 mx-auto text-center">
          <span style="display:inline-block; background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.3); color:#fff; padding:6px 18px; border-radius:30px; font-size:.8rem; font-weight:700; letter-spacing:.5px; margin-bottom:18px;">
            <i class="bi bi-graph-up-arrow me-1"></i> Investment Opportunity
          </span>
          <h1 style="color:#fff; font-weight:800; font-size:2.4rem; margin-bottom:16px;">
            Join Us in Building India's Next-Gen Real Estate Platform
          </h1>
          <p style="color:rgba(255,255,255,.85); font-size:1.05rem; max-width:640px; margin:0 auto 28px;">
            {{ config('app.name') }} is raising capital to accelerate growth across Chandigarh Tricity and beyond.
            We're looking for strategic investors who share our vision of making property discovery
            simple, transparent and trustworthy for every Indian.
          </p>
          <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="#investor-contact" class="btn btn-warning px-4 py-2 fw-700">
              <i class="bi bi-envelope me-1"></i> Get in Touch
            </a>
            <a href="#overview" class="btn btn-outline-light px-4 py-2 fw-700">
              <i class="bi bi-file-earmark-text me-1"></i> View Overview
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ══════════════════════════════════════
       VISION
  ══════════════════════════════════════ --}}
  <section id="overview" style="padding:64px 0;">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-9">
          <span style="color:#0078d4; font-weight:700; font-size:.8rem; letter-spacing:1px; text-transform:uppercase;">Our Vision</span>
          <h2 style="font-weight:800; color:#0a2d5e; margin:8px 0 18px;">Making Real Estate Simple, Trusted &amp; Accessible Across India</h2>
          <p style="color:#475569; font-size:1rem; line-height:1.8;">
            {{ config('app.name') }} started in Chandigarh Tricity with a simple goal: help people find verified,
            trustworthy properties without the confusion, fake listings and endless broker calls that plague
            Indian real estate today. We're building a platform that connects buyers, tenants, sellers, dealers
            and builders on one transparent marketplace — and we intend to take this model to other high-growth
            cities across India.
          </p>
        </div>
      </div>
    </div>
  </section>

  {{-- ══════════════════════════════════════
       PROBLEM
  ══════════════════════════════════════ --}}
  <section style="padding:56px 0; background:#f8fafc;">
    <div class="container">
      <div class="row align-items-center gy-4">
        <div class="col-lg-6">
          <span style="color:#0078d4; font-weight:700; font-size:.8rem; letter-spacing:1px; text-transform:uppercase;">The Problem</span>
          <h2 style="font-weight:800; color:#0a2d5e; margin:8px 0 18px;">Real Estate in India Is Still Broken</h2>
          <ul style="color:#475569; font-size:.96rem; line-height:1.9; padding-left:18px;">
            <li>Duplicate and fake listings waste buyers' time and erode trust.</li>
            <li>Unverified dealers and brokers create friction and risk for both sides.</li>
            <li>Buyers lack access to financing, legal and insurance help in one place.</li>
            <li>Regional markets (Tier-2 cities like the Chandigarh Tricity belt) are underserved by the big national portals.</li>
          </ul>
        </div>
        <div class="col-lg-6">
          <span style="color:#0078d4; font-weight:700; font-size:.8rem; letter-spacing:1px; text-transform:uppercase;">Our Solution</span>
          <h2 style="font-weight:800; color:#0a2d5e; margin:8px 0 18px;">A Verified, End-to-End Property Marketplace</h2>
          <ul style="color:#475569; font-size:.96rem; line-height:1.9; padding-left:18px;">
            <li>Every listing and dealer is verified before going live on the platform.</li>
            <li>Buy, rent, PG/co-living and renovation — all in one place.</li>
            <li>Integrated home loan, property insurance and legal assistance.</li>
            <li>Hyperlocal focus with room to expand city-by-city across India.</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  {{-- ══════════════════════════════════════
       MARKET OPPORTUNITY
  ══════════════════════════════════════ --}}
  <section style="padding:60px 0;">
    <div class="container">
      <div class="text-center mb-4">
        <span style="color:#0078d4; font-weight:700; font-size:.8rem; letter-spacing:1px; text-transform:uppercase;">Market Opportunity</span>
        <h2 style="font-weight:800; color:#0a2d5e; margin:8px 0;">A Large &amp; Fast-Growing Market</h2>
        <p style="color:#64748b; max-width:640px; margin:0 auto;">India's real estate sector is one of the largest and fastest-growing in the world, with online property search adoption still rising sharply outside the top metros.</p>
      </div>
      <div class="row gy-4 text-center">
        <div class="col-md-4">
          <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:28px 20px; height:100%;">
            <div style="font-size:1.8rem; font-weight:800; color:#0a2d5e;">TAM</div>
            <p style="color:#64748b; font-size:.85rem; margin:0;">Total Addressable Market — Indian online real estate classifieds &amp; services sector</p>
          </div>
        </div>
        <div class="col-md-4">
          <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:28px 20px; height:100%;">
            <div style="font-size:1.8rem; font-weight:800; color:#0a2d5e;">SAM</div>
            <p style="color:#64748b; font-size:.85rem; margin:0;">Serviceable Available Market — Tier-2 city real estate portals &amp; related services</p>
          </div>
        </div>
        <div class="col-md-4">
          <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:28px 20px; height:100%;">
            <div style="font-size:1.8rem; font-weight:800; color:#0a2d5e;">SOM</div>
            <p style="color:#64748b; font-size:.85rem; margin:0;">Serviceable Obtainable Market — Chandigarh Tricity &amp; adjoining regions in the near term</p>
          </div>
        </div>
      </div>
      <p class="text-center mt-4" style="color:#94a3b8; font-size:.82rem;">
        <i class="bi bi-info-circle me-1"></i>Detailed market sizing figures and sources are shared directly with serious investors — request our investor deck below.
      </p>
    </div>
  </section>

  {{-- ══════════════════════════════════════
       TRACTION
  ══════════════════════════════════════ --}}
  <section style="padding:56px 0; background:#0a2d5e;">
    <div class="container">
      <div class="text-center mb-5">
        <span style="color:#50e6ff; font-weight:700; font-size:.8rem; letter-spacing:1px; text-transform:uppercase;">Current Traction</span>
        <h2 style="font-weight:800; color:#fff; margin:8px 0;">Where We Stand Today</h2>
      </div>
      <div class="row gy-4 text-center">
        <div class="col-lg-3 col-6">
          <div style="font-size:2.2rem; font-weight:800; color:#50e6ff;">500+</div>
          <div style="color:rgba(255,255,255,.75); font-size:.85rem;">Verified Properties Listed</div>
        </div>
        <div class="col-lg-3 col-6">
          <div style="font-size:2.2rem; font-weight:800; color:#fbbf24;">200+</div>
          <div style="color:rgba(255,255,255,.75); font-size:.85rem;">Registered Dealers &amp; Agents</div>
        </div>
        <div class="col-lg-3 col-6">
          <div style="font-size:2.2rem; font-weight:800; color:#86efac;">25+</div>
          <div style="color:rgba(255,255,255,.75); font-size:.85rem;">Localities Covered</div>
        </div>
        <div class="col-lg-3 col-6">
          <div style="font-size:2.2rem; font-weight:800; color:#f472b6;">Growing</div>
          <div style="color:rgba(255,255,255,.75); font-size:.85rem;">Monthly Visitors &amp; Enquiries</div>
        </div>
      </div>
      <p class="text-center mt-4" style="color:rgba(255,255,255,.6); font-size:.82rem;">
        <i class="bi bi-lock me-1"></i>Detailed traffic, revenue and growth-rate figures are shared under NDA during investor conversations.
      </p>
    </div>
  </section>
  {{--
    NOTE FOR SITE OWNER: Replace the placeholder numbers above with your real, current figures
    before this page goes live for investors. Add revenue and month-over-month growth rate
    once you're comfortable sharing them publicly (or keep them under NDA as above).
  --}}

  {{-- ══════════════════════════════════════
       BUSINESS MODEL
  ══════════════════════════════════════ --}}
  <section style="padding:60px 0;">
    <div class="container">
      <div class="text-center mb-4">
        <span style="color:#0078d4; font-weight:700; font-size:.8rem; letter-spacing:1px; text-transform:uppercase;">Business Model</span>
        <h2 style="font-weight:800; color:#0a2d5e; margin:8px 0;">How We Make Money</h2>
      </div>
      <div class="row gy-4">
        <div class="col-md-3 col-6">
          <div style="text-align:center;">
            <i class="bi bi-star" style="font-size:1.8rem; color:#0078d4;"></i>
            <h5 style="margin:12px 0 6px; font-weight:700; color:#0a2d5e;">Featured Listings</h5>
            <p style="color:#64748b; font-size:.85rem;">Owners &amp; dealers pay for premium placement and boosted visibility.</p>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div style="text-align:center;">
            <i class="bi bi-person-badge" style="font-size:1.8rem; color:#0078d4;"></i>
            <h5 style="margin:12px 0 6px; font-weight:700; color:#0a2d5e;">Dealer Subscriptions</h5>
            <p style="color:#64748b; font-size:.85rem;">Monthly &amp; annual plans for agents and dealers listing in bulk.</p>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div style="text-align:center;">
            <i class="bi bi-buildings" style="font-size:1.8rem; color:#0078d4;"></i>
            <h5 style="margin:12px 0 6px; font-weight:700; color:#0a2d5e;">Builder Packages</h5>
            <p style="color:#64748b; font-size:.85rem;">Per-project packages for builders showcasing new launches.</p>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div style="text-align:center;">
            <i class="bi bi-shield-check" style="font-size:1.8rem; color:#0078d4;"></i>
            <h5 style="margin:12px 0 6px; font-weight:700; color:#0a2d5e;">Financial &amp; Legal Services</h5>
            <p style="color:#64748b; font-size:.85rem;">Referral revenue from home loans, insurance and legal assistance.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- ══════════════════════════════════════
       FUNDING REQUIREMENT + USE OF FUNDS
  ══════════════════════════════════════ --}}
  <section style="padding:56px 0; background:#f8fafc;">
    <div class="container">
      <div class="row gy-5">
        <div class="col-lg-6">
          <span style="color:#0078d4; font-weight:700; font-size:.8rem; letter-spacing:1px; text-transform:uppercase;">Funding Requirement</span>
          <h2 style="font-weight:800; color:#0a2d5e; margin:8px 0 18px;">Our Funding Ask</h2>
          <p style="color:#475569; font-size:.96rem; line-height:1.8;">
            We are raising <strong>[funding amount]</strong> for <strong>[equity %]</strong> to accelerate product
            development, expand into new cities and grow our dealer &amp; builder network.
          </p>
          <p style="color:#94a3b8; font-size:.82rem;">
            <i class="bi bi-info-circle me-1"></i>Exact terms are shared directly with qualified investors after an initial conversation.
          </p>
        </div>
        <div class="col-lg-6">
          <span style="color:#0078d4; font-weight:700; font-size:.8rem; letter-spacing:1px; text-transform:uppercase;">Use of Funds</span>
          <h2 style="font-weight:800; color:#0a2d5e; margin:8px 0 18px;">Where the Capital Goes</h2>
          <div style="display:flex; flex-direction:column; gap:12px;">
            @php
            $uses = [
              ['label'=>'Technology & Platform Development','pct'=>35],
              ['label'=>'Marketing & City Expansion','pct'=>30],
              ['label'=>'Team & Operations','pct'=>20],
              ['label'=>'Working Capital & Contingency','pct'=>15],
            ];
            @endphp
            @foreach($uses as $u)
            <div>
              <div class="d-flex justify-content-between" style="font-size:.85rem; color:#0a2d5e; font-weight:600;">
                <span>{{ $u['label'] }}</span><span>{{ $u['pct'] }}%</span>
              </div>
              <div style="background:#e2e8f0; border-radius:20px; height:8px; overflow:hidden;">
                <div style="background:linear-gradient(90deg,#0078d4,#50e6ff); height:100%; width:{{ $u['pct'] }}%;"></div>
              </div>
            </div>
            @endforeach
          </div>
          <p style="color:#94a3b8; font-size:.78rem; margin-top:10px;">
            <i class="bi bi-info-circle me-1"></i>Illustrative breakdown — update with your actual allocation.
          </p>
        </div>
      </div>
    </div>
  </section>

  {{-- ══════════════════════════════════════
       FOUNDER
  ══════════════════════════════════════ --}}
  <section style="padding:70px 0; background:#f8fafc;">
    <div class="container-xl">
      <div class="text-center mb-5">
        <span style="color:#0078d4; font-weight:700; font-size:.8rem; letter-spacing:1px; text-transform:uppercase;">Leadership</span>
        <h2 style="font-weight:800; color:#0a2d5e; margin:8px 0;">Meet the Founder</h2>
      </div>

      <div style="background:#fff; border-radius:20px; padding:44px; box-shadow:0 10px 40px rgba(10,45,94,.10);">
        <div class="row g-5">

          {{-- LEFT: identity + bio + companies --}}
          <div class="col-lg-7">
            <div class="d-flex flex-column flex-md-row gap-4 align-items-start mb-3">
              <div style="width:90px; height:90px; min-width:90px; border-radius:50%; background:linear-gradient(135deg,#0a2d5e 0%,#0078d4 100%); color:#fff; display:flex; align-items:center; justify-content:center; font-size:1.8rem; font-weight:800; box-shadow:0 6px 16px rgba(10,45,94,.25);">
                PM
              </div>
              <div>
                <h4 style="font-weight:700; color:#0a2d5e; margin-bottom:2px; font-size:1.3rem;">Prakash Chandra Mishra</h4>
                <p style="color:#0078d4; font-size:.9rem; font-weight:600; margin-bottom:14px;">Founder &amp; CEO, {{ config('app.name') }}</p>

                <div class="d-flex flex-wrap gap-2 mb-3">
                  <span style="background:#eef4fb; color:#0a2d5e; padding:6px 14px; border-radius:20px; font-size:.78rem; font-weight:700;">
                    <i class="bi bi-briefcase me-1"></i> 18+ Years Experience
                  </span>
                  <span style="background:#eef4fb; color:#0a2d5e; padding:6px 14px; border-radius:20px; font-size:.78rem; font-weight:700;">
                    <i class="bi bi-people me-1"></i> Engineering Team Leadership
                  </span>
                  <span style="background:#eef4fb; color:#0a2d5e; padding:6px 14px; border-radius:20px; font-size:.78rem; font-weight:700;">
                    <i class="bi bi-diagram-3 me-1"></i> Microservices &amp; Cloud Architecture
                  </span>
                </div>
              </div>
            </div>

            <p style="color:#64748b; font-size:.92rem; line-height:1.75; margin-bottom:20px;">
              Prakash is a full-stack technologist and engineering leader who has architected and scaled
              web platforms for global companies across two decades. He has led cross-functional engineering
              teams, designed microservices architectures, and shipped production systems spanning
              PHP/Laravel, Node.js, Go and modern cloud infrastructure — the same technical foundation
              {{ config('app.name') }} is built on. From real-time POS systems to enterprise CRM platforms
              for U.S. institutions, his focus has stayed constant: software that's fast, reliable, and
              built to scale.
              <!-- TODO(Prakash): replace this bridging line with your own specific reason for starting
                   {{ config('app.name') }} — investors respond strongly to an authentic personal "why". -->
              After years building mission-critical systems for other companies, he set out to bring that
              same engineering discipline to India's real estate market — building {{ config('app.name') }}
              as the platform he wished existed for dealers, builders and buyers alike.
            </p>

            <p style="color:#94a3b8; font-size:.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; margin-bottom:10px;">
              Previously Built Platforms For
            </p>
            <div class="d-flex flex-wrap gap-2 mb-4">
              @foreach(['GoDaddy', 'Carsome', 'Motorola Mobility', '360Alumni', 'Lavu (MenuDrive)'] as $company)
                <span style="background:#fff; border:1px solid #e2e8f0; color:#334155; padding:7px 16px; border-radius:8px; font-size:.82rem; font-weight:600;">
                  <i class="bi bi-building me-1" style="color:#0078d4;"></i>{{ $company }}
                </span>
              @endforeach
            </div>

            <a href="https://www.linkedin.com/in/pcmishra22/" target="_blank" rel="noopener"
               style="display:inline-flex; align-items:center; gap:6px; background:#0a2d5e; color:#fff; padding:9px 18px; border-radius:8px; font-size:.85rem; font-weight:700; text-decoration:none;">
              <i class="bi bi-linkedin"></i> Connect on LinkedIn
            </a>
          </div>

          {{-- RIGHT: technologies & skills panel --}}
          <div class="col-lg-5">
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:16px; padding:28px; height:100%;">
              <h6 style="font-weight:800; color:#0a2d5e; margin-bottom:16px; font-size:.95rem;">
                <i class="bi bi-code-slash me-1"></i> Technologies &amp; Skills
              </h6>

              <p style="color:#94a3b8; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; margin-bottom:8px;">Backend</p>
              <div class="d-flex flex-wrap gap-2 mb-3">
                @foreach(['PHP', 'Laravel', 'Node.js', 'Go', 'Ruby on Rails', 'Python'] as $tech)
                  <span style="background:#0a2d5e; color:#fff; padding:5px 13px; border-radius:6px; font-size:.76rem; font-weight:600;">{{ $tech }}</span>
                @endforeach
              </div>

              <p style="color:#94a3b8; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; margin-bottom:8px;">Frontend</p>
              <div class="d-flex flex-wrap gap-2 mb-3">
                @foreach(['React.js', 'Vue.js', 'TypeScript', 'Angular.js'] as $tech)
                  <span style="background:#0078d4; color:#fff; padding:5px 13px; border-radius:6px; font-size:.76rem; font-weight:600;">{{ $tech }}</span>
                @endforeach
              </div>

              <p style="color:#94a3b8; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; margin-bottom:8px;">Infrastructure &amp; Data</p>
              <div class="d-flex flex-wrap gap-2 mb-3">
                @foreach(['Docker', 'Kubernetes', 'AWS', 'MySQL', 'MongoDB', 'Redis'] as $tech)
                  <span style="background:#334155; color:#fff; padding:5px 13px; border-radius:6px; font-size:.76rem; font-weight:600;">{{ $tech }}</span>
                @endforeach
              </div>

              <p style="color:#94a3b8; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; margin-bottom:8px;">Messaging &amp; APIs</p>
              <div class="d-flex flex-wrap gap-2">
                @foreach(['RabbitMQ', 'Kafka', 'REST/GraphQL', '50+ API Integrations'] as $tech)
                  <span style="background:#fff; border:1px solid #cbd5e1; color:#334155; padding:5px 13px; border-radius:6px; font-size:.76rem; font-weight:600;">{{ $tech }}</span>
                @endforeach
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  {{-- ══════════════════════════════════════
       CONTACT FORM
  ══════════════════════════════════════ --}}
  <section id="investor-contact" style="padding:60px 0; background:#0a2d5e;">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-7">
          <div class="text-center mb-4">
            <h2 style="font-weight:800; color:#fff;">Interested in Investing?</h2>
            <p style="color:rgba(255,255,255,.75);">Tell us a bit about yourself and we'll get back to you with our investor deck and financials.</p>
          </div>

          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          <form action="{{ route('contact.store') }}" method="POST" id="investorForm" style="background:#fff; border-radius:16px; padding:30px;">
            @csrf
            <input type="hidden" name="subject" value="Investor Inquiry">
            <input type="hidden" name="message" id="investorMessageField">

            <div class="row g-3">
              <div class="col-md-6">
                <input type="text" class="form-control" name="name" placeholder="Your Name" required>
              </div>
              <div class="col-md-6">
                <input type="email" class="form-control" name="email" placeholder="Email Address" required>
              </div>
              <div class="col-md-6">
                <input type="tel" class="form-control" id="investorPhone" placeholder="Phone Number">
              </div>
              <div class="col-md-6">
                <select class="form-select" id="investorType">
                  <option value="">I am a...</option>
                  <option>Angel Investor</option>
                  <option>Venture Capital Firm</option>
                  <option>Strategic Partner</option>
                  <option>Other</option>
                </select>
              </div>
              <div class="col-12">
                <select class="form-select" id="investmentRange">
                  <option value="">Approximate Investment Range</option>
                  <option>Under ₹10 Lakh</option>
                  <option>₹10 Lakh – ₹50 Lakh</option>
                  <option>₹50 Lakh – ₹1 Crore</option>
                  <option>Above ₹1 Crore</option>
                </select>
              </div>
              <div class="col-12">
                <textarea class="form-control" id="investorNote" rows="4" placeholder="Tell us about your interest or any questions you have"></textarea>
              </div>
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-warning px-5 py-2 fw-700">
                  <i class="bi bi-send me-1"></i> Send Inquiry
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

</main>
@endsection

@section('scripts')
<script>
document.getElementById('investorForm')?.addEventListener('submit', function () {
  var phone = document.getElementById('investorPhone').value;
  var type = document.getElementById('investorType').value;
  var range = document.getElementById('investmentRange').value;
  var note = document.getElementById('investorNote').value;

  var parts = [];
  if (type)  parts.push('Investor Type: ' + type);
  if (range) parts.push('Investment Range: ' + range);
  if (phone) parts.push('Phone: ' + phone);
  if (note)  parts.push('Message: ' + note);

  document.getElementById('investorMessageField').value = parts.join('\n') || 'Investor inquiry submitted.';
});
</script>
@endsection
