<?php
declare(strict_types=1);
/**
 * api/watchlist.php — watchlist, analyze, tick, leaders, and pagination
 * handlers. Depends on datasources.php, indicators.php, signals.php
 * (all loaded earlier by public/index.php before routing).
 */

// ── Custom watchlist helpers ──────────────────────────────────
// ── Custom watchlist helpers ──────────────────────────────────
function getActiveWatchlist(): array
{
    if (file_exists(WL_FILE)) {
        $custom = json_decode(file_get_contents(WL_FILE), true);
        if (!empty($custom)) return $custom;
    }
    // Default: top 5 well-known NSE stocks for fast/reliable loading
    return ['RELIANCE.NS', 'TCS.NS', 'HDFCBANK.NS', 'INFY.NS', 'ICICIBANK.NS'];
}

function apiWatchlist(): array
{
    $cacheFile = STORAGE . '/watchlist_cache.json';
    if (file_exists($cacheFile)) {
        $cached = json_decode(file_get_contents($cacheFile), true);
        if ($cached && (time() - ($cached['ts'] ?? 0)) < 300) {
            $cached['cached'] = true;
            return $cached;
        }
    }

    $symbols = getActiveWatchlist();
    $stocks  = [];

    foreach ($symbols as $sym) {
        $quote = yahooQuote($sym);
        if (!$quote) continue;

        $history = yahooHistory($sym, 60);
        if (count($history) < 20) continue;

        $closePrices = closes($history);
        $ema20   = ema($closePrices, 20);
        $ema50   = ema($closePrices, 50);
        $rsiArr  = rsi($closePrices);
        $macdArr = macd($closePrices);
        $bbArr   = bollingerBands($closePrices);
        $vwap    = vwapDaily($history);
        $st      = supertrend($history);

        $indicators = [
            'ema20' => $ema20, 'ema50' => $ema50,
            'rsi'   => $rsiArr, 'macd' => $macdArr,
            'bb'    => $bbArr,  'vwap' => $vwap, 'supertrend' => $st,
        ];

        $sig = generateSignalFull($quote, $history, $indicators);
        $mom = momentumScore($quote, $history, $indicators);

        // New indicators
        $adxData = adx($history);
        $stoch   = stochastic($history);
        $obvData = obv($history);
        $pivots  = pivotPoints($history);

        $price   = $quote['regularMarketPrice'] ?? 0;
        $rsiLast = lastNonNull($rsiArr) ?: 50;
        $ema20L  = round(lastNonNull($ema20) ?: $price, 2);
        $ema50L  = round(lastNonNull($ema50) ?: $price, 2);

        // ATR-based target/SL
        $n    = count($history);
        $last = $history[$n - 1];
        $prev = $history[$n - 2] ?? $last;
        $atr  = max($last['high'] - $last['low'], abs($last['close'] - $prev['close']));
        $target = round($price + 2.5 * $atr, 2);
        $sl     = round($price - 1.5 * $atr, 2);

        // 5-day price change for momentum tracking
        $close5ago = $n >= 5 ? $history[$n - 6]['close'] : $history[0]['close'];
        $chg5d = $close5ago > 0 ? round((($price - $close5ago) / $close5ago) * 100, 2) : 0;

        // Pattern detection
        $pats = detectPatterns($history);
        $topPat = $pats[0]['name'] ?? 'None';

        // Cache indicators for fast per-minute tick reuse
        $indFile = STORAGE . '/indicators_cache.json';
        $indCache = file_exists($indFile) ? json_decode(file_get_contents($indFile), true) : [];
        $display = toNseDisplay($sym);
        $indCache[$sym] = [
            'rsi'        => round(lastNonNull($rsiArr) ?: 50, 1),
            'ema20'      => round(lastNonNull($ema20) ?: $price, 2),
            'ema50'      => round(lastNonNull($ema50) ?: $price, 2),
            'supertrend' => $st,
            'macd_hist'  => round(lastNonNull($macdArr['hist']) ?: 0, 4),
            'updated'    => time(),
        ];
        if (!is_dir(STORAGE)) mkdir(STORAGE, 0755, true);
        file_put_contents($indFile, json_encode($indCache));

        $stocks[] = [
            'symbol'        => toNseDisplay($sym),
            'name'          => $quote['shortName'] ?? toNseDisplay($sym),
            'price'         => round($price, 2),
            'change_pct'    => round($quote['regularMarketChangePercent'] ?? 0, 2),
            'change_5d'     => $chg5d,
            'signal'        => $sig['signal'],
            'confidence'    => $sig['confidence'],
            'momentum_score'=> $mom['score'],
            'momentum_rank' => $mom['rank'],
            'direction'     => $mom['direction'],
            'vol_label'     => $mom['vol_label'],
            'vol_surge'     => $mom['vol_surge'],
            'rsi'           => round($rsiLast, 1),
            'ema20'         => $ema20L,
            'ema50'         => $ema50L,
            'supertrend'    => $st,
            'trend'         => $sig['trend'],
            'key_reason'    => $sig['bullFactors'][0] ?? $sig['bearFactors'][0] ?? 'Mixed signals',
            'pattern'       => $topPat,
            'target'        => $target,
            'stoploss'      => $sl,
            'sector'        => $quote['sector'] ?? 'N/A',
            '52w_high'      => round($quote['fiftyTwoWeekHigh'] ?? 0, 2),
            '52w_low'       => round($quote['fiftyTwoWeekLow'] ?? 0, 2),
            // New indicators
            'adx'           => $adxData['adx'],
            'adx_strength'  => $adxData['trend_strength'] ?? 'N/A',
            'adx_direction' => $adxData['direction'] ?? 'N/A',
            'stoch_k'       => $stoch['k'],
            'stoch_d'       => $stoch['d'],
            'stoch_signal'  => $stoch['signal'],
            'obv_trend'     => $obvData['trend'] ?? 'N/A',
            'pivot_pp'      => $pivots['PP'] ?? null,
            'pivot_r1'      => $pivots['R1'] ?? null,
            'pivot_s1'      => $pivots['S1'] ?? null,
        ];
        usleep(200000);
    }

    // Sort by momentum score descending
    usort($stocks, fn($a, $b) => $b['momentum_score'] <=> $a['momentum_score']);

    // Split into buy and sell lists
    $buyList  = array_values(array_filter($stocks, fn($s) => $s['momentum_score'] >= 0));
    $sellList = array_values(array_filter($stocks, fn($s) => $s['momentum_score'] < 0));
    // Sell list: weakest first (most negative at top)
    $sellList = array_reverse($sellList);

    $buys  = count(array_filter($stocks, fn($s) => in_array($s['signal'], ['Buy'])));
    $sells = count(array_filter($stocks, fn($s) => in_array($s['signal'], ['Sell'])));
    $total = count($stocks);
    $moodScore = $total > 0 ? array_sum(array_column($stocks, 'momentum_score')) / $total : 0;
    $mood = $moodScore > 10 ? 'Bullish' : ($moodScore < -10 ? 'Bearish' : 'Neutral');

    $result = [
        'stocks'           => $stocks,
        'buy_list'         => $buyList,
        'sell_list'        => $sellList,
        'market_mood'      => $mood,
        'mood_score'       => round($moodScore, 1),
        'nifty_view'       => "Buy: {$buys} | Sell: {$sells} | Hold: " . ($total - $buys - $sells) . " of {$total}",
        'updated'          => date('Y-m-d H:i'),
        'ts'               => time(),
        'cached'           => false,
        'source'           => DATA_API_KEY ? 'Twelve Data' : 'Legacy fallback sources',
        'custom_watchlist' => file_exists(WL_FILE) ? json_decode(file_get_contents(WL_FILE), true) : [],
    ];

    if (!is_dir(STORAGE)) mkdir(STORAGE, 0755, true);
    file_put_contents($cacheFile, json_encode($result));
    return $result;
}

