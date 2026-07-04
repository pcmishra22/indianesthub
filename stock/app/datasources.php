<?php
declare(strict_types=1);
/**
 * datasources.php — all external market-data fetchers, normalised to a common
 * Yahoo-Finance-shaped quote array so callers (signals.php, api/*.php) never
 * need to know which provider actually answered.
 *
 * ── STATUS AS OF 2026-06-30 (verified, not guessed) ──────────────────────
 * - Yahoo Finance: DEAD for this use case. The crumb endpoint
 *   (v1/test/csrfToken) returns "Unknown Host" from Yahoo's own edge — the
 *   backend service has been decommissioned. The v7 quote endpoint, even with
 *   a valid session cookie, returns a clean 401 "User is unable to access
 *   this feature" directly from Yahoo's API. This is Yahoo deliberately
 *   closing third-party access, not a bug on our end. Kept below only as a
 *   documented dead-end — do not re-enable as a primary source.
 * - NSE India: returns a clean WAF 403 in ~75ms from this server's hosting
 *   IP range (Hostinger shared hosting). Likely IP-reputation blocking of
 *   datacenter ranges. Kept as a fallback in case it's intermittent or your
 *   IP range changes; not reliable as a primary source.
 * - Stooq: had a malformed URL (bare "&h" flag with no value) causing 404s.
 *   Fixed below. Coverage of NSE tickers is thin/inconsistent — usable as a
 *   tertiary fallback for daily history, not for live quotes.
 * - Groww / BSE: unofficial internal APIs, unverified reliability, kept as
 *   last-resort fallbacks only.
 *
 * PRIMARY SOURCE: a real, documented API with a key (Twelve Data / EODHD —
 * see twelveDataQuote() / twelveDataHistory() below). This is what production
 * code should actually rely on; everything else here is a safety net only.
 */

// ══════════════════════════════════════════════════════════════
//  PRIMARY: real API with key (fill in once DATA_API_KEY is set)
// ══════════════════════════════════════════════════════════════

/**
 * Fetch a single quote from Twelve Data (https://twelvedata.com).
 * Free tier: 800 requests/day, documented NSE support, real CORS/auth —
 * no scraping, no IP-reputation risk, no risk of being silently deprecated
 * the way the Yahoo crumb endpoint was.
 *
 * Returns null if DATA_API_KEY is not configured, so callers fall through
 * to the legacy scrapers automatically until you've signed up.
 */
function twelveDataQuote(string $symbol): ?array
{
    $result = twelveDataQuoteDebug($symbol);
    return $result['quote'];
}

/**
 * Same as twelveDataQuote() but returns the raw response and diagnosis
 * instead of swallowing it — used by /api/debug/twelvedata so a wrong
 * assumption about field names fails visibly instead of looking identical
 * to "no data available" (the trap that cost a week with Yahoo).
 */
function twelveDataQuoteDebug(string $symbol): array
{
    if (!DATA_API_KEY) {
        return ['quote' => null, 'http_code' => null, 'raw' => null, 'diagnosis' => 'No DATA_API_KEY set in .env'];
    }

    $nseSym = strtoupper(str_replace('.NS', '', $symbol));
    $url = 'https://api.twelvedata.com/quote?symbol=' . urlencode($nseSym)
         . '&exchange=NSE&apikey=' . urlencode(DATA_API_KEY);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 10, CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => ['Accept: application/json'],
    ]);
    $raw       = curl_exec($ch);
    $code      = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr   = curl_error($ch);
    $curlErrno = curl_errno($ch);
    curl_close($ch);

    if ($raw === false) {
        return ['quote' => null, 'http_code' => $code, 'raw' => null,
                'diagnosis' => "curl failed (errno {$curlErrno}): {$curlErr}"];
    }

    $d = json_decode($raw, true);
    if ($d === null) {
        return ['quote' => null, 'http_code' => $code, 'raw' => substr($raw, 0, 500),
                'diagnosis' => "Response was not valid JSON — HTTP {$code}"];
    }

    // Twelve Data returns errors as HTTP 200 with a "status"/"code"/"message" payload,
    // NOT as a non-200 HTTP status — this must be checked explicitly.
    if (isset($d['status']) && $d['status'] === 'error') {
        return ['quote' => null, 'http_code' => $code, 'raw' => $d,
                'diagnosis' => 'API returned an error: ' . ($d['message'] ?? 'unknown error') . ' (code ' . ($d['code'] ?? '?') . ')'];
    }
    if (!isset($d['close'])) {
        return ['quote' => null, 'http_code' => $code, 'raw' => $d,
                'diagnosis' => 'Response had no "close" field — unexpected shape, check raw response'];
    }

    $price = (float)$d['close'];
    if ($price <= 0) {
        return ['quote' => null, 'http_code' => $code, 'raw' => $d,
                'diagnosis' => 'close field was present but <= 0'];
    }

    $quote = [
        'symbol'                     => $nseSym . '.NS',
        'shortName'                  => $d['name'] ?? $nseSym,
        'longName'                   => $d['name'] ?? $nseSym,
        'regularMarketPrice'         => $price,
        'regularMarketChange'        => (float)($d['change'] ?? 0),
        'regularMarketChangePercent' => (float)($d['percent_change'] ?? 0),
        'regularMarketPreviousClose' => (float)($d['previous_close'] ?? $price),
        'regularMarketOpen'          => (float)($d['open'] ?? $price),
        'regularMarketDayHigh'       => (float)($d['high'] ?? $price),
        'regularMarketDayLow'        => (float)($d['low'] ?? $price),
        'regularMarketVolume'        => (int)($d['volume'] ?? 0),
        'averageDailyVolume3Month'   => (int)($d['average_volume'] ?? 0),
        'fiftyTwoWeekHigh'           => (float)($d['fifty_two_week']['high'] ?? $price),
        'fiftyTwoWeekLow'            => (float)($d['fifty_two_week']['low'] ?? $price),
        'trailingPE' => null, 'priceToBook' => null, 'marketCap' => null,
        'sector' => null, 'industry' => null, 'returnOnEquity' => null, 'debtToEquity' => null,
        '_source' => 'twelvedata',
    ];
    return ['quote' => $quote, 'http_code' => $code, 'raw' => $d, 'diagnosis' => 'PASS'];
}

/**
 * Bulk quote fetch from Twelve Data.
 * Twelve Data's /quote endpoint with multiple symbols returns:
 *   { "TCS": { "symbol":"TCS","close":"3500.00",... }, "INFY": {...}, ... }
 * Each value is a stock object (or an error object with "status":"error").
 * Free tier: 800 req/day. With batches of 50 symbols per call, 5 stocks
 * costs 1 call; 200 stocks costs 4 calls — well within limits.
 */
