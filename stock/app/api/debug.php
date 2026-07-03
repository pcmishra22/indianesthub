<?php
declare(strict_types=1);
/**
 * api/debug.php — diagnostic endpoints used throughout the week-long
 * Yahoo/NSE/Stooq investigation. Kept intact since they're genuinely useful
 * for diagnosing future data-source issues (e.g. if Twelve Data's free tier
 * runs out and legacy fallbacks need re-checking).
 *
 * Findings baked into the diagnosis text below (verified 2026-06-30):
 * - Yahoo crumb endpoint is dead (decommissioned backend, "Unknown Host").
 * - Yahoo v7/v8 quote endpoints return 401 even with valid cookies — Yahoo
 *   has deliberately closed third-party access.
 * - NSE India returns a clean WAF 403 from this server's hosting IP range.
 * - Stooq's quote URL had a malformed bare "&h" flag, fixed throughout.
 */

if ($uri === '/api/debug/quicktest') {
    header('Content-Type: application/json');
    $out = [
        'ts'          => date('Y-m-d H:i:s'),
        'php_version' => PHP_VERSION,
        'server_ip'   => gethostbyname(gethostname()),
        'checkpoints' => [],
    ];

    // ══════════════════════════════════════════════════════
    // CHECKPOINT 1: Stooq single symbol (raw HTTP + CSV parse)
    // ══════════════════════════════════════════════════════
    $t0 = microtime(true);
    $stooqUrl = 'https://stooq.com/q/l/?s=reliance.in&f=sd2t2ohlcv&e=csv'; // .in is correct Stooq suffix for NSE India
    $ch = curl_init($stooqUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER => ['User-Agent: Mozilla/5.0'],
    ]);
    $raw  = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);
    $lines = $raw ? array_values(array_filter(explode("\n", trim($raw)))) : [];
    $cp1_price = null;
    if (count($lines) >= 2) {
        $row = str_getcsv($lines[1]);
        $cp1_price = isset($row[6]) ? (float)$row[6] : null;
    }
    $out['checkpoints']['CP1_stooq_single'] = [
        'label'       => 'Stooq single symbol HTTP fetch (reliance.in)',
        'ok'          => $code === 200 && $cp1_price > 0,
        'http_code'   => $code,
        'curl_error'  => $err ?: null,
        'csv_lines'   => count($lines),
        'header_row'  => $lines[0] ?? null,
        'data_row'    => $lines[1] ?? null,
        'parsed_price'=> $cp1_price,
        'ms'          => round((microtime(true) - $t0) * 1000),
        'diagnosis'   => $code === 200 && $cp1_price > 0 ? 'PASS'
            : ($code === 0   ? 'FAIL: No connection — Stooq unreachable from server (DNS/firewall block?)'
            : ($code === 200 ? 'FAIL: Connected but price=0 — CSV parse issue or N/D response'
            : "FAIL: HTTP {$code} — Stooq returned error")),
    ];

    // ══════════════════════════════════════════════════════
    // CHECKPOINT 2: stooqBulkFetch() function with 3 symbols
    // ══════════════════════════════════════════════════════
    $t0 = microtime(true);
    $bulkResult = stooqBulkFetch(['RELIANCE.NS', 'TCS.NS', 'INFY.NS']);
    $out['checkpoints']['CP2_stooq_bulk_function'] = [
        'label'        => 'stooqBulkFetch() with RELIANCE, TCS, INFY',
        'ok'           => count($bulkResult) > 0,
        'symbols_sent' => ['RELIANCE.NS', 'TCS.NS', 'INFY.NS'],
        'symbols_got'  => array_keys($bulkResult),
        'count'        => count($bulkResult),
        'sample_price' => $bulkResult['RELIANCE.NS']['regularMarketPrice'] ?? null,
        'ms'           => round((microtime(true) - $t0) * 1000),
        'diagnosis'    => count($bulkResult) === 3 ? 'PASS: All 3 symbols fetched (using .in suffix)'
            : (count($bulkResult) > 0 ? 'PARTIAL: Only ' . count($bulkResult) . '/3 fetched — some symbols may be wrong format'
            : 'FAIL: 0 symbols returned — stooqBulkFetch returning empty (CP1 likely also failed)'),
    ];

    // ══════════════════════════════════════════════════════
    // CHECKPOINT 2a: Stooq .in format direct test
    // ══════════════════════════════════════════════════════
    $t0 = microtime(true);
    $stooqInUrl = 'https://stooq.com/q/l/?s=reliance.in&f=sd2t2ohlcv&e=csv';
    $ch2a = curl_init($stooqInUrl);
    curl_setopt_array($ch2a, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
            'Accept: text/csv,text/plain,*/*', 'Referer: https://stooq.com/',
        ],
    ]);
    $raw2a  = curl_exec($ch2a);
    $code2a = curl_getinfo($ch2a, CURLINFO_HTTP_CODE);
    curl_close($ch2a);
    $lines2a = $raw2a ? array_values(array_filter(explode("\n", trim($raw2a)))) : [];
    $price2a = null;
    if (count($lines2a) >= 2) { $r = str_getcsv($lines2a[1]); $price2a = isset($r[6]) ? (float)$r[6] : null; }
    $out['checkpoints']['CP2a_stooq_in_format'] = [
        'label'        => 'Stooq .in format (reliance.in — correct NSE suffix)',
        'ok'           => $code2a === 200 && $price2a > 0,
        'http_code'    => $code2a,
        'parsed_price' => $price2a,
        'ms'           => round((microtime(true) - $t0) * 1000),
        'diagnosis'    => $code2a === 200 && $price2a > 0 ? 'PASS: Stooq .in works!' :
            ($code2a === 200 ? 'FAIL: Connected but no price — N/D response' :
            "FAIL: HTTP {$code2a}"),
    ];

    // ══════════════════════════════════════════════════════
    // CHECKPOINT 2b: Yahoo Finance v7 (mobile UA fallback)
    // ══════════════════════════════════════════════════════
    $t0 = microtime(true);
    $yv7 = yahooV7BulkFetch(['RELIANCE.NS', 'TCS.NS']);
    $out['checkpoints']['CP2b_yahoo_v7'] = [
        'label'      => 'Yahoo Finance v7 bulk (mobile User-Agent)',
        'ok'         => count($yv7) > 0,
        'count'      => count($yv7),
        'symbols'    => array_keys($yv7),
        'sample_price' => $yv7['RELIANCE.NS']['regularMarketPrice'] ?? null,
        'ms'         => round((microtime(true) - $t0) * 1000),
        'diagnosis'  => count($yv7) > 0 ? 'PASS: Yahoo v7 works — will be used as fallback'
            : 'FAIL: Yahoo v7 also blocked on this server',
    ];

    // ══════════════════════════════════════════════════════
    // CHECKPOINT 3: NSE India API connectivity
    // ══════════════════════════════════════════════════════
    $t0 = microtime(true);
    // Step 3a: get NSE cookie first (parse Set-Cookie headers directly — jar file proven unreliable)
    $rawHeaders3 = '';
    $ch3 = curl_init('https://www.nseindia.com/');
    curl_setopt_array($ch3, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 8,
        CURLOPT_SSL_VERIFYPEER => false, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HEADERFUNCTION => function ($c, $h) use (&$rawHeaders3) { $rawHeaders3 .= $h; return strlen($h); },
        CURLOPT_HTTPHEADER => ['User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'],
    ]);
    curl_exec($ch3);
    $codeHome = curl_getinfo($ch3, CURLINFO_HTTP_CODE);
    curl_close($ch3);

    $nseCookieMap = [];
    foreach (explode("\r\n", $rawHeaders3) as $hLine) {
        if (stripos($hLine, 'set-cookie:') !== 0) continue;
        $seg = explode(';', trim(substr($hLine, strlen('set-cookie:'))), 2)[0];
        $eq  = strpos($seg, '=');
        if ($eq === false) continue;
        $nseCookieMap[trim(substr($seg, 0, $eq))] = trim(substr($seg, $eq + 1));
    }
    $nseCookieStr = '';
    foreach ($nseCookieMap as $name => $value) { $nseCookieStr .= ($nseCookieStr ? '; ' : '') . $name . '=' . $value; }

    // Step 3b: actual API call
    $ch3b = curl_init('https://www.nseindia.com/api/quote-equity?symbol=RELIANCE');
    curl_setopt_array($ch3b, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_COOKIE => $nseCookieStr,
        CURLOPT_HTTPHEADER => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Accept: application/json', 'Referer: https://www.nseindia.com/',
            'X-Requested-With: XMLHttpRequest',
        ],
    ]);
    $raw3  = curl_exec($ch3b);
    $code3 = curl_getinfo($ch3b, CURLINFO_HTTP_CODE);
    $err3  = curl_error($ch3b);
    curl_close($ch3b);
    $d3    = $raw3 ? json_decode($raw3, true) : null;
    $nsePrice = $d3['priceInfo']['lastPrice'] ?? null;
    $out['checkpoints']['CP3_nse_india'] = [
        'label'            => 'NSE India API (nseindia.com)',
        'ok'               => $nsePrice > 0,
        'homepage_http'    => $codeHome,
        'cookies_captured' => count($nseCookieMap),
        'api_http'         => $code3,
        'curl_error'       => $err3 ?: null,
        'reliance_price'   => $nsePrice,
        'raw_snippet'      => $raw3 ? substr($raw3, 0, 200) : null,
        'ms'               => round((microtime(true) - $t0) * 1000),
        'diagnosis'        => $nsePrice > 0 ? 'PASS'
            : ($code3 === 401 || $code3 === 403 ? 'FAIL: NSE blocking server IP (403/401) — cookie/session rejected'
            : ($code3 === 0 ? 'FAIL: Cannot reach nseindia.com — DNS or firewall block'
            : "FAIL: HTTP {$code3} — " . (substr($raw3 ?? '', 0, 100) ?: 'empty response'))),
    ];

    // ══════════════════════════════════════════════════════
    // CHECKPOINT 4: /api/quotes/bulk endpoint self-test
    // (simulates what JS calls — does it return quotes array?)
    // ══════════════════════════════════════════════════════
    $t0 = microtime(true);
    $testSyms = ['RELIANCE.NS', 'TCS.NS', 'HDFCBANK.NS'];
    // Re-run exactly what the endpoint does (Stooq → Yahoo v7 → Yahoo v8 → NSE)
    $ep4_quotes = stooqBulkFetch($testSyms);
    if (empty($ep4_quotes)) {
        $ep4_quotes = yahooV7BulkFetch($testSyms);
    }
    if (empty($ep4_quotes)) {
        foreach (array_slice($testSyms, 0, 3) as $s4) {
            $nq = nseQuoteFallback($s4);
            if ($nq && ($nq['regularMarketPrice'] ?? 0) > 0) $ep4_quotes[$s4] = $nq;
        }
    }
    $ep4_arr = array_values($ep4_quotes);
    $out['checkpoints']['CP4_bulk_endpoint_simulation'] = [
        'label'          => '/api/quotes/bulk simulation (what JS actually receives)',
        'ok'             => count($ep4_arr) > 0,
        'quotes_count'   => count($ep4_arr),
        'source_used'    => !empty($ep4_quotes) ? ($ep4_arr[0]['_source'] ?? 'unknown') : 'none',
        'sample'         => !empty($ep4_arr) ? [
            'symbol' => $ep4_arr[0]['symbol'] ?? null,
            'price'  => $ep4_arr[0]['regularMarketPrice'] ?? null,
            'source' => $ep4_arr[0]['_source'] ?? null,
        ] : null,
        'json_response_preview' => json_encode([
            'ok'    => count($ep4_arr) > 0,
            'count' => count($ep4_arr),
            'quotes'=> array_slice($ep4_arr, 0, 1),
        ]),
        'ms'             => round((microtime(true) - $t0) * 1000),
        'diagnosis'      => count($ep4_arr) > 0
            ? 'PASS: JS will receive ' . count($ep4_arr) . ' quotes — watchlist should load'
            : 'FAIL: JS receives empty quotes array — this triggers the error message on screen',
    ];

    // ══════════════════════════════════════════════════════
    // CHECKPOINT 5: Storage directory + cache file status
    // ══════════════════════════════════════════════════════
    $cacheFile   = STORAGE . '/bulk_quotes.json';
    $storageOk   = is_dir(STORAGE) && is_writable(STORAGE);
    $cacheExists = file_exists($cacheFile);
    $cacheData   = $cacheExists ? json_decode(file_get_contents($cacheFile), true) : null;
    $cacheCount  = is_array($cacheData) ? count($cacheData) : 0;
    $cacheAge    = $cacheExists ? (time() - filemtime($cacheFile)) : null;
    $out['checkpoints']['CP5_storage_and_cache'] = [
        'label'             => 'Storage directory + bulk_quotes.json cache',
        'ok'                => $storageOk && $cacheCount > 0,
        'storage_path'      => STORAGE,
        'storage_writable'  => $storageOk,
        'cache_exists'      => $cacheExists,
        'cache_age_sec'     => $cacheAge,
        'cache_age_human'   => $cacheAge !== null ? ($cacheAge < 60 ? "{$cacheAge}s ago" : round($cacheAge/60) . 'min ago') : 'N/A',
        'cache_valid'       => $cacheAge !== null && $cacheAge < 300,
        'cached_symbols'    => $cacheCount,
        'sample_keys'       => $cacheData ? array_slice(array_keys($cacheData), 0, 5) : [],
        'diagnosis'         => !$storageOk ? 'FAIL: Storage dir not writable — cache cannot be saved'
            : (!$cacheExists ? 'WARN: No cache file yet — first load will be slow'
            : ($cacheAge > 300 ? "WARN: Cache is stale ({$cacheAge}s old, >5min) — will re-fetch"
            : "PASS: Cache has {$cacheCount} symbols, " . round($cacheAge) . "s old")),
    ];

    // ── Summary ──────────────────────────────────────────
    $passes = array_filter($out['checkpoints'], fn($c) => $c['ok']);
    $out['summary'] = [
        'passed'     => count($passes) . '/' . count($out['checkpoints']),
        'overall_ok' => count($passes) >= 4,
        'root_cause' => !$out['checkpoints']['CP1_stooq_single']['ok']
            ? 'SERVER CANNOT REACH STOOQ — outbound HTTP blocked on this host'
            : (!$out['checkpoints']['CP2_stooq_bulk_function']['ok']
            ? 'stooqBulkFetch() broken — check symbol format sent'
            : (!$out['checkpoints']['CP4_bulk_endpoint_simulation']['ok']
            ? 'Both Stooq and NSE failed — all data sources blocked on server'
            : 'No critical issue — check CP5 cache for staleness')),
        'next_action' => count($passes) >= 4
            ? 'All good — watchlist should work. Hard refresh browser (Ctrl+Shift+R).'
            : (!$out['checkpoints']['CP1_stooq_single']['ok']
            ? 'Fix: Contact hosting provider to unblock outbound HTTPS to stooq.com'
            : 'Fix: Share this JSON output for further diagnosis'),
    ];

    echo json_encode($out, JSON_PRETTY_PRINT);
    exit;
}


