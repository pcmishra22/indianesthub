<?php
declare(strict_types=1);
ini_set('display_errors', '1');
error_reporting(E_ALL);

// ─── Bootstrap ────────────────────────────────────────────────
define('BASE',       dirname(__DIR__));
define('STORAGE',    BASE . '/storage');
define('WL_FILE',    STORAGE . '/watchlist.json');
define('ALERT_FILE', STORAGE . '/alerts.json');

// ── Constants (moved before page render) ─────────────────
define('WATCHLIST_SYMBOLS', [
    // ── NIFTY 50 ─────────────────────────────────────────────
    'RELIANCE.NS','TCS.NS','HDFCBANK.NS','BHARTIARTL.NS','ICICIBANK.NS',
    'INFY.NS','SBIN.NS','HINDUNILVR.NS','ITC.NS','LT.NS',
    'KOTAKBANK.NS','AXISBANK.NS','BAJFINANCE.NS','MARUTI.NS','TITAN.NS',
    'SUNPHARMA.NS','NTPC.NS','POWERGRID.NS','ONGC.NS','HCLTECH.NS',
    'ADANIENT.NS','ADANIPORTS.NS','COALINDIA.NS','JSWSTEEL.NS','TATASTEEL.NS',
    'TATACONSUM.NS','TECHM.NS','WIPRO.NS','DIVISLAB.NS','DRREDDY.NS',
    'CIPLA.NS','APOLLOHOSP.NS','BAJAJFINSV.NS','BAJAJ-AUTO.NS','EICHERMOT.NS',
    'HEROMOTOCO.NS','TATAMOTORS.NS','M&M.NS','NESTLEIND.NS','BRITANNIA.NS',
    'ULTRACEMCO.NS','GRASIM.NS','INDUSINDBK.NS','HINDALCO.NS','VEDL.NS',
    'BPCL.NS','IOC.NS','HDFCLIFE.NS','SBILIFE.NS','SHRIRAMFIN.NS',

    // ── NIFTY NEXT 50 ─────────────────────────────────────────
    'SIEMENS.NS','ABB.NS','PIDILITIND.NS','HAVELLS.NS','MUTHOOTFIN.NS',
    'CHOLAFIN.NS','PFC.NS','RECLTD.NS','IRFC.NS','NAUKRI.NS',
    'DMART.NS','TRENT.NS','COLPAL.NS','MARICO.NS','DABUR.NS',
    'GODREJCP.NS','BERGEPAINT.NS','ASIANPAINT.NS','ASTRAL.NS','POLYCAB.NS',
    'MOTHERSON.NS','APLAPOLLO.NS','TIINDIA.NS','CUMMINSIND.NS','THERMAX.NS',
    'KALYANKJIL.NS','ZYDUSLIFE.NS','LUPIN.NS','TORNTPHARM.NS','GLAXO.NS',
    'AMBUJACEM.NS','ACC.NS','SHREECEM.NS','DALBHARAT.NS','RAMCOCEM.NS',

    // ── BANKING & FINANCE ─────────────────────────────────────
    'BANKBARODA.NS','CANBK.NS','PNB.NS','UNIONBANK.NS','IDFCFIRSTB.NS',
    'FEDERALBNK.NS','BANDHANBNK.NS','RBLBANK.NS','DCBBANK.NS','KARURVYSYA.NS',
    'AUBANK.NS','EQUITASBNK.NS','FINPIPE.NS','MANAPPURAM.NS','BAJAJHFL.NS',
    'LICHSGFIN.NS','PNBHOUSING.NS','AAVAS.NS','HOMEFIRST.NS','CUB.NS',
    'ABCAPITAL.NS','ANGELONE.NS','5PAISA.NS','MOTILALOFS.NS','EDELWEISS.NS',

    // ── IT & TECHNOLOGY ───────────────────────────────────────
    'LTIM.NS','MPHASIS.NS','PERSISTENT.NS','COFORGE.NS','OFSS.NS',
    'KPITTECH.NS','TATAELXSI.NS','RATEGAIN.NS','NEWGEN.NS','MASTEK.NS',
    'SONATSOFTW.NS','ZENSAR.NS','NIITTECH.NS','CYIENT.NS','BIRLASOFT.NS',

    // ── PHARMA & HEALTHCARE ───────────────────────────────────
    'AUROPHARMA.NS','ALKEM.NS','IPCALAB.NS','NATCOPHARM.NS','GRANULES.NS',
    'LAURUSLABS.NS','GLAND.NS','CONCORD.NS','AJANTPHARM.NS','JBCHEPHARM.NS',
    'MAXHEALTH.NS','FORTIS.NS','METROPOLIS.NS','LALPATHLAB.NS','SUVENPHAR.NS',

    // ── AUTO & AUTO ANCILLARY ─────────────────────────────────
    'TVSMOTOR.NS','ASHOKLEY.NS','BHARATFORG.NS','BOSCHLTD.NS','EXIDEIND.NS',
    'AMARAJABAT.NS','BALKRISIND.NS','CEATLTD.NS','MRF.NS','APOLLOTYRE.NS',
    'SUNDRMFAST.NS','ENDURANCE.NS','CRAFTSMAN.NS','SWARAJENG.NS','EIHOTEL.NS',

    // ── ENERGY & POWER ────────────────────────────────────────
    'TATAPOWER.NS','ADANIGREEN.NS','ADANIENSOL.NS','TORNTPOWER.NS','CESC.NS',
    'NHPC.NS','SJVN.NS','IREDA.NS','INOXWIND.NS','SUZLON.NS',
    'GMRINFRA.NS','RPOWER.NS','JSWENERGY.NS','GREENKO.NS','ACMESOLAR.NS',

    // ── FMCG & CONSUMER ───────────────────────────────────────
    'MCDOWELL-N.NS','RADICO.NS','JUBLFOOD.NS','DEVYANI.NS','SAPPHIRE.NS',
    'WESTLIFE.NS','ZOMATO.NS','NYKAA.NS','BIKAJI.NS','DOMS.NS',
    'PATANJALI.NS','EMAMILTD.NS','BAJAJCON.NS','JYOTHYLAB.NS','GSKCONS.NS',

    // ── METALS & MINING ───────────────────────────────────────
    'NATIONALUM.NS','HINDZINC.NS','MOIL.NS','WELCORP.NS','RATNAMANI.NS',
    'SAILNSE.NS','NMDC.NS','GMDC.NS','HINDCOPPER.NS','PATELENG.NS',

    // ── CEMENT & INFRA ────────────────────────────────────────
    'JKCEMENT.NS','BIRLACORPN.NS','HEIDELBERG.NS','ORIENTCEM.NS','NUVOCO.NS',
    'IRB.NS','KNR.NS','PNCINFRA.NS','HG INFRA.NS','GPPL.NS',

    // ── REAL ESTATE ───────────────────────────────────────────
    'DLF.NS','GODREJPROP.NS','PRESTIGE.NS','OBEROIRLTY.NS','BRIGADE.NS',
    'MAHLIFE.NS','SOBHA.NS','PUREIT.NS','SUNTECK.NS','KOLTEPATIL.NS',

    // ── TELECOM & MEDIA ───────────────────────────────────────
    'IDEA.NS','TATACOMM.NS','HFCL.NS','TEJAS.NS','STLTECH.NS',
    'ZEEL.NS','SUNTV.NS','PVRINOX.NS','INOXLEISUR.NS','NETWORK18.NS',

    // ── CHEMICALS & SPECIALTY ─────────────────────────────────
    'SRF.NS','DEEPAKNITR.NS','AARTI.NS','PIIND.NS','NAVINFLUOR.NS',
    'FLUOROCHEM.NS','VINATIORGA.NS','CLEAN.NS','BASF.NS','GALAXYSURF.NS',

    // ── LOGISTICS & TRADE ─────────────────────────────────────
    'CONCOR.NS','BLUEDART.NS','MAHINDLOG.NS','DELHIVERY.NS','GESHIP.NS',

    // ── CAPITAL GOODS ─────────────────────────────────────────
    'BEL.NS','HAL.NS','BHEL.NS','COCHINSHIP.NS','GRINDWELL.NS',
    'AIAENG.NS','ELGIEQUIP.NS','ESCORTS.NS','KIRLOSENG.NS','TDPOWERSYS.NS',
]);

define('SECTOR_MAP', [
    'Banking'    => ['HDFCBANK','ICICIBANK','SBIN','KOTAKBANK','AXISBANK','INDUSINDBK','BANKBARODA','CANBK','PNB','UNIONBANK','IDFCFIRSTB','FEDERALBNK','BANDHANBNK','RBLBANK','AUBANK','CUB'],
    'Finance'    => ['BAJFINANCE','BAJAJFINSV','SHRIRAMFIN','HDFCLIFE','SBILIFE','CHOLAFIN','MUTHOOTFIN','MANAPPURAM','LICHSGFIN','ANGELONE','MOTILALOFS','ABCAPITAL'],
    'IT'         => ['TCS','INFY','HCLTECH','WIPRO','TECHM','LTIM','MPHASIS','PERSISTENT','COFORGE','OFSS','TATAELXSI','KPITTECH'],
    'Pharma'     => ['SUNPHARMA','DIVISLAB','DRREDDY','CIPLA','APOLLOHOSP','AUROPHARMA','LUPIN','TORNTPHARM','ALKEM','ZYDUSLIFE','NATCOPHARM','MAXHEALTH','FORTIS'],
    'Auto'       => ['MARUTI','TATAMOTORS','M&M','BAJAJ-AUTO','EICHERMOT','HEROMOTOCO','TVSMOTOR','ASHOKLEY','BHARATFORG','BOSCHLTD','MRF'],
    'Energy'     => ['RELIANCE','ONGC','BPCL','IOC','TATAPOWER','ADANIGREEN','NTPC','POWERGRID','COALINDIA','NHPC','SUZLON','JSWENERGY'],
    'FMCG'       => ['HINDUNILVR','ITC','NESTLEIND','BRITANNIA','TATACONSUM','COLPAL','MARICO','DABUR','GODREJCP','EMAMILTD','JUBLFOOD','ZOMATO'],
    'Metals'     => ['TATASTEEL','JSWSTEEL','HINDALCO','VEDL','NATIONALUM','HINDZINC','NMDC','SAILNSE','MOIL'],
    'Cement'     => ['ULTRACEMCO','GRASIM','AMBUJACEM','ACC','SHREECEM','DALBHARAT','JKCEMENT'],
    'RealEstate' => ['DLF','GODREJPROP','PRESTIGE','OBEROIRLTY','BRIGADE','SOBHA'],
    'Chemicals'  => ['SRF','DEEPAKNITR','AARTI','PIIND','NAVINFLUOR','FLUOROCHEM'],
    'CapGoods'   => ['LT','SIEMENS','ABB','BEL','HAL','BHEL','HAVELLS','POLYCAB','CUMMINSIND'],
    'Telecom'    => ['BHARTIARTL','IDEA','TATACOMM','HFCL'],
    'Logistics'  => ['CONCOR','BLUEDART','DELHIVERY','ADANIPORTS'],
]);


// Auto-create storage dir on first run
if (!is_dir(STORAGE)) mkdir(STORAGE, 0755, true);

// Load .env
foreach (file(BASE . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
    $line = trim($line);
    if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) continue;
    [$k, $v] = explode('=', $line, 2);
    putenv(trim($k) . '=' . trim(trim($v), '"\''));
}

date_default_timezone_set(getenv('TIMEZONE') ?: 'Asia/Kolkata');
set_time_limit(90);

session_name('stock_sess');
session_start();

$APP_NAME = getenv('APP_NAME') ?: 'NSE Stock Analyzer';
$USER     = getenv('DEMO_USER') ?: 'admin';
$PASS     = getenv('DEMO_PASS') ?: 'stockpass123';

// ─── Routing ──────────────────────────────────────────────────
// BASE = subdir the app lives in, e.g. "/stock_v3" or "" for root.
// Apache rewrites to public/index.php so SCRIPT_NAME is like /stock_v3/public/index.php
// — we go up two levels to get the real browser-visible base.
// If accessed directly as /stock_v3/public/index.php or /stock_v3/index.php, still resolve correctly.
$_scriptName = $_SERVER['SCRIPT_NAME'];
$_parts      = explode('/', trim($_scriptName, '/'));
// Remove known trailing segments in any order
$_strip = ['index.php', 'public'];
foreach ($_strip as $_seg) {
    if (end($_parts) === $_seg) array_pop($_parts);
}
// Second pass in case both were present
foreach ($_strip as $_seg) {
    if (end($_parts) === $_seg) array_pop($_parts);
}
$_base    = count($_parts) ? '/' . implode('/', $_parts) : '';
$basePath = $_base;
unset($_scriptName, $_parts, $_seg, $_strip);

$requestUri = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
if ($_base !== '' && str_starts_with($requestUri, $_base)) {
    $uri = substr($requestUri, strlen($_base));
} else {
    $uri = $requestUri;
}
$uri = '/' . ltrim($uri, '/');
if ($uri === '') $uri = '/';

// Redirect helper — always correct regardless of subdir
function redirect(string $path): void {
    global $_base;
    header('Location: ' . $_base . '/' . ltrim($path, '/'));
    exit;
}

if ($uri === '/login' || $uri === '/login/') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (trim($_POST['u'] ?? '') === $USER && trim($_POST['p'] ?? '') === $PASS) {
            $_SESSION['auth'] = true;
            $_SESSION['user'] = $USER;
            redirect('/');
        }
        $err = 'Invalid credentials.';
    }
    loginPage($APP_NAME, $err ?? ''); exit;
}
if ($uri === '/logout' || $uri === '/logout/') {
    session_destroy(); redirect('login');
}

if (empty($_SESSION['auth'])) { redirect('login'); }

header_remove('X-Powered-By');

// ── Data source diagnostic endpoint ──────────────────────────
// ── Quick connectivity test ──────────────────────────────────
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
    $stooqUrl = 'https://stooq.com/q/l/?s=reliance.in&f=sd2t2ohlcv&h&e=csv'; // .in is correct Stooq suffix for NSE India
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
    $stooqInUrl = 'https://stooq.com/q/l/?s=reliance.in&f=sd2t2ohlcv&h&e=csv';
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
    // Step 3a: get NSE cookie first
    $cookieJar = STORAGE . '/nse_cookie_debug.txt';
    $ch3 = curl_init('https://www.nseindia.com/');
    curl_setopt_array($ch3, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 8,
        CURLOPT_SSL_VERIFYPEER => false, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_COOKIEJAR => $cookieJar, CURLOPT_COOKIEFILE => $cookieJar,
        CURLOPT_HTTPHEADER => ['User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'],
    ]);
    $rawHome = curl_exec($ch3);
    $codeHome = curl_getinfo($ch3, CURLINFO_HTTP_CODE);
    curl_close($ch3);
    // Step 3b: actual API call
    $ch3b = curl_init('https://www.nseindia.com/api/quote-equity?symbol=RELIANCE');
    curl_setopt_array($ch3b, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_COOKIEJAR => $cookieJar, CURLOPT_COOKIEFILE => $cookieJar,
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
    $sym = strtoupper(trim($_POST['symbol'] ?? ''));
    if ($sym && !str_ends_with($sym, '.NS')) $sym .= '.NS';
    $wl = file_exists(WL_FILE) ? json_decode(file_get_contents(WL_FILE), true) : [];
    if ($sym && !in_array($sym, $wl)) { $wl[] = $sym; file_put_contents(WL_FILE, json_encode($wl)); }
    echo json_encode(['ok' => true, 'watchlist' => $wl]);
    exit;
}
if ($uri === '/api/watchlist/remove' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $sym = strtoupper(trim($_POST['symbol'] ?? ''));
    if (!str_ends_with($sym, '.NS')) $sym .= '.NS';
    $wl = file_exists(WL_FILE) ? json_decode(file_get_contents(WL_FILE), true) : [];
    $wl = array_values(array_filter($wl, fn($s) => $s !== $sym));
    file_put_contents(WL_FILE, json_encode($wl));
    echo json_encode(['ok' => true, 'watchlist' => $wl]);
    exit;
}
if ($uri === '/api/watchlist/list') {
    header('Content-Type: application/json');
    $wl = file_exists(WL_FILE) ? json_decode(file_get_contents(WL_FILE), true) : [];
    echo json_encode(['watchlist' => $wl]);
    exit;
}
if ($uri === '/api/watchlist/reset' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    if (file_exists(WL_FILE)) unlink(WL_FILE);
    // Also clear watchlist cache so it refetches with defaults
    $cacheFile = STORAGE . '/watchlist_cache.json';
    if (file_exists($cacheFile)) unlink($cacheFile);
    echo json_encode(['ok' => true]);
    exit;
}

// ── Clear Yahoo Finance crumb/cookie/quote cache (force re-auth) ──
if ($uri === '/api/cache/clear') {
    header('Content-Type: application/json');
    $cleared = [];
    foreach (['/yahoo_crumb.json', '/yahoo_cookie.txt', '/bulk_quotes.json'] as $f) {
        $p = STORAGE . $f;
        if (file_exists($p)) { unlink($p); $cleared[] = basename($f); }
    }
    echo json_encode(['ok' => true, 'cleared' => $cleared]);
    exit;
}

// ── Debug: test Yahoo Finance connectivity + crumb ─────────────
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

    // Test 4: Stooq fallback
    $stooqResult = stooqQuoteFallback('TCS.NS');
    $out['stooq_fetch'] = ['got_data'=>!empty($stooqResult), 'price'=>$stooqResult['regularMarketPrice']??null];

    // Test 5: NSE fallback
    $nseResult = nseQuoteFallback('TCS.NS');
    $out['nse_fetch'] = ['got_data'=>!empty($nseResult), 'price'=>$nseResult['regularMarketPrice']??null];

    echo json_encode($out, JSON_PRETTY_PRINT);
    exit;
}


