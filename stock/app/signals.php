<?php
declare(strict_types=1);
/**
 * signals.php — turns raw indicator values into buy/sell/hold signals,
 * momentum scores, and human-readable reasoning. Depends on indicators.php
 * (pure math) and, for multiTimeframe(), on http.php for a weekly-data fetch.
 */

/** Generate buy/sell signal + reasoning from indicators */
function generateSignal(array $quote, array $history, array $indicators): array
{
    $price  = $quote['regularMarketPrice'] ?? 0;
    $rsiVal = end(array_filter($indicators['rsi'], fn($v) => $v !== null)) ?: 50;
    $macdH  = end(array_filter($indicators['macd']['hist'], fn($v) => $v !== null)) ?: 0;
    $macdV  = end(array_filter($indicators['macd']['macd'], fn($v) => $v !== null)) ?: 0;
    $ema20  = end(array_filter($indicators['ema20'], fn($v) => $v !== null)) ?: $price;
    $ema50  = end(array_filter($indicators['ema50'], fn($v) => $v !== null)) ?: $price;
    $bbU    = end(array_filter($indicators['bb']['upper'], fn($v) => $v !== null)) ?: $price * 1.05;
    $bbL    = end(array_filter($indicators['bb']['lower'], fn($v) => $v !== null)) ?: $price * 0.95;
    $vwap   = $indicators['vwap'];
    $st     = $indicators['supertrend'];

    $bullish = 0; $bearish = 0;
    $bullFactors = []; $bearFactors = [];

    // EMA signals
    if ($price > $ema20 && $price > $ema50) { $bullish += 2; $bullFactors[] = 'Price above EMA20 and EMA50 — uptrend intact'; }
    elseif ($price < $ema20 && $price < $ema50) { $bearish += 2; $bearFactors[] = 'Price below EMA20 and EMA50 — downtrend active'; }
    if ($ema20 > $ema50) { $bullish++; $bullFactors[] = 'Golden Cross: EMA20 above EMA50'; }
    elseif ($ema20 < $ema50) { $bearish++; $bearFactors[] = 'Death Cross: EMA20 below EMA50'; }

    // RSI
    if ($rsiVal < 30) { $bullish += 2; $bullFactors[] = "RSI oversold at {$rsiVal} — potential bounce"; }
    elseif ($rsiVal > 70) { $bearish += 2; $bearFactors[] = "RSI overbought at {$rsiVal} — potential pullback"; }
    elseif ($rsiVal >= 50) { $bullish++; $bullFactors[] = "RSI at {$rsiVal} — bullish momentum"; }
    else { $bearish++; $bearFactors[] = "RSI at {$rsiVal} — bearish momentum"; }

    // MACD
    if ($macdH > 0 && $macdV > 0) { $bullish += 2; $bullFactors[] = 'MACD above signal line — bullish crossover'; }
    elseif ($macdH < 0 && $macdV < 0) { $bearish += 2; $bearFactors[] = 'MACD below signal line — bearish crossover'; }
    elseif ($macdH > 0) { $bullish++; $bullFactors[] = 'MACD histogram turning positive'; }
    else { $bearish++; $bearFactors[] = 'MACD histogram turning negative'; }

    // Bollinger
    if ($price <= $bbL) { $bullish++; $bullFactors[] = 'Price at lower Bollinger Band — oversold zone'; }
    elseif ($price >= $bbU) { $bearish++; $bearFactors[] = 'Price at upper Bollinger Band — overbought zone'; }

    // Supertrend
    if ($st === 'Bullish') { $bullish += 2; $bullFactors[] = 'Supertrend indicator is Bullish'; }
    else { $bearish += 2; $bearFactors[] = 'Supertrend indicator is Bearish'; }

    // VWAP
    if ($price > $vwap && $vwap > 0) { $bullish++; $bullFactors[] = 'Price above VWAP — intraday buyers in control'; }
    elseif ($price < $vwap && $vwap > 0) { $bearish++; $bearFactors[] = 'Price below VWAP — sellers in control'; }

    // Change
    $chgPct = $quote['regularMarketChangePercent'] ?? 0;
    if ($chgPct > 1.5) { $bullish++; $bullFactors[] = sprintf('Strong positive day: +%.2f%%', $chgPct); }
    elseif ($chgPct < -1.5) { $bearish++; $bearFactors[] = sprintf('Strong negative day: %.2f%%', $chgPct); }

    $total = $bullish + $bearish;
    if ($total === 0) $total = 1;
    $confidence = (int) round(max($bullish, $bearish) / $total * 100);

    if ($bullish > $bearish + 1) {
        $signal = 'Buy';
        $trend  = 'Bullish';
        $verdict = "The technical picture for this stock leans bullish. " . implode('. ', $bullFactors) . ". Consider entering near current levels with a stop below EMA20.";
    } elseif ($bearish > $bullish + 1) {
        $signal = 'Sell';
        $trend  = 'Bearish';
        $verdict = "Bears are in control. " . implode('. ', $bearFactors) . ". Avoid fresh long positions; wait for RSI to reach oversold before re-entry.";
    } else {
        $signal = 'Hold';
        $trend  = 'Sideways';
        $verdict = "Mixed signals. Bullish: " . implode(', ', $bullFactors ?: ['none']) . ". Bearish: " . implode(', ', $bearFactors ?: ['none']) . ". Wait for a cleaner setup.";
    }

    return compact('signal', 'trend', 'confidence', 'bullFactors', 'bearFactors', 'verdict');
}