function apiAnalyze(string $symbol): array
{
    if (!$symbol) return ['error' => 'No symbol provided'];

    // Normalize symbol — add .NS if needed for Indian stocks
    $yahooSym = str_ends_with($symbol, '.NS') ? $symbol : $symbol . '.NS';

    $quote = yahooQuote($yahooSym);
    if (!$quote) {
        // Try without .NS (for indices like ^NSEI)
        $quote = yahooQuote($symbol);
        if (!$quote) return ['error' => "Could not fetch data for {$symbol}. Check the symbol (e.g., RELIANCE, TCS, INFY)."];
        $yahooSym = $symbol;
    }

    $history = yahooHistory($yahooSym, 90);
    if (count($history) < 26) return ['error' => "Not enough history for {$symbol}. Need at least 26 trading days."];

    $closePrices = closes($history);
    $ema20  = ema($closePrices, 20);
    $ema50  = ema($closePrices, 50);
    $rsiArr = rsi($closePrices);
    $macdArr = macd($closePrices);
    $bbArr  = bollingerBands($closePrices);
    $vwap   = vwapDaily($history);
    $st     = supertrend($history);
    $pats   = detectPatterns($history);

    $indicators = ['ema20' => $ema20, 'ema50' => $ema50, 'rsi' => $rsiArr, 'macd' => $macdArr, 'bb' => $bbArr, 'vwap' => $vwap, 'supertrend' => $st];
    $sig = generateSignalFull($quote, $history, $indicators);

    // New indicators
    $adxData   = adx($history);
    $stoch     = stochastic($history);
    $obvData   = obv($history);
    $pivots    = pivotPoints($history);
    $wr        = williamsR($history, 14);
    $cciVal    = cci($history, 20);
    $mfiVal    = mfi($history, 14);
    $ichimoku  = ichimoku($history);
    $fibs      = fibonacci($history, 60);
    $volAnal   = volumeAnalysis($history);
    $mtf       = multiTimeframe($symbol, $quote['regularMarketPrice'] ?? 0, $history);

    $price   = $quote['regularMarketPrice'] ?? 0;
    $high52  = (float)($quote['fiftyTwoWeekHigh'] ?? 0);
    $low52   = (float)($quote['fiftyTwoWeekLow']  ?? 0);
    $pos52w  = position52W($price, $high52, $low52);
    $rsiLast = lastNonNull($rsiArr) ?: 50;
    $ema20L  = round(lastNonNull($ema20) ?: $price, 2);
    $ema50L  = round(lastNonNull($ema50) ?: $price, 2);
    $macdL   = lastNonNull($macdArr['macd']) ?: 0;
    $macdS   = lastNonNull($macdArr['signal']) ?: 0;
    $bbU     = lastNonNull($bbArr['upper']) ?: $price;
    $bbM     = lastNonNull($bbArr['middle']) ?: $price;
    $bbLo    = lastNonNull($bbArr['lower']) ?: $price;

    $bbPos = $price >= $bbU * 0.98 ? 'Near upper band' : ($price <= $bbLo * 1.02 ? 'Near lower band' : 'Middle of band');

    // Support / Resistance from recent history
    $recent20 = array_slice($history, -20);
    $support  = round(min(array_column($recent20, 'low')), 2);
    $resist   = round(max(array_column($recent20, 'high')), 2);

    // Trade setup using ATR
    $lastRows = array_slice($history, -14);
    $atrVals  = [];
    for ($i = 1; $i < count($lastRows); $i++) {
        $h = $lastRows[$i]['high']; $l = $lastRows[$i]['low']; $pc = $lastRows[$i - 1]['close'];
        $atrVals[] = max($h - $l, abs($h - $pc), abs($l - $pc));
    }
    $atr = $atrVals ? round(array_sum($atrVals) / count($atrVals), 2) : $price * 0.015;
    $entry   = round($price, 2);
    $target1 = round($price + 1.5 * $atr, 2);
    $target2 = round($price + 3.0 * $atr, 2);
    $sl      = round($price - $atr, 2);
    $rr      = $atr > 0 ? '1:' . round(1.5 * $atr / $atr, 1) : '1:1.5';

    $pe   = $quote['trailingPE'] ?? null;
    $pb   = $quote['priceToBook'] ?? null;
    $mcap = $quote['marketCap'] ?? 0;
    $roe  = isset($quote['returnOnEquity']) ? round($quote['returnOnEquity'] * 100, 1) : null;
    $de   = $quote['debtToEquity'] ?? null;

    $mcapLabel = $mcap > 1e12 ? 'Large Cap' : ($mcap > 2e11 ? 'Mid Cap' : 'Small Cap');

    $summary = sprintf(
        '%s (NSE: %s) is trading at ₹%.2f (%+.2f%%). Technical outlook: %s with %d%% confidence. %s',
        $quote['shortName'] ?? $symbol,
        $symbol,
        $price,
        $quote['regularMarketChangePercent'] ?? 0,
        $sig['trend'],
        $sig['confidence'],
        $sig['signal'] === 'Buy' ? 'Positive momentum with bullish indicators.' : ($sig['signal'] === 'Sell' ? 'Bearish pressure is dominant.' : 'Mixed signals — wait for confirmation.')
    );

    return [
        'symbol'    => $symbol,
        'name'      => $quote['shortName'] ?? $quote['longName'] ?? $symbol,
        'sector'    => $quote['sector'] ?? 'N/A',
        'industry'  => $quote['industry'] ?? 'N/A',
        'price'     => round($price, 2),
        'change_pct'=> round($quote['regularMarketChangePercent'] ?? 0, 2),
        '52w_high'  => round($quote['fiftyTwoWeekHigh'] ?? 0, 2),
        '52w_low'   => round($quote['fiftyTwoWeekLow'] ?? 0, 2),
        'signal'    => $sig['signal'],
        'confidence'=> $sig['confidence'],
        'summary'   => $summary,
        'technicals'=> [
            'trend'        => $sig['trend'],
            'ema_20'       => $ema20L,
            'ema_50'       => $ema50L,
            'ema_signal'   => $ema20L > $ema50L ? 'Golden Cross (EMA20 > EMA50)' : 'Death Cross (EMA20 < EMA50)',
            'rsi'          => round($rsiLast, 1),
            'rsi_signal'   => $rsiLast > 70 ? 'Overbought' : ($rsiLast < 30 ? 'Oversold' : 'Neutral'),
            'macd'         => $macdL > $macdS ? 'Bullish' : 'Bearish',
            'macd_note'    => sprintf('MACD: %.2f | Signal: %.2f | Hist: %.2f', $macdL, $macdS, $macdL - $macdS),
            'bollinger'    => $bbPos,
            'bollinger_note' => sprintf('Upper: ₹%.2f | Mid: ₹%.2f | Lower: ₹%.2f', $bbU, $bbM, $bbLo),
            'volume'       => ($quote['regularMarketVolume'] ?? 0) > ($quote['averageVolume'] ?? 1) * 1.3 ? 'High' : 'Normal',
            'volume_note'  => sprintf('Vol: %s | Avg: %s', number_format($quote['regularMarketVolume'] ?? 0), number_format($quote['averageVolume'] ?? 0)),
            'supertrend'   => $st,
            'support'      => $support,
            'resistance'   => $resist,
            'vwap'         => $vwap,
            'vwap_signal'  => $price > $vwap ? 'Above VWAP' : 'Below VWAP',
            // New
            'adx'          => $adxData['adx'],
            'adx_strength' => $adxData['trend_strength'] ?? 'N/A',
            'adx_direction'=> $adxData['direction'] ?? 'N/A',
            'plus_di'      => $adxData['plus_di'] ?? null,
            'minus_di'     => $adxData['minus_di'] ?? null,
            'stoch_k'      => $stoch['k'],
            'stoch_d'      => $stoch['d'],
            'stoch_signal' => $stoch['signal'],
            'obv_trend'    => $obvData['trend'] ?? 'N/A',
            // New
            'williams_r'   => $wr,
            'williams_signal' => $wr!==null ? ($wr<-80?'Oversold':($wr>-20?'Overbought':'Neutral')) : 'N/A',
            'cci'          => $cciVal,
            'cci_signal'   => $cciVal!==null ? ($cciVal<-100?'Oversold':($cciVal>100?'Overbought':'Neutral')) : 'N/A',
            'mfi'          => $mfiVal,
            'mfi_signal'   => $mfiVal!==null ? ($mfiVal<20?'Oversold':($mfiVal>80?'Overbought':'Neutral')) : 'N/A',
        ],
        'ichimoku'      => $ichimoku,
        'fibonacci'     => $fibs,
        'volume_analysis'=> $volAnal,
        'multi_timeframe'=> $mtf,
        'position_52w'  => $pos52w,
        'score_breakdown'=> scoreBreakdown($quote, $history,
            ['rsi'=>$rsiArr,'macd'=>$macdArr,'ema20'=>$ema20,'ema50'=>$ema50,'supertrend'=>$st,'vwap'=>$vwap],
            $adxData, $stoch, $obvData, $wr, $cciVal, $mfiVal, $ichimoku),
        'pivot_points' => $pivots,
        'patterns'  => $pats,
        'fundamentals' => [
            'pe_ratio'    => $pe ? round($pe, 1) : null,
            'pb_ratio'    => $pb ? round($pb, 1) : null,
            'market_cap'  => $mcapLabel,
            'market_cap_cr'=> $mcap > 0 ? '₹' . number_format(round($mcap / 1e7), 0) . ' Cr' : 'N/A',
            'debt_equity' => $de ? round($de, 2) : null,
            'roe'         => $roe,
            'note'        => $pe ? sprintf('P/E %.1fx vs sector; P/B %.1fx; ROE %s%%', $pe, $pb ?? 0, $roe ?? 'N/A') : 'Fundamental data limited for this symbol.',
        ],
        'buy_sell_reasoning' => [
            'bullish_factors' => $sig['bullFactors'],
            'bearish_factors' => $sig['bearFactors'],
            'verdict'         => $sig['verdict'],
        ],
        'trade_setup' => [
            'entry'          => $entry,
            'target_1'       => $target1,
            'target_2'       => $target2,
            'stoploss'       => $sl,
            'risk_reward'    => $rr,
            'holding_period' => 'Intraday / 1-3 days',
        ],
        'news_catalyst' => 'Check Moneycontrol, Economic Times, or NSE announcements for latest news on this stock.',
        'risk_warning'  => '⚠ This is algorithmic analysis based on price data only. Past performance does not guarantee future results. Always use stop-losses and position size responsibly. Not SEBI-registered advice.',
        'data_source'   => DATA_API_KEY ? 'Twelve Data' : 'Legacy fallback sources',
    ];
}

