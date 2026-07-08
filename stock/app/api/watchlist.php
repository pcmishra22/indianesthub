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
    $username = getCurrentUser();
    if ($username) {
        $custom = getUserWatchlist($username);
        if (!empty($custom)) return $custom;
    }
    // Default: the full curated NSE universe (config.php WATCHLIST_SYMBOLS, ~230 stocks).
    // Was previously hardcoded to 5 well-known names — too narrow for anything that needs
    // real breadth (e.g. Top-10 gainers/losers, sector-momentum picks).
    return WATCHLIST_SYMBOLS;
}

function apiWatchlist(): array
{
    $cacheFile = getUserWatchlistCachePath(getCurrentUser());
    if (file_exists($cacheFile)) {
        $cached = json_decode(file_get_contents($cacheFile), true);
        if ($cached && (time() - ($cached['ts'] ?? 0)) < 300) {
            $cached['cached'] = true;
            return $cached;
        }
    }

    $username = getCurrentUser();
    $symbols = $username ? getUserWatchlist($username) : getActiveWatchlist();
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

    // Split into buy and sell lists.
    // Bug: this used to bucket purely on momentum_score (>=0 → Buy Candidates,
    // <0 → Sell/Avoid) — a completely separate number from the 'signal' field
    // shown in each row. That let a stock read "Signal: Hold, Supertrend:
    // Bullish" while still being dropped into the "SELL / Avoid" section,
    // because its momentum_score happened to be negative. Buckets now follow
    // the same signal (and therefore the same Supertrend override) the row
    // itself displays, so the section a stock lands in can never contradict
    // its own Signal/Supertrend columns. Hold stocks appear in neither list.
    $buyList  = array_values(array_filter($stocks, fn($s) => in_array($s['signal'], ['Buy', 'Strong Buy'], true)));
    $sellList = array_values(array_filter($stocks, fn($s) => in_array($s['signal'], ['Sell', 'Strong Sell'], true)));
    // Buy list: strongest momentum first; sell list: weakest first
    usort($buyList,  fn($a, $b) => $b['momentum_score'] <=> $a['momentum_score']);
    usort($sellList, fn($a, $b) => $a['momentum_score'] <=> $b['momentum_score']);

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
        'custom_watchlist' => getCurrentUser() ? getUserWatchlist(getCurrentUser()) : [],
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
    if (!prakashIsMarketHours()) {
        return ['tick' => date('H:i'), 'ts' => time(), 'data' => [], 'market_open' => false];
    }

    $logFile  = STORAGE . '/signals_' . date('Y-m-d') . '.json';
    $indFile  = STORAGE . '/indicators_cache.json';

    // Load cached indicators (rebuilt every 10 min by apiWatchlist)
    $indCache = file_exists($indFile) ? json_decode(file_get_contents($indFile), true) : [];

    // Load today's signal log
    $log = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];

    $now     = time();
    $minute  = date('H:i');
    $results = [];

    // Batch fetch all symbols using the multi-source bulk fetcher (BSE→Stooq→NSE→Groww).
    // forceRefresh=true: this is a manual/per-minute "get current data now" action, so it
    // must not silently serve the same up-to-5-min-old bulk_quotes.json cache that
    // Watchlist/Leaders share — that was making repeated "Tick Now" clicks record identical
    // duplicate ticks instead of fresh ones.
    $activeSyms = getActiveWatchlist();
    $quotes = yahooQuoteBulk($activeSyms, true);

    foreach ($activeSyms as $sym) {
        $quote = $quotes[$sym] ?? null;
        if (!$quote) continue;

        $price   = $quote['regularMarketPrice'] ?? 0;
        $chgPct  = $quote['regularMarketChangePercent'] ?? 0;
        // Bug: this quote array never has an 'averageVolume' key — every provider in
        // datasources.php populates 'averageDailyVolume3Month' instead. That meant
        // $avgVol always fell back to 1, so $volR = $curVol / 1 (hundreds of thousands)
        // was >1.5 on almost every tick, silently reinforcing whichever side the day's
        // move already favored.
        $avgVol  = $quote['averageDailyVolume3Month'] ?? 0;
        $curVol  = $quote['regularMarketVolume'] ?? 0;
        $volR    = $avgVol > 0 ? $curVol / $avgVol : 1;

        // Use cached indicators if available; otherwise compute a real (not guessed)
        // quick read from the already-cached price history (hist_*.json, 6-hour TTL —
        // no network call needed) instead of assuming either direction.
        //
        // Note: an earlier version of this fallback just set everything to a neutral
        // 0/null when indicators_cache.json was stale, which turned out to be too
        // weak on its own — chgPct/volR alone almost never cross the "$bull > $bear+1"
        // bar, so every symbol without a fresh cache entry landed on Hold (visible as
        // an all-Hold Leaders page). Computing the real indicators here instead of
        // punting fixes that without reintroducing the old fake-bullish-default bug.
        $cached = $indCache[$sym] ?? null;

        if ($cached) {
            $rsi   = $cached['rsi'] ?? 50;
            $ema20 = $cached['ema20'] ?? $price;
            $ema50 = $cached['ema50'] ?? $price;
            $st    = $cached['supertrend'] ?? null;
            $macdH = $cached['macd_hist'] ?? 0;
        } else {
            $hist = yahooHistory($sym, 60);
            if (count($hist) >= 20) {
                $closesArr = closes($hist);
                $rsi   = lastNonNull(rsi($closesArr));
                $ema20 = lastNonNull(ema($closesArr, 20)) ?: $price;
                $ema50 = lastNonNull(ema($closesArr, 50)) ?: $price;
                $macdH = lastNonNull(macd($closesArr)['hist'] ?? []);
                $st    = supertrend($hist);
            } else {
                // Genuinely no data available anywhere yet (brand-new symbol) —
                // stay neutral rather than guess.
                $rsi = null; $ema20 = $price; $ema50 = $price; $st = null; $macdH = null;
            }
        }

        // Quick signal logic
        $bull = 0; $bear = 0;
        if ($price > $ema20 && $ema20 > $ema50) $bull += 3;
        elseif ($price < $ema20 && $ema20 < $ema50) $bear += 3;
        if ($rsi !== null) {
            if ($rsi < 35) $bull += 2;
            elseif ($rsi > 65) $bear += 2;
            elseif ($rsi >= 50) $bull++;
            else $bear++;
        }
        if ($macdH !== null) {
            if ($macdH > 0) $bull += 2; elseif ($macdH < 0) $bear += 2;
        }
        if ($st === 'Bullish') $bull += 2; elseif ($st === 'Bearish') $bear += 2;
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
    $logFile   = STORAGE . '/signals_' . date('Y-m-d') . '.json';
    $wlCache   = getUserWatchlistCachePath(getCurrentUser());
    $now       = time();
    $hour      = $now - 3600;

    $todayBuy = $todaySell = $hourBuy = $hourSell = [];

    // If we have tick history, use it for leaders
    if (file_exists($logFile)) {
        $log  = json_decode(file_get_contents($logFile), true) ?? [];

        foreach ($log as $sym => $data) {
            $ticks      = $data['ticks'] ?? [];
            $name       = $data['name'] ?? $sym;
            $todayTicks = $ticks;
            $hourTicks  = array_values(array_filter($ticks, fn($t) => $t['ts'] >= $hour));

            if (!$todayTicks) continue;

            $tBuys  = count(array_filter($todayTicks, fn($t) => $t['signal'] === 'Buy'));
            $tSells = count(array_filter($todayTicks, fn($t) => $t['signal'] === 'Sell'));
            $tTotal = count($todayTicks);
            $tAvgScore   = round(array_sum(array_column($todayTicks, 'score')) / $tTotal, 1);
            $tLastPrice  = end($todayTicks)['price'];
            $tFirstPrice = $todayTicks[0]['price'];
            $tPriceChg   = $tFirstPrice > 0 ? round((($tLastPrice - $tFirstPrice) / $tFirstPrice) * 100, 2) : 0;
            $tStreakSig   = end($todayTicks)['signal'];
            $tStreak      = 0;
            for ($i = count($todayTicks)-1; $i >= 0; $i--) {
                if ($todayTicks[$i]['signal'] === $tStreakSig) $tStreak++; else break;
            }

            $entry = [
                'symbol' => $sym, 'name' => $name, 'price' => $tLastPrice,
                'price_chg' => $tPriceChg, 'ticks' => $tTotal, 'avg_score' => $tAvgScore,
                'streak' => $tStreak, 'streak_sig' => $tStreakSig,
                'last_chg' => end($todayTicks)['chg'],
                'buy_count' => $tBuys, 'sell_count' => $tSells,
                'dominance' => $tTotal > 0 ? round(max($tBuys,$tSells)/$tTotal*100) : 0,
            ];

            if ($tBuys > $tSells) $todayBuy[] = $entry;
            elseif ($tSells > $tBuys) $todaySell[] = $entry;

            if ($hourTicks) {
                $hBuys  = count(array_filter($hourTicks, fn($t) => $t['signal'] === 'Buy'));
                $hSells = count(array_filter($hourTicks, fn($t) => $t['signal'] === 'Sell'));
                $hTotal = count($hourTicks);
                $hAvgScore  = round(array_sum(array_column($hourTicks, 'score')) / $hTotal, 1);
                $hStreakSig = end($hourTicks)['signal'];
                $hStreak = 0;
                for ($i = count($hourTicks)-1; $i >= 0; $i--) {
                    if ($hourTicks[$i]['signal'] === $hStreakSig) $hStreak++; else break;
                }
                $hEntry = array_merge($entry, [
                    'buy_count' => $hBuys, 'sell_count' => $hSells,
                    'avg_score' => $hAvgScore, 'ticks' => $hTotal,
                    'dominance' => $hTotal > 0 ? round(max($hBuys,$hSells)/$hTotal*100) : 0,
                    'streak' => $hStreak, 'streak_sig' => $hStreakSig,
                ]);
                if ($hBuys > $hSells)     $hourBuy[]  = $hEntry;
                elseif ($hSells > $hBuys) $hourSell[] = $hEntry;
            }
        }
    }

    // If tick history is thin, supplement with current watchlist cache
    if ((empty($todayBuy) && empty($todaySell)) && file_exists($wlCache)) {
        $wl = json_decode(file_get_contents($wlCache), true) ?? [];
        $stocks = $wl['stocks'] ?? [];
        foreach ($stocks as $s) {
            $sym    = $s['symbol'] ?? '';
            $signal = $s['signal'] ?? 'Hold';
            $score  = $s['momentum'] ?? 0;
            $chg    = $s['day_change_pct'] ?? 0;
            $price  = $s['price'] ?? 0;
            $entry  = [
                'symbol' => $sym, 'name' => $s['name'] ?? $sym,
                'price' => $price, 'price_chg' => $chg,
                'ticks' => 1, 'avg_score' => $score,
                'streak' => 1, 'streak_sig' => $signal,
                'last_chg' => $chg, 'buy_count' => $signal==='Buy'?1:0,
                'sell_count' => $signal==='Sell'?1:0, 'dominance' => 100,
            ];
            if ($signal === 'Buy')       { $todayBuy[]  = $entry; $hourBuy[]  = $entry; }
            elseif ($signal === 'Sell')  { $todaySell[] = $entry; $hourSell[] = $entry; }
        }
    }

    $sorter = fn($a,$b) => (max($b['buy_count'],$b['sell_count']) - max($a['buy_count'],$a['sell_count']))
                        ?: ($b['streak'] - $a['streak'])
                        ?: ($b['avg_score'] <=> $a['avg_score']);

    usort($todayBuy,$sorter); usort($todaySell,$sorter);
    usort($hourBuy,$sorter);  usort($hourSell,$sorter);

    $log         = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) ?? [] : [];
    $totalTicks  = array_sum(array_map(fn($d) => count($d['ticks'] ?? []), $log));

    return [
        'today_buy'   => array_slice($todayBuy,  0, 5),
        'today_sell'  => array_slice($todaySell, 0, 5),
        'hour_buy'    => array_slice($hourBuy,   0, 5),
        'hour_sell'   => array_slice($hourSell,  0, 5),
        'total_ticks' => $totalTicks,
        'date'        => date('Y-m-d'),
        'generated'   => date('H:i:s'),
        'market_open' => prakashIsMarketHours(),
    ];
}

