<?php
declare(strict_types=1);
/**
 * ai_recommendations.php — "AI Recommendation" track record.
 *
 * Same locked-in-target daily tracking scheme as prakash_recommendations.php
 * (see that file for the full design notes), but the Buy/Sell box here is
 * built from this app's own indicator scorecard (signals.php's
 * generateSignal()/generateSignalFull() → 'signal' + 'confidence' per
 * stock) instead of Prakash's rank-movement logic. This lets the dashboard
 * show two independently-tracked, independently-scored recommendation
 * engines side by side with their own success rates.
 *
 * Storage is intentionally separate from Prakash's (ai_state.json,
 * ai_recommendations_history.json, *_ai_daily_YYYY-MM-DD.json) so the two
 * track records never mix.
 */

if (!defined('STORAGE')) {
    define('STORAGE', dirname(__DIR__) . '/storage');
}

function aiRecommendationStateFile(?string $username = null): string
{
    return $username ? getUserAiRecommendationStatePath($username) : (getStorageBasePath() . '/ai_state.json');
}

function aiRecommendationHistoryFile(?string $username = null): string
{
    return $username ? getUserAiRecommendationHistoryPath($username) : (getStorageBasePath() . '/ai_recommendations_history.json');
}

// One file per user per calendar day — identical shape/behavior to
// prakashDailyFile(), just under an 'ai_daily' prefix so it never collides.
function aiDailyFile(?string $username = null, ?string $date = null): string
{
    $date = $date ?: date('Y-m-d');
    if ($username) {
        $prefix = getUserDataDir() . '/' . preg_replace('/[^a-z0-9._-]/i', '_', trim($username));
        return $prefix . '_ai_daily_' . $date . '.json';
    }
    // No-username fallback: must live INSIDE storage/, not as a sibling
    // file next to it (same bug fixed in prakashDailyFile()).
    return getStorageBasePath() . '/ai_daily_' . $date . '.json';
}

// Call on each refresh (or via cron once after close). Freezes any still-open
// AI recommendation for the day as "Not Achieved" once market close passes.
function closeAiDailyIfNeeded(?string $username = null): ?array
{
    $path = aiDailyFile($username);
    $daily = loadPrakashDaily($path); // shape is identical, reuse the same loader
    if (($daily['date'] ?? '') !== date('Y-m-d')) return null;
    if ($daily['closed']) return $daily;
    if (!prakashIsMarketClosed()) return null;

    foreach ($daily['recommendations'] as &$rec) {
        $rec['final_status'] = !empty($rec['achieved']) ? 'Achieved' : 'Not Achieved';
    }
    unset($rec);
    $daily['closed'] = true;
    $daily['closed_at'] = date('Y-m-d H:i:s');
    savePrakashDaily($daily, $path); // same file shape, reuse the same saver
    return $daily;
}