// ── Price alerts ──────────────────────────────────────────────
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
        'error'        => empty($quotes) ? 'All sources failed (Stooq, NSE, Yahoo). Check server outbound connectivity.' : null,
    ]);
    exit;
}

dashboardPage($APP_NAME, $_SESSION['user'] ?? 'Trader');

// ══════════════════════════════════════════════════════════════
//  FREE DATA LAYER — Yahoo Finance (no API key needed)
// ══════════════════════════════════════════════════════════════

/**
 * Fetch quote snapshot from Yahoo Finance v8 quote endpoint (free, no key).
 * Returns assoc array or null on failure.
 */
function yahooQuote(string $symbol): ?array
{
    // Check bulk cache first (populated by yahooQuoteBulk)
    $bulkCache = STORAGE . '/bulk_quotes.json';
    if (file_exists($bulkCache) && (time() - filemtime($bulkCache)) < 300) {
        $all = json_decode(file_get_contents($bulkCache), true) ?? [];
        if (!empty($all) && isset($all[$symbol])) return $all[$symbol];
    }

    // ── Priority 1: NSE India (works best from Indian hosting) ──
    $nse = nseQuoteFallback($symbol);
    if ($nse && ($nse['regularMarketPrice'] ?? 0) > 0) return $nse;

    // ── Priority 2: Stooq (no auth, globally reliable) ──────────
    $stooq = stooqQuoteFallback($symbol);
    if ($stooq && ($stooq['regularMarketPrice'] ?? 0) > 0) return $stooq;

    // ── Priority 3: Yahoo Finance (may be blocked on some IPs) ──
    $fields = 'regularMarketPrice,regularMarketChange,regularMarketChangePercent,'
            . 'regularMarketVolume,averageDailyVolume3Month,fiftyTwoWeekHigh,fiftyTwoWeekLow,'
            . 'trailingPE,priceToBook,marketCap,shortName,longName,sector,industry,'
            . 'returnOnEquity,debtToEquity,regularMarketDayHigh,regularMarketDayLow,'
            . 'regularMarketPreviousClose,regularMarketOpen';
    $url = 'https://query2.finance.yahoo.com/v8/finance/quote?symbols=' . urlencode($symbol)
         . '&fields=' . $fields . '&lang=en-US&region=IN';
    $raw = httpGet($url);
    if ($raw) {
        $data = json_decode($raw, true);
        $result = $data['quoteResponse']['result'][0] ?? null;
        if ($result && ($result['regularMarketPrice'] ?? 0) > 0) return $result;
    }

    return null;
}

/**
 * Fallback quote from NSE India's public API.
 * Normalises the response to match Yahoo Finance field names so callers need no changes.
 */
function nseQuoteFallback(string $symbol): ?array
{
    $nseSym    = strtoupper(str_replace('.NS', '', $symbol));
    $cookieJar = STORAGE . '/nse_cookie.txt';

    // NSE needs a browser session — hit the homepage first to get cookies
    if (!file_exists($cookieJar) || (time() - filemtime($cookieJar)) > 3600) {
        $ch = curl_init('https://www.nseindia.com/');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 10, CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_COOKIEJAR => $cookieJar, CURLOPT_COOKIEFILE => $cookieJar,
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                'Accept: text/html,*/*', 'Accept-Language: en-IN,en;q=0.9',
            ],
        ]);
        curl_exec($ch); curl_close($ch);
    }

    $ch = curl_init('https://www.nseindia.com/api/quote-equity?symbol=' . urlencode($nseSym));
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 10, CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_COOKIEJAR => $cookieJar, CURLOPT_COOKIEFILE => $cookieJar,
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
 */
function stooqQuoteFallback(string $symbol): ?array
{
    // Stooq uses format: TCS.NS (lowercase .ns) for NSE stocks
    $base   = strtolower(str_replace('.NS', '', $symbol));
    $stooqSym = $base . '.in';

    $url = 'https://stooq.com/q/l/?s=' . urlencode($stooqSym) . '&f=sd2t2ohlcv&h&e=csv';
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

/**
 * Parallel bulk fetch from Stooq for multiple symbols.
 */
function stooqBulkFetch(array $symbols): array
{
    $all = [];
    $mh  = curl_multi_init();
    $handles = [];

    foreach ($symbols as $sym) {
        $base     = strtolower(str_replace('.NS', '', $sym));
        $stooqSym = $base . '.in';
        $url = 'https://stooq.com/q/l/?s=' . urlencode($stooqSym) . '&f=sd2t2ohlcv&h&e=csv';
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
    $cookieJar = STORAGE . '/nse_mkt_cookie.txt';

    // Warm up session with homepage visit
    if (!file_exists($cookieJar) || (time() - filemtime($cookieJar)) > 1800) {
        $ch = curl_init('https://www.nseindia.com/market-data/live-equity-market');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 10, CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_COOKIEJAR => $cookieJar, CURLOPT_COOKIEFILE => $cookieJar,
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
    }

    // API call with full session headers
    $ch = curl_init('https://www.nseindia.com/api/quote-equity?symbol=' . urlencode($sym));
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 12, CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_ENCODING => 'gzip',
        CURLOPT_COOKIEJAR => $cookieJar, CURLOPT_COOKIEFILE => $cookieJar,
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
function bseQuoteFetch(string $nseSymbol): ?array
{
    // BSE uses scrip codes, but their search API accepts symbol names
    $sym = strtoupper(str_replace('.NS', '', $nseSymbol));
    // BSE search to find scrip code
    $searchUrl = 'https://api.bseindia.com/BseIndiaAPI/api/ComHeader/w?quotetype=EQ&scripcode=&companyname=' . urlencode($sym);
    $ch = curl_init($searchUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 8, CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_ENCODING => 'gzip',
        CURLOPT_HTTPHEADER => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
            'Accept: application/json, text/plain, */*',
            'Referer: https://www.bseindia.com/',
            'Origin: https://www.bseindia.com',
        ],
    ]);
    $raw  = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$raw || $code !== 200) return null;
    $d = json_decode($raw, true);
    $price = (float)($d['CurrRate'] ?? $d['Curr_Rate'] ?? 0);
    if ($price <= 0) return null;

    return [
        'symbol'                     => $sym . '.NS',
        'shortName'                  => $d['CompanyName'] ?? $sym,
        'longName'                   => $d['CompanyName'] ?? $sym,
        'regularMarketPrice'         => $price,
        'regularMarketChange'        => (float)($d['Chg'] ?? 0),
        'regularMarketChangePercent' => (float)($d['PcChg'] ?? 0),
        'regularMarketPreviousClose' => (float)($d['PrevClose'] ?? $price),
        'regularMarketOpen'          => (float)($d['Open'] ?? $price),
        'regularMarketDayHigh'       => (float)($d['High'] ?? $price),
        'regularMarketDayLow'        => (float)($d['Low'] ?? $price),
        'regularMarketVolume'        => (int)($d['TotalTradedQty'] ?? 0),
        'averageDailyVolume3Month'   => (int)($d['TotalTradedQty'] ?? 0),
        'fiftyTwoWeekHigh'           => (float)($d['WeekHigh52'] ?? $price),
        'fiftyTwoWeekLow'            => (float)($d['WeekLow52'] ?? $price),
        'trailingPE' => null, 'priceToBook' => null, 'marketCap' => null,
        'sector' => null, 'industry' => null, 'returnOnEquity' => null, 'debtToEquity' => null,
        '_source' => 'bse',
    ];
}


/**
 * Bulk-fetch quotes: tries NSE+Stooq first (reliable), then Yahoo Finance.
 * Caches to bulk_quotes.json for 5 minutes.
 */
function yahooQuoteBulk(array $symbols): array
{
    $bulkCache = STORAGE . '/bulk_quotes.json';
    if (file_exists($bulkCache) && (time() - filemtime($bulkCache)) < 300) {
        $cached = json_decode(file_get_contents($bulkCache), true) ?? [];
        if (!empty($cached)) return $cached;
    }

    $all = [];

    // ── Priority 1: Stooq parallel fetch (.ns format) ────────────
    $all = stooqBulkFetch($symbols);

    // ── Priority 2: Yahoo Finance v7 (mobile UA, less blocked) ───
    if (empty($all)) {
        $all = yahooV7BulkFetch($symbols);
    }

    // ── Priority 3: Yahoo Finance v8 with crumb ──────────────────
    if (empty($all)) {
        $crumbData  = yahooGetCrumb();
        $crumb      = $crumbData['crumb'] ?? '';
        $cookie     = $crumbData['cookie'] ?? '';
        $cookieJar  = STORAGE . '/yahoo_cookie.txt';
        $crumbParam = $crumb ? '&crumb=' . urlencode($crumb) : '';
        $fields     = 'regularMarketPrice,regularMarketChange,regularMarketChangePercent,'
                    . 'regularMarketVolume,averageDailyVolume3Month,fiftyTwoWeekHigh,fiftyTwoWeekLow,'
                    . 'trailingPE,priceToBook,marketCap,shortName,longName,sector,industry,'
                    . 'returnOnEquity,debtToEquity,regularMarketDayHigh,regularMarketDayLow,'
                    . 'regularMarketPreviousClose,regularMarketOpen';
        foreach (array_chunk(array_values($symbols), 50) as $chunk) {
            $symsParam = implode(',', array_map('urlencode', $chunk));
            foreach (['query2', 'query1'] as $host) {
                $url = "https://{$host}.finance.yahoo.com/v8/finance/quote?symbols={$symsParam}&fields={$fields}&lang=en-US&region=IN{$crumbParam}";
                $ch  = curl_init($url);
                $opts = [
                    CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_TIMEOUT => 20, CURLOPT_CONNECTTIMEOUT => 8,
                    CURLOPT_ENCODING => 'gzip', CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_HTTPHEADER => [
                        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                        'Accept: application/json', 'Accept-Language: en-US,en;q=0.9',
                        'Referer: https://finance.yahoo.com/',
                    ],
                ];
                if ($cookie) { $opts[CURLOPT_COOKIE] = $cookie; $opts[CURLOPT_COOKIEFILE] = $cookieJar; $opts[CURLOPT_COOKIEJAR] = $cookieJar; }
                curl_setopt_array($ch, $opts);
                $raw  = curl_exec($ch);
                $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if (!$raw || $code !== 200) continue;
                $data = json_decode($raw, true);
                foreach ($data['quoteResponse']['result'] ?? [] as $q) {
                    if (!empty($q['symbol']) && ($q['regularMarketPrice'] ?? 0) > 0) {
                        $q['_source'] = 'yahoo_v8';
                        $all[$q['symbol']] = $q;
                    }
                }
                if (!empty($all)) break;
            }
        }
    }

    // ── Priority 4: NSE India with improved session (market-data referer) ─
    if (empty($all)) {
        foreach (array_slice($symbols, 0, 20) as $sym) {
            $nse = nseMarketFetch($sym);
            if ($nse && ($nse['regularMarketPrice'] ?? 0) > 0) $all[$sym] = $nse;
            usleep(300000); // NSE needs ~300ms between calls
        }
    }

    // ── Priority 5: NSE India original endpoint ───────────────────────
    if (empty($all)) {
        foreach (array_slice($symbols, 0, 20) as $sym) {
            $nse = nseQuoteFallback($sym);
            if ($nse && ($nse['regularMarketPrice'] ?? 0) > 0) $all[$sym] = $nse;
            usleep(200000);
        }
    }

    // ── Priority 6: Groww API (India-based CDN, no IP block) ─────────
    if (empty($all)) {
        foreach (array_slice($symbols, 0, 30) as $sym) {
            $gw = growwQuoteFetch($sym);
            if ($gw && ($gw['regularMarketPrice'] ?? 0) > 0) $all[$sym] = $gw;
            usleep(100000);
        }
    }

    // ── Priority 7: BSE India public API ─────────────────────────────
    if (empty($all)) {
        foreach (array_slice($symbols, 0, 20) as $sym) {
            $bse = bseQuoteFetch($sym);
            if ($bse && ($bse['regularMarketPrice'] ?? 0) > 0) $all[$sym] = $bse;
            usleep(150000);
        }
    }

    if (!empty($all)) file_put_contents($bulkCache, json_encode($all));
    return $all;
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

/**
 * Fetch historical OHLCV. Cached per-symbol for 6 hours (daily data is stable intraday).
 */
function yahooHistory(string $symbol, int $days = 90): array
{
    $cacheFile = STORAGE . '/hist_' . preg_replace('/[^A-Z0-9]/', '_', strtoupper($symbol)) . '.json';
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 21600) {
        $cached = json_decode(file_get_contents($cacheFile), true);
        if (!empty($cached)) return $cached;
    }
    $period2 = time();
    $period1 = $period2 - ($days * 86400);
    $url = 'https://query2.finance.yahoo.com/v8/finance/chart/' . urlencode($symbol)
         . '?period1=' . $period1 . '&period2=' . $period2
         . '&interval=1d&events=history&includeAdjustedClose=true';
    $raw = httpGet($url);
    if (!$raw) {
        // Fallback: fetch history from Stooq
        return stooqHistoryFallback($symbol, $days);
    }
    $data  = json_decode($raw, true);
    $chart = $data['chart']['result'][0] ?? null;
    if (!$chart) return stooqHistoryFallback($symbol, $days);
    $timestamps = $chart['timestamp'] ?? [];
    $ohlcv      = $chart['indicators']['quote'][0] ?? [];
    $rows = [];
    foreach ($timestamps as $i => $ts) {
        $close = $ohlcv['close'][$i] ?? null;
        if ($close === null) continue;
        $rows[] = [
            'date'   => date('Y-m-d', $ts),
            'open'   => round($ohlcv['open'][$i]   ?? $close, 2),
            'high'   => round($ohlcv['high'][$i]   ?? $close, 2),
            'low'    => round($ohlcv['low'][$i]    ?? $close, 2),
            'close'  => round($close, 2),
            'volume' => $ohlcv['volume'][$i] ?? 0,
        ];
    }
    if (!empty($rows)) file_put_contents($cacheFile, json_encode($rows));
    return $rows;
}

/**
 * Fetch Yahoo Finance crumb + cookies (required since 2023 anti-bot update).
 * Cached in storage for 30 minutes.
 */
