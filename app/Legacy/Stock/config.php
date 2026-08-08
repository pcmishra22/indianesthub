<?php
declare(strict_types=1);
/**
 * config.php — bootstrap: constants + .env loading for the Stock module.
 *
 * Ported from the standalone stock.indianesthub.com app. Only the
 * path/base-URL/session/routing plumbing was changed to fit inside Laravel
 * (StockController already resolves $uri and $_base before requiring this
 * file, and Laravel's router replaces the old manual SCRIPT_NAME parsing).
 * Every constant, default, and value below is unchanged from the original.
 */

// ─── Paths ────────────────────────────────────────────────────
// Data lives under storage/app/stock (writable, outside version control),
// separate from the IndianEstHub app's own storage.
define('STORAGE',    storage_path('app/stock'));
define('WL_FILE',    STORAGE . '/watchlist.json');
define('ALERT_FILE', STORAGE . '/alerts.json');

// ─── Load the Stock module's own .env ──────────────────────────
// Kept separate from Laravel's main .env — this app has its own demo
// logins and its own data-provider API keys.
$__stockEnvFile = STORAGE . '/.env';
foreach (file($__stockEnvFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
    $line = trim($line);
    if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) continue;
    [$k, $v] = explode('=', $line, 2);
    putenv(trim($k) . '=' . trim(trim($v), '"\''));
}
unset($__stockEnvFile, $line, $k, $v);

// ─── Prakash Recommendation settings ─────────────────────────
define('PRAKASH_TARGET_PCT', (float)(getenv('PRAKASH_TARGET_PCT') ?: 1.0));
define('PRAKASH_FAST_MOVER_THRESHOLD_PCT', (float)(getenv('PRAKASH_FAST_MOVER_THRESHOLD_PCT') ?: 1.0));
define('PRAKASH_MAX_PER_BOX', (int)(getenv('PRAKASH_MAX_PER_BOX') ?: 5));
define('PRAKASH_MAX_TRACKED', (int)(getenv('PRAKASH_MAX_TRACKED') ?: 20));
define('PRAKASH_MAX_PLAUSIBLE_MOVE_PCT', (float)(getenv('PRAKASH_MAX_PLAUSIBLE_MOVE_PCT') ?: 12.0));
define('PRAKASH_TOP_N', (int)(getenv('PRAKASH_TOP_N') ?: 10));

// ─── Sector Momentum (laggard) signal ─────────────────────────────
define('PRAKASH_SECTOR_MIN_COUNT', (int)(getenv('PRAKASH_SECTOR_MIN_COUNT') ?: 3));
define('PRAKASH_SECTOR_MIN_RATIO', (float)(getenv('PRAKASH_SECTOR_MIN_RATIO') ?: 0.3));

// ─── 5-Day Reversal vs Sector Strength signal ──────────────────────
define('PRAKASH_5D_NEAR_PCT', (float)(getenv('PRAKASH_5D_NEAR_PCT') ?: 2.0));
define('PRAKASH_SECTOR_RESILIENCE_PCT', (float)(getenv('PRAKASH_SECTOR_RESILIENCE_PCT') ?: 0.5));

define('PRAKASH_CONFIDENCE_W_CONSECUTIVE', (int)(getenv('PRAKASH_CONFIDENCE_W_CONSECUTIVE') ?: 40));
define('PRAKASH_CONFIDENCE_W_RANK_MOVE',   (int)(getenv('PRAKASH_CONFIDENCE_W_RANK_MOVE')   ?: 25));
define('PRAKASH_CONFIDENCE_W_MAGNITUDE',   (int)(getenv('PRAKASH_CONFIDENCE_W_MAGNITUDE')   ?: 20));
define('PRAKASH_CONFIDENCE_W_NEW_ENTRY',   (int)(getenv('PRAKASH_CONFIDENCE_W_NEW_ENTRY')   ?: 15));
define('PRAKASH_CONFIDENCE_MAGNITUDE_CAP', (float)(getenv('PRAKASH_CONFIDENCE_MAGNITUDE_CAP') ?: 5.0));

