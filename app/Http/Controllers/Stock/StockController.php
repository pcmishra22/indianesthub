<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * StockController — single entry point for the whole /stock module.
 *
 * The original stock.indianesthub.com app was a standalone procedural PHP
 * app that routed itself: public/index.php required its files in order,
 * each API file exiting the process the moment its own $uri matched, and
 * falling through to the dashboard view if nothing did. That routing
 * engine and every function/behaviour is preserved as-is here — this
 * controller only replaces the old "parse SCRIPT_NAME by hand" step with
 * Laravel's own router (which is what mounts /stock in the first place),
 * and does not run through Laravel's 'web' middleware group so the
 * module's own session/auth (see config.php, auth.php) stays completely
 * separate from the rest of the site, exactly as requested.
 *
 * See app/Legacy/Stock/*.php for the ported application code.
 */
class StockController extends Controller
{
    private const PREFIX = '/stock';

    public function handle(Request $request)
    {
        // Reproduce the original app's $uri (path relative to its own
        // base) and $_base (its own mount point), which config.php,
        // auth.php and every app/api/*.php file expect as globals.
        $full = '/' . ltrim($request->path(), '/');
        $uri = str_starts_with($full, self::PREFIX) ? substr($full, strlen(self::PREFIX)) : $full;
        if ($uri === '') $uri = '/';
        if ($uri[0] !== '/') $uri = '/' . $uri;

        $_base = self::PREFIX;

        $legacy = app_path('Legacy/Stock');

        // ── Same load order as the original public/index.php ──────────
        require_once $legacy . '/config.php';   // constants, .env, session ($uri/$_base already set above)

        // A couple of legacy functions read these via `global $x;` (real
        // PHP-global scope), which a controller method's locals are not —
        // the original app worked because public/index.php WAS the global
        // scope. Mirror the few that matter into $GLOBALS explicitly.
        $GLOBALS['_base'] = $_base;
        $GLOBALS['USER']  = $USER;
        $GLOBALS['PASS']  = $PASS;

        require_once $legacy . '/users.php';    // multi-user helpers, JSON storage
        require_once $legacy . '/auth.php';     // login/register/logout; exits if not authed

        require_once $legacy . '/http.php';
        require_once $legacy . '/datasources.php';
        require_once $legacy . '/indicators.php';
        require_once $legacy . '/signals.php';
        require_once $legacy . '/momentum_score.php';
        require_once $legacy . '/prakash_recommendations.php';
        require_once $legacy . '/ai_recommendations.php';

        // Function definitions only — must load before the executable
        // route files below that call them.
        require_once $legacy . '/api/watchlist.php';
        require_once $legacy . '/api/eod.php';
        require_once $legacy . '/api/news.php';
        require_once $legacy . '/api/intraday.php';
        require_once $legacy . '/api/alerts.php';

        // Executable routes — each exits the request if its $uri matches.
        require_once $legacy . '/api/debug.php';
        require_once $legacy . '/api/proxy.php';
        require_once $legacy . '/api/routes.php';

        // Nothing matched an API route — render the dashboard (the
        // original app's catch-all for any authenticated request).
        require_once $legacy . '/views/dashboard.php';
        dashboardPage($APP_NAME, $_SESSION['user'] ?? 'Trader');
        exit;
    }
}