function yahooGetCrumb(bool $forceDebug = false): array
{
    $crumbFile = STORAGE . '/yahoo_crumb.json';
    if (!$forceDebug && file_exists($crumbFile) && (time() - filemtime($crumbFile)) < 1800) {
        $cached = json_decode(file_get_contents($crumbFile), true);
        if (!empty($cached['crumb']) && !empty($cached['cookie'])) return $cached;
    }

    $cookieJar = STORAGE . '/yahoo_cookie.txt';
    @unlink($cookieJar); // start fresh so the cookie jar reflects this attempt only
    $debug = [];

    // Step 1: hit the main page to get cookies
    $ch = curl_init('https://finance.yahoo.com/');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_ENCODING       => 'gzip',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_COOKIEJAR      => $cookieJar,
        CURLOPT_COOKIEFILE     => $cookieJar,
        CURLOPT_HTTPHEADER     => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
            'Accept: text/html,application/xhtml+xml,*/*',
            'Accept-Language: en-US,en;q=0.9',
        ],
    ]);
    $step1Body = curl_exec($ch);
    $debug['step1_homepage'] = [
        'http_code'  => curl_getinfo($ch, CURLINFO_HTTP_CODE),
        'curl_errno' => curl_errno($ch),
        'curl_error' => curl_error($ch) ?: null,
        'final_url'  => curl_getinfo($ch, CURLINFO_EFFECTIVE_URL),
        'body_len'   => $step1Body !== false ? strlen($step1Body) : 0,
    ];
    curl_close($ch);

    $cookieCountAfterStep1 = 0;
    if (file_exists($cookieJar)) {
        foreach (file($cookieJar) as $line) {
            if (trim($line) !== '' && $line[0] !== '#') $cookieCountAfterStep1++;
        }
    }
    $debug['step1_homepage']['cookies_set'] = $cookieCountAfterStep1;

    // Step 2: fetch crumb token (requires the cookie from step 1)
    $ch2 = curl_init('https://query1.finance.yahoo.com/v1/test/csrfToken');
    curl_setopt_array($ch2, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_ENCODING       => 'gzip',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_COOKIEJAR      => $cookieJar,
        CURLOPT_COOKIEFILE     => $cookieJar,
        CURLOPT_HTTPHEADER     => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
            'Accept: application/json',
            'Referer: https://finance.yahoo.com/',
        ],
    ]);
    $raw = curl_exec($ch2);
    $debug['step2_crumb'] = [
        'http_code'  => curl_getinfo($ch2, CURLINFO_HTTP_CODE),
        'curl_errno' => curl_errno($ch2),
        'curl_error' => curl_error($ch2) ?: null,
        'body_preview' => $raw !== false ? substr((string)$raw, 0, 200) : null,
    ];
    curl_close($ch2);

    $crumb = '';
    if ($raw) {
        $json  = json_decode($raw, true);
        $crumb = $json['crumb'] ?? '';
        if (!$crumb && strlen(trim($raw)) < 60 && !str_contains($raw, '<')) $crumb = trim($raw, "\" \t\n\r");
    }
    $debug['step2_crumb']['extracted_crumb'] = $crumb ?: null;

    // Read cookie string from Netscape cookie jar file
    $cookieStr = '';
    if (file_exists($cookieJar)) {
        foreach (file($cookieJar) as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#') continue;
            $parts = explode("\t", $line);
            if (count($parts) >= 7) {
                $cookieStr .= ($cookieStr ? '; ' : '') . $parts[5] . '=' . $parts[6];
            }
        }
    }

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
    $crumbData = yahooGetCrumb();
    $crumb     = $crumbData['crumb'] ?? '';
    $cookie    = $crumbData['cookie'] ?? '';
    $cookieJar = STORAGE . '/yahoo_cookie.txt';

    if ($crumb && str_contains($url, 'finance.yahoo.com')) {
        $url .= (str_contains($url, '?') ? '&' : '?') . 'crumb=' . urlencode($crumb);
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
            $opts[CURLOPT_COOKIE]     = $cookie;
            $opts[CURLOPT_COOKIEFILE] = $cookieJar;
            $opts[CURLOPT_COOKIEJAR]  = $cookieJar;
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

// ══════════════════════════════════════════════════════════════
//  TECHNICAL INDICATOR CALCULATIONS (pure PHP, no dependency)
// ══════════════════════════════════════════════════════════════

/** Extract close prices from history rows */
function closes(array $history): array
{
    return array_column($history, 'close');
}

/** Simple Moving Average */
function sma(array $prices, int $period): array
{
    $result = [];
    $n = count($prices);
    for ($i = 0; $i < $n; $i++) {
        if ($i < $period - 1) { $result[] = null; continue; }
        $slice = array_slice($prices, $i - $period + 1, $period);
        $result[] = round(array_sum($slice) / $period, 4);
    }
    return $result;
}

/** Exponential Moving Average */
function ema(array $prices, int $period): array
{
    $result = [];
    $k = 2 / ($period + 1);
    $n = count($prices);
    $prevEma = null;
    for ($i = 0; $i < $n; $i++) {
        if ($i < $period - 1) { $result[] = null; continue; }
        if ($prevEma === null) {
            // seed with SMA
            $prevEma = array_sum(array_slice($prices, 0, $period)) / $period;
            $result[] = round($prevEma, 4);
        } else {
            $prevEma = ($prices[$i] - $prevEma) * $k + $prevEma;
            $result[] = round($prevEma, 4);
        }
    }
    return $result;
}

/** RSI (14-period) */
function rsi(array $prices, int $period = 14): array
{
    $result = [];
    $n = count($prices);
    if ($n < $period + 1) return array_fill(0, $n, null);

    $gains = $losses = [];
    for ($i = 1; $i < $n; $i++) {
        $diff = $prices[$i] - $prices[$i - 1];
        $gains[]  = max(0, $diff);
        $losses[] = max(0, -$diff);
    }

    // Initial avg
    $avgGain = array_sum(array_slice($gains, 0, $period)) / $period;
    $avgLoss = array_sum(array_slice($losses, 0, $period)) / $period;

    // Pad nulls for first $period prices
    for ($i = 0; $i <= $period; $i++) $result[] = null;

    for ($i = $period; $i < count($gains); $i++) {
        $avgGain = ($avgGain * ($period - 1) + $gains[$i]) / $period;
        $avgLoss = ($avgLoss * ($period - 1) + $losses[$i]) / $period;
        $rs  = $avgLoss == 0 ? 100 : $avgGain / $avgLoss;
        $result[] = round(100 - 100 / (1 + $rs), 2);
    }
    return $result;
}

/** MACD: returns ['macd', 'signal', 'hist'] arrays */
function macd(array $prices, int $fast = 12, int $slow = 26, int $signal = 9): array
{
    $emaFast   = ema($prices, $fast);
    $emaSlow   = ema($prices, $slow);
    $macdLine  = [];
    foreach ($emaFast as $i => $ef) {
        $es = $emaSlow[$i] ?? null;
        $macdLine[] = ($ef !== null && $es !== null) ? round($ef - $es, 4) : null;
    }
    // Signal line = EMA9 of MACD (only non-null MACD values)
    $nonNull = array_values(array_filter($macdLine, fn($v) => $v !== null));
    $sigLine = ema($nonNull, $signal);

    // Re-align signal to full length
    $nullCount = count(array_filter($macdLine, fn($v) => $v === null));
    $fullSignal = array_merge(array_fill(0, $nullCount, null), $sigLine);

    $hist = [];
    foreach ($macdLine as $i => $m) {
        $s = $fullSignal[$i] ?? null;
        $hist[] = ($m !== null && $s !== null) ? round($m - $s, 4) : null;
    }
    return ['macd' => $macdLine, 'signal' => $fullSignal, 'hist' => $hist];
}

/** Bollinger Bands (20, 2) — returns ['upper','middle','lower'] */
function bollingerBands(array $prices, int $period = 20, float $stdDev = 2.0): array
{
    $upper = $middle = $lower = [];
    $n = count($prices);
    for ($i = 0; $i < $n; $i++) {
        if ($i < $period - 1) {
            $upper[] = $middle[] = $lower[] = null;
            continue;
        }
        $slice = array_slice($prices, $i - $period + 1, $period);
        $mean  = array_sum($slice) / $period;
        $variance = 0;
        foreach ($slice as $v) $variance += ($v - $mean) ** 2;
        $sd = sqrt($variance / $period);
        $upper[]  = round($mean + $stdDev * $sd, 2);
        $middle[] = round($mean, 2);
        $lower[]  = round($mean - $stdDev * $sd, 2);
    }
    return ['upper' => $upper, 'middle' => $middle, 'lower' => $lower];
}

/** Supertrend (10, 3) — simplified using ATR */
function supertrend(array $history, int $period = 10, float $mult = 3.0): string
{
    $n = count($history);
    if ($n < $period + 1) return 'Bullish';

    $trs = [];
    for ($i = 1; $i < $n; $i++) {
        $h = $history[$i]['high'];
        $l = $history[$i]['low'];
        $pc = $history[$i - 1]['close'];
        $trs[] = max($h - $l, abs($h - $pc), abs($l - $pc));
    }
    // ATR = SMA of TRs
    $atr = array_sum(array_slice($trs, -$period)) / $period;

    $last = $history[$n - 1];
    $hl2 = ($last['high'] + $last['low']) / 2;
    $upperBand = $hl2 + $mult * $atr;
    $lowerBand = $hl2 - $mult * $atr;

    return $last['close'] > $lowerBand ? 'Bullish' : 'Bearish';
}

/** VWAP — intraday approximation using daily data */
function vwapDaily(array $history): float
{
    // Use last 20 days as proxy
    $slice = array_slice($history, -20);
    $num = $den = 0;
    foreach ($slice as $r) {
        $typical = ($r['high'] + $r['low'] + $r['close']) / 3;
        $num += $typical * $r['volume'];
        $den += $r['volume'];
    }
    return $den > 0 ? round($num / $den, 2) : 0;
}

/** Alias: vwap() — same as vwapDaily() */
function vwap(array $history): float { return vwapDaily($history); }

/** ATR (Average True Range, 14-period) */
function atr(array $history, int $period = 14): float
{
    $n = count($history);
    if ($n < 2) return 0.0;
    $trs = [];
    for ($i = 1; $i < $n; $i++) {
        $h  = $history[$i]['high'];
        $l  = $history[$i]['low'];
        $pc = $history[$i - 1]['close'];
        $trs[] = max($h - $l, abs($h - $pc), abs($l - $pc));
    }
    $slice = array_slice($trs, -$period);
    return count($slice) ? array_sum($slice) / count($slice) : 0.0;
}

/** Alias: candlestickPatterns() — same as detectPatterns() */
function candlestickPatterns(array $history): array { return detectPatterns($history); }

/** Detect simple candlestick patterns on last 3 candles */
function detectPatterns(array $history): array
{
    $patterns = [];
    $n = count($history);
    if ($n < 3) return $patterns;

    $c = $history[$n - 1];
    $p = $history[$n - 2];
    $pp = $history[$n - 3];

    $body = abs($c['close'] - $c['open']);
    $range = $c['high'] - $c['low'];
    $upperWick = $c['high'] - max($c['open'], $c['close']);
    $lowerWick = min($c['open'], $c['close']) - $c['low'];

    // Doji
    if ($range > 0 && $body / $range < 0.1) {
        $patterns[] = ['name' => 'Doji', 'type' => 'neutral', 'description' => 'Indecision candle — market at equilibrium'];
    }
    // Hammer
    if ($lowerWick > 2 * $body && $upperWick < $body && $c['close'] > $c['open']) {
        $patterns[] = ['name' => 'Hammer', 'type' => 'bullish', 'description' => 'Potential reversal from downtrend'];
    }
    // Shooting Star
    if ($upperWick > 2 * $body && $lowerWick < $body && $c['close'] < $c['open']) {
        $patterns[] = ['name' => 'Shooting Star', 'type' => 'bearish', 'description' => 'Potential reversal from uptrend'];
    }
    // Engulfing
    if ($c['close'] > $c['open'] && $p['close'] < $p['open'] &&
        $c['open'] <= $p['close'] && $c['close'] >= $p['open']) {
        $patterns[] = ['name' => 'Bullish Engulfing', 'type' => 'bullish', 'description' => 'Strong reversal signal — bulls took control'];
    }
    if ($c['close'] < $c['open'] && $p['close'] > $p['open'] &&
        $c['open'] >= $p['close'] && $c['close'] <= $p['open']) {
        $patterns[] = ['name' => 'Bearish Engulfing', 'type' => 'bearish', 'description' => 'Strong reversal signal — bears took control'];
    }
    // Morning/Evening star
    $midBody = abs($p['close'] - $p['open']);
    if ($pp['close'] < $pp['open'] && $midBody < ($pp['high'] - $pp['low']) * 0.3
        && $c['close'] > $c['open'] && $c['close'] > ($pp['open'] + $pp['close']) / 2) {
        $patterns[] = ['name' => 'Morning Star', 'type' => 'bullish', 'description' => 'Three-candle bullish reversal pattern'];
    }
    // Volume spike
    $avgVol = array_sum(array_column(array_slice($history, -10, 9), 'volume')) / 9;
    if ($avgVol > 0 && $c['volume'] > 1.5 * $avgVol) {
        $patterns[] = ['name' => 'Volume Spike', 'type' => $c['close'] >= $c['open'] ? 'bullish' : 'bearish',
            'description' => sprintf('%.1fx average volume — strong participation', $c['volume'] / $avgVol)];
    }

    return $patterns ?: [['name' => 'No Clear Pattern', 'type' => 'neutral', 'description' => 'No strong candlestick pattern detected']];
}

/** Generate buy/sell signal + reasoning from indicators */
function generateSignal(array $quote, array $history, array $indicators): array
{
    $price  = $quote['regularMarketPrice'] ?? 0;
    $rsiVal = end(array_filter($indicators['rsi'], fn($v) => $v !== null)) ?: 50;
    $macdH  = end(array_filter($indicators['macd']['hist'], fn($v) => $v !== null)) ?: 0;
    $macdV  = end(array_filter($indicators['macd']['macd'], fn($v) => $v !== null)) ?: 0;
    $ema20  = end(array_filter($indicators['ema20'], fn($v) => $v !== null)) ?: $price;
    $ema50  = end(array_filter($indicators['ema50'], fn($v) => $v !== null)) ?: $price;
    $bbU    = end(array_filter($indicators['bb']['upper'], fn($v) => $v !== null)) ?: $price * 1.05;
    $bbL    = end(array_filter($indicators['bb']['lower'], fn($v) => $v !== null)) ?: $price * 0.95;
    $vwap   = $indicators['vwap'];
    $st     = $indicators['supertrend'];

    $bullish = 0; $bearish = 0;
    $bullFactors = []; $bearFactors = [];

    // EMA signals
    if ($price > $ema20 && $price > $ema50) { $bullish += 2; $bullFactors[] = 'Price above EMA20 and EMA50 — uptrend intact'; }
    elseif ($price < $ema20 && $price < $ema50) { $bearish += 2; $bearFactors[] = 'Price below EMA20 and EMA50 — downtrend active'; }
    if ($ema20 > $ema50) { $bullish++; $bullFactors[] = 'Golden Cross: EMA20 above EMA50'; }
    elseif ($ema20 < $ema50) { $bearish++; $bearFactors[] = 'Death Cross: EMA20 below EMA50'; }

    // RSI
    if ($rsiVal < 30) { $bullish += 2; $bullFactors[] = "RSI oversold at {$rsiVal} — potential bounce"; }
    elseif ($rsiVal > 70) { $bearish += 2; $bearFactors[] = "RSI overbought at {$rsiVal} — potential pullback"; }
    elseif ($rsiVal >= 50) { $bullish++; $bullFactors[] = "RSI at {$rsiVal} — bullish momentum"; }
    else { $bearish++; $bearFactors[] = "RSI at {$rsiVal} — bearish momentum"; }

    // MACD
    if ($macdH > 0 && $macdV > 0) { $bullish += 2; $bullFactors[] = 'MACD above signal line — bullish crossover'; }
    elseif ($macdH < 0 && $macdV < 0) { $bearish += 2; $bearFactors[] = 'MACD below signal line — bearish crossover'; }
    elseif ($macdH > 0) { $bullish++; $bullFactors[] = 'MACD histogram turning positive'; }
    else { $bearish++; $bearFactors[] = 'MACD histogram turning negative'; }

    // Bollinger
    if ($price <= $bbL) { $bullish++; $bullFactors[] = 'Price at lower Bollinger Band — oversold zone'; }
    elseif ($price >= $bbU) { $bearish++; $bearFactors[] = 'Price at upper Bollinger Band — overbought zone'; }

    // Supertrend
    if ($st === 'Bullish') { $bullish += 2; $bullFactors[] = 'Supertrend indicator is Bullish'; }
    else { $bearish += 2; $bearFactors[] = 'Supertrend indicator is Bearish'; }

    // VWAP
    if ($price > $vwap && $vwap > 0) { $bullish++; $bullFactors[] = 'Price above VWAP — intraday buyers in control'; }
    elseif ($price < $vwap && $vwap > 0) { $bearish++; $bearFactors[] = 'Price below VWAP — sellers in control'; }

    // Change
    $chgPct = $quote['regularMarketChangePercent'] ?? 0;
    if ($chgPct > 1.5) { $bullish++; $bullFactors[] = sprintf('Strong positive day: +%.2f%%', $chgPct); }
    elseif ($chgPct < -1.5) { $bearish++; $bearFactors[] = sprintf('Strong negative day: %.2f%%', $chgPct); }

    $total = $bullish + $bearish;
    if ($total === 0) $total = 1;
    $confidence = (int) round(max($bullish, $bearish) / $total * 100);

    if ($bullish > $bearish + 1) {
        $signal = 'Buy';
        $trend  = 'Bullish';
        $verdict = "The technical picture for this stock leans bullish. " . implode('. ', $bullFactors) . ". Consider entering near current levels with a stop below EMA20.";
    } elseif ($bearish > $bullish + 1) {
        $signal = 'Sell';
        $trend  = 'Bearish';
        $verdict = "Bears are in control. " . implode('. ', $bearFactors) . ". Avoid fresh long positions; wait for RSI to reach oversold before re-entry.";
    } else {
        $signal = 'Hold';
        $trend  = 'Sideways';
        $verdict = "Mixed signals. Bullish: " . implode(', ', $bullFactors ?: ['none']) . ". Bearish: " . implode(', ', $bearFactors ?: ['none']) . ". Wait for a cleaner setup.";
    }

    return compact('signal', 'trend', 'confidence', 'bullFactors', 'bearFactors', 'verdict');
}


/** Map Yahoo symbol to NSE symbol display */
function toNseDisplay(string $ySymbol): string
{
    return str_replace('.NS', '', $ySymbol);
}

/**
 * Momentum Score (0-100): combines price velocity, volume surge,
 * RSI direction, MACD strength, EMA alignment, and Supertrend.
 * Positive = bullish momentum, negative = bearish momentum.
 */
/** 5-day percentage change from history */
function change5d(array $history): float
{
    $n = count($history);
    if ($n < 6) return 0.0;
    $now  = (float)$history[$n-1]["close"];
    $prev = (float)$history[$n-6]["close"];
    return $prev > 0 ? round(($now - $prev) / $prev * 100, 2) : 0.0;
}

function momentumScore(array $quote, array $history, array $indicators): array
{
    $price    = $quote['regularMarketPrice'] ?? 0;
    $chgPct   = $quote['regularMarketChangePercent'] ?? 0;
    $avgVol   = $quote['averageVolume'] ?? 1;
    $curVol   = $quote['regularMarketVolume'] ?? 0;
    $volRatio = $avgVol > 0 ? $curVol / $avgVol : 1;

    $closes   = closes($history);
    $n        = count($closes);

    // 1. Price velocity — % change today weighted by volume surge
    $velScore = $chgPct * min($volRatio, 3.0); // cap vol multiplier at 3x

    // 2. Short-term momentum — compare last 3 closes vs 3 before that
    $recent3 = $n >= 6 ? array_sum(array_slice($closes, -3)) / 3 : $price;
    $prev3   = $n >= 6 ? array_sum(array_slice($closes, -6, 3)) / 3 : $price;
    $stMom   = $prev3 > 0 ? (($recent3 - $prev3) / $prev3) * 100 : 0;

    // 3. RSI momentum — distance from 50 (neutral)
    $rsiLast = end(array_filter($indicators['rsi'], fn($v) => $v !== null)) ?: 50;
    $rsiMom  = ($rsiLast - 50) / 50 * 30; // scale to ±30

    // 4. MACD histogram direction and strength
    $histArr    = $indicators['macd']['hist'];
    $histNonNull = array_values(array_filter($histArr, fn($v) => $v !== null));
    $hLast  = end($histNonNull) ?: 0;
    $hPrev  = count($histNonNull) >= 2 ? $histNonNull[count($histNonNull)-2] : 0;
    $macdMom = ($hLast > $hPrev ? 1 : -1) * min(abs($hLast) * 10, 20); // direction + strength

    // 5. EMA alignment
    $ema20L = end(array_filter($indicators['ema20'], fn($v) => $v !== null)) ?: $price;
    $ema50L = end(array_filter($indicators['ema50'], fn($v) => $v !== null)) ?: $price;
    $emaScore = 0;
    if ($price > $ema20L && $ema20L > $ema50L) $emaScore = 15;
    elseif ($price > $ema20L) $emaScore = 8;
    elseif ($price < $ema20L && $ema20L < $ema50L) $emaScore = -15;
    elseif ($price < $ema20L) $emaScore = -8;

    // 6. Supertrend
    $stScore = $indicators['supertrend'] === 'Bullish' ? 10 : -10;

    // 7. 52-week position
    $w52h = $quote['fiftyTwoWeekHigh'] ?? $price;
    $w52l = $quote['fiftyTwoWeekLow'] ?? $price;
    $w52range = $w52h - $w52l;
    $w52pos = $w52range > 0 ? (($price - $w52l) / $w52range) * 20 - 10 : 0; // -10 to +10

    $total = $velScore + $stMom + $rsiMom + $macdMom + $emaScore + $stScore + $w52pos;

    // Normalize to -100..+100
    $normalized = max(-100, min(100, $total));

    // Volume surge flag
    $volSurge = $volRatio >= 1.5;
    $volLabel = $volRatio >= 2.0 ? sprintf('🔥 %.1fx vol', $volRatio)
              : ($volRatio >= 1.5 ? sprintf('📈 %.1fx vol', $volRatio)
              : sprintf('%.1fx vol', $volRatio));

    // Rank label
    if ($normalized >= 40)      $rank = 'Strong Buy';
    elseif ($normalized >= 15)  $rank = 'Buy';
    elseif ($normalized >= -15) $rank = 'Hold';
    elseif ($normalized >= -40) $rank = 'Sell';
    else                        $rank = 'Strong Sell';

    // Momentum change direction vs previous cached score (if available)
    $direction = $chgPct >= 0.5 ? 'rising' : ($chgPct <= -0.5 ? 'falling' : 'flat');

    return [
        'score'      => round($normalized, 1),
        'rank'       => $rank,
        'direction'  => $direction,
        'vol_ratio'  => round($volRatio, 2),
        'vol_surge'  => $volSurge,
        'vol_label'  => $volLabel,
        'components' => [
            'price_velocity' => round($velScore, 2),
            'short_momentum' => round($stMom, 2),
            'rsi_momentum'   => round($rsiMom, 2),
            'macd_momentum'  => round($macdMom, 2),
            'ema_alignment'  => $emaScore,
            'supertrend'     => $stScore,
            '52w_position'   => round($w52pos, 2),
        ],
    ];
}


// ══════════════════════════════════════════════════════════════
//  API ENDPOINT HANDLERS
// ══════════════════════════════════════════════════════════════



function apiWatchlist(): array
{
    $cacheFile = STORAGE . '/watchlist_cache.json';
    if (file_exists($cacheFile)) {
        $cached = json_decode(file_get_contents($cacheFile), true);
        if ($cached && (time() - ($cached['ts'] ?? 0)) < 300) {
            $cached['cached'] = true;
            return $cached;
        }
    }

    $symbols = getActiveWatchlist();
    $stocks  = [];

    foreach ($symbols as $sym) {
        $quote = yahooQuote($sym);
        if (!$quote) continue;

        $history = yahooHistory($sym, 60);
        if (count($history) < 20) continue;

        $closePrices = closes($history);
        $ema20   = ema($closePrices, 20);
        $ema50   = ema($closePrices, 50);
        $rsiArr  = rsi($closePrices);
        $macdArr = macd($closePrices);
        $bbArr   = bollingerBands($closePrices);
        $vwap    = vwapDaily($history);
        $st      = supertrend($history);

        $indicators = [
            'ema20' => $ema20, 'ema50' => $ema50,
            'rsi'   => $rsiArr, 'macd' => $macdArr,
            'bb'    => $bbArr,  'vwap' => $vwap, 'supertrend' => $st,
        ];

        $sig = generateSignalFull($quote, $history, $indicators);
        $mom = momentumScore($quote, $history, $indicators);

        // New indicators
        $adxData = adx($history);
        $stoch   = stochastic($history);
        $obvData = obv($history);
        $pivots  = pivotPoints($history);

        $price   = $quote['regularMarketPrice'] ?? 0;
        $rsiLast = end(array_filter($rsiArr, fn($v) => $v !== null)) ?: 50;
        $ema20L  = round(end(array_filter($ema20, fn($v) => $v !== null)) ?: $price, 2);
        $ema50L  = round(end(array_filter($ema50, fn($v) => $v !== null)) ?: $price, 2);

        // ATR-based target/SL
        $n    = count($history);
        $last = $history[$n - 1];
        $prev = $history[$n - 2] ?? $last;
        $atr  = max($last['high'] - $last['low'], abs($last['close'] - $prev['close']));
        $target = round($price + 2.5 * $atr, 2);
        $sl     = round($price - 1.5 * $atr, 2);

        // 5-day price change for momentum tracking
        $close5ago = $n >= 5 ? $history[$n - 6]['close'] : $history[0]['close'];
        $chg5d = $close5ago > 0 ? round((($price - $close5ago) / $close5ago) * 100, 2) : 0;

        // Pattern detection
        $pats = detectPatterns($history);
        $topPat = $pats[0]['name'] ?? 'None';

        // Cache indicators for fast per-minute tick reuse
        $indFile = STORAGE . '/indicators_cache.json';
        $indCache = file_exists($indFile) ? json_decode(file_get_contents($indFile), true) : [];
        $display = toNseDisplay($sym);
        $indCache[$sym] = [
            'rsi'        => round(end(array_filter($rsiArr, fn($v) => $v !== null)) ?: 50, 1),
            'ema20'      => round(end(array_filter($ema20, fn($v) => $v !== null)) ?: $price, 2),
            'ema50'      => round(end(array_filter($ema50, fn($v) => $v !== null)) ?: $price, 2),
            'supertrend' => $st,
            'macd_hist'  => round(end(array_filter($macdArr['hist'], fn($v) => $v !== null)) ?: 0, 4),
            'updated'    => time(),
        ];
        if (!is_dir(STORAGE)) mkdir(STORAGE, 0755, true);
        file_put_contents($indFile, json_encode($indCache));

        $stocks[] = [
            'symbol'        => toNseDisplay($sym),
            'name'          => $quote['shortName'] ?? toNseDisplay($sym),
            'price'         => round($price, 2),
            'change_pct'    => round($quote['regularMarketChangePercent'] ?? 0, 2),
            'change_5d'     => $chg5d,
            'signal'        => $sig['signal'],
            'confidence'    => $sig['confidence'],
            'momentum_score'=> $mom['score'],
            'momentum_rank' => $mom['rank'],
            'direction'     => $mom['direction'],
            'vol_label'     => $mom['vol_label'],
            'vol_surge'     => $mom['vol_surge'],
            'rsi'           => round($rsiLast, 1),
            'ema20'         => $ema20L,
            'ema50'         => $ema50L,
            'supertrend'    => $st,
            'trend'         => $sig['trend'],
            'key_reason'    => $sig['bullFactors'][0] ?? $sig['bearFactors'][0] ?? 'Mixed signals',
            'pattern'       => $topPat,
            'target'        => $target,
            'stoploss'      => $sl,
            'sector'        => $quote['sector'] ?? 'N/A',
            '52w_high'      => round($quote['fiftyTwoWeekHigh'] ?? 0, 2),
            '52w_low'       => round($quote['fiftyTwoWeekLow'] ?? 0, 2),
            // New indicators
            'adx'           => $adxData['adx'],
            'adx_strength'  => $adxData['trend_strength'] ?? 'N/A',
            'adx_direction' => $adxData['direction'] ?? 'N/A',
            'stoch_k'       => $stoch['k'],
            'stoch_d'       => $stoch['d'],
            'stoch_signal'  => $stoch['signal'],
            'obv_trend'     => $obvData['trend'] ?? 'N/A',
            'pivot_pp'      => $pivots['PP'] ?? null,
            'pivot_r1'      => $pivots['R1'] ?? null,
            'pivot_s1'      => $pivots['S1'] ?? null,
        ];
        usleep(200000);
    }

    // Sort by momentum score descending
    usort($stocks, fn($a, $b) => $b['momentum_score'] <=> $a['momentum_score']);

    // Split into buy and sell lists
    $buyList  = array_values(array_filter($stocks, fn($s) => $s['momentum_score'] >= 0));
    $sellList = array_values(array_filter($stocks, fn($s) => $s['momentum_score'] < 0));
    // Sell list: weakest first (most negative at top)
    $sellList = array_reverse($sellList);

    $buys  = count(array_filter($stocks, fn($s) => in_array($s['signal'], ['Buy'])));
    $sells = count(array_filter($stocks, fn($s) => in_array($s['signal'], ['Sell'])));
    $total = count($stocks);
    $moodScore = $total > 0 ? array_sum(array_column($stocks, 'momentum_score')) / $total : 0;
    $mood = $moodScore > 10 ? 'Bullish' : ($moodScore < -10 ? 'Bearish' : 'Neutral');

    $result = [
        'stocks'           => $stocks,
        'buy_list'         => $buyList,
        'sell_list'        => $sellList,
        'market_mood'      => $mood,
        'mood_score'       => round($moodScore, 1),
        'nifty_view'       => "Buy: {$buys} | Sell: {$sells} | Hold: " . ($total - $buys - $sells) . " of {$total}",
        'updated'          => date('Y-m-d H:i'),
        'ts'               => time(),
        'cached'           => false,
        'source'           => 'Yahoo Finance (free)',
        'custom_watchlist' => file_exists(WL_FILE) ? json_decode(file_get_contents(WL_FILE), true) : [],
    ];

    if (!is_dir(STORAGE)) mkdir(STORAGE, 0755, true);
    file_put_contents($cacheFile, json_encode($result));
    return $result;
}

function apiAnalyze(string $symbol): array
{
    if (!$symbol) return ['error' => 'No symbol provided'];

    // Normalize symbol — add .NS if needed for Indian stocks
    $yahooSym = str_ends_with($symbol, '.NS') ? $symbol : $symbol . '.NS';

    $quote = yahooQuote($yahooSym);
    if (!$quote) {
        // Try without .NS (for indices like ^NSEI)
        $quote = yahooQuote($symbol);
        if (!$quote) return ['error' => "Could not fetch data for {$symbol}. Check the symbol (e.g., RELIANCE, TCS, INFY)."];
        $yahooSym = $symbol;
    }

    $history = yahooHistory($yahooSym, 90);
    if (count($history) < 26) return ['error' => "Not enough history for {$symbol}. Need at least 26 trading days."];

    $closePrices = closes($history);
    $ema20  = ema($closePrices, 20);
    $ema50  = ema($closePrices, 50);
    $rsiArr = rsi($closePrices);
    $macdArr = macd($closePrices);
    $bbArr  = bollingerBands($closePrices);
    $vwap   = vwapDaily($history);
    $st     = supertrend($history);
    $pats   = detectPatterns($history);

    $indicators = ['ema20' => $ema20, 'ema50' => $ema50, 'rsi' => $rsiArr, 'macd' => $macdArr, 'bb' => $bbArr, 'vwap' => $vwap, 'supertrend' => $st];
    $sig = generateSignalFull($quote, $history, $indicators);

    // New indicators
    $adxData   = adx($history);
    $stoch     = stochastic($history);
    $obvData   = obv($history);
    $pivots    = pivotPoints($history);
    $wr        = williamsR($history, 14);
    $cciVal    = cci($history, 20);
    $mfiVal    = mfi($history, 14);
    $ichimoku  = ichimoku($history);
    $fibs      = fibonacci($history, 60);
    $volAnal   = volumeAnalysis($history);
    $mtf       = multiTimeframe($symbol, $quote['regularMarketPrice'] ?? 0, $history);

    $price   = $quote['regularMarketPrice'] ?? 0;
    $high52  = (float)($quote['fiftyTwoWeekHigh'] ?? 0);
    $low52   = (float)($quote['fiftyTwoWeekLow']  ?? 0);
    $pos52w  = position52W($price, $high52, $low52);
    $rsiLast = end(array_filter($rsiArr, fn($v) => $v !== null)) ?: 50;
    $ema20L  = round(end(array_filter($ema20, fn($v) => $v !== null)) ?: $price, 2);
    $ema50L  = round(end(array_filter($ema50, fn($v) => $v !== null)) ?: $price, 2);
    $macdL   = end(array_filter($macdArr['macd'], fn($v) => $v !== null)) ?: 0;
    $macdS   = end(array_filter($macdArr['signal'], fn($v) => $v !== null)) ?: 0;
    $bbU     = end(array_filter($bbArr['upper'], fn($v) => $v !== null)) ?: $price;
    $bbM     = end(array_filter($bbArr['middle'], fn($v) => $v !== null)) ?: $price;
    $bbLo    = end(array_filter($bbArr['lower'], fn($v) => $v !== null)) ?: $price;

    $bbPos = $price >= $bbU * 0.98 ? 'Near upper band' : ($price <= $bbLo * 1.02 ? 'Near lower band' : 'Middle of band');

    // Support / Resistance from recent history
    $recent20 = array_slice($history, -20);
    $support  = round(min(array_column($recent20, 'low')), 2);
    $resist   = round(max(array_column($recent20, 'high')), 2);

    // Trade setup using ATR
    $lastRows = array_slice($history, -14);
    $atrVals  = [];
    for ($i = 1; $i < count($lastRows); $i++) {
        $h = $lastRows[$i]['high']; $l = $lastRows[$i]['low']; $pc = $lastRows[$i - 1]['close'];
        $atrVals[] = max($h - $l, abs($h - $pc), abs($l - $pc));
    }
    $atr = $atrVals ? round(array_sum($atrVals) / count($atrVals), 2) : $price * 0.015;
    $entry   = round($price, 2);
    $target1 = round($price + 1.5 * $atr, 2);
    $target2 = round($price + 3.0 * $atr, 2);
    $sl      = round($price - $atr, 2);
    $rr      = $atr > 0 ? '1:' . round(1.5 * $atr / $atr, 1) : '1:1.5';

    $pe   = $quote['trailingPE'] ?? null;
    $pb   = $quote['priceToBook'] ?? null;
    $mcap = $quote['marketCap'] ?? 0;
    $roe  = isset($quote['returnOnEquity']) ? round($quote['returnOnEquity'] * 100, 1) : null;
    $de   = $quote['debtToEquity'] ?? null;

    $mcapLabel = $mcap > 1e12 ? 'Large Cap' : ($mcap > 2e11 ? 'Mid Cap' : 'Small Cap');

    $summary = sprintf(
        '%s (NSE: %s) is trading at ₹%.2f (%+.2f%%). Technical outlook: %s with %d%% confidence. %s',
        $quote['shortName'] ?? $symbol,
        $symbol,
        $price,
        $quote['regularMarketChangePercent'] ?? 0,
        $sig['trend'],
        $sig['confidence'],
        $sig['signal'] === 'Buy' ? 'Positive momentum with bullish indicators.' : ($sig['signal'] === 'Sell' ? 'Bearish pressure is dominant.' : 'Mixed signals — wait for confirmation.')
    );

    return [
        'symbol'    => $symbol,
        'name'      => $quote['shortName'] ?? $quote['longName'] ?? $symbol,
        'sector'    => $quote['sector'] ?? 'N/A',
        'industry'  => $quote['industry'] ?? 'N/A',
        'price'     => round($price, 2),
        'change_pct'=> round($quote['regularMarketChangePercent'] ?? 0, 2),
        '52w_high'  => round($quote['fiftyTwoWeekHigh'] ?? 0, 2),
        '52w_low'   => round($quote['fiftyTwoWeekLow'] ?? 0, 2),
        'signal'    => $sig['signal'],
        'confidence'=> $sig['confidence'],
        'summary'   => $summary,
        'technicals'=> [
            'trend'        => $sig['trend'],
            'ema_20'       => $ema20L,
            'ema_50'       => $ema50L,
            'ema_signal'   => $ema20L > $ema50L ? 'Golden Cross (EMA20 > EMA50)' : 'Death Cross (EMA20 < EMA50)',
            'rsi'          => round($rsiLast, 1),
            'rsi_signal'   => $rsiLast > 70 ? 'Overbought' : ($rsiLast < 30 ? 'Oversold' : 'Neutral'),
            'macd'         => $macdL > $macdS ? 'Bullish' : 'Bearish',
            'macd_note'    => sprintf('MACD: %.2f | Signal: %.2f | Hist: %.2f', $macdL, $macdS, $macdL - $macdS),
            'bollinger'    => $bbPos,
            'bollinger_note' => sprintf('Upper: ₹%.2f | Mid: ₹%.2f | Lower: ₹%.2f', $bbU, $bbM, $bbLo),
            'volume'       => ($quote['regularMarketVolume'] ?? 0) > ($quote['averageVolume'] ?? 1) * 1.3 ? 'High' : 'Normal',
            'volume_note'  => sprintf('Vol: %s | Avg: %s', number_format($quote['regularMarketVolume'] ?? 0), number_format($quote['averageVolume'] ?? 0)),
            'supertrend'   => $st,
            'support'      => $support,
            'resistance'   => $resist,
            'vwap'         => $vwap,
            'vwap_signal'  => $price > $vwap ? 'Above VWAP' : 'Below VWAP',
            // New
            'adx'          => $adxData['adx'],
            'adx_strength' => $adxData['trend_strength'] ?? 'N/A',
            'adx_direction'=> $adxData['direction'] ?? 'N/A',
            'plus_di'      => $adxData['plus_di'] ?? null,
            'minus_di'     => $adxData['minus_di'] ?? null,
            'stoch_k'      => $stoch['k'],
            'stoch_d'      => $stoch['d'],
            'stoch_signal' => $stoch['signal'],
            'obv_trend'    => $obvData['trend'] ?? 'N/A',
            // New
            'williams_r'   => $wr,
            'williams_signal' => $wr!==null ? ($wr<-80?'Oversold':($wr>-20?'Overbought':'Neutral')) : 'N/A',
            'cci'          => $cciVal,
            'cci_signal'   => $cciVal!==null ? ($cciVal<-100?'Oversold':($cciVal>100?'Overbought':'Neutral')) : 'N/A',
            'mfi'          => $mfiVal,
            'mfi_signal'   => $mfiVal!==null ? ($mfiVal<20?'Oversold':($mfiVal>80?'Overbought':'Neutral')) : 'N/A',
        ],
        'ichimoku'      => $ichimoku,
        'fibonacci'     => $fibs,
        'volume_analysis'=> $volAnal,
        'multi_timeframe'=> $mtf,
        'position_52w'  => $pos52w,
        'score_breakdown'=> scoreBreakdown($quote, $history,
            ['rsi'=>$rsiArr,'macd'=>$macdArr,'ema20'=>$ema20,'ema50'=>$ema50,'supertrend'=>$st,'vwap'=>$vwap],
            $adxData, $stoch, $obvData, $wr, $cciVal, $mfiVal, $ichimoku),
        'pivot_points' => $pivots,
        'patterns'  => $pats,
        'fundamentals' => [
            'pe_ratio'    => $pe ? round($pe, 1) : null,
            'pb_ratio'    => $pb ? round($pb, 1) : null,
            'market_cap'  => $mcapLabel,
            'market_cap_cr'=> $mcap > 0 ? '₹' . number_format(round($mcap / 1e7), 0) . ' Cr' : 'N/A',
            'debt_equity' => $de ? round($de, 2) : null,
            'roe'         => $roe,
            'note'        => $pe ? sprintf('P/E %.1fx vs sector; P/B %.1fx; ROE %s%%', $pe, $pb ?? 0, $roe ?? 'N/A') : 'Fundamental data limited for this symbol.',
        ],
        'buy_sell_reasoning' => [
            'bullish_factors' => $sig['bullFactors'],
            'bearish_factors' => $sig['bearFactors'],
            'verdict'         => $sig['verdict'],
        ],
        'trade_setup' => [
            'entry'          => $entry,
            'target_1'       => $target1,
            'target_2'       => $target2,
            'stoploss'       => $sl,
            'risk_reward'    => $rr,
            'holding_period' => 'Intraday / 1-3 days',
        ],
        'news_catalyst' => 'Check Moneycontrol, Economic Times, or NSE announcements for latest news on this stock.',
        'risk_warning'  => '⚠ This is algorithmic analysis based on price data only. Past performance does not guarantee future results. Always use stop-losses and position size responsibly. Not SEBI-registered advice.',
        'data_source'   => 'Yahoo Finance (free, real-time delayed)',
    ];
}

// ══════════════════════════════════════════════════════════════
//  PER-MINUTE TICK + SIGNAL ACCUMULATION
// ══════════════════════════════════════════════════════════════

/**
 * Per-minute tick: fetch live quotes for all symbols, run quick signal check,
 * record result into per-day signal log. Fast — no history fetch.
 */
function apiTick(): array
{
    $logFile  = STORAGE . '/signals_' . date('Y-m-d') . '.json';
    $indFile  = STORAGE . '/indicators_cache.json';

    // Load cached indicators (rebuilt every 10 min by apiWatchlist)
    $indCache = file_exists($indFile) ? json_decode(file_get_contents($indFile), true) : [];

    // Load today's signal log
    $log = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];

    $now     = time();
    $minute  = date('H:i');
    $results = [];

    // Batch fetch all symbols using the multi-source bulk fetcher (NSE→Stooq→Yahoo)
    $activeSyms = getActiveWatchlist();
    $quotes = yahooQuoteBulk($activeSyms);

    foreach ($activeSyms as $sym) {
        $quote = $quotes[$sym] ?? null;
        if (!$quote) continue;

        $price   = $quote['regularMarketPrice'] ?? 0;
        $chgPct  = $quote['regularMarketChangePercent'] ?? 0;
        $avgVol  = $quote['averageVolume'] ?? 1;
        $curVol  = $quote['regularMarketVolume'] ?? 0;
        $volR    = $avgVol > 0 ? $curVol / $avgVol : 1;

        // Use cached indicators if available, else quick signal from price action only
        $cached = $indCache[$sym] ?? null;

        if ($cached) {
            $rsi  = $cached['rsi'] ?? 50;
            $ema20 = $cached['ema20'] ?? $price;
            $ema50 = $cached['ema50'] ?? $price;
            $st   = $cached['supertrend'] ?? 'Bullish';
            $macdH = $cached['macd_hist'] ?? 0;
        } else {
            $rsi = 50; $ema20 = $price; $ema50 = $price; $st = 'Bullish'; $macdH = 0;
        }

        // Quick signal logic
        $bull = 0; $bear = 0;
        if ($price > $ema20 && $ema20 > $ema50) $bull += 3;
        elseif ($price < $ema20 && $ema20 < $ema50) $bear += 3;
        if ($rsi < 35) $bull += 2;
        elseif ($rsi > 65) $bear += 2;
        elseif ($rsi >= 50) $bull++;
        else $bear++;
        if ($macdH > 0) $bull += 2; else $bear += 2;
        if ($st === 'Bullish') $bull += 2; else $bear += 2;
        if ($chgPct > 0.5) $bull++; elseif ($chgPct < -0.5) $bear++;
        if ($volR > 1.5 && $chgPct > 0) $bull++; elseif ($volR > 1.5 && $chgPct < 0) $bear++;

        $signal = $bull > $bear + 1 ? 'Buy' : ($bear > $bull + 1 ? 'Sell' : 'Hold');
        $score  = round((($bull - $bear) / ($bull + $bear + 1)) * 100, 1);

        // Record into log
        $display = toNseDisplay($sym);
        if (!isset($log[$display])) {
            $log[$display] = ['name' => $quote['shortName'] ?? $display, 'ticks' => []];
        }
        $log[$display]['ticks'][] = [
            'ts'     => $now,
            'min'    => $minute,
            'price'  => round($price, 2),
            'chg'    => round($chgPct, 2),
            'signal' => $signal,
            'score'  => $score,
            'vol_r'  => round($volR, 2),
        ];
        // Keep only last 500 ticks per symbol per day
        if (count($log[$display]['ticks']) > 500) {
            $log[$display]['ticks'] = array_slice($log[$display]['ticks'], -500);
        }

        $results[$display] = [
            'price'   => round($price, 2),
            'chg'     => round($chgPct, 2),
            'signal'  => $signal,
            'score'   => $score,
            'vol_r'   => round($volR, 2),
        ];
    }

    if (!is_dir(STORAGE)) mkdir(STORAGE, 0755, true);
    file_put_contents($logFile, json_encode($log));

    return ['tick' => $minute, 'ts' => $now, 'data' => $results];
}