if ($uri === '/api/debug/datasource') {
    header('Content-Type: application/json');
    $testSym = 'TCS.NS';
    $out = ['symbol' => $testSym, 'tests' => []];

    // Test NSE
    $t0 = microtime(true);
    $nse = nseQuoteFallback($testSym);
    $out['tests']['nse_india'] = [
        'ok'    => !empty($nse) && ($nse['regularMarketPrice'] ?? 0) > 0,
        'price' => $nse['regularMarketPrice'] ?? null,
        'ms'    => round((microtime(true) - $t0) * 1000),
    ];

    // Test Stooq
    $t0 = microtime(true);
    $stooq = stooqQuoteFallback($testSym);
    $out['tests']['stooq'] = [
        'ok'    => !empty($stooq) && ($stooq['regularMarketPrice'] ?? 0) > 0,
        'price' => $stooq['regularMarketPrice'] ?? null,
        'ms'    => round((microtime(true) - $t0) * 1000),
    ];

    // Test Yahoo
    $t0 = microtime(true);
    $fields = 'regularMarketPrice';
    $url = 'https://query2.finance.yahoo.com/v8/finance/quote?symbols=' . urlencode($testSym) . '&fields=' . $fields . '&lang=en-US&region=IN';
    $raw = httpGet($url, 8);
    $data = $raw ? json_decode($raw, true) : null;
    $yPrice = $data['quoteResponse']['result'][0]['regularMarketPrice'] ?? null;
    $out['tests']['yahoo_finance'] = [
        'ok'    => $yPrice > 0,
        'price' => $yPrice,
        'ms'    => round((microtime(true) - $t0) * 1000),
    ];

    // Test Stooq history
    $t0 = microtime(true);
    $hist = stooqHistoryFallback($testSym, 30);
    $out['tests']['stooq_history'] = [
        'ok'    => count($hist) >= 20,
        'bars'  => count($hist),
        'ms'    => round((microtime(true) - $t0) * 1000),
    ];

    $out['recommended_source'] = $out['tests']['nse_india']['ok'] ? 'NSE India'
        : ($out['tests']['stooq']['ok'] ? 'Stooq' : ($out['tests']['yahoo_finance']['ok'] ? 'Yahoo Finance' : 'NONE — all sources blocked'));
    echo json_encode($out, JSON_PRETTY_PRINT);
    exit;
}