/** Map Yahoo symbol to NSE symbol display */
function toNseDisplay(string $ySymbol): string
{
    return str_replace('.NS', '', $ySymbol);
}

/**
 * Momentum Score (0-100): combines price velocity, volume surge,
 * RSI direction, MACD strength, EMA alignment, and Supertrend.
 * Positive = bullish momentum, negative = bearish momentum.
 */
/** 5-day percentage change from history */
function change5d(array $history): float
{
    $n = count($history);
    if ($n < 6) return 0.0;
    $now  = (float)$history[$n-1]["close"];
    $prev = (float)$history[$n-6]["close"];
    return $prev > 0 ? round(($now - $prev) / $prev * 100, 2) : 0.0;
}

function momentumScore(array $quote, array $history, array $indicators): array
{
    $price    = $quote['regularMarketPrice'] ?? 0;
    $chgPct   = $quote['regularMarketChangePercent'] ?? 0;
    $avgVol   = $quote['averageVolume'] ?? 1;
    $curVol   = $quote['regularMarketVolume'] ?? 0;
    $volRatio = $avgVol > 0 ? $curVol / $avgVol : 1;

    $closes   = closes($history);
    $n        = count($closes);

    // 1. Price velocity — % change today weighted by volume surge
    $velScore = $chgPct * min($volRatio, 3.0); // cap vol multiplier at 3x

    // 2. Short-term momentum — compare last 3 closes vs 3 before that
    $recent3 = $n >= 6 ? array_sum(array_slice($closes, -3)) / 3 : $price;
    $prev3   = $n >= 6 ? array_sum(array_slice($closes, -6, 3)) / 3 : $price;
    $stMom   = $prev3 > 0 ? (($recent3 - $prev3) / $prev3) * 100 : 0;

    // 3. RSI momentum — distance from 50 (neutral)
    $rsiLast = end(array_filter($indicators['rsi'], fn($v) => $v !== null)) ?: 50;
    $rsiMom  = ($rsiLast - 50) / 50 * 30; // scale to ±30

    // 4. MACD histogram direction and strength
    $histArr    = $indicators['macd']['hist'];
    $histNonNull = array_values(array_filter($histArr, fn($v) => $v !== null));
    $hLast  = end($histNonNull) ?: 0;
    $hPrev  = count($histNonNull) >= 2 ? $histNonNull[count($histNonNull)-2] : 0;
    $macdMom = ($hLast > $hPrev ? 1 : -1) * min(abs($hLast) * 10, 20); // direction + strength

    // 5. EMA alignment
    $ema20L = end(array_filter($indicators['ema20'], fn($v) => $v !== null)) ?: $price;
    $ema50L = end(array_filter($indicators['ema50'], fn($v) => $v !== null)) ?: $price;
    $emaScore = 0;
    if ($price > $ema20L && $ema20L > $ema50L) $emaScore = 15;
    elseif ($price > $ema20L) $emaScore = 8;
    elseif ($price < $ema20L && $ema20L < $ema50L) $emaScore = -15;
    elseif ($price < $ema20L) $emaScore = -8;

    // 6. Supertrend
    $stScore = $indicators['supertrend'] === 'Bullish' ? 10 : -10;

    // 7. 52-week position
    $w52h = $quote['fiftyTwoWeekHigh'] ?? $price;
    $w52l = $quote['fiftyTwoWeekLow'] ?? $price;
    $w52range = $w52h - $w52l;
    $w52pos = $w52range > 0 ? (($price - $w52l) / $w52range) * 20 - 10 : 0; // -10 to +10

    $total = $velScore + $stMom + $rsiMom + $macdMom + $emaScore + $stScore + $w52pos;

    // Normalize to -100..+100
    $normalized = max(-100, min(100, $total));

    // Volume surge flag
    $volSurge = $volRatio >= 1.5;
    $volLabel = $volRatio >= 2.0 ? sprintf('🔥 %.1fx vol', $volRatio)
              : ($volRatio >= 1.5 ? sprintf('📈 %.1fx vol', $volRatio)
              : sprintf('%.1fx vol', $volRatio));

    // Rank label
    if ($normalized >= 40)      $rank = 'Strong Buy';
    elseif ($normalized >= 15)  $rank = 'Buy';
    elseif ($normalized >= -15) $rank = 'Hold';
    elseif ($normalized >= -40) $rank = 'Sell';
    else                        $rank = 'Strong Sell';

    // Momentum change direction vs previous cached score (if available)
    $direction = $chgPct >= 0.5 ? 'rising' : ($chgPct <= -0.5 ? 'falling' : 'flat');

    return [
        'score'      => round($normalized, 1),
        'rank'       => $rank,
        'direction'  => $direction,
        'vol_ratio'  => round($volRatio, 2),
        'vol_surge'  => $volSurge,
        'vol_label'  => $volLabel,
        'components' => [
            'price_velocity' => round($velScore, 2),
            'short_momentum' => round($stMom, 2),
            'rsi_momentum'   => round($rsiMom, 2),
            'macd_momentum'  => round($macdMom, 2),
            'ema_alignment'  => $emaScore,
            'supertrend'     => $stScore,
            '52w_position'   => round($w52pos, 2),
        ],
    ];
}