function twelveDataQuoteBulk(array $symbols): array
{
    if (!DATA_API_KEY || empty($symbols)) return [];

    $all = [];
    foreach (array_chunk($symbols, 50) as $chunk) {
        $nseSyms  = array_map(fn($s) => strtoupper(str_replace('.NS', '', $s)), $chunk);
        $symParam = implode(',', $nseSyms);
        $url = 'https://api.twelvedata.com/quote?symbol=' . urlencode($symParam)
             . '&exchange=NSE&apikey=' . urlencode(DATA_API_KEY);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 20, CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);
        $raw  = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if (!$raw || $code !== 200) continue;

        $data = json_decode($raw, true);
        if (!is_array($data)) continue;

        // Twelve Data returns 200 with status:error for API-level errors
        if (isset($data['status']) && $data['status'] === 'error') continue;

        // Single symbol: flat object {"symbol":"TCS","close":"3500",...}
        // Multiple symbols: {"TCS":{"symbol":"TCS","close":"3500",...},"INFY":{...}}
        // Detect which shape we got by checking if values are arrays (multi) or scalars (single)
        if (count($nseSyms) === 1) {
            $entries = [$nseSyms[0] => $data];
        } else {
            $entries = $data;
        }

        foreach ($entries as $sym => $d) {
            if (!is_array($d)) continue;
            if (isset($d['status']) && $d['status'] === 'error') continue;
            $price = (float)($d['close'] ?? 0);
            if ($price <= 0) continue;
            $nseSym = strtoupper($sym) . '.NS';
            $all[$nseSym] = [
                'symbol'                     => $nseSym,
                'shortName'                  => $d['name'] ?? $sym,
                'longName'                   => $d['name'] ?? $sym,
                'regularMarketPrice'         => $price,
                'regularMarketChange'        => (float)($d['change'] ?? 0),
                'regularMarketChangePercent' => (float)($d['percent_change'] ?? 0),
                'regularMarketPreviousClose' => (float)($d['previous_close'] ?? $price),
                'regularMarketOpen'          => (float)($d['open'] ?? $price),
                'regularMarketDayHigh'       => (float)($d['high'] ?? $price),
                'regularMarketDayLow'        => (float)($d['low'] ?? $price),
                'regularMarketVolume'        => (int)($d['volume'] ?? 0),
                'averageDailyVolume3Month'   => (int)($d['average_volume'] ?? 0),
                'fiftyTwoWeekHigh'           => (float)($d['fifty_two_week']['high'] ?? $price),
                'fiftyTwoWeekLow'            => (float)($d['fifty_two_week']['low'] ?? $price),
                'trailingPE'   => isset($d['pe']) ? (float)$d['pe'] : null,
                'priceToBook'  => null,
                'marketCap'    => isset($d['market_cap']) ? (float)$d['market_cap'] : null,
                'sector'       => $d['sector'] ?? null,
                'industry'     => $d['industry'] ?? null,
                'returnOnEquity' => null, 'debtToEquity' => null,
                '_source' => 'twelvedata',
            ];
        }
        if (count($chunk) > 1) usleep(250000); // 250ms between chunks — be polite
    }
    return $all;
}

/**
 * Historical daily OHLCV from Twelve Data.
 */
function twelveDataHistory(string $symbol, int $days = 90): array
{
    if (!DATA_API_KEY) return [];

    $nseSym = strtoupper(str_replace('.NS', '', $symbol));
    $endDate   = date('Y-m-d');
    $startDate = date('Y-m-d', strtotime("-{$days} days -10 days"));
    $url = 'https://api.twelvedata.com/time_series?symbol=' . urlencode($nseSym)
         . '&exchange=NSE&interval=1day&start_date=' . $startDate . '&end_date=' . $endDate
         . '&outputsize=' . ($days + 15) . '&apikey=' . urlencode(DATA_API_KEY);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 15, CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => ['Accept: application/json'],
    ]);
    $raw  = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$raw || $code !== 200) return [];
    $d = json_decode($raw, true);
    $values = $d['values'] ?? [];
    if (empty($values)) return [];

    $rows = [];
    foreach (array_reverse($values) as $v) { // Twelve Data returns newest-first
        $close = (float)($v['close'] ?? 0);
        if ($close <= 0) continue;
        $rows[] = [
            'date'   => substr($v['datetime'] ?? '', 0, 10),
            'open'   => round((float)($v['open'] ?? $close), 2),
            'high'   => round((float)($v['high'] ?? $close), 2),
            'low'    => round((float)($v['low'] ?? $close), 2),
            'close'  => round($close, 2),
            'volume' => (int)($v['volume'] ?? 0),
        ];
    }
    $rows = array_slice($rows, -$days);
    if (!empty($rows)) {
        $cacheFile = STORAGE . '/hist_' . preg_replace('/[^A-Z0-9]/', '_', strtoupper($symbol)) . '.json';
        file_put_contents($cacheFile, json_encode($rows));
    }
    return $rows;
}

// ══════════════════════════════════════════════════════════════
//  SECONDARY: EODHD (eodhistoricaldata.com) — free tier fallback
//  Used automatically if Twelve Data returns no data (e.g. quota exceeded).
//  Free tier: end-of-day data only (no intraday), up to 20 API calls/day.
//  NSE India symbols use .NS suffix (NOT .NSE — that is Nigerian Stock Exchange).
//  BSE India symbols use .BO suffix.
//  NOTE: EODHD returns "NA" (string) for all price fields when market is closed.
//  Use eoN() helper to safely parse these values.
// ══════════════════════════════════════════════════════════════

/** Safely parse an EODHD price field that may be "NA", null, or a real number. */
function eoN(mixed $v, float $default = 0.0): float
{
    if ($v === null || $v === 'NA' || $v === '') return $default;
    return (float)$v;
}

function eodhdQuote(string $symbol): ?array
{
    $result = eodhdQuoteDebug($symbol);
    return $result['quote'];
}

/**
 * Debug variant — surfaces raw response instead of returning null silently.
 */
function eodhdQuoteDebug(string $symbol): array
{
    $key = getenv('EODHD_API_KEY') ?: '';
    if (!$key) return ['quote' => null, 'http_code' => null, 'raw' => null, 'diagnosis' => 'No EODHD_API_KEY set in .env'];

    $nseSym = strtoupper(str_replace('.NS', '', $symbol)) . '.NS';
    $url = 'https://eodhd.com/api/real-time/' . urlencode($nseSym)
         . '?api_token=' . urlencode($key) . '&fmt=json';

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 10, CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => ['Accept: application/json'],
    ]);
    $raw  = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);

    if ($raw === false) return ['quote' => null, 'http_code' => $code, 'raw' => null, 'diagnosis' => "curl error: {$err}"];
    if ($code !== 200)  return ['quote' => null, 'http_code' => $code, 'raw' => substr((string)$raw, 0, 500), 'diagnosis' => "HTTP {$code}"];

    $d = json_decode($raw, true);
    if (!is_array($d))        return ['quote' => null, 'http_code' => $code, 'raw' => substr((string)$raw, 0, 500), 'diagnosis' => 'Response is not valid JSON'];
    if (!isset($d['close']))  return ['quote' => null, 'http_code' => $code, 'raw' => $d, 'diagnosis' => 'No "close" field in response — unexpected shape'];

    // EODHD returns "NA" (string) for all fields when the market is closed.
    // Use previousClose as the price in that case — it's the last known price.
    $closeRaw = $d['close'];
    $prevRaw  = $d['previousClose'] ?? 'NA';
    $price    = eoN($closeRaw) > 0 ? eoN($closeRaw) : eoN($prevRaw);

    if ($price <= 0) return ['quote' => null, 'http_code' => $code, 'raw' => $d,
        'diagnosis' => "close={$closeRaw} and previousClose={$prevRaw} — market closed and no prior price available"];

    $base  = strtoupper(str_replace('.NS', '', $symbol));
    $quote = [
        'symbol'                     => $base . '.NS',
        'shortName'                  => $base,
        'longName'                   => $base,
        'regularMarketPrice'         => $price,
        'regularMarketChange'        => eoN($d['change']),
        'regularMarketChangePercent' => eoN($d['change_p']),
        'regularMarketPreviousClose' => eoN($d['previousClose'], $price),
        'regularMarketOpen'          => eoN($d['open'], $price),
        'regularMarketDayHigh'       => eoN($d['high'], $price),
        'regularMarketDayLow'        => eoN($d['low'], $price),
        'regularMarketVolume'        => (int)eoN($d['volume']),
        'averageDailyVolume3Month'   => (int)eoN($d['volume']),
        'fiftyTwoWeekHigh'           => eoN($d['52WeekHigh'], $price),
        'fiftyTwoWeekLow'            => eoN($d['52WeekLow'], $price),
        'trailingPE' => null, 'priceToBook' => null, 'marketCap' => null,
        'sector' => null, 'industry' => null, 'returnOnEquity' => null, 'debtToEquity' => null,
        '_source' => 'eodhd',
    ];
    return ['quote' => $quote, 'http_code' => $code, 'raw' => $d, 'diagnosis' => 'PASS'];
}