/**
 * Build leaderboard from today's accumulated signal log.
 * Returns top buy/sell for today and last hour.
 */
function apiLeaders(): array
{
    $logFile = STORAGE . '/signals_' . date('Y-m-d') . '.json';
    if (!file_exists($logFile)) return ['error' => 'No signal data yet. Wait for first tick.'];

    $log  = json_decode(file_get_contents($logFile), true);
    $now  = time();
    $hour = $now - 3600;

    $todayBuy = $todaySell = $hourBuy = $hourSell = [];

    foreach ($log as $sym => $data) {
        $ticks     = $data['ticks'] ?? [];
        $name      = $data['name'] ?? $sym;
        $todayTicks = $ticks; // all ticks today
        $hourTicks  = array_values(array_filter($ticks, fn($t) => $t['ts'] >= $hour));

        if (!$todayTicks) continue;

        // Today counts
        $tBuys  = count(array_filter($todayTicks, fn($t) => $t['signal'] === 'Buy'));
        $tSells = count(array_filter($todayTicks, fn($t) => $t['signal'] === 'Sell'));
        $tTotal = count($todayTicks);
        $tAvgScore = round(array_sum(array_column($todayTicks, 'score')) / $tTotal, 1);
        $tLastPrice = end($todayTicks)['price'];
        $tFirstPrice = $todayTicks[0]['price'];
        $tPriceChg = $tFirstPrice > 0 ? round((($tLastPrice - $tFirstPrice) / $tFirstPrice) * 100, 2) : 0;
        // Streak: consecutive same signals at end
        $tStreak = 0; $tStreakSig = end($todayTicks)['signal'];
        for ($i = count($todayTicks)-1; $i >= 0; $i--) {
            if ($todayTicks[$i]['signal'] === $tStreakSig) $tStreak++;
            else break;
        }

        $entry = [
            'symbol'    => $sym,
            'name'      => $name,
            'price'     => $tLastPrice,
            'price_chg' => $tPriceChg,
            'ticks'     => $tTotal,
            'avg_score' => $tAvgScore,
            'streak'    => $tStreak,
            'streak_sig'=> $tStreakSig,
            'last_chg'  => end($todayTicks)['chg'],
        ];

        if ($tBuys > $tSells) {
            $entry['buy_count']  = $tBuys;
            $entry['sell_count'] = $tSells;
            $entry['dominance']  = round($tBuys / $tTotal * 100);
            $todayBuy[] = $entry;
        } elseif ($tSells > $tBuys) {
            $entry['buy_count']  = $tBuys;
            $entry['sell_count'] = $tSells;
            $entry['dominance']  = round($tSells / $tTotal * 100);
            $todaySell[] = $entry;
        }

        // Hour counts
        if ($hourTicks) {
            $hBuys  = count(array_filter($hourTicks, fn($t) => $t['signal'] === 'Buy'));
            $hSells = count(array_filter($hourTicks, fn($t) => $t['signal'] === 'Sell'));
            $hTotal = count($hourTicks);
            $hAvgScore = round(array_sum(array_column($hourTicks, 'score')) / $hTotal, 1);
            $hStreak = 0; $hStreakSig = end($hourTicks)['signal'];
            for ($i = count($hourTicks)-1; $i >= 0; $i--) {
                if ($hourTicks[$i]['signal'] === $hStreakSig) $hStreak++;
                else break;
            }
            $hEntry = array_merge($entry, [
                'buy_count'  => $hBuys,
                'sell_count' => $hSells,
                'avg_score'  => $hAvgScore,
                'ticks'      => $hTotal,
                'dominance'  => $hTotal > 0 ? round(max($hBuys,$hSells)/$hTotal*100) : 0,
                'streak'     => $hStreak,
                'streak_sig' => $hStreakSig,
            ]);
            if ($hBuys > $hSells)       $hourBuy[]  = $hEntry;
            elseif ($hSells > $hBuys)   $hourSell[] = $hEntry;
        }
    }

    // Sort: primary = count of dominant signal, secondary = streak, tertiary = avg_score
    $sorter = function($a, $b) use ($log) {
        $aCnt = max($a['buy_count'], $a['sell_count']);
        $bCnt = max($b['buy_count'], $b['sell_count']);
        if ($aCnt !== $bCnt) return $bCnt - $aCnt;
        if ($a['streak'] !== $b['streak']) return $b['streak'] - $a['streak'];
        return $b['avg_score'] <=> $a['avg_score'];
    };
    usort($todayBuy,  $sorter);
    usort($todaySell, $sorter);
    usort($hourBuy,   $sorter);
    usort($hourSell,  $sorter);

    // Total ticks tracked today
    $totalTicks = array_sum(array_map(fn($d) => count($d['ticks']), $log));

    return [
        'today_buy'   => array_slice($todayBuy,  0, 5),
        'today_sell'  => array_slice($todaySell, 0, 5),
        'hour_buy'    => array_slice($hourBuy,   0, 5),
        'hour_sell'   => array_slice($hourSell,  0, 5),
        'total_ticks' => $totalTicks,
        'date'        => date('Y-m-d'),
        'generated'   => date('H:i:s'),
    ];
}


