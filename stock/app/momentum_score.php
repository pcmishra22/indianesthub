<?php
declare(strict_types=1);

/**
 * app/momentum_score.php — Momentum Ranking Engine point-score layer.
 *
 * Prakash's existing engine (app/prakash_recommendations.php) already scores
 * every refresh's rank movement, sustained Top-N presence, and new-entry
 * breakouts into a single 0..100 "confidence" number. This module adds the
 * additional signals from the point-based spec on top of that context —
 * volume spikes, price/percentage-change confirmation, acceleration
 * (2nd-derivative of % change), and a volatility penalty for erratic
 * stocks — and combines everything into an unbounded point score with a
 * star-tier recommendation, exactly like the spec's worked example:
 *
 *   Buy Score = Rank + Consistency + Volume + Trend + Acceleration
 *               + Breakout - Volatility
 *   > 100            -> ⭐⭐⭐⭐⭐ Strong Buy/Sell
 *   80 - 100         -> ⭐⭐⭐⭐  Buy/Sell
 *   60 - 80          -> ⭐⭐⭐   Watch
 *   < 60             -> Ignore
 *
 * Every function here is a pure function of its inputs (no I/O, no global
 * state) so it can be unit-tested signal-by-signal. buildPrakashRecommendations()
 * is the only caller and supplies the per-symbol context.
 */

// ── Signal 1: Rank Score ───────────────────────────────────────
// +MS_POINTS_RANK if this stock currently holds the #1 spot (Buy) or the
// very last spot (Sell) on the %-change leaderboard.
function msRankScore(int $rank, int $totalCount, string $side): float
{
    if ($totalCount <= 0 || $rank <= 0) return 0.0;
    if ($side === 'Buy') return $rank === 1 ? (float)MS_POINTS_RANK : 0.0;
    return $rank === $totalCount ? (float)MS_POINTS_RANK : 0.0;
}

// ── Signal 2: Top-N Membership ─────────────────────────────────
// +MS_POINTS_TOPN if the stock is currently inside the Top-N gainers
// (Buy side) or Top-N losers (Sell side) leaderboard slice.
function msTopNScore(bool $inTopN): float
{
    return $inTopN ? (float)MS_POINTS_TOPN : 0.0;
}

// ── Signal 3: Consistency ───────────────────────────────────────
// +MS_POINTS_CONSISTENCY only when the stock has held a Top-N spot for
// the *entire* lookback window (e.g. all 5 of the last 5 refreshes) —
// a single miss anywhere in the window means no bonus.
function msConsistencyScore(int $consecutive, int $lookback): float
{
    if ($lookback <= 0) return 0.0;
    return $consecutive >= $lookback ? (float)MS_POINTS_CONSISTENCY : 0.0;
}

// ── Signal 4: Rank Improvement ──────────────────────────────────
// $rankSeries is oldest -> newest rank numbers over the lookback window,
// e.g. [12, 8, 5, 3, 2]. Every rank position gained is worth
// MS_POINTS_PER_RANK_STEP. For the Buy side a shrinking rank number is
// good (climbing toward #1); for the Sell side a growing rank number is
// good (sinking toward the bottom) so the sign is flipped. Returns a
// signed value — a stock that's actually moving the *wrong* way for its
// side comes back negative, pulling its total score down rather than
// just contributing zero.
function msRankImprovementScore(array $rankSeries, string $side): float
{
    $n = count($rankSeries);
    if ($n < 2) return 0.0;
    $first = (float)reset($rankSeries);
    $last = (float)end($rankSeries);
    $improvement = $first - $last; // +ve = rank number shrank = climbed toward #1
    if ($side === 'Sell') $improvement = -$improvement; // sinking toward the bottom is the "good" direction for Sell
    return $improvement * (float)MS_POINTS_PER_RANK_STEP;
}

// ── Signal 5: New Breakout ──────────────────────────────────────
// +MS_POINTS_BREAKOUT the first time a stock enters the Top-N today.
function msBreakoutScore(bool $isNewEntry): float
{
    return $isNewEntry ? (float)MS_POINTS_BREAKOUT : 0.0;
}