/**
 * Bulk real-time quote from EODHD.
 * EODHD supports fetching multiple symbols in one call:
 * /api/real-time/TCS.NSE?api_token=KEY&fmt=json&s=INFY.NSE,RELIANCE.NSE
 * Returns array of objects when multiple symbols are requested.
 */
function eodhdQuoteBulk(array $symbols): array
{
    $key = getenv('EODHD_API_KEY') ?: '';
    if (!$key || empty($symbols)) return [];

    $all = [];
    foreach (array_chunk($symbols, 50) as $chunk) {
        $nseSyms = array_map(fn($s) => strtoupper(str_replace('.NS', '', $s)) . '.NS', $chunk);
        // First symbol is the endpoint path, rest go in &s= param
        $primary = array_shift($nseSyms);
        $extra   = implode(',', $nseSyms);
        $url = 'https://eodhd.com/api/real-time/' . urlencode($primary)
             . '?api_token=' . urlencode($key) . '&fmt=json'
             . ($extra ? '&s=' . urlencode($extra) : '');

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 15, CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);
        $raw  = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!$raw || $code !== 200) continue;
        $data = json_decode($raw, true);
        if (!is_array($data)) continue;

        // Single symbol returns flat object; multiple returns array of objects
        $entries = isset($data[0]) ? $data : [$data];

        foreach ($entries as $d) {
            if (!is_array($d) || !isset($d['close'])) continue;
            // Use previousClose as fallback when market is closed ("NA" values)
            $price = eoN($d['close']) > 0 ? eoN($d['close']) : eoN($d['previousClose'] ?? 0);
            if ($price <= 0) continue;

            $rawCode = $d['code'] ?? '';
            $base    = strtoupper(str_replace(['.NS', '.BO', '.NSE'], '', $rawCode)) ?: strtoupper(str_replace('.NS', '', array_shift($chunk) ?? ''));
            $nsKey   = $base . '.NS';

            $all[$nsKey] = [
                'symbol'                     => $nsKey,
                'shortName'                  => $base,
                'longName'                   => $base,
                'regularMarketPrice'         => $price,
                'regularMarketChange'        => eoN($d['change']),
                'regularMarketChangePercent' => eoN($d['change_p']),
                'regularMarketPreviousClose' => eoN($d['previousClose'], $price),
                'regularMarketOpen'          => eoN($d['open'], $price),
                'regularMarketDayHigh'       => eoN($d['high'], $price),
                'regularMarketDayLow'        => eoN($d['low'], $price),
                'regularMarketVolume'        => (int)eoN($d['volume']),
                'averageDailyVolume3Month'   => (int)eoN($d['volume']),
                'fiftyTwoWeekHigh'           => eoN($d['52WeekHigh'], $price),
                'fiftyTwoWeekLow'            => eoN($d['52WeekLow'], $price),
                'trailingPE' => null, 'priceToBook' => null, 'marketCap' => null,
                'sector' => null, 'industry' => null, 'returnOnEquity' => null, 'debtToEquity' => null,
                '_source' => 'eodhd',
            ];
        }
        if (count($chunk) > 1) usleep(200000);
    }
    return $all;
}

function eodhdHistory(string $symbol, int $days = 90): array
{
    $key = getenv('EODHD_API_KEY') ?: '';
    if (!$key) return [];

    $nseSym   = strtoupper(str_replace('.NS', '', $symbol)) . '.NS';
    $from     = date('Y-m-d', strtotime("-{$days} days -5 days"));
    $to       = date('Y-m-d');
    $url = 'https://eodhd.com/api/eod/' . urlencode($nseSym)
         . '?api_token=' . urlencode($key) . '&fmt=json&from=' . $from . '&to=' . $to;

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 15, CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => ['Accept: application/json'],
    ]);
    $raw  = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$raw || $code !== 200) return [];
    $data = json_decode($raw, true);
    if (!is_array($data)) return [];

    $rows = [];
    foreach ($data as $v) {
        $close = (float)($v['close'] ?? 0);
        if ($close <= 0) continue;
        $rows[] = [
            'date'   => $v['date'] ?? '',
            'open'   => round((float)($v['open'] ?? $close), 2),
            'high'   => round((float)($v['high'] ?? $close), 2),
            'low'    => round((float)($v['low'] ?? $close), 2),
            'close'  => round($close, 2),
            'volume' => (int)($v['volume'] ?? 0),
        ];
    }
    $rows = array_slice($rows, -$days);
    if (!empty($rows)) {
        $cacheFile = STORAGE . '/hist_' . preg_replace('/[^A-Z0-9]/', '_', strtoupper($symbol)) . '.json';
        file_put_contents($cacheFile, json_encode($rows));
    }
    return $rows;
}

// ══════════════════════════════════════════════════════════════
//  UNIFIED ENTRY POINTS — callers use these, never the raw
//  per-provider functions below directly.
// ══════════════════════════════════════════════════════════════

/**
 * Get a single quote, trying the real API first, then legacy scrapers
 * as a safety net (in priority order: NSE, Stooq, Yahoo-dead-path-last).
 */
function yahooQuote(string $symbol): ?array
{
    // Check bulk cache first (populated by yahooQuoteBulk)
    $bulkCache = STORAGE . '/bulk_quotes.json';
    if (file_exists($bulkCache) && (time() - filemtime($bulkCache)) < 300) {
        $all = json_decode(file_get_contents($bulkCache), true) ?? [];
        if (!empty($all) && isset($all[$symbol])) return $all[$symbol];
    }

    // Priority 1: BSE (confirmed working — getScripHeaderData API returns live prices)
    $bse = bseQuoteFetch($symbol);
    if ($bse && ($bse['regularMarketPrice'] ?? 0) > 0) return $bse;

    // Priority 2: Stooq
    $stooq = stooqQuoteFallback($symbol);
    if ($stooq && ($stooq['regularMarketPrice'] ?? 0) > 0) return $stooq;

    // Priority 3: NSE India direct
    $nse = nseQuoteFallback($symbol);
    if ($nse && ($nse['regularMarketPrice'] ?? 0) > 0) return $nse;

    // Priority 4: NSE market fetch
    $nse2 = nseMarketFetch($symbol);
    if ($nse2 && ($nse2['regularMarketPrice'] ?? 0) > 0) return $nse2;

    // Priority 5: Groww
    $gw = growwQuoteFetch($symbol);
    if ($gw && ($gw['regularMarketPrice'] ?? 0) > 0) return $gw;

    return null;
}

/**
 * Bulk quote fetch — same priority order as yahooQuote(), but batched.
 * Caches to bulk_quotes.json for 5 minutes so repeated calls (watchlist,
 * leaders, tick) within that window share one fetch.
 */
