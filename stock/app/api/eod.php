<?php
declare(strict_types=1);
/**
 * api/eod.php — end-of-day signal report: checks saved signals against
 * live prices to see which hit target/stoploss/still open.
 */

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

