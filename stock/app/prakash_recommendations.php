<?php
declare(strict_types=1);

if (!defined('STORAGE')) {
    define('STORAGE', dirname(__DIR__) . '/storage');
}

function prakashRecommendationStateFile(?string $username = null): string
{
    return $username ? getUserRecommendationsStatePath($username) : (getStorageBasePath() . '/prakash_state.json');
}

function prakashRecommendationHistoryFile(?string $username = null): string
{
    return $username ? getUserRecommendationsHistoryPath($username) : (getStorageBasePath() . '/prakash_recommendations_history.json');
}

// ── Daily intraday target-tracking file ───────────────────────
// One file per user per calendar day. Holds every Buy/Sell box entry given
// that day plus whether its intraday target was hit. No carryover — a new
// day always starts a fresh file.
function prakashDailyFile(?string $username = null, ?string $date = null): string
{
    $date = $date ?: date('Y-m-d');
    $prefix = $username ? (getUserDataDir() . '/' . preg_replace('/[^a-z0-9._-]/i', '_', trim($username))) : getStorageBasePath();
    return $prefix . '_prakash_daily_' . $date . '.json';
}

// Market close time (IST) — anything still open past this gets locked in as
// "Not Achieved" for the day. Adjust here if your market hours differ.
define('PRAKASH_MARKET_CLOSE_HOUR', 15);
define('PRAKASH_MARKET_CLOSE_MINUTE', 30);

function prakashIsMarketClosed(): bool
{
    $closeTs = mktime(PRAKASH_MARKET_CLOSE_HOUR, PRAKASH_MARKET_CLOSE_MINUTE, 0);
    return time() >= $closeTs;
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
        } else {
            $rec['final_status'] = 'Achieved';
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
    return [
        'date'            => $decoded['date'] ?? date('Y-m-d'),
        'recommendations' => is_array($decoded['recommendations'] ?? null) ? $decoded['recommendations'] : [],
        'closed'          => (bool)($decoded['closed'] ?? false),
    ];
}

function savePrakashDaily(array $daily, string $path): void
{
    $base = getStorageBasePath();
    if (!is_dir($base)) mkdir($base, 0755, true);
    file_put_contents($path, json_encode($daily, JSON_PRETTY_PRINT));
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
    if (!file_exists($statePath)) return ['updated' => null, 'ranks' => []];
    $decoded = json_decode((string)file_get_contents($statePath), true);
    if (!is_array($decoded)) return ['updated' => null, 'ranks' => []];
    return [
        'updated' => $decoded['updated'] ?? null,
        'ranks' => is_array($decoded['ranks'] ?? null) ? $decoded['ranks'] : [],
    ];
}

function savePrakashState(array $state, string $statePath): void
{
    $base = getStorageBasePath();
    if (!is_dir($base)) mkdir($base, 0755, true);
    file_put_contents($statePath, json_encode($state, JSON_PRETTY_PRINT));
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
    $changePct = $stock['change_pct'] ?? null;
    if ($changePct === null) return true; // nothing to compare against, allow it
    $changePct = (float)$changePct;
    $prevClose = $changePct > -100 ? $price / (1 + $changePct / 100) : null;
    if (!$prevClose || $prevClose <= 0) return true; // can't derive a sane baseline, allow it
    $maxMove = defined('PRAKASH_MAX_PLAUSIBLE_MOVE_PCT') ? PRAKASH_MAX_PLAUSIBLE_MOVE_PCT : 12.0;
    $impliedMovePct = abs($price - $prevClose) / $prevClose * 100;
    return $impliedMovePct <= $maxMove;
}