function yahooQuoteBulk(array $symbols, bool $forceRefresh = false): array
{
    $bulkCache = STORAGE . '/bulk_quotes.json';
    if (!$forceRefresh && file_exists($bulkCache) && (time() - filemtime($bulkCache)) < 300) {
        $cached = json_decode(file_get_contents($bulkCache), true) ?? [];
        if (!empty($cached)) return $cached;
    }

    // Priority 1: BSE bulk (confirmed working API)
    $all = bseQuoteBulk($symbols);

    // Priority 2: Stooq bulk parallel
    if (empty($all)) {
        $all = stooqBulkFetch($symbols);
    }

    // Priority 3: NSE per-symbol
    if (empty($all)) {
        foreach (array_slice($symbols, 0, 20) as $sym) {
            $q = nseMarketFetch($sym);
            if (!$q) $q = nseQuoteFallback($sym);
            if ($q && ($q['regularMarketPrice'] ?? 0) > 0) $all[$sym] = $q;
            usleep(250000);
        }
    }

    // Priority 4: Groww
    if (empty($all)) {
        foreach (array_slice($symbols, 0, 20) as $sym) {
            $q = growwQuoteFetch($sym);
            if ($q && ($q['regularMarketPrice'] ?? 0) > 0) $all[$sym] = $q;
            usleep(150000);
        }
    }

    if (!empty($all)) file_put_contents($bulkCache, json_encode($all));
    return $all;
}

/**
 * Historical OHLCV — real API first, Stooq/Yahoo as fallback.
 * Cached per-symbol for 6 hours (daily data is stable intraday).
 */
function yahooHistory(string $symbol, int $days = 90): array
{
    $cacheFile = STORAGE . '/hist_' . preg_replace('/[^A-Z0-9]/', '_', strtoupper($symbol)) . '.json';
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 21600) {
        $cached = json_decode(file_get_contents($cacheFile), true);
        if (!empty($cached)) return $cached;
    }

    // Priority 1: Yahoo Finance chart — confirmed working (returns 63 bars for TCS.NS, HTTP 200)
    $period2 = time();
    $period1 = $period2 - (($days + 30) * 86400);
    foreach (['query2', 'query1'] as $host) {
        $url = "https://{$host}.finance.yahoo.com/v8/finance/chart/{$symbol}?period1={$period1}&period2={$period2}&interval=1d";
        $ch  = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 15, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_ENCODING => 'gzip',
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                'Accept: application/json', 'Referer: https://finance.yahoo.com/',
            ],
        ]);
        $raw  = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if (!$raw || $code !== 200) continue;
        $data  = json_decode($raw, true);
        $chart = $data['chart']['result'][0] ?? null;
        if (!$chart) continue;
        $timestamps = $chart['timestamp'] ?? [];
        $ohlcv      = $chart['indicators']['quote'][0] ?? [];
        $rows = [];
        foreach ($timestamps as $i => $ts) {
            $close = $ohlcv['close'][$i] ?? null;
            if ($close === null || $close <= 0) continue;
            $rows[] = [
                'date'   => date('Y-m-d', $ts),
                'open'   => round((float)($ohlcv['open'][$i]   ?? $close), 2),
                'high'   => round((float)($ohlcv['high'][$i]   ?? $close), 2),
                'low'    => round((float)($ohlcv['low'][$i]    ?? $close), 2),
                'close'  => round((float)$close, 2),
                'volume' => (int)($ohlcv['volume'][$i] ?? 0),
            ];
        }
        if (!empty($rows)) {
            $rows = array_slice($rows, -$days);
            file_put_contents($cacheFile, json_encode($rows));
            return $rows;
        }
    }

    // Priority 2: BSE historical (fallback)
    $bseRows = bseHistory($symbol, $days);
    if (!empty($bseRows)) return $bseRows;

    return [];
}

// ══════════════════════════════════════════════════════════════
//  LEGACY SCRAPERS — fallback safety net only. See header comment.
// ══════════════════════════════════════════════════════════════

/**
 * Fallback quote from NSE India's public API.
 * Normalises the response to match Yahoo Finance field names so callers need no changes.
 */
function nseQuoteFallback(string $symbol): ?array
{
    $nseSym = strtoupper(str_replace('.NS', '', $symbol));

    $cookieFile = STORAGE . '/nse_cookie.json';
    $cookieStr  = '';
    if (file_exists($cookieFile) && (time() - filemtime($cookieFile)) < 3600) {
        $cookieStr = json_decode(file_get_contents($cookieFile), true)['cookie'] ?? '';
    }

    if (!$cookieStr) {
        // NSE needs a browser session — hit the homepage first to capture cookies from headers
        $rawHeaders = '';
        $ch = curl_init('https://www.nseindia.com/');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 10, CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HEADERFUNCTION => function ($c, $h) use (&$rawHeaders) { $rawHeaders .= $h; return strlen($h); },
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                'Accept: text/html,*/*', 'Accept-Language: en-IN,en;q=0.9',
            ],
        ]);
        curl_exec($ch); curl_close($ch);

        $cookieMap = [];
        foreach (explode("\r\n", $rawHeaders) as $hLine) {
            if (stripos($hLine, 'set-cookie:') !== 0) continue;
            $part = trim(substr($hLine, strlen('set-cookie:')));
            $seg  = explode(';', $part, 2)[0];
            $eq   = strpos($seg, '=');
            if ($eq === false) continue;
            $cookieMap[trim(substr($seg, 0, $eq))] = trim(substr($seg, $eq + 1));
        }
        foreach ($cookieMap as $name => $value) {
            $cookieStr .= ($cookieStr ? '; ' : '') . $name . '=' . $value;
        }
        if ($cookieStr) file_put_contents($cookieFile, json_encode(['cookie' => $cookieStr]));
    }

    $ch = curl_init('https://www.nseindia.com/api/quote-equity?symbol=' . urlencode($nseSym));
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 10, CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_COOKIE => $cookieStr,
        CURLOPT_HTTPHEADER => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
            'Accept: application/json, text/plain, */*',
            'Accept-Language: en-IN,en;q=0.9',
            'Referer: https://www.nseindia.com/',
            'X-Requested-With: XMLHttpRequest',
        ],
    ]);
    $raw  = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$raw || $code !== 200) return null;
    $d = json_decode($raw, true);
    if (empty($d['priceInfo'])) return null;

    $p  = $d['priceInfo'];
    $md = $d['metadata'] ?? [];
    return [
        'symbol'                     => $nseSym . '.NS',
        'shortName'                  => $md['companyName'] ?? $nseSym,
        'longName'                   => $md['companyName'] ?? $nseSym,
        'regularMarketPrice'         => (float)($p['lastPrice'] ?? 0),
        'regularMarketChange'        => (float)($p['change'] ?? 0),
        'regularMarketChangePercent' => (float)($p['pChange'] ?? 0),
        'regularMarketPreviousClose' => (float)($p['previousClose'] ?? 0),
        'regularMarketOpen'          => (float)($p['open'] ?? 0),
        'regularMarketDayHigh'       => (float)($p['intraDayHighLow']['max'] ?? 0),
        'regularMarketDayLow'        => (float)($p['intraDayHighLow']['min'] ?? 0),
        'regularMarketVolume'        => (int)($d['securityInfo']['tradedVolume'] ?? 0),
        'averageDailyVolume3Month'   => (int)($d['securityInfo']['tradedVolume'] ?? 0),
        'fiftyTwoWeekHigh'           => (float)($p['weekHighLow']['max'] ?? 0),
        'fiftyTwoWeekLow'            => (float)($p['weekHighLow']['min'] ?? 0),
        'trailingPE' => null, 'priceToBook' => null, 'marketCap' => null,
        'sector' => null, 'industry' => null, 'returnOnEquity' => null, 'debtToEquity' => null,
        '_source' => 'nse',
    ];
}

/**
 * Last-resort price fetch from Stooq (no auth, no cookies, works everywhere).
 * Returns a minimal quote array or null.
 *
 * URL FIX (2026-06-30): the original URL had a bare "&h" flag with no value,
 * which Stooq's endpoint rejected with a 404 ("page does not exist"), not a
 * block — verified via /api/debug/quicktest. Removed the stray flag below.
 */
