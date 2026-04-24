<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Exclusive Plans – {{ config('app.name') }}</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { background:#eef2f7; font-family:'Segoe UI',Tahoma,Arial,sans-serif; -webkit-font-smoothing:antialiased; }
    table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
    a { color:#0078d4; text-decoration:none; }

    /* Wrapper */
    .ew { width:100%; background:#eef2f7; padding:40px 16px; }

    /* Container */
    .ec {
      max-width:620px; margin:0 auto; background:#fff;
      border-radius:18px; overflow:hidden;
      box-shadow:0 12px 48px rgba(6,24,48,.18);
    }

    /* ── HEADER ──────────────────────────────────────── */
    .eh-wrap {
      background:linear-gradient(150deg,#040e1f 0%,#061830 30%,#0a2d5e 65%,#0f4c81 100%);
      position:relative;
    }
    /* Top accent line */
    .eh-top-bar {
      height:4px;
      background:linear-gradient(90deg,#50e6ff 0%,#0078d4 40%,#7c3aed 70%,#50e6ff 100%);
    }
    /* Main header body */
    .eh-body {
      padding:32px 40px 24px;
      text-align:center;
    }
    /* Icon badge */
    .eh-badge {
      display:inline-block;
      background:rgba(80,230,255,.12);
      border:1px solid rgba(80,230,255,.3);
      border-radius:50px;
      padding:6px 16px;
      font-size:11px;
      font-weight:700;
      color:#50e6ff;
      letter-spacing:1.5px;
      text-transform:uppercase;
      margin-bottom:18px;
    }
    /* Brand */
    .eh-brand {
      font-size:36px;
      font-weight:900;
      color:#fff;
      letter-spacing:0.5px;
      line-height:1;
      display:block;
    }
    .eh-brand span { color:#50e6ff; }
    /* Domain pill */
    .eh-domain {
      display:inline-block;
      margin-top:10px;
      background:rgba(80,230,255,.15);
      border:1px solid rgba(80,230,255,.35);
      border-radius:20px;
      padding:5px 18px;
      font-size:13px;
      font-weight:700;
      color:#a5f3fc;
      letter-spacing:1px;
    }
    .eh-domain span { color:#50e6ff; }
    /* Separator */
    .eh-sep {
      border:0;
      border-top:1px solid rgba(255,255,255,.12);
      margin:18px 0 14px;
    }
    /* Tagline */
    .eh-tagline {
      font-size:11.5px;
      color:rgba(255,255,255,.55);
      letter-spacing:0.6px;
    }

    /* Stats strip inside header */
    .eh-stats {
      background:rgba(0,0,0,.25);
      padding:14px 40px;
      text-align:center;
    }
    .eh-stats table { width:100%; }
    .eh-stats td { text-align:center; padding:0 8px; }
    .eh-stats td + td { border-left:1px solid rgba(255,255,255,.12); }
    .eh-stats .sv { font-size:18px; font-weight:800; color:#50e6ff; display:block; }
    .eh-stats .sl { font-size:10px; color:rgba(255,255,255,.5); font-weight:600; text-transform:uppercase; letter-spacing:.5px; }

    /* Bottom accent line */
    .eh-bot-bar {
      height:3px;
      background:linear-gradient(90deg,#0078d4 0%,#50e6ff 50%,#0078d4 100%);
    }

    /* ── HERO ─────────────────────────────────────────── */
    .ehero {
      padding:30px 40px 18px;
      text-align:center;
      border-bottom:1px solid #f1f5f9;
      background:linear-gradient(180deg,#f8faff 0%,#fff 100%);
    }
    .ehero h1 { font-size:22px; font-weight:800; color:#0a2d5e; line-height:1.35; margin-bottom:8px; }
    .ehero p  { font-size:14px; color:#64748b; line-height:1.65; }

    /* ── BODY ─────────────────────────────────────────── */
    .eb { padding:28px 40px 32px; }
    .eb p  { font-size:15px; color:#475569; line-height:1.75; margin-bottom:16px; }
    .eb h2 { font-size:17px; font-weight:700; color:#0a2d5e; margin:26px 0 10px; }
    .eb strong { color:#0a2d5e; }

    /* Buttons */
    .bw  { text-align:center; margin:26px 0; }
    .btn { display:inline-block; padding:14px 36px; border-radius:8px; font-size:15px; font-weight:700; text-decoration:none; letter-spacing:.3px; }
    .btn-p  { background:linear-gradient(135deg,#0a2d5e,#0078d4); color:#fff !important; }
    .btn-wa { background:#25D366; color:#fff !important; }
    .btn-o  { display:inline-block; padding:10px 24px; border:2px solid #0078d4; border-radius:8px; color:#0078d4 !important; font-weight:700; font-size:14px; text-decoration:none; }

    /* Info boxes */
    .ib   { background:#f0f8ff; border-left:4px solid #0078d4; border-radius:0 8px 8px 0; padding:14px 18px; margin:18px 0; }
    .ib p { color:#0a2d5e; font-size:14px; margin:0; line-height:1.6; }
    .sb   { background:#f0fdf4; border-left:4px solid #22c55e; border-radius:0 8px 8px 0; padding:14px 18px; margin:18px 0; }
    .sb p { color:#166534; font-size:14px; margin:0; }

    /* Divider */
    .dv { border:0; border-top:1px solid #e2e8f0; margin:22px 0; }

    /* Plan cards table */
    .dt { width:100%; margin:12px 0; border-collapse:collapse; }
    .dt td { padding:10px 0; border-bottom:1px solid #f1f5f9; font-size:14px; vertical-align:top; }
    .dt td:first-child { font-weight:700; color:#0a2d5e; width:34%; padding-right:12px; white-space:nowrap; }
    .dt td:last-child { color:#475569; font-weight:400; }

    /* Plan section header */
    .plan-hdr {
      background:linear-gradient(135deg,#f0f6ff,#e8f2ff);
      border-left:4px solid #0078d4;
      border-radius:0 8px 8px 0;
      padding:10px 16px;
      margin:22px 0 6px;
      font-size:15px;
      font-weight:700;
      color:#0a2d5e;
    }

    /* Checklist */
    .cl { list-style:none; padding:0; margin:14px 0; }
    .cl li { font-size:14px; color:#475569; padding:5px 0 5px 26px; position:relative; line-height:1.5; }
    .cl li::before { content:'✓'; position:absolute; left:0; color:#22c55e; font-weight:800; }

    /* Footer */
    .ef { background:#f8fafc; border-top:1px solid #e2e8f0; padding:22px 40px; text-align:center; }
    .ef .fl a { font-size:12px; color:#64748b; margin:0 7px; text-decoration:none; }
    .ef p { font-size:11px; color:#94a3b8; line-height:1.7; margin:6px 0; }
    .ef .unsub { margin-top:10px; }
    .ef .unsub a { font-size:11px; color:#cbd5e1; text-decoration:underline; }

    @media only screen and (max-width:600px) {
      .ew  { padding:10px 6px; }
      .eh-body { padding:24px 20px 18px; }
      .eh-stats { padding:12px 16px; }
      .ehero { padding:22px 20px 14px; }
      .eb  { padding:20px 20px 24px; }
      .ef  { padding:18px 20px; }
      .btn { padding:12px 22px; font-size:14px; }
      .eh-brand { font-size:28px; }
    }
  </style>
</head>
<body>
<div class="ew">
<div class="ec">

  {{-- ══ HEADER ══════════════════════════════════════════ --}}
  <div class="eh-wrap">

    {{-- Top rainbow accent bar --}}
    <div class="eh-top-bar"></div>

    {{-- Brand + domain --}}
    <div class="eh-body">
      <div class="eh-badge">&#127968; Official Partnership Proposal</div>

      <span class="eh-brand">Indianes<span>Hub</span></span>

      <div class="eh-domain">
        &#127760;&nbsp; <span>www.indianesthub.com</span>
      </div>

      <hr class="eh-sep">

      <div class="eh-tagline">
        Chandigarh Tricity's Most Trusted Real Estate Portal &nbsp;·&nbsp; Chandigarh &nbsp;|&nbsp; Mohali &nbsp;|&nbsp; Panchkula &nbsp;|&nbsp; Zirakpur
      </div>
    </div>

    {{-- Live platform stats --}}
    <div class="eh-stats">
      <table><tr>
        <td><span class="sv">3,000+</span><span class="sl">Active Listings</span></td>
        <td><span class="sv">340+</span><span class="sl">Verified Dealers</span></td>
        <td><span class="sv">25+</span><span class="sl">Localities</span></td>
        <td><span class="sv">Daily</span><span class="sl">Real Buyer Leads</span></td>
      </tr></table>
    </div>

    {{-- Bottom accent bar --}}
    <div class="eh-bot-bar"></div>

  </div>{{-- /eh-wrap --}}

  {{-- ══ HERO ═══════════════════════════════════════════ --}}
  <div class="ehero">
    <h1>🏠 Exclusive Plans for Your Real Estate Business</h1>
    <p>Join 340+ dealers &amp; builders already growing with {{ config('app.name') }} — choose a plan and start receiving real buyer leads today.</p>
  </div>

  {{-- ══ BODY ════════════════════════════════════════════ --}}
  <div class="eb">

    <p>Hi <strong>{{ $recipientName }}</strong>,</p>

    <p>
      We're reaching out from <strong>{{ config('app.name') }}</strong> — Chandigarh Tricity's fastest-growing real estate platform.
      Whether you're listing sale properties, rental units, or showcasing builder projects, we have a plan built exactly for your business.
    </p>

    <div class="sb">
      <p>✅ <strong>3,000+ active listings &nbsp;·&nbsp; 340+ verified {{ $recipientType === 'builder' ? 'builders & dealers' : 'dealers' }} &nbsp;·&nbsp; 25+ localities &nbsp;·&nbsp; Real buyer leads daily</strong></p>
    </div>

    <hr class="dv">

    {{-- Sale Plans --}}
    <div class="plan-hdr">📋 Sale Property Plans</div>
    <table class="dt">
      <tr><td>Starter</td><td>₹1,499 &nbsp;·&nbsp; Up to 10 listings &nbsp;·&nbsp; Standard placement</td></tr>
      <tr><td>Value ⭐</td><td>₹3,499 &nbsp;·&nbsp; Up to 30 listings &nbsp;·&nbsp; 3 featured slots &nbsp;·&nbsp; Priority leads</td></tr>
      <tr><td>Premium</td><td>₹6,999 &nbsp;·&nbsp; Up to 75 listings &nbsp;·&nbsp; 10 featured slots &nbsp;·&nbsp; Instant WhatsApp alerts</td></tr>
      <tr><td>Ultimate</td><td>₹11,999 &nbsp;·&nbsp; Unlimited listings &nbsp;·&nbsp; Unlimited featured &nbsp;·&nbsp; Dedicated manager</td></tr>
    </table>

    {{-- Rent Plans --}}
    <div class="plan-hdr">🔑 Rental Property Plans</div>
    <table class="dt">
      <tr><td>Rent Starter</td><td>₹999 &nbsp;·&nbsp; Up to 10 listings &nbsp;·&nbsp; Standard placement</td></tr>
      <tr><td>Rent Value ⭐</td><td>₹2,499 &nbsp;·&nbsp; Up to 30 listings &nbsp;·&nbsp; 3 featured slots &nbsp;·&nbsp; Priority leads</td></tr>
      <tr><td>Rent Premium</td><td>₹4,999 &nbsp;·&nbsp; Up to 75 listings &nbsp;·&nbsp; 10 featured slots &nbsp;·&nbsp; Instant WhatsApp alerts</td></tr>
      <tr><td>Rent Ultimate</td><td>₹8,999 &nbsp;·&nbsp; Unlimited listings &nbsp;·&nbsp; Unlimited featured &nbsp;·&nbsp; Dedicated manager</td></tr>
    </table>

    {{-- PG Plans --}}
    <div class="plan-hdr">🏘️ PG / Hostel Plans</div>
    <table class="dt">
      <tr><td>PG Basic</td><td>₹799 &nbsp;·&nbsp; Up to 5 listings &nbsp;·&nbsp; Standard placement</td></tr>
      <tr><td>PG Value ⭐</td><td>₹1,999 &nbsp;·&nbsp; Up to 15 listings &nbsp;·&nbsp; 2 featured slots</td></tr>
      <tr><td>PG Premium</td><td>₹3,999 &nbsp;·&nbsp; Up to 40 listings &nbsp;·&nbsp; 5 featured slots &nbsp;·&nbsp; Instant alerts</td></tr>
      <tr><td>PG Ultimate</td><td>₹6,999 &nbsp;·&nbsp; Unlimited listings &nbsp;·&nbsp; Priority placement &nbsp;·&nbsp; Dedicated manager</td></tr>
    </table>

    <hr class="dv">

    {{-- Why {{ config('app.name') }} --}}
    <h2>💡 Why Choose {{ config('app.name') }}?</h2>
    <ul class="cl">
      <li>Hyper-local focus on <strong>Chandigarh, Mohali, Panchkula &amp; Zirakpur</strong> — real buyers, not bots</li>
      <li>Listings go live <strong>instantly</strong> — no long approval delays</li>
      <li>Buyer leads delivered via <strong>WhatsApp + Email</strong> in real time</li>
      <li>Easy dashboard to manage all listings, inquiries, and renewals</li>
      <li>Dedicated support team available on WhatsApp</li>
      <li><strong>UPI payment</strong> — fast, simple, no hidden charges</li>
    </ul>

    <div class="ib">
      <p>📊 <strong>Dealers on the Value plan or above get 5× more inquiries</strong> compared to free listings — based on our platform data.</p>
    </div>

    {{-- CTAs --}}
    <div class="bw">
      <a href="{{ url('/pricing') }}" class="btn btn-p">View All Plans &amp; Choose Now →</a>
    </div>

    <div class="bw" style="margin-top:0;">
      <a href="https://wa.me/91{{ config('app.whatsapp_number','7340753780') }}?text=Hi%2C%20I%20want%20to%20know%20more%20about%20{{ urlencode(config('app.name')) }}%20plans." class="btn btn-wa">
        💬 Chat with Us on WhatsApp
      </a>
    </div>

    <hr class="dv">

    <p style="font-size:13px; color:#94a3b8; text-align:center;">
      You are receiving this because you are a registered {{ $recipientType }} on {{ config('app.name') }}.<br>
      Questions? Reply to this email or WhatsApp us at +91 {{ config('app.contact_phone','7340753780') }}.
    </p>

    {{-- ── Open-tracking pixel (invisible 1×1 image) ── --}}
    @if(!empty($trackingToken))
    <img src="{{ url('/email/track/' . $trackingToken) }}"
         width="1" height="1" border="0"
         style="display:block;width:1px;height:1px;opacity:0;line-height:1px;"
         alt="">
    @endif

  </div>{{-- /eb --}}

  {{-- ══ FOOTER ══════════════════════════════════════════ --}}
  <div class="ef">
    <div class="fl" style="margin-bottom:10px;">
      <a href="{{ url('/') }}">Home</a>
      <a href="{{ url('/properties') }}">Properties</a>
      <a href="{{ url('/pricing') }}">Pricing</a>
      <a href="{{ url('/contact') }}">Contact</a>
      <a href="{{ url('/privacy') }}">Privacy</a>
    </div>
    <p>
      <strong>{{ config('app.name') }}</strong> &nbsp;·&nbsp; www.indianesthub.com &nbsp;·&nbsp; Chandigarh Tricity<br>
      📞 +91 {{ config('app.contact_phone','7340753780') }} &nbsp;·&nbsp;
      ✉ {{ config('app.contact_email','support@indianesthub.com') }}
    </p>
    <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    <div class="unsub">
      <a href="{{ url('/unsubscribe') }}">Unsubscribe from promotional emails</a>
    </div>
  </div>

</div>{{-- /ec --}}
</div>{{-- /ew --}}
</body>
</html>
