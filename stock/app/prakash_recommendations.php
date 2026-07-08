<?php
declare(strict_types=1);

if (!defined('STORAGE')) {
    define('STORAGE', dirname(__DIR__) . '/storage');
}

// The point-score Momentum Ranking Engine layer (Signals 6-9 + tiering)
// lives in its own file so it can be unit-tested independently, but this
// file is the only caller — require_once here means load order in
// public/index.php or a standalone test doesn't matter.
require_once __DIR__ . '/momentum_score.php';

// Internal base-path resolver so this file can be loaded standalone
// (e.g. from the unit test which doesn't go through users.php) without
// "undefined function getStorageBasePath" crashes. When users.php is
// loaded first, that function takes precedence and uses the writable
// preferred/fallback logic; otherwise we fall back to the STORAGE
// constant defined right above.
function prakashStorageBase(): string
{
    if (function_exists('getStorageBasePath')) return getStorageBasePath();
    if (!is_dir(STORAGE) && !@mkdir(STORAGE, 0755, true)) {
        $tmp = sys_get_temp_dir() . '/stock_storage';
        if (!is_dir($tmp)) @mkdir($tmp, 0755, true);
        return $tmp;
    }
    return STORAGE;
}

// ── Files ─────────────────────────────────────────────────────
// State file: a single per-user record of the latest iteration's
// rank + price snapshot. Used to derive single-refresh rank movement
// signals (kept for the rollup / current-rank display).
function prakashRecommendationStateFile(?string $username = null): string
{
    return $username ? getUserRecommendationsStatePath($username) : (getStorageBasePath() . '/prakash_state.json');
}

// History file: a flat append-only list of every Buy/Sell event the engine
// has emitted. Powers the long-running EOD log and the per-day rollup.
function prakashRecommendationHistoryFile(?string $username = null): string
{
    return $username ? getUserRecommendationsHistoryPath($username) : (getStorageBasePath() . '/prakash_recommendations_history.json');
}

// Rank-history file: the *raw* per-iteration rank map, capped at the last
// 5 refreshes (25 minutes of intraday history). This is the input the
// momentum detector reads each refresh — we need to remember not just
// "what ranks were 5 minutes ago" but "what ranks were 5, 10, 15, 20, 25
// minutes ago" so a sustained trend over the lookback window counts.
function prakashRankHistoryFile(?string $username = null): string
{
    if ($username && function_exists('getUserDataDir')) {
        $base = getUserDataDir();
    } else {
        $base = prakashStorageBase();
    }
    $name = $username ? ('/' . preg_replace('/[^a-z0-9._-]/i', '_', trim($username)) . '_prakash_rank_history.json')
                      : '/prakash_rank_history.json';
    return $base . $name;
}

// ── Daily intraday target-tracking file ───────────────────────
// One file per user per calendar day. Holds every Buy/Sell entry given
// that day plus whether its intraday target was hit. No carryover — a new
// day always starts a fresh file.
function prakashDailyFile(?string $username = null, ?string $date = null): string
{
    $date = $date ?: date('Y-m-d');
    if ($username) {
        $prefix = getUserDataDir() . '/' . preg_replace('/[^a-z0-9._-]/i', '_', trim($username));
        return $prefix . '_prakash_daily_' . $date . '.json';
    }
    // No-username fallback: must live INSIDE storage/, not as a sibling
    // file next to it (missing '/' here used to produce a stray
    // "storage_prakash_daily_YYYY-MM-DD.json" file at the repo root).
    return getStorageBasePath() . '/prakash_daily_' . $date . '.json';
}

// Market open / close times (IST).
// Pre-open settles at 9:10 AM — that's the "initial iteration" of the day.
define('PRAKASH_MARKET_OPEN_HOUR', 9);
define('PRAKASH_MARKET_OPEN_MINUTE', 10);
define('PRAKASH_MARKET_CLOSE_HOUR', 15);
define('PRAKASH_MARKET_CLOSE_MINUTE', 30);

// Number of consecutive iterations (refreshes) the momentum detector
// requires before a Buy/Sell call is made. At 5 minutes/iteration this
// matches the spec's 25-minute lookback window.
define('PRAKASH_MOMENTUM_LOOKBACK', 5);

function prakashIsMarketClosed(): bool
{
    $closeTs = mktime(PRAKASH_MARKET_CLOSE_HOUR, PRAKASH_MARKET_CLOSE_MINUTE, 0);
    return time() >= $closeTs;
}

// True only between 9:10 AM and 3:30 PM. We don't actively push refreshes,
// but the file schema is sized for one iteration per ~5 minutes, so a
// refresh outside market hours is just the start of tomorrow's log.
function prakashIsMarketHours(): bool
{
    $now = time();
    $open = mktime(PRAKASH_MARKET_OPEN_HOUR, PRAKASH_MARKET_OPEN_MINUTE, 0);
    $close = mktime(PRAKASH_MARKET_CLOSE_HOUR, PRAKASH_MARKET_CLOSE_MINUTE, 0);
    return $now >= $open && $now <= $close;
}

// First refresh of the trading day — rank history is empty (or from a
// previous date). The spec wants the first Buy/Sell to come straight from
// the top/bottom of the %-change leaderboard at this point.
function prakashIsInitialIteration(string $rankHistoryPath): bool
{
    if (!file_exists($rankHistoryPath)) return true;
    $decoded = json_decode((string)file_get_contents($rankHistoryPath), true);
    if (!is_array($decoded)) return true;
    if (($decoded['date'] ?? '') !== date('Y-m-d')) return true;
    $iters = $decoded['iterations'] ?? [];
    return !is_array($iters) || count($iters) === 0;
}

// Call on each refresh (or via a cron hitting the API once after close). If
// the day's file isn't closed yet and market close has passed, freeze it:
// every still-open recommendation is finalized as Not Achieved, and the
// file is marked closed so tomorrow's first refresh starts a clean file.
function closePrakashDailyIfNeeded(?string $username = null): ?array
{
    $path = prakashDailyFile($username);
    $daily = loadPrakashDaily($path);
    if (($daily['date'] ?? '') !== date('Y-m-d')) return null; // nothing open for today yet
    if ($daily['closed']) return $daily;
    if (!prakashIsMarketClosed()) return null;

    foreach ($daily['recommendations'] as &$rec) {
        if (!$rec['achieved']) {
            $rec['final_status'] = 'Not Achieved';
            $rec['status'] = $rec['status'] ?? 'Not Achieved';
        } else {
            $rec['final_status'] = 'Achieved';
            if (($rec['status'] ?? '') !== 'Target Hit') {
                $rec['status'] = 'Target Hit';
            }
        }
    }
    unset($rec);
    $daily['closed'] = true;
    $daily['closed_at'] = date('Y-m-d H:i:s');
    savePrakashDaily($daily, $path);
    return $daily;
}

// Rolls up every stored daily file (across all days, optionally for one
// user) into overall win-rate stats — the number you'd actually want to see
// on a "Prakash Track Record" page.
// $withDetails=true attaches every individual stock-level recommendation
// (symbol, side, entry/target price, achieved/not) for each day, so a "view
// details" UI can list and let the user verify every entry behind the
// aggregate rate rather than just trusting the summary number.
function prakashRollupHistory(?string $username = null, int $maxDays = 90, bool $withDetails = false): array
{
    $dir = getStorageBasePath();
    $prefix = $username ? preg_replace('/[^a-z0-9._-]/i', '_', trim($username)) . '_' : '';
    $pattern = $dir . '/' . ($prefix ?: '') . 'prakash_daily_*.json';
    if ($username) {
        // user files live under users_data/, global ones directly under storage/
        $pattern = getUserDataDir() . '/' . $prefix . 'prakash_daily_*.json';
    }
    $files = glob($pattern) ?: [];
    rsort($files); // most recent date first (filenames are YYYY-MM-DD)
    $files = array_slice($files, 0, $maxDays);

    $days = [];
    $totalAchieved = 0;
    $totalCount = 0;
    foreach ($files as $file) {
        $decoded = json_decode((string)file_get_contents($file), true);
        if (!is_array($decoded)) continue;
        $recs = is_array($decoded['recommendations'] ?? null) ? $decoded['recommendations'] : [];
        $achieved = count(array_filter($recs, fn($r) => !empty($r['achieved'])));
        $count = count($recs);
        $totalAchieved += $achieved;
        $totalCount += $count;
        $dayEntry = [
            'date' => $decoded['date'] ?? basename($file),
            'total' => $count,
            'achieved' => $achieved,
            'success_rate' => $count > 0 ? round($achieved / $count * 100, 1) : null,
            'closed' => (bool)($decoded['closed'] ?? false),
        ];
        if ($withDetails) $dayEntry['recommendations'] = $recs;
        $days[] = $dayEntry;
    }

    return [
        'days' => $days,
        'overall_total' => $totalCount,
        'overall_achieved' => $totalAchieved,
        'overall_success_rate' => $totalCount > 0 ? round($totalAchieved / $totalCount * 100, 1) : null,
    ];
}

function loadPrakashDaily(string $path): array
{
    if (!file_exists($path)) return ['date' => date('Y-m-d'), 'recommendations' => [], 'closed' => false];
    $decoded = json_decode((string)file_get_contents($path), true);
    if (!is_array($decoded)) return ['date' => date('Y-m-d'), 'recommendations' => [], 'closed' => false];
    $recs = is_array($decoded['recommendations'] ?? null) ? $decoded['recommendations'] : [];
    foreach ($recs as &$rec) {
        if (!is_array($rec)) continue;
        $rec['entry_price'] = isset($rec['entry_price']) ? (float)$rec['entry_price'] : null;
        $rec['target_price'] = isset($rec['target_price']) ? (float)$rec['target_price'] : null;
        $rec['achieved_price'] = isset($rec['achieved_price']) ? (float)$rec['achieved_price'] : null;
        $rec['last_checked_price'] = isset($rec['last_checked_price']) ? (float)$rec['last_checked_price'] : null;
    }
    unset($rec);
    return [
        'date'            => $decoded['date'] ?? date('Y-m-d'),
        'recommendations' => $recs,
        'closed'          => (bool)($decoded['closed'] ?? false),
    ];
}