function apiTick(): array
{
    $logFile  = STORAGE . '/signals_' . date('Y-m-d') . '.json';
    $indFile  = STORAGE . '/indicators_cache.json';

    // Load cached indicators (rebuilt every 10 min by apiWatchlist)
    $indCache = file_exists($indFile) ? json_decode(file_get_contents($indFile), true) : [];

    // Load today's signal log
    $log = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];

    $now     = time();
    $minute  = date('H:i');
    $results = [];

    // Batch fetch all symbols using the multi-source bulk fetcher (NSE→Stooq→Yahoo)
    $activeSyms = getActiveWatchlist();
    $quotes = yahooQuoteBulk($activeSyms);

    foreach ($activeSyms as $sym) {
        $quote = $quotes[$sym] ?? null;
        if (!$quote) continue;

        $price   = $quote['regularMarketPrice'] ?? 0;
        $chgPct  = $quote['regularMarketChangePercent'] ?? 0;
        $avgVol  = $quote['averageVolume'] ?? 1;
        $curVol  = $quote['regularMarketVolume'] ?? 0;
        $volR    = $avgVol > 0 ? $curVol / $avgVol : 1;

        // Use cached indicators if available, else quick signal from price action only
        $cached = $indCache[$sym] ?? null;

        if ($cached) {
            $rsi  = $cached['rsi'] ?? 50;
            $ema20 = $cached['ema20'] ?? $price;
            $ema50 = $cached['ema50'] ?? $price;
            $st   = $cached['supertrend'] ?? 'Bullish';
            $macdH = $cached['macd_hist'] ?? 0;
        } else {
            $rsi = 50; $ema20 = $price; $ema50 = $price; $st = 'Bullish'; $macdH = 0;
        }

        // Quick signal logic
        $bull = 0; $bear = 0;
        if ($price > $ema20 && $ema20 > $ema50) $bull += 3;
        elseif ($price < $ema20 && $ema20 < $ema50) $bear += 3;
        if ($rsi < 35) $bull += 2;
        elseif ($rsi > 65) $bear += 2;
        elseif ($rsi >= 50) $bull++;
        else $bear++;
        if ($macdH > 0) $bull += 2; else $bear += 2;
        if ($st === 'Bullish') $bull += 2; else $bear += 2;
        if ($chgPct > 0.5) $bull++; elseif ($chgPct < -0.5) $bear++;
        if ($volR > 1.5 && $chgPct > 0) $bull++; elseif ($volR > 1.5 && $chgPct < 0) $bear++;

        $signal = $bull > $bear + 1 ? 'Buy' : ($bear > $bull + 1 ? 'Sell' : 'Hold');
        $score  = round((($bull - $bear) / ($bull + $bear + 1)) * 100, 1);

        // Record into log
        $display = toNseDisplay($sym);
        if (!isset($log[$display])) {
            $log[$display] = ['name' => $quote['shortName'] ?? $display, 'ticks' => []];
        }
        $log[$display]['ticks'][] = [
            'ts'     => $now,
            'min'    => $minute,
            'price'  => round($price, 2),
            'chg'    => round($chgPct, 2),
            'signal' => $signal,
            'score'  => $score,
            'vol_r'  => round($volR, 2),
        ];
        // Keep only last 500 ticks per symbol per day
        if (count($log[$display]['ticks']) > 500) {
            $log[$display]['ticks'] = array_slice($log[$display]['ticks'], -500);
        }

        $results[$display] = [
            'price'   => round($price, 2),
            'chg'     => round($chgPct, 2),
            'signal'  => $signal,
            'score'   => $score,
            'vol_r'   => round($volR, 2),
        ];
    }

    if (!is_dir(STORAGE)) mkdir(STORAGE, 0755, true);
    file_put_contents($logFile, json_encode($log));

    return ['tick' => $minute, 'ts' => $now, 'data' => $results];
}