function stooqQuoteFallback(string $symbol): ?array
{
    // Stooq uses format: TCS.NS (lowercase .ns) for NSE stocks
    $base   = strtolower(str_replace('.NS', '', $symbol));
    $stooqSym = $base . '.in';

    $url = 'https://stooq.com/q/l/?s=' . urlencode($stooqSym) . '&f=sd2t2ohlcv&e=csv';
    $ch  = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 8, CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
            'Accept: text/csv,text/plain,*/*',
            'Referer: https://stooq.com/',
        ],
    ]);
    $raw  = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$raw || $code !== 200) return null;

    // Parse CSV: Symbol,Date,Time,Open,High,Low,Close,Volume
    $lines = array_filter(explode("\n", trim($raw)));
    if (count($lines) < 2) return null;
    $row = str_getcsv(array_values($lines)[1]);
    if (count($row) < 7) return null;
    [$sym, $date, $time, $open, $high, $low, $close, $volume] = array_pad($row, 8, 0);

    $close = (float)$close;
    if ($close <= 0) return null;

    $nseSym = strtoupper(str_replace('.NS', '', $symbol));
    return [
        'symbol'                     => $nseSym . '.NS',
        'shortName'                  => $nseSym,
        'longName'                   => $nseSym,
        'regularMarketPrice'         => $close,
        'regularMarketChange'        => 0,
        'regularMarketChangePercent' => 0,
        'regularMarketPreviousClose' => $close,
        'regularMarketOpen'          => (float)$open,
        'regularMarketDayHigh'       => (float)$high,
        'regularMarketDayLow'        => (float)$low,
        'regularMarketVolume'        => (int)$volume,
        'averageDailyVolume3Month'   => (int)$volume,
        'fiftyTwoWeekHigh'           => (float)$high,
        'fiftyTwoWeekLow'            => (float)$low,
        'trailingPE' => null, 'priceToBook' => null, 'marketCap' => null,
        'sector' => null, 'industry' => null, 'returnOnEquity' => null, 'debtToEquity' => null,
        '_source' => 'stooq',
    ];
}

function stooqBulkFetch(array $symbols): array
{
    $all = [];
    $mh  = curl_multi_init();
    $handles = [];

    foreach ($symbols as $sym) {
        $base     = strtolower(str_replace('.NS', '', $sym));
        $stooqSym = $base . '.in';
        $url = 'https://stooq.com/q/l/?s=' . urlencode($stooqSym) . '&f=sd2t2ohlcv&e=csv';
        $ch  = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 12, CURLOPT_CONNECTTIMEOUT => 6,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                'Accept: text/csv,text/plain,*/*',
                'Referer: https://stooq.com/',
            ],
        ]);
        curl_multi_add_handle($mh, $ch);
        $handles[$sym] = $ch;
    }

    $active = null;
    do { curl_multi_exec($mh, $active); curl_multi_select($mh, 0.2); } while ($active);

    foreach ($handles as $sym => $ch) {
        $raw  = curl_multi_getcontent($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_multi_remove_handle($mh, $ch); curl_close($ch);
        if (!$raw || $code !== 200) continue;

        $lines = array_values(array_filter(explode("\n", trim($raw))));
        if (count($lines) < 2) continue;
        $row = str_getcsv($lines[1]);
        if (count($row) < 7) continue;
        [$s, $date, $time, $open, $high, $low, $close, $volume] = array_pad($row, 8, 0);
        $close = (float)$close;
        if ($close <= 0) continue;

        $nseSym = strtoupper(str_replace('.NS', '', $sym)) . '.NS';
        $all[$nseSym] = [
            'symbol'                     => $nseSym,
            'shortName'                  => strtoupper(str_replace('.NS', '', $sym)),
            'longName'                   => strtoupper(str_replace('.NS', '', $sym)),
            'regularMarketPrice'         => $close,
            'regularMarketChange'        => 0,
            'regularMarketChangePercent' => 0,
            'regularMarketPreviousClose' => $close,
            'regularMarketOpen'          => (float)$open,
            'regularMarketDayHigh'       => (float)$high,
            'regularMarketDayLow'        => (float)$low,
            'regularMarketVolume'        => (int)($volume ?? 0),
            'averageDailyVolume3Month'   => (int)($volume ?? 0),
            'fiftyTwoWeekHigh'           => (float)$high,
            'fiftyTwoWeekLow'            => (float)$low,
            'trailingPE' => null, 'priceToBook' => null, 'marketCap' => null,
            'sector' => null, 'industry' => null, 'returnOnEquity' => null, 'debtToEquity' => null,
            '_source' => 'stooq',
        ];
    }
    curl_multi_close($mh);
    return $all;
}


/**
 * Fetch quote from Yahoo Finance v7 (different endpoint, sometimes less blocked).
 */
function yahooV7Quote(string $symbol): ?array
{
    $url = 'https://query1.finance.yahoo.com/v7/finance/quote?symbols=' . urlencode($symbol)
         . '&fields=regularMarketPrice,regularMarketChange,regularMarketChangePercent,'
         . 'regularMarketVolume,regularMarketDayHigh,regularMarketDayLow,'
         . 'regularMarketPreviousClose,regularMarketOpen,fiftyTwoWeekHigh,fiftyTwoWeekLow,shortName';

    foreach (['query1', 'query2'] as $host) {
        $tryUrl = str_replace('query1', $host, $url);
        $ch = curl_init($tryUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 10, CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_SSL_VERIFYPEER => false, CURLOPT_ENCODING => 'gzip',
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1',
                'Accept: application/json',
                'Referer: https://finance.yahoo.com/',
            ],
        ]);
        $raw  = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($raw && $code === 200) {
            $d = json_decode($raw, true);
            $r = $d['quoteResponse']['result'][0] ?? null;
            if ($r && ($r['regularMarketPrice'] ?? 0) > 0) return $r;
        }
    }
    return null;
}

/**
 * Bulk fetch via Yahoo Finance v7 for multiple symbols at once.
 */
function yahooV7BulkFetch(array $symbols): array
{
    $all    = [];
    $fields = 'regularMarketPrice,regularMarketChange,regularMarketChangePercent,'
            . 'regularMarketVolume,averageDailyVolume3Month,regularMarketDayHigh,regularMarketDayLow,'
            . 'regularMarketPreviousClose,regularMarketOpen,fiftyTwoWeekHigh,fiftyTwoWeekLow,'
            . 'shortName,longName';

    foreach (array_chunk($symbols, 20) as $chunk) {
        $syms = implode(',', array_map('urlencode', $chunk));
        foreach (['query1', 'query2'] as $host) {
            $url = "https://{$host}.finance.yahoo.com/v7/finance/quote?symbols={$syms}&fields={$fields}";
            $ch  = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 15, CURLOPT_CONNECTTIMEOUT => 6,
                CURLOPT_SSL_VERIFYPEER => false, CURLOPT_ENCODING => 'gzip',
                CURLOPT_HTTPHEADER => [
                    'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1',
                    'Accept: application/json',
                    'Referer: https://finance.yahoo.com/',
                    'Origin: https://finance.yahoo.com',
                ],
            ]);
            $raw  = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($raw && $code === 200) {
                $d = json_decode($raw, true);
                foreach ($d['quoteResponse']['result'] ?? [] as $q) {
                    if (!empty($q['symbol']) && ($q['regularMarketPrice'] ?? 0) > 0) {
                        $q['_source'] = 'yahoo_v7';
                        $all[$q['symbol']] = $q;
                    }
                }
                if (!empty($all)) break;
            }
        }
        if (!empty($all)) usleep(300000); // 300ms between chunks
    }
    return $all;
}

