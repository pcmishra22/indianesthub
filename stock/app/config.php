<?php
declare(strict_types=1);
/**
 * config.php — bootstrap: constants, .env loading, session, base-path resolution.
 * No output, no routing decisions — just sets up everything later files depend on.
 */

// ─── Paths ────────────────────────────────────────────────────
define('BASE',       dirname(__DIR__));
define('STORAGE',    BASE . '/storage');
define('WL_FILE',    STORAGE . '/watchlist.json');
define('ALERT_FILE', STORAGE . '/alerts.json');

// ─── Load .env ────────────────────────────────────────────────
// Moved up here (was previously below the constant defines further down),
// because several constants below now read via getenv() — if .env loads
// after those defines run, getenv() always sees nothing and every value
// silently falls back to its default, ignoring whatever is in .env.
foreach (file(BASE . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
    $line = trim($line);
    if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) continue;
    [$k, $v] = explode('=', $line, 2);
    putenv(trim($k) . '=' . trim(trim($v), '"\''));
}

// ─── Prakash Recommendation settings ─────────────────────────
// Intraday target as a percentage move from entry price (both Buy and Sell).
// Override in .env as PRAKASH_TARGET_PCT=1.5 etc.
define('PRAKASH_TARGET_PCT', (float)(getenv('PRAKASH_TARGET_PCT') ?: 1.0));
// Minimum % change swing since the previous refresh to flag a stock as a
// "fast mover" candidate, even if its rank barely moved.
define('PRAKASH_FAST_MOVER_THRESHOLD_PCT', (float)(getenv('PRAKASH_FAST_MOVER_THRESHOLD_PCT') ?: 1.0));
// Max number of stocks shown in each of the Buy/Sell boxes.
define('PRAKASH_MAX_PER_BOX', (int)(getenv('PRAKASH_MAX_PER_BOX') ?: 5));
// Max number of stocks Prakash ranks/tracks at all (was hardcoded to 20).
// Override in .env as PRAKASH_MAX_TRACKED=40 to get more than 20 recommendations.
define('PRAKASH_MAX_TRACKED', (int)(getenv('PRAKASH_MAX_TRACKED') ?: 20));
// Sanity guard: if a live quote implies a bigger single-refresh move than this
// percent vs. the day's previous close, treat it as a bad/glitched tick and
// ignore it for entry-locking and target-hit checks rather than trusting it.
// This stops a single bad data-source read from permanently mis-recording an
// entry price or falsely marking a target as "hit". Raise this if you find
// genuine fast-moving days getting rejected.
define('PRAKASH_MAX_PLAUSIBLE_MOVE_PCT', (float)(getenv('PRAKASH_MAX_PLAUSIBLE_MOVE_PCT') ?: 12.0));

// How many of the top gainers / top losers the spec wants us to track for
// strong-presence (5 consecutive iterations) and new-entry detection.
// PRAKASH_MAX_PER_BOX above is the display cap on the Buy/Sell boxes (5);
// PRAKASH_TOP_N is the internal ceiling used for the spec's "Top 10
// Gainers" / "Top 10 Losers" detection layer. They are intentionally
// separate — a stock at rank 6 should still count toward strong-presence
// across the day even though it doesn't fit in the visible box.
define('PRAKASH_TOP_N', (int)(getenv('PRAKASH_TOP_N') ?: 10));

