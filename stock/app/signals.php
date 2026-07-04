<?php
declare(strict_types=1);
/**
 * signals.php — turns raw indicator values into buy/sell/hold signals,
 * momentum scores, and human-readable reasoning. Depends on indicators.php
 * (pure math) and, for multiTimeframe(), on http.php for a weekly-data fetch.
 */

/**
 * Return the last non-null value from an array.
 * Avoids passing a temporary expression to end() by reference, which
 * causes a PHP Notice on strict hosts (e.g. Hostinger shared hosting).
 */
function lastNonNull(array $arr, mixed $default = null): mixed
{
    $filtered = array_filter($arr, fn($v) => $v !== null);
    return empty($filtered) ? $default : end($filtered);
}

/**
 * Generate buy/sell signal + reasoning from indicators.
 *
 * Implements the weighted 15-indicator scorecard below, split into three
 * categories (Trend / Momentum / Confirmation). A confidence filter turns
 * the raw bullish/bearish totals into a signal, and a Strong Buy/Strong
 * Sell additionally requires all three categories to agree in direction —
 * this is what prevents a "momentum looks good but trend is weak" false
 * signal from ever showing as a top-conviction call.
 *
 *   Indicator             Bull  Bear   Category
 *   Supertrend              3     3    Trend
 *   EMA20 vs EMA50          2     2    Trend
 *   ADX > 25                2     0    Trend
 *   RSI                     2     2    Momentum
 *   MACD Cross              2     2    Momentum
 *   Price vs EMA20          1     1    Confirmation
 *   VWAP                    1     1    Confirmation
 *   Bollinger Reversal      1     1    Confirmation
 *   Volume Spike            2     2    Confirmation
 *   HH-HL / LH-LL           2     2    Confirmation
 *   Resistance Breakout     2     0    Confirmation
 *   Support Breakdown       0     2    Confirmation
 *   52-week Breakout        3     0    Confirmation
 *   Delivery %              1     1    Confirmation  (no data feed yet — see note below)
 *   OI + Price              2     2    Confirmation  (no data feed yet — see note below)
 *
 * Delivery % and F&O Open Interest aren't available from this app's Yahoo
 * Finance data source (that comes from NSE bhavcopy / F&O feeds this app
 * doesn't fetch). Both are wired in and activate automatically the moment
 * $quote['deliveryPercent'] / $quote['oiChangePercent'] are populated;
 * until then they contribute 0 to both sides instead of being guessed at.
 */