// ══════════════════════════════════════════════════════════════
//  EOD REPORT FUNCTIONS
// ══════════════════════════════════════════════════════════════

function apiEodReport(string $date = ''): array
{
    if (!$date) $date = date('Y-m-d');
    $file = STORAGE . '/eod_signals_' . $date . '.json';
    if (!file_exists($file)) return ['date' => $date, 'signals' => [], 'summary' => null];
    $signals = json_decode(file_get_contents($file), true) ?? [];
    if (empty($signals)) return ['date' => $date, 'signals' => [], 'summary' => null];
    
    // Fetch current prices using multi-source fallback chain (NSE→Stooq→Yahoo)
    $prices = [];
    foreach ($signals as $sig) {
        $sym    = strtoupper(str_replace('.NS', '', $sig['symbol']));
        $nssSym = $sym . '.NS';
        $q      = yahooQuote($nssSym);
        if ($q && ($q['regularMarketPrice'] ?? 0) > 0) {
            $prices[$sym] = [
                'price' => $q['regularMarketPrice'],
                'prev'  => $q['regularMarketPreviousClose'] ?? $q['regularMarketPrice'],
            ];
        }
    }
    $hits = 0; $misses = 0; $pending = 0;
    foreach ($signals as &$sig) {
        $sym  = str_replace('.NS', '', $sig['symbol']);
        $live = $prices[$sym]['price'] ?? 0;
        $sig['current_price'] = round($live, 2);
        $sig['price_change_pct'] = $sig['entry_price'] > 0 ? round((($live - $sig['entry_price']) / $sig['entry_price']) * 100, 2) : 0;
        if ($live <= 0) { $pending++; $sig['status'] = 'pending'; continue; }
        $target = $sig['target_price']; $sl = $sig['stoploss']; $isBuy = strtolower($sig['signal']) === 'buy';
        if ($isBuy) {
            if ($live >= $target) { $sig['status'] = 'target_hit'; $hits++; }
            elseif ($sl > 0 && $live <= $sl) { $sig['status'] = 'sl_hit'; $misses++; }
            else { $sig['status'] = 'open'; $pending++; }
        } else {
            if ($live <= $target) { $sig['status'] = 'target_hit'; $hits++; }
            elseif ($sl > 0 && $live >= $sl) { $sig['status'] = 'sl_hit'; $misses++; }
            else { $sig['status'] = 'open'; $pending++; }
        }
    }
    unset($sig);
    $total = count($signals); $resolved = $hits + $misses;
    $hitPct = $resolved > 0 ? round($hits / $resolved * 100) : null;
    $summary = ['total' => $total, 'hits' => $hits, 'misses' => $misses, 'pending' => $pending, 'hit_pct' => $hitPct, 'date' => $date];
    return ['date' => $date, 'signals' => $signals, 'summary' => $summary];
}

function apiEodCheck(): array { return apiEodReport(); }

function apiNews(): array
{
    $cacheFile = STORAGE . '/news_cache.json';
    if (file_exists($cacheFile)) {
        $cached = json_decode(file_get_contents($cacheFile), true);
        if ($cached && (time() - ($cached['ts'] ?? 0)) < 600) return $cached;
    }

    $feeds = [
        ['url' => 'https://economictimes.indiatimes.com/markets/stocks/rssfeeds/2146842.cms', 'label' => 'Economic Times'],
        ['url' => 'https://www.moneycontrol.com/rss/MCtopnews.xml', 'label' => 'Moneycontrol'],
    ];

    $newsItems = [];
    foreach ($feeds as $feed) {
        $xml = httpGet($feed['url'], 10);
        if (!$xml) continue;
        // Strip namespace prefixes
        $xml = preg_replace('/[a-z0-9]+:[a-z0-9]+=/i', '=', $xml);
        $xml = preg_replace('/<([a-z0-9]+):([a-z0-9]+)/i', '<$2', $xml);
        $xml = preg_replace('/<\/([a-z0-9]+):([a-z0-9]+)/i', '</$2', $xml);

        libxml_use_internal_errors(true);
        $obj = simplexml_load_string($xml);
        if (!$obj) continue;

        $items = $obj->channel->item ?? [];
        foreach ($items as $item) {
            $title = strip_tags((string)($item->title ?? ''));
            $desc  = strip_tags((string)($item->description ?? ''));
            if (!$title) continue;

            // Simple impact heuristic
            $t = strtolower($title . ' ' . $desc);
            $impact = 'Neutral';
            if (preg_match('/surge|rally|rise|gain|bull|positive|profit|revenue|record|high|breakout|buy/i', $t)) $impact = 'Bullish';
            elseif (preg_match('/fall|drop|crash|loss|bear|decline|weak|concern|risk|sell|warning|cut/i', $t)) $impact = 'Bearish';

            // Extract stock mentions (CAPS words 2-15 chars)
            preg_match_all('/\b([A-Z]{2,15})\b/', $title, $m);
            $nseWords = ['NSE', 'BSE', 'IPO', 'FII', 'DII', 'RBI', 'SEBI', 'GDP', 'CPI', 'EMI', 'SBI', 'LIC', 'HDFC', 'ICICI'];
            $stocks = array_unique(array_filter($m[1] ?? [], fn($w) => strlen($w) >= 3 && !in_array($w, $nseWords)));

            $newsItems[] = [
                'headline'       => $title,
                'summary'        => $desc ? substr($desc, 0, 200) . '...' : 'Read full article for details.',
                'impact'         => $impact,
                'sector'         => 'Markets',
                'stocks_affected'=> array_values(array_slice($stocks, 0, 4)),
                'source'         => $feed['label'],
            ];
            if (count($newsItems) >= 16) break 2;
        }
    }

    // Fallback if no news fetched
    if (!$newsItems) {
        $newsItems = [
            ['headline' => 'Markets open for trading', 'summary' => 'Indian equity markets are open. Check NSE/BSE for live updates.', 'impact' => 'Neutral', 'sector' => 'Markets', 'stocks_affected' => [], 'source' => 'System'],
        ];
    }

    $result = ['news' => $newsItems, 'ts' => time(), 'source' => 'RSS (free)'];
    if (!is_dir(STORAGE)) mkdir(STORAGE, 0755, true);
    file_put_contents($cacheFile, json_encode($result));
    return $result;
}

