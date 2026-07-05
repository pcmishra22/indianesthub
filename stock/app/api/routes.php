<?php
declare(strict_types=1);
/**
 * api/routes.php — route-to-handler wiring for endpoints whose business
 * logic lives elsewhere (watchlist.php, eod.php, news.php, alerts.php,
 * intraday.php). This file is just the "if ($uri === ...) { call it }"
 * glue, kept together since each block is short.
 */

if ($uri === '/api/watchlist') {
    header('Content-Type: application/json');
    try { echo json_encode(apiWatchlist()); } catch (\Throwable $e) { echo json_encode(['error' => $e->getMessage(), 'line' => $e->getLine()]); }
    exit;
}

if ($uri === '/api/analyze' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $sym = strtoupper(trim($_POST['symbol'] ?? ''));
    try { echo json_encode(apiAnalyze($sym)); } catch (\Throwable $e) { echo json_encode(['error' => $e->getMessage(), 'line' => $e->getLine()]); }
    exit;
}
if ($uri === '/api/news') {
    header('Content-Type: application/json');
    try { echo json_encode(apiNews()); } catch (\Throwable $e) { echo json_encode(['error' => $e->getMessage(), 'line' => $e->getLine()]); }
    exit;
}

// Per-minute tick — fast quote fetch + signal recording
if ($uri === '/api/tick') {
    header('Content-Type: application/json');
    try { echo json_encode(apiTick()); } catch (\Throwable $e) { echo json_encode(['error' => $e->getMessage(), 'line' => $e->getLine()]); }
    exit;
}

// Return accumulated signal history for leaderboard
if ($uri === '/api/leaders') {
    header('Content-Type: application/json');
    try { echo json_encode(apiLeaders()); } catch (\Throwable $e) { echo json_encode(['error' => $e->getMessage(), 'line' => $e->getLine()]); }
    exit;
}

// ── Custom watchlist management ───────────────────────────────
if ($uri === '/api/watchlist/add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $username = getCurrentUser();
    $sym = strtoupper(trim($_POST['symbol'] ?? ''));
    if ($sym && !str_ends_with($sym, '.NS')) $sym .= '.NS';
    $wl = $username ? getUserWatchlist($username) : [];
    if ($sym && !in_array($sym, $wl)) { $wl[] = $sym; }
    if ($username) saveUserWatchlist($username, $wl);
    echo json_encode(['ok' => true, 'watchlist' => $wl]);
    exit;
}
if ($uri === '/api/watchlist/remove' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $username = getCurrentUser();
    $sym = strtoupper(trim($_POST['symbol'] ?? ''));
    if (!str_ends_with($sym, '.NS')) $sym .= '.NS';
    $wl = $username ? getUserWatchlist($username) : [];
    $wl = array_values(array_filter($wl, fn($s) => $s !== $sym));
    if ($username) saveUserWatchlist($username, $wl);
    echo json_encode(['ok' => true, 'watchlist' => $wl]);
    exit;
}
if ($uri === '/api/watchlist/list') {
    header('Content-Type: application/json');
    $username = getCurrentUser();
    $wl = $username ? getUserWatchlist($username) : [];
    echo json_encode(['watchlist' => $wl]);
    exit;
}
if ($uri === '/api/watchlist/reset' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $username = getCurrentUser();
    if ($username) {
        saveUserWatchlist($username, []);
        $cacheFile = getUserWatchlistCachePath($username);
        if (file_exists($cacheFile)) unlink($cacheFile);
    }
    echo json_encode(['ok' => true]);
    exit;
}

// ── Clear Yahoo Finance crumb/cookie/quote cache (force re-auth) ──
if ($uri === '/api/cache/clear') {
    header('Content-Type: application/json');
    $cleared = [];
    foreach (['/yahoo_crumb.json', '/nse_cookie.json', '/nse_mkt_cookie.json', '/bulk_quotes.json'] as $f) {
        $p = STORAGE . $f;
        if (file_exists($p)) { unlink($p); $cleared[] = basename($f); }
    }
    echo json_encode(['ok' => true, 'cleared' => $cleared]);
    exit;
}

if ($uri === '/api/alerts/save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $alerts = file_exists(ALERT_FILE) ? json_decode(file_get_contents(ALERT_FILE), true) : [];
    $alerts[] = [
        'symbol'    => strtoupper(trim($_POST['symbol'] ?? '')),
        'condition' => $_POST['condition'] ?? 'above',  // above|below
        'price'     => (float)($_POST['price'] ?? 0),
        'created'   => time(),
        'triggered' => false,
    ];
    file_put_contents(ALERT_FILE, json_encode($alerts));
    echo json_encode(['ok' => true]);
    exit;
}
if ($uri === '/api/alerts/check') {
    header('Content-Type: application/json');
    echo json_encode(checkAlerts());
    exit;
}