function apiLeaders(): array
{
    $logFile = STORAGE . '/signals_' . date('Y-m-d') . '.json';
    if (!file_exists($logFile)) return ['error' => 'No signal data yet. Wait for first tick.'];

    $log  = json_decode(file_get_contents($logFile), true);
    $now  = time();
    $hour = $now - 3600;

    $todayBuy = $todaySell = $hourBuy = $hourSell = [];

    foreach ($log as $sym => $data) {
        $ticks     = $data['ticks'] ?? [];
        $name      = $data['name'] ?? $sym;
        $todayTicks = $ticks; // all ticks today
        $hourTicks  = array_values(array_filter($ticks, fn($t) => $t['ts'] >= $hour));

        if (!$todayTicks) continue;

        // Today counts
        $tBuys  = count(array_filter($todayTicks, fn($t) => $t['signal'] === 'Buy'));
        $tSells = count(array_filter($todayTicks, fn($t) => $t['signal'] === 'Sell'));
        $tTotal = count($todayTicks);
        $tAvgScore = round(array_sum(array_column($todayTicks, 'score')) / $tTotal, 1);
        $tLastPrice = end($todayTicks)['price'];
        $tFirstPrice = $todayTicks[0]['price'];
        $tPriceChg = $tFirstPrice > 0 ? round((($tLastPrice - $tFirstPrice) / $tFirstPrice) * 100, 2) : 0;
        // Streak: consecutive same signals at end
        $tStreak = 0; $tStreakSig = end($todayTicks)['signal'];
        for ($i = count($todayTicks)-1; $i >= 0; $i--) {
            if ($todayTicks[$i]['signal'] === $tStreakSig) $tStreak++;
            else break;
        }

        $entry = [
            'symbol'    => $sym,
            'name'      => $name,
            'price'     => $tLastPrice,
            'price_chg' => $tPriceChg,
            'ticks'     => $tTotal,
            'avg_score' => $tAvgScore,
            'streak'    => $tStreak,
            'streak_sig'=> $tStreakSig,
            'last_chg'  => end($todayTicks)['chg'],
        ];

        if ($tBuys > $tSells) {
            $entry['buy_count']  = $tBuys;
            $entry['sell_count'] = $tSells;
            $entry['dominance']  = round($tBuys / $tTotal * 100);
            $todayBuy[] = $entry;
        } elseif ($tSells > $tBuys) {
            $entry['buy_count']  = $tBuys;
            $entry['sell_count'] = $tSells;
            $entry['dominance']  = round($tSells / $tTotal * 100);
            $todaySell[] = $entry;
        }

        // Hour counts
        if ($hourTicks) {
            $hBuys  = count(array_filter($hourTicks, fn($t) => $t['signal'] === 'Buy'));
            $hSells = count(array_filter($hourTicks, fn($t) => $t['signal'] === 'Sell'));
            $hTotal = count($hourTicks);
            $hAvgScore = round(array_sum(array_column($hourTicks, 'score')) / $hTotal, 1);
            $hStreak = 0; $hStreakSig = end($hourTicks)['signal'];
            for ($i = count($hourTicks)-1; $i >= 0; $i--) {
                if ($hourTicks[$i]['signal'] === $hStreakSig) $hStreak++;
                else break;
            }
            $hEntry = array_merge($entry, [
                'buy_count'  => $hBuys,
                'sell_count' => $hSells,
                'avg_score'  => $hAvgScore,
                'ticks'      => $hTotal,
                'dominance'  => $hTotal > 0 ? round(max($hBuys,$hSells)/$hTotal*100) : 0,
                'streak'     => $hStreak,
                'streak_sig' => $hStreakSig,
            ]);
            if ($hBuys > $hSells)       $hourBuy[]  = $hEntry;
            elseif ($hSells > $hBuys)   $hourSell[] = $hEntry;
        }
    }

    // Sort: primary = count of dominant signal, secondary = streak, tertiary = avg_score
    $sorter = function($a, $b) use ($log) {
        $aCnt = max($a['buy_count'], $a['sell_count']);
        $bCnt = max($b['buy_count'], $b['sell_count']);
        if ($aCnt !== $bCnt) return $bCnt - $aCnt;
        if ($a['streak'] !== $b['streak']) return $b['streak'] - $a['streak'];
        return $b['avg_score'] <=> $a['avg_score'];
    };
    usort($todayBuy,  $sorter);
    usort($todaySell, $sorter);
    usort($hourBuy,   $sorter);
    usort($hourSell,  $sorter);

    // Total ticks tracked today
    $totalTicks = array_sum(array_map(fn($d) => count($d['ticks']), $log));

    return [
        'today_buy'   => array_slice($todayBuy,  0, 5),
        'today_sell'  => array_slice($todaySell, 0, 5),
        'hour_buy'    => array_slice($hourBuy,   0, 5),
        'hour_sell'   => array_slice($hourSell,  0, 5),
        'total_ticks' => $totalTicks,
        'date'        => date('Y-m-d'),
        'generated'   => date('H:i:s'),
    ];
}