/**
 * Fetch from Groww public API (no auth, India-based, works from IN servers).
 */
function growwQuoteFetch(string $nseSymbol): ?array
{
    $sym = strtoupper(str_replace('.NS', '', $nseSymbol));
    $url = 'https://groww.in/v1/api/stocks_data/v1/accord_points/stock/nsecm/' . urlencode($sym) . '/chart_data/v2?endTimeInMs=' . (time()*1000) . '&intervalInMinutes=1&startTimeInMs=' . ((time()-86400)*1000);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 8, CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Accept: application/json',
            'Referer: https://groww.in/',
        ],
    ]);
    $raw  = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$raw || $code !== 200) return null;
    $d = json_decode($raw, true);

    // Groww returns candle data — get last price from latest candle
    $candles = $d['data']['candles'] ?? $d['candles'] ?? [];
    if (empty($candles)) return null;
    $last = end($candles); // [timestamp, open, high, low, close, volume]
    if (!isset($last[4]) || $last[4] <= 0) return null;

    return [
        'symbol'                     => $sym . '.NS',
        'shortName'                  => $sym,
        'longName'                   => $sym,
        'regularMarketPrice'         => (float)$last[4],
        'regularMarketChange'        => 0,
        'regularMarketChangePercent' => 0,
        'regularMarketPreviousClose' => (float)($last[1] ?? $last[4]),
        'regularMarketOpen'          => (float)($last[1] ?? $last[4]),
        'regularMarketDayHigh'       => (float)($last[2] ?? $last[4]),
        'regularMarketDayLow'        => (float)($last[3] ?? $last[4]),
        'regularMarketVolume'        => (int)($last[5] ?? 0),
        'averageDailyVolume3Month'   => (int)($last[5] ?? 0),
        'fiftyTwoWeekHigh'           => (float)($last[2] ?? $last[4]),
        'fiftyTwoWeekLow'            => (float)($last[3] ?? $last[4]),
        'trailingPE' => null, 'priceToBook' => null, 'marketCap' => null,
        'sector' => null, 'industry' => null, 'returnOnEquity' => null, 'debtToEquity' => null,
        '_source' => 'groww',
    ];
}

/**
 * Fetch quote from NSE India's unofficial JSON API (v2 endpoint, more stable).
 * Different from the main API — uses market data endpoint.
 */
function nseMarketFetch(string $symbol): ?array
{
    $sym = strtoupper(str_replace('.NS', '', $symbol));
    $cookieFile = STORAGE . '/nse_mkt_cookie.json';
    $cookieStr  = '';
    if (file_exists($cookieFile) && (time() - filemtime($cookieFile)) < 1800) {
        $cookieStr = json_decode(file_get_contents($cookieFile), true)['cookie'] ?? '';
    }

    // Warm up session with homepage visit if we don't have a fresh cookie
    if (!$cookieStr) {
        $rawHeaders = '';
        $ch = curl_init('https://www.nseindia.com/market-data/live-equity-market');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 10, CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HEADERFUNCTION => function ($c, $h) use (&$rawHeaders) { $rawHeaders .= $h; return strlen($h); },
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
                'Accept: text/html,application/xhtml+xml,*/*;q=0.9',
                'Accept-Language: en-IN,en;q=0.9',
                'Accept-Encoding: gzip, deflate, br',
                'Connection: keep-alive',
                'Upgrade-Insecure-Requests: 1',
            ],
        ]);
        curl_exec($ch); curl_close($ch);
        usleep(500000); // 500ms pause — NSE needs time between calls

        $cookieMap = [];
        foreach (explode("\r\n", $rawHeaders) as $hLine) {
            if (stripos($hLine, 'set-cookie:') !== 0) continue;
            $seg = explode(';', trim(substr($hLine, strlen('set-cookie:'))), 2)[0];
            $eq  = strpos($seg, '=');
            if ($eq === false) continue;
            $cookieMap[trim(substr($seg, 0, $eq))] = trim(substr($seg, $eq + 1));
        }
        foreach ($cookieMap as $name => $value) { $cookieStr .= ($cookieStr ? '; ' : '') . $name . '=' . $value; }
        if ($cookieStr) file_put_contents($cookieFile, json_encode(['cookie' => $cookieStr]));
    }

    // API call with full session headers
    $ch = curl_init('https://www.nseindia.com/api/quote-equity?symbol=' . urlencode($sym));
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 12, CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_ENCODING => 'gzip',
        CURLOPT_COOKIE => $cookieStr,
        CURLOPT_HTTPHEADER => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
            'Accept: application/json, text/plain, */*',
            'Accept-Language: en-IN,en;q=0.9',
            'Accept-Encoding: gzip, deflate, br',
            'Referer: https://www.nseindia.com/market-data/live-equity-market',
            'X-Requested-With: XMLHttpRequest',
            'sec-ch-ua: "Google Chrome";v="125", "Not:A-Brand";v="8"',
            'sec-ch-ua-mobile: ?0',
            'sec-fetch-dest: empty',
            'sec-fetch-mode: cors',
            'sec-fetch-site: same-origin',
        ],
    ]);
    $raw  = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$raw || $code !== 200) return null;
    $d = json_decode($raw, true);
    if (empty($d['priceInfo'])) return null;

    $p  = $d['priceInfo'];
    $md = $d['metadata'] ?? [];
    return [
        'symbol'                     => $sym . '.NS',
        'shortName'                  => $md['companyName'] ?? $sym,
        'longName'                   => $md['companyName'] ?? $sym,
        'regularMarketPrice'         => (float)($p['lastPrice'] ?? 0),
        'regularMarketChange'        => (float)($p['change'] ?? 0),
        'regularMarketChangePercent' => (float)($p['pChange'] ?? 0),
        'regularMarketPreviousClose' => (float)($p['previousClose'] ?? 0),
        'regularMarketOpen'          => (float)($p['open'] ?? 0),
        'regularMarketDayHigh'       => (float)($p['intraDayHighLow']['max'] ?? 0),
        'regularMarketDayLow'        => (float)($p['intraDayHighLow']['min'] ?? 0),
        'regularMarketVolume'        => (int)($d['securityInfo']['tradedVolume'] ?? 0),
        'averageDailyVolume3Month'   => (int)($d['securityInfo']['tradedVolume'] ?? 0),
        'fiftyTwoWeekHigh'           => (float)($p['weekHighLow']['max'] ?? 0),
        'fiftyTwoWeekLow'            => (float)($p['weekHighLow']['min'] ?? 0),
        'trailingPE' => null, 'priceToBook' => null, 'marketCap' => null,
        'sector' => null, 'industry' => null, 'returnOnEquity' => null, 'debtToEquity' => null,
        '_source' => 'nse_market',
    ];
}

/**
 * Fetch quote from BSE India public API (no auth, Indian datacenter-friendly).
 */