// ── Intraday candles (5-min / 15-min) ────────────────────────
if ($uri === '/api/intraday') {
    header('Content-Type: application/json');
    $sym      = strtoupper(trim($_GET['symbol'] ?? ''));
    $interval = in_array($_GET['interval'] ?? '', ['5m','15m','1h']) ? $_GET['interval'] : '5m';
    if ($sym && !str_ends_with($sym, '.NS')) $sym .= '.NS';
    try { echo json_encode(apiIntraday($sym, $interval)); } catch (\Throwable $e) { echo json_encode(['error' => $e->getMessage(), 'line' => $e->getLine()]); }
    exit;
}

// ── Historical OHLCV — called by browser's browserFetchHistory() ──
if ($uri === '/api/history') {
    header('Content-Type: application/json');
    $sym  = strtoupper(trim($_GET['symbol'] ?? ''));
    $days = max(30, min(365, (int)($_GET['days'] ?? 90)));
    if (!$sym) { echo json_encode(['error' => 'No symbol']); exit; }
    if (!str_ends_with($sym, '.NS')) $sym .= '.NS';
    $rows = yahooHistory($sym, $days);
    echo json_encode(['symbol' => $sym, 'days' => $days, 'rows' => $rows, 'count' => count($rows)]);
    exit;
}


// ── Pivot points ──────────────────────────────────────────────
if ($uri === '/api/pivots') {
    header('Content-Type: application/json');
    $sym = strtoupper(trim($_GET['symbol'] ?? ''));
    if ($sym && !str_ends_with($sym, '.NS')) $sym .= '.NS';
    try { echo json_encode(apiPivots($sym)); } catch (\Throwable $e) { echo json_encode(['error' => $e->getMessage(), 'line' => $e->getLine()]); }
    exit;
}

// ── Cron endpoint (call from crontab every minute) ───────────
// Usage: * * * * * curl -s "http://localhost/stock_v3/api/cron?key=YOUR_CRON_KEY" > /dev/null
if ($uri === '/api/cron') {
    $cronKey = getenv('CRON_KEY') ?: 'changeme';
    if (($_GET['key'] ?? '') !== $cronKey) { http_response_code(403); echo '{"error":"forbidden"}'; exit; }
    header('Content-Type: application/json');
    try { echo json_encode(apiTick()); } catch (\Throwable $e) { echo json_encode(['error' => $e->getMessage(), 'line' => $e->getLine()]); }
    exit;
}

// Paginated watchlist — 20 stocks per page, filterable by sector/search
if ($uri === '/api/watchlist/page') {
    header('Content-Type: application/json');
    $page   = max(1, (int)($_GET['page']   ?? 1));
    $sector = trim($_GET['sector'] ?? '');
    $search = strtoupper(trim($_GET['search'] ?? ''));
    try {
        try { echo json_encode(apiWatchlistPage($page, $sector, $search)); } catch (\Throwable $e) { echo json_encode(['error' => $e->getMessage(), 'line' => $e->getLine()]); }
    } catch (\Throwable $e) {
        echo json_encode(['error' => $e->getMessage(), 'file' => basename($e->getFile()), 'line' => $e->getLine(), 'stocks' => []]);
    }
    exit;
}

// ── Momentum Picks — standalone open-persistence + sector-momentum list ──
// (independent of the main generateSignal()/generateSignalFull() scorecard)
if ($uri === '/api/momentum-picks') {
    header('Content-Type: application/json');
    try { echo json_encode(apiMomentumPicks()); } catch (\Throwable $e) { echo json_encode(['error' => $e->getMessage(), 'line' => $e->getLine()]); }
    exit;
}

// All available sectors
if ($uri === '/api/sectors') {
    header('Content-Type: application/json');
    echo json_encode(['sectors' => array_keys(SECTOR_MAP)]);
    exit;
}