function savePrakashDaily(array $daily, string $path): void
{
    $base = getStorageBasePath();
    if (!is_dir($base)) mkdir($base, 0755, true);
    file_put_contents($path, json_encode($daily, JSON_PRETTY_PRINT));
}

// ── Live price refresh for report views ────────────────────────────────
// Both prakash_daily_*.json and ai_daily_*.json entries only get their
// price/target/achieved fields updated whenever buildPrakashRecommendations()
// / buildAiRecommendations() happens to run (i.e. whenever someone has the
// Watchlist tab open ticking). If nobody has triggered a refresh recently,
// a report opened straight from the EOD tab would otherwise show a stale
// "current price" (really: "price as of the last tick") with no indication
// that it's stale, and — worse — a target that was actually hit in the
// meantime would sit shown as "Open" until the next unrelated tick.
//
// This function re-fetches live quotes for every symbol still open in a
// day's recommendation list and:
//   1. always sets 'current_price' to the freshest price we have (live if
//      available, otherwise the last price we ever saw for it),
//   2. flags whether that price is actually live this call ('price_live'),
//   3. re-checks any still-open entry against its target with that live
//      price and flips it to achieved right away if it has been hit,
//   4. stamps an unambiguous 'status_label' (OPEN / ACHIEVED / NOT ACHIEVED)
//      so callers never have to re-derive status from several booleans.
//
// Mutates $daily in place and returns true if anything actually changed
// (i.e. the caller should persist it back to disk).
function prakashRefreshRowsLive(array &$daily, array $quotesBySymbol): bool
{
    $changed = false;
    $isClosed = (bool)($daily['closed'] ?? false);
    $now = date('Y-m-d H:i:s');

    foreach ($daily['recommendations'] as &$rec) {
        if (!is_array($rec)) continue;
        $symbol = (string)($rec['symbol'] ?? '');
        $quote  = $quotesBySymbol[$symbol] ?? null;
        $live   = $quote ? (float)($quote['price'] ?? 0) : 0.0;
        $havelive = $live > 0 && ($quote['plausible'] ?? true);

        if ($havelive) {
            if (($rec['current_price'] ?? null) !== $live) $changed = true;
            $rec['current_price'] = $live;
            $rec['price_live'] = true;
        } else {
            $fallback = $rec['last_checked_price'] ?? $rec['entry_price'] ?? 0.0;
            $rec['current_price'] = $fallback;
            $rec['price_live'] = false;
        }

        if (!$isClosed && !($rec['achieved'] ?? false) && $havelive) {
            $rec['last_checked_price'] = $live;
            $hit = ($rec['side'] ?? '') === 'Buy'
                ? $live >= (float)($rec['target_price'] ?? INF)
                : $live <= (float)($rec['target_price'] ?? -INF);
            if ($hit) {
                $rec['achieved'] = true;
                $rec['achieved_at'] = $now;
                $rec['achieved_price'] = $live;
                $rec['status'] = 'Target Hit';
                $changed = true;
            }
        }

        if (!empty($rec['achieved'])) {
            $rec['status_label'] = 'ACHIEVED';
        } elseif ($isClosed) {
            $rec['status_label'] = 'NOT ACHIEVED';
        } else {
            $rec['status_label'] = 'OPEN';
        }

        $entry = (float)($rec['entry_price'] ?? 0);
        $rec['gap_pct'] = $entry > 0 ? round(((float)$rec['current_price'] - $entry) / $entry * 100, 2) : null;
    }
    unset($rec);

    return $changed;
}

// Builds a symbol => ['price' => float, 'plausible' => bool] lookup from a
// bulk-quote result for use with prakashRefreshRowsLive(). $symbols are the
// plain display symbols (no .NS) as stored in a daily recommendations file.
function prakashBuildQuoteLookup(array $symbols): array
{
    $symbols = array_values(array_unique(array_filter($symbols)));
    if (empty($symbols)) return [];
    $withSuffix = array_map(fn($s) => prakashNormalizeSymbol($s), $symbols);
    $quotes = yahooQuoteBulk($withSuffix);

    $lookup = [];
    foreach ($symbols as $i => $sym) {
        $q = $quotes[$withSuffix[$i]] ?? null;
        if (!$q) continue;
        $price = (float)($q['regularMarketPrice'] ?? 0);
        if ($price <= 0) continue;
        $prevClose = (float)($q['regularMarketPreviousClose'] ?? 0);
        $changePct = $prevClose > 0 ? (($price - $prevClose) / $prevClose) * 100 : null;
        $plausible = prakashIsPricePlausible(['price' => $price, 'change_pct' => $changePct]);
        $lookup[$sym] = ['price' => $price, 'plausible' => $plausible];
    }
    return $lookup;
}

function prakashNormalizeSymbol(string $symbol): string
{
    $sym = strtoupper(trim($symbol));
    if ($sym === '') return '';
    if (!str_ends_with($sym, '.NS')) $sym .= '.NS';
    return $sym;
}

function prakashDisplaySymbol(string $symbol): string
{
    return strtoupper(trim(str_replace('.NS', '', $symbol)));
}

function loadPrakashState(string $statePath): array
{
    if (!file_exists($statePath)) return ['updated' => null, 'date' => null, 'ranks' => [], 'changes' => []];
    $decoded = json_decode((string)file_get_contents($statePath), true);
    if (!is_array($decoded)) return ['updated' => null, 'date' => null, 'ranks' => [], 'changes' => []];
    // Reset on date change — otherwise the single-refresh rank-movement
    // comparison (rank_movement_buy / rank_movement_sell) would compare
    // today's opening ranks against yesterday's closing ranks on the first
    // refresh of a new day, producing a misleading overnight "movement".
    if (($decoded['date'] ?? '') !== date('Y-m-d')) {
        return ['updated' => null, 'date' => date('Y-m-d'), 'ranks' => [], 'changes' => []];
    }
    return [
        'updated' => $decoded['updated'] ?? null,
        'date'    => $decoded['date'],
        'ranks'   => is_array($decoded['ranks'] ?? null) ? $decoded['ranks'] : [],
        'changes' => is_array($decoded['changes'] ?? null) ? $decoded['changes'] : [],
    ];
}

function savePrakashState(array $state, string $statePath): void
{
    $base = getStorageBasePath();
    if (!is_dir($base)) mkdir($base, 0755, true);
    file_put_contents($statePath, json_encode($state, JSON_PRETTY_PRINT));
}

// ── Rank-history helpers ──────────────────────────────────────
// File schema:
//   {
//     "date": "2026-07-06",
//     "iterations": [
//       { "ts": "09:10:00", "ranks": { "RELIANCE.NS": 1, "TCS.NS": 2, ... } },
//       ... up to PRAKASH_MOMENTUM_LOOKBACK entries, oldest dropped
//     ]
//   }
// We keep this separate from the prakash_state.json (which is just a flat
// rank snapshot of the latest refresh) because the momentum detector needs
// the full lookback window, not a single point.
function loadPrakashRankHistory(string $path): array
{
    if (!file_exists($path)) return ['date' => date('Y-m-d'), 'iterations' => []];
    $decoded = json_decode((string)file_get_contents($path), true);
    if (!is_array($decoded)) return ['date' => date('Y-m-d'), 'iterations' => []];
    $iters = is_array($decoded['iterations'] ?? null) ? $decoded['iterations'] : [];
    // Drop stale dates — yesterday's rank window is irrelevant once a new
    // trading day starts and the spec calls for a fresh initial iteration.
    if (($decoded['date'] ?? '') !== date('Y-m-d')) {
        return ['date' => date('Y-m-d'), 'iterations' => []];
    }
    return ['date' => $decoded['date'], 'iterations' => $iters];
}

function savePrakashRankHistory(array $history, string $path): void
{
    $base = getStorageBasePath();
    if (!is_dir($base)) mkdir($base, 0755, true);
    file_put_contents($path, json_encode($history, JSON_PRETTY_PRINT));
}

// Append a new iteration and prune to the lookback window. A new trading
// day (history['date'] != today) wipes the window — spec calls for a fresh
// initial iteration.
//
// $changes and $prices are optional symbol => value maps (percentage
// change and live price respectively) captured at the same instant as
// $ranks. They feed the point-score engine's price-confirmation,
// acceleration, and volatility signals, which all need a short time
// series rather than just the latest rank.
function appendPrakashRankIteration(array $ranks, string $path, array $changes = [], array $prices = []): array
{
    $history = loadPrakashRankHistory($path);
    $history['date'] = date('Y-m-d');
    $history['iterations'][] = [
        'ts'     => date('H:i:s'),
        'epoch'  => time(),
        'ranks'  => $ranks,
        'changes'=> $changes,
        'prices' => $prices,
    ];
    if (count($history['iterations']) > PRAKASH_MOMENTUM_LOOKBACK) {
        $history['iterations'] = array_slice($history['iterations'], -PRAKASH_MOMENTUM_LOOKBACK);
    }
    savePrakashRankHistory($history, $path);
    return $history;
}

// Builds an oldest -> newest series for one symbol out of a per-iteration
// field ('ranks', 'changes', or 'prices') across the stored rank-history
// window, appending the current refresh's own value on the end (since
// $rankHistory as returned by appendPrakashRankIteration already includes
// the just-appended iteration, this is simply "read every iteration").
// Missing iterations for a symbol (it wasn't tracked/present that refresh)
// are skipped rather than gapped with a placeholder.
function prakashFieldSeries(array $rankHistory, string $symbol, string $field): array
{
    $series = [];
    foreach ($rankHistory['iterations'] ?? [] as $iter) {
        $values = is_array($iter[$field] ?? null) ? $iter[$field] : [];
        if (array_key_exists($symbol, $values)) {
            $series[] = $values[$symbol];
        }
    }
    return $series;
}

