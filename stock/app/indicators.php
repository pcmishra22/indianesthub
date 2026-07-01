<?php
declare(strict_types=1);
/**
 * indicators.php — pure technical-analysis math. No network calls, no file
 * I/O, no globals. Every function here takes price/OHLCV arrays in and
 * returns numbers/arrays out — safe to unit-test in isolation.
 */

/** Extract close prices from history rows */
function closes(array $history): array
{
    return array_column($history, 'close');
}

/** Simple Moving Average */
function sma(array $prices, int $period): array
{
    $result = [];
    $n = count($prices);
    for ($i = 0; $i < $n; $i++) {
        if ($i < $period - 1) { $result[] = null; continue; }
        $slice = array_slice($prices, $i - $period + 1, $period);
        $result[] = round(array_sum($slice) / $period, 4);
    }
    return $result;
}

/** Exponential Moving Average */
function ema(array $prices, int $period): array
{
    $result = [];
    $k = 2 / ($period + 1);
    $n = count($prices);
    $prevEma = null;
    for ($i = 0; $i < $n; $i++) {
        if ($i < $period - 1) { $result[] = null; continue; }
        if ($prevEma === null) {
            // seed with SMA
            $prevEma = array_sum(array_slice($prices, 0, $period)) / $period;
            $result[] = round($prevEma, 4);
        } else {
            $prevEma = ($prices[$i] - $prevEma) * $k + $prevEma;
            $result[] = round($prevEma, 4);
        }
    }
    return $result;
}

/** RSI (14-period) */
function rsi(array $prices, int $period = 14): array
{
    $result = [];
    $n = count($prices);
    if ($n < $period + 1) return array_fill(0, $n, null);

    $gains = $losses = [];
    for ($i = 1; $i < $n; $i++) {
        $diff = $prices[$i] - $prices[$i - 1];
        $gains[]  = max(0, $diff);
        $losses[] = max(0, -$diff);
    }

    // Initial avg
    $avgGain = array_sum(array_slice($gains, 0, $period)) / $period;
    $avgLoss = array_sum(array_slice($losses, 0, $period)) / $period;

    // Pad nulls for first $period prices
    for ($i = 0; $i <= $period; $i++) $result[] = null;

    for ($i = $period; $i < count($gains); $i++) {
        $avgGain = ($avgGain * ($period - 1) + $gains[$i]) / $period;
        $avgLoss = ($avgLoss * ($period - 1) + $losses[$i]) / $period;
        $rs  = $avgLoss == 0 ? 100 : $avgGain / $avgLoss;
        $result[] = round(100 - 100 / (1 + $rs), 2);
    }
    return $result;
}

/** MACD: returns ['macd', 'signal', 'hist'] arrays */
function macd(array $prices, int $fast = 12, int $slow = 26, int $signal = 9): array
{
    $emaFast   = ema($prices, $fast);
    $emaSlow   = ema($prices, $slow);
    $macdLine  = [];
    foreach ($emaFast as $i => $ef) {
        $es = $emaSlow[$i] ?? null;
        $macdLine[] = ($ef !== null && $es !== null) ? round($ef - $es, 4) : null;
    }
    // Signal line = EMA9 of MACD (only non-null MACD values)
    $nonNull = array_values(array_filter($macdLine, fn($v) => $v !== null));
    $sigLine = ema($nonNull, $signal);

    // Re-align signal to full length
    $nullCount = count(array_filter($macdLine, fn($v) => $v === null));
    $fullSignal = array_merge(array_fill(0, $nullCount, null), $sigLine);

    $hist = [];
    foreach ($macdLine as $i => $m) {
        $s = $fullSignal[$i] ?? null;
        $hist[] = ($m !== null && $s !== null) ? round($m - $s, 4) : null;
    }
    return ['macd' => $macdLine, 'signal' => $fullSignal, 'hist' => $hist];
}

