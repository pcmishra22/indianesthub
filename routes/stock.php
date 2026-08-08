<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Stock\StockController;

/**
 * The whole Stock module (dashboard, its own login/register/logout, and
 * every /api/* endpoint) is handled by one controller that replays the
 * original app's own routing — see StockController for why.
 *
 * Deliberately NOT registered under the 'web' middleware group (see
 * bootstrap/app.php) so Laravel's own session/CSRF middleware never
 * touches these requests — the module keeps its own separate
 * "stock_sess" session/login, entirely independent of the main site.
 */
Route::any('/stock/{any?}', [StockController::class, 'handle'])->where('any', '.*');