// Confidence-score weights for the Buy/Sell candidate ranking. Kept in one
// place so the formula is auditable. Each sub-score is normalized to 0..1
// first, multiplied by its weight, and the weighted sum is clamped to
// 0..100 and rounded to 1 decimal before being attached to a candidate.
define('PRAKASH_CONFIDENCE_W_CONSECUTIVE', (int)(getenv('PRAKASH_CONFIDENCE_W_CONSECUTIVE') ?: 40)); // iterations in top 10 / 5
define('PRAKASH_CONFIDENCE_W_RANK_MOVE',   (int)(getenv('PRAKASH_CONFIDENCE_W_RANK_MOVE')   ?: 25)); // |rank Δ| vs. worst-case
define('PRAKASH_CONFIDENCE_W_MAGNITUDE',   (int)(getenv('PRAKASH_CONFIDENCE_W_MAGNITUDE')   ?: 20)); // min(|%change|, cap) / cap
define('PRAKASH_CONFIDENCE_W_NEW_ENTRY',   (int)(getenv('PRAKASH_CONFIDENCE_W_NEW_ENTRY')   ?: 15)); // new-entry boost
define('PRAKASH_CONFIDENCE_MAGNITUDE_CAP', (float)(getenv('PRAKASH_CONFIDENCE_MAGNITUDE_CAP') ?: 5.0)); // |%change| that scores 1.0

// ─── Momentum Ranking Engine (point-score) settings ──────────
// A second, additive scoring layer on top of Prakash's confidence score.
// Instead of a single 0..100 confidence, this scores every tracked stock
// on 9 independent signals (rank, top-N membership, consistency, rank
// improvement, breakout, volume spike, price confirmation, acceleration,
// volatility) and sums them into a point score that can exceed 100 —
// mirroring the "⭐⭐⭐⭐⭐ Strong Buy / ⭐⭐⭐⭐ Buy / ⭐⭐⭐ Watch / Ignore"
// tiering. See app/momentum_score.php for the implementation.
define('MS_POINTS_RANK',                  (float)(getenv('MS_POINTS_RANK') ?: 20));   // Signal 1: rank #1 / last
define('MS_POINTS_TOPN',                  (float)(getenv('MS_POINTS_TOPN') ?: 10));   // Signal 2: in Top-N gainers/losers
define('MS_POINTS_CONSISTENCY',           (float)(getenv('MS_POINTS_CONSISTENCY') ?: 40)); // Signal 3: full lookback in Top-N
define('MS_POINTS_PER_RANK_STEP',         (float)(getenv('MS_POINTS_PER_RANK_STEP') ?: 5)); // Signal 4: per rank improved/worsened
define('MS_POINTS_BREAKOUT',              (float)(getenv('MS_POINTS_BREAKOUT') ?: 25));  // Signal 5: new Top-N entry
define('MS_POINTS_VOLUME',                (float)(getenv('MS_POINTS_VOLUME') ?: 20));   // Signal 6: volume spike
define('MS_VOLUME_SPIKE_RATIO',           (float)(getenv('MS_VOLUME_SPIKE_RATIO') ?: 2.0)); // matches volumeAnalysis()'s own "spike" cutoff
define('MS_POINTS_PRICE_CONFIRM_STRONG',  (float)(getenv('MS_POINTS_PRICE_CONFIRM_STRONG') ?: 15)); // Signal 7: price + %change both confirming
define('MS_POINTS_PRICE_CONFIRM_WEAK',    (float)(getenv('MS_POINTS_PRICE_CONFIRM_WEAK') ?: 5));  // Signal 7: only one confirms
define('MS_POINTS_ACCELERATION',          (float)(getenv('MS_POINTS_ACCELERATION') ?: 15));  // Signal 8: cap on acceleration bonus
define('MS_ACCELERATION_SCALE',           (float)(getenv('MS_ACCELERATION_SCALE') ?: 10));  // Signal 8: points per (%-change unit of 2nd derivative)
define('MS_VOLATILITY_FLIP_THRESHOLD',    (int)(getenv('MS_VOLATILITY_FLIP_THRESHOLD') ?: 2)); // Signal 9: sign flips within the window to count as "unstable"
define('MS_POINTS_VOLATILITY_PENALTY',    (float)(getenv('MS_POINTS_VOLATILITY_PENALTY') ?: 15)); // Signal 9: penalty subtracted when unstable
define('MS_POINTS_SWING',                 (float)(getenv('MS_POINTS_SWING') ?: 15));  // Signal 10: Higher-High/Higher-Low (or reverse) price structure
define('MS_TIER_STRONG',                  (float)(getenv('MS_TIER_STRONG') ?: 100)); // score > this -> 5 stars "Strong Buy/Sell"
define('MS_TIER_ACTIONABLE',              (float)(getenv('MS_TIER_ACTIONABLE') ?: 80)); // score >= this -> 4 stars "Buy/Sell"
define('MS_TIER_WATCH',                   (float)(getenv('MS_TIER_WATCH') ?: 60)); // score >= this -> 3 stars "Watch"; below -> "Ignore"
define('MS_TOP_PICKS_COUNT',              (int)(getenv('MS_TOP_PICKS_COUNT') ?: 5)); // "Top 5 Buy" / "Top 5 Sell" pick-of-the-day list size

