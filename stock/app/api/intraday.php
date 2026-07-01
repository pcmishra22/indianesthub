<?php
declare(strict_types=1);
/**
 * api/intraday.php — intraday candle data and pivot points.
 * Uses Twelve Data's /time_series endpoint (server-side, no CORS issues)
 * when DATA_API_KEY is configured. Falls back to Yahoo chart endpoint
 * (which is mostly dead but kept as a last-resort attempt).
 */

function apiIntraday(string $symbol, string $interval = '5m'): array
{
    if (!$symbol) return ['error' => 'No symbol'];

    $nseSym = strtoupper(str_replace('.NS', '', $symbol));

    // ── Twelve Data intraday (server-side, no CORS, reliable) ──
    if (DATA_API_KEY) {
        // Map our interval names to Twelve Data's format
        $tdInterval = match($interval) {
            '5m'  => '5min',
            '15m' => '15min',
            '1h'  => '1h',
            '1d'  => '1day',
            default => '5min',
        };
        $outputSize = ($interval === '1h') ? 120 : 80; // more bars for hourly
        $url = 'https://api.twelvedata.com/time_series?symbol=' . urlencode($nseSym)
             . '&exchange=NSE&interval=' . $tdInterval
             . '&outputsize=' . $outputSize
             . '&apikey=' . urlencode(DATA_API_KEY);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 12, CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);
        $raw  = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($raw && $code === 200) {
            $d      = json_decode($raw, true);
            $values = $d['values'] ?? [];
            if (!empty($values) && !isset($d['status'])) { // status=error means API error
                $candles = [];
                foreach (array_reverse($values) as $v) {
                    $close = (float)($v['close'] ?? 0);
                    if ($close <= 0) continue;
                    // Convert "2024-01-15 09:15:00" datetime to a unix timestamp
                    $ts = strtotime($v['datetime'] ?? '');
                    if (!$ts) continue;
                    $candles[] = [
                        't' => $ts,
                        'o' => round((float)($v['open']   ?? $close), 2),
                        'h' => round((float)($v['high']   ?? $close), 2),
                        'l' => round((float)($v['low']    ?? $close), 2),
                        'c' => round($close, 2),
                        'v' => (int)($v['volume'] ?? 0),
                    ];
                }
                if (!empty($candles)) {
                    return ['symbol' => $symbol, 'interval' => $interval, 'candles' => $candles, 'count' => count($candles), 'source' => 'twelvedata'];
                }
            }
        }
    }

    // ── Legacy: Yahoo chart endpoint (mostly dead, kept as last resort) ──
    $range = in_array($interval, ['1h']) ? '5d' : '1d';
    $url   = "https://query2.finance.yahoo.com/v8/finance/chart/{$nseSym}.NS?range={$range}&interval={$interval}";
    $raw   = httpGet($url, 15);
    if (!$raw) return ['error' => 'Could not fetch intraday data (no API key configured or Twelve Data unavailable)'];

    $data   = json_decode($raw, true);
    $result = $data['chart']['result'][0] ?? null;
    if (!$result) return ['error' => 'No intraday data available'];

    $timestamps = $result['timestamp'] ?? [];
    $q = $result['indicators']['quote'][0] ?? [];
    $candles = [];
    foreach ($timestamps as $i => $ts) {
        $c = $q['close'][$i]  ?? null;
        if ($c === null) continue;
        $candles[] = [
            't' => $ts,
            'o' => round((float)($q['open'][$i]   ?? $c), 2),
            'h' => round((float)($q['high'][$i]   ?? $c), 2),
            'l' => round((float)($q['low'][$i]    ?? $c), 2),
            'c' => round((float)$c, 2),
            'v' => (int)($q['volume'][$i] ?? 0),
        ];
    }
    return ['symbol' => $symbol, 'interval' => $interval, 'candles' => $candles, 'count' => count($candles), 'source' => 'yahoo_legacy'];
}

// ── Pivot points API ──────────────────────────────────────────
function apiPivots(string $symbol): array
{
    if (!$symbol) return ['error' => 'No symbol'];
    $history = yahooHistory($symbol, 5);
    if (count($history) < 2) return ['error' => 'Not enough historical data'];
    $pivots = pivotPoints($history);
    return ['symbol' => $symbol, 'pivots' => $pivots, 'computed_from' => 'Previous day OHLC'];
}