// ── Signal 6: Volume Spike ──────────────────────────────────────
// +MS_POINTS_VOLUME when today's volume is at least MS_VOLUME_SPIKE_RATIO
// times the recent average (mirrors volumeAnalysis()'s own "spike" flag
// in app/signals.php). A null ratio (not enough history to compute one)
// is ignored rather than penalized, per spec ("No volume -> Ignore").
function msVolumeScore(?float $volRatio): float
{
    if ($volRatio === null) return 0.0;
    return $volRatio >= (float)MS_VOLUME_SPIKE_RATIO ? (float)MS_POINTS_VOLUME : 0.0;
}

// ── Signal 7: Price Confirmation ────────────────────────────────
// Strong confirmation when BOTH the price itself and the %-change figure
// moved further in the stock's favor since the previous refresh; a weaker
// bonus otherwise. $priceSeries / $changeSeries are oldest -> newest.
function msPriceConfirmationScore(array $priceSeries, array $changeSeries, string $side): float
{
    $np = count($priceSeries);
    $nc = count($changeSeries);
    if ($np < 2 || $nc < 2) return 0.0;
    $priceDelta = (float)end($priceSeries) - (float)$priceSeries[$np - 2];
    $changeDelta = (float)end($changeSeries) - (float)$changeSeries[$nc - 2];
    if ($side === 'Buy') {
        return ($priceDelta > 0 && $changeDelta > 0)
            ? (float)MS_POINTS_PRICE_CONFIRM_STRONG
            : (float)MS_POINTS_PRICE_CONFIRM_WEAK;
    }
    return ($priceDelta < 0 && $changeDelta < 0)
        ? (float)MS_POINTS_PRICE_CONFIRM_STRONG
        : (float)MS_POINTS_PRICE_CONFIRM_WEAK;
}

// ── Signal 8: Acceleration ──────────────────────────────────────
// Not just "% change is rising" (that's signal 7) but "the *rate* of rise
// is itself increasing" — e.g. 2.0 -> 2.4 -> 3.1 -> 4.0 has step sizes
// 0.4, 0.7, 0.9: each step bigger than the last. Requires at least 3
// points of %-change history (2 deltas) to detect. Bonus is proportional
// to how much the step size grew, capped at MS_POINTS_ACCELERATION.
function msAccelerationScore(array $changeSeries, string $side): float
{
    $n = count($changeSeries);
    if ($n < 3) return 0.0;
    $deltas = [];
    for ($i = 1; $i < $n; $i++) {
        $deltas[] = (float)$changeSeries[$i] - (float)$changeSeries[$i - 1];
    }
    $m = count($deltas);

    if ($side === 'Buy') {
        for ($i = 1; $i < $m; $i++) {
            if ($deltas[$i] <= $deltas[$i - 1]) return 0.0; // not strictly accelerating up
        }
        $gain = end($deltas) - $deltas[0];
    } else {
        for ($i = 1; $i < $m; $i++) {
            if ($deltas[$i] >= $deltas[$i - 1]) return 0.0; // not strictly accelerating down
        }
        $gain = $deltas[0] - end($deltas);
    }
    if ($gain <= 0) return 0.0;
    return min((float)MS_POINTS_ACCELERATION, $gain * (float)MS_ACCELERATION_SCALE);
}

// ── Signal 9: Volatility ────────────────────────────────────────
// Counts sign flips in the %-change series (e.g. +4% -> -1% -> +5% -> -2%
// is 3 flips). MS_VOLATILITY_FLIP_THRESHOLD or more flips inside the
// window means "too unstable to trust" — a fixed penalty is subtracted
// from the combined score rather than the whole thing being discarded,
// so a stock can still register on other signals but with reduced
// overall confidence.
function msVolatilityPenalty(array $changeSeries): float
{
    $n = count($changeSeries);
    if ($n < 3) return 0.0;
    $flips = 0;
    $prevSign = 0;
    foreach ($changeSeries as $v) {
        $v = (float)$v;
        if ($v === 0.0) continue; // flat readings don't count as a flip either way
        $sign = $v > 0 ? 1 : -1;
        if ($prevSign !== 0 && $sign !== $prevSign) $flips++;
        $prevSign = $sign;
    }
    return $flips >= (int)MS_VOLATILITY_FLIP_THRESHOLD ? (float)MS_POINTS_VOLATILITY_PENALTY : 0.0;
}