// ─── Momentum Ranking Engine (point-score) settings ──────────
define('MS_POINTS_RANK',                  (float)(getenv('MS_POINTS_RANK') ?: 20));
define('MS_POINTS_TOPN',                  (float)(getenv('MS_POINTS_TOPN') ?: 10));
define('MS_POINTS_CONSISTENCY',           (float)(getenv('MS_POINTS_CONSISTENCY') ?: 40));
define('MS_POINTS_PER_RANK_STEP',         (float)(getenv('MS_POINTS_PER_RANK_STEP') ?: 5));
define('MS_POINTS_BREAKOUT',              (float)(getenv('MS_POINTS_BREAKOUT') ?: 25));
define('MS_POINTS_VOLUME',                (float)(getenv('MS_POINTS_VOLUME') ?: 20));
define('MS_VOLUME_SPIKE_RATIO',           (float)(getenv('MS_VOLUME_SPIKE_RATIO') ?: 2.0));
define('MS_POINTS_PRICE_CONFIRM_STRONG',  (float)(getenv('MS_POINTS_PRICE_CONFIRM_STRONG') ?: 15));
define('MS_POINTS_PRICE_CONFIRM_WEAK',    (float)(getenv('MS_POINTS_PRICE_CONFIRM_WEAK') ?: 5));
define('MS_POINTS_ACCELERATION',          (float)(getenv('MS_POINTS_ACCELERATION') ?: 15));
define('MS_ACCELERATION_SCALE',           (float)(getenv('MS_ACCELERATION_SCALE') ?: 10));
define('MS_VOLATILITY_FLIP_THRESHOLD',    (int)(getenv('MS_VOLATILITY_FLIP_THRESHOLD') ?: 2));
define('MS_POINTS_VOLATILITY_PENALTY',    (float)(getenv('MS_POINTS_VOLATILITY_PENALTY') ?: 15));
define('MS_POINTS_SWING',                 (float)(getenv('MS_POINTS_SWING') ?: 15));
define('MS_TIER_STRONG',                  (float)(getenv('MS_TIER_STRONG') ?: 100));
define('MS_TIER_ACTIONABLE',              (float)(getenv('MS_TIER_ACTIONABLE') ?: 80));
define('MS_TIER_WATCH',                   (float)(getenv('MS_TIER_WATCH') ?: 60));
define('MS_TOP_PICKS_COUNT',              (int)(getenv('MS_TOP_PICKS_COUNT') ?: 5));

// ─── AI Recommendation settings ──────────────────────────────
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
define('WATCHLIST_LARGE_THRESHOLD', (int)(getenv('WATCHLIST_LARGE_THRESHOLD') ?: 50));

// How close (in %) current price must be to a real swing support/resistance
// level (see keyLevels() in indicators.php) to show up in the "Near
// Support" / "Near Resistance" watch lists. Kept generous enough to catch
// a stock approaching a level, tight enough that "already ran away from
// it" stocks (e.g. up 500% off an old low) correctly don't qualify.
define('NEAR_LEVEL_PCT', (float)(getenv('NEAR_LEVEL_PCT') ?: 5.0));
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

// ─── Session ──────────────────────────────────────────────────
// Deliberately separate session cookie from Laravel's own ("stock_sess"
// vs Laravel's default "laravel_session") — this is what gives the Stock
// module a genuinely separate login from the rest of the site, matching
// the original standalone app. StockController does NOT route these
// requests through Laravel's 'web' middleware group (no Laravel session,
// no CSRF middleware), so plain session_start() here owns the cookie
// exactly like the original app did.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('stock_sess');
    session_start();
}

$APP_NAME = getenv('APP_NAME') ?: 'NSE Stock Analyzer';
$USER     = getenv('DEMO_USER') ?: 'admin';
$PASS     = getenv('DEMO_PASS') ?: 'stockpass123';

// ─── Optional: external data API key (Twelve Data, EODHD, etc.) ──
define('DATA_API_PROVIDER', getenv('DATA_API_PROVIDER') ?: '');
define('DATA_API_KEY',      getenv('DATA_API_KEY') ?: '');

// NOTE: $_base and $uri are set by StockController BEFORE this file is
// required (Laravel already did the routing/base-path resolution that the
// original config.php did by hand via SCRIPT_NAME parsing).