/** Bollinger Bands (20, 2) — returns ['upper','middle','lower'] */
function bollingerBands(array $prices, int $period = 20, float $stdDev = 2.0): array
{
    $upper = $middle = $lower = [];
    $n = count($prices);
    for ($i = 0; $i < $n; $i++) {
        if ($i < $period - 1) {
            $upper[] = $middle[] = $lower[] = null;
            continue;
        }
        $slice = array_slice($prices, $i - $period + 1, $period);
        $mean  = array_sum($slice) / $period;
        $variance = 0;
        foreach ($slice as $v) $variance += ($v - $mean) ** 2;
        $sd = sqrt($variance / $period);
        $upper[]  = round($mean + $stdDev * $sd, 2);
        $middle[] = round($mean, 2);
        $lower[]  = round($mean - $stdDev * $sd, 2);
    }
    return ['upper' => $upper, 'middle' => $middle, 'lower' => $lower];
}

/** Supertrend (10, 3) — simplified using ATR */
function supertrend(array $history, int $period = 10, float $mult = 3.0): string
{
    $n = count($history);
    if ($n < $period + 1) return 'Bullish';

    $trs = [];
    for ($i = 1; $i < $n; $i++) {
        $h = $history[$i]['high'];
        $l = $history[$i]['low'];
        $pc = $history[$i - 1]['close'];
        $trs[] = max($h - $l, abs($h - $pc), abs($l - $pc));
    }
    // ATR = SMA of TRs
    $atr = array_sum(array_slice($trs, -$period)) / $period;

    $last = $history[$n - 1];
    $hl2 = ($last['high'] + $last['low']) / 2;
    $upperBand = $hl2 + $mult * $atr;
    $lowerBand = $hl2 - $mult * $atr;

    return $last['close'] > $lowerBand ? 'Bullish' : 'Bearish';
}

/** VWAP — intraday approximation using daily data */
function vwapDaily(array $history): float
{
    // Use last 20 days as proxy
    $slice = array_slice($history, -20);
    $num = $den = 0;
    foreach ($slice as $r) {
        $typical = ($r['high'] + $r['low'] + $r['close']) / 3;
        $num += $typical * $r['volume'];
        $den += $r['volume'];
    }
    return $den > 0 ? round($num / $den, 2) : 0;
}

/** Alias: vwap() — same as vwapDaily() */
function vwap(array $history): float { return vwapDaily($history); }

/** ATR (Average True Range, 14-period) */
function atr(array $history, int $period = 14): float
{
    $n = count($history);
    if ($n < 2) return 0.0;
    $trs = [];
    for ($i = 1; $i < $n; $i++) {
        $h  = $history[$i]['high'];
        $l  = $history[$i]['low'];
        $pc = $history[$i - 1]['close'];
        $trs[] = max($h - $l, abs($h - $pc), abs($l - $pc));
    }
    $slice = array_slice($trs, -$period);
    return count($slice) ? array_sum($slice) / count($slice) : 0.0;
}

/** Alias: candlestickPatterns() — same as detectPatterns() */
function candlestickPatterns(array $history): array { return detectPatterns($history); }

