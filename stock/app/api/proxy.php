<?php
declare(strict_types=1);
/**
 * api/proxy.php — receives quote/history data fetched by the BROWSER and
 * stores it server-side so PHP's technical-analysis functions can use it.
 *
 * This exists because of the CORS wall documented in datasources.php: the
 * browser can sometimes reach data sources directly (no server IP block),
 * but the resulting JSON can't be read cross-origin unless the source sends
 * Access-Control-Allow-Origin — which Yahoo/Groww do not. So the actual
 * working pattern is: browser fetches → browser cannot read the response
 * due to CORS → this proxy is currently NOT the primary path; the real fix
 * is DATA_API_KEY (Twelve Data) called server-side, which has no CORS
 * concern since the browser never talks to it directly. Kept for symmetry
 * and as a manual-override path (e.g. pasting in a quote via devtools).
 */

// ── Browser-proxy: accept quote data fetched by the client browser ──
// Browser fetches from Yahoo/NSE (no server-IP block), POSTs JSON here.
// PHP stores it in the bulk cache so all analysis functions can use it.
if ($uri === '/api/proxy/quotes' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $body   = file_get_contents('php://input');
    $quotes = json_decode($body, true);
    if (!is_array($quotes) || empty($quotes)) {
        echo json_encode(['ok' => false, 'error' => 'No quote data received']);
        exit;
    }
    // Normalise: key by symbol
    $map = [];
    foreach ($quotes as $q) {
        $sym = $q['symbol'] ?? null;
        if ($sym) $map[$sym] = $q;
    }
    if (!is_dir(STORAGE)) mkdir(STORAGE, 0755, true);
    file_put_contents(STORAGE . '/bulk_quotes.json', json_encode($map));
    echo json_encode(['ok' => true, 'stored' => count($map)]);
    exit;
}

// ── Browser-proxy: accept historical OHLCV data for one symbol ──
if ($uri === '/api/proxy/history' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);
    $sym  = strtoupper(trim($data['symbol'] ?? ''));
    $rows = $data['rows'] ?? [];
    if (!$sym || empty($rows)) {
        echo json_encode(['ok' => false, 'error' => 'Missing symbol or rows']);
        exit;
    }
    if (!is_dir(STORAGE)) mkdir(STORAGE, 0755, true);
    $cacheFile = STORAGE . '/hist_' . preg_replace('/[^A-Z0-9]/', '_', $sym) . '.json';
    file_put_contents($cacheFile, json_encode($rows));
    echo json_encode(['ok' => true, 'symbol' => $sym, 'bars' => count($rows)]);
    exit;
}

// ── Browser-proxy: analyze after browser pushes history ─────────
if ($uri === '/api/proxy/analyze' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);
    $sym  = strtoupper(trim($data['symbol'] ?? ''));
    $rows = $data['rows'] ?? [];   // OHLCV array from browser
    $quote= $data['quote'] ?? [];  // quote snapshot from browser

    if (!$sym) { echo json_encode(['error' => 'No symbol']); exit; }

    // Store history so apiAnalyze can read it
    if (!empty($rows)) {
        if (!is_dir(STORAGE)) mkdir(STORAGE, 0755, true);
        $cacheFile = STORAGE . '/hist_' . preg_replace('/[^A-Z0-9]/', '_', $sym . '.NS') . '.json';
        file_put_contents($cacheFile, json_encode($rows));
    }
    // Store quote so yahooQuote() can read it from bulk cache
    if (!empty($quote)) {
        $bulkCache = STORAGE . '/bulk_quotes.json';
        $all = file_exists($bulkCache) ? json_decode(file_get_contents($bulkCache), true) : [];
        $all[$sym . '.NS'] = $quote;
        file_put_contents($bulkCache, json_encode($all));
    }

    try { echo json_encode(apiAnalyze($sym)); }
    catch (\Throwable $e) { echo json_encode(['error' => $e->getMessage(), 'line' => $e->getLine()]); }
    exit;
}