// ── Save a signal with price + target for EOD tracking ────────
if ($uri === '/api/signal/save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $today = date('Y-m-d');
    $file  = STORAGE . '/eod_signals_' . $today . '.json';
    $saved = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    $sym   = strtoupper(trim($_POST['symbol'] ?? ''));
    $entry = [
        'symbol'       => $sym,
        'name'         => trim($_POST['name'] ?? $sym),
        'signal'       => trim($_POST['signal'] ?? ''),
        'entry_price'  => (float)($_POST['entry_price'] ?? 0),
        'target_price' => (float)($_POST['target_price'] ?? 0),
        'stoploss'     => (float)($_POST['stoploss'] ?? 0),
        'target2'      => (float)($_POST['target2'] ?? 0),
        'saved_at'     => date('H:i:s'),
        'ts'           => time(),
        'hit'          => null,   // filled at EOD check
        'close_price'  => null,
    ];
    // Overwrite if same symbol already saved today
    $idx = array_search($sym, array_column($saved, 'symbol'));
    if ($idx !== false) $saved[$idx] = $entry;
    else $saved[] = $entry;
    if (!is_dir(STORAGE)) mkdir(STORAGE, 0755, true);
    file_put_contents($file, json_encode($saved));
    echo json_encode(['ok' => true, 'entry' => $entry]);
    exit;
}

// ── EOD Report: list today's signals + live price check ───────
if ($uri === '/api/eod/report') {
    header('Content-Type: application/json');
    try { echo json_encode(apiEodReport()); } catch (\Throwable $e) { echo json_encode(['error' => $e->getMessage(), 'line' => $e->getLine()]); }
    exit;
}

// ── EOD Report: check current prices vs targets ───────────────
if ($uri === '/api/eod/check') {
    header('Content-Type: application/json');
    try { echo json_encode(apiEodCheck()); } catch (\Throwable $e) { echo json_encode(['error' => $e->getMessage(), 'line' => $e->getLine()]); }
    exit;
}

// ── EOD Report: list all dates that have saved signal files ───
if ($uri === '/api/eod/dates') {
    header('Content-Type: application/json');
    $files = glob(STORAGE . '/eod_signals_*.json') ?: [];
    $dates = [];
    foreach ($files as $f) {
        preg_match('/eod_signals_(\d{4}-\d{2}-\d{2})\.json$/', $f, $m);
        if ($m[1] ?? null) $dates[] = $m[1];
    }
    rsort($dates);
    echo json_encode(['dates' => $dates]);
    exit;
}

// ── Prakash Track Record: rolled-up win rate across all daily files ───
if ($uri === '/api/prakash/rollup') {
    header('Content-Type: application/json');
    $days = isset($_GET['days']) ? max(1, (int)$_GET['days']) : 90;
    try { echo json_encode(prakashRollupHistory(getCurrentUser(), $days)); }
    catch (\Throwable $e) { echo json_encode(['error' => $e->getMessage(), 'line' => $e->getLine()]); }
    exit;
}



// ── Server-side bulk quotes via Stooq (replaces browser Yahoo fetch) ──
if ($uri === '/api/quotes/bulk' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $body    = file_get_contents('php://input');
    $data    = json_decode($body, true);
    $symbols = $data['symbols'] ?? [];
    if (empty($symbols)) {
        echo json_encode(['ok' => false, 'error' => 'No symbols']); exit;
    }
    // Sanitize
    $symbols = array_map(fn($s) => strtoupper(trim($s)), $symbols);
    $symbols = array_filter($symbols, fn($s) => preg_match('/^[A-Z0-9\.\-&]+$/', $s));
    $symbols = array_values($symbols);

    // Use yahooQuoteBulk which tries: Stooq → Yahoo v7 → Yahoo v8 → NSE
    // Results are cached in bulk_quotes.json for 5 min
    // Only delete cache if it was not written by the browser (via /api/proxy/quotes).
    // Browser-pushed data is the fallback when server sources are IP-blocked.
    $bulkCacheFile = STORAGE . '/bulk_quotes.json';
    // Do NOT unlink here — preserve browser-pushed quotes in the cache.

    $quotes = yahooQuoteBulk($symbols);
    $sources_tried = ['stooq_ns', 'yahoo_v7', 'yahoo_v8', 'nse'];

    // Save to bulk_quotes.json so server-side TA can read it
    if (!empty($quotes)) {
        if (!is_dir(STORAGE)) mkdir(STORAGE, 0755, true);
        $existing = file_exists(STORAGE . '/bulk_quotes.json')
            ? (json_decode(file_get_contents(STORAGE . '/bulk_quotes.json'), true) ?? []) : [];
        foreach ($quotes as $sym => $q) $existing[$sym] = $q;
        file_put_contents(STORAGE . '/bulk_quotes.json', json_encode($existing));
    }

    echo json_encode([
        'ok'           => !empty($quotes),
        'quotes'       => array_values($quotes),
        'count'        => count($quotes),
        'sources_tried'=> $sources_tried,
        'error'        => empty($quotes) ? ('All quote sources failed (Stooq, NSE, Groww, BSE). Market may be closed or sources temporarily unavailable.') : null,
    ]);
    exit;
}


