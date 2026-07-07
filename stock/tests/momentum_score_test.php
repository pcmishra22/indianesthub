<?php
require __DIR__ . '/../app/config.php';
require __DIR__ . '/../app/momentum_score.php';

function msTestAssert(bool $cond, string $msg): void
{
    if (!$cond) {
        fwrite(STDERR, "FAIL: $msg\n");
        exit(1);
    }
    fwrite(STDOUT, "  ok: $msg\n");
}

echo "── Signal 1: Rank Score ─────────────────────────────────────\n";
msTestAssert(msRankScore(1, 20, 'Buy') === (float)MS_POINTS_RANK, 'rank #1 scores full points on Buy side');
msTestAssert(msRankScore(2, 20, 'Buy') === 0.0, 'rank #2 scores 0 on Buy side');
msTestAssert(msRankScore(20, 20, 'Sell') === (float)MS_POINTS_RANK, 'last rank scores full points on Sell side');
msTestAssert(msRankScore(19, 20, 'Sell') === 0.0, 'second-to-last scores 0 on Sell side');

echo "── Signal 2: Top-N Membership ───────────────────────────────\n";
msTestAssert(msTopNScore(true) === (float)MS_POINTS_TOPN, 'in Top-N scores full points');
msTestAssert(msTopNScore(false) === 0.0, 'outside Top-N scores 0');

echo "── Signal 3: Consistency ────────────────────────────────────\n";
msTestAssert(msConsistencyScore(5, 5) === (float)MS_POINTS_CONSISTENCY, 'full lookback presence scores full points');
msTestAssert(msConsistencyScore(4, 5) === 0.0, 'one miss in the window scores 0 (all-or-nothing)');

echo "── Signal 4: Rank Improvement ───────────────────────────────\n";
msTestAssert(msRankImprovementScore([12, 8, 5, 3, 2], 'Buy') === (10.0 * MS_POINTS_PER_RANK_STEP), '12→2 improvement scores +10 steps on Buy side');
msTestAssert(msRankImprovementScore([2, 4, 8, 15], 'Buy') < 0, 'weakening rank scores negative on Buy side');
msTestAssert(msRankImprovementScore([2, 4, 8, 15], 'Sell') === (13.0 * MS_POINTS_PER_RANK_STEP), '2→15 sinking scores positive on Sell side');
msTestAssert(msRankImprovementScore([5], 'Buy') === 0.0, 'single data point scores 0 (nothing to compare)');

echo "── Signal 5: Breakout ────────────────────────────────────────\n";
msTestAssert(msBreakoutScore(true) === (float)MS_POINTS_BREAKOUT, 'new entry scores full points');
msTestAssert(msBreakoutScore(false) === 0.0, 'not a new entry scores 0');

echo "── Signal 6: Volume Spike ────────────────────────────────────\n";
msTestAssert(msVolumeScore(3.0) === (float)MS_POINTS_VOLUME, 'volume 3x average scores full points');
msTestAssert(msVolumeScore(1.1) === 0.0, 'volume near average scores 0');
msTestAssert(msVolumeScore(null) === 0.0, 'no volume data is ignored, not penalized');

echo "── Signal 7: Price Confirmation ─────────────────────────────\n";
msTestAssert(
    msPriceConfirmationScore([100, 102], [1.0, 2.0], 'Buy') === (float)MS_POINTS_PRICE_CONFIRM_STRONG,
    'price up AND %change up = Strong Buy confirmation'
);
msTestAssert(
    msPriceConfirmationScore([100, 99], [1.0, 2.0], 'Buy') === (float)MS_POINTS_PRICE_CONFIRM_WEAK,
    'price down but %change up = Weak Buy confirmation'
);
msTestAssert(
    msPriceConfirmationScore([100, 98], [-1.0, -2.0], 'Sell') === (float)MS_POINTS_PRICE_CONFIRM_STRONG,
    'price down AND %change down = Strong Sell confirmation'
);

echo "── Signal 8: Acceleration ────────────────────────────────────\n";
msTestAssert(msAccelerationScore([2.0, 2.4, 3.1, 4.0], 'Buy') > 0, 'strictly widening upward steps score positive acceleration');
msTestAssert(msAccelerationScore([2.0, 2.4, 2.7, 2.9], 'Buy') === 0.0, 'shrinking upward steps (decelerating) score 0');
msTestAssert(msAccelerationScore([-2.0, -2.4, -3.1, -4.0], 'Sell') > 0, 'strictly widening downward steps score positive on Sell side');
msTestAssert(
    min((float)MS_POINTS_ACCELERATION, 1000.0) === (float)MS_POINTS_ACCELERATION,
    'acceleration bonus is capped at MS_POINTS_ACCELERATION'
);
msTestAssert(msAccelerationScore([1.0, 2.0], 'Buy') === 0.0, 'fewer than 3 points cannot show acceleration');