/** Detect simple candlestick patterns on last 3 candles */
function detectPatterns(array $history): array
{
    $patterns = [];
    $n = count($history);
    if ($n < 3) return $patterns;

    $c = $history[$n - 1];
    $p = $history[$n - 2];
    $pp = $history[$n - 3];

    $body = abs($c['close'] - $c['open']);
    $range = $c['high'] - $c['low'];
    $upperWick = $c['high'] - max($c['open'], $c['close']);
    $lowerWick = min($c['open'], $c['close']) - $c['low'];

    // Doji
    if ($range > 0 && $body / $range < 0.1) {
        $patterns[] = ['name' => 'Doji', 'type' => 'neutral', 'description' => 'Indecision candle — market at equilibrium'];
    }
    // Hammer
    if ($lowerWick > 2 * $body && $upperWick < $body && $c['close'] > $c['open']) {
        $patterns[] = ['name' => 'Hammer', 'type' => 'bullish', 'description' => 'Potential reversal from downtrend'];
    }
    // Shooting Star
    if ($upperWick > 2 * $body && $lowerWick < $body && $c['close'] < $c['open']) {
        $patterns[] = ['name' => 'Shooting Star', 'type' => 'bearish', 'description' => 'Potential reversal from uptrend'];
    }
    // Engulfing
    if ($c['close'] > $c['open'] && $p['close'] < $p['open'] &&
        $c['open'] <= $p['close'] && $c['close'] >= $p['open']) {
        $patterns[] = ['name' => 'Bullish Engulfing', 'type' => 'bullish', 'description' => 'Strong reversal signal — bulls took control'];
    }
    if ($c['close'] < $c['open'] && $p['close'] > $p['open'] &&
        $c['open'] >= $p['close'] && $c['close'] <= $p['open']) {
        $patterns[] = ['name' => 'Bearish Engulfing', 'type' => 'bearish', 'description' => 'Strong reversal signal — bears took control'];
    }
    // Morning/Evening star
    $midBody = abs($p['close'] - $p['open']);
    if ($pp['close'] < $pp['open'] && $midBody < ($pp['high'] - $pp['low']) * 0.3
        && $c['close'] > $c['open'] && $c['close'] > ($pp['open'] + $pp['close']) / 2) {
        $patterns[] = ['name' => 'Morning Star', 'type' => 'bullish', 'description' => 'Three-candle bullish reversal pattern'];
    }
    // Volume spike
    $avgVol = array_sum(array_column(array_slice($history, -10, 9), 'volume')) / 9;
    if ($avgVol > 0 && $c['volume'] > 1.5 * $avgVol) {
        $patterns[] = ['name' => 'Volume Spike', 'type' => $c['close'] >= $c['open'] ? 'bullish' : 'bearish',
            'description' => sprintf('%.1fx average volume — strong participation', $c['volume'] / $avgVol)];
    }

    return $patterns ?: [['name' => 'No Clear Pattern', 'type' => 'neutral', 'description' => 'No strong candlestick pattern detected']];
}


function adx(array $history, int $period = 14): array
{
    $n = count($history);
    if ($n < $period + 2) return ['adx' => null, 'plus_di' => null, 'minus_di' => null, 'trend_strength' => 'Weak'];
    $trs = $plus = $minus = [];
    for ($i = 1; $i < $n; $i++) {
        $h = $history[$i]['high']; $l = $history[$i]['low']; $pc = $history[$i-1]['close'];
        $ph = $history[$i-1]['high']; $pl = $history[$i-1]['low'];
        $trs[]  = max($h - $l, abs($h - $pc), abs($l - $pc));
        $upMove = $h - $ph; $dnMove = $pl - $l;
        $plus[]  = ($upMove > $dnMove && $upMove > 0) ? $upMove : 0;
        $minus[] = ($dnMove > $upMove && $dnMove > 0) ? $dnMove : 0;
    }
    // Wilder smoothing
    $atr = array_sum(array_slice($trs, 0, $period));
    $pdi = array_sum(array_slice($plus, 0, $period));
    $mdi = array_sum(array_slice($minus, 0, $period));
    $dxArr = [];
    for ($i = $period; $i < count($trs); $i++) {
        $atr = $atr - $atr / $period + $trs[$i];
        $pdi = $pdi - $pdi / $period + $plus[$i];
        $mdi = $mdi - $mdi / $period + $minus[$i];
        $pdiPct = $atr > 0 ? $pdi / $atr * 100 : 0;
        $mdiPct = $atr > 0 ? $mdi / $atr * 100 : 0;
        $sum    = $pdiPct + $mdiPct;
        $dxArr[] = $sum > 0 ? abs($pdiPct - $mdiPct) / $sum * 100 : 0;
    }
    if (empty($dxArr)) return ['adx' => null, 'plus_di' => null, 'minus_di' => null, 'trend_strength' => 'Weak'];
    $adxVal = array_sum($dxArr) / count($dxArr);
    $atrF = array_sum(array_slice($trs, -$period)) / $period;
    $pdiF = $atr > 0 ? $pdi / $atr * 100 : 0;
    $mdiF = $atr > 0 ? $mdi / $atr * 100 : 0;
    $strength = $adxVal >= 40 ? 'Very Strong' : ($adxVal >= 25 ? 'Strong' : ($adxVal >= 20 ? 'Moderate' : 'Weak'));
    return [
        'adx'           => round($adxVal, 1),
        'plus_di'       => round($pdiF, 1),
        'minus_di'      => round($mdiF, 1),
        'trend_strength'=> $strength,
        'direction'     => $pdiF > $mdiF ? 'Bullish' : 'Bearish',
    ];
}

