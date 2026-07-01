<?php
declare(strict_types=1);
ini_set('display_errors', '1');
error_reporting(E_ALL);

/**
 * public/index.php — thin bootstrap and router.
 *
 * This used to be one 5,900-line file. It's now split by responsibility:
 *
 *   app/config.php       constants, .env, session, base-path resolution
 *   app/auth.php         login/logout routes + login page view
 *   app/http.php         low-level Yahoo HTTP client (verified cookie fix)
 *   app/datasources.php  all market-data fetchers (Twelve Data + legacy)
 *   app/indicators.php   pure technical-analysis math (RSI, MACD, etc.)
 *   app/signals.php      buy/sell signal generation + scoring
 *   app/api/*.php        one file per API endpoint group
 *   app/views/*.php      HTML page views
 *
 * Load order matters: config first (constants/session), then auth (which
 * also handles the login/logout routes and exits early if unauthenticated),
 * then the data/logic layers in dependency order, then API routes (each of
 * which exits if its $uri matches), and finally the dashboard view as the
 * catch-all for any authenticated request that didn't match an API route.
 */

require __DIR__ . '/../app/config.php';      // constants, .env, session, $uri
require __DIR__ . '/../app/auth.php';        // login/logout routes; exits if not authed
require __DIR__ . '/../app/http.php';        // yahooGetCrumb(), httpGet(), httpGetDebug()
require __DIR__ . '/../app/datasources.php'; // yahooQuote(), yahooQuoteBulk(), yahooHistory(), legacy fallbacks
require __DIR__ . '/../app/indicators.php';  // sma(), ema(), rsi(), macd(), adx(), etc.
require __DIR__ . '/../app/signals.php';     // generateSignal(), momentumScore(), multiTimeframe(), etc.

// ── Function definitions only (no executable routes) — must load before
//    any file below that might call these functions from a matched route.
require __DIR__ . '/../app/api/watchlist.php'; // apiWatchlist(), apiAnalyze(), apiTick(), apiLeaders(), apiWatchlistPage(), getActiveWatchlist()
require __DIR__ . '/../app/api/eod.php';       // apiEodReport(), apiEodCheck()
require __DIR__ . '/../app/api/news.php';      // apiNews()
require __DIR__ . '/../app/api/intraday.php';  // apiIntraday(), apiPivots()
require __DIR__ . '/../app/api/alerts.php';    // checkAlerts()

// ── Executable routes — each exits if its $uri matches. Order between
//    these doesn't matter since URIs are mutually exclusive, but debug/proxy
//    are checked first since they're used most often while diagnosing issues.
require __DIR__ . '/../app/api/debug.php';  // /api/debug/quicktest, /api/debug/datasource, /api/debug/yahoo
require __DIR__ . '/../app/api/proxy.php';  // /api/proxy/quotes, /api/proxy/history, /api/proxy/analyze
require __DIR__ . '/../app/api/routes.php'; // all remaining routes (watchlist, eod, news, alerts, intraday, etc.)

// If we got here, no API route matched — render the dashboard.
require __DIR__ . '/../app/views/dashboard.php';
dashboardPage($APP_NAME, $_SESSION['user'] ?? 'Trader');