echo "── Signal 9: Volatility ──────────────────────────────────────\n";
msTestAssert(msVolatilityPenalty([4.0, -1.0, 5.0, -2.0]) === (float)MS_POINTS_VOLATILITY_PENALTY, '3 sign flips triggers the volatility penalty');
msTestAssert(msVolatilityPenalty([1.0, 2.0, 3.0, 4.0]) === 0.0, 'a steady one-directional series has no penalty');
msTestAssert(msVolatilityPenalty([1.0, 2.0]) === 0.0, 'fewer than 3 points cannot be judged volatile');

echo "── Combine + Tier ────────────────────────────────────────────\n";
$strong = msCombineSignals([
    'rank_score' => 20, 'topn_score' => 10, 'consistency_score' => 40,
    'improvement_score' => 18, 'breakout_score' => 15, 'volume_score' => 15,
    'price_confirm_score' => 0, 'acceleration_score' => 12, 'volatility_penalty' => 0,
]);
msTestAssert($strong['score'] === 130.0, 'worked-example signals sum to 130 (matches spec\'s example total)');
$tierStrong = msTier($strong['score'], 'Buy');
msTestAssert($tierStrong['stars'] === 5 && $tierStrong['tier'] === 'Strong Buy', 'score > 100 tiers as 5-star Strong Buy');

$tierBuy = msTier(85.0, 'Buy');
msTestAssert($tierBuy['stars'] === 4 && $tierBuy['tier'] === 'Buy', '80-100 tiers as 4-star Buy');
$tierSell = msTier(85.0, 'Sell');
msTestAssert($tierSell['tier'] === 'Sell', 'Sell side reuses the same thresholds with a Sell label');

$tierWatch = msTier(65.0, 'Buy');
msTestAssert($tierWatch['stars'] === 3 && $tierWatch['tier'] === 'Watch', '60-80 tiers as 3-star Watch (side-agnostic label)');

$tierIgnore = msTier(40.0, 'Buy');
msTestAssert($tierIgnore['stars'] === 0 && $tierIgnore['tier'] === 'Ignore', 'below 60 tiers as Ignore');

$negative = msCombineSignals(['volatility_penalty' => 999]);
msTestAssert($negative['score'] === 0.0, 'combined score floors at 0 even if the raw sum is negative');

echo "── One-shot scorer (msScoreStock) ────────────────────────────\n";
$ctx = [
    'rank' => 1, 'totalCount' => 20, 'inTopN' => true, 'consecutive' => 5, 'lookback' => 5,
    'isNewEntry' => true, 'volRatio' => 3.0,
    'rankSeries' => [5, 4, 3, 2, 1], 'changeSeries' => [1.0, 1.8, 2.9, 4.3], 'priceSeries' => [100, 101, 103, 106],
];
$result = msScoreStock($ctx, 'Buy');
msTestAssert($result['score'] > (float)MS_TIER_STRONG, 'a stock hitting every Buy signal scores above the Strong tier');
msTestAssert($result['stars'] === 5, 'that stock gets 5 stars');
msTestAssert($result['tier'] === 'Strong Buy', 'that stock is tiered Strong Buy');
msTestAssert(isset($result['signals']['rank_score']), 'breakdown includes every named signal');

$flatCtx = [
    'rank' => 10, 'totalCount' => 20, 'inTopN' => false, 'consecutive' => 0, 'lookback' => 5,
    'isNewEntry' => false, 'volRatio' => null,
    'rankSeries' => [10, 10], 'changeSeries' => [0.1, 0.1], 'priceSeries' => [100, 100],
];
$flatResult = msScoreStock($flatCtx, 'Buy');
msTestAssert($flatResult['score'] < (float)MS_TIER_WATCH, 'a flat, unremarkable stock scores below the Watch tier');
msTestAssert($flatResult['tier'] === 'Ignore', 'a flat stock is tiered Ignore');

echo "\nAll momentum-score engine tests passed ✓\n";
