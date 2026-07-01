<?php
declare(strict_types=1);
/**
 * api/intraday.php — intraday candle data and pivot points.
 * Primary: EODHD /api/intraday endpoint (1min, 5min, 1hour intervals, NSE free).
 * Fallback: Twelve Data time_series (NSE requires paid plan).
 * Last resort: Yahoo chart endpoint (mostly dead).
 */

function apiIntraday(string $symbol, string $interval = '5m'): array
{
    if (!$symbol) return ['error' => 'No symbol'];
    $nseSym = strtoupper(str_replace('.NS', '', $symbol));

    // ── Priority 1: EODHD intraday ───────────────────────────────
    $eodhdKey = getenv('EODHD_API_KEY') ?: '';
    if ($eodhdKey) {
        // EODHD intraday interval format: 1m, 5m, 15m, 30m, 1h
        $eodhdInterval = match($interval) {
            '5m'  => '5m',
            '15m' => '15m',
            '1h'  => '1h',
            default => '5m',
        };
        $from = date('Y-m-d', strtotime($interval === '1h' ? '-5 days' : '-1 day'));
        $url  = 'https://eodhd.com/api/intraday/' . urlencode($nseSym . '.NSE')
              . '?api_token=' . urlencode($eodhdKey)
              . '&fmt=json&interval=' . $eodhdInterval
              . '&from=' . $from;

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
            $data = json_decode($raw, true);
            if (is_array($data) && !empty($data)) {
                $candles = [];
                foreach ($data as $v) {
                    $close = (float)($v['close'] ?? 0);
                    if ($close <= 0) continue;
                    $ts = isset($v['timestamp']) ? (int)$v['timestamp'] : strtotime($v['datetime'] ?? '');
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
                    return ['symbol' => $symbol, 'interval' => $interval, 'candles' => $candles, 'count' => count($candles), 'source' => 'eodhd'];
                }
            }
        }
    }

    // ── Priority 2: Twelve Data intraday ─────────────────────────
    if (DATA_API_KEY) {
        $tdInterval = match($interval) { '5m' => '5min', '15m' => '15min', '1h' => '1h', default => '5min' };
        $url = 'https://api.twelvedata.com/time_series?symbol=' . urlencode($nseSym)
             . '&exchange=NSE&interval=' . $tdInterval . '&outputsize=80'
             . '&apikey=' . urlencode(DATA_API_KEY);

        $ch = curl_init($url);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 12, CURLOPT_SSL_VERIFYPEER => false]);
        $raw  = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($raw && $code === 200) {
            $d = json_decode($raw, true);
            if (!empty($d['values']) && !isset($d['status'])) {
                $candles = [];
                foreach (array_reverse($d['values']) as $v) {
                    $close = (float)($v['close'] ?? 0);
                    if ($close <= 0) continue;
                    $ts = strtotime($v['datetime'] ?? '');
                    if (!$ts) continue;
                    $candles[] = ['t' => $ts, 'o' => round((float)($v['open'] ?? $close), 2), 'h' => round((float)($v['high'] ?? $close), 2), 'l' => round((float)($v['low'] ?? $close), 2), 'c' => round($close, 2), 'v' => (int)($v['volume'] ?? 0)];
                }
                if (!empty($candles)) return ['symbol' => $symbol, 'interval' => $interval, 'candles' => $candles, 'count' => count($candles), 'source' => 'twelvedata'];
            }
        }
    }

    // ── Priority 3: Yahoo chart (legacy last resort) ──────────────
    $url = "https://query2.finance.yahoo.com/v8/finance/chart/{$nseSym}.NS?range=1d&interval={$interval}";
    $raw = httpGet($url, 15);
    if ($raw) {
        $data   = json_decode($raw, true);
        $result = $data['chart']['result'][0] ?? null;
        if ($result) {
            $ts  = $result['timestamp'] ?? [];
            $q   = $result['indicators']['quote'][0] ?? [];
            $candles = [];
            foreach ($ts as $i => $t) {
                $c = $q['close'][$i] ?? null;
                if ($c === null) continue;
                $candles[] = ['t' => $t, 'o' => round((float)($q['open'][$i] ?? $c), 2), 'h' => round((float)($q['high'][$i] ?? $c), 2), 'l' => round((float)($q['low'][$i] ?? $c), 2), 'c' => round((float)$c, 2), 'v' => (int)($q['volume'][$i] ?? 0)];
            }
            if (!empty($candles)) return ['symbol' => $symbol, 'interval' => $interval, 'candles' => $candles, 'count' => count($candles), 'source' => 'yahoo_legacy'];
        }
    }

    return ['error' => 'No intraday data available. EODHD intraday requires the All-World plan — check your subscription at eodhistoricaldata.com'];
}

function apiPivots(string $symbol): array
{
    if (!$symbol) return ['error' => 'No symbol'];
    $history = yahooHistory($symbol, 5);
    if (count($history) < 2) return ['error' => 'Not enough historical data'];
    return ['symbol' => $symbol, 'pivots' => pivotPoints($history), 'computed_from' => 'Previous day OHLC'];
}