/** Stochastic Oscillator %K and %D */
function stochastic(array $history, int $kPeriod = 14, int $dPeriod = 3): array
{
    $n = count($history);
    if ($n < $kPeriod) return ['k' => null, 'd' => null, 'signal' => 'N/A'];
    $kArr = [];
    for ($i = $kPeriod - 1; $i < $n; $i++) {
        $slice = array_slice($history, $i - $kPeriod + 1, $kPeriod);
        $hh    = max(array_column($slice, 'high'));
        $ll    = min(array_column($slice, 'low'));
        $range = $hh - $ll;
        $kArr[] = $range > 0 ? round(($history[$i]['close'] - $ll) / $range * 100, 2) : 50.0;
    }
    $kLast = end($kArr);
    $dLast = count($kArr) >= $dPeriod ? round(array_sum(array_slice($kArr, -$dPeriod)) / $dPeriod, 2) : $kLast;
    $signal = $kLast > 80 ? 'Overbought' : ($kLast < 20 ? 'Oversold' : ($kLast > $dLast ? 'Bullish' : 'Bearish'));
    return ['k' => round($kLast, 1), 'd' => round($dLast, 1), 'signal' => $signal];
}

/** On Balance Volume (OBV) — cumulative volume indicator */
function obv(array $history): array
{
    $n = count($history);
    if ($n < 2) return ['obv' => null, 'trend' => 'N/A'];
    $obvVal = 0;
    $vals   = [];
    for ($i = 1; $i < $n; $i++) {
        $c = $history[$i]['close']; $pc = $history[$i-1]['close']; $v = $history[$i]['volume'] ?? 0;
        if ($c > $pc)      $obvVal += $v;
        elseif ($c < $pc)  $obvVal -= $v;
        $vals[] = $obvVal;
    }
    $last5  = array_slice($vals, -5);
    $trend  = end($last5) > $last5[0] ? 'Rising (accumulation)' : 'Falling (distribution)';
    return ['obv' => $obvVal, 'trend' => $trend, 'last' => end($vals)];
}

/** Standard Pivot Points (daily) — PP, R1/R2/R3, S1/S2/S3 */
function pivotPoints(array $history): array
{
    if (empty($history)) return [];
    $prev = $history[count($history) - 2] ?? end($history);
    $h = $prev['high']; $l = $prev['low']; $c = $prev['close'];
    $pp = ($h + $l + $c) / 3;
    return [
        'PP' => round($pp, 2),
        'R1' => round(2 * $pp - $l, 2),
        'R2' => round($pp + ($h - $l), 2),
        'R3' => round($h + 2 * ($pp - $l), 2),
        'S1' => round(2 * $pp - $h, 2),
        'S2' => round($pp - ($h - $l), 2),
        'S3' => round($l - 2 * ($h - $pp), 2),
        // CPR — Central Pivot Range
        'BC' => round(($h + $l) / 2, 2),          // Bottom of CPR
        'TC' => round((2 * $pp) - ($h + $l) / 2, 2), // Top of CPR
    ];
}


// ══════════════════════════════════════════════════════════════
//  ADDITIONAL INDICATORS
// ══════════════════════════════════════════════════════════════

/** Williams %R — momentum oscillator, -100 to 0. Below -80 = oversold, above -20 = overbought */
function williamsR(array $history, int $period = 14): ?float
{
    $n = count($history);
    if ($n < $period) return null;
    $slice = array_slice($history, -$period);
    $hh    = max(array_column($slice, 'high'));
    $ll    = min(array_column($slice, 'low'));
    $close = end($history)['close'];
    return $hh - $ll > 0 ? round((($hh - $close) / ($hh - $ll)) * -100, 2) : -50.0;
}

/** CCI — Commodity Channel Index. Measures price deviation from average */
function cci(array $history, int $period = 20): ?float
{
    $n = count($history);
    if ($n < $period) return null;
    $slice = array_slice($history, -$period);
    $tp    = array_map(fn($r) => ($r['high'] + $r['low'] + $r['close']) / 3, $slice);
    $mean  = array_sum($tp) / $period;
    $mad   = array_sum(array_map(fn($v) => abs($v - $mean), $tp)) / $period;
    return $mad > 0 ? round(($tp[count($tp)-1] - $mean) / (0.015 * $mad), 2) : 0.0;
}