if ($uri === '/api/debug/yahoo') {
    header('Content-Type: application/json');
    $out = [];

    // Test 1: plain connectivity (no crumb, no cookie)
    $ch = curl_init('https://query1.finance.yahoo.com/v8/finance/quote?symbols=TCS.NS&fields=regularMarketPrice&lang=en-US&region=IN');
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER=>true, CURLOPT_TIMEOUT=>10, CURLOPT_SSL_VERIFYPEER=>false,
        CURLOPT_HTTPHEADER=>['User-Agent: Mozilla/5.0','Accept: application/json']]);
    $r = curl_exec($ch); $code = curl_getinfo($ch, CURLINFO_HTTP_CODE); $err = curl_error($ch); $errno = curl_errno($ch); curl_close($ch);
    $out['plain_fetch'] = [
        'http_code'=>$code, 'curl_errno'=>$errno, 'curl_error'=>$err ?: null,
        'body_preview'=>substr((string)($r ?: ''), 0, 300),
        'diagnosis' => $code === 200 ? 'PASS' : ($errno !== 0 ? "FAIL: curl error {$errno} — {$err}" : "FAIL: HTTP {$code}"),
    ];

    // Test 2: crumb fetch (needed for authenticated v8 calls) — force fresh, bypass cache
    $crumb = yahooGetCrumb(true);
    $out['crumb'] = [
        'crumb'=>$crumb['crumb']??'', 'cookie_len'=>strlen($crumb['cookie']??''), 'ts'=>$crumb['ts']??0,
        'steps' => $crumb['debug'] ?? null,
        'diagnosis' => !empty($crumb['crumb']) ? 'PASS: got crumb' : 'FAIL: see steps.step1_homepage / steps.step2_crumb for exact cause',
    ];

    // Test 3: authenticated fetch via httpGetDebug (shows every attempt, no swallowing)
    $debugResult = httpGetDebug('https://query1.finance.yahoo.com/v8/finance/quote?symbols=TCS.NS&fields=regularMarketPrice&lang=en-US&region=IN', 15);
    $data = $debugResult['body'] ? json_decode($debugResult['body'], true) : null;
    $out['auth_fetch'] = [
        'got_data'    => !empty($data['quoteResponse']['result']),
        'price'       => $data['quoteResponse']['result'][0]['regularMarketPrice'] ?? null,
        'final_code'  => $debugResult['code'],
        'attempts'    => $debugResult['attempts'],
        'diagnosis'   => !empty($data['quoteResponse']['result']) ? 'PASS'
            : 'FAIL: see attempts[] above for exact HTTP code + curl error per host tried',
    ];

    // Test 4: v8 quote with cookie only, NO crumb param at all — isolates whether crumb is actually required
    $crumbForCookie = yahooGetCrumb();
    $cookieOnly = $crumbForCookie['cookie'] ?? '';
    $rawHeadersT4 = '';
    $ch4 = curl_init('https://query1.finance.yahoo.com/v8/finance/quote?symbols=TCS.NS&fields=regularMarketPrice&lang=en-US&region=IN');
    curl_setopt_array($ch4, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 12, CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_COOKIE => $cookieOnly,
        CURLOPT_HEADERFUNCTION => function($c,$h) use (&$rawHeadersT4) { $rawHeadersT4 .= $h; return strlen($h); },
        CURLOPT_HTTPHEADER => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
            'Accept: application/json', 'Referer: https://finance.yahoo.com/',
        ],
    ]);
    $rawT4 = curl_exec($ch4);
    $codeT4 = curl_getinfo($ch4, CURLINFO_HTTP_CODE);
    curl_close($ch4);
    $dataT4 = $rawT4 ? json_decode($rawT4, true) : null;
    $out['test4_v8_cookie_no_crumb'] = [
        'http_code' => $codeT4,
        'cookie_sent_len' => strlen($cookieOnly),
        'got_data'  => !empty($dataT4['quoteResponse']['result']),
        'price'     => $dataT4['quoteResponse']['result'][0]['regularMarketPrice'] ?? null,
        'body_preview' => $rawT4 !== false ? substr((string)$rawT4, 0, 300) : null,
        'diagnosis' => !empty($dataT4['quoteResponse']['result']) ? 'PASS: v8 works with cookie alone, crumb not required!'
            : "FAIL: HTTP {$codeT4} even with cookie, no crumb",
    ];

    // Test 5: v7 quote endpoint with cookie (different endpoint, sometimes has different rules)
    $ch5 = curl_init('https://query1.finance.yahoo.com/v7/finance/quote?symbols=TCS.NS&fields=regularMarketPrice');
    curl_setopt_array($ch5, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 12, CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_COOKIE => $cookieOnly,
        CURLOPT_HTTPHEADER => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
            'Accept: application/json', 'Referer: https://finance.yahoo.com/',
        ],
    ]);
    $rawT5 = curl_exec($ch5);
    $codeT5 = curl_getinfo($ch5, CURLINFO_HTTP_CODE);
    curl_close($ch5);
    $dataT5 = $rawT5 ? json_decode($rawT5, true) : null;
    $out['test5_v7_with_cookie'] = [
        'http_code' => $codeT5,
        'got_data'  => !empty($dataT5['quoteResponse']['result']),
        'price'     => $dataT5['quoteResponse']['result'][0]['regularMarketPrice'] ?? null,
        'body_preview' => $rawT5 !== false ? substr((string)$rawT5, 0, 300) : null,
        'diagnosis' => !empty($dataT5['quoteResponse']['result']) ? 'PASS: v7 works with cookie!'
            : "FAIL: HTTP {$codeT5}",
    ];

    // Test 6: Stooq fallback
    $stooqResult = stooqQuoteFallback('TCS.NS');
    $out['stooq_fetch'] = ['got_data'=>!empty($stooqResult), 'price'=>$stooqResult['regularMarketPrice']??null];

    // Test 7: NSE fallback
    $nseResult = nseQuoteFallback('TCS.NS');
    $out['nse_fetch'] = ['got_data'=>!empty($nseResult), 'price'=>$nseResult['regularMarketPrice']??null];

    echo json_encode($out, JSON_PRETTY_PRINT);
    exit;
}