/** Multi-timeframe signal — fetch weekly data and compare */
function multiTimeframe(string $symbol, float $price, array $dailyHistory): array
{
    // Get weekly data (3mo range, 1wk interval)
    $url     = "https://query2.finance.yahoo.com/v8/finance/chart/{$symbol}?range=1y&interval=1wk";
    $raw     = httpGet($url, 12);
    $weekly  = [];
    if ($raw) {
        $data = json_decode($raw, true);
        $r    = $data['chart']['result'][0] ?? null;
        if ($r) {
            $q   = $r['indicators']['quote'][0] ?? [];
            $n   = count($r['timestamp'] ?? []);
            for ($i = 0; $i < $n; $i++) {
                $c = $q['close'][$i] ?? null;
                $h = $q['high'][$i]  ?? null;
                $l = $q['low'][$i]   ?? null;
                $v = $q['volume'][$i]?? 0;
                if ($c !== null) $weekly[] = ['close'=>(float)$c,'high'=>(float)($h??$c),'low'=>(float)($l??$c),'volume'=>(int)$v];
            }
        }
    }
    if (count($weekly) < 20) {
        return ['daily' => 'N/A', 'weekly' => 'Insufficient data', 'aligned' => false];
    }
    $wCloses  = array_column($weekly, 'close');
    $wEma20   = ema($wCloses, 20);
    $wRsi     = rsi($wCloses);
    $wRsiLast = end(array_filter($wRsi, fn($v) => $v !== null)) ?: 50;
    $wEmaLast = end(array_filter($wEma20, fn($v) => $v !== null)) ?: $price;
    $wMacd    = macd($wCloses);
    $wMacdH   = end(array_filter($wMacd['hist'] ?? [], fn($v) => $v !== null)) ?: 0;

    // Daily signal
    $dCloses  = array_column($dailyHistory, 'close');
    $dEma20L  = end(array_filter(ema($dCloses, 20), fn($v) => $v !== null)) ?: $price;
    $dRsiArr  = rsi($dCloses);
    $dRsiLast = end(array_filter($dRsiArr, fn($v) => $v !== null)) ?: 50;

    $dailySig  = $price > $dEma20L && $dRsiLast > 50 ? 'Bullish' : ($price < $dEma20L && $dRsiLast < 50 ? 'Bearish' : 'Neutral');
    $weeklySig = $price > $wEmaLast && $wRsiLast > 50 && $wMacdH > 0 ? 'Bullish'
               : ($price < $wEmaLast && $wRsiLast < 50 && $wMacdH < 0 ? 'Bearish' : 'Neutral');

    $aligned = ($dailySig === $weeklySig && $dailySig !== 'Neutral');

    return [
        'daily'       => $dailySig,
        'weekly'      => $weeklySig,
        'aligned'     => $aligned,
        'weekly_ema20'=> round($wEmaLast, 2),
        'weekly_rsi'  => round($wRsiLast, 1),
        'weekly_macd' => $wMacdH > 0 ? 'Bullish' : 'Bearish',
        'note'        => $aligned
            ? "Daily and weekly both {$dailySig} — high-conviction signal"
            : "Daily {$dailySig} vs Weekly {$weeklySig} — signals not aligned, higher risk",
    ];
}