// ══════════════════════════════════════════════════════════════
//  VIEWS (unchanged from v2 — full UI preserved)
// ══════════════════════════════════════════════════════════════

// ══════════════════════════════════════════════════════════════
//  NEW INDICATORS — ADX/DMI, Stochastic, OBV, Pivot Points
// ══════════════════════════════════════════════════════════════

/** ADX / DMI — trend strength (0-100). >25 = strong trend */
function adx(array $history, int $period = 14): array
{
    $n = count($history);
    if ($n < $period + 2) return ['adx' => null, 'plus_di' => null, 'minus_di' => null, 'trend_strength' => 'Weak'];
    $trs = $plus = $minus = [];
    for ($i = 1; $i < $n; $i++) {
        $h = $history[$i]['high']; $l = $history[$i]['low']; $pc = $history[$i-1]['close'];
        $ph = $history[$i-1]['high']; $pl = $history[$i-1]['low'];
        $trs[]  = max($h - $l, abs($h - $pc), abs($l - $pc));
        $upMove = $h - $ph; $dnMove = $pl - $l;
        $plus[]  = ($upMove > $dnMove && $upMove > 0) ? $upMove : 0;
        $minus[] = ($dnMove > $upMove && $dnMove > 0) ? $dnMove : 0;
    }
    // Wilder smoothing
    $atr = array_sum(array_slice($trs, 0, $period));
    $pdi = array_sum(array_slice($plus, 0, $period));
    $mdi = array_sum(array_slice($minus, 0, $period));
    $dxArr = [];
    for ($i = $period; $i < count($trs); $i++) {
        $atr = $atr - $atr / $period + $trs[$i];
        $pdi = $pdi - $pdi / $period + $plus[$i];
        $mdi = $mdi - $mdi / $period + $minus[$i];
        $pdiPct = $atr > 0 ? $pdi / $atr * 100 : 0;
        $mdiPct = $atr > 0 ? $mdi / $atr * 100 : 0;
        $sum    = $pdiPct + $mdiPct;
        $dxArr[] = $sum > 0 ? abs($pdiPct - $mdiPct) / $sum * 100 : 0;
    }
    if (empty($dxArr)) return ['adx' => null, 'plus_di' => null, 'minus_di' => null, 'trend_strength' => 'Weak'];
    $adxVal = array_sum($dxArr) / count($dxArr);
    $atrF = array_sum(array_slice($trs, -$period)) / $period;
    $pdiF = $atr > 0 ? $pdi / $atr * 100 : 0;
    $mdiF = $atr > 0 ? $mdi / $atr * 100 : 0;
    $strength = $adxVal >= 40 ? 'Very Strong' : ($adxVal >= 25 ? 'Strong' : ($adxVal >= 20 ? 'Moderate' : 'Weak'));
    return [
        'adx'           => round($adxVal, 1),
        'plus_di'       => round($pdiF, 1),
        'minus_di'      => round($mdiF, 1),
        'trend_strength'=> $strength,
        'direction'     => $pdiF > $mdiF ? 'Bullish' : 'Bearish',
    ];
}

/** Stochastic Oscillator %K and %D */
function stochastic(array $history, int $kPeriod = 14, int $dPeriod = 3): array
{
    $n = count($history);
    if ($n < $kPeriod) return ['k' => null, 'd' => null, 'signal' => 'N/A'];
    $kArr = [];
    for ($i = $kPeriod - 1; $i < $n; $i++) {
        $slice = array_slice($history, $i - $kPeriod + 1, $kPeriod);
        $hh    = max(array_column($slice, 'high'));
        $ll    = min(array_column($slice, 'low'));
        $range = $hh - $ll;
        $kArr[] = $range > 0 ? round(($history[$i]['close'] - $ll) / $range * 100, 2) : 50.0;
    }
    $kLast = end($kArr);
    $dLast = count($kArr) >= $dPeriod ? round(array_sum(array_slice($kArr, -$dPeriod)) / $dPeriod, 2) : $kLast;
    $signal = $kLast > 80 ? 'Overbought' : ($kLast < 20 ? 'Oversold' : ($kLast > $dLast ? 'Bullish' : 'Bearish'));
    return ['k' => round($kLast, 1), 'd' => round($dLast, 1), 'signal' => $signal];
}

/** On Balance Volume (OBV) — cumulative volume indicator */
function obv(array $history): array
{
    $n = count($history);
    if ($n < 2) return ['obv' => null, 'trend' => 'N/A'];
    $obvVal = 0;
    $vals   = [];
    for ($i = 1; $i < $n; $i++) {
        $c = $history[$i]['close']; $pc = $history[$i-1]['close']; $v = $history[$i]['volume'] ?? 0;
        if ($c > $pc)      $obvVal += $v;
        elseif ($c < $pc)  $obvVal -= $v;
        $vals[] = $obvVal;
    }
    $last5  = array_slice($vals, -5);
    $trend  = end($last5) > $last5[0] ? 'Rising (accumulation)' : 'Falling (distribution)';
    return ['obv' => $obvVal, 'trend' => $trend, 'last' => end($vals)];
}

/** Standard Pivot Points (daily) — PP, R1/R2/R3, S1/S2/S3 */
function pivotPoints(array $history): array
{
    if (empty($history)) return [];
    $prev = $history[count($history) - 2] ?? end($history);
    $h = $prev['high']; $l = $prev['low']; $c = $prev['close'];
    $pp = ($h + $l + $c) / 3;
    return [
        'PP' => round($pp, 2),
        'R1' => round(2 * $pp - $l, 2),
        'R2' => round($pp + ($h - $l), 2),
        'R3' => round($h + 2 * ($pp - $l), 2),
        'S1' => round(2 * $pp - $h, 2),
        'S2' => round($pp - ($h - $l), 2),
        'S3' => round($l - 2 * ($h - $pp), 2),
        // CPR — Central Pivot Range
        'BC' => round(($h + $l) / 2, 2),          // Bottom of CPR
        'TC' => round((2 * $pp) - ($h + $l) / 2, 2), // Top of CPR
    ];
}


// ══════════════════════════════════════════════════════════════
//  ADDITIONAL INDICATORS
// ══════════════════════════════════════════════════════════════

/** Williams %R — momentum oscillator, -100 to 0. Below -80 = oversold, above -20 = overbought */
function williamsR(array $history, int $period = 14): ?float
{
    $n = count($history);
    if ($n < $period) return null;
    $slice = array_slice($history, -$period);
    $hh    = max(array_column($slice, 'high'));
    $ll    = min(array_column($slice, 'low'));
    $close = end($history)['close'];
    return $hh - $ll > 0 ? round((($hh - $close) / ($hh - $ll)) * -100, 2) : -50.0;
}

/** CCI — Commodity Channel Index. Measures price deviation from average */
function cci(array $history, int $period = 20): ?float
{
    $n = count($history);
    if ($n < $period) return null;
    $slice = array_slice($history, -$period);
    $tp    = array_map(fn($r) => ($r['high'] + $r['low'] + $r['close']) / 3, $slice);
    $mean  = array_sum($tp) / $period;
    $mad   = array_sum(array_map(fn($v) => abs($v - $mean), $tp)) / $period;
    return $mad > 0 ? round(($tp[count($tp)-1] - $mean) / (0.015 * $mad), 2) : 0.0;
}

/** MFI — Money Flow Index. Volume-weighted RSI. 0-100, <20=oversold, >80=overbought */
function mfi(array $history, int $period = 14): ?float
{
    $n = count($history);
    if ($n < $period + 1) return null;
    $posFlow = $negFlow = 0.0;
    for ($i = $n - $period; $i < $n; $i++) {
        $tp   = ($history[$i]['high'] + $history[$i]['low'] + $history[$i]['close']) / 3;
        $tpp  = ($history[$i-1]['high'] + $history[$i-1]['low'] + $history[$i-1]['close']) / 3;
        $vol  = $history[$i]['volume'] ?? 0;
        $rmf  = $tp * $vol;
        if ($tp > $tpp)  $posFlow += $rmf;
        else             $negFlow += $rmf;
    }
    if ($negFlow == 0) return 100.0;
    return round(100 - 100 / (1 + $posFlow / $negFlow), 2);
}

/** Ichimoku Cloud — returns key lines */
function ichimoku(array $history): array
{
    $n = count($history);
    if ($n < 52) return ['tenkan' => null, 'kijun' => null, 'senkou_a' => null, 'senkou_b' => null, 'signal' => 'Insufficient data'];

    $midpoint = function(array $h, int $from, int $len) {
        $slice = array_slice($h, $from, $len);
        return (max(array_column($slice, 'high')) + min(array_column($slice, 'low'))) / 2;
    };

    $tenkan   = $midpoint($history, $n - 9,  9);   // Conversion line (9-period)
    $kijun    = $midpoint($history, $n - 26, 26);  // Base line (26-period)
    $senkou_a = round(($tenkan + $kijun) / 2, 2);  // Leading Span A
    $senkou_b = round($midpoint($history, $n - 52, 52), 2); // Leading Span B (52-period)
    $chikou   = end($history)['close'];             // Lagging span

    $price    = $chikou;
    $aboveCloud = $price > max($senkou_a, $senkou_b);
    $belowCloud = $price < min($senkou_a, $senkou_b);
    $bullishCloud = $senkou_a > $senkou_b;

    $signal = $aboveCloud && $tenkan > $kijun && $bullishCloud ? 'Strong Bullish'
            : ($aboveCloud ? 'Bullish'
            : ($belowCloud && $tenkan < $kijun && !$bullishCloud ? 'Strong Bearish'
            : ($belowCloud ? 'Bearish' : 'Neutral (in cloud)')));

    return [
        'tenkan'      => round($tenkan, 2),
        'kijun'       => round($kijun, 2),
        'senkou_a'    => $senkou_a,
        'senkou_b'    => $senkou_b,
        'chikou'      => round($chikou, 2),
        'signal'      => $signal,
        'above_cloud' => $aboveCloud,
        'below_cloud' => $belowCloud,
        'cloud_bullish'=> $bullishCloud,
    ];
}

/** Fibonacci retracement levels from recent swing high/low */
function fibonacci(array $history, int $lookback = 60): array
{
    $slice = array_slice($history, -min($lookback, count($history)));
    $high  = max(array_column($slice, 'high'));
    $low   = min(array_column($slice, 'low'));
    $diff  = $high - $low;
    return [
        'high'  => round($high, 2),
        'low'   => round($low, 2),
        '0'     => round($high, 2),
        '23.6'  => round($high - $diff * 0.236, 2),
        '38.2'  => round($high - $diff * 0.382, 2),
        '50'    => round($high - $diff * 0.500, 2),
        '61.8'  => round($high - $diff * 0.618, 2),
        '78.6'  => round($high - $diff * 0.786, 2),
        '100'   => round($low,  2),
        'ext_127'=> round($low  - $diff * 0.272, 2),
        'ext_161'=> round($low  - $diff * 0.618, 2),
    ];
}

/** 52W position — where is price in its yearly range (0-100%) */
function position52W(float $price, float $high52, float $low52): ?float
{
    $range = $high52 - $low52;
    return $range > 0 ? round(($price - $low52) / $range * 100, 1) : null;
}

/** Multi-timeframe signal — fetch weekly data and compare */
function multiTimeframe(string $symbol, float $price, array $dailyHistory): array
{
    // Get weekly data (3mo range, 1wk interval)
    $url     = "https://query2.finance.yahoo.com/v8/finance/chart/{$symbol}?range=1y&interval=1wk";
    $raw     = httpGet($url, 12);
    $weekly  = [];
    if ($raw) {
        $data = json_decode($raw, true);
        $r    = $data['chart']['result'][0] ?? null;
        if ($r) {
            $q   = $r['indicators']['quote'][0] ?? [];
            $n   = count($r['timestamp'] ?? []);
            for ($i = 0; $i < $n; $i++) {
                $c = $q['close'][$i] ?? null;
                $h = $q['high'][$i]  ?? null;
                $l = $q['low'][$i]   ?? null;
                $v = $q['volume'][$i]?? 0;
                if ($c !== null) $weekly[] = ['close'=>(float)$c,'high'=>(float)($h??$c),'low'=>(float)($l??$c),'volume'=>(int)$v];
            }
        }
    }
    if (count($weekly) < 20) {
        return ['daily' => 'N/A', 'weekly' => 'Insufficient data', 'aligned' => false];
    }
    $wCloses  = array_column($weekly, 'close');
    $wEma20   = ema($wCloses, 20);
    $wRsi     = rsi($wCloses);
    $wRsiLast = end(array_filter($wRsi, fn($v) => $v !== null)) ?: 50;
    $wEmaLast = end(array_filter($wEma20, fn($v) => $v !== null)) ?: $price;
    $wMacd    = macd($wCloses);
    $wMacdH   = end(array_filter($wMacd['hist'] ?? [], fn($v) => $v !== null)) ?: 0;

    // Daily signal
    $dCloses  = array_column($dailyHistory, 'close');
    $dEma20L  = end(array_filter(ema($dCloses, 20), fn($v) => $v !== null)) ?: $price;
    $dRsiArr  = rsi($dCloses);
    $dRsiLast = end(array_filter($dRsiArr, fn($v) => $v !== null)) ?: 50;

    $dailySig  = $price > $dEma20L && $dRsiLast > 50 ? 'Bullish' : ($price < $dEma20L && $dRsiLast < 50 ? 'Bearish' : 'Neutral');
    $weeklySig = $price > $wEmaLast && $wRsiLast > 50 && $wMacdH > 0 ? 'Bullish'
               : ($price < $wEmaLast && $wRsiLast < 50 && $wMacdH < 0 ? 'Bearish' : 'Neutral');

    $aligned = ($dailySig === $weeklySig && $dailySig !== 'Neutral');

    return [
        'daily'       => $dailySig,
        'weekly'      => $weeklySig,
        'aligned'     => $aligned,
        'weekly_ema20'=> round($wEmaLast, 2),
        'weekly_rsi'  => round($wRsiLast, 1),
        'weekly_macd' => $wMacdH > 0 ? 'Bullish' : 'Bearish',
        'note'        => $aligned
            ? "Daily and weekly both {$dailySig} — high-conviction signal"
            : "Daily {$dailySig} vs Weekly {$weeklySig} — signals not aligned, higher risk",
    ];
}

/** Volume spike detection — is today's volume unusually high? */
function volumeAnalysis(array $history): array
{
    $n = count($history);
    if ($n < 20) return ['ratio' => null, 'label' => 'N/A', 'spike' => false];
    $recent    = $history[$n-1];
    $past20vol = array_sum(array_column(array_slice($history, -21, 20), 'volume')) / 20;
    $ratio     = $past20vol > 0 ? round($recent['volume'] / $past20vol, 2) : 1.0;
    $spike     = $ratio >= 2.0;
    $label     = $ratio >= 3.0 ? '🔥 Huge spike ('.$ratio.'x avg)'
               : ($ratio >= 2.0 ? '📈 High volume ('.$ratio.'x avg)'
               : ($ratio >= 1.3 ? 'Above average ('.$ratio.'x)'
               : ($ratio < 0.7  ? 'Low volume ('.$ratio.'x)' : 'Normal ('.$ratio.'x)')));
    return ['ratio' => $ratio, 'label' => $label, 'spike' => $spike, 'today' => $recent['volume'], 'avg20' => round($past20vol)];
}