// Rolls up every stored AI daily file into overall win-rate stats — the AI
// counterpart of prakashRollupHistory(). $withDetails=true attaches every
// individual stock-level entry per day for the "view details" UI.
function aiRollupHistory(?string $username = null, int $maxDays = 90, bool $withDetails = false): array
{
    $dir = getStorageBasePath();
    $prefix = $username ? preg_replace('/[^a-z0-9._-]/i', '_', trim($username)) . '_' : '';
    $pattern = $dir . '/' . ($prefix ?: '') . 'ai_daily_*.json';
    if ($username) {
        $pattern = getUserDataDir() . '/' . $prefix . 'ai_daily_*.json';
    }
    $files = glob($pattern) ?: [];
    rsort($files);
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

function aiNormalizeSymbol(string $symbol): string
{
    return strtoupper(str_replace('.NS', '', trim($symbol)));
}

/**
 * Build this refresh's AI Buy/Sell recommendation boxes from the watchlist
 * stocks the caller already scored (each needs 'symbol','price','change_pct',
 * 'signal','confidence','momentum_score' — exactly what apiWatchlistPage()
 * / apiWatchlist() already produce per stock via generateSignal()).
 *
 * Selection: Buy box = the AI_MAX_PER_BOX stocks currently signaling
 * Buy/Strong Buy, ranked by confidence (ties broken by momentum score).
 * Sell box = the same for Sell/Strong Sell. Unlike Prakash (rank-movement
 * driven, so the box can be empty most refreshes), the AI box is populated
 * any refresh where the signal engine finds qualifying stocks.
 */
function buildAiRecommendations(array $stocks, ?string $statePath = null, ?string $historyPath = null, ?string $username = null): array
{
    $statePath = $statePath ?? aiRecommendationStateFile($username);
    $historyPath = $historyPath ?? aiRecommendationHistoryFile($username);

    $tracked = array_values(array_filter($stocks, fn($s) => is_array($s)));

    if (empty($tracked)) {
        return [
            'buy_recommendation' => null,
            'sell_recommendation' => null,
            'buy_box' => [],
            'sell_box' => [],
            'daily_summary' => null,
            'tracked_count' => 0,
        ];
    }

    $buyPool = array_values(array_filter($tracked, fn($s) => in_array($s['signal'] ?? '', ['Buy', 'Strong Buy'], true)));
    $sellPool = array_values(array_filter($tracked, fn($s) => in_array($s['signal'] ?? '', ['Sell', 'Strong Sell'], true)));

    usort($buyPool, fn($a, $b) => (($b['confidence'] ?? 0) <=> ($a['confidence'] ?? 0)) ?: (($b['momentum_score'] ?? 0) <=> ($a['momentum_score'] ?? 0)));
    usort($sellPool, fn($a, $b) => (($b['confidence'] ?? 0) <=> ($a['confidence'] ?? 0)) ?: (($a['momentum_score'] ?? 0) <=> ($b['momentum_score'] ?? 0)));

    $normalize = function (array $c, string $reason): array {
        return [
            'symbol' => aiNormalizeSymbol((string)($c['symbol'] ?? '')),
            'price' => (float)($c['price'] ?? 0),
            'percentage_change' => (float)($c['change_pct'] ?? 0),
            'confidence' => (float)($c['confidence'] ?? 0),
            'reason' => $reason,
            'strength' => (float)($c['confidence'] ?? 0),
        ];
    };

    $buyCandidates = array_map(
        fn($c) => $normalize($c, ($c['signal'] ?? '') === 'Strong Buy' ? 'Strong Buy Signal' : 'Buy Signal'),
        array_slice($buyPool, 0, AI_MAX_PER_BOX)
    );
    $sellCandidates = array_map(
        fn($c) => $normalize($c, ($c['signal'] ?? '') === 'Strong Sell' ? 'Strong Sell Signal' : 'Sell Signal'),
        array_slice($sellPool, 0, AI_MAX_PER_BOX)
    );

    // ── Intraday target tracking — identical scheme to Prakash's: first
    // time a symbol appears in a box today, lock in entry price + a flat
    // AI_TARGET_PCT target; every later refresh just checks the live price
    // against that locked-in target.
    $dailyPath = aiDailyFile($username);
    $daily = loadPrakashDaily($dailyPath);
    $todayStr = date('Y-m-d');
    if (($daily['date'] ?? '') !== $todayStr) {
        $daily = ['date' => $todayStr, 'recommendations' => [], 'closed' => false];
    }

    $recsBySymbol = [];
    foreach ($daily['recommendations'] as $i => $rec) {
        $recsBySymbol[$rec['symbol']][] = $i;
    }

    // A symbol is tracked once PER OPEN POSITION, not once per day — same
    // scheme as Prakash's engine (see prakash_recommendations.php). If its
    // most recent entry already hit target, a fresh signal for it starts a
    // brand-new entry instead of being silently swallowed by the dedup lock,
    // so e.g. "TITAN Buy @4550→4600 (hit)" and a later "TITAN Buy @4610→4660
    // (open)" both show up as separate rows for the day.
    $registerBoxEntries = function (array $candidates, string $side) use (&$daily, &$recsBySymbol) {
        $targetMult = $side === 'Buy' ? (1 + AI_TARGET_PCT / 100) : (1 - AI_TARGET_PCT / 100);
        $now = date('Y-m-d H:i:s');
        foreach ($candidates as $c) {
            $symbol = $c['symbol'];
            $livePrice = (float)($c['price'] ?? 0);

            // Skip bad/glitched ticks — same guard as Prakash, see
            // prakashIsPricePlausible() in prakash_recommendations.php.
            if (!prakashIsPricePlausible($c)) continue;

            $indices = $recsBySymbol[$symbol] ?? [];
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
                    continue;
                }
                $rec['last_checked_price'] = $livePrice;
                if (!$rec['achieved']) {
                    $hit = $rec['side'] === 'Buy' ? ($livePrice >= $rec['target_price']) : ($livePrice <= $rec['target_price']);
                    if ($hit) {
                        $rec['achieved'] = true;
                        $rec['achieved_at'] = $now;
                        $rec['achieved_price'] = $livePrice;
                        $rec['achieved_source'] = $c['_source'] ?? null;
                    }
                }
                unset($rec);
                continue;
            }

            // No open entry for this symbol (either brand new today, or
            // every prior entry already hit target) — start a fresh one.
            $entryPrice = $livePrice;
            $targetPrice = round($entryPrice * $targetMult, 2);
            $daily['recommendations'][] = [
                'symbol' => $symbol,
                'side' => $side,
                'reason' => $c['reason'] ?? ($side === 'Buy' ? 'Buy Signal' : 'Sell Signal'),
                'confidence' => $c['confidence'] ?? null,
                'entry_price' => $entryPrice,
                'entry_time' => $now,
                'entry_source' => $c['_source'] ?? null,
                'target_pct' => AI_TARGET_PCT,
                'target_price' => $targetPrice,
                'achieved' => false,
                'achieved_at' => null,
                'achieved_price' => null,
                'last_checked_price' => $livePrice,
                // How many times this symbol has been recommended today,
                // including this one — lets the UI label repeats ("2nd pick").
                'occurrence' => count($indices) + 1,
            ];
            $recsBySymbol[$symbol][] = count($daily['recommendations']) - 1;
        }
    };

    $registerBoxEntries($buyCandidates, 'Buy');
    $registerBoxEntries($sellCandidates, 'Sell');

    // Re-check every already-tracked symbol today against this refresh's
    // live price, even if it fell out of today's box (still-open targets
    // shouldn't stop being checked just because the stock isn't top-ranked
    // by confidence anymore). Only the symbol's OPEN entry (if any) is
    // touched — earlier, already-achieved entries stay untouched as history.
    foreach ($tracked as $stock) {
        $symbol = aiNormalizeSymbol((string)($stock['symbol'] ?? ''));
        if (!isset($recsBySymbol[$symbol])) continue;
        if (!prakashIsPricePlausible($stock)) continue; // ignore bad/glitched tick
        $openIdx = null;
        foreach ($recsBySymbol[$symbol] as $i) {
            if (empty($daily['recommendations'][$i]['achieved'])) { $openIdx = $i; break; }
        }
        if ($openIdx === null) continue;
        $rec = &$daily['recommendations'][$openIdx];
        $livePrice = (float)($stock['price'] ?? 0);
        $rec['last_checked_price'] = $livePrice;
        $hit = $rec['side'] === 'Buy' ? ($livePrice >= $rec['target_price']) : ($livePrice <= $rec['target_price']);
        if ($hit) {
            $rec['achieved'] = true;
            $rec['achieved_at'] = date('Y-m-d H:i:s');
            $rec['achieved_price'] = $livePrice;
            $rec['achieved_source'] = $stock['_source'] ?? null;
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

    $timestamp = date('Y-m-d H:i:s');
    $historyEntries = [];
    foreach ($buyCandidates as $c) {
        $historyEntries[] = [
            'timestamp' => $timestamp,
            'datetime' => $timestamp,
            'stock_symbol' => $c['symbol'],
            'recommendation' => 'Buy',
            'percentage_change' => $c['percentage_change'],
            'price' => $c['price'],
            'confidence' => $c['confidence'],
            'reason' => $c['reason'],
        ];
    }
    foreach ($sellCandidates as $c) {
        $historyEntries[] = [
            'timestamp' => $timestamp,
            'datetime' => $timestamp,
            'stock_symbol' => $c['symbol'],
            'recommendation' => 'Sell',
            'percentage_change' => $c['percentage_change'],
            'price' => $c['price'],
            'confidence' => $c['confidence'],
            'reason' => $c['reason'],
        ];
    }
    foreach ($historyEntries as $entry) {
        appendPrakashHistory($entry, $historyPath); // generic append, works for any history file
    }

    savePrakashState(['updated' => $timestamp, 'ranks' => []], $statePath);

    $topBuy = $buyCandidates[0] ?? null;
    $topSell = $sellCandidates[0] ?? null;

    return [
        'updated_at' => $timestamp,
        'market_open' => prakashIsMarketHours(),
        'buy_recommendation' => $topBuy ? array_merge($topBuy, ['recommendation' => 'Buy']) : null,
        'sell_recommendation' => $topSell ? array_merge($topSell, ['recommendation' => 'Sell']) : null,
        'buy_box' => $buyCandidates,
        'sell_box' => $sellCandidates,
        'daily_summary' => $dailySummary,
        'tracked_count' => count($tracked),
        'history_file' => $historyPath,
        'state_file' => $statePath,
        'daily_file' => $dailyPath,
    ];
}