/** Volume spike detection — is today's volume unusually high? */
function volumeAnalysis(array $history): array
{
    $n = count($history);
    if ($n < 20) return ['ratio' => null, 'label' => 'N/A', 'spike' => false];
    $recent    = $history[$n-1];
    $past20vol = array_sum(array_column(array_slice($history, -21, 20), 'volume')) / 20;
    $ratio     = $past20vol > 0 ? round($recent['volume'] / $past20vol, 2) : 1.0;
    $spike     = $ratio >= 2.0;
    $label     = $ratio >= 3.0 ? '🔥 Huge spike ('.$ratio.'x avg)'
               : ($ratio >= 2.0 ? '📈 High volume ('.$ratio.'x avg)'
               : ($ratio >= 1.3 ? 'Above average ('.$ratio.'x)'
               : ($ratio < 0.7  ? 'Low volume ('.$ratio.'x)' : 'Normal ('.$ratio.'x)')));
    return ['ratio' => $ratio, 'label' => $label, 'spike' => $spike, 'today' => $recent['volume'], 'avg20' => round($past20vol)];
}

function scoreBreakdown(array $quote, array $history, array $indicators, array $adxData, array $stoch, array $obvData, ?float $wr, ?float $cciVal, ?float $mfiVal, array $ichimoku): array
{
    $price  = $quote['regularMarketPrice'] ?? 0;
    $chg    = $quote['regularMarketChangePercent'] ?? 0;
    $rsiVal = end(array_filter($indicators['rsi'], fn($v) => $v !== null)) ?: 50;
    $macdH  = end(array_filter($indicators['macd']['hist'], fn($v) => $v !== null)) ?: 0;
    $ema20  = end(array_filter($indicators['ema20'], fn($v) => $v !== null)) ?: $price;
    $ema50  = end(array_filter($indicators['ema50'], fn($v) => $v !== null)) ?: $price;
    $st     = $indicators['supertrend'];
    $vwap   = $indicators['vwap'];

    $components = [];

    $components[] = ['name'=>'Price vs EMA20',    'score'=> $price>$ema20  ?  1.5 : -1.5, 'detail'=> $price>$ema20  ? 'Above':'Below'];
    $components[] = ['name'=>'EMA20 vs EMA50',    'score'=> $ema20>$ema50  ?  1.2 : -1.2, 'detail'=> $ema20>$ema50  ? 'Golden Cross':'Death Cross'];
    $components[] = ['name'=>'RSI (14)',           'score'=> $rsiVal<30?2.0:($rsiVal>70?-2.0:($rsiVal>=50?0.8:-0.8)), 'detail'=> "{$rsiVal}"];
    $components[] = ['name'=>'MACD Histogram',    'score'=> $macdH>0?1.5:-1.5, 'detail'=> $macdH>0?'Positive':'Negative'];
    $components[] = ['name'=>'Supertrend',         'score'=> $st==='Bullish'?1.5:-1.5, 'detail'=> $st];
    $components[] = ['name'=>'VWAP',               'score'=> ($vwap>0&&$price>$vwap)?1.0:-1.0, 'detail'=> $price>$vwap?'Above':'Below'];
    $components[] = ['name'=>'Day Change%',        'score'=> min(max($chg*0.3,-1.5),1.5), 'detail'=> round($chg,2).'%'];
    $components[] = ['name'=>'ADX Trend',          'score'=> ($adxData['adx']??0)>=25?($adxData['direction']==='Bullish'?1.0:-1.0):0, 'detail'=> $adxData['adx']??'N/A'];
    $components[] = ['name'=>'Stochastic',         'score'=> ($stoch['k']??50)<20?1.5:(($stoch['k']??50)>80?-1.5:0), 'detail'=> $stoch['k']??'N/A'];
    $components[] = ['name'=>'OBV Trend',          'score'=> str_contains($obvData['trend']??'','accum')?1.0:-0.5, 'detail'=> $obvData['trend']??'N/A'];
    $components[] = ['name'=>"Williams %R",        'score'=> ($wr??-50)<-80?1.5:(($wr??-50)>-20?-1.5:0), 'detail'=> $wr??'N/A'];
    $components[] = ['name'=>'CCI',                'score'=> ($cciVal??0)<-100?1.0:(($cciVal??0)>100?-1.0:0), 'detail'=> $cciVal??'N/A'];
    $components[] = ['name'=>'MFI',                'score'=> ($mfiVal??50)<20?1.5:(($mfiVal??50)>80?-1.5:0.5), 'detail'=> $mfiVal??'N/A'];
    $components[] = ['name'=>'Ichimoku',           'score'=> str_contains($ichimoku['signal']??'','Bullish')?1.5:(str_contains($ichimoku['signal']??'','Bearish')?-1.5:0), 'detail'=> $ichimoku['signal']??'N/A'];

    $total = array_sum(array_column($components, 'score'));
    foreach ($components as &$c) {
        $c['score']  = round($c['score'], 2);
        $c['bull']   = $c['score'] > 0;
    }

    return ['components' => $components, 'total' => round($total, 2)];
}