/** Score breakdown — returns each component contribution */
function scoreBreakdown(array $quote, array $history, array $indicators, array $adxData, array $stoch, array $obvData, ?float $wr, ?float $cciVal, ?float $mfiVal, array $ichimoku): array
{
    $price  = $quote['regularMarketPrice'] ?? 0;
    $chg    = $quote['regularMarketChangePercent'] ?? 0;
    $rsiVal = end(array_filter($indicators['rsi'], fn($v) => $v !== null)) ?: 50;
    $macdH  = end(array_filter($indicators['macd']['hist'], fn($v) => $v !== null)) ?: 0;
    $ema20  = end(array_filter($indicators['ema20'], fn($v) => $v !== null)) ?: $price;
    $ema50  = end(array_filter($indicators['ema50'], fn($v) => $v !== null)) ?: $price;
    $st     = $indicators['supertrend'];
    $vwap   = $indicators['vwap'];

    $components = [];

    $components[] = ['name'=>'Price vs EMA20',    'score'=> $price>$ema20  ?  1.5 : -1.5, 'detail'=> $price>$ema20  ? 'Above':'Below'];
    $components[] = ['name'=>'EMA20 vs EMA50',    'score'=> $ema20>$ema50  ?  1.2 : -1.2, 'detail'=> $ema20>$ema50  ? 'Golden Cross':'Death Cross'];
    $components[] = ['name'=>'RSI (14)',           'score'=> $rsiVal<30?2.0:($rsiVal>70?-2.0:($rsiVal>=50?0.8:-0.8)), 'detail'=> "{$rsiVal}"];
    $components[] = ['name'=>'MACD Histogram',    'score'=> $macdH>0?1.5:-1.5, 'detail'=> $macdH>0?'Positive':'Negative'];
    $components[] = ['name'=>'Supertrend',         'score'=> $st==='Bullish'?1.5:-1.5, 'detail'=> $st];
    $components[] = ['name'=>'VWAP',               'score'=> ($vwap>0&&$price>$vwap)?1.0:-1.0, 'detail'=> $price>$vwap?'Above':'Below'];
    $components[] = ['name'=>'Day Change%',        'score'=> min(max($chg*0.3,-1.5),1.5), 'detail'=> round($chg,2).'%'];
    $components[] = ['name'=>'ADX Trend',          'score'=> ($adxData['adx']??0)>=25?($adxData['direction']==='Bullish'?1.0:-1.0):0, 'detail'=> $adxData['adx']??'N/A'];
    $components[] = ['name'=>'Stochastic',         'score'=> ($stoch['k']??50)<20?1.5:(($stoch['k']??50)>80?-1.5:0), 'detail'=> $stoch['k']??'N/A'];
    $components[] = ['name'=>'OBV Trend',          'score'=> str_contains($obvData['trend']??'','accum')?1.0:-0.5, 'detail'=> $obvData['trend']??'N/A'];
    $components[] = ['name'=>"Williams %R",        'score'=> ($wr??-50)<-80?1.5:(($wr??-50)>-20?-1.5:0), 'detail'=> $wr??'N/A'];
    $components[] = ['name'=>'CCI',                'score'=> ($cciVal??0)<-100?1.0:(($cciVal??0)>100?-1.0:0), 'detail'=> $cciVal??'N/A'];
    $components[] = ['name'=>'MFI',                'score'=> ($mfiVal??50)<20?1.5:(($mfiVal??50)>80?-1.5:0.5), 'detail'=> $mfiVal??'N/A'];
    $components[] = ['name'=>'Ichimoku',           'score'=> str_contains($ichimoku['signal']??'','Bullish')?1.5:(str_contains($ichimoku['signal']??'','Bearish')?-1.5:0), 'detail'=> $ichimoku['signal']??'N/A'];

    $total = array_sum(array_column($components, 'score'));
    foreach ($components as &$c) {
        $c['score']  = round($c['score'], 2);
        $c['bull']   = $c['score'] > 0;
    }

    return ['components' => $components, 'total' => round($total, 2)];
}

// ── Extend generateSignal to use new indicators ───────────────
function generateSignalFull(array $quote, array $history, array $indicators): array
{
    // Start with base signal
    $base = generateSignal($quote, $history, $indicators);

    $price   = $quote['regularMarketPrice'] ?? 0;
    $adxData = adx($history);
    $stoch   = stochastic($history);
    $obvData = obv($history);

    $bull = $base['bullFactors'];
    $bear = $base['bearFactors'];
    $b = 0; $be = 0;

    // ADX/DMI
    if ($adxData['adx'] !== null) {
        if ($adxData['adx'] >= 25) {
            if ($adxData['direction'] === 'Bullish') { $b += 2; $bull[] = "ADX {$adxData['adx']} — Strong bullish trend (+DI > -DI)"; }
            else { $be += 2; $bear[] = "ADX {$adxData['adx']} — Strong bearish trend (-DI > +DI)"; }
        } else {
            $bear[] = "ADX {$adxData['adx']} — Weak/no trend ({$adxData['trend_strength']})";
        }
    }

    // Stochastic
    if ($stoch['k'] !== null) {
        if ($stoch['k'] < 20) { $b += 2; $bull[] = "Stochastic oversold at {$stoch['k']} — potential reversal up"; }
        elseif ($stoch['k'] > 80) { $be += 2; $bear[] = "Stochastic overbought at {$stoch['k']} — potential reversal down"; }
        elseif ($stoch['signal'] === 'Bullish') { $b++; $bull[] = "Stochastic K({$stoch['k']}) above D({$stoch['d']}) — bullish"; }
        else { $be++; $bear[] = "Stochastic K({$stoch['k']}) below D({$stoch['d']}) — bearish"; }
    }

    // OBV
    if ($obvData['trend']) {
        if (str_contains($obvData['trend'], 'accumulation')) { $b++; $bull[] = "OBV: " . $obvData['trend']; }
        else { $be++; $bear[] = "OBV: " . $obvData['trend']; }
    }

    // Recompute total
    $totalBull = substr_count(implode(',', array_keys(array_filter(['b'=>count($bull)]))), 'b');
    $newBull = count($bull); $newBear = count($bear);
    $total   = $newBull + $newBear ?: 1;
    $conf    = (int)round(max($newBull, $newBear) / $total * 100);

    $signal = ($newBull > $newBear + 1) ? 'Buy' : (($newBear > $newBull + 1) ? 'Sell' : $base['signal']);
    $trend  = $signal === 'Buy' ? 'Bullish' : ($signal === 'Sell' ? 'Bearish' : $base['trend']);
    $verdict = $base['verdict']; // keep existing verdict

    return array_merge($base, [
        'signal'      => $signal,
        'trend'       => $trend,
        'confidence'  => $conf,
        'bullFactors' => $bull,
        'bearFactors' => $bear,
        'adx'         => $adxData,
        'stoch'       => $stoch,
        'obv'         => $obvData,
    ]);
}

// ── Intraday candles API ──────────────────────────────────────
function apiIntraday(string $symbol, string $interval = '5m'): array
{
    if (!$symbol) return ['error' => 'No symbol'];
    $range = in_array($interval, ['1h']) ? '5d' : '1d';
    $url   = "https://query2.finance.yahoo.com/v8/finance/chart/{$symbol}?range={$range}&interval={$interval}";
    $raw   = httpGet($url, 15);
    if (!$raw) return ['error' => 'Could not fetch intraday data'];
    $data  = json_decode($raw, true);
    $result = $data['chart']['result'][0] ?? null;
    if (!$result) return ['error' => 'No intraday data'];

    $timestamps = $result['timestamp'] ?? [];
    $q = $result['indicators']['quote'][0] ?? [];
    $candles = [];
    foreach ($timestamps as $i => $ts) {
        $c = $q['close'][$i] ?? null;
        $o = $q['open'][$i]  ?? null;
        $h = $q['high'][$i]  ?? null;
        $l = $q['low'][$i]   ?? null;
        $v = $q['volume'][$i]?? null;
        if ($c === null) continue;
        $candles[] = [
            't' => $ts,
            'o' => round((float)$o, 2),
            'h' => round((float)$h, 2),
            'l' => round((float)$l, 2),
            'c' => round((float)$c, 2),
            'v' => (int)$v,
        ];
    }
    return ['symbol' => $symbol, 'interval' => $interval, 'candles' => $candles, 'count' => count($candles)];
}

// ── Pivot points API ──────────────────────────────────────────
function apiPivots(string $symbol): array
{
    if (!$symbol) return ['error' => 'No symbol'];
    $history = yahooHistory($symbol, 5);
    if (count($history) < 2) return ['error' => 'Not enough data'];
    $pivots = pivotPoints($history);
    return ['symbol' => $symbol, 'pivots' => $pivots, 'computed_from' => 'Previous day OHLC'];
}

// ── Custom watchlist helpers ──────────────────────────────────
function getActiveWatchlist(): array
{
    if (file_exists(WL_FILE)) {
        $custom = json_decode(file_get_contents(WL_FILE), true);
        if (!empty($custom)) return $custom;
    }
    // Default: top 5 well-known NSE stocks for fast/reliable loading
    return ['RELIANCE.NS', 'TCS.NS', 'HDFCBANK.NS', 'INFY.NS', 'ICICIBANK.NS'];
}

/**
 * Paginated watchlist — handles 200+ stocks efficiently.
 *
 * Strategy:
 *  1. Bulk-fetch ALL quotes in parallel (4 chunks of 50, ~3s total)
 *  2. Filter by sector or search term
 *  3. Run technical analysis only on the 20 stocks for this page
 *  4. History fetched in parallel for page stocks only
 *  5. Full list cached 5 min; per-symbol history cached 6 hours
 */
function apiWatchlistPage(int $page = 1, string $sector = '', string $search = ''): array
{
    $perPage  = 20;
    $allSyms  = getActiveWatchlist();

    // ── Step 1: check if browser already pushed quotes via /api/proxy/quotes ──
    // This is the primary path when server-side sources (Stooq/Yahoo/NSE) are IP-blocked.
    $bulkCache = STORAGE . '/bulk_quotes.json';
    $browserQuotes = [];
    if (file_exists($bulkCache) && (time() - filemtime($bulkCache)) < 300) {
        $cached = json_decode(file_get_contents($bulkCache), true) ?? [];
        if (count($cached) >= 3) {
            $browserQuotes = $cached;
        }
    }

    // ── Step 2: if no valid browser cache, try server-side fetch ──
    $allQuotes = !empty($browserQuotes) ? $browserQuotes : yahooQuoteBulk($allSyms);

    // ── Step 2: filter by sector ────────────────────────────────
    if ($sector && isset(SECTOR_MAP[$sector])) {
        $sectorSyms = array_map(fn($s) => $s . '.NS', SECTOR_MAP[$sector]);
        $allSyms    = array_values(array_filter($allSyms, fn($s) => in_array($s, $sectorSyms)));
    }

    // ── Step 3: filter by search ────────────────────────────────
    if ($search) {
        $allSyms = array_values(array_filter($allSyms, function($s) use ($search, $allQuotes) {
            if (str_contains(strtoupper($s), $search)) return true;
            $name = strtoupper($allQuotes[$s]['shortName'] ?? '');
            return str_contains($name, $search);
        }));
    }

    $totalSyms  = count($allSyms);
    $totalPages = (int)ceil($totalSyms / $perPage);
    $page       = min($page, max(1, $totalPages));
    $pageSyms   = array_slice($allSyms, ($page - 1) * $perPage, $perPage);

    if (empty($pageSyms)) {
        return ['stocks'=>[], 'page'=>$page, 'total_pages'=>0, 'total_stocks'=>0,
                'per_page'=>$perPage, 'sector'=>$sector, 'search'=>$search, 'error'=>'No stocks found'];
    }

    // ── Step 4: parallel history fetch for page stocks only ─────
    $mh = curl_multi_init();
    $hHandles = [];
    foreach ($pageSyms as $sym) {
        $cacheFile = STORAGE . '/hist_' . preg_replace('/[^A-Z0-9]/', '_', strtoupper($sym)) . '.json';
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 21600) continue; // already cached
        $period2 = time();
        $period1 = $period2 - (90 * 86400);
        $url = 'https://query2.finance.yahoo.com/v8/finance/chart/' . urlencode($sym)
             . '?period1=' . $period1 . '&period2=' . $period2 . '&interval=1d';
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 20,
            CURLOPT_CONNECTTIMEOUT => 8,    CURLOPT_ENCODING => 'gzip',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Accept: application/json', 'Referer: https://finance.yahoo.com/',
            ],
        ]);
        curl_multi_add_handle($mh, $ch);
        $hHandles[$sym] = $ch;
    }
    if (!empty($hHandles)) {
        $active = null;
        do { curl_multi_exec($mh, $active); curl_multi_select($mh, 0.3); } while ($active);
        foreach ($hHandles as $sym => $ch) {
            $raw = curl_multi_getcontent($ch);
            curl_multi_remove_handle($mh, $ch); curl_close($ch);
            if (!$raw) continue;
            $data  = json_decode($raw, true);
            $chart = $data['chart']['result'][0] ?? null;
            if (!$chart) continue;
            $ts   = $chart['timestamp'] ?? [];
            $ohlcv= $chart['indicators']['quote'][0] ?? [];
            $rows = [];
            foreach ($ts as $i => $t) {
                $c = $ohlcv['close'][$i] ?? null;
                if ($c === null) continue;
                $rows[] = ['date'=>date('Y-m-d',$t),'open'=>round($ohlcv['open'][$i]??$c,2),
                           'high'=>round($ohlcv['high'][$i]??$c,2),'low'=>round($ohlcv['low'][$i]??$c,2),
                           'close'=>round($c,2),'volume'=>$ohlcv['volume'][$i]??0];
            }
            if (!empty($rows)) {
                $cf = STORAGE . '/hist_' . preg_replace('/[^A-Z0-9]/','_',strtoupper($sym)) . '.json';
                file_put_contents($cf, json_encode($rows));
            }
        }
    }
    curl_multi_close($mh);

    // ── Step 5: analyse page stocks ─────────────────────────────
    $stocks = [];
    $skippedNoQuote = 0;
    foreach ($pageSyms as $sym) {
        $quote = $allQuotes[$sym] ?? null;
        if (!$quote) { $skippedNoQuote++; continue; }
        try {
            $history = yahooHistory($sym, 90);
            // Use whatever history we have; skip only if truly empty
            if (count($history) < 5) {
                // Still show the stock with price data, minimal indicators
                $price = (float)($quote['regularMarketPrice'] ?? 0);
                $chg   = (float)($quote['regularMarketChangePercent'] ?? 0);
                $stocks[] = [
                    'symbol'        => $sym,
                    'name'          => $quote['shortName'] ?? $sym,
                    'price'         => $price,
                    'change_pct'    => round($chg, 2),
                    'change_5d'     => 0,
                    'momentum_score'=> 0,
                    'signal'        => 'Hold',
                    'confidence'    => 0,
                    'trend'         => 'N/A',
                    'direction'     => 'flat',
                    'rsi'           => 50,
                    'supertrend'    => 'N/A',
                    'ema_signal'    => null,
                    'macd_signal'   => 'N/A',
                    'adx'           => null,
                    'adx_strength'  => null,
                    'adx_direction' => null,
                    'stoch_k'       => 50,
                    'stoch_d'       => 50,
                    'stoch_signal'  => 'N/A',
                    'obv_trend'     => null,
                    'vol_ratio'     => 1,
                    'vol_label'     => 'N/A',
                    'vol_surge'     => false,
                    'pattern'       => '',
                    'target'        => round($price * 1.03, 2),
                    'stoploss'      => round($price * 0.97, 2),
                    'position_52w'  => null,
                    'sector'        => $quote['sector'] ?? null,
                    '52w_high'      => round($quote['fiftyTwoWeekHigh'] ?? 0, 2),
                    '52w_low'       => round($quote['fiftyTwoWeekLow'] ?? 0, 2),
                    'bull_factors'  => [],
                    'bear_factors'  => [],
                ];
                continue;
            }

            $closePrices = array_column($history, 'close');
            $ema20       = ema($closePrices, 20);
            $ema50       = ema($closePrices, 50);
            $rsiArr      = rsi($closePrices);
            $macdArr     = macd($closePrices);
            $bbArr       = bollingerBands($closePrices);
            $stSuper     = supertrend($history);
            $vwap        = vwap($history);
            $atrVal      = atr($history);
            $adxData     = adx($history);
            $stoch       = stochastic($history);
            $obvData     = obv($history);
            $candlePats  = candlestickPatterns($history);

            $indicators = [
                'ema20'=>$ema20,'ema50'=>$ema50,'rsi'=>$rsiArr,
                'macd'=>$macdArr,'bb'=>$bbArr,'supertrend'=>$stSuper,
                'vwap'=>$vwap,'atr'=>$atrVal,
            ];
            $sig  = generateSignalFull($quote, $history, $indicators);
            $mom  = momentumScore($quote, $history, $indicators);
            $volA = volumeAnalysis($history);

            $price    = (float)($quote['regularMarketPrice'] ?? 0);
            $chg      = (float)($quote['regularMarketChangePercent'] ?? 0);
            $chg5d    = change5d($history);
            $rsiLast  = end(array_filter($rsiArr, fn($v) => $v !== null)) ?: 50;
            $topPat   = !empty($candlePats) ? $candlePats[0]['name'] : '';
            $atrV     = $atrVal ?? $price * 0.015;
            $target   = $sig['signal'] === 'Buy'  ? round($price + $atrV * 2, 2) : round($price - $atrV * 2, 2);
            $sl       = $sig['signal'] === 'Buy'  ? round($price - $atrV,     2) : round($price + $atrV,     2);
            $pos52w   = position52W($price, (float)($quote['fiftyTwoWeekHigh']??0), (float)($quote['fiftyTwoWeekLow']??0));

            $stocks[] = [
                'symbol'        => $sym,
                'name'          => $quote['shortName'] ?? $sym,
                'price'         => $price,
                'change_pct'    => round($chg, 2),
                'change_5d'     => $chg5d,
                'momentum_score'=> $mom['score'],
                'signal'        => $sig['signal'],
                'confidence'    => $sig['confidence'],
                'trend'         => $sig['trend'],
                'direction'     => $mom['score'] >= 15 ? 'rising' : ($mom['score'] <= -15 ? 'falling' : 'flat'),
                'rsi'           => round($rsiLast, 1),
                'supertrend'    => $stSuper,
                'ema_signal'    => ($ema20 && $ema50) ? (end(array_filter($ema20,fn($v)=>$v!==null)) > end(array_filter($ema50,fn($v)=>$v!==null)) ? 'Golden' : 'Death') : null,
                'macd_signal'   => end(array_filter($macdArr['hist'] ?? [], fn($v) => $v !== null)) > 0 ? 'Bullish' : 'Bearish',
                'adx'           => $adxData['adx'],
                'adx_strength'  => $adxData['trend_strength'] ?? null,
                'adx_direction' => $adxData['direction'] ?? null,
                'stoch_k'       => $stoch['k'],
                'stoch_d'       => $stoch['d'],
                'stoch_signal'  => $stoch['signal'],
                'obv_trend'     => $obvData['trend'] ?? null,
                'vol_ratio'     => $volA['ratio'],
                'vol_label'     => $volA['label'],
                'vol_surge'     => $volA['spike'],
                'pattern'       => $topPat,
                'target'        => $target,
                'stoploss'      => $sl,
                'position_52w'  => $pos52w,
                'sector'        => $quote['sector'] ?? null,
                '52w_high'      => round($quote['fiftyTwoWeekHigh'] ?? 0, 2),
                '52w_low'       => round($quote['fiftyTwoWeekLow'] ?? 0, 2),
                'bull_factors'  => $sig['bullFactors'] ?? [],
                'bear_factors'  => $sig['bearFactors'] ?? [],
            ];
        } catch (\Throwable $e) {
            continue;
        }
    }

    // Sort: Buy first by momentum, then Sells
    usort($stocks, fn($a,$b) => ($b['signal']==='Buy'?1:0) - ($a['signal']==='Buy'?1:0) ?: $b['momentum_score'] <=> $a['momentum_score']);

    $buys  = array_values(array_filter($stocks, fn($s) => $s['signal'] === 'Buy'));
    $sells = array_values(array_filter($stocks, fn($s) => $s['signal'] !== 'Buy'));
    $mood  = count($buys) / max(1, count($stocks)) >= 0.6 ? 'Bullish'
           : (count($buys) / max(1, count($stocks)) <= 0.4 ? 'Bearish' : 'Mixed');

    return [
        'stocks'          => $stocks,
        'buy_list'        => $buys,
        'sell_list'       => $sells,
        'market_mood'     => $mood,
        'page'            => $page,
        'total_pages'     => $totalPages,
        'total_stocks'    => $totalSyms,
        'per_page'        => $perPage,
        'sector'          => $sector,
        'search'          => $search,
        'ts'              => time(),
        'quotes_fetched'  => count($allQuotes),
        'skipped_no_quote'=> $skippedNoQuote ?? 0,
        'warning'         => empty($allQuotes) ? 'Could not fetch quotes from any source (NSE India, Stooq, or Yahoo Finance). Visit /api/debug/datasource to diagnose which sources are reachable from your server.' : (count($allQuotes) < 10 ? 'Partial data: only ' . count($allQuotes) . ' quotes fetched. Some stocks may be missing.' : null),
    ];
}