function buildPrakashRecommendations(array $stocks, ?string $statePath = null, ?string $historyPath = null, ?string $username = null): array
{
    $statePath = $statePath ?? prakashRecommendationStateFile($username);
    $historyPath = $historyPath ?? prakashRecommendationHistoryFile($username);

    $tracked = array_values(array_filter($stocks, fn($stock) => is_array($stock)));
    $maxTracked = defined('PRAKASH_MAX_TRACKED') ? PRAKASH_MAX_TRACKED : 20;
    if ($maxTracked > 0 && count($tracked) > $maxTracked) {
        $tracked = array_slice($tracked, 0, $maxTracked);
    }

    if (empty($tracked)) {
        return [
            'buy_recommendation' => null,
            'sell_recommendation' => null,
            'top_gainer' => null,
            'top_loser' => null,
            'rank_movement_buy' => null,
            'rank_movement_sell' => null,
            'buy_box' => [],
            'sell_box' => [],
            'daily_summary' => null,
            'tracked_count' => 0,
        ];
    }

    usort($tracked, fn($a, $b) => ((float)($b['change_pct'] ?? 0)) <=> ((float)($a['change_pct'] ?? 0)));

    $topGainer = $tracked[0] ?? null;
    $topLoser = $tracked[count($tracked) - 1] ?? null;

    $currentRanks = [];
    $currentChanges = [];
    foreach ($tracked as $index => $stock) {
        $symbol = prakashNormalizeSymbol((string)($stock['symbol'] ?? ''));
        if ($symbol !== '') {
            $currentRanks[$symbol] = $index + 1;
            $currentChanges[$symbol] = (float)($stock['change_pct'] ?? 0);
        }
    }

    $previousState = loadPrakashState($statePath);
    $prevRanks = is_array($previousState['ranks'] ?? null) ? $previousState['ranks'] : [];
    $prevChanges = is_array($previousState['changes'] ?? null) ? $previousState['changes'] : [];

    $rankMovementBuy = null;
    $rankMovementSell = null;

    // All rank movers this refresh (not just the single biggest) — feeds the
    // capped Buy/Sell boxes below. bestBuy / bestSell (single strongest) are
    // still kept for backwards-compat top-level fields.
    $rankMoversBuy = [];
    $rankMoversSell = [];

    foreach ($currentRanks as $symbol => $currentRank) {
        $previousRank = $prevRanks[$symbol] ?? null;
        if ($previousRank === null || (int)$previousRank === (int)$currentRank) continue;

        $stock = null;
        foreach ($tracked as $candidate) {
            $candidateSymbol = prakashNormalizeSymbol((string)($candidate['symbol'] ?? ''));
            if ($candidateSymbol === $symbol) {
                $stock = $candidate;
                break;
            }
        }
        if (!$stock) continue;

        $difference = (int)$previousRank - (int)$currentRank;
        $entry = [
            'symbol' => $symbol,
            'price' => (float)($stock['price'] ?? 0),
            'percentage_change' => (float)($stock['change_pct'] ?? 0),
            'previous_rank' => (int)$previousRank,
            'current_rank' => (int)$currentRank,
            'rank_difference' => $difference,
            'reason' => $difference > 0 ? 'Rank Movement Up' : 'Rank Movement Down',
            'strength' => abs($difference),
        ];

        if ($difference > 0) {
            $rankMoversBuy[] = $entry;
            if ($rankMovementBuy === null || $difference > ($rankMovementBuy['rank_difference'] ?? 0)) {
                $rankMovementBuy = $entry;
            }
        } elseif ($difference < 0) {
            $rankMoversSell[] = $entry;
            if ($rankMovementSell === null || $difference < ($rankMovementSell['rank_difference'] ?? 0)) {
                $rankMovementSell = $entry;
            }
        }
    }

    // ── Fast movers: big % change swing since the previous refresh, even if
    // the stock's rank barely moved. Catches a stock spiking from -1% to +2%
    // in one refresh that a pure rank-based check would miss.
    $fastMoversBuy = [];
    $fastMoversSell = [];
    foreach ($currentChanges as $symbol => $currentChange) {
        if (!isset($prevChanges[$symbol])) continue;
        $delta = $currentChange - (float)$prevChanges[$symbol];
        if (abs($delta) < PRAKASH_FAST_MOVER_THRESHOLD_PCT) continue;

        $stock = null;
        foreach ($tracked as $candidate) {
            if (prakashNormalizeSymbol((string)($candidate['symbol'] ?? '')) === $symbol) { $stock = $candidate; break; }
        }
        if (!$stock) continue;

        $entry = [
            'symbol' => $symbol,
            'price' => (float)($stock['price'] ?? 0),
            'percentage_change' => $currentChange,
            'change_delta' => round($delta, 2),
            'reason' => 'Fast Mover',
            'strength' => abs($delta),
        ];
        if ($delta > 0) $fastMoversBuy[] = $entry;
        else $fastMoversSell[] = $entry;
    }

    // ── Build capped Buy/Sell boxes: rank movers first (strongest signal),
    // then fast movers, then Top Gainer/Loser as a guaranteed fallback.
    // Deduplicated by symbol — a symbol already in the box from a stronger
    // trigger isn't added again for a weaker one.
    $buildBox = function (array $rankMovers, array $fastMovers, ?array $fallback, string $fallbackReason) {
        usort($rankMovers, fn($a, $b) => $b['strength'] <=> $a['strength']);
        usort($fastMovers, fn($a, $b) => $b['strength'] <=> $a['strength']);
        $box = [];
        $seen = [];
        foreach (array_merge($rankMovers, $fastMovers) as $c) {
            if (isset($seen[$c['symbol']])) continue;
            $seen[$c['symbol']] = true;
            $box[] = $c;
            if (count($box) >= PRAKASH_MAX_PER_BOX) return $box;
        }
        if ($fallback) {
            $fbSymbol = prakashNormalizeSymbol((string)($fallback['symbol'] ?? ''));
            if ($fbSymbol !== '' && !isset($seen[$fbSymbol])) {
                $box[] = [
                    'symbol' => $fbSymbol,
                    'price' => (float)($fallback['price'] ?? 0),
                    'percentage_change' => (float)($fallback['change_pct'] ?? 0),
                    'reason' => $fallbackReason,
                    'strength' => 0,
                ];
            }
        }
        return array_slice($box, 0, PRAKASH_MAX_PER_BOX);
    };

    $buyCandidates  = $buildBox($rankMoversBuy, $fastMoversBuy, $topGainer, 'Top Gainer');
    $sellCandidates = $buildBox($rankMoversSell, $fastMoversSell, $topLoser, 'Top Loser');

    // ── Intraday target tracking ──────────────────────────────────
    // Every symbol that appears in a box today gets an entry price + a flat
    // 1% intraday target (PRAKASH_TARGET_PCT, same math for Buy and Sell).
    // Once logged for the day, the entry/target price never changes — only
    // 'achieved' and 'achieved_at' get updated as later refreshes check the
    // live price against that locked-in target. No carryover across days;
    // a new day always starts a fresh daily file (see prakashDailyFile()).
    $dailyPath = prakashDailyFile($username);
    $daily = loadPrakashDaily($dailyPath);
    $todayStr = date('Y-m-d');
    if (($daily['date'] ?? '') !== $todayStr) {
        // Stale file from a previous day slipped through (e.g. process left
        // running past midnight) — start clean rather than mixing days.
        $daily = ['date' => $todayStr, 'recommendations' => [], 'closed' => false];
    }

    $recsBySymbol = [];
    foreach ($daily['recommendations'] as $i => $rec) {
        $recsBySymbol[$rec['symbol']] = $i;
    }

    $registerBoxEntries = function (array $candidates, string $side) use (&$daily, &$recsBySymbol) {
        $targetMult = $side === 'Buy' ? (1 + PRAKASH_TARGET_PCT / 100) : (1 - PRAKASH_TARGET_PCT / 100);
        $now = date('Y-m-d H:i:s');
        foreach ($candidates as $c) {
            $symbol = prakashDisplaySymbol((string)($c['symbol'] ?? ''));
            $livePrice = (float)($c['price'] ?? 0);

            // Skip bad/glitched ticks entirely — don't lock a wrong entry
            // price and don't let a spurious spike falsely mark a hit.
            if (!prakashIsPricePlausible($c)) continue;

            if (!isset($recsBySymbol[$symbol])) {
                // First time we've seen this symbol in a box today — lock in
                // entry price and target for the rest of the day.
                $entryPrice = $livePrice;
                $targetPrice = round($entryPrice * $targetMult, 2);
                $daily['recommendations'][] = [
                    'symbol' => $symbol,
                    'side' => $side,
                    'reason' => $c['reason'] ?? ($side === 'Buy' ? 'Top Gainer' : 'Top Loser'),
                    'entry_price' => $entryPrice,
                    'entry_time' => $now,
                    'entry_source' => $c['_source'] ?? null,
                    'target_pct' => PRAKASH_TARGET_PCT,
                    'target_price' => $targetPrice,
                    'achieved' => false,
                    'achieved_at' => null,
                    'achieved_price' => null,
                    'last_checked_price' => $livePrice,
                ];
                $recsBySymbol[$symbol] = count($daily['recommendations']) - 1;
            } else {
                // Already tracked today — just check the live price against
                // the target already locked in; never move the target.
                $idx = $recsBySymbol[$symbol];
                $rec = &$daily['recommendations'][$idx];
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
            }
        }
    };

    $registerBoxEntries($buyCandidates, 'Buy');
    $registerBoxEntries($sellCandidates, 'Sell');

    // Also re-check every already-tracked symbol today against the live
    // price we have this refresh, even if it fell out of the box (e.g. a
    // rank mover from earlier isn't in today's top movers anymore but still
    // has an open target).
    foreach ($tracked as $stock) {
        $symbol = prakashDisplaySymbol((string)($stock['symbol'] ?? ''));
        if (!isset($recsBySymbol[$symbol])) continue;
        if (!prakashIsPricePlausible($stock)) continue; // ignore bad/glitched tick
        $idx = $recsBySymbol[$symbol];
        $rec = &$daily['recommendations'][$idx];
        if ($rec['achieved']) { unset($rec); continue; }
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

    $buyRecommendation = $rankMovementBuy ? [
        'symbol' => prakashDisplaySymbol($rankMovementBuy['symbol']),
        'price' => $rankMovementBuy['price'],
        'percentage_change' => $rankMovementBuy['percentage_change'],
        'recommendation' => 'Buy',
        'reason' => 'Rank Movement Up',
        'previous_rank' => $rankMovementBuy['previous_rank'],
        'current_rank' => $rankMovementBuy['current_rank'],
        'rank_difference' => $rankMovementBuy['rank_difference'],
    ] : [
        'symbol' => $topGainer['symbol'] ?? '',
        'price' => (float)($topGainer['price'] ?? 0),
        'percentage_change' => (float)($topGainer['change_pct'] ?? 0),
        'recommendation' => 'Buy',
        'reason' => 'Top Gainer',
        'previous_rank' => null,
        'current_rank' => null,
        'rank_difference' => null,
    ];

    $sellRecommendation = $rankMovementSell ? [
        'symbol' => prakashDisplaySymbol($rankMovementSell['symbol']),
        'price' => $rankMovementSell['price'],
        'percentage_change' => $rankMovementSell['percentage_change'],
        'recommendation' => 'Sell',
        'reason' => 'Rank Movement Down',
        'previous_rank' => $rankMovementSell['previous_rank'],
        'current_rank' => $rankMovementSell['current_rank'],
        'rank_difference' => $rankMovementSell['rank_difference'],
    ] : [
        'symbol' => $topLoser['symbol'] ?? '',
        'price' => (float)($topLoser['price'] ?? 0),
        'percentage_change' => (float)($topLoser['change_pct'] ?? 0),
        'recommendation' => 'Sell',
        'reason' => 'Top Loser',
        'previous_rank' => null,
        'current_rank' => null,
        'rank_difference' => null,
    ];

    $timestamp = date('Y-m-d H:i:s');

    $historyEntries = [];
    if ($topGainer) {
        $historyEntries[] = [
            'timestamp' => $timestamp,
            'datetime' => $timestamp,
            'stock_symbol' => prakashDisplaySymbol((string)($topGainer['symbol'] ?? '')),
            'recommendation' => 'Buy',
            'percentage_change' => (float)($topGainer['change_pct'] ?? 0),
            'price' => (float)($topGainer['price'] ?? 0),
            'reason' => 'Top Gainer',
        ];
    }
    if ($topLoser) {
        $historyEntries[] = [
            'timestamp' => $timestamp,
            'datetime' => $timestamp,
            'stock_symbol' => prakashDisplaySymbol((string)($topLoser['symbol'] ?? '')),
            'recommendation' => 'Sell',
            'percentage_change' => (float)($topLoser['change_pct'] ?? 0),
            'price' => (float)($topLoser['price'] ?? 0),
            'reason' => 'Top Loser',
        ];
    }
    if ($rankMovementBuy) {
        $historyEntries[] = [
            'timestamp' => $timestamp,
            'datetime' => $timestamp,
            'stock_symbol' => prakashDisplaySymbol($rankMovementBuy['symbol']),
            'recommendation' => 'Buy',
            'percentage_change' => $rankMovementBuy['percentage_change'],
            'price' => $rankMovementBuy['price'],
            'reason' => 'Rank Movement Up',
            'previous_rank' => $rankMovementBuy['previous_rank'],
            'current_rank' => $rankMovementBuy['current_rank'],
            'rank_difference' => $rankMovementBuy['rank_difference'],
        ];
    }
    if ($rankMovementSell) {
        $historyEntries[] = [
            'timestamp' => $timestamp,
            'datetime' => $timestamp,
            'stock_symbol' => prakashDisplaySymbol($rankMovementSell['symbol']),
            'recommendation' => 'Sell',
            'percentage_change' => $rankMovementSell['percentage_change'],
            'price' => $rankMovementSell['price'],
            'reason' => 'Rank Movement Down',
            'previous_rank' => $rankMovementSell['previous_rank'],
            'current_rank' => $rankMovementSell['current_rank'],
            'rank_difference' => $rankMovementSell['rank_difference'],
        ];
    }

    foreach ($historyEntries as $entry) {
        appendPrakashHistory($entry, $historyPath);
    }

    savePrakashState([
        'updated' => $timestamp,
        'ranks' => $currentRanks,
    ], $statePath);

    return [
        'buy_recommendation' => $buyRecommendation,
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
        'rank_movement_buy' => $rankMovementBuy ? [
            'symbol' => prakashDisplaySymbol($rankMovementBuy['symbol']),
            'price' => $rankMovementBuy['price'],
            'percentage_change' => $rankMovementBuy['percentage_change'],
            'recommendation' => 'Buy',
            'reason' => 'Rank Movement Up',
            'previous_rank' => $rankMovementBuy['previous_rank'],
            'current_rank' => $rankMovementBuy['current_rank'],
            'rank_difference' => $rankMovementBuy['rank_difference'],
        ] : null,
        'rank_movement_sell' => $rankMovementSell ? [
            'symbol' => prakashDisplaySymbol($rankMovementSell['symbol']),
            'price' => $rankMovementSell['price'],
            'percentage_change' => $rankMovementSell['percentage_change'],
            'recommendation' => 'Sell',
            'reason' => 'Rank Movement Down',
            'previous_rank' => $rankMovementSell['previous_rank'],
            'current_rank' => $rankMovementSell['current_rank'],
            'rank_difference' => $rankMovementSell['rank_difference'],
        ] : null,
        'buy_box' => $buyCandidates,
        'sell_box' => $sellCandidates,
        'daily_summary' => $dailySummary,
        'tracked_count' => count($tracked),
        'history_file' => $historyPath,
        'state_file' => $statePath,
        'daily_file' => $dailyPath,
    ];
}