/**
 * Momentum Picks — a separate, standalone list (does NOT feed generateSignal()/
 * generateSignalFull() or the main Buy/Sell score). Built from the per-minute
 * tick log that apiTick() already writes to storage/signals_YYYY-MM-DD.json.
 *
 * Encodes two observed patterns, requested to be checked explicitly rather than
 * assumed:
 *   1. "Top gainer/loser at market open tends to stay on top" — checked by
 *      comparing the open-snapshot rank (first tick at/before 09:20) against the
 *      current rank, not just asserted.
 *   2. "Whichever sector dominates the current Top 10 gainers/losers is a good
 *      momentum pick" — a sector only qualifies if it has 3+ members in the
 *      current Top 10 (i.e. a stock shares its sector with 2+ others), per your
 *      confirmed requirement.
 *
 * IMPORTANT CAVEATS (see these reflected in the 'disclaimer' field too):
 * - This is a heuristic based on today's single session, not a backtested
 *   strategy. Gap-and-fade (reversal) is at least as common as gap-and-go
 *   (continuation), especially for large/low-volume gaps.
 * - No stop-loss, target, or position-sizing logic is included — this is a
 *   watchlist filter, not a trade plan.
 * - Needs the cron (api/cron) to have been running since ~09:16 for the open
 *   snapshot to be meaningful; if cron started late, the earliest available
 *   tick of the day is used instead and flagged as such.
 */