function apiWatchlistPage(int $page = 1, string $sector = '', string $search = ''): array
{
    $perPage  = 20;
    $allSyms  = getActiveWatchlist();

    // ── Step 1: check if browser already pushed quotes via /api/proxy/quotes ──
    // This is the primary path when server-side sources (Stooq/Yahoo/NSE) are IP-blocked.
    $bulkCache = STORAGE . '/bulk_quotes.json';
    $browserQuotes = [];
    if (file_exists($bulkCache) && (time() - filemtime($bulkCache)) < 300) {
        $cached = json_decode(file_get_contents($bulkCache), true) ?? [];
        if (count($cached) >= 3) {
            $browserQuotes = $cached;
        }
    }

    // ── Step 2: if no valid browser cache, try server-side fetch ──
    $allQuotes = !empty($browserQuotes) ? $browserQuotes : yahooQuoteBulk($allSyms);

    // ── Step 2: filter by sector ────────────────────────────────
    if ($sector && isset(SECTOR_MAP[$sector])) {
        $sectorSyms = array_map(fn($s) => $s . '.NS', SECTOR_MAP[$sector]);
        $allSyms    = array_values(array_filter($allSyms, fn($s) => in_array($s, $sectorSyms)));
    }

    // ── Step 3: filter by search ────────────────────────────────
    if ($search) {
        $allSyms = array_values(array_filter($allSyms, function($s) use ($search, $allQuotes) {
            if (str_contains(strtoupper($s), $search)) return true;
            $name = strtoupper($allQuotes[$s]['shortName'] ?? '');
            return str_contains($name, $search);
        }));
    }

    $totalSyms  = count($allSyms);
    $totalPages = (int)ceil($totalSyms / $perPage);
    $page       = min($page, max(1, $totalPages));
    $pageSyms   = array_slice($allSyms, ($page - 1) * $perPage, $perPage);

    if (empty($pageSyms)) {
        return ['stocks'=>[], 'page'=>$page, 'total_pages'=>0, 'total_stocks'=>0,
                'per_page'=>$perPage, 'sector'=>$sector, 'search'=>$search, 'error'=>'No stocks found'];
    }

    // ── Step 4: parallel history fetch for page stocks only ─────
    $mh = curl_multi_init();
    $hHandles = [];
    foreach ($pageSyms as $sym) {
        $cacheFile = STORAGE . '/hist_' . preg_replace('/[^A-Z0-9]/', '_', strtoupper($sym)) . '.json';
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 21600) continue; // already cached
        $period2 = time();
        $period1 = $period2 - (90 * 86400);
        $url = 'https://query2.finance.yahoo.com/v8/finance/chart/' . urlencode($sym)
             . '?period1=' . $period1 . '&period2=' . $period2 . '&interval=1d';
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 20,
            CURLOPT_CONNECTTIMEOUT => 8,    CURLOPT_ENCODING => 'gzip',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Accept: application/json', 'Referer: https://finance.yahoo.com/',
            ],
        ]);
        curl_multi_add_handle($mh, $ch);
        $hHandles[$sym] = $ch;
    }
    if (!empty($hHandles)) {
        $active = null;
        do { curl_multi_exec($mh, $active); curl_multi_select($mh, 0.3); } while ($active);
        foreach ($hHandles as $sym => $ch) {
            $raw = curl_multi_getcontent($ch);
            curl_multi_remove_handle($mh, $ch); curl_close($ch);
            if (!$raw) continue;
            $data  = json_decode($raw, true);
            $chart = $data['chart']['result'][0] ?? null;
            if (!$chart) continue;
            $ts   = $chart['timestamp'] ?? [];
            $ohlcv= $chart['indicators']['quote'][0] ?? [];
            $rows = [];
            foreach ($ts as $i => $t) {
                $c = $ohlcv['close'][$i] ?? null;
                if ($c === null) continue;
                $rows[] = ['date'=>date('Y-m-d',$t),'open'=>round($ohlcv['open'][$i]??$c,2),
                           'high'=>round($ohlcv['high'][$i]??$c,2),'low'=>round($ohlcv['low'][$i]??$c,2),
                           'close'=>round($c,2),'volume'=>$ohlcv['volume'][$i]??0];
            }
            if (!empty($rows)) {
                $cf = STORAGE . '/hist_' . preg_replace('/[^A-Z0-9]/','_',strtoupper($sym)) . '.json';
                file_put_contents($cf, json_encode($rows));
            }
        }
    }
    curl_multi_close($mh);

    // ── Step 5: analyse page stocks ─────────────────────────────
    $stocks = [];
    $skippedNoQuote = 0;
    foreach ($pageSyms as $sym) {
        $quote = $allQuotes[$sym] ?? null;
        if (!$quote) { $skippedNoQuote++; continue; }
        try {
            $history = yahooHistory($sym, 90);
            // Use whatever history we have; skip only if truly empty
            if (count($history) < 5) {
                // Still show the stock with price data, minimal indicators
                $price = (float)($quote['regularMarketPrice'] ?? 0);
                $chg   = (float)($quote['regularMarketChangePercent'] ?? 0);
                $stocks[] = [
                    'symbol'        => $sym,
                    'name'          => $quote['shortName'] ?? $sym,
                    'price'         => $price,
                    'change_pct'    => round($chg, 2),
                    'change_5d'     => 0,
                    'momentum_score'=> 0,
                    'signal'        => 'Hold',
                    'confidence'    => 0,
                    'trend'         => 'N/A',
                    'direction'     => 'flat',
                    'rsi'           => 50,
                    'supertrend'    => 'N/A',
                    'ema_signal'    => null,
                    'macd_signal'   => 'N/A',
                    'adx'           => null,
                    'adx_strength'  => null,
                    'adx_direction' => null,
                    'stoch_k'       => 50,
                    'stoch_d'       => 50,
                    'stoch_signal'  => 'N/A',
                    'obv_trend'     => null,
                    'vol_ratio'     => 1,
                    'vol_label'     => 'N/A',
                    'vol_surge'     => false,
                    'pattern'       => '',
                    'target'        => round($price * 1.03, 2),
                    'stoploss'      => round($price * 0.97, 2),
                    'position_52w'  => null,
                    'sector'        => $quote['sector'] ?? null,
                    '52w_high'      => round($quote['fiftyTwoWeekHigh'] ?? 0, 2),
                    '52w_low'       => round($quote['fiftyTwoWeekLow'] ?? 0, 2),
                    'bull_factors'  => [],
                    'bear_factors'  => [],
                ];
                continue;
            }

            $closePrices = array_column($history, 'close');
            $ema20       = ema($closePrices, 20);
            $ema50       = ema($closePrices, 50);
            $rsiArr      = rsi($closePrices);
            $macdArr     = macd($closePrices);
            $bbArr       = bollingerBands($closePrices);
            $stSuper     = supertrend($history);
            $vwap        = vwap($history);
            $atrVal      = atr($history);
            $adxData     = adx($history);
            $stoch       = stochastic($history);
            $obvData     = obv($history);
            $candlePats  = candlestickPatterns($history);

            $indicators = [
                'ema20'=>$ema20,'ema50'=>$ema50,'rsi'=>$rsiArr,
                'macd'=>$macdArr,'bb'=>$bbArr,'supertrend'=>$stSuper,
                'vwap'=>$vwap,'atr'=>$atrVal,
            ];
            $sig  = generateSignalFull($quote, $history, $indicators);
            $mom  = momentumScore($quote, $history, $indicators);
            $volA = volumeAnalysis($history);

            $price    = (float)($quote['regularMarketPrice'] ?? 0);
            $chg      = (float)($quote['regularMarketChangePercent'] ?? 0);
            $chg5d    = change5d($history);
            $rsiLast  = lastNonNull($rsiArr) ?: 50;
            $topPat   = !empty($candlePats) ? $candlePats[0]['name'] : '';
            $atrV     = $atrVal ?? $price * 0.015;
            $target   = $sig['signal'] === 'Buy'  ? round($price + $atrV * 2, 2) : round($price - $atrV * 2, 2);
            $sl       = $sig['signal'] === 'Buy'  ? round($price - $atrV,     2) : round($price + $atrV,     2);
            $pos52w   = position52W($price, (float)($quote['fiftyTwoWeekHigh']??0), (float)($quote['fiftyTwoWeekLow']??0));

            $stocks[] = [
                'symbol'        => $sym,
                'name'          => $quote['shortName'] ?? $sym,
                'price'         => $price,
                'change_pct'    => round($chg, 2),
                'change_5d'     => $chg5d,
                'momentum_score'=> $mom['score'],
                'signal'        => $sig['signal'],
                'confidence'    => $sig['confidence'],
                'trend'         => $sig['trend'],
                'direction'     => $mom['score'] >= 15 ? 'rising' : ($mom['score'] <= -15 ? 'falling' : 'flat'),
                'rsi'           => round($rsiLast, 1),
                'supertrend'    => $stSuper,
                'ema_signal'    => ($ema20 && $ema50) ? (lastNonNull($ema20) > lastNonNull($ema50) ? 'Golden' : 'Death') : null,
                'macd_signal'   => lastNonNull($macdArr['hist'] ?? []) > 0 ? 'Bullish' : 'Bearish',
                'adx'           => $adxData['adx'],
                'adx_strength'  => $adxData['trend_strength'] ?? null,
                'adx_direction' => $adxData['direction'] ?? null,
                'stoch_k'       => $stoch['k'],
                'stoch_d'       => $stoch['d'],
                'stoch_signal'  => $stoch['signal'],
                'obv_trend'     => $obvData['trend'] ?? null,
                'vol_ratio'     => $volA['ratio'],
                'vol_label'     => $volA['label'],
                'vol_surge'     => $volA['spike'],
                'pattern'       => $topPat,
                'target'        => $target,
                'stoploss'      => $sl,
                'position_52w'  => $pos52w,
                'sector'        => $quote['sector'] ?? null,
                '52w_high'      => round($quote['fiftyTwoWeekHigh'] ?? 0, 2),
                '52w_low'       => round($quote['fiftyTwoWeekLow'] ?? 0, 2),
                'bull_factors'  => $sig['bullFactors'] ?? [],
                'bear_factors'  => $sig['bearFactors'] ?? [],
            ];
        } catch (\Throwable $e) {
            continue;
        }
    }

    // Sort: Buy first by momentum, then Sells
    usort($stocks, fn($a,$b) => ($b['signal']==='Buy'?1:0) - ($a['signal']==='Buy'?1:0) ?: $b['momentum_score'] <=> $a['momentum_score']);

    $buys  = array_values(array_filter($stocks, fn($s) => $s['signal'] === 'Buy'));
    $sells = array_values(array_filter($stocks, fn($s) => $s['signal'] !== 'Buy'));
    $mood  = count($buys) / max(1, count($stocks)) >= 0.6 ? 'Bullish'
           : (count($buys) / max(1, count($stocks)) <= 0.4 ? 'Bearish' : 'Mixed');

    return [
        'stocks'          => $stocks,
        'buy_list'        => $buys,
        'sell_list'       => $sells,
        'market_mood'     => $mood,
        'page'            => $page,
        'total_pages'     => $totalPages,
        'total_stocks'    => $totalSyms,
        'per_page'        => $perPage,
        'sector'          => $sector,
        'search'          => $search,
        'ts'              => time(),
        'quotes_fetched'  => count($allQuotes),
        'skipped_no_quote'=> $skippedNoQuote ?? 0,
        'warning'         => empty($allQuotes) ? ('Could not fetch live quotes. Sources tried: Stooq, NSE India, Groww, BSE. Market may be closed or sources temporarily unavailable. Try refreshing in a few minutes.') : (count($allQuotes) < 10 ? 'Partial data: only ' . count($allQuotes) . ' quotes fetched. Some stocks may be missing.' : null),
    ];
}

