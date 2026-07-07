<?php
declare(strict_types=1);
/**
 * views/dashboard.php — the main app shell: HTML/CSS layout plus the
 * inline JS frontend app. Kept as one file (not split further) because the
 * JS needs a PHP-computed BASE_PATH constant at the top — splitting would
 * mean duplicating that snippet across files for no real benefit.
 *
 * Structure: HTML/CSS markup (~500 lines) followed by <script> (~1650 lines).
 * The JS section implements browser-side data fetching (see api/proxy/*.php
 * for the server-side counterparts) since this is where live quotes are
 * actually retrieved from — see datasources.php header comment for why.
 */
function dashboardPage(string $appName, string $username): void { ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= htmlspecialchars($appName) ?></title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#0b0e1a;--panel:#131728;--panel2:#1a1f35;
  --border:rgba(255,255,255,.07);--border2:rgba(255,255,255,.12);
  --accent:#0072ff;--accent2:#00c6ff;
  --green:#10b981;--green2:#34d399;
  --red:#ef4444;--red2:#f87171;
  --orange:#f59e0b;--yellow:#fbbf24;
  --text:#e2e8f0;--muted:#6b7280;--muted2:#9ca3af;
  --r:12px;
}
body{background:var(--bg);color:var(--text);font-family:'Segoe UI',system-ui,sans-serif;font-size:14px;min-height:100vh}
.topbar{background:var(--panel);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 20px;height:54px;position:sticky;top:0;z-index:200}
.logo{font-size:1.05rem;font-weight:700;color:#fff;display:flex;align-items:center;gap:8px}
.logo-icon{width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:14px}
.nav{display:flex;gap:2px;margin-left:20px}
.nb{padding:6px 14px;border-radius:8px;cursor:pointer;font-size:13px;font-weight:500;border:none;background:transparent;color:var(--muted);transition:all .15s}
.nb:hover,.nb.active{background:rgba(255,255,255,.08);color:#fff}
.topbar-r{display:flex;align-items:center;gap:12px}
.clock{font-size:12px;color:var(--accent2);background:rgba(0,198,255,.1);padding:4px 10px;border-radius:20px;font-weight:600}
.user-tag{font-size:12px;color:var(--muted)}
.btn-sm{font-size:12px;padding:5px 12px;border-radius:8px;border:1px solid var(--border2);background:none;color:var(--muted);cursor:pointer;transition:all .15s}
.btn-sm:hover{color:var(--red);border-color:var(--red)}
.free-tag{font-size:10px;color:var(--green);background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.2);padding:3px 8px;border-radius:10px}
.ticker-bar{background:rgba(0,0,0,.4);border-bottom:1px solid var(--border);padding:6px 20px;font-size:11px;color:var(--muted);display:flex;gap:4px;align-items:center;overflow:hidden}
.ticker-item{white-space:nowrap;padding:0 12px;border-right:1px solid var(--border)}
.up{color:var(--green)}.dn{color:var(--red)}
.wrap{padding:18px 20px;max-width:1600px;margin:0 auto}
.tab-pane{display:none}.tab-pane.active{display:block}
.panel{background:var(--panel);border:1px solid var(--border);border-radius:var(--r)}
.panel-head{display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border-bottom:1px solid var(--border)}
.panel-title{font-size:12px;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);display:flex;align-items:center;gap:8px}
.panel-title strong{font-size:14px;color:#fff;text-transform:none;letter-spacing:0}
.kpi-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:16px}
.kpi{background:var(--panel);border:1px solid var(--border);border-radius:var(--r);padding:16px 18px;position:relative;overflow:hidden}
.kpi::before{content:'';position:absolute;top:0;left:0;right:0;height:3px}
.kpi.blue::before{background:linear-gradient(90deg,var(--accent),var(--accent2))}
.kpi.green::before{background:linear-gradient(90deg,#059669,var(--green))}
.kpi.red::before{background:linear-gradient(90deg,#dc2626,var(--red))}
.kpi.orange::before{background:linear-gradient(90deg,#d97706,var(--orange))}
.kpi-label{font-size:10px;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);margin-bottom:6px}
.kpi-val{font-size:1.5rem;font-weight:700;color:#fff}
.kpi-sub{font-size:11px;color:var(--muted);margin-top:3px}
.btn{padding:9px 18px;border-radius:8px;border:none;cursor:pointer;font-size:13px;font-weight:600;transition:all .15s}
.btn-primary{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff}
.btn-primary:hover{opacity:.9}
.btn-outline{background:rgba(0,114,255,.1);border:1px solid rgba(0,114,255,.3);color:var(--accent2)}
.btn-outline:hover{background:rgba(0,114,255,.2)}
.watch-grid{overflow-x:auto}
table{width:100%;border-collapse:collapse}
th{padding:10px 14px;text-align:left;font-size:10px;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);border-bottom:1px solid var(--border);background:rgba(255,255,255,.02);white-space:nowrap;cursor:pointer}
th:hover{color:#fff}
td{padding:12px 14px;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle}
tr:hover td{background:rgba(255,255,255,.02)}
.sym{font-weight:700;color:#fff;font-size:13px}
.co-name{font-size:10px;color:var(--muted);margin-top:1px}
.price{font-weight:600;font-size:13px}
.chg-up{color:var(--green)}.chg-dn{color:var(--red)}
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
.badge-buy{background:rgba(16,185,129,.12);color:var(--green);border:1px solid rgba(16,185,129,.3)}
.badge-sell{background:rgba(239,68,68,.12);color:var(--red);border:1px solid rgba(239,68,68,.3)}
.badge-hold{background:rgba(245,158,11,.12);color:var(--orange);border:1px solid rgba(245,158,11,.3)}
.conf-wrap{display:flex;align-items:center;gap:8px}
.conf-bar-bg{flex:1;height:4px;background:rgba(255,255,255,.07);border-radius:4px;min-width:50px}
.conf-bar-fill{height:100%;border-radius:4px}
.action-btn{font-size:11px;padding:4px 10px;border-radius:6px;border:1px solid rgba(0,114,255,.3);background:rgba(0,114,255,.08);color:var(--accent2);cursor:pointer;transition:all .15s;white-space:nowrap}
.action-btn:hover{background:rgba(0,114,255,.2)}
.analyze-box{display:grid;grid-template-columns:1fr 2fr;gap:16px;margin-bottom:16px}
@media(max-width:900px){.analyze-box{grid-template-columns:1fr}}
.search-card{background:var(--panel);border:1px solid var(--border);border-radius:var(--r);padding:20px}
.search-card h3{font-size:14px;font-weight:600;color:#fff;margin-bottom:4px}
.search-card p{font-size:12px;color:var(--muted);margin-bottom:16px}
.sym-input{width:100%;padding:12px 14px;background:var(--panel2);border:1px solid var(--border2);border-radius:8px;color:#fff;font-size:14px;font-weight:600;text-transform:uppercase;letter-spacing:1px;outline:none;margin-bottom:10px;transition:border-color .2s}
.sym-input:focus{border-color:var(--accent)}
.quick-syms{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px}
.qsym{font-size:11px;padding:4px 10px;border-radius:6px;border:1px solid var(--border2);background:rgba(255,255,255,.04);color:var(--muted2);cursor:pointer;transition:all .15s}
.qsym:hover{border-color:var(--accent2);color:var(--accent2)}
.analysis-result{background:var(--panel);border:1px solid var(--border);border-radius:var(--r);padding:20px;min-height:300px;display:flex;align-items:center;justify-content:center}
.result-placeholder{text-align:center;color:var(--muted)}
.result-placeholder .icon{font-size:40px;margin-bottom:12px;opacity:.4}
.analysis-loaded{width:100%}
.analysis-top{display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid var(--border)}
.analysis-sym{font-size:1.6rem;font-weight:800;color:#fff}
.analysis-name{font-size:13px;color:var(--muted);margin-top:2px}
.analysis-price{text-align:right}
.analysis-price .price-big{font-size:1.5rem;font-weight:700;color:#fff}
.big-signal{display:inline-flex;align-items:center;gap:6px;padding:6px 16px;border-radius:8px;font-size:15px;font-weight:700;margin-top:8px}
.big-signal.buy{background:rgba(16,185,129,.15);color:var(--green);border:1px solid rgba(16,185,129,.4)}
.big-signal.sell{background:rgba(239,68,68,.15);color:var(--red);border:1px solid rgba(239,68,68,.4)}
.big-signal.hold{background:rgba(245,158,11,.15);color:var(--orange);border:1px solid rgba(245,158,11,.4)}
.analysis-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px}
@media(max-width:700px){.analysis-grid{grid-template-columns:1fr}}
.a-section{background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:10px;padding:14px}
.a-section-title{font-size:10px;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);margin-bottom:10px;display:flex;align-items:center;gap:6px}
.ind-row{display:flex;justify-content:space-between;align-items:center;padding:5px 0;border-bottom:1px solid rgba(255,255,255,.04);font-size:12px}
.ind-row:last-child{border-bottom:none}
.ind-label{color:var(--muted)}
.ind-val{font-weight:600}
.bull-val{color:var(--green)}.bear-val{color:var(--red)}.neu-val{color:var(--orange)}
.factor-list{list-style:none}
.factor-list li{padding:5px 0;font-size:12px;color:var(--text);display:flex;align-items:flex-start;gap:8px;border-bottom:1px solid rgba(255,255,255,.04)}
.factor-list li:last-child{border-bottom:none}
.factor-list .ico{flex-shrink:0;margin-top:1px}
.verdict-box{background:rgba(0,114,255,.06);border:1px solid rgba(0,114,255,.2);border-radius:10px;padding:14px;font-size:13px;line-height:1.7;color:var(--text);margin-bottom:14px}
.trade-setup{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
@media(max-width:700px){.trade-setup{grid-template-columns:repeat(2,1fr)}}
.ts-box{background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:8px;padding:12px;text-align:center}
.ts-label{font-size:10px;text-transform:uppercase;letter-spacing:.6px;color:var(--muted);margin-bottom:6px}
.ts-val{font-size:1.1rem;font-weight:700;color:#fff}
.ts-entry{color:var(--accent2)}.ts-t1{color:var(--green)}.ts-t2{color:var(--green2)}.ts-sl{color:var(--red)}
.pattern-tags{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:14px}
.pat-tag{font-size:11px;padding:4px 10px;border-radius:20px}
.pat-bull{background:rgba(16,185,129,.1);color:var(--green);border:1px solid rgba(16,185,129,.25)}
.pat-bear{background:rgba(239,68,68,.1);color:var(--red);border:1px solid rgba(239,68,68,.25)}
.pat-neu{background:rgba(255,255,255,.05);color:var(--muted2);border:1px solid var(--border)}
.risk-box{background:rgba(245,158,11,.06);border:1px solid rgba(245,158,11,.2);border-radius:8px;padding:12px;font-size:12px;color:var(--orange);line-height:1.6}
.news-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:14px}
.news-card{background:var(--panel);border:1px solid var(--border);border-radius:var(--r);padding:16px;cursor:pointer;transition:border-color .2s}
.news-card:hover{border-color:rgba(0,114,255,.4)}
.news-impact{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;margin-bottom:8px}
.imp-bull{color:var(--green)}.imp-bear{color:var(--red)}.imp-neu{color:var(--orange)}
.news-head{font-size:13px;font-weight:600;color:#fff;line-height:1.5;margin-bottom:8px}
.news-sum{font-size:12px;color:var(--muted);line-height:1.6}
.news-stocks{display:flex;gap:6px;flex-wrap:wrap;margin-top:10px}
.ns-tag{font-size:10px;background:rgba(0,114,255,.1);border:1px solid rgba(0,114,255,.2);color:var(--accent2);padding:2px 7px;border-radius:4px}
.leader-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px}
@media(max-width:800px){.leader-grid{grid-template-columns:1fr}}
.leader-card{background:var(--panel);border:1px solid var(--border);border-radius:var(--r);overflow:hidden}
.leader-card-head{padding:12px 16px;display:flex;align-items:center;gap:10px;border-bottom:1px solid var(--border)}
.leader-card-title{font-size:13px;font-weight:700}
.leader-row{display:flex;align-items:center;padding:10px 16px;border-bottom:1px solid rgba(255,255,255,.04);gap:10px;transition:background .15s}
.leader-row:hover{background:rgba(255,255,255,.03)}
.leader-rank{font-size:18px;font-weight:900;width:28px;text-align:center;flex-shrink:0}
.leader-sym{font-size:13px;font-weight:700;color:#fff}
.leader-name{font-size:10px;color:var(--muted)}
.leader-bars{flex:1;min-width:0}
.leader-signal-bar{height:6px;border-radius:3px;margin-bottom:3px}
.leader-meta{font-size:10px;color:var(--muted);display:flex;gap:8px;flex-wrap:wrap}
.streak-badge{font-size:10px;padding:2px 7px;border-radius:10px;font-weight:700}
.streak-buy{background:rgba(16,185,129,.15);color:var(--green)}
.streak-sell{background:rgba(239,68,68,.15);color:var(--red)}
.tick-dot{display:inline-block;width:7px;height:7px;border-radius:50%;margin:1px}
.live-row{display:flex;align-items:center;padding:8px 16px;border-bottom:1px solid rgba(255,255,255,.03);gap:8px;font-size:12px}
.live-ticker{font-weight:700;color:#fff;width:90px;flex-shrink:0}
.live-price{width:70px;color:var(--accent2)}
.live-chg{width:60px}
.live-signal{width:50px}
.live-score-bar{flex:1;height:4px;border-radius:2px;background:rgba(255,255,255,.07)}
.pulse{animation:pulse 1.5s ease-in-out infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}
.spin{display:inline-block;width:16px;height:16px;border:2px solid rgba(255,255,255,.1);border-top-color:var(--accent2);border-radius:50%;animation:spin .7s linear infinite;vertical-align:middle}
@keyframes spin{to{transform:rotate(360deg)}}
.loading-card{display:flex;align-items:center;justify-content:center;padding:60px;flex-direction:column;gap:14px;color:var(--muted);min-height:200px}
.loading-card .spin{width:28px;height:28px;border-width:3px}
::-webkit-scrollbar{width:5px;height:5px}
::-webkit-scrollbar-thumb{background:rgba(255,255,255,.1);border-radius:4px}
.refresh-row{display:flex;align-items:center;gap:12px;margin-bottom:16px}
.refresh-row .label{font-size:12px;color:var(--muted)}
.rbar-bg{flex:1;max-width:200px;height:4px;background:rgba(255,255,255,.07);border-radius:4px;overflow:hidden}
.rbar-fill{height:100%;background:linear-gradient(90deg,var(--accent),var(--accent2));border-radius:4px;transition:width 1s linear}
.err-box{background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.25);border-radius:var(--r);padding:20px;color:var(--red2);font-size:13px;line-height:1.7}
.source-badge{font-size:10px;color:var(--green);background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.15);padding:2px 8px;border-radius:8px}
/* ── Mobile responsive ── */
@media(max-width:680px){
  .topbar{padding:0 10px}
  .wrap{padding:10px}
  .nav .nb{padding:5px 8px;font-size:11px}
  .kpi-row{grid-template-columns:1fr 1fr}
  .trade-setup{grid-template-columns:1fr 1fr}
  .analysis-grid{grid-template-columns:1fr}
  .analyze-box{grid-template-columns:1fr}
  .leader-grid{grid-template-columns:1fr}
  .news-grid{grid-template-columns:1fr}
  .search-card{order:2}
  .analysis-result{order:1;min-height:200px}
  table{font-size:11px}
  th,td{padding:7px 8px}
  .kpi-val{font-size:1.1rem}
  .analysis-sym{font-size:1.2rem}
  .price-big{font-size:1.1rem}
  .big-signal{font-size:12px;padding:4px 10px}
  #priceChart{max-height:160px !important}
  /* Hide non-essential table columns on mobile */
  table th:nth-child(n+8),table td:nth-child(n+8){display:none}
  .topbar-r .free-tag{display:none}
  .panel-title strong{font-size:12px}
  .wl-manager{flex-direction:column;gap:8px}
}
@media(max-width:420px){
  .nav .nb span{display:none}
  .kpi-row{grid-template-columns:1fr}
  .trade-setup{grid-template-columns:1fr}
  /* On very small screens show only first 5 table columns */
  table th:nth-child(n+6),table td:nth-child(n+6){display:none}
  .nav .nb{padding:4px 6px;font-size:10px}
  h2{font-size:.95rem}
}
/* Tablet */
@media(min-width:681px) and (max-width:1024px){
  .kpi-row{grid-template-columns:repeat(3,1fr)}
  .analysis-grid{grid-template-columns:1fr}
  .leader-grid{grid-template-columns:1fr 1fr}
  table{font-size:12px}
  th,td{padding:8px 9px}
}
/* Ensure tables scroll on mobile instead of overflow */
.tw,.tbl-wrap,div[style*="overflow-x:auto"]{-webkit-overflow-scrolling:touch}
/* Touch-friendly buttons */
@media(pointer:coarse){
  .nb,.btn,.action-btn,.btn-sm{min-height:36px}
  .action-btn{padding:6px 12px}
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
</head>
<body>

<header class="topbar">
  <div style="display:flex;align-items:center">
    <div class="logo"><div class="logo-icon">📈</div><?= htmlspecialchars($appName) ?></div>
    <nav class="nav">
      <button class="nb active" onclick="showTab('watchlist',this)">📊 Watchlist</button>
      <button class="nb" onclick="showTab('analyze',this)">🔍 Analyze</button>
      <button class="nb" onclick="showTab('news',this)">📰 News</button>
      <button class="nb" onclick="showTab('leaders',this)">🏆 Leaders</button>
      <button class="nb" onclick="showTab('intraday',this)">📉 Chart</button>
      <button class="nb" onclick="showTab('eodreport',this);loadEodReport()">📋 EOD Report</button>
    </nav>
  </div>
  <div class="topbar-r">
    <span class="free-tag">✅ Free API</span>
    <span class="user-tag">👤 <?= htmlspecialchars($username) ?></span>
    <span class="clock" id="clock">--:--:--</span>
    <button class="btn-sm" onclick="location.href='logout'">Sign Out</button>
  </div>
</header>

<div class="wrap">

<!-- WATCHLIST TAB -->
<div class="tab-pane active" id="tab-watchlist">
  <div class="kpi-row" id="kpiRow">
    <div class="kpi blue"><div class="kpi-label">Watchlist</div><div class="kpi-val" id="kpiTotal">—</div><div class="kpi-sub">stocks tracked</div></div>
    <div class="kpi green"><div class="kpi-label">Buy Signals</div><div class="kpi-val" id="kpiBuy">—</div><div class="kpi-sub" id="kpiBuyPct">of watchlist</div></div>
    <div class="kpi red"><div class="kpi-label">Sell Signals</div><div class="kpi-val" id="kpiSell">—</div><div class="kpi-sub" id="kpiSellPct">of watchlist</div></div>
    <div class="kpi orange"><div class="kpi-label">Market Mood</div><div class="kpi-val" id="kpiMood" style="font-size:1rem">—</div><div class="kpi-sub" id="kpiNifty">Signals summary</div></div>
    <div class="kpi blue"><div class="kpi-label">Last Update</div><div class="kpi-val" id="kpiTime" style="font-size:1rem">—</div><div class="kpi-sub" id="kpiCached">live</div></div>
  </div>

  <!-- Controls row -->
  <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:12px">
    <span class="label">Auto-refresh in <strong id="cdSec">300</strong>s</span>
    <div class="rbar-bg"><div class="rbar-fill" id="rbar" style="width:100%"></div></div>
    <button class="btn btn-outline" onclick="wlPage=1;loadWatchlist()" style="padding:5px 12px;font-size:12px" id="refreshBtn">🔄 Refresh</button>
    <button class="btn btn-outline" onclick="clearYahooCache()" style="padding:5px 12px;font-size:12px;color:var(--orange);border-color:var(--orange)" id="clearCacheBtn" title="Clear data cache and reload">🗑️ Clear Cache</button>
    <span id="watchlistSourceBadge" style="font-size:11px;padding:4px 8px;border-radius:999px;background:rgba(0,114,255,.12);border:1px solid rgba(0,114,255,.25);color:var(--a2)">Loading…</span>
    <div id="cacheNote" style="font-size:11px;color:var(--muted)"></div>
  </div>

  <!-- Search + Sector filter + Custom WL -->
  <div style="background:var(--panel);border:1px solid var(--border);border-radius:var(--r);padding:12px 16px;margin-bottom:12px">
    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">

      <!-- Search -->
      <input id="wlSearchInput" type="text" placeholder="🔍 Search symbol or name…"
        style="background:var(--panel2);border:1px solid var(--border2);border-radius:6px;padding:6px 11px;color:#fff;font-size:12px;outline:none;width:200px"
        oninput="clearTimeout(window._st);window._st=setTimeout(()=>setSearch(this.value.trim()),500)"
        onkeydown="if(event.key==='Enter')setSearch(this.value.trim())">

      <!-- Sector filter -->
      <select id="sectorFilter" onchange="setSector(this.value)"
        style="background:var(--panel2);border:1px solid var(--border2);border-radius:6px;padding:6px 10px;color:var(--m2);font-size:12px;outline:none">
        <option value="">All Sectors</option>
      </select>

      <!-- Sector quick pills -->
      <div style="display:flex;gap:4px;flex-wrap:wrap">
        <?php foreach (array_keys(SECTOR_MAP) as $s): ?>
        <button onclick="setSector('<?=htmlspecialchars($s,ENT_QUOTES)?>')" class="sector-pill"
          style="font-size:10px;padding:2px 8px;border-radius:20px;border:1px solid var(--border2);background:rgba(255,255,255,.04);color:var(--m2);cursor:pointer;white-space:nowrap">
          <?=htmlspecialchars($s)?>
        </button>
        <?php endforeach; ?>
        <button onclick="setSector('')" style="font-size:10px;padding:2px 8px;border-radius:20px;border:1px solid var(--accent);background:rgba(0,114,255,.1);color:var(--a2);cursor:pointer">All</button>
      </div>

      <!-- Spacer -->
      <div style="flex:1"></div>

      <!-- Add custom stock -->
      <input id="wlAddInput" type="text" placeholder="Add stock…"
        style="background:var(--panel2);border:1px solid var(--border2);border-radius:6px;padding:5px 9px;color:#fff;font-size:12px;outline:none;width:120px;text-transform:uppercase"
        oninput="this.value=this.value.toUpperCase()" onkeydown="if(event.key==='Enter')addToWatchlist()">
      <button class="btn btn-outline" onclick="addToWatchlist()" style="padding:5px 10px;font-size:12px">+ Add</button>
      <button onclick="resetWatchlist()" style="font-size:11px;padding:4px 10px;border-radius:6px;border:1px solid var(--border);background:none;color:var(--muted);cursor:pointer">Reset</button>
    </div>

    <!-- Custom WL chips -->
    <div id="wlItems" style="margin-top:8px;display:flex;flex-wrap:wrap;gap:4px"></div>
  </div>

  <div id="prakashRecommendations" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:10px;margin-bottom:12px"></div>
  <div id="aiRecommendations" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:10px;margin-bottom:12px"></div>

  <div class="panel" id="watchPanel">
    <div class="loading-card" id="watchLoading">
      <div class="spin"></div>
      <div>Fetching 200+ NSE stocks…</div>
      <div style="font-size:11px;color:var(--muted)">Bulk parallel fetch → page analysis (~10s, then cached)</div>
    </div>
    <div id="watchTable" style="display:none"></div>
    <!-- Pagination -->
    <div id="wlPagination" style="padding:0 16px;border-top:1px solid var(--border)"></div>
  </div>
</div>

<!-- ANALYZE TAB -->
<div class="tab-pane" id="tab-analyze">
  <div class="analyze-box">
    <div class="search-card">
      <h3>🔍 Analyze a Stock</h3>
      <p>Enter any NSE symbol for technical + fundamental analysis using live market data</p>
      <input class="sym-input" type="text" id="symInput" placeholder="e.g. RELIANCE" maxlength="20"
        oninput="this.value=this.value.toUpperCase()"
        onkeydown="if(event.key==='Enter')runAnalyze()">
      <button class="btn btn-primary" style="width:100%;margin-bottom:14px" onclick="runAnalyze()">
        Analyze →
      </button>
      <div style="font-size:11px;color:var(--muted);margin-bottom:8px">Quick picks:</div>
      <div class="quick-syms">
        <?php foreach (['RELIANCE','TCS','INFY','HDFCBANK','ICICIBANK','BAJFINANCE','AXISBANK','WIPRO','TATAMOTORS','SUNPHARMA','NIFTY50','MARUTI','LT','TITAN','KOTAKBANK'] as $s): ?>
          <span class="qsym" onclick="quickSym('<?= $s ?>')"><?= $s ?></span>
        <?php endforeach; ?>
      </div>
      <div id="histList" style="margin-top:16px;border-top:1px solid var(--border);padding-top:14px;display:none">
        <div style="font-size:11px;color:var(--muted);margin-bottom:8px">Recent:</div>
        <div id="histItems"></div>
      </div>
      <div style="margin-top:14px;font-size:11px;color:var(--green);background:rgba(16,185,129,.06);border:1px solid rgba(16,185,129,.15);border-radius:8px;padding:8px 10px">
        ✅ Live NSE data · EMA, RSI, MACD, BB, Supertrend
      </div>
    </div>
    <div class="analysis-result" id="analyzeResult">
      <div class="result-placeholder">
        <div class="icon">🔬</div>
        <div style="font-size:14px;font-weight:600;color:var(--muted2);margin-bottom:6px">Stock Analysis</div>
        <div style="font-size:12px">Enter a symbol and click Analyze<br>for full technical + fundamental breakdown<br>using live NSE market data</div>
      </div>
    </div>
  </div>
</div>

<!-- NEWS TAB -->
<div class="tab-pane" id="tab-news">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
    <h2 style="font-size:1.1rem;font-weight:600;color:#fff">📰 Market News &amp; Events</h2>
    <div style="display:flex;align-items:center;gap:10px">
      <span class="source-badge">Economic Times + Moneycontrol RSS</span>
      <button class="btn btn-outline" onclick="loadNews(true)" style="padding:6px 14px;font-size:12px">🔄 Refresh</button>
    </div>
  </div>
  <div id="newsContainer">
    <div class="loading-card"><div class="spin"></div><div>Loading market news…</div></div>
  </div>
</div>

<!-- LEADERS TAB -->
<div class="tab-pane" id="tab-leaders">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px">
    <div>
      <h2 style="font-size:1.1rem;font-weight:700;color:#fff">🏆 Signal Leaders</h2>
      <div style="font-size:12px;color:var(--muted);margin-top:3px">Stocks accumulating the most Buy/Sell signals over time — updated every minute</div>
    </div>
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
      <div id="tickStatus" style="font-size:12px;color:var(--muted)">⏳ Waiting for first tick…</div>
      <button class="btn btn-outline" onclick="forceTick()" style="padding:6px 14px;font-size:12px">▶ Tick Now</button>
      <button class="btn btn-outline" onclick="loadLeaders()" style="padding:6px 14px;font-size:12px">🔄 Refresh Leaders</button>
    </div>
  </div>

  <!-- Prakash intraday Buy/Sell boxes with entry price + 1% target + achieved status -->
  <div class="panel" style="margin-bottom:16px;padding:14px 16px">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;flex-wrap:wrap;gap:8px">
      <h3 style="font-size:.95rem;font-weight:700;color:#fff;margin:0">🎯 Momentum Intraday Recommendations</h3>
      <div id="prakashDailySummary" style="font-size:12px;color:var(--muted)"></div>
    </div>
    <div id="prakashBoxes" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:12px"></div>
    <div id="prakashTopPicks"></div>
  </div>

  <!-- Prakash Track Record: cross-day win-rate rollup -->
  <div class="panel" style="margin-bottom:16px;padding:14px 16px">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;flex-wrap:wrap;gap:8px">
      <h3 style="font-size:.95rem;font-weight:700;color:#fff;margin:0">📊 Momentum Track Record</h3>
      <div style="display:flex;gap:8px">
        <button class="btn btn-outline" onclick="openTrackRecordDetails('prakash')" style="padding:5px 12px;font-size:11px">🔍 View All Details</button>
        <button class="btn btn-outline" onclick="loadPrakashRollup()" style="padding:5px 12px;font-size:11px">🔄 Refresh</button>
      </div>
    </div>
    <div id="prakashRollup"><div style="font-size:12px;color:var(--muted)">Loading track record…</div></div>
  </div>

  <!-- AI intraday Buy/Sell boxes with entry price + 1% target + achieved status,
       driven by this app's own indicator signal engine (signals.php) rather
       than Prakash's rank-movement logic. -->
  <div class="panel" style="margin-bottom:16px;padding:14px 16px">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;flex-wrap:wrap;gap:8px">
      <h3 style="font-size:.95rem;font-weight:700;color:#fff;margin:0">🤖 AI Intraday Recommendations</h3>
      <div id="aiDailySummary" style="font-size:12px;color:var(--muted)"></div>
    </div>
    <div id="aiBoxes" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:12px"></div>
  </div>

  <!-- AI Track Record: cross-day win-rate rollup, same shape as Prakash's -->
  <div class="panel" style="margin-bottom:16px;padding:14px 16px">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;flex-wrap:wrap;gap:8px">
      <h3 style="font-size:.95rem;font-weight:700;color:#fff;margin:0">📊 AI Track Record</h3>
      <div style="display:flex;gap:8px">
        <button class="btn btn-outline" onclick="openTrackRecordDetails('ai')" style="padding:5px 12px;font-size:11px">🔍 View All Details</button>
        <button class="btn btn-outline" onclick="loadAiRollup()" style="padding:5px 12px;font-size:11px">🔄 Refresh</button>
      </div>
    </div>
    <div id="aiRollup"><div style="font-size:12px;color:var(--muted)">Loading track record…</div></div>
  </div>

  <!-- Top Recommendation for Today: every distinct stock that appeared in
       either engine's Buy/Sell box at any point today (not just the latest
       5-per-refresh snapshot), so a stock/two coming up across refreshes
       isn't lost when the box rotates. -->
  <div class="panel" style="margin-bottom:16px;padding:14px 16px">
    <h3 style="font-size:.95rem;font-weight:700;color:#fff;margin:0 0 10px">⭐ Top Recommendation for Today</h3>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:16px">
      <div>
        <div style="font-size:12px;font-weight:700;color:var(--accent2);text-transform:uppercase;letter-spacing:.6px;margin-bottom:8px">🎯 Momentum</div>
        <div style="font-size:11px;color:var(--green);font-weight:700;margin-bottom:4px">Today Buy</div>
        <div id="prakashTodayBuy" style="display:flex;flex-direction:column;gap:6px;margin-bottom:10px"></div>
        <div style="font-size:11px;color:var(--red);font-weight:700;margin-bottom:4px">Today Sell</div>
        <div id="prakashTodaySell" style="display:flex;flex-direction:column;gap:6px"></div>
      </div>
      <div>
        <div style="font-size:12px;font-weight:700;color:var(--accent2);text-transform:uppercase;letter-spacing:.6px;margin-bottom:8px">🤖 AI</div>
        <div style="font-size:11px;color:var(--green);font-weight:700;margin-bottom:4px">Today Buy</div>
        <div id="aiTodayBuy" style="display:flex;flex-direction:column;gap:6px;margin-bottom:10px"></div>
        <div style="font-size:11px;color:var(--red);font-weight:700;margin-bottom:4px">Today Sell</div>
        <div id="aiTodaySell" style="display:flex;flex-direction:column;gap:6px"></div>
      </div>
    </div>
  </div>

  <!-- Track Record detail modal: full stock-level list behind the rollup
       numbers (task: "so user can see and trust on this data") -->
  <div id="trackRecordModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center;padding:20px">
    <div style="background:var(--panel,#171923);border:1px solid var(--border);border-radius:12px;max-width:820px;width:100%;max-height:85vh;display:flex;flex-direction:column">
      <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border-bottom:1px solid var(--border)">
        <h3 id="trackRecordModalTitle" style="font-size:.95rem;font-weight:700;color:#fff;margin:0">Track Record Details</h3>
        <button class="btn btn-outline" onclick="closeTrackRecordDetails()" style="padding:4px 10px;font-size:12px">✕ Close</button>
      </div>
      <div id="trackRecordModalBody" style="padding:14px 18px;overflow-y:auto"></div>
    </div>
  </div>

  <!-- Live ticker strip -->
  <div class="panel" style="margin-bottom:16px">
    <div style="padding:10px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span style="font-size:11px;text-transform:uppercase;letter-spacing:.7px;color:var(--muted)">📡 Live Prices — Last Tick</span>
      <span id="liveTick" style="font-size:11px;color:var(--accent2)">—</span>
    </div>
    <div id="liveStrip" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr))">
      <div style="padding:20px;color:var(--muted);font-size:12px">Prices will appear after first tick…</div>
    </div>
  </div>

  <!-- Leaderboards -->
  <div id="leadersContent">
    <div class="loading-card"><div class="spin"></div><div>Waiting for signal data…</div><div style="font-size:11px;color:var(--muted)">Click "Tick Now" to start tracking</div></div>
  </div>
</div>

<!-- INTRADAY CHART TAB -->
<div class="tab-pane" id="tab-intraday">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px">
    <h2 style="font-size:1.1rem;font-weight:700;color:#fff">📉 Intraday Chart</h2>
    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
      <input type="text" id="chartSymInput" placeholder="e.g. RELIANCE" maxlength="20"
        style="background:var(--panel2);border:1px solid var(--border2);border-radius:8px;padding:7px 12px;color:#fff;font-size:13px;font-weight:700;text-transform:uppercase;outline:none;width:140px"
        oninput="this.value=this.value.toUpperCase()"
        onkeydown="if(event.key==='Enter')loadChart()">
      <select id="chartInterval" style="background:var(--panel2);border:1px solid var(--border2);border-radius:8px;padding:7px 10px;color:#fff;font-size:12px;outline:none">
        <option value="5m">5 Min</option>
        <option value="15m">15 Min</option>
        <option value="1h">1 Hour</option>
      </select>
      <button class="btn btn-primary" onclick="loadChart()" style="padding:7px 16px">📊 Load Chart</button>
    </div>
  </div>

  <div class="panel" style="margin-bottom:14px">
    <div id="chartStatus" style="padding:40px;text-align:center;color:var(--muted)">
      Enter a symbol above and click Load Chart
    </div>
    <div id="chartWrap" style="display:none;padding:16px">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;flex-wrap:wrap;gap:8px">
        <div id="chartTitle" style="font-size:14px;font-weight:700;color:#fff"></div>
        <div id="chartMeta" style="font-size:12px;color:var(--muted)"></div>
      </div>
      <!-- Price line chart using SVG -->
      <div style="position:relative;height:280px;background:rgba(255,255,255,.02);border:1px solid var(--border);border-radius:8px;overflow:hidden">
        <canvas id="priceChart" style="width:100%;height:100%"></canvas>
      </div>
      <!-- Volume bars -->
      <div style="margin-top:8px;position:relative;height:70px;background:rgba(255,255,255,.02);border:1px solid var(--border);border-radius:8px;overflow:hidden">
        <canvas id="volChart" style="width:100%;height:100%"></canvas>
      </div>
      <!-- Intraday stats -->
      <div id="intradayStats" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:10px;margin-top:12px"></div>
      <!-- Pivot levels -->
      <div id="pivotDisplay" style="margin-top:12px"></div>
    </div>
  </div>
</div>

<!-- EOD REPORT TAB -->
<div class="tab-pane" id="tab-eodreport">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px">
    <div>
      <h2 style="font-size:1.1rem;font-weight:700;color:#fff">📋 End-of-Day Signal Report</h2>
      <div style="font-size:12px;color:var(--muted);margin-top:3px">Track every Buy/Sell signal given today — see if targets were hit ✅ or missed ❌</div>
    </div>
    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
      <select id="eodDatePicker" onchange="loadEodReport(this.value)"
        style="background:var(--panel2);border:1px solid var(--border2);border-radius:8px;padding:7px 12px;color:#fff;font-size:12px;outline:none">
        <option value="">Today</option>
      </select>
      <button class="btn btn-primary" onclick="loadEodReport()" style="padding:7px 16px">🔄 Refresh</button>
      <button class="btn btn-outline" onclick="downloadCombinedEodReport()" style="padding:7px 16px">⬇ Download Momentum+AI Report (CSV)</button>
    </div>
  </div>

  <!-- Combined Prakash + AI recommendation log for the selected date — every
       stock either engine recommended, with entry/target price and whether
       it succeeded or failed, for the person's own EOD understanding. -->
  <div class="panel" id="combinedEodPanel" style="margin-bottom:16px;padding:14px 16px">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;flex-wrap:wrap;gap:8px">
      <h3 style="font-size:.95rem;font-weight:700;color:#fff;margin:0">🗂 Momentum + AI Recommendation Log</h3>
      <div id="combinedEodSummary" style="font-size:12px;color:var(--muted)"></div>
    </div>
    <div id="combinedEodTable" style="font-size:12px"><div style="color:var(--muted)">Loading…</div></div>
  </div>

  <!-- Summary KPI row -->
  <div id="eodSummary" style="display:none;margin-bottom:16px">
    <div class="kpi-row">
      <div class="kpi blue"><div class="kpi-label">Total Signals</div><div class="kpi-val" id="eodTotal">—</div><div class="kpi-sub">tracked today</div></div>
      <div class="kpi green"><div class="kpi-label">Targets Hit ✅</div><div class="kpi-val" id="eodHits">—</div><div class="kpi-sub" id="eodHitSub">achieved target</div></div>
      <div class="kpi red"><div class="kpi-label">SL Hit / Missed ❌</div><div class="kpi-val" id="eodMisses">—</div><div class="kpi-sub">stopped out</div></div>
      <div class="kpi orange"><div class="kpi-label">Still Open ⏳</div><div class="kpi-val" id="eodPending">—</div><div class="kpi-sub">awaiting outcome</div></div>
      <div class="kpi" style="border-top:3px solid #a78bfa"><div class="kpi-label">Hit Rate</div><div class="kpi-val" id="eodHitPct" style="font-size:2rem">—</div><div class="kpi-sub">of resolved signals</div></div>
    </div>
    <!-- Hit rate progress bar -->
    <div id="eodProgressWrap" style="background:var(--panel);border:1px solid var(--border);border-radius:var(--r);padding:16px;margin-bottom:12px">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
        <span style="font-size:12px;font-weight:600;color:#fff">Overall Accuracy</span>
        <span id="eodAccLabel" style="font-size:13px;font-weight:700;color:var(--green)"></span>
      </div>
      <div style="height:12px;background:rgba(255,255,255,.07);border-radius:6px;overflow:hidden">
        <div id="eodProgressBar" style="height:100%;border-radius:6px;background:linear-gradient(90deg,var(--green),#34d399);width:0%;transition:width .6s ease"></div>
      </div>
      <div style="display:flex;justify-content:space-between;margin-top:6px;font-size:10px;color:var(--muted)">
        <span>0% Miss</span><span>50% Neutral</span><span>100% Perfect</span>
      </div>
    </div>
  </div>

  <!-- Signals table -->
  <div class="panel" id="eodPanel">
    <div id="eodLoading" style="padding:50px;text-align:center;color:var(--muted)">
      <div class="spin" style="width:28px;height:28px;margin:0 auto 14px"></div>
      <div>Loading EOD report…</div>
    </div>
    <div id="eodTable" style="display:none"></div>
    <div id="eodEmpty" style="display:none;padding:50px;text-align:center;color:var(--muted)">
      <div style="font-size:40px;margin-bottom:12px;opacity:.4">📋</div>
      <div style="font-weight:600;color:#fff;margin-bottom:6px">No signals tracked yet for this date</div>
      <div style="font-size:12px">Signals are saved automatically when you view the Watchlist with Buy/Sell recommendations.<br>They will appear here with live target tracking.</div>
    </div>
  </div>
</div>

</div><!-- /wrap -->


<script>
const BASE_PATH = '<?php
$_sn = $_SERVER["SCRIPT_NAME"] ?? "";
$_sn = str_replace(["/public/index.php", "/index.php"], "", $_sn);
echo rtrim($_sn, "/");
?>';
function apiUrl(path){ return BASE_PATH + '/' + path.replace(/^\//,''); }
function tick(){document.getElementById('clock').textContent=new Date().toLocaleTimeString('en-IN',{timeZone:'Asia/Kolkata'});}
setInterval(tick,1000);tick();

// ── Tab switching ─────────────────────────────────────────────
function showTab(name,btn){
  document.querySelectorAll('.tab-pane').forEach(e=>e.classList.remove('active'));
  document.querySelectorAll('.nb').forEach(e=>e.classList.remove('active'));
  document.getElementById('tab-'+name).classList.add('active');
  if(btn) btn.classList.add('active');
  if(name==='news'&&!newsLoaded) loadNews();
  if(name==='leaders'){
    if(!leaderLoaded) loadLeaders();
    if(!prakashRollupLoaded) loadPrakashRollup();
    if(!tickTimer){
      forceTick();
      tickTimer=setInterval(forceTick, TICK_INTERVAL);
    }
  }
}

// ══ WATCHLIST ════════════════════════════════════════════════
let cdTotal=300,cdCur=300,cdTimer=null;

function startCountdown(){
  clearInterval(cdTimer);
  cdCur=cdTotal;
  cdTimer=setInterval(()=>{
    cdCur--;
    document.getElementById('cdSec').textContent=cdCur;
    document.getElementById('rbar').style.width=(cdCur/cdTotal*100)+'%';
    if(cdCur<=0){loadWatchlist(true);}
  },1000);
}

// ── State ─────────────────────────────────────────────────────
let wlPage=1, wlSector='', wlSearch='', wlTotalPages=1, wlLoading=false;

async function loadWatchlist(force=false){
  if(wlLoading) return;
  wlLoading=true;
  document.getElementById('watchLoading').style.display='flex';
  document.getElementById('watchLoading').innerHTML=`<div class="spin"></div><div>Connecting to data sources…</div>`;
  document.getElementById('watchTable').style.display='none';
  const rb=document.getElementById('refreshBtn');
  if(rb){rb.disabled=true;rb.textContent='⏳ Loading…';}
  try{
    // Step 1: get symbols
    const wlRes=await fetch(apiUrl('api/watchlist/list'));
    const wlData=await wlRes.json();
    let symbols=wlData.watchlist||[];
    const watchlistMeta=wlData.meta||{};
    if(!symbols.length) symbols=['RELIANCE.NS','TCS.NS','HDFCBANK.NS','INFY.NS','ICICIBANK.NS'];
    const sourceLabel = watchlistMeta.used_default_fallback ? 'Default universe' : 'Custom watchlist';
    const sourceBadge = document.getElementById('watchlistSourceBadge');
    if(sourceBadge){ sourceBadge.textContent = sourceLabel; }

    // Step 2: identify current-page symbols for priority fetch
    const PAGE=20;
    const pageStart=(wlPage-1)*PAGE;
    const pageSyms=symbols.slice(pageStart,pageStart+PAGE);
    const restSyms=symbols.filter(s=>!pageSyms.includes(s));

    document.getElementById('watchLoading').innerHTML=`<div class="spin"></div><div>Fetching quotes for ${pageSyms.length} stocks on this page…</div><div style="font-size:11px;color:var(--muted)">Fetching directly from browser (bypasses server IP restrictions)</div>`;

    // Step 3: fetch page quotes first (priority), then rest in background
    const pageQuotes=await fetchQuotesDirect(pageSyms);

    if(pageQuotes.length>0){
      document.getElementById('watchLoading').innerHTML=`<div class="spin"></div><div>Got ${pageQuotes.length} quotes — pushing to server for analysis…</div>`;
      // MUST await this — PHP reads bulk_quotes.json immediately after
      await fetch(apiUrl('api/proxy/quotes'),{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(pageQuotes)});
      // Background fetch for remaining symbols (fire-and-forget, no await)
      if(restSyms.length>0){
        fetchQuotesDirect(restSyms).then(rest=>{
          if(rest.length>0) fetch(apiUrl('api/proxy/quotes'),{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(rest)});
        });
      }
    } else {
      // All browser sources failed too — try server-side as last resort
      document.getElementById('watchLoading').innerHTML=`<div class="spin"></div><div>Browser fetch failed — trying server-side sources…</div>`;
      try{
        const r=await fetch(apiUrl('api/quotes/bulk'),{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({symbols:pageSyms})});
        if(r.ok){const d=await r.json();if(d.count>0) console.log('Server fallback succeeded:',d.count,'quotes');}
      }catch(e){console.warn('Server fallback also failed:',e);}
    }

    // Step 4: run TA on server (reads from bulk_quotes.json populated above)
    document.getElementById('watchLoading').innerHTML=`<div class="spin"></div><div>Running technical analysis (RSI, MACD, EMA, Supertrend…)</div>`;

    const url=apiUrl('api/watchlist/page')
      +'?page='+wlPage
      +(wlSector?'&sector='+encodeURIComponent(wlSector):'')
      +(wlSearch?'&search='+encodeURIComponent(wlSearch):'');
    const r=await fetch(url);
    const text=await r.text();
    let d;
    try{ d=JSON.parse(text); }
    catch(je){
      document.getElementById('watchLoading').innerHTML=`<div class="err-box"><strong>API Error (not JSON)</strong><br>URL: <code>${escHtml(url)}</code><br>Status: ${r.status}<br>Response: <code>${escHtml(text.slice(0,200))}</code></div>`;
      return;
    }
    if(d.error&&!d.stocks?.length){
      document.getElementById('watchLoading').innerHTML=`<div class="err-box">${escHtml(d.error)}</div>`;
      return;
    }
    if(d.warning){
      document.getElementById('watchLoading').style.display='flex';
      document.getElementById('watchLoading').innerHTML=`<div class="err-box" style="width:100%">⚠️ ${escHtml(d.warning)}<br><small>Quotes fetched: ${d.quotes_fetched||0} · Skipped (no quote): ${d.skipped_no_quote||0}</small></div>`;
      if(!d.stocks?.length) return;
    }
    wlTotalPages=d.total_pages||1;
    renderWatchlist(d);
    renderPagination(d);
    renderPrakashRecommendations(d.prakash_recommendations);
    renderAiRecommendations(d.ai_recommendations);
    startCountdown();
  }catch(e){
    document.getElementById('watchLoading').innerHTML=`<div class="err-box">Error: ${escHtml(e.message)}</div>`;
  }finally{
    wlLoading=false;
    if(rb){rb.disabled=false;rb.textContent='🔄 Refresh';}
  }
}

// ── Fetch quotes directly from browser ───────────────────────────
// Strategy: try multiple sources that work from browser (not server)
// ── Data fetching: goes through OUR server, not directly to Yahoo/Groww ──
// The server now has real API keys (Twelve Data + EODHD) so all data
// fetching happens server-side, no CORS issues, no IP blocks on the browser.
// The browser just calls our own /api endpoints.

async function fetchQuotesDirect(symbols){
  // Ask OUR server to bulk-fetch from Twelve Data (which it can reach)
  try{
    const r=await fetch(apiUrl('api/quotes/bulk'),{
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body:JSON.stringify({symbols})
    });
    if(r.ok){
      const d=await r.json();
      if(d.quotes&&d.quotes.length>0) return d.quotes;
    }
  }catch(e){}
  return [];
}

// Legacy alias kept for EOD report and other callers
async function browserFetchQuotes(symbols){ return fetchQuotesDirect(symbols); }

// History goes through our server too (Twelve Data time_series)
async function browserFetchHistory(yahooSym){
  const sym=yahooSym.replace('.NS','');
  try{
    const r=await fetch(apiUrl('api/history')+'?symbol='+encodeURIComponent(sym)+'&days=90');
    if(r.ok){
      const d=await r.json();
      if(d.rows&&d.rows.length>0) return d.rows;
    }
  }catch(e){}
  return [];
}

function parseYahooChart(j){
  const chart=j?.chart?.result?.[0];
  if(!chart) return [];
  const ts=chart.timestamp||[];
  const ohlcv=chart.indicators?.quote?.[0]||{};
  return ts.map((t,i)=>({
    date:new Date(t*1000).toISOString().slice(0,10),
    open:+(ohlcv.open?.[i]||0).toFixed(2),
    high:+(ohlcv.high?.[i]||0).toFixed(2),
    low:+(ohlcv.low?.[i]||0).toFixed(2),
    close:+(ohlcv.close?.[i]||0).toFixed(2),
    volume:ohlcv.volume?.[i]||0
  })).filter(r=>r.close>0);
}

function goPage(p){wlPage=p;loadWatchlist();}
function setSector(s){wlSector=s;wlPage=1;loadWatchlist();}
async function clearYahooCache(){
  const btn=document.getElementById('clearCacheBtn');
  if(btn){btn.disabled=true;btn.textContent='⏳ Clearing…';}
  try{
    const r=await fetch(apiUrl('api/cache/clear'));
    const d=await r.json();
    if(btn){btn.disabled=false;btn.textContent='🗑️ Clear Cache';}
    wlPage=1;
    loadWatchlist(true);
  }catch(e){
    if(btn){btn.disabled=false;btn.textContent='🗑️ Clear Cache';}
    alert('Cache clear failed: '+e.message);
  }
}
function setSearch(q){wlSearch=q;wlPage=1;loadWatchlist();}

function renderPagination(d){
  const el=document.getElementById('wlPagination');
  if(!el) return;
  const tp=d.total_pages||1, cp=d.page||1, ts=d.total_stocks||0;
  let html=`<div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;padding:10px 0">
    <span style="font-size:11px;color:var(--muted)">${ts} stocks · Page ${cp} of ${tp} · 20 per page</span>
    <div style="display:flex;gap:4px;flex-wrap:wrap">`;
  if(cp>1) html+=`<button onclick="goPage(1)" style="${pgBtn()}">«</button><button onclick="goPage(${cp-1})" style="${pgBtn()}">‹</button>`;
  // Show window of pages
  const start=Math.max(1,cp-2), end=Math.min(tp,cp+2);
  for(let i=start;i<=end;i++){
    html+=`<button onclick="goPage(${i})" style="${pgBtn(i===cp)}">${i}</button>`;
  }
  if(cp<tp) html+=`<button onclick="goPage(${cp+1})" style="${pgBtn()}">›</button><button onclick="goPage(${tp})" style="${pgBtn()}">»</button>`;
  html+='</div></div>';
  el.innerHTML=html;
}
function pgBtn(active=false){
  return `font-size:11px;padding:3px 9px;border-radius:5px;cursor:pointer;border:1px solid ${active?'var(--accent)':'var(--border)'};background:${active?'rgba(0,114,255,.2)':'transparent'};color:${active?'var(--accent2)':'var(--muted)'}`;
}

async function loadSectors(){
  try{
    const r=await fetch(apiUrl('api/sectors'));
    const d=await r.json();
    const el=document.getElementById('sectorFilter');
    if(!el) return;
    el.innerHTML='<option value="">All Sectors</option>'
      +(d.sectors||[]).map(s=>`<option value="${escHtml(s)}">${escHtml(s)}</option>`).join('');
  }catch(e){}
}

function renderPrakashRecommendations(rec){ renderEngineRecommendations('prakash', rec); }
function renderAiRecommendations(rec){ renderEngineRecommendations('ai', rec); }

// Shared renderer for both engines — 'prakash' uses ids prakashRecommendations/
// prakashBoxes/prakashDailySummary/prakashTodayBuy/prakashTodaySell, 'ai' uses
// the aiXxx equivalents. Behavior is otherwise identical.
function renderEngineRecommendations(engine, rec){
  const el=document.getElementById(engine+'Recommendations');
  const isPrakash=engine==='prakash';
  if(el){
    if(!rec){ el.innerHTML=''; }
    else{
      const buy=rec.buy_recommendation||rec.top_gainer||null;
      const sell=rec.sell_recommendation||rec.top_loser||null;
      const cardStyle='background:linear-gradient(135deg,rgba(255,255,255,.06),rgba(255,255,255,.03));border:1px solid var(--border);border-radius:12px;padding:12px 14px;min-height:120px';
      const buyTitle=isPrakash?(buy?.reason==='Rank Movement Up'?'Rank Movement Buy':'Momentum Buy Pick'):(buy?.reason||'Top Buy — AI');
      const sellTitle=isPrakash?(sell?.reason==='Rank Movement Down'?'Rank Movement Sell':'Momentum Sell Pick'):(sell?.reason||'Top Sell — AI');

      const buyHtml=`<div style="${cardStyle}">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.6px;color:var(--muted);margin-bottom:6px">${escHtml(buyTitle)}</div>
        <div style="font-size:16px;font-weight:700;color:#fff;margin-bottom:4px">${escHtml(buy?.symbol||'—')}</div>
        <div style="font-size:12px;color:var(--green);margin-bottom:4px">${buy?.recommendation||'Buy'} · ${buy?.reason||''}</div>
        <div style="font-size:12px;color:var(--muted2)">Price: ₹${fmtNum(buy?.price||0)} · Change: ${fmtNum(buy?.percentage_change||0)}%${buy?.confidence!==undefined&&buy?.confidence!==null?` · Confidence: ${fmtNum(buy.confidence)}%`:''}</div>
        ${buy?.previous_rank!==null&&buy?.previous_rank!==undefined&&buy?.current_rank!==null&&buy?.current_rank!==undefined?`<div style="font-size:11px;color:var(--muted);margin-top:4px">Rank ${buy.previous_rank} → ${buy.current_rank}</div>`:''}
      </div>`;

      const sellHtml=`<div style="${cardStyle}">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.6px;color:var(--muted);margin-bottom:6px">${escHtml(sellTitle)}</div>
        <div style="font-size:16px;font-weight:700;color:#fff;margin-bottom:4px">${escHtml(sell?.symbol||'—')}</div>
        <div style="font-size:12px;color:var(--red);margin-bottom:4px">${sell?.recommendation||'Sell'} · ${sell?.reason||''}</div>
        <div style="font-size:12px;color:var(--muted2)">Price: ₹${fmtNum(sell?.price||0)} · Change: ${fmtNum(sell?.percentage_change||0)}%${sell?.confidence!==undefined&&sell?.confidence!==null?` · Confidence: ${fmtNum(sell.confidence)}%`:''}</div>
        ${sell?.previous_rank!==null&&sell?.previous_rank!==undefined&&sell?.current_rank!==null&&sell?.current_rank!==undefined?`<div style="font-size:11px;color:var(--muted);margin-top:4px">Rank ${sell.previous_rank} → ${sell.current_rank}</div>`:''}
      </div>`;

      el.innerHTML=`<div>${buy?buyHtml:'<div style="'+cardStyle+'">No buy recommendation yet</div>'}</div><div>${sell?sellHtml:'<div style="'+cardStyle+'">No sell recommendation yet</div>'}</div>`;
    }
  }
  renderEngineBoxes(engine, rec);
  renderEngineTodayLists(engine, rec);
}

// Renders the up-to-5-stock Buy/Sell boxes with locked-in entry price,
// 1% intraday target, and achieved/open status — pulled from
// rec.buy_box / rec.sell_box / rec.daily_summary (populated server-side by
// buildPrakashRecommendations() / buildAiRecommendations()).
function renderEngineBoxes(engine, rec){
  const el=document.getElementById(engine+'Boxes');
  const summaryEl=document.getElementById(engine+'DailySummary');
  if(!el) return;
  if(!rec){el.innerHTML='';if(summaryEl)summaryEl.textContent='';return;}

  const daily=rec.daily_summary||null;
  const dailyBySymbol={};
  (daily?.recommendations||[]).forEach(r=>{dailyBySymbol[r.symbol]=r;});

  if(summaryEl){
    if(daily && daily.total>0){
      const rate=daily.success_rate!==null?daily.success_rate+'%':'—';
      summaryEl.innerHTML=`Today: <strong style="color:#fff">${daily.achieved}/${daily.total}</strong> targets hit · <strong style="color:${daily.success_rate>=50?'var(--green)':'var(--red)'}">${rate}</strong>${daily.closed?' · <span style="color:var(--muted)">Market closed</span>':''}`;
    } else {
      summaryEl.textContent='No recommendations yet today';
    }
  }

  function boxCard(entry,side){
    const isBuy=side==='Buy';
    const symbol=escHtml(entry.symbol||'—');
    const trackedDaily=dailyBySymbol[entry.symbol]||null;
    const entryPrice=trackedDaily?.entry_price ?? entry.price ?? 0;
    const targetPrice=trackedDaily?.target_price ?? null;
    const achieved=trackedDaily?.achieved || false;
    const achievedAt=trackedDaily?.achieved_at || null;
    const statusColor=achieved?'var(--green)':'var(--muted)';
    const statusText=achieved?`✅ Target hit${achievedAt?' @ '+achievedAt.split(' ')[1]:''}`:'⏳ Open';
    const sideColor=isBuy?'var(--green)':'var(--red)';
    const hasScore=entry.stars!==undefined && entry.stars!==null;
    const starsHtml=hasScore?`<div style="font-size:11px;color:${sideColor};margin-top:3px" title="Momentum point score: ${fmtNum(entry.point_score)}">${'⭐'.repeat(entry.stars)||'—'} ${escHtml(entry.tier||'')} (${fmtNum(entry.point_score)} pts)</div>`:'';
    return `<div style="background:linear-gradient(135deg,rgba(255,255,255,.06),rgba(255,255,255,.03));border:1px solid var(--border);border-radius:10px;padding:10px 12px">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
        <span style="font-size:14px;font-weight:700;color:#fff">${symbol}</span>
        <span style="font-size:10px;font-weight:700;color:${sideColor};text-transform:uppercase">${side}</span>
      </div>
      <div style="font-size:11px;color:var(--muted);margin-bottom:3px">${escHtml(entry.reason||'')}</div>
      <div style="font-size:12px;color:var(--muted2)">Entry: ₹${fmtNum(entryPrice)}${targetPrice?` → Target: ₹${fmtNum(targetPrice)}`:''}</div>
      <div style="font-size:11px;color:${statusColor};margin-top:4px;font-weight:600">${statusText}</div>
      ${starsHtml}
    </div>`;
  }

  const buyBox=rec.buy_box||[];
  const sellBox=rec.sell_box||[];
  if(buyBox.length===0 && sellBox.length===0){el.innerHTML='<div style="font-size:12px;color:var(--muted)">No box entries yet — waiting for next refresh</div>';return;}
  el.innerHTML = buyBox.map(e=>boxCard(e,'Buy')).join('') + sellBox.map(e=>boxCard(e,'Sell')).join('');
  renderEngineTopPicks(engine, rec);
}

// "Pick of the Day" — Top 5 Buy + Top 5 Sell across the WHOLE tracked
// universe, ranked by the point-score Momentum Ranking Engine (see
// app/momentum_score.php), not just whichever 5 made this refresh's
// momentum/new-entry box. Populated from rec.top5_buy / rec.top5_sell.
function renderEngineTopPicks(engine, rec){
  const el=document.getElementById(engine+'TopPicks');
  if(!el) return;
  const top5Buy=rec?.top5_buy||[];
  const top5Sell=rec?.top5_sell||[];
  if(top5Buy.length===0 && top5Sell.length===0){el.innerHTML='';return;}

  function pickRow(p,side){
    const sideColor=side==='Buy'?'var(--green)':'var(--red)';
    return `<div style="display:flex;justify-content:space-between;align-items:center;padding:6px 10px;background:rgba(255,255,255,.03);border-radius:6px;font-size:11px;gap:8px">
      <span style="color:#fff;font-weight:700;min-width:70px">${escHtml(p.symbol)}</span>
      <span style="color:var(--muted2)">₹${fmtNum(p.price)} · ${fmtNum(p.percentage_change)}%</span>
      <span style="color:${sideColor};font-weight:600;text-align:right">${'⭐'.repeat(p.stars)||'—'} ${escHtml(p.tier)} · ${fmtNum(p.score)} pts</span>
    </div>`;
  }
  const buyCol=`<div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">🏆 Top ${top5Buy.length} Buy</div>` +
    (top5Buy.length?top5Buy.map(p=>pickRow(p,'Buy')).join(''):'<div style="font-size:11px;color:var(--muted)">No candidates yet</div>');
  const sellCol=`<div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">🏆 Top ${top5Sell.length} Sell</div>` +
    (top5Sell.length?top5Sell.map(p=>pickRow(p,'Sell')).join(''):'<div style="font-size:11px;color:var(--muted)">No candidates yet</div>');
  el.innerHTML=`<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;margin-top:12px">
    <div style="display:flex;flex-direction:column;gap:5px">${buyCol}</div>
    <div style="display:flex;flex-direction:column;gap:5px">${sellCol}</div>
  </div>`;
}

// "Top Recommendation for Today": every distinct stock the engine has put in
// a Buy/Sell box at ANY point today, pulled straight from daily_summary.recommendations
// (which already accumulates across refreshes) — not just the latest 5-per-refresh
// snapshot, so a stock that showed up once earlier today doesn't disappear
// once the box rotates to different names.
function renderEngineTodayLists(engine, rec){
  const buyEl=document.getElementById(engine+'TodayBuy');
  const sellEl=document.getElementById(engine+'TodaySell');
  if(!buyEl || !sellEl) return;
  const recs=(rec?.daily_summary?.recommendations)||[];
  if(recs.length===0){
    buyEl.innerHTML='<div style="font-size:11px;color:var(--muted)">None yet today</div>';
    sellEl.innerHTML='<div style="font-size:11px;color:var(--muted)">None yet today</div>';
    return;
  }
  function row(r){
    const achieved=!!r.achieved;
    const statusColor=achieved?'var(--green)':'var(--muted)';
    const statusText=achieved?'✅ Hit':(r.final_status==='Not Achieved'?'❌ Not achieved':'⏳ Open');
    return `<div style="display:flex;justify-content:space-between;align-items:center;padding:6px 10px;background:rgba(255,255,255,.03);border-radius:6px;font-size:11px">
      <span style="color:#fff;font-weight:600">${escHtml(r.symbol)}</span>
      <span style="color:var(--muted2)">₹${fmtNum(r.entry_price)} → ₹${fmtNum(r.target_price)}</span>
      <span style="color:${statusColor};font-weight:600">${statusText}</span>
    </div>`;
  }
  const buys=recs.filter(r=>r.side==='Buy');
  const sells=recs.filter(r=>r.side==='Sell');
  buyEl.innerHTML = buys.length ? buys.map(row).join('') : '<div style="font-size:11px;color:var(--muted)">None yet today</div>';
  sellEl.innerHTML = sells.length ? sells.map(row).join('') : '<div style="font-size:11px;color:var(--muted)">None yet today</div>';
}

// Cross-day win-rate rollup from /api/prakash/rollup and /api/ai/rollup
async function loadPrakashRollup(){ loadEngineRollup('prakash'); }
async function loadAiRollup(){ loadEngineRollup('ai'); }

async function loadEngineRollup(engine){
  const el=document.getElementById(engine+'Rollup');
  if(!el) return;
  try{
    const r=await fetch(apiUrl('api/'+engine+'/rollup?days=30'));
    const d=await r.json();
    if(engine==='prakash') prakashRollupLoaded=true;
    if(d.error){ el.innerHTML=`<div class="err-box">${escHtml(d.error)}</div>`; return; }
    if(!d.days || d.days.length===0){ el.innerHTML='<div style="font-size:12px;color:var(--muted)">No daily history yet — check back after a full trading day.</div>'; return; }

    const overallRate=d.overall_success_rate!==null?d.overall_success_rate+'%':'—';
    const rateColor=(d.overall_success_rate||0)>=50?'var(--green)':'var(--red)';
    let html=`<div style="margin-bottom:12px;font-size:13px;color:var(--muted2)">Last ${d.days.length} day(s): <strong style="color:#fff">${d.overall_achieved}/${d.overall_total}</strong> targets hit · <strong style="color:${rateColor}">${overallRate}</strong> overall success rate</div>`;
    html+='<div style="display:flex;flex-direction:column;gap:6px;max-height:260px;overflow-y:auto">';
    d.days.forEach(day=>{
      const rate=day.success_rate!==null?day.success_rate+'%':'—';
      const rc=(day.success_rate||0)>=50?'var(--green)':'var(--red)';
      html+=`<div style="display:flex;justify-content:space-between;align-items:center;padding:6px 10px;background:rgba(255,255,255,.03);border-radius:6px;font-size:12px">
        <span style="color:var(--muted2)">${escHtml(day.date)}${day.closed?'':' <span style=\"color:var(--orange)\">(in progress)</span>'}</span>
        <span style="color:#fff">${day.achieved}/${day.total}</span>
        <span style="color:${rc};font-weight:600">${rate}</span>
      </div>`;
    });
    html+='</div>';
    el.innerHTML=html;
  }catch(e){
    el.innerHTML=`<div class="err-box">Error: ${escHtml(e.message)}</div>`;
  }
}

// "View All Details" modal — full stock-level list behind the rollup number,
// so the person can check every entry (symbol, entry/target price, hit or
// not) rather than just trusting the aggregate percentage.
async function openTrackRecordDetails(engine){
  const modal=document.getElementById('trackRecordModal');
  const title=document.getElementById('trackRecordModalTitle');
  const body=document.getElementById('trackRecordModalBody');
  if(!modal||!body) return;
  title.textContent=(engine==='prakash'?'📊 Momentum':'🤖 AI')+' Track Record — Full Details';
  body.innerHTML='<div style="padding:30px;text-align:center;color:var(--muted)">Loading…</div>';
  modal.style.display='flex';
  try{
    const r=await fetch(apiUrl('api/'+engine+'/rollup?days=30&details=1'));
    const d=await r.json();
    if(d.error){ body.innerHTML=`<div class="err-box">${escHtml(d.error)}</div>`; return; }
    if(!d.days || d.days.length===0){ body.innerHTML='<div style="color:var(--muted);font-size:13px">No daily history yet.</div>'; return; }
    let html='';
    d.days.forEach(day=>{
      const rate=day.success_rate!==null?day.success_rate+'%':'—';
      const rc=(day.success_rate||0)>=50?'var(--green)':'var(--red)';
      html+=`<div style="margin-bottom:16px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
          <span style="font-weight:700;color:#fff;font-size:13px">${escHtml(day.date)}${day.closed?'':' <span style=\"color:var(--orange);font-weight:400;font-size:11px\">(in progress)</span>'}</span>
          <span style="color:${rc};font-weight:700;font-size:12px">${day.achieved}/${day.total} · ${rate}</span>
        </div>
        <div style="display:flex;flex-direction:column;gap:4px">`;
      (day.recommendations||[]).forEach(r=>{
        const achieved=!!r.achieved;
        const statusColor=achieved?'var(--green)':(r.final_status==='Not Achieved'?'var(--red)':'var(--muted)');
        const statusText=achieved?`✅ Hit @ ₹${fmtNum(r.achieved_price||0)}${r.achieved_at?' ('+escHtml(r.achieved_at.split(' ')[1]||'')+')':''}`:(r.final_status==='Not Achieved'?'❌ Not achieved':'⏳ Open');
        const sideColor=r.side==='Buy'?'var(--green)':'var(--red)';
        html+=`<div style="display:grid;grid-template-columns:70px 60px 1fr 1fr;gap:8px;align-items:center;padding:5px 8px;background:rgba(255,255,255,.03);border-radius:6px;font-size:11px">
          <span style="color:#fff;font-weight:600">${escHtml(r.symbol)}</span>
          <span style="color:${sideColor};font-weight:700;text-transform:uppercase">${escHtml(r.side)}</span>
          <span style="color:var(--muted2)">Entry ₹${fmtNum(r.entry_price)} → Target ₹${fmtNum(r.target_price)}</span>
          <span style="color:${statusColor};font-weight:600">${statusText}</span>
        </div>`;
      });
      html+='</div></div>';
    });
    body.innerHTML=html;
  }catch(e){
    body.innerHTML=`<div class="err-box">Error: ${escHtml(e.message)}</div>`;
  }
}
function closeTrackRecordDetails(){
  const modal=document.getElementById('trackRecordModal');
  if(modal) modal.style.display='none';
}

function renderWatchlist(d){
  const all=d.stocks||[];
  const buys=d.buy_list||[];
  const sells=d.sell_list||[];
  const total=all.length;
  const buyCount=all.filter(s=>s.signal==='Buy').length;
  const sellCount=all.filter(s=>s.signal==='Sell').length;
  document.getElementById('kpiTotal').textContent=total;
  document.getElementById('kpiBuy').textContent=buyCount;
  document.getElementById('kpiBuyPct').textContent=total?Math.round(buyCount/total*100)+'% of watchlist':'';
  document.getElementById('kpiSell').textContent=sellCount;
  document.getElementById('kpiSellPct').textContent=total?Math.round(sellCount/total*100)+'% of watchlist':'';
  const mood=d.market_mood||'Neutral';
  const mc=mood==='Bullish'?'var(--green)':mood==='Bearish'?'var(--red)':'var(--orange)';
  document.getElementById('kpiMood').textContent=mood+(d.mood_score?' ('+d.mood_score+')':'');
  document.getElementById('kpiMood').style.color=mc;
  document.getElementById('kpiNifty').textContent=d.nifty_view||'';
  document.getElementById('kpiTime').textContent=new Date().toLocaleTimeString('en-IN',{timeZone:'Asia/Kolkata'});
  document.getElementById('kpiCached').textContent=d.cached?'cached (< 5 min)':'fresh data';
  document.getElementById('cacheNote').textContent=d.cached?'⚡ Cached':'🔴 Live';
  renderWatchlistManager(d.custom_watchlist||[]);

  function momBar(score){
    const pct=Math.min(Math.abs(score),100);
    const color=score>=40?'#10b981':score>=15?'#34d399':score>=-15?'#f59e0b':score>=-40?'#f87171':'#ef4444';
    const arrow=score>=15?'▲':score<=-15?'▼':'→';
    return `<div style="display:flex;align-items:center;gap:5px"><span style="font-weight:700;color:${color};font-size:12px">${arrow} ${score>0?'+':''}${score}</span><div style="flex:1;height:4px;background:rgba(255,255,255,.07);border-radius:3px;min-width:30px"><div style="width:${pct}%;height:100%;background:${color};border-radius:3px"></div></div></div>`;
  }

  function stockRow(s,rank){
    const chg=parseFloat(s.change_pct)||0,chg5=parseFloat(s.change_5d)||0;
    const sig=s.signal||'Hold';
    const bc=sig==='Buy'?'badge-buy':sig==='Sell'?'badge-sell':'badge-hold';
    const rsi=parseFloat(s.rsi)||0;
    const rsiC=rsi>70?'var(--red)':rsi<30?'var(--green)':'var(--accent2)';
    const stC=s.supertrend==='Bullish'?'var(--green)':'var(--red)';
    const adx=parseFloat(s.adx)||0;
    const adxC=adx>=25?'var(--green)':'var(--muted)';
    const sk=parseFloat(s.stoch_k)||50;
    const skC=sk>80?'var(--red)':sk<20?'var(--green)':'var(--accent2)';
    const pp=s.pivot_pp?`PP:${parseFloat(s.pivot_pp).toFixed(0)} R1:${parseFloat(s.pivot_r1||0).toFixed(0)} S1:${parseFloat(s.pivot_s1||0).toFixed(0)}`:'';
    const dirIcon=s.direction==='rising'?'🚀':s.direction==='falling'?'📉':'➡️';
    // Price vs target gap %
    const tgt=parseFloat(s.target)||0;
    const curP=parseFloat(s.price)||0;
    const tgtGap=curP>0&&tgt>0?((tgt-curP)/curP*100):0;
    const isBuySignal=sig==='Buy'||sig==='Strong Buy';
    const tgtGapStr=tgtGap!==0?`(${tgtGap>0?'+':''}${tgtGap.toFixed(1)}%)`:'';
    const sl=parseFloat(s.stoploss)||0;
    const slGap=curP>0&&sl>0?(((sl-curP)/curP)*100):0;
    // Auto-save signal to EOD tracker (fire-and-forget)
    if(sig==='Buy'||sig==='Sell'){saveSignalEod(s);}
    return `<tr>
      <td style="font-size:11px;color:var(--muted);text-align:center">#${rank}</td>
      <td><div class="sym">${escHtml(s.symbol||'')}</div><div class="co-name">${escHtml(s.name||'')}</div></td>
      <td class="price" style="font-weight:700;font-size:13px">₹${fmtNum(curP)}</td>
      <td class="${chg>=0?'chg-up':'chg-dn'}" style="font-weight:600">${chg>=0?'▲':'▼'}${Math.abs(chg).toFixed(2)}%</td>
      <td class="${chg5>=0?'chg-up':'chg-dn'}" style="font-size:11px">${chg5>=0?'+':''}${chg5.toFixed(2)}%</td>
      <td>${momBar(s.momentum_score)}</td>
      <td>${dirIcon} <span style="font-size:11px;color:var(--muted)">${escHtml(s.direction||'')}</span></td>
      <td><span style="font-size:11px;${s.vol_surge?'font-weight:700;color:var(--orange)':'color:var(--muted)'}">${escHtml(s.vol_label||'')}</span></td>
      <td><span style="color:${rsiC};font-weight:600">${rsi.toFixed(1)}</span></td>
      <td><span style="color:${stC};font-size:11px;font-weight:600">${escHtml(s.supertrend||'')}</span></td>
      <td><span style="color:${adxC};font-size:11px;font-weight:600">${adx?adx+' '+escHtml(s.adx_strength||''):'N/A'}</span><br><span style="font-size:9px;color:var(--muted)">${escHtml(s.adx_direction||'')}</span></td>
      <td><span style="color:${skC};font-weight:600;font-size:11px">${sk.toFixed(0)}</span><br><span style="font-size:9px;color:var(--muted)">${escHtml(s.stoch_signal||'')}</span></td>
      <td style="font-size:10px;color:var(--muted)">${escHtml(s.obv_trend||'—')}</td>
      <td><span class="badge ${bc}">${escHtml(sig)}</span></td>
      <td style="font-size:10px;color:var(--muted2);max-width:100px">${escHtml(s.pattern||'')}</td>
      <td style="min-width:140px">
        <div style="background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:8px;padding:7px 10px">
          <div style="font-size:10px;color:var(--muted);margin-bottom:4px;text-transform:uppercase;letter-spacing:.5px">Price → Target</div>
          <div style="display:flex;align-items:center;gap:6px;margin-bottom:3px">
            <span style="font-size:11px;color:var(--muted2)">Now</span>
            <span style="font-weight:700;color:#fff;font-size:13px">₹${fmtNum(curP)}</span>
          </div>
          <div style="display:flex;align-items:center;gap:6px;margin-bottom:3px">
            <span style="font-size:11px;color:var(--muted2)">T1</span>
            <span style="font-weight:700;color:var(--green);font-size:13px">₹${fmtNum(tgt)}</span>
            <span style="font-size:10px;color:var(--green);opacity:.8">${tgtGapStr}</span>
          </div>
          ${sl?`<div style="display:flex;align-items:center;gap:6px">
            <span style="font-size:11px;color:var(--muted2)">SL</span>
            <span style="font-weight:600;color:var(--red);font-size:12px">₹${fmtNum(sl)}</span>
            <span style="font-size:10px;color:var(--red);opacity:.8">(${slGap.toFixed(1)}%)</span>
          </div>`:''}
          ${pp?`<div style="font-size:9px;color:var(--muted);margin-top:4px;padding-top:4px;border-top:1px solid var(--border)">${pp}</div>`:''}
        </div>
      </td>
      <td>
        <button class="action-btn" onclick="analyzeFromWatch('${escHtml(s.symbol||'')}')" style="display:block;margin-bottom:3px">Analyze →</button>
        <button class="action-btn" onclick="setAlert('${escHtml(s.symbol||'')}',${s.price||0})" style="font-size:10px">🔔 Alert</button>
      </td>
    </tr>`;
  }

  function stockTable(list,title,color,icon){
    if(!list.length) return `<div style="padding:20px;color:var(--muted);font-size:13px">No ${title.includes('BUY')?'buy':'sell'} signals right now — stocks may be in a neutral/hold zone, Try refreshing or wait a moment for data to load.</div>`;
    return `<div style="padding:12px 18px 8px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px;flex-wrap:wrap">
      <span style="font-size:16px">${icon}</span>
      <span style="font-weight:700;color:${color};font-size:14px">${title}</span>
      <span style="font-size:11px;color:var(--muted)">${list.length} stocks</span>
    </div>
    <div style="overflow-x:auto"><table><thead><tr>
      <th>#</th><th>Symbol</th><th>Price</th><th>Day%</th><th>5D%</th>
      <th>Momentum</th><th>Direction</th><th>Volume</th><th>RSI</th>
      <th>Supertrend</th><th>ADX/DMI</th><th>Stoch</th><th>OBV</th>
      <th>Signal</th><th>Pattern</th><th>Target/SL+Pivots</th><th>Action</th>
    </tr></thead><tbody>${list.map((s,i)=>stockRow(s,i+1)).join('')}</tbody></table></div>`;
  }

  document.getElementById('watchLoading').style.display='none';
  document.getElementById('watchTable').innerHTML=`
    <div style="margin-bottom:16px">
      <div style="background:rgba(16,185,129,.06);border:1px solid rgba(16,185,129,.2);border-radius:var(--r);margin-bottom:12px">${stockTable(buys,'📈 BUY Candidates','var(--green)','🟢')}</div>
      <div style="background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.2);border-radius:var(--r)">${stockTable(sells,'📉 SELL / Avoid','var(--red)','🔴')}</div>
    </div>
    <div style="padding:8px;font-size:10px;color:var(--muted)">Score = Price×Volume + RSI + MACD + EMA + ADX + Supertrend · Live NSE Data · Educational only</div>`;
  document.getElementById('watchTable').style.display='block';
}

// Custom watchlist manager
async function addToWatchlist(){
  const sym=(document.getElementById('wlAddInput').value||'').trim().toUpperCase();
  if(!sym)return;
  const fd=new FormData(); fd.append('symbol',sym);
  const r=await fetch(apiUrl('api/watchlist/add'),{method:'POST',body:fd});
  const d=await r.json();
  if(d.ok){document.getElementById('wlAddInput').value='';renderWatchlistManager(d.watchlist);loadWatchlist(true);}
}
async function removeFromWatchlist(sym){
  const fd=new FormData(); fd.append('symbol',sym);
  const r=await fetch(apiUrl('api/watchlist/remove'),{method:'POST',body:fd});
  const d=await r.json();
  if(d.ok){renderWatchlistManager(d.watchlist);loadWatchlist(true);}
}
function renderWatchlistManager(wl){
  const el=document.getElementById('wlItems');
  if(!el)return;
  if(!wl||!wl.length){el.innerHTML='<span style="color:var(--muted);font-size:11px">Using default 5 stocks</span>';return;}
  el.innerHTML=wl.map(s=>`<span style="display:inline-flex;align-items:center;gap:3px;background:rgba(0,114,255,.1);border:1px solid rgba(0,114,255,.25);border-radius:5px;padding:2px 7px;font-size:11px;margin:2px">${escHtml(s.replace('.NS',''))}<button onclick="removeFromWatchlist('${escHtml(s)}')" style="background:none;border:none;color:var(--red);cursor:pointer;font-size:12px;padding:0 2px">×</button></span>`).join('');
}

// Alerts
function setAlert(sym,price){
  const cond=prompt('Set alert for '+sym+'\nFormat: above 1234 or below 1234','above '+Math.round(price*1.02));
  if(!cond)return;
  const m=cond.trim().match(/^(above|below)\s+([\d.]+)$/i);
  if(!m){alert('Use format: above 1234 or below 1234');return;}
  const fd=new FormData(); fd.append('symbol',sym); fd.append('condition',m[1].toLowerCase()); fd.append('price',m[2]);
  fetch(apiUrl('api/alerts/save'),{method:'POST',body:fd}).then(()=>alert('✅ Alert set: '+sym+' '+m[1]+' ₹'+m[2]));
}
setInterval(async()=>{
  try{const r=await fetch(apiUrl('api/alerts/check'));const d=await r.json();
  (d.triggered||[]).forEach(a=>alert('🔔 ALERT: '+a.symbol+' hit ₹'+a.triggered_price+' ('+a.condition+' ₹'+a.price+')'));}catch(e){}
},60000);


function analyzeFromWatch(sym){
  document.querySelectorAll('.tab-pane').forEach(e=>e.classList.remove('active'));
  document.querySelectorAll('.nb').forEach(e=>e.classList.remove('active'));
  document.getElementById('tab-analyze').classList.add('active');
  document.querySelectorAll('.nb')[1].classList.add('active');
  document.getElementById('symInput').value=sym;
  runAnalyze();
}

// ══ ANALYZE ══════════════════════════════════════════════════
let analyzeHistory=[];

function quickSym(s){document.getElementById('symInput').value=s;runAnalyze();}

async function runAnalyze(){
  const sym=document.getElementById('symInput').value.trim().toUpperCase();
  if(!sym){document.getElementById('symInput').focus();return;}

  const el=document.getElementById('analyzeResult');
  el.innerHTML=`<div class="loading-card"><div class="spin"></div>
    <div>Running technical analysis on <strong>${escHtml(sym)}</strong> (RSI, MACD, EMA, Supertrend…)</div>
  </div>`;

  try{
    // Analyze is now served entirely by the server (same BSE/Stooq/NSE
    // pipeline the Watchlist already uses successfully) instead of the
    // browser calling Yahoo Finance directly — that endpoint has been
    // shut down by Yahoo and was failing for every symbol, not just this one.
    const r=await fetch(apiUrl('api/analyze'),{
      method:'POST',
      headers:{'Content-Type':'application/x-www-form-urlencoded'},
      body:'symbol='+encodeURIComponent(sym)
    });
    const d=await r.json();
    if(d.error){
      el.innerHTML=`<div class="err-box"><strong>Analysis failed:</strong><br>${escHtml(d.error)}</div>`;
      return;
    }
    renderAnalysis(el,d);
    addHistory(sym);
    setTimeout(()=>loadChart(d.symbol||sym,'5m'), 100);
  }catch(e){
    el.innerHTML=`<div class="err-box">Error: ${escHtml(e.message)}</div>`;
  }
}

function renderAnalysis(el,d){
  const sig=(d.signal||'Hold').toLowerCase();
  const sigIcon=sig==='buy'?'🟢':sig==='sell'?'🔴':'🟡';
  const sigColor=sig==='buy'?'var(--green)':sig==='sell'?'var(--red)':'var(--orange)';
  const t=d.technicals||{};
  const f=d.fundamentals||{};
  const bs=d.buy_sell_reasoning||{};
  const ts=d.trade_setup||{};
  const pats=d.patterns||[];
  const chg=parseFloat(d.change_pct)||0;
  const ich=d.ichimoku||{};
  const fibs=d.fibonacci||{};
  const vol=d.volume_analysis||{};
  const mtf=d.multi_timeframe||{};
  const sb=d.score_breakdown||{};
  const pivots=d.pivot_points||{};
  const pos52=d.position_52w;

  function iv(v,bull,bear){
    if(v===null||v===undefined)return'<span class="neu-val">N/A</span>';
    const vs=String(v),ib=bull.some(b=>vs.toLowerCase().includes(b.toLowerCase())),ibe=bear.some(b=>vs.toLowerCase().includes(b.toLowerCase()));
    return`<span class="${ib?'bull-val':ibe?'bear-val':'neu-val'}">${escHtml(vs)}</span>`;
  }

  // ── 52W Range bar ────────────────────────────────────────────
  const rangeBar = pos52!=null ? `
    <div style="margin:8px 0 4px;display:flex;align-items:center;gap:10px;font-size:11px">
      <span style="color:var(--muted);min-width:60px">52W Low<br>₹${fmtNum(d['52w_low'])}</span>
      <div style="flex:1;position:relative">
        <div style="height:6px;background:linear-gradient(90deg,var(--red),var(--orange),var(--green));border-radius:3px"></div>
        <div style="position:absolute;top:-3px;left:${pos52}%;transform:translateX(-50%);width:12px;height:12px;background:#fff;border-radius:50%;border:2px solid ${sigColor};box-shadow:0 0 6px ${sigColor}"></div>
        <div style="position:absolute;top:10px;left:${pos52}%;transform:translateX(-50%);font-size:10px;font-weight:700;color:${sigColor};white-space:nowrap">${pos52}%</div>
      </div>
      <span style="color:var(--muted);min-width:60px;text-align:right">52W High<br>₹${fmtNum(d['52w_high'])}</span>
    </div>` : '';

  // ── Score gauge ───────────────────────────────────────────────
  const scoreTotal = sb.total||0;
  const scoreAbs   = Math.min(Math.abs(scoreTotal)*5,100);
  const scoreColor = scoreTotal>=3?'var(--green)':scoreTotal<=-3?'var(--red)':'var(--orange)';
  const scoreLabel = scoreTotal>=5?'Strong Buy':scoreTotal>=2?'Buy':scoreTotal>=-2?'Hold':scoreTotal>=-5?'Sell':'Strong Sell';

  // ── Volume bar ────────────────────────────────────────────────
  const volRatio = parseFloat(vol.ratio)||1;
  const volW     = Math.min(volRatio/3*100,100);
  const volColor = volRatio>=2?'var(--orange)':volRatio>=1.3?'var(--green)':'var(--muted)';

  el.innerHTML=`<div class="analysis-loaded">

    <!-- Header -->
    <div class="analysis-top">
      <div>
        <div class="analysis-sym">${escHtml(d.symbol||'')}</div>
        <div class="analysis-name">${escHtml(d.name||'')} · ${escHtml(d.sector||'')}${d.industry&&d.industry!==d.sector?' · '+escHtml(d.industry):''}</div>
        ${rangeBar}
      </div>
      <div class="analysis-price">
        <div class="price-big">₹${fmtNum(d.price)}</div>
        <div class="${chg>=0?'chg-up':'chg-dn'}" style="font-size:13px;font-weight:600">${chg>=0?'▲':'▼'}${Math.abs(chg).toFixed(2)}% today</div>
        <div class="big-signal ${sig}">${sigIcon} ${escHtml(d.signal||'Hold')} · ${d.confidence||0}% Confidence</div>
      </div>
    </div>

    <!-- Summary -->
    <div class="verdict-box">💡 <strong>Summary:</strong> ${escHtml(d.summary||'')}</div>

    <!-- Multi-timeframe alignment -->
    ${mtf.daily?`<div style="background:${mtf.aligned?'rgba(16,185,129,.06)':'rgba(245,158,11,.06)'};border:1px solid ${mtf.aligned?'rgba(16,185,129,.2)':'rgba(245,158,11,.2)'};border-radius:8px;padding:10px 14px;margin-bottom:12px;display:flex;align-items:center;gap:12px;flex-wrap:wrap">
      <span style="font-size:13px">${mtf.aligned?'✅':'⚠️'}</span>
      <div>
        <div style="font-size:12px;font-weight:600;color:#fff">Multi-Timeframe: Daily <span style="color:${mtf.daily==='Bullish'?'var(--green)':'var(--red)'}">${escHtml(mtf.daily)}</span> · Weekly <span style="color:${mtf.weekly==='Bullish'?'var(--green)':'var(--red)'}">${escHtml(mtf.weekly)}</span></div>
        <div style="font-size:11px;color:var(--muted);margin-top:2px">${escHtml(mtf.note||'')}</div>
      </div>
      <div style="margin-left:auto;font-size:11px;color:var(--muted)">W-EMA20: ₹${fmtNum(mtf.weekly_ema20)} · W-RSI: ${mtf.weekly_rsi||'—'} · W-MACD: ${escHtml(mtf.weekly_macd||'—')}</div>
    </div>`:''}

    <!-- Patterns -->
    ${pats.length?`<div class="pattern-tags">${pats.map(p=>`<span class="pat-tag ${p.type==='bullish'?'pat-bull':p.type==='bearish'?'pat-bear':'pat-neu'}" title="${escHtml(p.description||'')}">${escHtml(p.name||'')}</span>`).join('')}</div>`:''}

    <!-- Score Breakdown -->
    <div style="background:rgba(255,255,255,.02);border:1px solid var(--border);border-radius:10px;padding:14px;margin-bottom:12px">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:8px">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.7px;color:var(--muted)">⚖️ Signal Scorecard (${(sb.components||[]).length} indicators)</div>
        <div style="display:flex;align-items:center;gap:10px">
          <div style="font-size:1.2rem;font-weight:800;color:${scoreColor}">${scoreTotal>=0?'+':''}${scoreTotal}</div>
          <div style="background:${scoreColor};color:#000;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px">${scoreLabel}</div>
        </div>
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:6px">
        ${(sb.components||[]).map(c=>`
          <div style="display:flex;align-items:center;gap:7px;background:rgba(255,255,255,.03);border-radius:6px;padding:5px 9px">
            <span style="font-size:14px">${c.score>0?'🟢':c.score<0?'🔴':'⚪'}</span>
            <div style="flex:1;min-width:0">
              <div style="font-size:11px;color:var(--muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${escHtml(c.name)}</div>
              <div style="font-size:10px;color:${c.score>0?'var(--green)':c.score<0?'var(--red)':'var(--muted)'};font-weight:600">${c.score>0?'+':''}${c.score} · ${escHtml(String(c.detail))}</div>
            </div>
          </div>`).join('')}
      </div>
    </div>

    <!-- 3-column indicator grid -->
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:12px">

      <!-- Column 1: Trend Indicators -->
      <div class="a-section">
        <div class="a-section-title">📈 Trend</div>
        ${row('EMA Signal', iv(t.ema_signal,['Golden','Above'],['Death','Below']))}
        ${row('EMA 20', t.ema_20?`<span class="neu-val">₹${fmtNum(t.ema_20)}</span>`:'<span class="neu-val">N/A</span>')}
        ${row('EMA 50', t.ema_50?`<span class="neu-val">₹${fmtNum(t.ema_50)}</span>`:'<span class="neu-val">N/A</span>')}
        ${row('Supertrend', iv(t.supertrend,['Bullish'],['Bearish']))}
        ${row('VWAP', iv(t.vwap_signal,['Above'],['Below']))}
        ${row('ADX', t.adx!=null?`<span class="${t.adx>=25?'bull-val':'neu-val'}">${t.adx} — ${escHtml(t.adx_strength||'')}</span>`:'<span class="neu-val">N/A</span>')}
        ${row('+DI / -DI', t.plus_di!=null?`<span class="neu-val">+${t.plus_di} / -${t.minus_di}</span>`:'<span class="neu-val">N/A</span>')}
        ${row('Ichimoku', iv(ich.signal,['Bullish'],['Bearish']))}
        ${ich.tenkan?row('Tenkan/Kijun',`<span class="neu-val" style="font-size:10px">T:₹${fmtNum(ich.tenkan)} K:₹${fmtNum(ich.kijun)}</span>`):''}
        ${ich.senkou_a?row('Cloud',`<span class="${ich.cloud_bullish?'bull-val':'bear-val'}" style="font-size:10px">${ich.cloud_bullish?'Bullish':'Bearish'} (A:₹${fmtNum(ich.senkou_a)} B:₹${fmtNum(ich.senkou_b)})</span>`):''}
      </div>

      <!-- Column 2: Momentum -->
      <div class="a-section">
        <div class="a-section-title">⚡ Momentum</div>
        ${row('RSI (14)', t.rsi!=null?`<span class="${t.rsi>70?'bear-val':t.rsi<30?'bull-val':'neu-val'}">${parseFloat(t.rsi).toFixed(1)} — ${escHtml(t.rsi_signal||'')}</span>`:'<span class="neu-val">N/A</span>')}
        ${row('MACD', iv(t.macd,['Bullish'],['Bearish']))}
        ${row('MACD Detail', `<span class="neu-val" style="font-size:10px">${escHtml(t.macd_note||'')}</span>`)}
        ${row('Stochastic', t.stoch_k!=null?`<span class="${t.stoch_k>80?'bear-val':t.stoch_k<20?'bull-val':'neu-val'}">K:${t.stoch_k} D:${t.stoch_d} — ${escHtml(t.stoch_signal||'')}</span>`:'<span class="neu-val">N/A</span>')}
        ${row("Williams %R", t.williams_r!=null?`<span class="${t.williams_r<-80?'bull-val':t.williams_r>-20?'bear-val':'neu-val'}">${t.williams_r} — ${escHtml(t.williams_signal||'')}</span>`:'<span class="neu-val">N/A</span>')}
        ${row('CCI (20)', t.cci!=null?`<span class="${t.cci<-100?'bull-val':t.cci>100?'bear-val':'neu-val'}">${t.cci} — ${escHtml(t.cci_signal||'')}</span>`:'<span class="neu-val">N/A</span>')}
        ${row('MFI (14)', t.mfi!=null?`<span class="${t.mfi<20?'bull-val':t.mfi>80?'bear-val':'neu-val'}">${t.mfi} — ${escHtml(t.mfi_signal||'')}</span>`:'<span class="neu-val">N/A</span>')}
        ${row('OBV', iv(t.obv_trend,['accum'],['distrib']))}
        ${row('Bollinger', iv(t.bollinger,['lower band'],['upper band']))}
        ${row('BB Levels', `<span class="neu-val" style="font-size:10px">${escHtml(t.bollinger_note||'')}</span>`)}
      </div>

      <!-- Column 3: Volume + S/R -->
      <div class="a-section">
        <div class="a-section-title">📊 Volume & Levels</div>
        <div style="margin-bottom:8px">
          <div style="font-size:10px;color:var(--muted);margin-bottom:4px">Volume vs 20-day avg</div>
          <div style="height:5px;background:rgba(255,255,255,.07);border-radius:3px;margin-bottom:3px">
            <div style="width:${Math.min(volRatio/3*100,100)}%;height:100%;background:${volColor};border-radius:3px"></div>
          </div>
          <div style="font-size:11px;font-weight:600;color:${volColor}">${escHtml(vol.label||'N/A')}</div>
          <div style="font-size:10px;color:var(--muted)">Today: ${N(vol.today)} · Avg20: ${N(vol.avg20)}</div>
        </div>
        ${row('Support', t.support?`<span class="bull-val">₹${fmtNum(t.support)}</span>`:'<span class="neu-val">N/A</span>')}
        ${row('Resistance', t.resistance?`<span class="bear-val">₹${fmtNum(t.resistance)}</span>`:'<span class="neu-val">N/A</span>')}
        ${row('Volume Signal', iv(t.volume,['High'],['Low']))}
        ${row('Vol Detail', `<span class="neu-val" style="font-size:10px">${escHtml(t.volume_note||'')}</span>`)}
      </div>
    </div>

    <!-- 2-column: Fundamentals + Reasoning -->
    <div class="analysis-grid" style="margin-bottom:12px">
      <div class="a-section">
        <div class="a-section-title">💰 Fundamentals</div>
        ${row('Market Cap', `<span class="neu-val">${escHtml(f.market_cap||'N/A')} ${f.market_cap_cr?'('+escHtml(f.market_cap_cr)+')':''}</span>`)}
        ${row('P/E Ratio', f.pe_ratio?`<span class="${f.pe_ratio<20?'bull-val':f.pe_ratio>40?'bear-val':'neu-val'}">${parseFloat(f.pe_ratio).toFixed(1)}x</span>`:'<span class="neu-val">N/A</span>')}
        ${row('P/B Ratio', f.pb_ratio?`<span class="neu-val">${parseFloat(f.pb_ratio).toFixed(1)}x</span>`:'<span class="neu-val">N/A</span>')}
        ${row('Debt/Equity', f.debt_equity!=null?`<span class="${f.debt_equity<1?'bull-val':f.debt_equity>2?'bear-val':'neu-val'}">${parseFloat(f.debt_equity).toFixed(2)}</span>`:'<span class="neu-val">N/A</span>')}
        ${row('ROE', f.roe?`<span class="${f.roe>15?'bull-val':'neu-val'}">${parseFloat(f.roe).toFixed(1)}%</span>`:'<span class="neu-val">N/A</span>')}
        ${f.note?`<div style="font-size:11px;color:var(--muted);margin-top:8px;padding-top:8px;border-top:1px solid var(--border)">${escHtml(f.note)}</div>`:''}
      </div>
      <div class="a-section">
        <div class="a-section-title">🧠 Buy/Sell Reasoning</div>
        ${bs.bullish_factors&&bs.bullish_factors.length?`
          <div style="font-size:11px;color:var(--green);font-weight:600;margin-bottom:4px">✅ Bullish (${bs.bullish_factors.length})</div>
          <ul class="factor-list">${bs.bullish_factors.map(f=>`<li><span class="ico">🟢</span>${escHtml(f)}</li>`).join('')}</ul>`:''}
        ${bs.bearish_factors&&bs.bearish_factors.length?`
          <div style="font-size:11px;color:var(--red);font-weight:600;margin:8px 0 4px">❌ Bearish (${bs.bearish_factors.length})</div>
          <ul class="factor-list">${bs.bearish_factors.map(f=>`<li><span class="ico">🔴</span>${escHtml(f)}</li>`).join('')}</ul>`:''}
      </div>
    </div>

    <!-- Verdict -->
    ${bs.verdict?`<div class="verdict-box" style="border-color:rgba(${sig==='buy'?'16,185,129':sig==='sell'?'239,68,68':'245,158,11'},.3)">
      <strong>${sig==='buy'?'🟢':'🔴'} Verdict:</strong><br>${escHtml(bs.verdict)}
    </div>`:''}

    <!-- Trade Setup -->
    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.7px;color:var(--muted);margin-bottom:8px">🎯 Trade Setup (ATR-based)</div>
    <div class="trade-setup" style="margin-bottom:8px">
      <div class="ts-box">
        <div class="ts-label">Current Price</div>
        <div class="ts-val ts-entry">₹${fmtNum(ts.entry)}</div>
      </div>
      <div class="ts-box" style="position:relative">
        <div class="ts-label">Target 1</div>
        <div class="ts-val ts-t1">₹${fmtNum(ts.target_1)}</div>
        ${ts.entry>0?`<div style="font-size:10px;color:var(--green);margin-top:3px">${((ts.target_1-ts.entry)/ts.entry*100).toFixed(1)}% upside</div>`:''}
      </div>
      <div class="ts-box">
        <div class="ts-label">Target 2</div>
        <div class="ts-val ts-t2">₹${fmtNum(ts.target_2)}</div>
        ${ts.entry>0?`<div style="font-size:10px;color:var(--green2);margin-top:3px">${((ts.target_2-ts.entry)/ts.entry*100).toFixed(1)}% upside</div>`:''}
      </div>
      <div class="ts-box">
        <div class="ts-label">Stop Loss</div>
        <div class="ts-val ts-sl">₹${fmtNum(ts.stoploss)}</div>
        ${ts.entry>0?`<div style="font-size:10px;color:var(--red);margin-top:3px">${((ts.stoploss-ts.entry)/ts.entry*100).toFixed(1)}% risk</div>`:''}
      </div>
    </div>
    <div style="display:flex;gap:12px;font-size:12px;color:var(--muted);margin-bottom:8px;flex-wrap:wrap">
      ${ts.risk_reward?`<span>Risk/Reward: <strong style="color:#fff">${escHtml(ts.risk_reward)}</strong></span>`:''}
      ${ts.holding_period?`<span>Holding: <strong style="color:#fff">${escHtml(ts.holding_period)}</strong></span>`:''}
    </div>
    <div style="margin-bottom:14px">
      <button class="btn btn-outline" style="font-size:11px;padding:5px 14px" onclick="saveSignalManual('${escHtml(d.symbol||'')}','${escHtml(d.name||d.symbol||'')}','${escHtml(d.signal||'')}',${ts.entry||0},${ts.target_1||0},${ts.stoploss||0},${ts.target_2||0})">📌 Track in EOD Report</button>
    </div>

    <!-- Fibonacci Levels -->
    ${fibs['50']?`<div style="margin-bottom:12px">
      <div style="font-size:11px;text-transform:uppercase;letter-spacing:.7px;color:var(--muted);margin-bottom:8px">🌀 Fibonacci Retracement (${fibs.high?'Swing High ₹'+fmtNum(fibs.high):''}${fibs.low?' → Low ₹'+fmtNum(fibs.low):''})</div>
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(90px,1fr));gap:6px">
        ${['0','23.6','38.2','50','61.8','78.6','100'].map(k=>fibs[k]!=null?`
          <div style="background:${k==='0'?'rgba(16,185,129,.08)':k==='100'?'rgba(239,68,68,.08)':'rgba(0,114,255,.08)'};border:1px solid rgba(255,255,255,.08);border-radius:7px;padding:7px;text-align:center">
            <div style="font-size:10px;color:var(--muted);margin-bottom:2px">${k}%</div>
            <div style="font-size:12px;font-weight:700;color:${k==='0'?'var(--green)':k==='100'?'var(--red)':'var(--accent2)'}">₹${fmtNum(fibs[k])}</div>
          </div>`:'').join('')}
      </div>
    </div>`:''}

    <!-- Pivot Points -->
    ${pivots.PP?`<div style="margin-bottom:12px">
      <div style="font-size:11px;text-transform:uppercase;letter-spacing:.7px;color:var(--muted);margin-bottom:8px">📐 Pivot Points + CPR</div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(70px,1fr));gap:5px">
        ${['R3','R2','R1','TC','PP','BC','S1','S2','S3'].map(k=>pivots[k]!=null?`
          <div style="background:${k.startsWith('R')?'rgba(16,185,129,.08)':k.startsWith('S')?'rgba(239,68,68,.08)':'rgba(0,114,255,.08)'};border:1px solid ${k.startsWith('R')?'rgba(16,185,129,.25)':k.startsWith('S')?'rgba(239,68,68,.25)':'rgba(0,114,255,.25)'};border-radius:7px;padding:7px;text-align:center">
            <div style="font-size:10px;color:var(--muted);text-transform:uppercase;margin-bottom:2px">${k}</div>
            <div style="font-size:11px;font-weight:700;color:${k.startsWith('R')?'var(--green)':k.startsWith('S')?'var(--red)':'var(--accent2)'}">₹${fmtNum(pivots[k])}</div>
          </div>`:'').join('')}
      </div>
    </div>`:''}

    <!-- Charts: RSI + MACD + Price -->
    <div style="margin-bottom:12px">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;flex-wrap:wrap;gap:6px">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.7px;color:var(--muted)">📈 Intraday Charts</div>
        <div style="display:flex;gap:5px">
          <button onclick="switchChartInterval('5m',this)" style="font-size:11px;padding:3px 10px;border-radius:5px;border:1px solid var(--accent);background:rgba(0,114,255,.15);color:var(--accent2);cursor:pointer" class="int-btn">5M</button>
          <button onclick="switchChartInterval('15m',this)" style="font-size:11px;padding:3px 10px;border-radius:5px;border:1px solid var(--border);background:transparent;color:var(--muted);cursor:pointer" class="int-btn">15M</button>
          <button onclick="switchChartInterval('1h',this)"  style="font-size:11px;padding:3px 10px;border-radius:5px;border:1px solid var(--border);background:transparent;color:var(--muted);cursor:pointer" class="int-btn">1H</button>
        </div>
      </div>
      <!-- Price chart -->
      <div style="background:rgba(255,255,255,.02);border:1px solid var(--border);border-radius:8px;padding:10px;margin-bottom:6px;position:relative">
        <div style="font-size:10px;color:var(--muted);margin-bottom:4px">Price</div>
        <canvas id="priceChart" style="width:100%;height:160px"></canvas>
        <div id="chartLoading" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:12px">Loading…</div>
      </div>
      <!-- RSI chart -->
      <div style="background:rgba(255,255,255,.02);border:1px solid var(--border);border-radius:8px;padding:10px;margin-bottom:6px">
        <div style="font-size:10px;color:var(--muted);margin-bottom:4px">RSI (14) — Oversold &lt;30 · Overbought &gt;70</div>
        <canvas id="rsiChart" style="width:100%;height:80px"></canvas>
      </div>
      <!-- MACD chart -->
      <div style="background:rgba(255,255,255,.02);border:1px solid var(--border);border-radius:8px;padding:10px;margin-bottom:6px">
        <div style="font-size:10px;color:var(--muted);margin-bottom:4px">MACD (12,26,9) Histogram</div>
        <canvas id="macdChart" style="width:100%;height:80px"></canvas>
      </div>
      <!-- Volume -->
      <div style="background:rgba(255,255,255,.02);border:1px solid var(--border);border-radius:8px;padding:10px">
        <div style="font-size:10px;color:var(--muted);margin-bottom:4px">Volume</div>
        <canvas id="volChartA" style="width:100%;height:60px"></canvas>
      </div>
    </div>

    <!-- Risk -->
    ${d.risk_warning?`<div class="risk-box">${escHtml(d.risk_warning)}</div>`:''}
    <div style="font-size:10px;color:var(--muted);margin-top:10px;text-align:center">
      Data: Live NSE (EODHD) · ${(sb.components||[]).length} indicators computed · Educational only · ${new Date().toLocaleString('en-IN',{timeZone:'Asia/Kolkata'})}
    </div>
  </div>`;

  // Load charts after DOM renders
  setTimeout(()=>loadAnalyzeCharts(d.symbol,'5m'),100);
}

// Chart instances for analyze page
let aCh={price:null,rsi:null,macd:null,vol:null};
let aCurrentSym='';

function switchChartInterval(iv,btn){
  document.querySelectorAll('.int-btn').forEach(b=>{
    b.style.borderColor='var(--border)';b.style.background='transparent';b.style.color='var(--muted)';
  });
  btn.style.borderColor='var(--accent)';btn.style.background='rgba(0,114,255,.15)';btn.style.color='var(--accent2)';
  loadAnalyzeCharts(aCurrentSym,iv);
}

async function loadAnalyzeCharts(sym,interval){
  if(!sym)return;
  aCurrentSym=sym;
  const loading=document.getElementById('chartLoading');
  if(loading)loading.style.display='flex';
  try{
    const r=await fetch(apiUrl('api/intraday')+'?symbol='+encodeURIComponent(sym)+'&interval='+interval);
    const d=await r.json();
    if(d.error||!d.candles||!d.candles.length){
      if(loading)loading.innerHTML='<span style="font-size:11px">No intraday data (market may be closed)</span>';
      return;
    }
    if(loading)loading.style.display='none';
    const candles=d.candles;
    const labels=candles.map(c=>new Date(c.t*1000).toLocaleTimeString('en-IN',{timeZone:'Asia/Kolkata',hour:'2-digit',minute:'2-digit'}));
    const closes=candles.map(c=>c.c);
    const vols=candles.map(c=>c.v);
    const isUp=closes[closes.length-1]>=closes[0];
    const lc=isUp?'#10b981':'#ef4444';
    const gc=isUp?'rgba(16,185,129,0.07)':'rgba(239,68,68,0.07)';
    const cfg={responsive:true,maintainAspectRatio:false,animation:false,
      plugins:{legend:{display:false},tooltip:{backgroundColor:'rgba(19,23,40,.95)',titleColor:'#9ca3af',bodyColor:'#fff',borderColor:'rgba(255,255,255,.1)',borderWidth:1}},
      scales:{x:{ticks:{color:'rgba(255,255,255,.4)',maxTicksLimit:6,font:{size:9}},grid:{color:'rgba(255,255,255,.04)'}},
              y:{ticks:{color:'rgba(255,255,255,.4)',font:{size:9}},grid:{color:'rgba(255,255,255,.04)'},position:'right'}}};

    // Compute RSI from closes
    function compRsi(cls,p=14){
      if(cls.length<=p)return cls.map(()=>50);
      let g=0,l=0;
      for(let i=1;i<=p;i++){const d=cls[i]-cls[i-1];d>0?g+=d:l+=Math.abs(d);}
      let ag=g/p,al=l/p;
      const r=[...Array(p).fill(null)];
      for(let i=p;i<cls.length;i++){const d=cls[i]-cls[i-1];ag=(ag*(p-1)+Math.max(0,d))/p;al=(al*(p-1)+Math.max(0,-d))/p;r.push(al===0?100:parseFloat((100-100/(1+ag/al)).toFixed(1)));}
      return r;
    }
    // Compute MACD histogram from closes
    function compEma(cls,p){let k=2/(p+1),e=cls.slice(0,p).reduce((a,b)=>a+b)/p;const r=[...Array(p-1).fill(null),e];for(let i=p;i<cls.length;i++){e=cls[i]*k+e*(1-k);r.push(parseFloat(e.toFixed(2)));}return r;}
    function compMacd(cls){const e12=compEma(cls,12),e26=compEma(cls,26);const ml=cls.map((_,i)=>e12[i]!=null&&e26[i]!=null?parseFloat((e12[i]-e26[i]).toFixed(4)):null);const valid=ml.filter(v=>v!=null);const sig=compEma(valid,9);const sigFull=ml.map((v,i)=>{if(v==null)return null;const vi=ml.slice(0,i+1).filter(x=>x!=null).length-1;return sig[vi]??null;});return ml.map((v,i)=>v!=null&&sigFull[i]!=null?parseFloat((v-sigFull[i]).toFixed(4)):null);}

    const rsiData=compRsi(closes);
    const macdHist=compMacd(closes);

    // Destroy old
    Object.values(aCh).forEach(c=>{if(c)c.destroy();});

    const priceCtx=document.getElementById('priceChart');
    const rsiCtx=document.getElementById('rsiChart');
    const macdCtx=document.getElementById('macdChart');
    const volCtx=document.getElementById('volChartA');
    if(!priceCtx||!rsiCtx||!macdCtx||!volCtx)return;

    aCh.price=new Chart(priceCtx,{type:'line',data:{labels,datasets:[{data:closes,borderColor:lc,backgroundColor:gc,borderWidth:1.5,pointRadius:0,fill:true,tension:0.2}]},options:{...cfg,scales:{...cfg.scales,y:{...cfg.scales.y,callbacks:{label:ctx=>'₹'+fmtNum(ctx.parsed.y)}}}}});
    aCh.rsi=new Chart(rsiCtx,{type:'line',data:{labels,datasets:[{data:rsiData,borderColor:'#a78bfa',backgroundColor:'rgba(167,139,250,.05)',borderWidth:1.5,pointRadius:0,fill:true}]},
      options:{...cfg,plugins:{...cfg.plugins,annotation:{annotations:{ob:{type:'line',y:70,borderColor:'rgba(239,68,68,.4)',borderWidth:1,borderDash:[4,4]},os:{type:'line',y:30,borderColor:'rgba(16,185,129,.4)',borderWidth:1,borderDash:[4,4]}}}},
      scales:{x:{...cfg.scales.x},y:{...cfg.scales.y,min:0,max:100}}}});
    aCh.macd=new Chart(macdCtx,{type:'bar',data:{labels,datasets:[{data:macdHist,backgroundColor:macdHist.map(v=>v==null?'transparent':v>=0?'rgba(16,185,129,.6)':'rgba(239,68,68,.6)'),borderWidth:0}]},options:{...cfg}});
    aCh.vol=new Chart(volCtx,{type:'bar',data:{labels,datasets:[{data:vols,backgroundColor:candles.map(c=>c.c>=c.o?'rgba(16,185,129,.5)':'rgba(239,68,68,.5)'),borderWidth:0}]},options:{...cfg}});
  }catch(e){
    const loading=document.getElementById('chartLoading');
    if(loading){loading.style.display='flex';loading.innerHTML='<span style="font-size:11px;color:var(--red)">Chart error: '+escHtml(e.message)+'</span>';}
  }
}

function row(label,valHtml){
  return `<div class="ind-row"><span class="ind-label">${escHtml(label)}</span><span class="ind-val">${valHtml}</span></div>`;
}

function addHistory(sym){
  analyzeHistory=analyzeHistory.filter(s=>s!==sym);
  analyzeHistory.unshift(sym);
  analyzeHistory=analyzeHistory.slice(0,8);
  const el=document.getElementById('histItems');
  const wrap=document.getElementById('histList');
  el.innerHTML=analyzeHistory.map(s=>`<span class="qsym" onclick="quickSym('${escHtml(s)}')" style="margin-bottom:4px">${escHtml(s)}</span> `).join('');
  wrap.style.display='block';
}

// ══ NEWS ═════════════════════════════════════════════════════
let newsLoaded=false;
async function loadNews(force=false){
  const el=document.getElementById('newsContainer');
  if(!force&&newsLoaded) return;
  el.innerHTML='<div class="loading-card"><div class="spin"></div><div>Loading market news from ET & Moneycontrol RSS…</div></div>';
  try{
    const r=await fetch(apiUrl('api/news')+(force?'?force=1':''));
    const d=await r.json();
    const items=d.news||[];
    if(!items.length){el.innerHTML='<div class="err-box">No news available right now. Check back later.</div>';return;}
    const html='<div class="news-grid">'+items.map(n=>{
      const ic=n.impact==='Bullish'?'imp-bull':n.impact==='Bearish'?'imp-bear':'imp-neu';
      const st=(n.stocks_affected||[]).map(s=>`<span class="ns-tag">${escHtml(s)}</span>`).join('');
      return `<div class="news-card">
        <div class="news-impact ${ic}">${escHtml(n.impact||'Neutral')} · ${escHtml(n.source||'Market')}</div>
        <div class="news-head">${escHtml(n.headline||'')}</div>
        <div class="news-sum">${escHtml(n.summary||'')}</div>
        ${st?`<div class="news-stocks">${st}</div>`:''}
      </div>`;
    }).join('')+'</div>';
    el.innerHTML=html;
    newsLoaded=true;
  }catch(e){el.innerHTML='<div class="err-box">'+escHtml(e.message)+'</div>';}
}

// ── Intraday Chart ────────────────────────────────────────────
let chartInstance = null;
async function loadChart(sym, interval='5m'){
  // Highlight active button
  ['5m','15m','1h'].forEach(i=>{
    const b=document.getElementById('btn'+i);
    if(b){ b.style.borderColor=i===interval?'var(--accent)':'var(--border)';
           b.style.background=i===interval?'rgba(0,114,255,.15)':'transparent';
           b.style.color=i===interval?'var(--accent2)':'var(--muted)'; }
  });
  const cl=document.getElementById('chartLoading');
  const cv=document.getElementById('priceChart');
  if(!cv)return;
  if(cl) cl.style.display='flex';
  cv.style.display='none';
  try{
    const yahooSym=sym.endsWith('.NS')?sym:sym+'.NS';
    // Map interval to API params
    const intervalMap={'5m':'5m','15m':'15m','1h':'60m'};
    const yInterval=intervalMap[interval]||'5m';
    const range=interval==='1h'?'5d':'1d';

    // Browser fetches intraday from Yahoo Finance directly (with proxy fallbacks)
    let candles=[];
    const chartUrl=`https://query1.finance.yahoo.com/v8/finance/chart/${encodeURIComponent(yahooSym)}?interval=${yInterval}&range=${range}`;

    // Try direct
    for(const host of ['query1','query2']){
      try{
        const r=await fetch(`https://${host}.finance.yahoo.com/v8/finance/chart/${encodeURIComponent(yahooSym)}?interval=${yInterval}&range=${range}`);
        if(r.ok){
          const j=await r.json();
          const chart=j?.chart?.result?.[0];
          if(chart){
            const ts=chart.timestamp||[];
            const ohlcv=chart.indicators?.quote?.[0]||{};
            candles=ts.map((t,i)=>({t,o:+(ohlcv.open?.[i]||0).toFixed(2),h:+(ohlcv.high?.[i]||0).toFixed(2),l:+(ohlcv.low?.[i]||0).toFixed(2),c:+(ohlcv.close?.[i]||0).toFixed(2),v:ohlcv.volume?.[i]||0})).filter(c=>c.c>0);
            if(candles.length)break;
          }
        }
      }catch(e){}
    }

    // Groww intraday fallback
    if(!candles.length){
      try{
        const base=sym.replace('.NS','').replace('.BO','');
        const sr=await fetch(`https://groww.in/v1/api/stocks_data/v1/company/search?q=${encodeURIComponent(base)}&page=0&size=1`);
        if(sr.ok){
          const sj=await sr.json();
          const slug=sj?.stocks?.[0]?.searchId||sj?.stocks?.[0]?.slug;
          if(slug){
            const mins={'5m':5,'15m':15,'1h':60}[interval]||5;
            const now=Date.now();
            const from=interval==='1h'?now-5*86400000:now-86400000;
            const cr=await fetch(`https://groww.in/v1/api/charting_service/v2/chart/exchange/NSE/segment/CASH/${encodeURIComponent(slug)}?startTimeInMillis=${from}&endTimeInMillis=${now}&intervalInMinutes=${mins}`);
            if(cr.ok){
              const cj=await cr.json();
              const gc=cj?.candles||cj?.data?.candles||[];
              candles=gc.map(c=>({t:Math.floor(c[0]/1000),o:+c[1].toFixed(2),h:+c[2].toFixed(2),l:+c[3].toFixed(2),c:+c[4].toFixed(2),v:c[5]||0})).filter(c=>c.c>0);
            }
          }
        }
      }catch(e){}
    }

    if(!candles.length){
      if(cl){cl.innerHTML='<span style="color:var(--muted)">No intraday data available</span>';cl.style.display='flex';}
      return;
    }
    cv.style.display='block';
    if(cl) cl.style.display='none';
    if(chartInstance){ chartInstance.destroy(); chartInstance=null; }
    const labels=candles.map(c=>{const dt=new Date(c.t*1000);return dt.toLocaleTimeString('en-IN',{hour:'2-digit',minute:'2-digit',timeZone:'Asia/Kolkata'});});
    const closes=candles.map(c=>c.c);
    const first=closes[0]||0;
    const ctx=cv.getContext('2d');
    chartInstance=new Chart(ctx,{
      type:'line',
      data:{
        labels,
        datasets:[{
          label:`${sym} (${interval})`,
          data:closes,
          borderColor:'rgba(0,198,255,0.9)',
          backgroundColor:'rgba(0,198,255,0.05)',
          borderWidth:1.5,
          pointRadius:0,
          fill:true,
          tension:0.3
        }]
      },
      options:{
        responsive:true,
        maintainAspectRatio:false,
        plugins:{
          legend:{display:false},
          tooltip:{callbacks:{label:ctx=>`₹${ctx.parsed.y.toFixed(2)}`}}
        },
        scales:{
          x:{ticks:{color:'#6b7280',maxRotation:0,font:{size:9},maxTicksLimit:8},grid:{color:'rgba(255,255,255,0.04)'}},
          y:{ticks:{color:'#6b7280',font:{size:9},callback:v=>'₹'+v.toFixed(0)},grid:{color:'rgba(255,255,255,0.04)'}}
        }
      }
    });
  }catch(e){
    if(cl){cl.innerHTML='<span style="color:var(--red)">Chart error: '+escHtml(e.message)+'</span>';cl.style.display='flex';}
  }
}

// Reset custom watchlist to default
async function resetWatchlist(){
  if(!confirm('Reset to default 5 stocks?')) return;
  const r=await fetch(apiUrl('api/watchlist/reset'),{method:'POST'});
  renderWatchlistManager([]);
  loadWatchlist(true);
}

// ── Helpers ───────────────────────────────────────────────────
function escHtml(s){
  return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function fmtNum(n){
  const f=parseFloat(n);
  return isNaN(f)?'—':f.toLocaleString('en-IN',{minimumFractionDigits:2,maximumFractionDigits:2});
}
function N(n){const f=parseInt(n);return isNaN(f)?"—":f.toLocaleString("en-IN");}

// ── Leaders / Tick ───────────────────────────────────────────
let tickTimer=null, leaderLoaded=false, tickCount=0, prakashRollupLoaded=false;
const TICK_INTERVAL=300000; // 5 minutes

async function forceTick(){
  document.getElementById('tickStatus').innerHTML='<span class="pulse">🔴 Ticking…</span>';
  try{
    const r=await fetch(apiUrl('api/tick'));
    const d=await r.json();
    if(d.data){
      tickCount++;
      const t=d.tick||'--:--';
      document.getElementById('tickStatus').innerHTML=
        `✅ Tick #${tickCount} at <strong>${t}</strong> · ${Object.keys(d.data).length} stocks`;
      document.getElementById('liveTick').textContent='Last tick: '+t;
      renderLiveStrip(d.data);
      loadLeaders();
    }
  }catch(e){
    document.getElementById('tickStatus').textContent='❌ Tick failed: '+e.message;
  }
}

function renderLiveStrip(data){
  const el=document.getElementById('liveStrip');
  let html='';
  Object.entries(data).forEach(([sym,s])=>{
    const chg=parseFloat(s.chg)||0;
    const chgCls=chg>=0?'chg-up':'chg-dn';
    const sig=s.signal||'Hold';
    const sigColor=sig==='Buy'?'var(--green)':sig==='Sell'?'var(--red)':'var(--orange)';
    const score=parseFloat(s.score)||0;
    const barW=Math.min(Math.abs(score),100);
    const barColor=score>0?'var(--green)':'var(--red)';
    html+=`<div class="live-row">
      <span class="live-ticker">${escHtml(sym)}</span>
      <span class="live-price">₹${fmtNum(s.price)}</span>
      <span class="live-chg ${chgCls}">${chg>=0?'+':''}${chg.toFixed(2)}%</span>
      <span style="font-size:11px;font-weight:700;color:${sigColor};width:40px">${escHtml(sig)}</span>
      <div class="live-score-bar"><div style="width:${barW}%;height:100%;background:${barColor};border-radius:2px"></div></div>
      <span style="font-size:10px;color:var(--muted);width:30px;text-align:right">${score>0?'+':''}${score}</span>
    </div>`;
  });
  el.innerHTML=html||'<div style="padding:16px;color:var(--muted)">No data</div>';
}

async function loadLeaders(){
  try{
    const r=await fetch(apiUrl('api/leaders'));
    const d=await r.json();
    if(d.error){
      document.getElementById('leadersContent').innerHTML=
        `<div class="err-box">${escHtml(d.error)}</div>`;
      return;
    }
    renderLeaders(d);
    leaderLoaded=true;
  }catch(e){
    document.getElementById('leadersContent').innerHTML=
      `<div class="err-box">Error: ${escHtml(e.message)}</div>`;
  }
}

function renderLeaders(d){
  const el=document.getElementById('leadersContent');
  const totalTicks=d.total_ticks||0;

  function rankEmoji(i){ return ['🥇','🥈','🥉','4️⃣','5️⃣'][i]||`${i+1}.`; }

  function leaderRow(s,i,type){
    const isBuy=type==='buy';
    const count=isBuy?s.buy_count:s.sell_count;
    const total=s.ticks||1;
    const dom=s.dominance||0;
    const barColor=isBuy?'var(--green)':'var(--red)';
    const chg=parseFloat(s.price_chg)||0;
    const chgStr=(chg>=0?'+':'')+chg.toFixed(2)+'%';
    const chgColor=chg>=0?'var(--green)':'var(--red)';
    const streakColor=s.streak_sig==='Buy'?'streak-buy':'streak-sell';
    const streakIcon=s.streak_sig==='Buy'?'🚀':'📉';

    // Mini signal history dots — last 20 ticks from today's log shown as colored dots
    return `<div class="leader-row" onclick="analyzeFromWatch('${escHtml(s.symbol)}');showTab('analyze',document.querySelectorAll('.nb')[1])">
      <div class="leader-rank" style="color:${barColor}">${rankEmoji(i)}</div>
      <div style="flex:1;min-width:0">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:3px">
          <span class="leader-sym">${escHtml(s.symbol)}</span>
          <span style="font-size:11px;color:${chgColor};font-weight:600">${chgStr}</span>
          <span style="font-size:11px;color:var(--accent2)">₹${fmtNum(s.price)}</span>
        </div>
        <div class="leader-name">${escHtml(s.name||'')}</div>
        <div style="margin:5px 0">
          <div style="display:flex;justify-content:space-between;font-size:10px;color:var(--muted);margin-bottom:3px">
            <span>${isBuy?'Buy':'Sell'} signals: <strong style="color:${barColor}">${count}/${total}</strong></span>
            <span>${dom}% ${isBuy?'bullish':'bearish'}</span>
          </div>
          <div style="height:6px;background:rgba(255,255,255,.07);border-radius:3px">
            <div style="width:${dom}%;height:100%;background:${barColor};border-radius:3px;transition:width .5s"></div>
          </div>
        </div>
        <div class="leader-meta">
          <span class="streak-badge ${streakColor}">${streakIcon} ${s.streak}× streak</span>
          <span>Score: ${s.avg_score>0?'+':''}${s.avg_score}</span>
          <span>${total} ticks tracked</span>
        </div>
      </div>
    </div>`;
  }

  function leaderCard(list, title, icon, type, color){
    const borderColor=type==='buy'?'rgba(16,185,129,.3)':'rgba(239,68,68,.3)';
    let html=`<div class="leader-card" style="border-color:${borderColor}">
      <div class="leader-card-head" style="background:${type==='buy'?'rgba(16,185,129,.06)':'rgba(239,68,68,.06)'}">
        <span style="font-size:18px">${icon}</span>
        <div>
          <div class="leader-card-title" style="color:${color}">${title}</div>
          <div style="font-size:10px;color:var(--muted)">Ranked by signal count + streak</div>
        </div>
      </div>`;
    if(!list.length){
      html+=`<div style="padding:24px;text-align:center;color:var(--muted);font-size:12px">
        No ${type} signals accumulated yet.<br>More ticks needed.
      </div>`;
    } else {
      list.forEach((s,i)=>{ html+=leaderRow(s,i,type); });
    }
    html+=`</div>`;
    return html;
  }

  let html=`
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;flex-wrap:wrap">
      <div style="font-size:12px;color:var(--muted)">📊 <strong style="color:#fff">${totalTicks}</strong> total signals tracked today · ${d.date||''} · Updated: ${d.generated||''}</div>
      <div style="font-size:11px;color:var(--green);background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);padding:3px 10px;border-radius:10px">Auto-ticks every 60s</div>
    </div>

    <div style="margin-bottom:20px">
      <div style="font-size:11px;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);margin-bottom:10px;padding-bottom:6px;border-bottom:1px solid var(--border)">
        ⏰ THIS HOUR — Last 60 Minutes
      </div>
      <div class="leader-grid">
        ${leaderCard(d.hour_buy,  '📈 Top Buy By AI For This Hour',  '🟢', 'buy',  'var(--green)')}
        ${leaderCard(d.hour_sell, '📉 Top Sell By AI For This Hour', '🔴', 'sell', 'var(--red)')}
      </div>
    </div>

    <div>
      <div style="font-size:11px;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);margin-bottom:10px;padding-bottom:6px;border-bottom:1px solid var(--border)">
        📅 TODAY — Since Market Open
      </div>
      <div class="leader-grid">
        ${leaderCard(d.today_buy,  '🏆 Top Buy For Today By AI',  '🥇', 'buy',  'var(--green)')}
        ${leaderCard(d.today_sell, '🏆 Top Sell For Today By AI', '🏴', 'sell', 'var(--red)')}
      </div>
    </div>

    <div style="font-size:11px;color:var(--muted);margin-top:14px;padding:10px;background:rgba(255,255,255,.02);border-radius:8px;line-height:1.7">
      💡 <strong>How it works:</strong> Every minute, each stock gets a Buy/Sell/Hold signal based on RSI, EMA20/50, MACD, Supertrend + volume. 
      The stock with the most Buy signals in the last hour = <strong style="color:var(--green)">Top Buy By AI For This Hour</strong>. 
      If a stock gets Buy 4 out of 5 minutes, it jumps to #1. 
      Streak = consecutive same signals right now. Click any row to deep-analyze that stock.
    </div>
  `;

  el.innerHTML=html;
}

// Start auto-tick when leaders tab is active — handled inside showTab above

// ── Intraday Chart ────────────────────────────────────────────
let priceChartInst = null, volChartInst = null;

async function loadChart(){
  let sym = (document.getElementById('chartSymInput').value||'').trim().toUpperCase();
  if(!sym){ document.getElementById('chartSymInput').focus(); return; }
  const interval = document.getElementById('chartInterval').value;
  document.getElementById('chartStatus').innerHTML = '<div style="padding:40px;text-align:center;color:var(--muted)"><div class="spin" style="width:24px;height:24px;border-width:3px;display:inline-block"></div><br><br>Fetching intraday data for <strong>'+escHtml(sym)+'</strong>…</div>';
  document.getElementById('chartStatus').style.display='block';
  document.getElementById('chartWrap').style.display='none';
  try{
    const r = await fetch(apiUrl('api/intraday')+'?symbol='+encodeURIComponent(sym)+'&interval='+interval);
    const d = await r.json();
    if(d.error){ document.getElementById('chartStatus').innerHTML='<div class="err-box" style="margin:16px">'+escHtml(d.error)+'</div>'; return; }
    renderChart(d, sym, interval);
    // Also load pivots
    const pr = await fetch(apiUrl('api/pivots')+'?symbol='+encodeURIComponent(sym));
    const pd = await pr.json();
    if(pd.pivots) renderPivots(pd.pivots);
  }catch(e){
    document.getElementById('chartStatus').innerHTML='<div class="err-box" style="margin:16px">Error: '+escHtml(e.message)+'</div>';
  }
}

function renderChart(d, sym, interval){
  const candles = d.candles||[];
  if(!candles.length){ document.getElementById('chartStatus').innerHTML='<div style="padding:40px;text-align:center;color:var(--muted)">No intraday data available. Market may be closed.</div>'; return; }

  document.getElementById('chartStatus').style.display='none';
  document.getElementById('chartWrap').style.display='block';

  const labels = candles.map(c=>{
    const dt = new Date(c.t*1000);
    return dt.toLocaleTimeString('en-IN',{timeZone:'Asia/Kolkata',hour:'2-digit',minute:'2-digit'});
  });
  const closes  = candles.map(c=>c.c);
  const volumes = candles.map(c=>c.v);
  const opens   = candles.map(c=>c.o);
  const first = closes[0], last = closes[closes.length-1];
  const isUp = last >= first;
  const lineColor = isUp ? '#10b981' : '#ef4444';
  const fillColor = isUp ? 'rgba(16,185,129,0.08)' : 'rgba(239,68,68,0.08)';

  // Stats
  const high = Math.max(...candles.map(c=>c.h));
  const low  = Math.min(...candles.map(c=>c.l));
  const chg  = first>0 ? ((last-first)/first*100).toFixed(2) : '0.00';
  const vol  = volumes.reduce((a,b)=>a+b,0);

  document.getElementById('chartTitle').textContent = sym + ' — ' + interval + ' Intraday';
  document.getElementById('chartMeta').textContent  = `${candles.length} candles · ${new Date().toLocaleDateString('en-IN')}`;

  document.getElementById('intradayStats').innerHTML = [
    {l:'Last Price', v:'₹'+fmtNum(last), c:isUp?'var(--green)':'var(--red)'},
    {l:'Change',     v:(isUp?'▲ +':' ▼ ')+chg+'%', c:isUp?'var(--green)':'var(--red)'},
    {l:'Day High',   v:'₹'+fmtNum(high), c:'var(--green)'},
    {l:'Day Low',    v:'₹'+fmtNum(low),  c:'var(--red)'},
    {l:'Open',       v:'₹'+fmtNum(opens[0]), c:'var(--accent2)'},
    {l:'Volume',     v:N(vol), c:'var(--text)'},
  ].map(s=>`<div style="background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:8px;padding:10px 12px">
    <div style="font-size:10px;text-transform:uppercase;letter-spacing:.6px;color:var(--muted);margin-bottom:4px">${s.l}</div>
    <div style="font-size:1rem;font-weight:700;color:${s.c}">${s.v}</div>
  </div>`).join('');

  // Destroy old charts
  if(priceChartInst){ priceChartInst.destroy(); priceChartInst=null; }
  if(volChartInst)  { volChartInst.destroy();   volChartInst=null; }

  const pCtx = document.getElementById('priceChart');
  const vCtx = document.getElementById('volChart');
  // Set canvas pixel size
  pCtx.width = pCtx.offsetWidth; pCtx.height = pCtx.offsetHeight;
  vCtx.width = vCtx.offsetWidth; vCtx.height = vCtx.offsetHeight;

  const gridColor = 'rgba(255,255,255,0.05)';
  const tickColor = 'rgba(255,255,255,0.4)';

  priceChartInst = new Chart(pCtx, {
    type:'line',
    data:{
      labels,
      datasets:[{
        label:'Price',data:closes,
        borderColor:lineColor, backgroundColor:fillColor,
        borderWidth:2, pointRadius:0, pointHoverRadius:3,
        fill:true, tension:0.1,
      }]
    },
    options:{
      responsive:true, maintainAspectRatio:false, animation:false,
      interaction:{intersect:false,mode:'index'},
      plugins:{
        legend:{display:false},
        tooltip:{
          callbacks:{label:ctx=>'₹'+fmtNum(ctx.parsed.y)},
          backgroundColor:'rgba(19,23,40,0.95)',
          titleColor:'#9ca3af', bodyColor:'#fff',
          borderColor:'rgba(255,255,255,0.1)', borderWidth:1,
        }
      },
      scales:{
        x:{ticks:{color:tickColor,maxTicksLimit:8,font:{size:10}},grid:{color:gridColor}},
        y:{ticks:{color:tickColor,callback:v=>'₹'+v.toLocaleString('en-IN'),font:{size:10}},grid:{color:gridColor},position:'right'},
      }
    }
  });

  const volColors = candles.map((c,i)=>c.c>=c.o?'rgba(16,185,129,0.6)':'rgba(239,68,68,0.6)');
  volChartInst = new Chart(vCtx, {
    type:'bar',
    data:{labels, datasets:[{label:'Volume',data:volumes,backgroundColor:volColors,borderWidth:0}]},
    options:{
      responsive:true, maintainAspectRatio:false, animation:false,
      plugins:{legend:{display:false},tooltip:{callbacks:{label:ctx=>'Vol: '+N(ctx.parsed.y)}}},
      scales:{
        x:{ticks:{display:false},grid:{color:gridColor}},
        y:{ticks:{color:tickColor,callback:v=>v>1e6?(v/1e6).toFixed(1)+'M':v>1e3?(v/1e3).toFixed(0)+'K':v,font:{size:9}},grid:{color:gridColor},position:'right'},
      }
    }
  });
}

function renderPivots(pivots){
  const el = document.getElementById('pivotDisplay');
  if(!el||!pivots) return;
  const levels = [
    {l:'R3', v:pivots.R3, c:'#dc2626'},
    {l:'R2', v:pivots.R2, c:'#ef4444'},
    {l:'R1', v:pivots.R1, c:'#f87171'},
    {l:'TC', v:pivots.TC, c:'#f59e0b', tip:'CPR Top'},
    {l:'PP', v:pivots.PP, c:'#fff', tip:'Pivot Point'},
    {l:'BC', v:pivots.BC, c:'#f59e0b', tip:'CPR Bottom'},
    {l:'S1', v:pivots.S1, c:'#6ee7b7'},
    {l:'S2', v:pivots.S2, c:'#10b981'},
    {l:'S3', v:pivots.S3, c:'#059669'},
  ];
  el.innerHTML = `<div style="background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:8px;padding:12px">
    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.7px;color:var(--muted);margin-bottom:10px">📐 Pivot Points + CPR (Standard)</div>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
      ${levels.map(lv=>`<div style="text-align:center;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);border-radius:6px;padding:6px 10px;min-width:70px">
        <div style="font-size:10px;color:var(--muted);margin-bottom:2px">${lv.l}${lv.tip?'*':''}</div>
        <div style="font-size:12px;font-weight:700;color:${lv.c}">₹${lv.v?parseFloat(lv.v).toFixed(2):'—'}</div>
      </div>`).join('')}
    </div>
    <div style="font-size:10px;color:var(--muted);margin-top:8px">*TC=CPR Top, BC=CPR Bottom · Computed from previous day OHLC</div>
  </div>`;
}

// Reset watchlist to default
async function resetWatchlist(){
  if(!confirm('Reset to default 5 stocks? Custom stocks will be removed.')) return;
  await fetch(apiUrl('api/watchlist/list')).then(r=>r.json()).then(async d=>{
    for(const s of (d.watchlist||[])){
      const fd=new FormData(); fd.append('symbol',s);
      await fetch(apiUrl('api/watchlist/remove'),{method:'POST',body:fd});
    }
  });
  renderWatchlistManager([]);
  loadWatchlist(true);
}

// ══ EOD REPORT ═══════════════════════════════════════════════
let eodLoaded=false;

// Auto-save a Buy/Sell signal to EOD tracker (called from stockRow)
const _eodSaved=new Set();
async function saveSignalEod(s){
  const key=s.symbol+'_'+(s.signal||'');
  if(_eodSaved.has(key))return; // don't double-save per session
  _eodSaved.add(key);
  try{
    const fd=new FormData();
    fd.append('symbol', s.symbol||'');
    fd.append('name',   s.name||s.symbol||'');
    fd.append('signal', s.signal||'');
    fd.append('entry_price',  s.price||0);
    fd.append('target_price', s.target||0);
    fd.append('stoploss',     s.stoploss||0);
    fd.append('target2',      0);
    await fetch(apiUrl('api/signal/save'),{method:'POST',body:fd});
  }catch(e){}
}

// Manually save from Analyze tab's "Track in EOD Report" button
async function saveSignalManual(sym,name,signal,entry,target1,sl,target2){
  try{
    const fd=new FormData();
    fd.append('symbol',sym); fd.append('name',name);
    fd.append('signal',signal); fd.append('entry_price',entry);
    fd.append('target_price',target1); fd.append('stoploss',sl);
    fd.append('target2',target2);
    await fetch(apiUrl('api/signal/save'),{method:'POST',body:fd});
    // Flash confirmation
    const btn=event&&event.target;
    if(btn){const orig=btn.textContent;btn.textContent='✅ Saved!';btn.style.color='var(--green)';setTimeout(()=>{btn.textContent=orig;btn.style.color='';},2000);}
  }catch(e){alert('Could not save signal: '+e.message);}
}

// Combined Prakash + AI recommendation log for a given date (defaults to
// today) — every stock either engine tracked that day, with entry price,
// target price, and achieved/failed status. Backs both the on-page table
// and the CSV download.
let _lastCombinedEod=null;
async function loadCombinedEodReport(date){
  const tableEl=document.getElementById('combinedEodTable');
  const summaryEl=document.getElementById('combinedEodSummary');
  if(!tableEl) return;
  tableEl.innerHTML='<div style="color:var(--muted)">Loading…</div>';
  try{
    const url=apiUrl('api/eod/combined')+(date?'?date='+encodeURIComponent(date):'');
    const r=await fetch(url);
    const d=await r.json();
    _lastCombinedEod=d;
    if(d.error){ tableEl.innerHTML=`<div class="err-box">${escHtml(d.error)}</div>`; return; }
    const rows=d.rows||[];
    if(summaryEl){
      const s=d.summary||{};
      const rate=s.success_rate!==null&&s.success_rate!==undefined?s.success_rate+'%':'—';
      const rc=(s.success_rate||0)>=50?'var(--green)':'var(--red)';
      summaryEl.innerHTML=rows.length?`<strong style="color:#fff">${s.achieved}/${s.total}</strong> hit · <strong style="color:${rc}">${rate}</strong> · Momentum: ${s.prakash_total} · AI: ${s.ai_total}`:'';
    }
    if(rows.length===0){
      tableEl.innerHTML='<div style="color:var(--muted)">No Momentum/AI recommendations logged for this date yet.</div>';
      return;
    }
    let html='<div style="display:flex;flex-direction:column;gap:4px;max-height:340px;overflow-y:auto">';
    rows.forEach(r=>{
      const achieved=!!r.achieved;
      const statusColor=achieved?'var(--green)':(r.final_status==='Not Achieved'?'var(--red)':'var(--muted)');
      const statusText=achieved?`✅ Hit @ ₹${fmtNum(r.achieved_price||0)}`:(r.final_status==='Not Achieved'?'❌ Not achieved':'⏳ Open');
      const sideColor=r.side==='Buy'?'var(--green)':'var(--red)';
      html+=`<div style="display:grid;grid-template-columns:70px 60px 60px 1fr 1fr;gap:8px;align-items:center;padding:5px 8px;background:rgba(255,255,255,.03);border-radius:6px">
        <span style="color:#fff;font-weight:600">${escHtml(r.symbol)}</span>
        <span style="color:var(--accent2);font-weight:600">${escHtml(r.engine)}</span>
        <span style="color:${sideColor};font-weight:700;text-transform:uppercase">${escHtml(r.side)}</span>
        <span style="color:var(--muted2)">Entry ₹${fmtNum(r.entry_price)} → Target ₹${fmtNum(r.target_price)}</span>
        <span style="color:${statusColor};font-weight:600">${statusText}</span>
      </div>`;
    });
    html+='</div>';
    tableEl.innerHTML=html;
  }catch(e){
    tableEl.innerHTML=`<div class="err-box">Error: ${escHtml(e.message)}</div>`;
  }
}

// Builds a CSV client-side from the last loaded combined report and triggers
// a download — this is the "EOD report with success/failure rate, stock,
// buy/sell, price, target price" the person can keep for their own records.
function downloadCombinedEodReport(){
  const d=_lastCombinedEod;
  if(!d || !d.rows || d.rows.length===0){ alert('No Momentum/AI recommendations for this date yet.'); return; }
  const headers=['Date','Engine','Symbol','Side','Entry Price','Target Price','Status','Achieved Price','Entry Time','Achieved Time'];
  const lines=[headers.join(',')];
  d.rows.forEach(r=>{
    const achieved=!!r.achieved;
    const status=achieved?'Hit':(r.final_status==='Not Achieved'?'Not Achieved':'Open');
    const row=[d.date, r.engine, r.symbol, r.side, r.entry_price, r.target_price, status, r.achieved_price||'', r.entry_time||'', r.achieved_at||''];
    lines.push(row.map(v=>{
      const s=String(v??'');
      return /[",\n]/.test(s) ? '"'+s.replace(/"/g,'""')+'"' : s;
    }).join(','));
  });
  const blob=new Blob([lines.join('\n')], {type:'text/csv'});
  const url=URL.createObjectURL(blob);
  const a=document.createElement('a');
  a.href=url; a.download=`eod_report_${d.date}.csv`;
  document.body.appendChild(a); a.click(); document.body.removeChild(a);
  URL.revokeObjectURL(url);
}

async function loadEodReport(date){
  eodLoaded=true;
  loadCombinedEodReport(date||''); // Prakash + AI recommendation log, independent of the signal-tracker table below
  // Load available dates into picker
  try{
    const dr=await fetch(apiUrl('api/eod/dates'));
    const dd=await dr.json();
    const picker=document.getElementById('eodDatePicker');
    if(picker){
      const today=new Date().toISOString().slice(0,10);
      picker.innerHTML='<option value="">Today</option>';
      (dd.dates||[]).forEach(d=>{
        if(d!==today){
          const opt=document.createElement('option');
          opt.value=d; opt.textContent=d;
          if(d===date) opt.selected=true;
          picker.appendChild(opt);
        }
      });
    }
  }catch(e){}

  document.getElementById('eodLoading').style.display='block';
  document.getElementById('eodTable').style.display='none';
  document.getElementById('eodEmpty').style.display='none';
  document.getElementById('eodSummary').style.display='none';

  try{
    // Step 1: get saved signals from PHP (no external fetch needed)
    const url=apiUrl('api/eod/report')+(date?'?date='+encodeURIComponent(date):'');
    const r=await fetch(url);
    const d=await r.json();
    document.getElementById('eodLoading').style.display='none';

    if(!d.signals||!d.signals.length){
      document.getElementById('eodEmpty').style.display='block';
      return;
    }

    // Step 2: browser fetches current prices for all signal symbols
    const syms=d.signals.map(s=>(s.symbol.endsWith('.NS')?s.symbol:s.symbol+'.NS'));
    if(syms.length){
      try{
        const quotes=await browserFetchQuotes(syms);
        // Inject current prices into signals
        const priceMap={};
        quotes.forEach(q=>{ priceMap[q.symbol.replace('.NS','')]={price:q.regularMarketPrice||0,prev:q.regularMarketPreviousClose||0}; });
        d.signals.forEach(sig=>{
          const key=sig.symbol.replace('.NS','');
          if(priceMap[key]){
            sig.current_price=priceMap[key].price;
            sig.price_change_pct=sig.entry_price>0?+((( priceMap[key].price-sig.entry_price)/sig.entry_price)*100).toFixed(2):0;
            // Update status based on live price
            const live=priceMap[key].price;
            const isBuy=(sig.signal||'').toLowerCase()==='buy';
            if(live>0){
              if(isBuy){
                if(live>=sig.target_price) sig.status='target_hit';
                else if(sig.stoploss>0&&live<=sig.stoploss) sig.status='sl_hit';
                else sig.status='open';
              }else{
                if(live<=sig.target_price) sig.status='target_hit';
                else if(sig.stoploss>0&&live>=sig.stoploss) sig.status='sl_hit';
                else sig.status='open';
              }
            }
          }
        });
        // Recalculate summary
        let hits=0,misses=0,pending=0;
        d.signals.forEach(s=>{
          if(s.status==='target_hit')hits++;
          else if(s.status==='sl_hit')misses++;
          else pending++;
        });
        const resolved=hits+misses;
        d.summary={...d.summary,hits,misses,pending,hit_pct:resolved>0?Math.round(hits/resolved*100):null};
      }catch(e){}
    }

    renderEodReport(d);
  }catch(e){
    document.getElementById('eodLoading').innerHTML='<div class="err-box" style="margin:16px">'+escHtml(e.message)+'</div>';
  }
}

function renderEodReport(d){
  const sum=d.summary||{};
  const sigs=d.signals||[];

  // Summary KPIs
  document.getElementById('eodSummary').style.display='block';
  document.getElementById('eodTotal').textContent=sum.total||0;
  document.getElementById('eodHits').textContent=sum.hits||0;
  document.getElementById('eodMisses').textContent=sum.misses||0;
  document.getElementById('eodPending').textContent=sum.pending||0;
  const pct=sum.hit_pct;
  const pctLabel=pct!==null&&pct!==undefined?pct+'%':'—';
  document.getElementById('eodHitPct').textContent=pctLabel;
  document.getElementById('eodHitPct').style.color=pct>=70?'var(--green)':pct>=50?'var(--orange)':'var(--red)';
  document.getElementById('eodHitSub').textContent=pct!==null?`${pct}% accuracy`:'targets given';

  // Progress bar
  const bar=document.getElementById('eodProgressBar');
  const accLabel=document.getElementById('eodAccLabel');
  if(pct!==null){
    bar.style.width=pct+'%';
    bar.style.background=pct>=70?'linear-gradient(90deg,var(--green),#34d399)':pct>=50?'linear-gradient(90deg,var(--orange),#fbbf24)':'linear-gradient(90deg,var(--red),#f87171)';
    accLabel.textContent=pct+'% Hit Rate';
    accLabel.style.color=pct>=70?'var(--green)':pct>=50?'var(--orange)':'var(--red)';
  } else {
    bar.style.width='0%';
    accLabel.textContent='No resolved signals yet';
  }
  document.getElementById('eodProgressWrap').style.display='block';

  // Build table
  const rows=sigs.map(s=>{
    const isBuy=(s.signal||'').toLowerCase()==='buy';
    const status=s.status||'pending';
    const statusIcon=status==='target_hit'?'✅':status==='sl_hit'?'❌':'⏳';
    const statusLabel=status==='target_hit'?'Target Hit':status==='sl_hit'?'SL Hit':'Open';
    const statusColor=status==='target_hit'?'var(--green)':status==='sl_hit'?'var(--red)':'var(--orange)';
    const statusBg=status==='target_hit'?'rgba(16,185,129,.1)':status==='sl_hit'?'rgba(239,68,68,.1)':'rgba(245,158,11,.1)';
    const sigBadge=isBuy?'badge-buy':'badge-sell';
    const curP=s.current_price||0;
    const entryP=s.entry_price||0;
    const tgtP=s.target_price||0;
    const slP=s.stoploss||0;
    const chgPct=s.price_change_pct||0;
    const tgtPct=entryP>0&&tgtP>0?((tgtP-entryP)/entryP*100):0;
    const slPct=entryP>0&&slP>0?((slP-entryP)/entryP*100):0;
    // Progress toward target
    let progress=0;
    if(entryP>0&&tgtP>0&&tgtP!==entryP){
      if(isBuy) progress=Math.min(100,Math.max(0,((curP-entryP)/(tgtP-entryP))*100));
      else progress=Math.min(100,Math.max(0,((entryP-curP)/(entryP-tgtP))*100));
    }
    const progColor=status==='target_hit'?'var(--green)':status==='sl_hit'?'var(--red)':'var(--accent)';

    return `<tr style="${status==='target_hit'?'background:rgba(16,185,129,.04)':status==='sl_hit'?'background:rgba(239,68,68,.04)':''}">
      <td>
        <div class="sym">${escHtml(s.symbol||'')}</div>
        <div class="co-name">${escHtml(s.name||'')}</div>
      </td>
      <td><span class="badge ${sigBadge}">${escHtml(s.signal||'')}</span></td>
      <td style="font-size:12px;color:var(--muted2)">${escHtml(s.saved_at||'—')}</td>
      <td style="font-weight:700;color:var(--accent2)">₹${fmtNum(entryP)}</td>
      <td>
        <div style="font-weight:700;color:var(--green)">₹${fmtNum(tgtP)}</div>
        <div style="font-size:10px;color:var(--green);opacity:.8">${tgtPct>=0?'+':''}${tgtPct.toFixed(1)}% from entry</div>
      </td>
      <td>
        <div style="font-weight:600;color:var(--red)">₹${fmtNum(slP)}</div>
        <div style="font-size:10px;color:var(--red);opacity:.8">${slPct.toFixed(1)}% risk</div>
      </td>
      <td>
        <div style="font-weight:700;color:${chgPct>=0?'var(--green)':'var(--red)'}">₹${fmtNum(curP)}</div>
        <div style="font-size:10px;color:${chgPct>=0?'var(--green)':'var(--red)'}">${chgPct>=0?'+':''}${chgPct.toFixed(2)}%</div>
      </td>
      <td style="min-width:110px">
        <div style="height:5px;background:rgba(255,255,255,.07);border-radius:3px;margin-bottom:4px;overflow:hidden">
          <div style="width:${progress.toFixed(0)}%;height:100%;background:${progColor};border-radius:3px;transition:width .4s"></div>
        </div>
        <div style="font-size:10px;color:var(--muted)">${progress.toFixed(0)}% to target</div>
      </td>
      <td>
        <div style="display:inline-flex;align-items:center;gap:6px;background:${statusBg};border:1px solid ${statusColor};border-radius:20px;padding:4px 12px;font-size:12px;font-weight:700;color:${statusColor}">
          ${statusIcon} ${statusLabel}
        </div>
      </td>
    </tr>`;
  }).join('');

  const buySigs=sigs.filter(s=>(s.signal||'').toLowerCase()==='buy');
  const sellSigs=sigs.filter(s=>(s.signal||'').toLowerCase()==='sell');
  const buyHits=buySigs.filter(s=>s.status==='target_hit').length;
  const sellHits=sellSigs.filter(s=>s.status==='target_hit').length;

  const el=document.getElementById('eodTable');
  el.innerHTML=`
    <!-- Mini stats -->
    <div style="display:flex;gap:12px;padding:12px 16px;border-bottom:1px solid var(--border);flex-wrap:wrap">
      <div style="font-size:12px;color:var(--muted)">
        <span style="color:var(--green);font-weight:700">📈 Buy signals: ${buySigs.length}</span>
        <span style="color:var(--muted);margin-left:4px">(${buyHits} hit)</span>
      </div>
      <div style="font-size:12px;color:var(--muted)">
        <span style="color:var(--red);font-weight:700">📉 Sell signals: ${sellSigs.length}</span>
        <span style="color:var(--muted);margin-left:4px">(${sellHits} hit)</span>
      </div>
      <div style="margin-left:auto;font-size:11px;color:var(--muted)">
        Date: ${escHtml(d.date||'')} · Auto-refreshes on page load
      </div>
    </div>
    <div style="overflow-x:auto">
    <table>
      <thead><tr>
        <th>Symbol</th>
        <th>Signal</th>
        <th>Saved At</th>
        <th>Entry Price</th>
        <th>Target</th>
        <th>Stop Loss</th>
        <th>Current Price</th>
        <th>Progress</th>
        <th>Result</th>
      </tr></thead>
      <tbody>${rows}</tbody>
    </table>
    </div>
    <div style="padding:10px 16px;font-size:10px;color:var(--muted);border-top:1px solid var(--border)">
      ✅ Target Hit = price reached the target &nbsp;|&nbsp; ❌ SL Hit = stop-loss triggered &nbsp;|&nbsp; ⏳ Open = still in play &nbsp;|&nbsp; Signals auto-saved from Watchlist and Analyze tabs
    </div>`;
  el.style.display='block';
}

// ── Boot ──────────────────────────────────────────────────────
loadWatchlist();
// Load custom watchlist chips
(async()=>{try{const r=await fetch(apiUrl('api/watchlist/list'));const d=await r.json();renderWatchlistManager(d.watchlist||[]);}catch(e){}})();
loadSectors();
// Check alerts on load
setTimeout(async()=>{
  try{const r=await fetch(apiUrl('api/alerts/check'));const d=await r.json();
  (d.triggered||[]).forEach(a=>alert('🔔 ALERT: '+a.symbol+' hit ₹'+a.triggered_price));}catch(e){}
},3000);
</script>

<?php }