function generateSignal(array $quote, array $history, array $indicators): array
{
    $price  = $quote['regularMarketPrice'] ?? 0;
    $rsiVal = lastNonNull($indicators['rsi']) ?: 50;
    $macdH  = lastNonNull($indicators['macd']['hist']) ?: 0;
    $histVals = array_values(array_filter($indicators['macd']['hist'] ?? [], fn($v) => $v !== null));
    $macdHPrev = count($histVals) >= 2 ? $histVals[count($histVals) - 2] : $macdH;
    $ema20  = lastNonNull($indicators['ema20']) ?: $price;
    $ema50  = lastNonNull($indicators['ema50']) ?: $price;
    $bbU    = lastNonNull($indicators['bb']['upper']) ?: $price * 1.05;
    $bbL    = lastNonNull($indicators['bb']['lower']) ?: $price * 0.95;
    $vwap   = $indicators['vwap'];
    $st     = $indicators['supertrend'];

    $bbBounceBull = false; $bbRejectBear = false;
    $bbUpperArr = $indicators['bb']['upper'] ?? [];
    $bbLowerArr = $indicators['bb']['lower'] ?? [];
    $n = count($history);
    if ($n >= 2) {
        $prevBar = $history[$n - 2];
        $currBar = $history[$n - 1];
        $prevBbL = $bbLowerArr[$n - 2] ?? null;
        $prevBbU = $bbUpperArr[$n - 2] ?? null;
        if ($prevBbL !== null && $prevBar['low'] <= $prevBbL && $currBar['close'] > $prevBar['close'] && $currBar['close'] > $bbL) {
            $bbBounceBull = true;
        }
        if ($prevBbU !== null && $prevBar['high'] >= $prevBbU && $currBar['close'] < $prevBar['close'] && $currBar['close'] < $bbU) {
            $bbRejectBear = true;
        }
    }

    // Per-category accumulators
    $trendB = 0; $trendR = 0;
    $momB   = 0; $momR   = 0;
    $confB  = 0; $confR  = 0;
    $bullFactors = []; $bearFactors = [];

    // ── TREND ──────────────────────────────────────────────────
    if ($st === 'Bullish') { $trendB += 3; $bullFactors[] = 'Supertrend is Bullish — uptrend confirmed'; }
    else                   { $trendR += 3; $bearFactors[] = 'Supertrend is Bearish — downtrend confirmed'; }

    if ($ema20 > $ema50) { $trendB += 2; $bullFactors[] = 'EMA20 above EMA50 — Golden Cross, uptrend'; }
    elseif ($ema20 < $ema50) { $trendR += 2; $bearFactors[] = 'EMA20 below EMA50 — Death Cross, downtrend'; }

    $adxData = adx($history);
    if (($adxData['adx'] ?? 0) > 25) {
        if ($adxData['direction'] === 'Bullish') { $trendB += 2; $bullFactors[] = "ADX {$adxData['adx']} > 25 — strong bullish trend"; }
        else { $bearFactors[] = "ADX {$adxData['adx']} > 25 — strong trend, but bearish side scores 0 by design"; }
    }

    // ── MOMENTUM ───────────────────────────────────────────────
    if ($rsiVal > 55) { $momB += 2; $bullFactors[] = "RSI at {$rsiVal} — bullish momentum"; }
    elseif ($rsiVal < 45) { $momR += 2; $bearFactors[] = "RSI at {$rsiVal} — bearish momentum"; }

    if ($macdHPrev <= 0 && $macdH > 0) { $momB += 2; $bullFactors[] = 'MACD crossed above Signal line — bullish crossover'; }
    elseif ($macdHPrev >= 0 && $macdH < 0) { $momR += 2; $bearFactors[] = 'MACD crossed below Signal line — bearish crossover'; }

    // ── CONFIRMATION ───────────────────────────────────────────
    if ($price > $ema20) { $confB++; $bullFactors[] = 'Price above EMA20'; }
    elseif ($price < $ema20) { $confR++; $bearFactors[] = 'Price below EMA20'; }

    if ($price > $vwap && $vwap > 0) { $confB++; $bullFactors[] = 'Price above VWAP — intraday buyers in control'; }
    elseif ($price < $vwap && $vwap > 0) { $confR++; $bearFactors[] = 'Price below VWAP — sellers in control'; }

    if ($bbBounceBull) { $confB++; $bullFactors[] = 'Price bounced off lower Bollinger Band — support held'; }
    elseif ($bbRejectBear) { $confR++; $bearFactors[] = 'Price rejected from upper Bollinger Band — resistance held'; }

    $volInfo = volumeAnalysis($history);
    $chgPct  = $quote['regularMarketChangePercent'] ?? 0;
    if ($volInfo['ratio'] !== null && $volInfo['ratio'] >= 1.5) {
        if ($chgPct > 0) { $confB += 2; $bullFactors[] = "Volume {$volInfo['ratio']}x the 20-day average on an up day — real buying participation"; }
        elseif ($chgPct < 0) { $confR += 2; $bearFactors[] = "Volume {$volInfo['ratio']}x the 20-day average on a down day — real selling participation"; }
    }

    $swing = swingStructure($history);
    if ($swing['structure'] === 'HH-HL') { $confB += 2; $bullFactors[] = 'Higher-High / Higher-Low structure — uptrend confirmed by price action'; }
    elseif ($swing['structure'] === 'LH-LL') { $confR += 2; $bearFactors[] = 'Lower-High / Lower-Low structure — downtrend confirmed by price action'; }

    $high52 = (float)($quote['fiftyTwoWeekHigh'] ?? 0);
    $nHist  = count($history);
    $priorHigh20 = $nHist > 21 ? max(array_column(array_slice($history, -21, 20), 'high')) : null;
    $priorLow20  = $nHist > 21 ? min(array_column(array_slice($history, -21, 20), 'low'))  : null;

    if ($high52 > 0 && $price >= $high52) {
        $confB += 3; $bullFactors[] = "Price at/above the 52-week high of {$high52} — breakout";
    } elseif ($priorHigh20 !== null && $price > $priorHigh20) {
        $confB += 2; $bullFactors[] = "Price broke above the prior 20-day high of {$priorHigh20} — resistance breakout";
    }
    if ($priorLow20 !== null && $price < $priorLow20) {
        $confR += 2; $bearFactors[] = "Price broke below the prior 20-day low of {$priorLow20} — support breakdown";
    }

    // Delivery % — activates automatically once a data source populates it
    if (isset($quote['deliveryPercent']) && $quote['deliveryPercent'] !== null) {
        $dp = (float)$quote['deliveryPercent'];
        if ($dp > 60) { $confB++; $bullFactors[] = "Delivery % at {$dp}% — high delivery, genuine buying"; }
        elseif ($dp < 40) { $confR++; $bearFactors[] = "Delivery % at {$dp}% — low delivery, speculative move"; }
    }

    // OI + Price — activates automatically once a data source populates it
    if (isset($quote['oiChangePercent']) && $quote['oiChangePercent'] !== null) {
        $oi = (float)$quote['oiChangePercent'];
        if ($oi > 0 && $chgPct > 0) { $confB += 2; $bullFactors[] = 'OI up with price up — long build-up'; }
        elseif ($oi > 0 && $chgPct < 0) { $confR += 2; $bearFactors[] = 'OI up with price down — short build-up'; }
    }

    $bullish = $trendB + $momB + $confB;
    $bearish = $trendR + $momR + $confR;
    $diff    = $bullish - $bearish;

    $total = $bullish + $bearish;
    if ($total === 0) $total = 1;
    $confidence = (int) round(max($bullish, $bearish) / $total * 100);

    $trendBullish      = $trendB > $trendR;
    $trendBearish      = $trendR > $trendB;
    $momentumBullish   = $momB > $momR;
    $momentumBearish   = $momR > $momB;
    $confirmBullish    = $confB > $confR;
    $confirmBearish    = $confR > $confB;
    $allCategoriesBull = $trendBullish && $momentumBullish && $confirmBullish;
    $allCategoriesBear = $trendBearish && $momentumBearish && $confirmBearish;

    $categories = [
        'trend'        => ['bullish' => $trendB, 'bearish' => $trendR, 'agrees' => $trendBullish ? 'Bullish' : ($trendBearish ? 'Bearish' : 'Neutral')],
        'momentum'     => ['bullish' => $momB,   'bearish' => $momR,   'agrees' => $momentumBullish ? 'Bullish' : ($momentumBearish ? 'Bearish' : 'Neutral')],
        'confirmation' => ['bullish' => $confB,  'bearish' => $confR,  'agrees' => $confirmBullish ? 'Bullish' : ($confirmBearish ? 'Bearish' : 'Neutral')],
    ];

    // ── Confidence-filter decision logic ────────────────────────
    if (abs($diff) <= 3) {
        $signal = 'Hold';
        $trend  = 'Sideways';
        $verdict = "Bullish {$bullish} vs Bearish {$bearish} — too close to call (within +/-3). Bullish: " . implode(', ', $bullFactors ?: ['none']) . ". Bearish: " . implode(', ', $bearFactors ?: ['none']) . ". Wait for a cleaner setup.";
    } elseif ($bullish >= 15 && $diff >= 5) {
        if ($allCategoriesBull) {
            $signal = 'Strong Buy';
            $trend  = 'Bullish';
            $verdict = "All three categories (Trend, Momentum, Confirmation) agree bullish, and the score ({$bullish} vs {$bearish}) clears the Strong Buy bar. " . implode('. ', $bullFactors) . ".";
        } else {
            $signal = 'Buy';
            $trend  = 'Bullish';
            $verdict = "Score clears the Strong Buy bar ({$bullish} vs {$bearish}) but categories don't all agree (Trend: {$categories['trend']['agrees']}, Momentum: {$categories['momentum']['agrees']}, Confirmation: {$categories['confirmation']['agrees']}), so this is downgraded to a plain Buy. " . implode('. ', $bullFactors) . ".";
        }
    } elseif ($bullish >= 11 && $diff > 3) {
        $signal = 'Buy';
        $trend  = 'Bullish';
        $verdict = "The technical picture leans bullish ({$bullish} vs {$bearish}). " . implode('. ', $bullFactors) . ". Consider entering near current levels with a stop below EMA20.";
    } elseif ($bearish >= 15 && $diff <= -5) {
        if ($allCategoriesBear) {
            $signal = 'Strong Sell';
            $trend  = 'Bearish';
            $verdict = "All three categories (Trend, Momentum, Confirmation) agree bearish, and the score ({$bearish} vs {$bullish}) clears the Strong Sell bar. " . implode('. ', $bearFactors) . ".";
        } else {
            $signal = 'Sell';
            $trend  = 'Bearish';
            $verdict = "Score clears the Strong Sell bar ({$bearish} vs {$bullish}) but categories don't all agree (Trend: {$categories['trend']['agrees']}, Momentum: {$categories['momentum']['agrees']}, Confirmation: {$categories['confirmation']['agrees']}), so this is downgraded to a plain Sell. " . implode('. ', $bearFactors) . ".";
        }
    } elseif ($bearish >= 11 && $diff < -3) {
        $signal = 'Sell';
        $trend  = 'Bearish';
        $verdict = "Bears are in control ({$bearish} vs {$bullish}). " . implode('. ', $bearFactors) . ". Avoid fresh long positions.";
    } else {
        if ($diff > 0) {
            $signal = 'Buy'; $trend = 'Bullish';
            $verdict = "Leans bullish ({$bullish} vs {$bearish}) but conviction is low. " . implode('. ', $bullFactors ?: ['none']) . ".";
        } elseif ($diff < 0) {
            $signal = 'Sell'; $trend = 'Bearish';
            $verdict = "Leans bearish ({$bearish} vs {$bullish}) but conviction is low. " . implode('. ', $bearFactors ?: ['none']) . ".";
        } else {
            $signal = 'Hold'; $trend = 'Sideways';
            $verdict = "Bullish and bearish scores are tied at {$bullish}. Wait for a cleaner setup.";
        }
    }

    return compact('signal', 'trend', 'confidence', 'bullFactors', 'bearFactors', 'verdict', 'bullish', 'bearish', 'categories');
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
    $rsiLast = lastNonNull($indicators['rsi']) ?: 50;
    $rsiMom  = ($rsiLast - 50) / 50 * 30; // scale to ±30

    // 4. MACD histogram direction and strength
    $histArr    = $indicators['macd']['hist'];
    $histNonNull = array_values(array_filter($histArr, fn($v) => $v !== null));
    $hLast  = end($histNonNull) ?: 0;
    $hPrev  = count($histNonNull) >= 2 ? $histNonNull[count($histNonNull)-2] : 0;
    $macdMom = ($hLast > $hPrev ? 1 : -1) * min(abs($hLast) * 10, 20); // direction + strength

    // 5. EMA alignment
    $ema20L = lastNonNull($indicators['ema20']) ?: $price;
    $ema50L = lastNonNull($indicators['ema50']) ?: $price;
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
    $wRsiLast = lastNonNull($wRsi) ?: 50;
    $wEmaLast = lastNonNull($wEma20) ?: $price;
    $wMacd    = macd($wCloses);
    $wMacdH   = lastNonNull($wMacd['hist'] ?? []) ?: 0;

    // Daily signal
    $dCloses  = array_column($dailyHistory, 'close');
    $dEma20L  = lastNonNull(ema($dCloses, 20)) ?: $price;
    $dRsiArr  = rsi($dCloses);
    $dRsiLast = lastNonNull($dRsiArr) ?: 50;

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
    $rsiVal = lastNonNull($indicators['rsi']) ?: 50;
    $macdH  = lastNonNull($indicators['macd']['hist']) ?: 0;
    $ema20  = lastNonNull($indicators['ema20']) ?: $price;
    $ema50  = lastNonNull($indicators['ema50']) ?: $price;
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

// ── Extend generateSignal with supplementary (non-scoring) indicators ─
// generateSignal() already implements the full 15-indicator scorecard
// (including ADX, as part of the Trend category) and produces the final
// signal via the category-based confidence filter. This wrapper only
// attaches extra indicators (Stochastic, OBV) as supplementary context
// for display — it deliberately does NOT let them change bullish/bearish
// totals or override the signal, since they aren't part of the specified
// scorecard and mixing them in would dilute the Trend/Momentum/Confirmation
// category logic (and silently break the 15-point "Strong Buy/Sell" bar).
function generateSignalFull(array $quote, array $history, array $indicators): array
{
    $base    = generateSignal($quote, $history, $indicators);
    $adxData = adx($history);
    $stoch   = stochastic($history);
    $obvData = obv($history);

    return array_merge($base, [
        'adx'   => $adxData,
        'stoch' => $stoch,
        'obv'   => $obvData,
    ]);
}