// ─── AI Recommendation settings ──────────────────────────────
// Same locked-in-target scheme as Prakash, but the Buy/Sell box is built
// from this app's own indicator-driven signal engine (signals.php) instead
// of rank movement — the "AI recommendation" as distinct from Prakash's.
define('AI_TARGET_PCT', (float)(getenv('AI_TARGET_PCT') ?: 1.0));
define('AI_MAX_PER_BOX', (int)(getenv('AI_MAX_PER_BOX') ?: 5));

// ─── Default watchlist symbols ───────────────────────────────
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

// ─── Reliable fallback symbols ────────────────────────────────
// WATCHLIST_SYMBOLS (~214 stocks) is too large to fetch reliably from the
// free scrape sources (BSE scrip map, Stooq, NSE, Groww) — most requests
// only get partial coverage and the result set varies from run to run.
// When the active watchlist is large (see WATCHLIST_LARGE_THRESHOLD below),
// we fall back to this fixed set of 24 large-cap, high-liquidity NSE names.
// These are exactly the symbols covered by bseScripCode() in datasources.php,
// so they reliably resolve a live quote every time regardless of which
// upstream source answers first.
define('WATCHLIST_LARGE_THRESHOLD', (int)(getenv('WATCHLIST_LARGE_THRESHOLD') ?: 50));
define('RELIABLE_FALLBACK_SYMBOLS', [
    'RELIANCE.NS','TCS.NS','HDFCBANK.NS','BHARTIARTL.NS','ICICIBANK.NS',
    'INFY.NS','SBIN.NS','HINDUNILVR.NS','ITC.NS','LT.NS',
    'KOTAKBANK.NS','AXISBANK.NS','BAJFINANCE.NS','MARUTI.NS','TITAN.NS',
    'SUNPHARMA.NS','NTPC.NS','POWERGRID.NS','ONGC.NS','HCLTECH.NS',
    'ADANIENT.NS','ADANIPORTS.NS','COALINDIA.NS','JSWSTEEL.NS',
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

date_default_timezone_set(getenv('TIMEZONE') ?: 'Asia/Kolkata');
set_time_limit(90);

session_name('stock_sess');
session_start();

$APP_NAME = getenv('APP_NAME') ?: 'NSE Stock Analyzer';
$USER     = getenv('DEMO_USER') ?: 'admin';
$PASS     = getenv('DEMO_PASS') ?: 'stockpass123';

// ─── Optional: external data API key (Twelve Data, EODHD, etc.) ──
// Set DATA_API_PROVIDER and DATA_API_KEY in .env once you have a key.
// See app/datasources.php for how this is consumed.
define('DATA_API_PROVIDER', getenv('DATA_API_PROVIDER') ?: '');
define('DATA_API_KEY',      getenv('DATA_API_KEY') ?: '');

// ─── Base-path + routing resolution ──────────────────────────
// BASE = subdir the app lives in, e.g. "/stock" or "" for root.
// Apache rewrites to public/index.php so SCRIPT_NAME is like /stock/public/index.php
// — we go up two levels to get the real browser-visible base.
$_scriptName = $_SERVER['SCRIPT_NAME'];
$_parts      = explode('/', trim($_scriptName, '/'));
$_strip = ['index.php', 'public'];
foreach ($_strip as $_seg) {
    if (end($_parts) === $_seg) array_pop($_parts);
}
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