// ── Extend generateSignal to use new indicators ───────────────
function generateSignalFull(array $quote, array $history, array $indicators): array
{
    // Start with base signal
    $base = generateSignal($quote, $history, $indicators);

    $price   = $quote['regularMarketPrice'] ?? 0;
    $adxData = adx($history);
    $stoch   = stochastic($history);
    $obvData = obv($history);

    $bull = $base['bullFactors'];
    $bear = $base['bearFactors'];
    $b = 0; $be = 0;

    // ADX/DMI
    if ($adxData['adx'] !== null) {
        if ($adxData['adx'] >= 25) {
            if ($adxData['direction'] === 'Bullish') { $b += 2; $bull[] = "ADX {$adxData['adx']} — Strong bullish trend (+DI > -DI)"; }
            else { $be += 2; $bear[] = "ADX {$adxData['adx']} — Strong bearish trend (-DI > +DI)"; }
        } else {
            $bear[] = "ADX {$adxData['adx']} — Weak/no trend ({$adxData['trend_strength']})";
        }
    }

    // Stochastic
    if ($stoch['k'] !== null) {
        if ($stoch['k'] < 20) { $b += 2; $bull[] = "Stochastic oversold at {$stoch['k']} — potential reversal up"; }
        elseif ($stoch['k'] > 80) { $be += 2; $bear[] = "Stochastic overbought at {$stoch['k']} — potential reversal down"; }
        elseif ($stoch['signal'] === 'Bullish') { $b++; $bull[] = "Stochastic K({$stoch['k']}) above D({$stoch['d']}) — bullish"; }
        else { $be++; $bear[] = "Stochastic K({$stoch['k']}) below D({$stoch['d']}) — bearish"; }
    }

    // OBV
    if ($obvData['trend']) {
        if (str_contains($obvData['trend'], 'accumulation')) { $b++; $bull[] = "OBV: " . $obvData['trend']; }
        else { $be++; $bear[] = "OBV: " . $obvData['trend']; }
    }

    // Recompute total
    $totalBull = substr_count(implode(',', array_keys(array_filter(['b'=>count($bull)]))), 'b');
    $newBull = count($bull); $newBear = count($bear);
    $total   = $newBull + $newBear ?: 1;
    $conf    = (int)round(max($newBull, $newBear) / $total * 100);

    $signal = ($newBull > $newBear + 1) ? 'Buy' : (($newBear > $newBull + 1) ? 'Sell' : $base['signal']);
    $trend  = $signal === 'Buy' ? 'Bullish' : ($signal === 'Sell' ? 'Bearish' : $base['trend']);
    $verdict = $base['verdict']; // keep existing verdict

    return array_merge($base, [
        'signal'      => $signal,
        'trend'       => $trend,
        'confidence'  => $conf,
        'bullFactors' => $bull,
        'bearFactors' => $bear,
        'adx'         => $adxData,
        'stoch'       => $stoch,
        'obv'         => $obvData,
    ]);
}