// ── Combine + Tier ───────────────────────────────────────────────
// Sums all 9 signals (already side-normalized so "good for this side"
// is always positive) into one score, clamped at a floor of 0 for
// display. Returns both the clamped score and the full breakdown so a
// caller (or a debug view) can show exactly which signals fired.
function msCombineSignals(array $signals): array
{
    $raw = ($signals['rank_score'] ?? 0.0)
        + ($signals['topn_score'] ?? 0.0)
        + ($signals['consistency_score'] ?? 0.0)
        + ($signals['improvement_score'] ?? 0.0)
        + ($signals['breakout_score'] ?? 0.0)
        + ($signals['volume_score'] ?? 0.0)
        + ($signals['price_confirm_score'] ?? 0.0)
        + ($signals['acceleration_score'] ?? 0.0)
        - ($signals['volatility_penalty'] ?? 0.0);

    $score = max(0.0, $raw);
    return [
        'score' => round($score, 1),
        'raw' => round($raw, 1),
        'breakdown' => $signals,
    ];
}

// Maps a combined score to the spec's star tiers. $side is only used to
// phrase the label ("Strong Buy" vs "Strong Sell") — the thresholds
// themselves are identical on both sides.
function msTier(float $score, string $side): array
{
    $verb = $side === 'Sell' ? 'Sell' : 'Buy';
    if ($score > (float)MS_TIER_STRONG) return ['stars' => 5, 'tier' => "Strong {$verb}"];
    if ($score >= (float)MS_TIER_ACTIONABLE) return ['stars' => 4, 'tier' => $verb];
    if ($score >= (float)MS_TIER_WATCH) return ['stars' => 3, 'tier' => 'Watch'];
    return ['stars' => 0, 'tier' => 'Ignore'];
}

// ── One-shot scorer ──────────────────────────────────────────────
// $ctx keys:
//   rank (int), totalCount (int), inTopN (bool), consecutive (int),
//   lookback (int), isNewEntry (bool), volRatio (?float),
//   rankSeries (array, oldest->newest), changeSeries (array, oldest->newest),
//   priceSeries (array, oldest->newest)
function msScoreStock(array $ctx, string $side): array
{
    $signals = [
        'rank_score' => msRankScore((int)($ctx['rank'] ?? 0), (int)($ctx['totalCount'] ?? 0), $side),
        'topn_score' => msTopNScore((bool)($ctx['inTopN'] ?? false)),
        'consistency_score' => msConsistencyScore((int)($ctx['consecutive'] ?? 0), (int)($ctx['lookback'] ?? 0)),
        'improvement_score' => msRankImprovementScore($ctx['rankSeries'] ?? [], $side),
        'breakout_score' => msBreakoutScore((bool)($ctx['isNewEntry'] ?? false)),
        'volume_score' => msVolumeScore(isset($ctx['volRatio']) ? (float)$ctx['volRatio'] : null),
        'price_confirm_score' => msPriceConfirmationScore($ctx['priceSeries'] ?? [], $ctx['changeSeries'] ?? [], $side),
        'acceleration_score' => msAccelerationScore($ctx['changeSeries'] ?? [], $side),
        'volatility_penalty' => msVolatilityPenalty($ctx['changeSeries'] ?? []),
    ];

    $combined = msCombineSignals($signals);
    $tier = msTier($combined['score'], $side);

    return [
        'score' => $combined['score'],
        'raw_score' => $combined['raw'],
        'stars' => $tier['stars'],
        'tier' => $tier['tier'],
        'signals' => $signals,
    ];
}