/** MFI — Money Flow Index. Volume-weighted RSI. 0-100, <20=oversold, >80=overbought */
function mfi(array $history, int $period = 14): ?float
{
    $n = count($history);
    if ($n < $period + 1) return null;
    $posFlow = $negFlow = 0.0;
    for ($i = $n - $period; $i < $n; $i++) {
        $tp   = ($history[$i]['high'] + $history[$i]['low'] + $history[$i]['close']) / 3;
        $tpp  = ($history[$i-1]['high'] + $history[$i-1]['low'] + $history[$i-1]['close']) / 3;
        $vol  = $history[$i]['volume'] ?? 0;
        $rmf  = $tp * $vol;
        if ($tp > $tpp)  $posFlow += $rmf;
        else             $negFlow += $rmf;
    }
    if ($negFlow == 0) return 100.0;
    return round(100 - 100 / (1 + $posFlow / $negFlow), 2);
}

/** Ichimoku Cloud — returns key lines */
function ichimoku(array $history): array
{
    $n = count($history);
    if ($n < 52) return ['tenkan' => null, 'kijun' => null, 'senkou_a' => null, 'senkou_b' => null, 'signal' => 'Insufficient data'];

    $midpoint = function(array $h, int $from, int $len) {
        $slice = array_slice($h, $from, $len);
        return (max(array_column($slice, 'high')) + min(array_column($slice, 'low'))) / 2;
    };

    $tenkan   = $midpoint($history, $n - 9,  9);   // Conversion line (9-period)
    $kijun    = $midpoint($history, $n - 26, 26);  // Base line (26-period)
    $senkou_a = round(($tenkan + $kijun) / 2, 2);  // Leading Span A
    $senkou_b = round($midpoint($history, $n - 52, 52), 2); // Leading Span B (52-period)
    $chikou   = end($history)['close'];             // Lagging span

    $price    = $chikou;
    $aboveCloud = $price > max($senkou_a, $senkou_b);
    $belowCloud = $price < min($senkou_a, $senkou_b);
    $bullishCloud = $senkou_a > $senkou_b;

    $signal = $aboveCloud && $tenkan > $kijun && $bullishCloud ? 'Strong Bullish'
            : ($aboveCloud ? 'Bullish'
            : ($belowCloud && $tenkan < $kijun && !$bullishCloud ? 'Strong Bearish'
            : ($belowCloud ? 'Bearish' : 'Neutral (in cloud)')));

    return [
        'tenkan'      => round($tenkan, 2),
        'kijun'       => round($kijun, 2),
        'senkou_a'    => $senkou_a,
        'senkou_b'    => $senkou_b,
        'chikou'      => round($chikou, 2),
        'signal'      => $signal,
        'above_cloud' => $aboveCloud,
        'below_cloud' => $belowCloud,
        'cloud_bullish'=> $bullishCloud,
    ];
}

/** Fibonacci retracement levels from recent swing high/low */
function fibonacci(array $history, int $lookback = 60): array
{
    $slice = array_slice($history, -min($lookback, count($history)));
    $high  = max(array_column($slice, 'high'));
    $low   = min(array_column($slice, 'low'));
    $diff  = $high - $low;
    return [
        'high'  => round($high, 2),
        'low'   => round($low, 2),
        '0'     => round($high, 2),
        '23.6'  => round($high - $diff * 0.236, 2),
        '38.2'  => round($high - $diff * 0.382, 2),
        '50'    => round($high - $diff * 0.500, 2),
        '61.8'  => round($high - $diff * 0.618, 2),
        '78.6'  => round($high - $diff * 0.786, 2),
        '100'   => round($low,  2),
        'ext_127'=> round($low  - $diff * 0.272, 2),
        'ext_161'=> round($low  - $diff * 0.618, 2),
    ];
}

/** 52W position — where is price in its yearly range (0-100%) */
function position52W(float $price, float $high52, float $low52): ?float
{
    $range = $high52 - $low52;
    return $range > 0 ? round(($price - $low52) / $range * 100, 1) : null;
}