// ── Debug: test EODHD + Twelve Data API keys ─────────────────
if ($uri === '/api/debug/apikeys') {
    header('Content-Type: application/json');
    $out = [];

    // BSE quote confirmed working
    $q = bseQuoteFetch('TCS.NS');
    $out['bse_quote_TCS'] = ['price' => $q['regularMarketPrice'] ?? null, 'ok' => ($q['regularMarketPrice'] ?? 0) > 0];

    // Test Stooq history CSV (different URL from quote — history download works differently)
    $urls = [
        'stooq_hist_tcs_in'  => 'https://stooq.com/q/d/l/?s=tcs.in&d1=20260401&d2=20260703&i=d',
        'stooq_hist_infy_in' => 'https://stooq.com/q/d/l/?s=infy.in&d1=20260401&d2=20260703&i=d',
        'stooq_hist_tcs_ns'  => 'https://stooq.com/q/d/l/?s=tcs.ns&d1=20260401&d2=20260703&i=d',
    ];
    foreach ($urls as $name => $url) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 10, CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => ['User-Agent: Mozilla/5.0', 'Accept: text/csv,*/*'],
        ]);
        $raw = curl_exec($ch); $code = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
        $out[$name] = ['http_code' => $code, 'body_preview' => substr((string)$raw, 0, 300)];
    }

    // Test Yahoo Finance chart (history) — sometimes works even when quote doesn't
    $ch = curl_init('https://query2.finance.yahoo.com/v8/finance/chart/TCS.NS?period1=1743465600&period2=1751500800&interval=1d');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 10, CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => ['User-Agent: Mozilla/5.0', 'Accept: application/json'],
    ]);
    $raw = curl_exec($ch); $code = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
    $d = $raw ? json_decode($raw, true) : null;
    $timestamps = $d['chart']['result'][0]['timestamp'] ?? [];
    $out['yahoo_chart_TCS'] = [
        'http_code' => $code,
        'bars'      => count($timestamps),
        'preview'   => substr((string)$raw, 0, 200),
    ];

    echo json_encode($out, JSON_PRETTY_PRINT);
    exit;
}