// ── Price alerts checker ──────────────────────────────────────
function checkAlerts(): array
{
    if (!file_exists(ALERT_FILE)) return ['triggered' => []];
    $alerts    = json_decode(file_get_contents(ALERT_FILE), true) ?? [];
    $triggered = [];
    $changed   = false;
    foreach ($alerts as &$a) {
        if ($a['triggered']) continue;
        $q = yahooQuote($a['symbol'] . (str_ends_with($a['symbol'],'.NS')?'':'.NS'));
        if (!$q) continue;
        $price = $q['regularMarketPrice'] ?? 0;
        $hit = ($a['condition'] === 'above' && $price >= $a['price'])
            || ($a['condition'] === 'below' && $price <= $a['price']);
        if ($hit) {
            $a['triggered'] = true; $a['triggered_at'] = time(); $a['triggered_price'] = $price;
            $triggered[] = $a;
            $changed = true;
        }
    }
    if ($changed) file_put_contents(ALERT_FILE, json_encode($alerts));
    return ['triggered' => $triggered, 'total' => count($alerts), 'pending' => count(array_filter($alerts, fn($a)=>!$a['triggered']))];
}

function loginPage(string $appName, string $err): void { ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login — <?= htmlspecialchars($appName) ?></title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{min-height:100vh;background:#0b0e1a;display:flex;align-items:center;justify-content:center;font-family:'Segoe UI',system-ui,sans-serif;background-image:radial-gradient(ellipse at 20% 50%,rgba(0,180,255,.07),transparent 60%),radial-gradient(ellipse at 80% 20%,rgba(80,0,255,.07),transparent 60%)}
.card{background:#131728;border:1px solid rgba(255,255,255,.08);border-radius:20px;padding:48px 40px;width:100%;max-width:420px;box-shadow:0 32px 80px rgba(0,0,0,.6)}
.logo{display:flex;align-items:center;gap:12px;margin-bottom:32px}
.logo-icon{width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#00c6ff,#0072ff);display:flex;align-items:center;justify-content:center;font-size:22px}
.logo-text{font-size:1.2rem;font-weight:700;color:#fff}
h2{font-size:1.5rem;color:#fff;margin-bottom:6px}
.sub{font-size:.875rem;color:#6b7280;margin-bottom:28px}
label{display:block;font-size:.8rem;color:#9ca3af;margin-bottom:6px;font-weight:500;letter-spacing:.5px;text-transform:uppercase}
input{width:100%;padding:12px 16px;background:#1e2235;border:1px solid rgba(255,255,255,.1);border-radius:10px;color:#fff;font-size:.95rem;outline:none;margin-bottom:18px;transition:border-color .2s}
input:focus{border-color:#0072ff}
.btn{width:100%;padding:13px;background:linear-gradient(135deg,#0072ff,#00c6ff);border:none;border-radius:10px;color:#fff;font-size:1rem;font-weight:600;cursor:pointer}
.err{background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.3);color:#f87171;padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:.875rem}
.hint{margin-top:20px;text-align:center;font-size:.78rem;color:#4b5563}
.free-badge{margin-top:12px;text-align:center;font-size:.72rem;color:#10b981;background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);padding:6px 12px;border-radius:20px}
</style>
</head>
<body>
<div class="card">
  <div class="logo"><div class="logo-icon">📈</div><div><div class="logo-text"><?= htmlspecialchars($appName) ?></div></div></div>
  <h2>Welcome back</h2>
  <p class="sub">Sign in to your trading dashboard</p>
  <?php if ($err): ?><div class="err">⚠ <?= htmlspecialchars($err) ?></div><?php endif; ?>
  <form method="POST">
    <label>Username</label><input type="text" name="u" required autocomplete="username">
    <label>Password</label><input type="password" name="p" required autocomplete="current-password">
    <button class="btn">Sign In →</button>
  </form>
  <div class="free-badge">✅ Powered by Yahoo Finance — No API key required</div>
  <p class="hint">Educational purposes only. Not financial advice.</p>
</div>
</body>
</html>
<?php }

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
    <button class="btn btn-outline" onclick="clearYahooCache()" style="padding:5px 12px;font-size:12px;color:var(--orange);border-color:var(--orange)" id="clearCacheBtn" title="Clear Yahoo Finance auth cache and reload">🗑️ Clear Cache</button>
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
      <p>Enter any NSE symbol for technical + fundamental analysis using real Yahoo Finance data</p>
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
        ✅ Real data from Yahoo Finance · No API key needed · EMA, RSI, MACD, BB, Supertrend
      </div>
    </div>
    <div class="analysis-result" id="analyzeResult">
      <div class="result-placeholder">
        <div class="icon">🔬</div>
        <div style="font-size:14px;font-weight:600;color:var(--muted2);margin-bottom:6px">Stock Analysis</div>
        <div style="font-size:12px">Enter a symbol and click Analyze<br>for full technical + fundamental breakdown<br>using real Yahoo Finance data</div>
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
    </div>
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
    if(!symbols.length) symbols=['RELIANCE.NS','TCS.NS','HDFCBANK.NS','INFY.NS','ICICIBANK.NS'];

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
async function fetchQuotesDirect(symbols){
  const allQuotes=[];
  const FIELDS='regularMarketPrice,regularMarketChange,regularMarketChangePercent,regularMarketVolume,averageDailyVolume3Month,regularMarketDayHigh,regularMarketDayLow,regularMarketPreviousClose,regularMarketOpen,fiftyTwoWeekHigh,fiftyTwoWeekLow,shortName,longName';

  // ── Source 1: Yahoo Finance v7 no-auth endpoint (often works from browsers) ──
  for(let i=0;i<symbols.length;i+=10){
    const chunk=symbols.slice(i,i+10);
    const syms=chunk.join(',');
    let got=false;
    // v7 endpoint sometimes bypasses auth requirements
    for(const url of [
      `https://query1.finance.yahoo.com/v7/finance/quote?symbols=${encodeURIComponent(syms)}&fields=${FIELDS}`,
      `https://query2.finance.yahoo.com/v7/finance/quote?symbols=${encodeURIComponent(syms)}&fields=${FIELDS}`,
      `https://query1.finance.yahoo.com/v8/finance/quote?symbols=${encodeURIComponent(syms)}&fields=${FIELDS}&lang=en-US&region=IN`,
    ]){
      try{
        const r=await fetch(url,{headers:{'Accept':'application/json','User-Agent':'Mozilla/5.0'}});
        if(r.ok){
          const j=await r.json();
          const results=(j?.quoteResponse?.result||j?.finance?.result||[]);
          const valid=results.filter(q=>q.regularMarketPrice>0);
          if(valid.length){valid.forEach(q=>allQuotes.push(q));got=true;break;}
        }
      }catch(e){}
    }
    if(got) continue;

    // ── Source 2: Groww public API (Indian broker, no CORS issues) ──
    for(const sym of chunk){
      const base=sym.replace('.NS','').replace('.BO','');
      try{
        const r=await fetch(`https://groww.in/v1/api/stocks_data/v1/company/search?q=${encodeURIComponent(base)}&page=0&size=1`,
          {headers:{'Accept':'application/json'}});
        if(r.ok){
          const j=await r.json();
          const s=j?.stocks?.[0];
          if(s&&s.ltp>0){
            allQuotes.push({
              symbol:sym,
              shortName:s.companyName||base,longName:s.companyName||base,
              regularMarketPrice:+s.ltp,
              regularMarketChange:+(s.dayChange||0),
              regularMarketChangePercent:+(s.dayChangePerc||0),
              regularMarketPreviousClose:+(s.previousClose||s.ltp),
              regularMarketOpen:+(s.open||s.ltp),
              regularMarketDayHigh:+(s.high||s.ltp),
              regularMarketDayLow:+(s.low||s.ltp),
              regularMarketVolume:+(s.totalTradedVolume||0),
              averageDailyVolume3Month:+(s.totalTradedVolume||0),
              fiftyTwoWeekHigh:+(s['52WeekHigh']||s.ltp),
              fiftyTwoWeekLow:+(s['52WeekLow']||s.ltp),
              _source:'groww'
            });
          }
        }
      }catch(e){}
      await new Promise(r=>setTimeout(r,80));
    }
  }
  return allQuotes;
}


// Legacy alias kept for other callers (EOD report, etc.)
async function browserFetchQuotes(symbols){
  return fetchQuotesDirect(symbols);
}

// ── Browser-side history fetcher ──────────────────────────────────
async function browserFetchHistory(yahooSym){
  const p2=Math.floor(Date.now()/1000);
  const p1=p2-(90*86400);
  const base=yahooSym.replace('.NS','').replace('.BO','');

  // Try Yahoo Finance direct
  for(const host of ['query1','query2']){
    try{
      const r=await fetch(`https://${host}.finance.yahoo.com/v8/finance/chart/${encodeURIComponent(yahooSym)}?period1=${p1}&period2=${p2}&interval=1d`);
      if(r.ok){const j=await r.json();const rows=parseYahooChart(j);if(rows.length) return rows;}
    }catch(e){}
  }

  // Try Groww candle API
  try{
    // First get the groww slug for this symbol
    const sr=await fetch(`https://groww.in/v1/api/stocks_data/v1/company/search?q=${encodeURIComponent(base)}&page=0&size=1`);
    if(sr.ok){
      const sj=await sr.json();
      const slug=sj?.stocks?.[0]?.searchId||sj?.stocks?.[0]?.slug;
      if(slug){
        const cr=await fetch(`https://groww.in/v1/api/charting_service/v2/chart/exchange/NSE/segment/CASH/${encodeURIComponent(slug)}?startTimeInMillis=${p1*1000}&endTimeInMillis=${p2*1000}&intervalInMinutes=1440`);
        if(cr.ok){
          const cj=await cr.json();
          const candles=cj?.candles||cj?.data?.candles||[];
          if(candles.length){
            return candles.map(c=>({
              date:new Date(c[0]).toISOString().slice(0,10),
              open:+c[1].toFixed(2),high:+c[2].toFixed(2),
              low:+c[3].toFixed(2),close:+c[4].toFixed(2),
              volume:c[5]||0
            })).filter(r=>r.close>0);
          }
        }
      }
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
    if(!list.length) return `<div style="padding:20px;color:var(--muted);font-size:13px">No ${title.includes('BUY')?'buy':'sell'} signals right now — stocks may be in a neutral/hold zone, or Yahoo Finance data is still loading. Try refreshing.</div>`;
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
    <div style="padding:8px;font-size:10px;color:var(--muted)">Score = Price×Volume + RSI + MACD + EMA + ADX + Supertrend · Yahoo Finance (free) · Educational only</div>`;
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
    <div>Fetching <strong>${escHtml(sym)}</strong> data from Yahoo Finance…</div>
    <div style="font-size:11px;color:var(--muted)">Browser fetching directly (bypasses server IP blocks)</div>
  </div>`;

  try{
    const yahooSym=sym.endsWith('.NS')?sym:sym+'.NS';

    // ── Step 1: browser fetches quote ──────────────────────────
    let quote=null;
    const fields='regularMarketPrice,regularMarketChange,regularMarketChangePercent,'
      +'regularMarketVolume,averageDailyVolume3Month,fiftyTwoWeekHigh,fiftyTwoWeekLow,'
      +'trailingPE,priceToBook,marketCap,shortName,longName,sector,industry,'
      +'returnOnEquity,debtToEquity,regularMarketDayHigh,regularMarketDayLow,'
      +'regularMarketPreviousClose,regularMarketOpen';
    for(const host of ['query1','query2']){
      try{
        const r=await fetch(`https://${host}.finance.yahoo.com/v8/finance/quote?symbols=${encodeURIComponent(yahooSym)}&fields=${fields}&lang=en-US&region=IN`);
        if(r.ok){const j=await r.json();quote=j?.quoteResponse?.result?.[0]||null;if(quote)break;}
      }catch(e){}
    }
    if(!quote){
      el.innerHTML=`<div class="err-box"><strong>Analysis failed</strong><br>Could not fetch quote for <strong>${escHtml(sym)}</strong> from Yahoo Finance.<br><small>Check that the symbol is correct (e.g. TCS, RELIANCE, INFY) and your internet connection.</small></div>`;
      return;
    }

    el.innerHTML=`<div class="loading-card"><div class="spin"></div>
      <div>Fetching 90-day price history for <strong>${escHtml(sym)}</strong>…</div></div>`;

    // ── Step 2: browser fetches historical OHLCV ───────────────
    const rows=await browserFetchHistory(yahooSym);

    el.innerHTML=`<div class="loading-card"><div class="spin"></div>
      <div>Running technical analysis (RSI, MACD, EMA, Supertrend…)</div></div>`;

    // ── Step 3: push to PHP for TA, get full analysis back ─────
    const r=await fetch(apiUrl('api/proxy/analyze'),{
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body:JSON.stringify({symbol:sym,quote:quote,rows:rows})
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
      Data: Yahoo Finance (free) · ${(sb.components||[]).length} indicators computed · Educational only · ${new Date().toLocaleString('en-IN',{timeZone:'Asia/Kolkata'})}
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
    // Map interval to Yahoo Finance params
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
      if(cl){cl.innerHTML='<span style="color:var(--muted)">No intraday data available from Yahoo Finance</span>';cl.style.display='flex';}
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
let tickTimer=null, leaderLoaded=false, tickCount=0;
const TICK_INTERVAL=60000; // 1 minute

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
        ${leaderCard(d.hour_buy,  '📈 Top Buy This Hour',  '🟢', 'buy',  'var(--green)')}
        ${leaderCard(d.hour_sell, '📉 Top Sell This Hour', '🔴', 'sell', 'var(--red)')}
      </div>
    </div>

    <div>
      <div style="font-size:11px;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);margin-bottom:10px;padding-bottom:6px;border-bottom:1px solid var(--border)">
        📅 TODAY — Since Market Open
      </div>
      <div class="leader-grid">
        ${leaderCard(d.today_buy,  '🏆 Top Buy Today',  '🥇', 'buy',  'var(--green)')}
        ${leaderCard(d.today_sell, '🏆 Top Sell Today', '🏴', 'sell', 'var(--red)')}
      </div>
    </div>

    <div style="font-size:11px;color:var(--muted);margin-top:14px;padding:10px;background:rgba(255,255,255,.02);border-radius:8px;line-height:1.7">
      💡 <strong>How it works:</strong> Every minute, each stock gets a Buy/Sell/Hold signal based on RSI, EMA20/50, MACD, Supertrend + volume. 
      The stock with the most Buy signals in the last hour = <strong style="color:var(--green)">Top Buy This Hour</strong>. 
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

async function loadEodReport(date){
  eodLoaded=true;
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
</body>
</html>
<?php }