function apiMomentumPicks(): array
{
    $today   = date('Y-m-d');
    $logFile = STORAGE . '/signals_' . $today . '.json';

    if (!file_exists($logFile)) {
        return ['error' => 'No tick data logged yet today — the cron endpoint (api/cron) needs to run at least once (ideally from market open).', 'date' => $today];
    }
    $log = json_decode(file_get_contents($logFile), true) ?? [];
    if (!$log) {
        return ['error' => 'Tick log is empty for today.', 'date' => $today];
    }

    $openCutoff = '09:20'; // ticks at/before this are treated as the "at open" snapshot
    $now        = time();
    $hourAgo    = $now - 3600;

    $openRows = []; $nowRows = []; $hourRows = []; $lateOpenFlag = false;

    foreach ($log as $sym => $data) {
        $ticks = $data['ticks'] ?? [];
        if (!$ticks) continue;
        $name = $data['name'] ?? $sym;

        // Open-snapshot tick: earliest tick at/before cutoff; else the day's first tick (flagged as late-start)
        $openTick = null;
        foreach ($ticks as $t) {
            if (($t['min'] ?? '99:99') <= $openCutoff) { $openTick = $t; break; }
        }
        if (!$openTick) { $openTick = $ticks[0]; $lateOpenFlag = true; }

        $lastTick = end($ticks);
        $hourTicks = array_values(array_filter($ticks, fn($t) => ($t['ts'] ?? 0) >= $hourAgo));
        $hourStartTick = $hourTicks[0] ?? $lastTick;

        $openRows[] = ['symbol' => $sym, 'name' => $name, 'chg' => $openTick['chg'], 'price' => $openTick['price'], 'min' => $openTick['min'] ?? null];
        $nowRows[]  = ['symbol' => $sym, 'name' => $name, 'chg' => $lastTick['chg'],  'price' => $lastTick['price']];
        $hourRows[] = ['symbol' => $sym, 'name' => $name, 'chg_now' => $lastTick['chg'], 'chg_hour_start' => $hourStartTick['chg'], 'price' => $lastTick['price']];
    }

    // Rank at open and now (gainers descending, losers ascending)
    $openByGain = $openRows; usort($openByGain, fn($a, $b) => $b['chg'] <=> $a['chg']);
    $openByLoss = $openRows; usort($openByLoss, fn($a, $b) => $a['chg'] <=> $b['chg']);
    $nowByGain  = $nowRows;  usort($nowByGain,  fn($a, $b) => $b['chg'] <=> $a['chg']);
    $nowByLoss  = $nowRows;  usort($nowByLoss,  fn($a, $b) => $a['chg'] <=> $b['chg']);

    $openTop10GainSyms = array_slice(array_column($openByGain, 'symbol'), 0, 10);
    $openTop10LossSyms = array_slice(array_column($openByLoss, 'symbol'), 0, 10);
    $nowTop10Gainers   = array_slice($nowByGain, 0, 10);
    $nowTop10Losers    = array_slice($nowByLoss, 0, 10);

    // ── Single-stock picks: current #1 mover, flagged by whether it persisted from the open Top 10 ──
    $topBuyForDay = null;
    if (!empty($nowByGain)) {
        $c = $nowByGain[0];
        $persisted = in_array($c['symbol'], $openTop10GainSyms, true);
        $topBuyForDay = array_merge($c, [
            'persisted_from_open' => $persisted,
            'note' => $persisted
                ? 'Was in the top-10 gainers at the market-open snapshot (~09:16-09:20) and is still the #1 gainer now — the "stays on top" pattern held today.'
                : 'Currently the #1 gainer, but was NOT in the open-snapshot top 10 — this is fresh intraday momentum, not the open-persistence pattern you observed.',
        ]);
    }
    $topSellForDay = null;
    if (!empty($nowByLoss)) {
        $c = $nowByLoss[0];
        $persisted = in_array($c['symbol'], $openTop10LossSyms, true);
        $topSellForDay = array_merge($c, [
            'persisted_from_open' => $persisted,
            'note' => $persisted
                ? 'Was in the top-10 losers at the market-open snapshot (~09:16-09:20) and is still the #1 loser now — the "stays down" pattern held today.'
                : 'Currently the #1 loser, but was NOT in the open-snapshot top 10 — this is fresh intraday weakness, not the open-persistence pattern you observed.',
        ]);
    }

    // ── Hour picks: biggest mover strictly within the trailing 1-hour window (independent of open-of-day rank) ──
    $hourByGain = $hourRows; usort($hourByGain, fn($a, $b) => ($b['chg_now'] - $b['chg_hour_start']) <=> ($a['chg_now'] - $a['chg_hour_start']));
    $hourByLoss = $hourRows; usort($hourByLoss, fn($a, $b) => ($a['chg_now'] - $a['chg_hour_start']) <=> ($b['chg_now'] - $b['chg_hour_start']));
    $topBuyForHour  = $hourByGain[0] ?? null;
    $topSellForHour = $hourByLoss[0] ?? null;

    // ── Sector-momentum picks: sector must have 3+ members in the current Top 10 (shares with 2+ others) ──
    $sectorOf = function (string $sym): ?string {
        $bare = strtoupper(str_replace('.NS', '', $sym));
        foreach (SECTOR_MAP as $sector => $members) {
            if (in_array($bare, $members, true)) return $sector;
        }
        return null;
    };

    $gainBySector = [];
    foreach ($nowTop10Gainers as $row) {
        $s = $sectorOf($row['symbol']);
        if ($s) $gainBySector[$s][] = $row;
    }
    $lossBySector = [];
    foreach ($nowTop10Losers as $row) {
        $s = $sectorOf($row['symbol']);
        if ($s) $lossBySector[$s][] = $row;
    }
    $sectorMomentumBuy  = array_filter($gainBySector, fn($rows) => count($rows) >= 3);
    $sectorMomentumSell = array_filter($lossBySector, fn($rows) => count($rows) >= 3);

    return [
        'date'      => $today,
        'generated' => date('H:i:s'),

        'top_buy_for_day'   => $topBuyForDay,
        'top_sell_for_day'  => $topSellForDay,
        'top_buy_for_hour'  => $topBuyForHour,
        'top_sell_for_hour' => $topSellForHour,

        'sector_momentum_buy'  => $sectorMomentumBuy,
        'sector_momentum_sell' => $sectorMomentumSell,

        'now_top10_gainers' => $nowTop10Gainers,
        'now_top10_losers'  => $nowTop10Losers,

        'open_snapshot_late' => $lateOpenFlag,
        'stocks_tracked'     => count($nowRows),

        'disclaimer' => "Heuristic watchlist filter based on today's session only — not a backtested strategy, not risk-managed, and not the same as the main scorecard signal. Gap-and-fade (reversal) happens about as often as gap-and-go (continuation); confirm with volume/trend before acting, and use your own stop-loss.",
    ];
}