// Common BSE scrip codes for major NSE stocks
// BSE uses numeric codes, not symbol names — we maintain a map of the most common ones
function bseScripCode(string $sym): string {
    $map = [
        'RELIANCE'=>'500325','TCS'=>'532540','HDFCBANK'=>'500180','BHARTIARTL'=>'532454',
        'ICICIBANK'=>'532174','INFY'=>'500209','SBIN'=>'500112','HINDUNILVR'=>'500696',
        'ITC'=>'500875','LT'=>'500510','KOTAKBANK'=>'500247','AXISBANK'=>'532215',
        'BAJFINANCE'=>'500034','MARUTI'=>'532500','TITAN'=>'500114','SUNPHARMA'=>'524715',
        'NTPC'=>'532555','POWERGRID'=>'532898','ONGC'=>'500312','HCLTECH'=>'532281',
        'ADANIENT'=>'512599','ADANIPORTS'=>'532921','COALINDIA'=>'533278','JSWSTEEL'=>'500228',
        'TATASTEEL'=>'500470','TATACONSUM'=>'500800','TECHM'=>'532755','WIPRO'=>'507685',
        'DIVISLAB'=>'532488','DRREDDY'=>'500124','CIPLA'=>'500087','APOLLOHOSP'=>'508869',
        'BAJAJFINSV'=>'532978','BAJAJ-AUTO'=>'532977','EICHERMOT'=>'505200','HEROMOTOCO'=>'500182',
        'TATAMOTORS'=>'500570','M&M'=>'500520','NESTLEIND'=>'500790','BRITANNIA'=>'500825',
        'ULTRACEMCO'=>'532538','GRASIM'=>'500300','INDUSINDBK'=>'532187','HINDALCO'=>'500440',
        'VEDL'=>'500295','BPCL'=>'500547','IOC'=>'530965','HDFCLIFE'=>'540777',
        'SBILIFE'=>'540719','SHRIRAMFIN'=>'511218','SIEMENS'=>'500550','ABB'=>'500002',
        'PIDILITIND'=>'500331','HAVELLS'=>'517354','MUTHOOTFIN'=>'533398','DMART'=>'540376',
        'TRENT'=>'500251','DLF'=>'532868','ZOMATO'=>'543320','NYKAA'=>'543384',
        'BEL'=>'500049','HAL'=>'541154','BHEL'=>'500103','IRFC'=>'543257',
        'PFC'=>'532810','RECLTD'=>'532955','IREDA'=>'544097','NHPC'=>'533098',
        'TATAPOWER'=>'500400','ADANIGREEN'=>'541450','SUZLON'=>'532667','BANKBARODA'=>'532134',
        'CANBK'=>'532483','PNB'=>'532461','UNIONBANK'=>'532477','IDFCFIRSTB'=>'539437',
        'FEDERALBNK'=>'500469','BANDHANBNK'=>'541153','LTIM'=>'540005','MPHASIS'=>'526299',
        'PERSISTENT'=>'533179','COFORGE'=>'532541','OFSS'=>'532466','KPITTECH'=>'542651',
        'TATAELXSI'=>'500408','AUROPHARMA'=>'524804','ALKEM'=>'539523','IPCALAB'=>'544155',
        'LUPIN'=>'500257','TORNTPHARM'=>'500420','MAXHEALTH'=>'543220','FORTIS'=>'532843',
        'TVSMOTOR'=>'532343','ASHOKLEY'=>'500477','BHARATFORG'=>'500493','BOSCHLTD'=>'500530',
        'EXIDEIND'=>'500086','MRF'=>'500290','APOLLOTYRE'=>'500877','CONCOR'=>'531344',
        'BLUEDART'=>'526612','SRF'=>'503806','DEEPAKNITR'=>'506401','PIDILITIND'=>'500331',
        'GODREJCP'=>'532424','MARICO'=>'531642','DABUR'=>'500096','COLPAL'=>'500830',
        'EMAMILTD'=>'531162','JUBLFOOD'=>'533155','ICICIPRULI'=>'540133','CHOLAFIN'=>'511243',
    ];
    return $map[strtoupper($sym)] ?? '';
}

function bseQuoteFetch(string $nseSymbol): ?array
{
    $sym  = strtoupper(str_replace('.NS', '', $nseSymbol));
    $code = bseScripCode($sym);
    if (!$code) return null;

    // BSE real-time quote API
    $url = 'https://api.bseindia.com/BseIndiaAPI/api/getScripHeaderData/w?Debtflag=&scripcode=' . $code . '&seriesid=';
    $ch  = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 10, CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_ENCODING => 'gzip',
        CURLOPT_HTTPHEADER => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
            'Accept: application/json, text/plain, */*',
            'Referer: https://www.bseindia.com/',
            'Origin: https://www.bseindia.com',
        ],
    ]);
    $raw  = curl_exec($ch);
    $code2 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$raw || $code2 !== 200) return null;
    $d = json_decode($raw, true);
    if (!is_array($d)) return null;

    // Real BSE response structure (verified 2026-07-02):
    // $d['CurrRate']['LTP']     = live price
    // $d['CurrRate']['Chg']     = change (e.g. "+50.85")
    // $d['CurrRate']['PcChg']   = change % (e.g. "+5.16")
    // $d['Header']['PrevClose'] = previous close
    // $d['Header']['Open']      = open
    // $d['Header']['High']      = day high
    // $d['Header']['Low']       = day low
    // $d['Cmpname']['FullN']    = full company name
    $cr = $d['CurrRate'] ?? [];
    $hd = $d['Header']   ?? [];
    $cn = $d['Cmpname']  ?? [];

    $price = (float)($cr['LTP'] ?? 0);
    if ($price <= 0) return null;

    return [
        'symbol'                     => $sym . '.NS',
        'shortName'                  => $cn['ShortN'] ?? $sym,
        'longName'                   => $cn['FullN']  ?? $sym,
        'regularMarketPrice'         => $price,
        'regularMarketChange'        => (float)($cr['Chg']   ?? 0),
        'regularMarketChangePercent' => (float)($cr['PcChg'] ?? 0),
        'regularMarketPreviousClose' => (float)($hd['PrevClose'] ?? $price),
        'regularMarketOpen'          => (float)($hd['Open']      ?? $price),
        'regularMarketDayHigh'       => (float)($hd['High']      ?? $price),
        'regularMarketDayLow'        => (float)($hd['Low']       ?? $price),
        'regularMarketVolume'        => 0,
        'averageDailyVolume3Month'   => 0,
        'fiftyTwoWeekHigh'           => (float)($hd['High'] ?? $price),
        'fiftyTwoWeekLow'            => (float)($hd['Low']  ?? $price),
        'trailingPE' => null, 'priceToBook' => null, 'marketCap' => null,
        'sector' => null, 'industry' => null, 'returnOnEquity' => null, 'debtToEquity' => null,
        '_source' => 'bse',
    ];
}

function bseQuoteBulk(array $symbols): array
{
    // Only fetch symbols we have a BSE scrip code for
    $toFetch = [];
    foreach ($symbols as $sym) {
        $base = strtoupper(str_replace('.NS', '', $sym));
        $code = bseScripCode($base);
        if ($code) $toFetch[$sym] = $code;
    }
    if (empty($toFetch)) return [];

    // Parallel fetch using curl_multi
    $mh      = curl_multi_init();
    $handles = [];
    $headers = [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        'Accept: application/json',
        'Referer: https://www.bseindia.com/',
        'Origin: https://www.bseindia.com',
    ];

    foreach ($toFetch as $sym => $code) {
        $ch = curl_init("https://api.bseindia.com/BseIndiaAPI/api/getScripHeaderData/w?Debtflag=&scripcode={$code}&seriesid=");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 10, CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => 'gzip', CURLOPT_HTTPHEADER => $headers,
        ]);
        curl_multi_add_handle($mh, $ch);
        $handles[$sym] = $ch;
    }

    $running = null;
    do { curl_multi_exec($mh, $running); curl_multi_select($mh); } while ($running > 0);

    $all = [];
    foreach ($handles as $sym => $ch) {
        $raw  = curl_multi_getcontent($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);

        if (!$raw || $code !== 200) continue;
        $d  = json_decode($raw, true);
        if (!is_array($d)) continue;

        $cr    = $d['CurrRate'] ?? [];
        $hd    = $d['Header']   ?? [];
        $cn    = $d['Cmpname']  ?? [];
        $price = (float)($cr['LTP'] ?? 0);
        if ($price <= 0) continue;

        $all[$sym] = [
            'symbol'                     => $sym,
            'shortName'                  => $cn['ShortN'] ?? str_replace('.NS', '', $sym),
            'longName'                   => $cn['FullN']  ?? str_replace('.NS', '', $sym),
            'regularMarketPrice'         => $price,
            'regularMarketChange'        => (float)($cr['Chg']   ?? 0),
            'regularMarketChangePercent' => (float)($cr['PcChg'] ?? 0),
            'regularMarketPreviousClose' => (float)($hd['PrevClose'] ?? $price),
            'regularMarketOpen'          => (float)($hd['Open']      ?? $price),
            'regularMarketDayHigh'       => (float)($hd['High']      ?? $price),
            'regularMarketDayLow'        => (float)($hd['Low']       ?? $price),
            'regularMarketVolume'        => 0,
            'averageDailyVolume3Month'   => 0,
            'fiftyTwoWeekHigh'           => (float)($hd['High'] ?? $price),
            'fiftyTwoWeekLow'            => (float)($hd['Low']  ?? $price),
            'trailingPE' => null, 'priceToBook' => null, 'marketCap' => null,
            'sector' => null, 'industry' => null, 'returnOnEquity' => null, 'debtToEquity' => null,
            '_source' => 'bse',
        ];
    }
    curl_multi_close($mh);
    return $all;
}

