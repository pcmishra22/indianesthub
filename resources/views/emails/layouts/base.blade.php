<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ $emailSubject ?? config('app.name') }}</title>
  <style>
    /* ── Reset ───────────────────────────────────────── */
    * { margin:0; padding:0; box-sizing:border-box; }
    body { background:#f0f4f8; font-family:'Segoe UI',Tahoma,Arial,sans-serif; -webkit-font-smoothing:antialiased; }
    table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
    a { color:#0078d4; text-decoration:none; }

    /* ── Wrapper ─────────────────────────────────────── */
    .ew { width:100%; background:#f0f4f8; padding:40px 16px; }

    /* ── Container ───────────────────────────────────── */
    .ec {
      max-width:600px; margin:0 auto; background:#fff;
      border-radius:16px; overflow:hidden;
      box-shadow:0 8px 32px rgba(10,45,94,.12);
    }

    /* ── Header ──────────────────────────────────────── */
    .eh {
      background:linear-gradient(135deg,#061830 0%,#0a2d5e 50%,#0f4c81 100%);
      padding:28px 40px; text-align:center;
    }
    .eh .icon { font-size:34px; display:block; margin-bottom:10px; }
    .eh .brand { font-size:24px; font-weight:800; color:#fff; letter-spacing:.5px; display:block; margin-bottom:6px; }
    .eh .brand span { color:#50e6ff; }
    .eh .tagline { font-size:11px; color:rgba(255,255,255,.6); letter-spacing:.3px; }

    /* ── Hero ────────────────────────────────────────── */
    .ehero { padding:28px 40px 16px; text-align:center; border-bottom:1px solid #f1f5f9; }
    .ehero h1 { font-size:22px; font-weight:800; color:#0a2d5e; line-height:1.3; margin-bottom:8px; }
    .ehero p  { font-size:14px; color:#64748b; line-height:1.6; }

    /* ── Body ────────────────────────────────────────── */
    .eb { padding:28px 40px 32px; }
    .eb p  { font-size:15px; color:#475569; line-height:1.75; margin-bottom:16px; }
    .eb h2 { font-size:17px; font-weight:700; color:#0a2d5e; margin:24px 0 10px; }
    .eb h3 { font-size:14px; font-weight:700; color:#334155; margin:16px 0 8px; }
    .eb strong { color:#0a2d5e; }
    .eb ul { padding-left:20px; margin-bottom:16px; }
    .eb ul li { font-size:14px; color:#475569; line-height:1.7; margin-bottom:4px; }

    /* ── Buttons ──────────────────────────────────────── */
    .bw  { text-align:center; margin:28px 0; }
    .btn { display:inline-block; padding:14px 36px; border-radius:8px; font-size:15px; font-weight:700; text-decoration:none; letter-spacing:.3px; }
    .btn-p  { background:linear-gradient(135deg,#0a2d5e,#0078d4); color:#fff !important; }
    .btn-g  { background:#22c55e; color:#fff !important; }
    .btn-wa { background:#25D366; color:#fff !important; }
    .btn-o  { display:inline-block; padding:10px 24px; border:2px solid #0078d4; border-radius:8px; color:#0078d4 !important; font-weight:700; font-size:14px; text-decoration:none; }

    /* ── Info Boxes ───────────────────────────────────── */
    .ib   { background:#f0f8ff; border-left:4px solid #0078d4; border-radius:0 8px 8px 0; padding:14px 18px; margin:18px 0; }
    .ib p { color:#0a2d5e; font-size:14px; margin:0; line-height:1.6; }
    .sb   { background:#f0fdf4; border-left:4px solid #22c55e; border-radius:0 8px 8px 0; padding:14px 18px; margin:18px 0; }
    .sb p { color:#166534; font-size:14px; margin:0; }
    .wb   { background:#fffbeb; border-left:4px solid #f59e0b; border-radius:0 8px 8px 0; padding:14px 18px; margin:18px 0; }
    .wb p { color:#92400e; font-size:14px; margin:0; }

    /* ── Divider ──────────────────────────────────────── */
    .dv { border:0; border-top:1px solid #e2e8f0; margin:22px 0; }

    /* ── Data Table ───────────────────────────────────── */
    .dt { width:100%; margin:14px 0; border-collapse:collapse; }
    .dt td { padding:9px 0; border-bottom:1px solid #f1f5f9; font-size:14px; vertical-align:top; }
    .dt td:first-child { color:#94a3b8; font-weight:600; width:36%; padding-right:12px; white-space:nowrap; }
    .dt td:last-child { color:#1e293b; font-weight:500; }

    /* ── Property Card ────────────────────────────────── */
    .pc { border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; margin:16px 0; }
    .pc-h { background:linear-gradient(135deg,#0a2d5e,#0078d4); padding:12px 18px; }
    .pc-h .ptype { font-size:11px; color:#50e6ff; font-weight:700; text-transform:uppercase; letter-spacing:1px; }
    .pc-b { padding:16px 18px; }
    .pc-title { font-size:16px; font-weight:700; color:#0a2d5e; margin-bottom:4px; }
    .pc-loc   { font-size:13px; color:#64748b; margin-bottom:10px; }
    .pc-price { font-size:22px; font-weight:800; color:#0078d4; }
    .pc-meta  { font-size:12px; color:#64748b; margin-top:8px; }

    /* ── Stats Strip ─────────────────────────────────── */
    .ss { background:#f8fafc; border-radius:10px; padding:18px; margin:18px 0; }
    .ss table { width:100%; }
    .ss td { text-align:center; padding:0 10px; }
    .ss td + td { border-left:1px solid #e2e8f0; }
    .ss .sv { font-size:20px; font-weight:800; color:#0a2d5e; display:block; }
    .ss .sl { font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:.5px; }

    /* ── Checklist ────────────────────────────────────── */
    .cl { list-style:none; padding:0; margin:14px 0; }
    .cl li { font-size:14px; color:#475569; padding:5px 0 5px 24px; position:relative; line-height:1.5; }
    .cl li::before { content:'✓'; position:absolute; left:0; color:#22c55e; font-weight:700; }

    /* ── Quick Action Links ───────────────────────────── */
    .al { text-align:center; margin:18px 0; }
    .al a { display:inline-block; margin:4px 6px; padding:7px 16px; border-radius:6px; font-size:13px; font-weight:600; text-decoration:none; background:#f1f5f9; color:#0a2d5e !important; }

    /* ── Footer ──────────────────────────────────────── */
    .ef { background:#f8fafc; border-top:1px solid #e2e8f0; padding:22px 40px; text-align:center; }
    .ef .fl a { font-size:12px; color:#64748b; margin:0 7px; text-decoration:none; }
    .ef p { font-size:11px; color:#94a3b8; line-height:1.7; margin:6px 0; }
    .ef .unsub { margin-top:10px; }
    .ef .unsub a { font-size:11px; color:#cbd5e1; text-decoration:underline; }

    @media only screen and (max-width:600px) {
      .ew  { padding:12px 8px; }
      .eh  { padding:22px 20px; }
      .eb  { padding:20px 20px 24px; }
      .ehero { padding:20px 20px 12px; }
      .ef  { padding:18px 20px; }
      .btn { padding:12px 22px; font-size:14px; }
    }
  </style>
</head>
<body>
<div class="ew">
  <div class="ec">

    {{-- Header --}}
    <div class="eh">
      @yield('header_icon', '')
      <span class="brand">Indianes<span>Hub</span></span>
      <div class="tagline">Chandigarh Tricity's Most Trusted Real Estate Portal</div>
    </div>

    {{-- Hero --}}
    @hasSection('hero_title')
    <div class="ehero">
      <h1>@yield('hero_title')</h1>
      @hasSection('hero_sub')
      <p>@yield('hero_sub')</p>
      @endif
    </div>
    @endif

    {{-- Main Content --}}
    <div class="eb">
      @yield('content')
    </div>

    {{-- Footer --}}
    <div class="ef">
      <div class="fl" style="margin-bottom:10px;">
        <a href="{{ url('/') }}">Home</a>
        <a href="{{ url('/properties') }}">Properties</a>
        <a href="{{ url('/pricing') }}">Pricing</a>
        <a href="{{ url('/contact') }}">Contact</a>
        <a href="{{ url('/privacy') }}">Privacy</a>
      </div>
      <p>
        <strong>{{ config('app.name') }}</strong> · Chandigarh Tricity<br>
        📞 +91 {{ config('app.contact_phone','7340753780') }} &nbsp;·&nbsp;
        ✉ {{ config('app.contact_email','support@indianesthub.com') }}
      </p>
      <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
      @yield('unsubscribe')
    </div>

  </div>
</div>
</body>
</html>
