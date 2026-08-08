<?php
declare(strict_types=1);
/**
 * api/eod.php — end-of-day signal report: checks saved signals against
 * live prices to see which hit target/stoploss/still open.
 */

// Most recent date that has a non-empty eod_signals_*.json file, if any.
// Used so the report doesn't default to an empty/near-empty "today" the
// moment the tab is opened before anything's been tracked yet.
function eodMostRecentDateWithSignals(): ?string
{
    $files = glob(STORAGE . '/eod_signals_*.json') ?: [];
    $dates = [];
    foreach ($files as $f) {
        if (preg_match('/eod_signals_(\d{4}-\d{2}-\d{2})\.json$/', $f, $m)) {
            $decoded = json_decode((string)file_get_contents($f), true);
            if (!empty($decoded)) $dates[] = $m[1];
        }
    }
    if (!$dates) return null;
    rsort($dates);
    return $dates[0];
}

function apiEodReport(string $date = ''): array
{
    $requestedExplicitDate = $date !== '';
    if (!$date) {
        $date = date('Y-m-d');
        $todayFile = STORAGE . '/eod_signals_' . $date . '.json';
        $todayHasData = file_exists($todayFile) && !empty(json_decode((string)file_get_contents($todayFile), true));
        if (!$todayHasData) {
            // Nothing tracked today yet (e.g. it's early in the session) —
            // show the most recent day that actually has signals instead
            // of silently presenting an empty/near-empty "today" the
            // moment the report loads, unprompted.
            $fallbackDate = eodMostRecentDateWithSignals();
            if ($fallbackDate) {
                $date = $fallbackDate;
            }
        }
    }
    $isFallbackDate = !$requestedExplicitDate && $date !== date('Y-m-d');

    $file = STORAGE . '/eod_signals_' . $date . '.json';
    if (!file_exists($file)) return ['date' => $date, 'signals' => [], 'summary' => null, 'is_fallback_date' => $isFallbackDate];
    $signals = json_decode(file_get_contents($file), true) ?? [];
    if (empty($signals)) return ['date' => $date, 'signals' => [], 'summary' => null, 'is_fallback_date' => $isFallbackDate];
    
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
    return ['date' => $date, 'signals' => $signals, 'summary' => $summary, 'is_fallback_date' => $isFallbackDate];
}

function apiEodCheck(): array { return apiEodReport(); }