function apiWatchlistPage(int $page = 1, string $sector = '', string $search = ''): array
{
    $perPage  = 20;
    $username = getCurrentUser();
    $allSyms  = $username ? getUserWatchlist($username) : getActiveWatchlist();

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
                    '_source'       => $quote['_source'] ?? null,
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
                '_source'       => $quote['_source'] ?? null,
            ];
        } catch (\Throwable $e) {
            continue;
        }
    }

    // Sort: Buy first by momentum, then Sells
    usort($stocks, fn($a,$b) => ($b['signal']==='Buy'?1:0) - ($a['signal']==='Buy'?1:0) ?: $b['momentum_score'] <=> $a['momentum_score']);

    $buys  = array_values(array_filter($stocks, fn($s) => in_array($s['signal'], ['Buy', 'Strong Buy'], true)));
    $sells = array_values(array_filter($stocks, fn($s) => in_array($s['signal'], ['Sell', 'Strong Sell'], true)));
    $mood  = count($buys) / max(1, count($stocks)) >= 0.6 ? 'Bullish'
           : (count($buys) / max(1, count($stocks)) <= 0.4 ? 'Bearish' : 'Mixed');

    $prakash = buildPrakashRecommendations($stocks, null, null, getCurrentUser());
    $ai = buildAiRecommendations($stocks, null, null, getCurrentUser());
    // Freeze today's file as Not Achieved for any open target once market
    // close has passed. Cheap no-op check on every request; only actually
    // rewrites the daily file the first time it runs after close each day.
    closePrakashDailyIfNeeded(getCurrentUser());
    closeAiDailyIfNeeded(getCurrentUser());

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
        'custom_watchlist' => $username ? getUserWatchlist($username) : [],
        'prakash_recommendations' => $prakash,
        'ai_recommendations' => $ai,
    ];
}