// ── Top-N Gainers / Losers helpers ────────────────────────────
// Splits an already-sorted-by-change_pct-desc list of stocks into the
// first $topN (gainers) and last $topN (losers) by their normalized
// symbol. The caller is expected to have passed a list that's been
// sorted by change_pct descending (which buildPrakashRecommendations
// already does at the top of the function). Empty entries and
// non-array rows are skipped defensively.
function prakashTopGainersLosers(array $tracked, int $topN): array
{
    $topN = max(0, $topN);
    $gainers = [];
    $losers  = [];
    $count = count($tracked);
    if ($topN === 0 || $count === 0) {
        return ['gainers' => $gainers, 'losers' => $losers];
    }
    for ($i = 0; $i < min($topN, $count); $i++) {
        $sym = prakashNormalizeSymbol((string)($tracked[$i]['symbol'] ?? ''));
        if ($sym !== '') $gainers[] = $sym;
    }
    // Walk from the bottom of the list — last N symbols in descending
    // sort order are the biggest losers.
    for ($i = $count - 1; $i >= max(0, $count - $topN); $i--) {
        $sym = prakashNormalizeSymbol((string)($tracked[$i]['symbol'] ?? ''));
        if ($sym !== '') $losers[] = $sym;
    }
    return ['gainers' => $gainers, 'losers' => $losers];
}

// ── "Seen in Top N today" persistence ──────────────────────────
// Tracks every symbol that has been in the day's Top N Gainers or
// Top N Losers at any prior iteration. Used by the new-entry detector
// to fire a Buy/Sell signal exactly once per symbol per day, the first
// time it appears in the Top N. Mirrors prakashRankHistoryFile's
// per-user path layout; for the global (no-username) case it lives
// directly under storage/.
function prakashTopSeenFile(?string $username = null): string
{
    if ($username && function_exists('getUserDataDir')) {
        $base = getUserDataDir();
    } else {
        $base = prakashStorageBase();
    }
    $name = $username
        ? ('/' . preg_replace('/[^a-z0-9._-]/i', '_', trim($username)) . '_prakash_top_seen.json')
        : '/prakash_top_seen.json';
    return $base . $name;
}

function loadPrakashTopSeen(string $path): array
{
    if (!file_exists($path)) return ['date' => date('Y-m-d'), 'gainers' => [], 'losers' => []];
    $decoded = json_decode((string)file_get_contents($path), true);
    if (!is_array($decoded)) return ['date' => date('Y-m-d'), 'gainers' => [], 'losers' => []];
    // Reset on date change so a new trading day gets a fresh "seen" set.
    if (($decoded['date'] ?? '') !== date('Y-m-d')) {
        return ['date' => date('Y-m-d'), 'gainers' => [], 'losers' => []];
    }
    return [
        'date'    => $decoded['date'],
        'gainers' => is_array($decoded['gainers'] ?? null) ? $decoded['gainers'] : [],
        'losers'  => is_array($decoded['losers']  ?? null) ? $decoded['losers']  : [],
    ];
}

function savePrakashTopSeen(array $seen, string $path): void
{
    $base = getStorageBasePath();
    if (!is_dir($base)) mkdir($base, 0755, true);
    file_put_contents($path, json_encode($seen, JSON_PRETTY_PRINT));
}

// Returns symbols that are in the *current* Top N Gainers / Losers
// but have not been seen in the Top N at any earlier iteration today.
// Persists the updated seen-set so a stock that enters on iter 2 and
// stays in the Top N through iter 5 will only fire on iter 2.
function prakashDetectNewEntries(array $topGainers, array $topLosers, string $path): array
{
    $seen = loadPrakashTopSeen($path);

    $newBuy = [];
    foreach ($topGainers as $sym) {
        if ($sym === '') continue;
        if (empty($seen['gainers'][$sym])) {
            $newBuy[] = $sym;
            $seen['gainers'][$sym] = true;
        }
    }
    $newSell = [];
    foreach ($topLosers as $sym) {
        if ($sym === '') continue;
        if (empty($seen['losers'][$sym])) {
            $newSell[] = $sym;
            $seen['losers'][$sym] = true;
        }
    }

    $seen['date'] = date('Y-m-d');
    savePrakashTopSeen($seen, $path);

    return ['buy' => $newBuy, 'sell' => $newSell];
}

// Counts how many of the most recent iterations (up to
// PRAKASH_MOMENTUM_LOOKBACK) a symbol appeared in the top-N ranks
// of the most recent iteration. Uses currentRank for the present
// iteration and the per-iteration rank map for prior ones. Used as
// the 'consecutive' factor of the confidence score.
function prakashConsecutiveTopNCount(string $symbol, array $rankHistory, int $currentRank, int $topN): int
{
    $consecutive = 0;
    $iters = $rankHistory['iterations'] ?? [];
    // Walk from newest to oldest; the freshest iteration is currentRank.
    $present = $currentRank > 0 && $currentRank <= $topN;
    if (!$present) return 0;
    $consecutive = 1;
    // We don't have the current iteration in $rankHistory yet at the
    // point this helper is called (it's appended after), so walk the
    // stored window and stop at the first miss.
    $reversed = array_reverse($iters);
    foreach ($reversed as $iter) {
        $ranks = is_array($iter['ranks'] ?? null) ? $iter['ranks'] : [];
        $r = $ranks[$symbol] ?? null;
        if ($r === null) break; // not present in this refresh at all
        if ((int)$r > $topN) break; // was in rank-history but not in Top N
        $consecutive++;
        if ($consecutive >= PRAKASH_MOMENTUM_LOOKBACK) break;
    }
    return min($consecutive, PRAKASH_MOMENTUM_LOOKBACK);
}

// ── Confidence score ──────────────────────────────────────────
// Spec's four factors, weighted and clamped to 0..100. The same helper
// is used for both Buy and Sell candidates; rank-movement is normalized
// against the side — a Buy wants a *lower* current rank than its
// starting rank, a Sell wants a *higher* one. Returns a float rounded
// to 1 decimal so the dashboard can show it directly.
function prakashConfidenceScore(array $factors, string $side): float
{
    $consecutive = max(0, min(PRAKASH_MOMENTUM_LOOKBACK, (int)($factors['consecutive'] ?? 0)));
    $rankMovement = (int)($factors['rank_movement'] ?? 0); // signed: +ve = ranks got worse (rank number grew)
    $magnitude = abs((float)($factors['magnitude'] ?? 0));
    $isNewEntry = !empty($factors['is_new_entry']);

    $wConsec = (int)(defined('PRAKASH_CONFIDENCE_W_CONSECUTIVE') ? PRAKASH_CONFIDENCE_W_CONSECUTIVE : 40);
    $wMove   = (int)(defined('PRAKASH_CONFIDENCE_W_RANK_MOVE')   ? PRAKASH_CONFIDENCE_W_RANK_MOVE   : 25);
    $wMag    = (int)(defined('PRAKASH_CONFIDENCE_W_MAGNITUDE')   ? PRAKASH_CONFIDENCE_W_MAGNITUDE   : 20);
    $wNew    = (int)(defined('PRAKASH_CONFIDENCE_W_NEW_ENTRY')   ? PRAKASH_CONFIDENCE_W_NEW_ENTRY   : 15);
    $magCap  = (float)(defined('PRAKASH_CONFIDENCE_MAGNITUDE_CAP') ? PRAKASH_CONFIDENCE_MAGNITUDE_CAP : 5.0);

    $maxWeight = max(1, $wConsec + $wMove + $wMag + $wNew);

    // Consecutive: 5/5 -> 1.0
    $sConsec = $consecutive / PRAKASH_MOMENTUM_LOOKBACK;

    // Rank movement: depends on side. Take |rank_movement| for the
    // sub-score magnitude, then normalise by the worst case (= topN,
    // i.e. it moved from #1 to #(topN) or vice versa).
    $topNRef = (int)(defined('PRAKASH_TOP_N') ? PRAKASH_TOP_N : 10);
    $sMove = min(1.0, abs($rankMovement) / max(1, $topNRef));
    // For Buy: a positive rank_movement (rank got worse) shouldn't
    // count toward Buy confidence. We treat the sign as a gate instead:
    // if it's the "wrong" sign, halve the sub-score (still possible
    // score from other factors, but the move sub-score should reflect
    // a "wrong" direction as near-zero).
    $wrongSign = ($side === 'Buy' && $rankMovement > 0) || ($side === 'Sell' && $rankMovement < 0);
    if ($wrongSign) $sMove *= 0.25;

    // Magnitude: cap at magCap, scale 0..1
    $sMag = $magCap > 0 ? min(1.0, $magnitude / $magCap) : 0.0;

    // New-entry: binary boost
    $sNew = $isNewEntry ? 1.0 : 0.0;

    $weighted = $sConsec * $wConsec + $sMove * $wMove + $sMag * $wMag + $sNew * $wNew;
    $score = ($weighted / $maxWeight) * 100.0;
    if ($score < 0) $score = 0;
    if ($score > 100) $score = 100;
    return round($score, 1);
}

function appendPrakashHistory(array $entry, string $historyPath): void
{
    $base = getStorageBasePath();
    if (!is_dir($base)) mkdir($base, 0755, true);
    $history = [];
    if (file_exists($historyPath)) {
        $decoded = json_decode((string)file_get_contents($historyPath), true);
        if (is_array($decoded)) $history = $decoded;
    }
    $history[] = $entry;
    file_put_contents($historyPath, json_encode($history, JSON_PRETTY_PRINT));
}

// ── Bad-tick guard ──────────────────────────────────────────────
// A single flaky read from one of the fallback data sources (BSE/Stooq/NSE/
// Groww) can occasionally return a garbage price for one refresh. Because
// entry price and "target hit" are both locked in permanently the moment
// they're recorded, one bad tick used to be able to freeze a wrong entry
// price or falsely mark a target as achieved for the rest of the day.
// This checks the live price against that stock's own previous close
// (derived from its reported change_pct) and rejects it if the implied
// move is bigger than PRAKASH_MAX_PLAUSIBLE_MOVE_PCT — in that case the
// caller should skip using this tick rather than trust it.
function prakashIsPricePlausible(array $stock): bool
{
    $price = (float)($stock['price'] ?? 0);
    if ($price <= 0) return false;
    $changePct = $stock['change_pct'] ?? $stock['percentage_change'] ?? null;
    if ($changePct === null) return true; // nothing to compare against, allow it
    $changePct = (float)$changePct;
    $prevClose = $changePct > -100 ? $price / (1 + $changePct / 100) : null;
    if (!$prevClose || $prevClose <= 0) return true; // can't derive a sane baseline, allow it
    $maxMove = defined('PRAKASH_MAX_PLAUSIBLE_MOVE_PCT') ? PRAKASH_MAX_PLAUSIBLE_MOVE_PCT : 12.0;
    $impliedMovePct = abs($price - $prevClose) / $prevClose * 100;
    return $impliedMovePct <= $maxMove;
}

