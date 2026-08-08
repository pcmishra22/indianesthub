<?php
declare(strict_types=1);
/**
 * http.php — low-level HTTP client for Yahoo Finance, with the verified cookie fix.
 *
 * IMPORTANT: this file previously relied on CURLOPT_COOKIEJAR/CURLOPT_COOKIEFILE to
 * persist cookies between requests. That was proven unreliable in this environment —
 * Yahoo would send valid Set-Cookie headers but curl's jar-file mechanism silently
 * failed to persist them (reproduced independently against github.com as a control).
 * Fix: parse Set-Cookie headers directly via CURLOPT_HEADERFUNCTION and pass the
 * cookie string explicitly via CURLOPT_COOKIE on every request. Do not revert this.
 *
 * Separately verified (2026-06-30): Yahoo's crumb endpoint
 * (query1.finance.yahoo.com/v1/test/csrfToken) is DEAD — Yahoo's own edge returns
 * "Unknown Host" / ats-ncache 500, meaning the backend service was decommissioned.
 * The v7 quote endpoint, called with a valid cookie, returns a clean 401 from Yahoo's
 * own API: "User is unable to access this feature" — i.e. Yahoo has deliberately
 * closed unauthenticated third-party access. This is not fixable via headers/retries.
 * See app/datasources.php for the real-API-key path that replaces Yahoo scraping.
 */

function yahooGetCrumb(bool $forceDebug = false): array
{
    $crumbFile = STORAGE . '/yahoo_crumb.json';
    if (!$forceDebug && file_exists($crumbFile) && (time() - filemtime($crumbFile)) < 1800) {
        $cached = json_decode(file_get_contents($crumbFile), true);
        if (!empty($cached['crumb']) && !empty($cached['cookie'])) return $cached;
    }

    $debug = [];

    // ── Step 1: hit the homepage and capture Set-Cookie headers directly ──
    // (Not relying on CURLOPT_COOKIEJAR file — proven unreliable in this environment;
    //  parsing Set-Cookie headers via CURLOPT_HEADERFUNCTION is robust everywhere.)
    $rawHeaders1 = '';
    $ch = curl_init('https://finance.yahoo.com/');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_ENCODING       => 'gzip',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HEADERFUNCTION => function ($curlHandle, $headerLine) use (&$rawHeaders1) {
            $rawHeaders1 .= $headerLine;
            return strlen($headerLine);
        },
        CURLOPT_HTTPHEADER     => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
            'Accept: text/html,application/xhtml+xml,*/*',
            'Accept-Language: en-US,en;q=0.9',
        ],
    ]);
    $step1Body = curl_exec($ch);
    $step1Code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $step1Err  = curl_error($ch);
    $step1Errno= curl_errno($ch);
    curl_close($ch);

    // Parse cookie NAME=VALUE pairs out of every Set-Cookie header (handles redirects: multiple headers possible)
    $cookieJarMap = []; // name => value
    foreach (explode("\r\n", $rawHeaders1) as $hLine) {
        if (stripos($hLine, 'set-cookie:') !== 0) continue;
        $cookiePart = trim(substr($hLine, strlen('set-cookie:')));
        $firstSegment = explode(';', $cookiePart, 2)[0]; // "NAME=VALUE"
        $eqPos = strpos($firstSegment, '=');
        if ($eqPos === false) continue;
        $name  = trim(substr($firstSegment, 0, $eqPos));
        $value = trim(substr($firstSegment, $eqPos + 1));
        if ($name !== '') $cookieJarMap[$name] = $value;
    }

    $debug['step1_homepage'] = [
        'http_code'   => $step1Code,
        'curl_errno'  => $step1Errno,
        'curl_error'  => $step1Err ?: null,
        'body_len'    => $step1Body !== false ? strlen($step1Body) : 0,
        'cookies_parsed_from_headers' => array_keys($cookieJarMap),
        'cookies_set' => count($cookieJarMap),
    ];

    // Build the Cookie header string to send on subsequent requests
    $cookieStr = '';
    foreach ($cookieJarMap as $name => $value) {
        $cookieStr .= ($cookieStr ? '; ' : '') . $name . '=' . $value;
    }

    // ── Step 2: fetch crumb token, sending the cookie string we just parsed ──
    $rawHeaders2 = '';
    $ch2 = curl_init('https://query1.finance.yahoo.com/v1/test/csrfToken');
    curl_setopt_array($ch2, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_ENCODING       => 'gzip',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_COOKIE         => $cookieStr,
        CURLOPT_HEADERFUNCTION => function ($curlHandle, $headerLine) use (&$rawHeaders2) {
            $rawHeaders2 .= $headerLine;
            return strlen($headerLine);
        },
        CURLOPT_HTTPHEADER     => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
            'Accept: application/json',
            'Referer: https://finance.yahoo.com/',
        ],
    ]);
    $raw = curl_exec($ch2);
    $step2Code  = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
    $step2Err   = curl_error($ch2);
    $step2Errno = curl_errno($ch2);
    curl_close($ch2);

    // Step 2 may also set/refresh cookies — merge them in too
    foreach (explode("\r\n", $rawHeaders2) as $hLine) {
        if (stripos($hLine, 'set-cookie:') !== 0) continue;
        $cookiePart = trim(substr($hLine, strlen('set-cookie:')));
        $firstSegment = explode(';', $cookiePart, 2)[0];
        $eqPos = strpos($firstSegment, '=');
        if ($eqPos === false) continue;
        $name  = trim(substr($firstSegment, 0, $eqPos));
        $value = trim(substr($firstSegment, $eqPos + 1));
        if ($name !== '') $cookieJarMap[$name] = $value;
    }
    $cookieStr = '';
    foreach ($cookieJarMap as $name => $value) {
        $cookieStr .= ($cookieStr ? '; ' : '') . $name . '=' . $value;
    }

    $debug['step2_crumb'] = [
        'http_code'    => $step2Code,
        'curl_errno'   => $step2Errno,
        'curl_error'   => $step2Err ?: null,
        'cookie_sent_len' => strlen($cookieStr),
        'body_preview' => $raw !== false ? substr((string)$raw, 0, 200) : null,
        'body_full_len' => $raw !== false ? strlen($raw) : 0,
        'body_full'    => $raw !== false ? $raw : null,
    ];

    $crumb = '';
    if ($raw) {
        $json  = json_decode($raw, true);
        $crumb = $json['crumb'] ?? '';
        if (!$crumb && strlen(trim($raw)) < 60 && !str_contains($raw, '<')) $crumb = trim($raw, "\" \t\n\r");
    }
    $debug['step2_crumb']['extracted_crumb'] = $crumb ?: null;

    $result = ['crumb' => $crumb, 'cookie' => $cookieStr, 'ts' => time(), 'debug' => $debug];
    if ($crumb) file_put_contents($crumbFile, json_encode($result));
    return $result;
}