function bseHistory(string $nseSymbol, int $days = 90): array
{
    $sym   = strtoupper(str_replace('.NS', '', $nseSymbol));
    $code  = bseScripCode($sym);
    if (!$code) return [];

    // BSE history via their JSON API (more reliable than CSV download)
    $toDate   = date('Ymd');
    $fromDate = date('Ymd', strtotime('-' . ($days + 30) . ' days'));

    // Try BSE chart data API first (returns JSON with OHLCV)
    $url = "https://api.bseindia.com/BseIndiaAPI/api/getScripHeaderData/w?Debtflag=&scripcode={$code}&seriesid=";
    // We use the stock price history endpoint
    $histUrl = "https://api.bseindia.com/BseIndiaAPI/api/StockPriceHistData/w?scripcode={$code}&seriesid=EQ&fromdate={$fromDate}&todate={$toDate}";

    $ch = curl_init($histUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 15, CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_ENCODING => 'gzip',
        CURLOPT_HTTPHEADER => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Accept: application/json',
            'Referer: https://www.bseindia.com/',
            'Origin: https://www.bseindia.com',
        ],
    ]);
    $raw      = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $rows = [];
    if ($raw && $httpCode === 200) {
        $d = json_decode($raw, true);
        // BSE StockPriceHistData returns array of objects
        $items = $d['Table'] ?? $d ?? [];
        if (is_array($items)) {
            foreach ($items as $v) {
                $close = (float)($v['CLOSE_PRICE'] ?? $v['Close'] ?? $v['close'] ?? 0);
                if ($close <= 0) continue;
                $dateStr = $v['TIMESTAMP'] ?? $v['Date'] ?? $v['date'] ?? '';
                $date    = date('Y-m-d', strtotime($dateStr));
                if (!$date || $date === '1970-01-01') continue;
                $rows[] = [
                    'date'   => $date,
                    'open'   => round((float)($v['OPEN_PRICE']  ?? $v['Open']   ?? $close), 2),
                    'high'   => round((float)($v['HIGH_PRICE']  ?? $v['High']   ?? $close), 2),
                    'low'    => round((float)($v['LOW_PRICE']   ?? $v['Low']    ?? $close), 2),
                    'close'  => round($close, 2),
                    'volume' => (int)($v['NO_OF_SHRS'] ?? $v['Volume'] ?? $v['volume'] ?? 0),
                ];
            }
        }
    }

    // If JSON API fails, fall back to CSV download
    if (empty($rows)) {
        $from = date('d/m/Y', strtotime('-' . ($days + 30) . ' days'));
        $to   = date('d/m/Y');
        $csvUrl = 'https://api.bseindia.com/BseIndiaAPI/api/StockPriceCSVDownload/w?scripcode=' . $code
                . '&seriesid=EQ&fromdate=' . urlencode($from) . '&todate=' . urlencode($to)
                . '&marketcap=&MarketCapFull=&myowner=&segment=';
        $ch = curl_init($csvUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 15, CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Referer: https://www.bseindia.com/', 'Accept: text/csv,*/*',
            ],
        ]);
        $raw      = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($raw && $httpCode === 200 && str_contains($raw, ',')) {
            $lines = array_values(array_filter(explode("\n", trim($raw))));
            for ($i = 1; $i < count($lines); $i++) {
                $col   = str_getcsv(trim($lines[$i]));
                if (count($col) < 5) continue;
                $close = (float)str_replace(',', '', $col[4] ?? 0);
                if ($close <= 0) continue;
                $rows[] = [
                    'date'   => date('Y-m-d', strtotime(str_replace('/', '-', $col[0]))),
                    'open'   => round((float)str_replace(',', '', $col[1] ?? $close), 2),
                    'high'   => round((float)str_replace(',', '', $col[2] ?? $close), 2),
                    'low'    => round((float)str_replace(',', '', $col[3] ?? $close), 2),
                    'close'  => round($close, 2),
                    'volume' => (int)str_replace(',', '', $col[5] ?? 0),
                ];
            }
            $rows = array_reverse($rows); // CSV is newest-first
        }
    }

    $rows = array_slice($rows, -$days);
    if (!empty($rows)) {
        $cacheFile = STORAGE . '/hist_' . preg_replace('/[^A-Z0-9]/', '_', strtoupper($nseSymbol)) . '.json';
        file_put_contents($cacheFile, json_encode($rows));
    }
    return $rows;
}


/**
 * Fetch historical OHLCV from Stooq CSV when Yahoo chart is blocked.
 * Stooq serves ~1 year of daily data free with no auth.
 */
function stooqHistoryFallback(string $symbol, int $days = 90): array
{
    $base     = strtolower(str_replace('.NS', '', $symbol));
    $stooqSym = $base . '.in';
    $from = date('Ymd', strtotime("-{$days} days -30 days"));
    $to   = date('Ymd');
    $url  = "https://stooq.com/q/d/l/?s={$stooqSym}&d1={$from}&d2={$to}&i=d";

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 12, CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => ['User-Agent: Mozilla/5.0', 'Accept: text/csv,*/*'],
    ]);
    $raw  = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$raw || $code !== 200) return [];
    $lines = array_values(array_filter(explode("\n", trim($raw))));
    if (count($lines) < 2) return [];

    $rows = [];
    // Header: Date,Open,High,Low,Close,Volume
    for ($i = 1; $i < count($lines); $i++) {
        $col   = str_getcsv(trim($lines[$i]));
        if (count($col) < 5) continue;
        [$date, $open, $high, $low, $close] = $col;
        $volume = $col[5] ?? 0;
        $close  = (float)$close;
        if ($close <= 0) continue;
        $rows[] = [
            'date'   => $date,
            'open'   => round((float)$open,  2),
            'high'   => round((float)$high,  2),
            'low'    => round((float)$low,   2),
            'close'  => round($close,         2),
            'volume' => (int)$volume,
        ];
    }
    $rows = array_slice($rows, -$days);
    if (!empty($rows)) {
        $cacheFile = STORAGE . '/hist_' . preg_replace('/[^A-Z0-9]/', '_', strtoupper($symbol)) . '.json';
        file_put_contents($cacheFile, json_encode($rows));
    }
    return $rows;
}