// ── Momentum detection ─────────────────────────────────────────
// For each symbol that appears in every iteration of the lookback window,
// compute the rank trend across the window. A "consistent up" trend means
// the rank number got *smaller* (i.e. closer to #1) at every step — e.g.
// 5 → 4 → 3 → 2 → 1. A "consistent down" trend is the inverse.
//
// We require strictly monotonic rank improvement/deterioration across the
// window so a single noisy refresh doesn't get to call it momentum. Stocks
// that don't have the full PRAKASH_MOMENTUM_LOOKBACK iterations of rank
// history yet (i.e. recent market opens) are simply skipped — they show
// up again on the next refresh once more history exists.
function prakashDetectMomentum(array $rankHistory, array $currentRanks): array
{
    $iters = $rankHistory['iterations'] ?? [];
    $lookback = PRAKASH_MOMENTUM_LOOKBACK;
    if (count($iters) < $lookback) {
        return ['up' => [], 'down' => []];
    }

    // Build a per-symbol series of the last $lookback ranks, oldest first.
    $series = []; // symbol => [rank_oldest, ..., rank_newest]
    foreach ($iters as $i => $iter) {
        $ranks = is_array($iter['ranks'] ?? null) ? $iter['ranks'] : [];
        foreach ($ranks as $symbol => $rank) {
            $series[$symbol][$i] = (int)$rank;
        }
    }

    $up = [];
    $down = [];
    foreach ($series as $symbol => $rankSteps) {
        if (count($rankSteps) < $lookback) continue; // missed a refresh — not enough to call
        $ks = array_keys($rankSteps);
        sort($ks);
        $ordered = [];
        foreach ($ks as $k) $ordered[] = $rankSteps[$k];

        // Strictly decreasing rank number (e.g. 12, 10, 7, 5, 2) = up.
        $isUp = true;
        $improvementSum = 0; // how many ranks the stock climbed
        for ($i = 1; $i < $lookback; $i++) {
            if ($ordered[$i] >= $ordered[$i - 1]) { $isUp = false; break; }
            $improvementSum += $ordered[$i - 1] - $ordered[$i];
        }
        $isDown = true;
        $declineSum = 0;
        for ($i = 1; $i < $lookback; $i++) {
            if ($ordered[$i] <= $ordered[$i - 1]) { $isDown = false; break; }
            $declineSum += $ordered[$i] - $ordered[$i - 1];
        }

        $currentRank = $currentRanks[$symbol] ?? end($ordered);
        if ($isUp) {
            $up[] = [
                'symbol' => $symbol,
                'current_rank' => (int)$currentRank,
                'ranks' => $ordered,
                'momentum' => $improvementSum,
                'reason' => 'Sustained Upward Momentum',
            ];
        } elseif ($isDown) {
            $down[] = [
                'symbol' => $symbol,
                'current_rank' => (int)$currentRank,
                'ranks' => $ordered,
                'momentum' => $declineSum,
                'reason' => 'Sustained Downward Momentum',
            ];
        }
    }

    // Strongest-momentum first.
    usort($up, fn($a, $b) => $b['momentum'] <=> $a['momentum']);
    usort($down, fn($a, $b) => $b['momentum'] <=> $a['momentum']);

    return ['up' => $up, 'down' => $down];
}