/** HTTP GET with Yahoo crumb/cookie auth + query1 fallback. */
function httpGet(string $url, int $timeout = 15): string|false
{
    $result = httpGetDebug($url, $timeout);
    return $result['body'] !== null && $result['code'] === 200 ? $result['body'] : false;
}

/**
 * Same as httpGet but returns full diagnostic info instead of swallowing errors.
 * Used by debug endpoints and by httpGet() itself.
 */
function httpGetDebug(string $url, int $timeout = 15): array
{
    // BUG FIX: this used to call yahooGetCrumb() unconditionally for every
    // single httpGet() call, including completely unrelated targets like
    // the ET/Moneycontrol RSS feeds in api/news.php. yahooGetCrumb() does
    // its OWN two curl round-trips first (finance.yahoo.com, up to 15s,
    // then query1.finance.yahoo.com, up to 10s) before this function's
    // actual request even starts — and per the note at the top of this
    // file, Yahoo's crumb endpoint is confirmed dead, so those ~25s are
    // wasted on *every* uncached call (the crumb cache file is only ever
    // written on success, which per that same note never happens anymore,
    // so it was never actually caching anything here). On a shared host
    // with a ~30s max_execution_time, that alone was enough to make the
    // real request (e.g. the RSS fetch) time out before it ever ran,
    // leaving news_cache.json stuck on whatever was last fetched
    // successfully — which is exactly the "news won't update / shows a
    // days-old item" symptom. Only pay the crumb cost for requests that
    // are actually going to Yahoo Finance.
    $isYahoo = (bool) preg_match('/(^|\.)finance\.yahoo\.com$/i', (string) parse_url($url, PHP_URL_HOST));

    $crumb = ''; $cookie = '';
    if ($isYahoo) {
        $crumbData = yahooGetCrumb();
        $crumb     = $crumbData['crumb'] ?? '';
        $cookie    = $crumbData['cookie'] ?? '';

        if ($crumb) {
            $url .= (str_contains($url, '?') ? '&' : '?') . 'crumb=' . urlencode($crumb);
        }
    }

    $urls = [$url];
    if (str_contains($url, 'query2.finance.yahoo.com')) {
        $urls[] = str_replace('query2.finance.yahoo.com', 'query1.finance.yahoo.com', $url);
    } elseif (str_contains($url, 'query1.finance.yahoo.com')) {
        $urls[] = str_replace('query1.finance.yahoo.com', 'query2.finance.yahoo.com', $url);
    }

    $attempts = [];
    foreach ($urls as $tryUrl) {
        $ch = curl_init($tryUrl);
        $opts = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_CONNECTTIMEOUT => 8,
            CURLOPT_ENCODING       => 'gzip',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER     => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                'Accept: application/json,text/html,*/*',
                'Accept-Language: en-US,en;q=0.9',
                'Cache-Control: no-cache',
                'Referer: https://finance.yahoo.com/',
            ],
        ];
        if ($cookie) {
            $opts[CURLOPT_COOKIE] = $cookie;
        }
        curl_setopt_array($ch, $opts);
        $res       = curl_exec($ch);
        $code      = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr   = curl_error($ch);
        $curlErrno = curl_errno($ch);
        curl_close($ch);

        $attempts[] = [
            'url'        => $tryUrl,
            'http_code'  => $code,
            'curl_errno' => $curlErrno,
            'curl_error' => $curlErr ?: null,
            'body_preview' => $res !== false ? substr((string)$res, 0, 300) : null,
        ];

        if ($res !== false && $code === 200) {
            return ['body' => $res, 'code' => $code, 'attempts' => $attempts];
        }
    }
    return ['body' => null, 'code' => $attempts[count($attempts)-1]['http_code'] ?? 0, 'attempts' => $attempts];
}