// ── Main entry point ──────────────────────────────────────────
//
// Strategy (per spec):
//   • Iteration 1 at 9:10 AM — sort all stocks by % change desc. Top = Buy,
//     bottom = Sell. Lock in those as the day's first recommendations.
//   • Subsequent iterations (every ~5 min) — for any stock that has a full
//     PRAKASH_MOMENTUM_LOOKBACK (=5) rank history, check for sustained
//     upward/downward momentum. That's the trigger for a new Buy/Sell.
//   • Every locked-in Buy/Sell carries a target price (entry × target_mult).
//     On every refresh, recompute status from the live price:
//       - "Active"  : target not yet hit
//       - "Target Hit" : live price reached/passed the target
//   • The existing daily file, the existing history log, and the
//     `closePrakashDailyIfNeeded()` end-of-day rollup continue to work —
//     we just route the entry decisions through the new strategy.
function buildPrakashRecommendations(array $stocks, ?string $statePath = null, ?string $historyPath = null, ?string $username = null, ?string $rankHistoryPath = null, ?string $topSeenPath = null): array
{
    $statePath     = $statePath     ?? prakashRecommendationStateFile($username);
    $historyPath   = $historyPath   ?? prakashRecommendationHistoryFile($username);
    $rankHistPath  = $rankHistoryPath ?? prakashRankHistoryFile($username);

    $tracked = array_values(array_filter($stocks, fn($stock) => is_array($stock)));
    $maxTracked = defined('PRAKASH_MAX_TRACKED') ? PRAKASH_MAX_TRACKED : 20;
    if ($maxTracked > 0 && count($tracked) > $maxTracked) {
        $tracked = array_slice($tracked, 0, $maxTracked);
    }

    if (empty($tracked)) {
        return [
            'buy_recommendation'  => null,
            'sell_recommendation' => null,
            'top_gainer'          => null,
            'top_loser'           => null,
            'rank_movement_buy'   => null,
            'rank_movement_sell'  => null,
            'buy_box'             => [],
            'sell_box'            => [],
            'top5_buy'            => [],
            'top5_sell'           => [],
            'daily_summary'       => null,
            'tracked_count'       => 0,
            'iteration'           => 0,
            'is_initial_iteration' => true,
        ];
    }

    usort($tracked, fn($a, $b) => ((float)($b['change_pct'] ?? 0)) <=> ((float)($a['change_pct'] ?? 0)));

    $topGainer = $tracked[0] ?? null;
    $topLoser  = $tracked[count($tracked) - 1] ?? null;

    $currentRanks = [];
    $currentChanges = [];
    $currentPrices = [];
    $stocksBySymbol = [];
    foreach ($tracked as $index => $stock) {
        $symbol = prakashNormalizeSymbol((string)($stock['symbol'] ?? ''));
        if ($symbol !== '') {
            $currentRanks[$symbol] = $index + 1;
            $currentChanges[$symbol] = (float)($stock['change_pct'] ?? 0);
            $currentPrices[$symbol] = (float)($stock['price'] ?? 0);
            $stocksBySymbol[$symbol] = $stock;
        }
    }

    $previousState = loadPrakashState($statePath);
    $prevRanks   = is_array($previousState['ranks'] ?? null)   ? $previousState['ranks']   : [];
    $prevChanges = is_array($previousState['changes'] ?? null) ? $previousState['changes'] : [];

    // ── Single-refresh rank-movement signal (kept for top-level fields
    // buy_recommendation / sell_recommendation fallback and the dashboard
    // cards that display "Rank X → Y". Momentum detection below is the
    // primary Buy/Sell trigger; rank movement is the secondary one.) ──
    $rankMovementBuy = null;
    $rankMovementSell = null;
    $bestBuyImprovement = null;
    $bestSellDecline = null;
    foreach ($currentRanks as $symbol => $currentRank) {
        $previousRank = $prevRanks[$symbol] ?? null;
        $stock = $stocksBySymbol[$symbol] ?? null;
        if (!$stock) continue;
        $difference = $previousRank === null ? null : ((int)$previousRank - (int)$currentRank);
        if ($difference !== null && $difference !== 0) {
            $entry = [
                'symbol' => $symbol,
                'price' => (float)($stock['price'] ?? 0),
                'percentage_change' => (float)($stock['change_pct'] ?? 0),
                'previous_rank' => (int)$previousRank,
                'current_rank' => (int)$currentRank,
                'rank_difference' => $difference,
            ];
            if ($difference > 0) {
                if ($rankMovementBuy === null || $difference > ($rankMovementBuy['rank_difference'] ?? 0)) {
                    $rankMovementBuy = $entry;
                }
            } elseif ($difference < 0) {
                if ($rankMovementSell === null || $difference < ($rankMovementSell['rank_difference'] ?? 0)) {
                    $rankMovementSell = $entry;
                }
            }
        } else {
            $prevChange = $prevChanges[$symbol] ?? null;
            $currChange = $currentChanges[$symbol] ?? null;
            if ($prevChange !== null && $currChange !== null) {
                $changeDelta = (float)$currChange - (float)$prevChange;
                if ($changeDelta > 0) {
                    if ($bestBuyImprovement === null || $changeDelta > $bestBuyImprovement) {
                        $bestBuyImprovement = $changeDelta;
                        $rankMovementBuy = [
                            'symbol' => $symbol,
                            'price' => (float)($stock['price'] ?? 0),
                            'percentage_change' => (float)($stock['change_pct'] ?? 0),
                            'previous_rank' => $previousRank !== null ? (int)$previousRank : (int)$currentRank,
                            'current_rank' => (int)$currentRank,
                            'rank_difference' => $previousRank !== null ? ((int)$previousRank - (int)$currentRank) : 0,
                        ];
                    }
                } elseif ($changeDelta < 0) {
                    if ($bestSellDecline === null || $changeDelta < $bestSellDecline) {
                        $bestSellDecline = $changeDelta;
                        $rankMovementSell = [
                            'symbol' => $symbol,
                            'price' => (float)($stock['price'] ?? 0),
                            'percentage_change' => (float)($stock['change_pct'] ?? 0),
                            'previous_rank' => $previousRank !== null ? (int)$previousRank : (int)$currentRank,
                            'current_rank' => (int)$currentRank,
                            'rank_difference' => $previousRank !== null ? ((int)$previousRank - (int)$currentRank) : 0,
                        ];
                    }
                }
            }
        }
    }

    // ── Append the current iteration to the rank-history window BEFORE
    // we read momentum — that way the current refresh's ranks are part of
    // the trend the detector sees, and a stock that's been moving up
    // *into* this refresh can fire on it. ──
    $rankHistory = appendPrakashRankIteration($currentRanks, $rankHistPath, $currentChanges, $currentPrices);
    $iterationNumber = count($rankHistory['iterations']);
    $isInitial = $iterationNumber <= 1;

    // ── Top-N Gainers / Losers + new-entry detection ───────────────
    // The spec's "Top 10 Gainers / Top 10 Losers" tracking layer, separate
    // from the display box (PRAKASH_MAX_PER_BOX=5). The new-entry
    // detector fires a Buy/Sell signal exactly once per symbol per day
    // the first time a stock enters the Top N. The seen-set is persisted
    // per-user (and globally for the no-username case) so a stock that
    // re-enters the Top N later in the day doesn't re-fire.
    $topN = (int)(defined('PRAKASH_TOP_N') ? PRAKASH_TOP_N : 10);
    $topNLists = prakashTopGainersLosers($tracked, $topN);
    $topGainersList = $topNLists['gainers'];
    $topLosersList  = $topNLists['losers'];
    $topSeenPath = $topSeenPath ?? prakashTopSeenFile($username);
    $newEntries = prakashDetectNewEntries($topGainersList, $topLosersList, $topSeenPath);
    $newEntryBuySymbols  = $newEntries['buy'];
    $newEntrySellSymbols = $newEntries['sell'];

    // ── Point-score Momentum Ranking Engine (Signals 1-9) ──────────────
    // Runs for every tracked stock, independent of whether it made the
    // momentum/new-entry candidate lists below. This is the additive
    // "sum every signal into a point total, then bucket into star tiers"
    // scoring the spec describes, layered on top of (not replacing)
    // Prakash's existing 0..100 confidence score above.
    $topGainersSet = array_flip($topGainersList);
    $topLosersSet  = array_flip($topLosersList);
    $newEntryBuySet  = array_flip($newEntryBuySymbols);
    $newEntrySellSet = array_flip($newEntrySellSymbols);
    $totalTrackedCount = count($tracked);

    $pointScores = []; // symbol => ['Buy' => [...msScoreStock result...], 'Sell' => [...]]
    foreach ($currentRanks as $symbol => $rank) {
        $stock = $stocksBySymbol[$symbol] ?? null;
        if (!$stock) continue;
        $volRatio = isset($stock['vol_ratio']) && is_numeric($stock['vol_ratio']) ? (float)$stock['vol_ratio'] : null;
        $rankSeries   = prakashFieldSeries($rankHistory, $symbol, 'ranks');
        $changeSeries = prakashFieldSeries($rankHistory, $symbol, 'changes');
        $priceSeries  = prakashFieldSeries($rankHistory, $symbol, 'prices');
        $consecutiveTopN = prakashConsecutiveTopNCount($symbol, $rankHistory, (int)$rank, $topN);

        $baseCtx = [
            'rank' => (int)$rank,
            'totalCount' => $totalTrackedCount,
            'consecutive' => $consecutiveTopN,
            'lookback' => PRAKASH_MOMENTUM_LOOKBACK,
            'volRatio' => $volRatio,
            'rankSeries' => $rankSeries,
            'changeSeries' => $changeSeries,
            'priceSeries' => $priceSeries,
        ];

        $pointScores[$symbol] = [
            'Buy' => msScoreStock($baseCtx + [
                'inTopN' => isset($topGainersSet[$symbol]),
                'isNewEntry' => isset($newEntryBuySet[$symbol]),
            ], 'Buy'),
            'Sell' => msScoreStock($baseCtx + [
                'inTopN' => isset($topLosersSet[$symbol]),
                'isNewEntry' => isset($newEntrySellSet[$symbol]),
            ], 'Sell'),
        ];
    }

    // "Pick of the Day": Top 5 Buy + Top 5 Sell across the *whole* tracked
    // universe, ranked by point score — not just the momentum/new-entry
    // box below, so a stock with a strong point score but no fresh
    // momentum trigger this exact refresh still surfaces here.
    //
    // A stock is scored independently for both sides (its Buy-side score
    // and Sell-side score use different signal formulas, so both can come
    // out non-trivial for a choppy/volatile stock), but showing the SAME
    // stock in both the Buy leaderboard and the Sell leaderboard at once
    // is not an actionable signal for a trader — you can't act on "buy
    // this AND sell this right now" for one stock. So each stock is only
    // ever placed on the side it scores strictly higher on; a tie falls
    // back to whichever side currently holds the leaderboard-extreme rank
    // (top gainer -> Buy, top loser -> Sell) to break it deterministically.
    $topPicksCount = (int)(defined('MS_TOP_PICKS_COUNT') ? MS_TOP_PICKS_COUNT : 5);
    $buyPicks = [];
    $sellPicks = [];
    foreach ($pointScores as $symbol => $sides) {
        $stock = $stocksBySymbol[$symbol] ?? null;
        if (!$stock) continue;
        $buyScore = $sides['Buy']['score'];
        $sellScore = $sides['Sell']['score'];
        if ($buyScore === $sellScore) {
            $dominant = isset($topGainersSet[$symbol]) ? 'Buy' : (isset($topLosersSet[$symbol]) ? 'Sell' : 'Buy');
        } else {
            $dominant = $buyScore > $sellScore ? 'Buy' : 'Sell';
        }
        if ($dominant === 'Buy') {
            $buyPicks[] = [
                'symbol' => $symbol,
                'price' => (float)($stock['price'] ?? 0),
                'percentage_change' => (float)($stock['change_pct'] ?? 0),
                'rank' => $currentRanks[$symbol] ?? null,
                'score' => $sides['Buy']['score'],
                'stars' => $sides['Buy']['stars'],
                'tier' => $sides['Buy']['tier'],
                'signals' => $sides['Buy']['signals'],
            ];
        } else {
            $sellPicks[] = [
                'symbol' => $symbol,
                'price' => (float)($stock['price'] ?? 0),
                'percentage_change' => (float)($stock['change_pct'] ?? 0),
                'rank' => $currentRanks[$symbol] ?? null,
                'score' => $sides['Sell']['score'],
                'stars' => $sides['Sell']['stars'],
                'tier' => $sides['Sell']['tier'],
                'signals' => $sides['Sell']['signals'],
            ];
        }
    }
    usort($buyPicks, fn($a, $b) => $b['score'] <=> $a['score']);
    usort($sellPicks, fn($a, $b) => $b['score'] <=> $a['score']);
    $top5Buy  = array_slice($buyPicks, 0, $topPicksCount);
    $top5Sell = array_slice($sellPicks, 0, $topPicksCount);

    // ── Build the Buy/Sell candidate sets for this refresh. ──
    //
    // Initial iteration: top-of-leaderboard = Buy, bottom = Sell. No
    // momentum is possible yet because the lookback window is empty.
    //
    // Later iterations: pull from the momentum detector. If the window
    // is full enough, a stock with sustained upward momentum across
    // all 5 iterations is a Buy candidate; sustained downward momentum
    // is a Sell candidate. New entries into the Top N (computed above)
    // are appended as additional candidates so the spec's "first-time
    // entry" rule is captured, with the source tagged in `reason`.
    $buyCandidates = [];
    $sellCandidates = [];
    $momentumUp = [];
    $momentumDown = [];
    $buyCandidateSources = [];   // symbol => 'momentum' | 'new_entry' | 'initial'
    $sellCandidateSources = [];
    $newEntryBuyCandidates  = [];  // raw momentum-style rows for new entries
    $newEntrySellCandidates = [];

    // closure captures the current changes map by value (it's a plain
    // array, copies are cheap) and the rank-history by value too.
    $computeConfidence = function (string $symbol, string $side, bool $isNewEntry) use ($currentChanges, $rankHistory, $currentRanks) {
        $currentRank = $currentRanks[$symbol] ?? 0;
        // "Starting rank" for the rank-movement factor: the oldest rank
        // in the lookback window for this symbol. If the symbol was
        // present throughout, use that; else fall back to current.
        $iters = $rankHistory['iterations'] ?? [];
        $startRank = $currentRank;
        $present = 0;
        foreach ($iters as $iter) {
            $ranks = is_array($iter['ranks'] ?? null) ? $iter['ranks'] : [];
            if (isset($ranks[$symbol])) {
                if ($startRank === $currentRank) $startRank = (int)$ranks[$symbol];
                $present++;
            }
        }
        $rankMovement = $currentRank - $startRank; // +ve = rank got worse
        $consecutive = prakashConsecutiveTopNCount($symbol, $rankHistory, $currentRank, (int)(defined('PRAKASH_TOP_N') ? PRAKASH_TOP_N : 10));
        $magnitude = (float)($currentChanges[$symbol] ?? 0);
        return prakashConfidenceScore([
            'consecutive'    => $consecutive,
            'rank_movement'  => $rankMovement,
            'magnitude'      => $magnitude,
            'is_new_entry'   => $isNewEntry,
        ], $side);
    };

    if ($isInitial) {
        // Cap the initial-iteration box at the configured size, with the
        // explicit top/bottom as the headline pick and the rest of the
        // leaderboard as additional box entries. Confidence is 0 for
        // initial-iteration picks — the spec only awards confidence once
        // a stock has had a chance to be re-observed across iterations.
        $boxCap = (int)(defined('PRAKASH_MAX_PER_BOX') ? PRAKASH_MAX_PER_BOX : 5);
        foreach (array_slice($tracked, 0, $boxCap) as $s) {
            $sym = prakashNormalizeSymbol((string)($s['symbol'] ?? ''));
            if ($sym === '') continue;
            $buyCandidates[] = [
                'symbol' => $sym,
                'price' => (float)($s['price'] ?? 0),
                'percentage_change' => (float)($s['change_pct'] ?? 0),
                'reason' => 'Initial Top Gainer',
                'strength' => 0,
                'confidence' => 0.0,
            ];
            $buyCandidateSources[$sym] = 'initial';
        }
        $bottomSlice = array_reverse(array_slice($tracked, -$boxCap));
        foreach ($bottomSlice as $s) {
            $sym = prakashNormalizeSymbol((string)($s['symbol'] ?? ''));
            if ($sym === '') continue;
            $sellCandidates[] = [
                'symbol' => $sym,
                'price' => (float)($s['price'] ?? 0),
                'percentage_change' => (float)($s['change_pct'] ?? 0),
                'reason' => 'Initial Top Loser',
                'strength' => 0,
                'confidence' => 0.0,
            ];
            $sellCandidateSources[$sym] = 'initial';
        }
    } else {
        $mom = prakashDetectMomentum($rankHistory, $currentRanks);
        $momentumUp = $mom['up'];
        $momentumDown = $mom['down'];
        $boxCap = (int)(defined('PRAKASH_MAX_PER_BOX') ? PRAKASH_MAX_PER_BOX : 5);
        foreach (array_slice($momentumUp, 0, $boxCap) as $m) {
            $stock = $stocksBySymbol[$m['symbol']] ?? null;
            if (!$stock) continue;
            $sym = $m['symbol'];
            $buyCandidates[] = [
                'symbol' => $sym,
                'price' => (float)($stock['price'] ?? 0),
                'percentage_change' => (float)($stock['change_pct'] ?? 0),
                'previous_rank' => $m['ranks'][0] ?? null,
                'current_rank' => $m['current_rank'],
                'rank_difference' => ($m['ranks'][0] ?? 0) - $m['current_rank'],
                'reason' => $m['reason'],
                'momentum' => $m['momentum'],
                'strength' => $m['momentum'],
                'confidence' => $computeConfidence($sym, 'Buy', false),
            ];
            $buyCandidateSources[$sym] = 'momentum';
        }
        foreach (array_slice($momentumDown, 0, $boxCap) as $m) {
            $stock = $stocksBySymbol[$m['symbol']] ?? null;
            if (!$stock) continue;
            $sym = $m['symbol'];
            $sellCandidates[] = [
                'symbol' => $sym,
                'price' => (float)($stock['price'] ?? 0),
                'percentage_change' => (float)($stock['change_pct'] ?? 0),
                'previous_rank' => $m['ranks'][0] ?? null,
                'current_rank' => $m['current_rank'],
                'rank_difference' => $m['current_rank'] - ($m['ranks'][0] ?? 0),
                'reason' => $m['reason'],
                'momentum' => $m['momentum'],
                'strength' => $m['momentum'],
                'confidence' => $computeConfidence($sym, 'Sell', false),
            ];
            $sellCandidateSources[$sym] = 'momentum';
        }

        // New-entry candidates: append to the box until the display cap
        // is reached. A new-entry symbol that's already a momentum pick
        // is skipped here (momentum takes priority — the box is a
        // display surface, not a duplicate slot).
        foreach ($newEntryBuySymbols as $sym) {
            if (count($buyCandidates) >= $boxCap) break;
            if (isset($buyCandidateSources[$sym])) continue;
            $stock = $stocksBySymbol[$sym] ?? null;
            if (!$stock) continue;
            $cr = $currentRanks[$sym] ?? null;
            $buyCandidates[] = [
                'symbol' => $sym,
                'price' => (float)($stock['price'] ?? 0),
                'percentage_change' => (float)($stock['change_pct'] ?? 0),
                'previous_rank' => null,
                'current_rank' => $cr,
                'rank_difference' => null,
                'reason' => 'New Top-10 Entry',
                'momentum' => 0,
                'strength' => 0,
                'confidence' => $computeConfidence($sym, 'Buy', true),
            ];
            $buyCandidateSources[$sym] = 'new_entry';
        }
        foreach ($newEntrySellSymbols as $sym) {
            if (count($sellCandidates) >= $boxCap) break;
            if (isset($sellCandidateSources[$sym])) continue;
            $stock = $stocksBySymbol[$sym] ?? null;
            if (!$stock) continue;
            $cr = $currentRanks[$sym] ?? null;
            $sellCandidates[] = [
                'symbol' => $sym,
                'price' => (float)($stock['price'] ?? 0),
                'percentage_change' => (float)($stock['change_pct'] ?? 0),
                'previous_rank' => null,
                'current_rank' => $cr,
                'rank_difference' => null,
                'reason' => 'New Top-10 Entry',
                'momentum' => 0,
                'strength' => 0,
                'confidence' => $computeConfidence($sym, 'Sell', true),
            ];
            $sellCandidateSources[$sym] = 'new_entry';
        }
    }
    // (No global state to clean up — confidence helper captures by value.)

    // Attach the point-score engine's output to every box entry, so the
    // dashboard can show both Prakash's existing 0..100 confidence AND
    // the spec's star-tier point score side by side.
    foreach ($buyCandidates as &$c) {
        $sym = $c['symbol'] ?? '';
        $ps = $pointScores[$sym]['Buy'] ?? null;
        if ($ps) {
            $c['point_score'] = $ps['score'];
            $c['stars'] = $ps['stars'];
            $c['tier'] = $ps['tier'];
        }
    }
    unset($c);
    foreach ($sellCandidates as &$c) {
        $sym = $c['symbol'] ?? '';
        $ps = $pointScores[$sym]['Sell'] ?? null;
        if ($ps) {
            $c['point_score'] = $ps['score'];
            $c['stars'] = $ps['stars'];
            $c['tier'] = $ps['tier'];
        }
    }
    unset($c);

    // ── Rank-based Top Gainer / Top Loser signal ──────────────────────
    // Runs on EVERY iteration (including the initial one), independent
    // of the 5-iteration momentum system above. Whichever stock is
    // currently in position #1 (top gainer) or the last position (top
    // loser) after sorting by change_pct fires as an immediate Buy/Sell.
    // Unlike momentum, this has no lookback requirement and no "once
    // per day" dedup on the signal itself — if the *same* stock keeps
    // holding #1 across many refreshes in a row it keeps firing every
    // time. (The underlying daily entry-price/target tracking still
    // only locks in once per symbol via registerRecommendation's
    // existing dedup — this only affects the box/history feed.)
    $rankBuyCandidate = null;
    $rankSellCandidate = null;
    if ($topGainer) {
        $sym = prakashNormalizeSymbol((string)($topGainer['symbol'] ?? ''));
        if ($sym !== '') {
            $rankBuyCandidate = [
                'symbol' => $sym,
                'price' => (float)($topGainer['price'] ?? 0),
                'percentage_change' => (float)($topGainer['change_pct'] ?? 0),
                'reason' => $isInitial ? 'Initial Top Gainer' : 'Current Top Gainer (Rank #1)',
                'strength' => 0,
                'confidence' => $isInitial ? 0.0 : $computeConfidence($sym, 'Buy', empty($buyCandidateSources[$sym])),
            ];
            // Surface it in the display box too if it isn't already there
            // (from momentum or new-entry) and there's room under the cap.
            if (!isset($buyCandidateSources[$sym]) && count($buyCandidates) < $boxCap) {
                $buyCandidates[] = $rankBuyCandidate;
                $buyCandidateSources[$sym] = 'rank';
            }
        }
    }
    if ($topLoser) {
        $sym = prakashNormalizeSymbol((string)($topLoser['symbol'] ?? ''));
        if ($sym !== '') {
            $rankSellCandidate = [
                'symbol' => $sym,
                'price' => (float)($topLoser['price'] ?? 0),
                'percentage_change' => (float)($topLoser['change_pct'] ?? 0),
                'reason' => $isInitial ? 'Initial Top Loser' : 'Current Top Loser (Rank Last)',
                'strength' => 0,
                'confidence' => $isInitial ? 0.0 : $computeConfidence($sym, 'Sell', empty($sellCandidateSources[$sym])),
            ];
            if (!isset($sellCandidateSources[$sym]) && count($sellCandidates) < $boxCap) {
                $sellCandidates[] = $rankSellCandidate;
                $sellCandidateSources[$sym] = 'rank';
            }
        }
    }

    // ── Intraday target + status tracking ────────────────────────────
    // Only the actual headline Buy/Sell recommendation gets logged for the
    // day. The larger buy/sell boxes are still returned for display, but a
    // stock is tracked once per day with a single entry price/target/status.
    $dailyPath = prakashDailyFile($username);
    $daily = loadPrakashDaily($dailyPath);
    $todayStr = date('Y-m-d');
    if (($daily['date'] ?? '') !== $todayStr || $isInitial) {
        // Start a fresh daily log for a new day or a fresh initial iteration.
        // This keeps the first 9:10 AM recommendations from being mixed with
        // any previous same-day run that was restarted or replayed.
        $daily = ['date' => $todayStr, 'recommendations' => [], 'closed' => false];
    }

    // Map symbol => list of indices into $daily['recommendations'] (a symbol
    // can have MORE THAN ONE entry per day now — see $registerRecommendation
    // below for why).
    $recsBySymbol = [];
    foreach ($daily['recommendations'] as $i => $rec) {
        $recsBySymbol[$rec['symbol']][] = $i;
    }

    $now = date('Y-m-d H:i:s');

    $hydrateMomentumPick = function (array $pick) use ($stocksBySymbol) {
        $sym = $pick['symbol'];
        $stock = $stocksBySymbol[$sym] ?? null;
        $pick['price'] = $stock ? (float)($stock['price'] ?? 0) : null;
        $pick['percentage_change'] = $stock ? (float)($stock['change_pct'] ?? 0) : null;
        return $pick;
    };

    $buyHeadlineReason = 'Top Gainer';
    $sellHeadlineReason = 'Top Loser';
    if ($isInitial) {
        $buyHeadlineReason = 'Initial Top Gainer';
        $sellHeadlineReason = 'Initial Top Loser';
    } else {
        if (!empty($momentumUp)) {
            $buyHeadlineReason = $momentumUp[0]['reason'];
        }
        if (!empty($momentumDown)) {
            $sellHeadlineReason = $momentumDown[0]['reason'];
        }
    }

    $headlineBuyCandidate = $isInitial ? $topGainer : (!empty($momentumUp) ? $hydrateMomentumPick($momentumUp[0]) : $topGainer);
    $headlineSellCandidate = $isInitial ? $topLoser : (!empty($momentumDown) ? $hydrateMomentumPick($momentumDown[0]) : $topLoser);

    // A symbol is tracked once PER OPEN POSITION, not once per day. If its
    // most recent entry already hit target (or was closed), a fresh signal
    // for it starts a brand-new entry — so "Titan buy @4550→4600 hit, then
    // buy @4610→4660" shows up as TWO rows today instead of the second
    // signal being silently swallowed by the first one's dedup lock.
    $registerRecommendation = function (?array $candidate, string $side, string $reason, ?float $confidence = null) use (&$daily, &$recsBySymbol, $now) {
        if (!$candidate) return;
        $targetMult = $side === 'Buy' ? (1 + PRAKASH_TARGET_PCT / 100) : (1 - PRAKASH_TARGET_PCT / 100);
        $symbol = prakashDisplaySymbol((string)($candidate['symbol'] ?? ''));
        $livePrice = (float)($candidate['price'] ?? 0);

        if (!prakashIsPricePlausible($candidate)) return;

        $indices = $recsBySymbol[$symbol] ?? [];
        // Find this symbol's currently OPEN entry, if any (there is at most
        // one open entry per symbol at a time — a new one only ever gets
        // created once the prior one is no longer open).
        $openIdx = null;
        foreach ($indices as $i) {
            if (empty($daily['recommendations'][$i]['achieved'])) { $openIdx = $i; break; }
        }

        if ($openIdx !== null) {
            $rec = &$daily['recommendations'][$openIdx];
            if (($rec['side'] ?? '') !== $side) {
                // Already have an open opposite-side position on this
                // symbol — don't flip it, just leave it as is.
                unset($rec);
                return;
            }
            $rec['last_checked_price'] = $livePrice;
            if ($rec['status'] === 'Active' && !$rec['achieved']) {
                $hit = $rec['side'] === 'Buy' ? ($livePrice >= $rec['target_price']) : ($livePrice <= $rec['target_price']);
                if ($hit) {
                    $rec['achieved'] = true;
                    $rec['achieved_at'] = $now;
                    $rec['achieved_price'] = (float)$livePrice;
                    $rec['status'] = 'Target Hit';
                }
            }
            unset($rec);
            return;
        }

        // No open entry for this symbol (either it's brand new today, or
        // every prior entry already hit target) — start a fresh one.
        $entryPrice = (float)$livePrice;
        $targetPrice = (float)round($entryPrice * $targetMult, 2);
        $daily['recommendations'][] = [
            'symbol' => $symbol,
            'side' => $side,
            'reason' => $reason,
            'confidence' => $confidence,
            'entry_price' => $entryPrice,
            'entry_time' => $now,
            'entry_source' => $candidate['_source'] ?? null,
            'target_pct' => PRAKASH_TARGET_PCT,
            'target_price' => $targetPrice,
            'status' => 'Active',
            'achieved' => false,
            'achieved_at' => null,
            'achieved_price' => null,
            'last_checked_price' => $entryPrice,
            // How many times this symbol has been recommended today,
            // including this one — lets the UI label repeats ("2nd pick").
            'occurrence' => count($indices) + 1,
        ];
        $recsBySymbol[$symbol][] = count($daily['recommendations']) - 1;
    };

    // ── Register recommendations into the day's tracked file ──────────
    //
    // Initial iteration (spec item 1): exactly ONE Buy (top of leaderboard)
    // and ONE Sell (bottom of leaderboard) — the day's opening picks.
    //
    // Every later iteration (spec items 2 & 3): EVERY candidate currently
    // in the Buy/Sell box gets registered — not just the single strongest
    // one. The box already contains every stock with sustained Top-10
    // momentum (item 2) plus every stock that just entered the Top 10 for
    // the first time today (item 3), each already de-duplicated once
    // locked in (a symbol's side/entry/target never change after its first
    // registration — see the dedup branch above). Registering the whole
    // box, rather than only the highest-confidence entry, is what actually
    // lets "Pick of the Day" be a list of multiple concurrent Buy/Sell
    // opportunities, per the spec, rather than a single rotating pick.
    if ($isInitial) {
        $registerRecommendation($headlineBuyCandidate, 'Buy', $buyHeadlineReason, 0.0);
        $registerRecommendation($headlineSellCandidate, 'Sell', $sellHeadlineReason, 0.0);
    } else {
        foreach ($buyCandidates as $c) {
            $registerRecommendation(
                ['symbol' => $c['symbol'], 'price' => $c['price'], 'change_pct' => $c['percentage_change'] ?? null],
                'Buy',
                $c['reason'] ?? 'Momentum Buy',
                isset($c['confidence']) ? (float)$c['confidence'] : null
            );
        }
        foreach ($sellCandidates as $c) {
            $registerRecommendation(
                ['symbol' => $c['symbol'], 'price' => $c['price'], 'change_pct' => $c['percentage_change'] ?? null],
                'Sell',
                $c['reason'] ?? 'Momentum Sell',
                isset($c['confidence']) ? (float)$c['confidence'] : null
            );
        }
        // The rank-based signal (current #1 / current last position) is
        // registered unconditionally here too, even if the display box
        // was already full and it didn't get a slot above — dedup inside
        // registerRecommendation makes this a no-op price refresh if it
        // was already tracked from the box loop just above.
        if ($rankBuyCandidate) {
            $registerRecommendation(
                ['symbol' => $rankBuyCandidate['symbol'], 'price' => $rankBuyCandidate['price'], 'change_pct' => $rankBuyCandidate['percentage_change'] ?? null],
                'Buy',
                $rankBuyCandidate['reason'],
                (float)($rankBuyCandidate['confidence'] ?? 0.0)
            );
        }
        if ($rankSellCandidate) {
            $registerRecommendation(
                ['symbol' => $rankSellCandidate['symbol'], 'price' => $rankSellCandidate['price'], 'change_pct' => $rankSellCandidate['percentage_change'] ?? null],
                'Sell',
                $rankSellCandidate['reason'],
                (float)($rankSellCandidate['confidence'] ?? 0.0)
            );
        }
    }

    // Also re-check every already-tracked symbol today against the live
    // price we have this refresh, even if it fell out of the box (e.g. a
    // momentum pick from a previous refresh isn't in this refresh's box
    // but still has an open target). Only the symbol's OPEN entry (if any)
    // is touched — earlier, already-achieved entries for the same symbol
    // stay untouched as a historical record.
    foreach ($tracked as $stock) {
        $symbol = prakashDisplaySymbol((string)($stock['symbol'] ?? ''));
        if (!isset($recsBySymbol[$symbol])) continue;
        if (!prakashIsPricePlausible($stock)) continue; // ignore bad/glitched tick
        $openIdx = null;
        foreach ($recsBySymbol[$symbol] as $i) {
            if (empty($daily['recommendations'][$i]['achieved'])) { $openIdx = $i; break; }
        }
        if ($openIdx === null) continue;
        $rec = &$daily['recommendations'][$openIdx];
        if ($rec['achieved'] || ($rec['status'] ?? '') === 'Target Hit') { unset($rec); continue; }
        $livePrice = (float)($stock['price'] ?? 0);
        $rec['last_checked_price'] = $livePrice;
        $hit = $rec['side'] === 'Buy' ? ($livePrice >= $rec['target_price']) : ($livePrice <= $rec['target_price']);
        if ($hit) {
            $rec['achieved'] = true;
            $rec['achieved_at'] = $now;
            $rec['achieved_price'] = (float)$livePrice;
            $rec['achieved_source'] = $stock['_source'] ?? null;
            $rec['status'] = 'Target Hit';
        }
        unset($rec);
    }

    savePrakashDaily($daily, $dailyPath);

    $achievedCount = count(array_filter($daily['recommendations'], fn($r) => $r['achieved']));
    $totalCount = count($daily['recommendations']);
    $dailySummary = [
        'date' => $daily['date'],
        'total' => $totalCount,
        'achieved' => $achievedCount,
        'success_rate' => $totalCount > 0 ? round($achievedCount / $totalCount * 100, 1) : null,
        'closed' => $daily['closed'],
        'recommendations' => $daily['recommendations'],
    ];

    $dailyRecBySymbol = [];
    foreach ($daily['recommendations'] as $rec) {
        $sym = (string)($rec['symbol'] ?? '');
        if ($sym !== '') $dailyRecBySymbol[$sym] = $rec;
    }

    // ── Headline buy / sell recommendations (single pick, not the box). ──
    // On the initial iteration: top gainer / top loser by % change. On
    // later iterations: the strongest-momentum stock in each direction,
    // falling back to top gainer / top loser if momentum hasn't built up
    // enough to fire yet.
    $buyMomentumPick  = null;
    $sellMomentumPick = null;
    if (!empty($momentumUp)) {
        $buyMomentumPick = $hydrateMomentumPick($momentumUp[0]);
    }
    if (!empty($momentumDown)) {
        $sellMomentumPick = $hydrateMomentumPick($momentumDown[0]);
    }

    $buildHeadline = function (?array $stock, ?array $momentumPick, string $side, string $reason, ?float $confidence = null) use ($dailyRecBySymbol) {
        $dailyRec = null;
        $symbol = null;
        if ($momentumPick) {
            $symbol = prakashDisplaySymbol($momentumPick['symbol']);
            $dailyRec = $dailyRecBySymbol[$symbol] ?? null;
            $previousRank = $momentumPick['ranks'][0] ?? null;
            $currentRank = $momentumPick['current_rank'];
            return [
                'symbol' => $symbol,
                'price' => $momentumPick['price'] ?? null,
                'percentage_change' => $momentumPick['percentage_change'] ?? null,
                'recommendation' => $side,
                'reason' => $momentumPick['reason'],
                'previous_rank' => $previousRank,
                'current_rank' => $currentRank,
                'rank_difference' => $side === 'Buy'
                    ? ($previousRank - $currentRank)
                    : ($currentRank - $previousRank),
                'momentum' => $momentumPick['momentum'],
                'confidence' => $confidence,
                'current_market_price' => $momentumPick['price'] ?? null,
                'recommended_entry_price' => $dailyRec['entry_price'] ?? ($momentumPick['price'] ?? null),
                'target_price' => $dailyRec['target_price'] ?? null,
                'stop_loss' => $dailyRec['stop_loss'] ?? null,
                'time_of_recommendation' => $dailyRec['entry_time'] ?? null,
                'current_status' => $dailyRec['status'] ?? 'Active',
            ];
        }
        if (!$stock) return null;
        $symbol = prakashDisplaySymbol((string)($stock['symbol'] ?? ''));
        $dailyRec = $dailyRecBySymbol[$symbol] ?? null;
        $entryPrice = $dailyRec['entry_price'] ?? (float)($stock['price'] ?? 0);
        $stopLoss = $dailyRec['stop_loss'] ?? ($entryPrice > 0 ? round($entryPrice * ($side === 'Buy' ? (1 - PRAKASH_TARGET_PCT / 100) : (1 + PRAKASH_TARGET_PCT / 100)), 2) : null);
        return [
            'symbol' => $symbol,
            'price' => (float)($stock['price'] ?? 0),
            'percentage_change' => (float)($stock['change_pct'] ?? 0),
            'recommendation' => $side,
            'reason' => $reason,
            'previous_rank' => null,
            'current_rank' => null,
            'rank_difference' => null,
            'momentum' => null,
            'confidence' => $confidence,
            'current_market_price' => (float)($stock['price'] ?? 0),
            'recommended_entry_price' => $entryPrice,
            'target_price' => $dailyRec['target_price'] ?? null,
            'stop_loss' => $stopLoss,
            'time_of_recommendation' => $dailyRec['entry_time'] ?? null,
            'current_status' => $dailyRec['status'] ?? 'Active',
        ];
    };

    // Pick the highest-confidence Buy / Sell candidate from the box to
    // attach as the headline's `confidence`. Headline = top momentum pick
    // on non-initial iterations, or top gainer / top loser on the initial
    // iteration (where confidence is 0 by spec). Falls back to the
    // first box entry's confidence if neither headline source has one.
    $headlineBuyConfidence = null;
    if ($buyMomentumPick && isset($buyCandidateSources[$buyMomentumPick['symbol']])) {
        foreach ($buyCandidates as $c) {
            if (($c['symbol'] ?? null) === $buyMomentumPick['symbol']) {
                $headlineBuyConfidence = (float)($c['confidence'] ?? 0.0);
                break;
            }
        }
    }
    if ($headlineBuyConfidence === null && $isInitial) {
        $headlineBuyConfidence = 0.0;
    }
    $headlineSellConfidence = null;
    if ($sellMomentumPick && isset($sellCandidateSources[$sellMomentumPick['symbol']])) {
        foreach ($sellCandidates as $c) {
            if (($c['symbol'] ?? null) === $sellMomentumPick['symbol']) {
                $headlineSellConfidence = (float)($c['confidence'] ?? 0.0);
                break;
            }
        }
    }
    if ($headlineSellConfidence === null && $isInitial) {
        $headlineSellConfidence = 0.0;
    }

    $buyRecommendation = $buildHeadline($topGainer, $buyMomentumPick, 'Buy', $buyHeadlineReason, $headlineBuyConfidence);
    $sellRecommendation = $buildHeadline($topLoser, $sellMomentumPick, 'Sell', $sellHeadlineReason, $headlineSellConfidence);

    $timestamp = date('Y-m-d H:i:s');

    $historyEntries = [];
    if ($isInitial) {
        if ($topGainer) {
            $historyEntries[] = [
                'timestamp' => $timestamp, 'datetime' => $timestamp,
                'stock_symbol' => prakashDisplaySymbol((string)($topGainer['symbol'] ?? '')),
                'recommendation' => 'Buy',
                'percentage_change' => (float)($topGainer['change_pct'] ?? 0),
                'price' => (float)($topGainer['price'] ?? 0),
                'reason' => 'Initial Top Gainer',
                'iteration' => $iterationNumber,
            ];
        }
        if ($topLoser) {
            $historyEntries[] = [
                'timestamp' => $timestamp, 'datetime' => $timestamp,
                'stock_symbol' => prakashDisplaySymbol((string)($topLoser['symbol'] ?? '')),
                'recommendation' => 'Sell',
                'percentage_change' => (float)($topLoser['change_pct'] ?? 0),
                'price' => (float)($topLoser['price'] ?? 0),
                'reason' => 'Initial Top Loser',
                'iteration' => $iterationNumber,
            ];
        }
    } else {
        foreach ($momentumUp as $m) {
            $stock = $stocksBySymbol[$m['symbol']] ?? null;
            if (!$stock) continue;
            $historyEntries[] = [
                'timestamp' => $timestamp, 'datetime' => $timestamp,
                'stock_symbol' => prakashDisplaySymbol($m['symbol']),
                'recommendation' => 'Buy',
                'percentage_change' => (float)($stock['change_pct'] ?? 0),
                'price' => (float)($stock['price'] ?? 0),
                'reason' => $m['reason'],
                'momentum' => $m['momentum'],
                'iteration' => $iterationNumber,
            ];
        }
        foreach ($momentumDown as $m) {
            $stock = $stocksBySymbol[$m['symbol']] ?? null;
            if (!$stock) continue;
            $historyEntries[] = [
                'timestamp' => $timestamp, 'datetime' => $timestamp,
                'stock_symbol' => prakashDisplaySymbol($m['symbol']),
                'recommendation' => 'Sell',
                'percentage_change' => (float)($stock['change_pct'] ?? 0),
                'price' => (float)($stock['price'] ?? 0),
                'reason' => $m['reason'],
                'momentum' => $m['momentum'],
                'iteration' => $iterationNumber,
            ];
        }
        // Rank-based signal: fires every single iteration for whichever
        // stock is currently #1 (top gainer) / last (top loser) by
        // %change — even if it's the exact same stock as last refresh,
        // and even if it already fired above as a momentum pick. This is
        // the plain "sort by %change, top = Buy, bottom = Sell, every
        // iteration" rule running independently alongside momentum.
        if ($rankBuyCandidate) {
            $historyEntries[] = [
                'timestamp' => $timestamp, 'datetime' => $timestamp,
                'stock_symbol' => prakashDisplaySymbol($rankBuyCandidate['symbol']),
                'recommendation' => 'Buy',
                'percentage_change' => $rankBuyCandidate['percentage_change'],
                'price' => $rankBuyCandidate['price'],
                'reason' => $rankBuyCandidate['reason'],
                'iteration' => $iterationNumber,
            ];
        }
        if ($rankSellCandidate) {
            $historyEntries[] = [
                'timestamp' => $timestamp, 'datetime' => $timestamp,
                'stock_symbol' => prakashDisplaySymbol($rankSellCandidate['symbol']),
                'recommendation' => 'Sell',
                'percentage_change' => $rankSellCandidate['percentage_change'],
                'price' => $rankSellCandidate['price'],
                'reason' => $rankSellCandidate['reason'],
                'iteration' => $iterationNumber,
            ];
        }
    }

    foreach ($historyEntries as $entry) {
        appendPrakashHistory($entry, $historyPath);
    }

    savePrakashState([
        'updated' => $timestamp,
        'date'    => $todayStr,
        'ranks'   => $currentRanks,
        'changes' => $currentChanges,
    ], $statePath);

    return [
        'updated_at' => $timestamp,
        'market_open' => prakashIsMarketHours(),
        'buy_recommendation'  => $buyRecommendation,
        'sell_recommendation' => $sellRecommendation,
        'top_gainer' => [
            'symbol' => prakashDisplaySymbol((string)($topGainer['symbol'] ?? '')),
            'price' => (float)($topGainer['price'] ?? 0),
            'percentage_change' => (float)($topGainer['change_pct'] ?? 0),
            'recommendation' => 'Buy',
            'reason' => 'Top Gainer',
        ],
        'top_loser' => [
            'symbol' => prakashDisplaySymbol((string)($topLoser['symbol'] ?? '')),
            'price' => (float)($topLoser['price'] ?? 0),
            'percentage_change' => (float)($topLoser['change_pct'] ?? 0),
            'recommendation' => 'Sell',
            'reason' => 'Top Loser',
        ],
        'rank_buy_recommendation' => $rankBuyCandidate ? array_merge($rankBuyCandidate, [
            'recommendation' => 'Buy',
            'recommended_entry_price' => $dailyRecBySymbol[prakashDisplaySymbol($rankBuyCandidate['symbol'])]['entry_price'] ?? $rankBuyCandidate['price'],
            'target_price' => $dailyRecBySymbol[prakashDisplaySymbol($rankBuyCandidate['symbol'])]['target_price'] ?? null,
            'current_status' => $dailyRecBySymbol[prakashDisplaySymbol($rankBuyCandidate['symbol'])]['status'] ?? 'Active',
        ]) : null,
        'rank_sell_recommendation' => $rankSellCandidate ? array_merge($rankSellCandidate, [
            'recommendation' => 'Sell',
            'recommended_entry_price' => $dailyRecBySymbol[prakashDisplaySymbol($rankSellCandidate['symbol'])]['entry_price'] ?? $rankSellCandidate['price'],
            'target_price' => $dailyRecBySymbol[prakashDisplaySymbol($rankSellCandidate['symbol'])]['target_price'] ?? null,
            'current_status' => $dailyRecBySymbol[prakashDisplaySymbol($rankSellCandidate['symbol'])]['status'] ?? 'Active',
        ]) : null,
        'rank_movement_buy' => $rankMovementBuy ? array_merge(
            ['symbol' => $rankMovementBuy['symbol']],
            $rankMovementBuy,
            ['recommendation' => 'Buy', 'reason' => 'Rank Movement Up']
        ) : null,
        'rank_movement_sell' => $rankMovementSell ? array_merge(
            ['symbol' => $rankMovementSell['symbol']],
            $rankMovementSell,
            ['recommendation' => 'Sell', 'reason' => 'Rank Movement Down']
        ) : null,
        'buy_box'   => $buyCandidates,
        'sell_box'  => $sellCandidates,
        'top5_buy'  => $top5Buy,
        'top5_sell' => $top5Sell,
        'momentum_up'   => $momentumUp,
        'momentum_down' => $momentumDown,
        'daily_summary' => $dailySummary,
        'tracked_count' => count($tracked),
        'iteration' => $iterationNumber,
        'is_initial_iteration' => $isInitial,
        'history_file' => $historyPath,
        'state_file'  => $statePath,
        'daily_file'  => $dailyPath,
        'rank_history_file' => $rankHistPath,
    ];
}
