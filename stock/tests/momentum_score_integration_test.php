<?php
require __DIR__ . '/../app/config.php';
require __DIR__ . '/../app/users.php';
require __DIR__ . '/../app/prakash_recommendations.php';

$tmpDir = __DIR__ . '/tmp_test_momentum_integration';
if (!is_dir($tmpDir)) mkdir($tmpDir, 0755, true);
$statePath       = $tmpDir . '/state.json';
$historyPath     = $tmpDir . '/history.json';
$rankHistoryPath = $tmpDir . '/rank_history.json';
$topSeenPath     = $tmpDir . '/top_seen.json';
@unlink($statePath);
@unlink($historyPath);
@unlink($rankHistoryPath);
@unlink($topSeenPath);

function msiTestAssert(bool $cond, string $msg): void
{
    if (!$cond) {
        fwrite(STDERR, "FAIL: $msg\n");
        exit(1);
    }
    fwrite(STDOUT, "  ok: $msg\n");
}

function msiRun(array $stocks, string $statePath, string $historyPath, string $rankHistoryPath, string $topSeenPath): array
{
    return buildPrakashRecommendations($stocks, $statePath, $historyPath, null, $rankHistoryPath, $topSeenPath);
}

echo "── Initial iteration exposes top5_buy / top5_sell shape ────────\n";
$stocks = [
    ['symbol' => 'AAA', 'price' => 100.0, 'change_pct' => 2.5, 'vol_ratio' => 1.0],
    ['symbol' => 'BBB', 'price' => 200.0, 'change_pct' => 1.0, 'vol_ratio' => 1.0],
    ['symbol' => 'CCC', 'price' => 300.0, 'change_pct' => -1.5, 'vol_ratio' => 1.0],
];
$out = msiRun($stocks, $statePath, $historyPath, $rankHistoryPath, $topSeenPath);
msiTestAssert(array_key_exists('top5_buy', $out), 'top5_buy key present in output');
msiTestAssert(array_key_exists('top5_sell', $out), 'top5_sell key present in output');
$buySymsInit = array_column($out['top5_buy'], 'symbol');
$sellSymsInit = array_column($out['top5_sell'], 'symbol');
msiTestAssert(count($buySymsInit) + count($sellSymsInit) === 3, 'every tracked stock is placed on exactly one side (Buy+Sell totals 3 when only 3 are tracked)');
msiTestAssert(count(array_intersect($buySymsInit, $sellSymsInit)) === 0, 'no stock appears in both top5_buy and top5_sell at once');
msiTestAssert(isset($out['top5_buy'][0]['score']), 'top5_buy entries carry a point score');
msiTestAssert(isset($out['top5_buy'][0]['stars']), 'top5_buy entries carry a star rating');
msiTestAssert(isset($out['top5_buy'][0]['tier']), 'top5_buy entries carry a tier label');
msiTestAssert(($out['buy_box'][0]['point_score'] ?? null) !== null, 'buy_box entries are annotated with point_score');
msiTestAssert(isset($out['buy_box'][0]['stars']), 'buy_box entries are annotated with stars');
msiTestAssert(isset($out['buy_box'][0]['tier']), 'buy_box entries are annotated with tier');

echo "── A stock that dominates every signal rises to the top of top5_buy ──\n";
@unlink($statePath); @unlink($historyPath); @unlink($rankHistoryPath); @unlink($topSeenPath);
// 5 iterations: STAR climbs 5→4→3→2→1 every single refresh (consistency +
// rank improvement + breakout on iter 1), with volume spike and a rising,
// accelerating %change series (price confirmation + acceleration).
$symbols = ['STAR', 'B', 'C', 'D', 'E'];
$changeSeriesFor = ['STAR' => [1.0, 1.6, 2.4, 3.4, 4.6], 'B' => [3, 3, 3, 3, 3], 'C' => [2, 2, 2, 2, 2], 'D' => [0.5, 0.5, 0.5, 0.5, 0.5], 'E' => [-1, -1, -1, -1, -1]];
$rankFor = ['STAR' => [5, 4, 3, 2, 1], 'B' => [1, 1, 2, 3, 3], 'C' => [2, 2, 1, 1, 2], 'D' => [3, 3, 4, 4, 4], 'E' => [4, 5, 5, 5, 5]];
for ($iter = 0; $iter < 5; $iter++) {
    $batch = [];
    foreach ($symbols as $sym) {
        $batch[] = ['symbol' => $sym, 'price' => 100.0 + $rankFor[$sym][$iter], 'change_pct' => $changeSeriesFor[$sym][$iter], 'vol_ratio' => $sym === 'STAR' ? 3.5 : 1.0];
    }
    // Order the batch by the intended rank for this iteration so the
    // engine's own sort-by-change_pct produces exactly that rank order.
    usort($batch, function ($a, $b) use ($rankFor, $iter) {
        return $rankFor[$a['symbol']][$iter] <=> $rankFor[$b['symbol']][$iter];
    });
    $out = msiRun($batch, $statePath, $historyPath, $rankHistoryPath, $topSeenPath);
}
$top5BuySymbols = array_column($out['top5_buy'], 'symbol');
msiTestAssert(($top5BuySymbols[0] ?? '') === 'STAR.NS', 'STAR (dominates every Buy signal) ranks #1 in top5_buy after 5 iterations');
msiTestAssert(($out['top5_buy'][0]['tier'] ?? '') === 'Strong Buy', 'STAR is tiered Strong Buy');
msiTestAssert(($out['top5_buy'][0]['stars'] ?? 0) === 5, 'STAR gets 5 stars');

echo "\nAll momentum-score integration tests passed ✓\n";

echo "── A choppy stock scoring on BOTH sides is only shown on its stronger side ──\n";
@unlink($statePath); @unlink($historyPath); @unlink($rankHistoryPath); @unlink($topSeenPath);
// TITAN-style stock: bounces around mid-table (rank series not cleanly
// monotonic either way) so it can pick up some Buy-side points (e.g. volume,
// partial rank recovery) and some Sell-side points (e.g. partial rank
// slippage) in the same refresh — exactly the scenario that used to let it
// surface in both the Top 5 Buy and Top 5 Sell leaderboards at once.
$symbols2 = ['TITAN', 'UP1', 'UP2', 'DOWN1', 'DOWN2'];
$rankFor2 = ['TITAN' => [3, 2, 3, 2, 3], 'UP1' => [5, 5, 4, 3, 1], 'UP2' => [4, 4, 4, 4, 2], 'DOWN1' => [1, 1, 2, 4, 4], 'DOWN2' => [2, 3, 1, 5, 5]];
$changeFor2 = ['TITAN' => [0.5, 1.0, -0.3, 0.8, 0.1], 'UP1' => [-1, -0.5, 0.5, 1.5, 3.0], 'UP2' => [-0.5, -0.2, 0.1, 0.4, 1.0], 'DOWN1' => [3.0, 2.0, 1.0, -1.0, -2.0], 'DOWN2' => [1.5, 0.5, 2.0, -2.0, -3.0]];
for ($iter = 0; $iter < 5; $iter++) {
    $batch = [];
    foreach ($symbols2 as $sym) {
        $batch[] = ['symbol' => $sym, 'price' => 100.0 + $rankFor2[$sym][$iter], 'change_pct' => $changeFor2[$sym][$iter], 'vol_ratio' => $sym === 'TITAN' ? 2.0 : 1.0];
    }
    usort($batch, function ($a, $b) use ($rankFor2, $iter) {
        return $rankFor2[$a['symbol']][$iter] <=> $rankFor2[$b['symbol']][$iter];
    });
    $out2 = msiRun($batch, $statePath, $historyPath, $rankHistoryPath, $topSeenPath);
}
$buySyms2 = array_column($out2['top5_buy'], 'symbol');
$sellSyms2 = array_column($out2['top5_sell'], 'symbol');
msiTestAssert(count(array_intersect($buySyms2, $sellSyms2)) === 0, 'TITAN-style choppy stock never appears in both leaderboards simultaneously');
$inBuy = in_array('TITAN.NS', $buySyms2, true);
$inSell = in_array('TITAN.NS', $sellSyms2, true);
msiTestAssert($inBuy xor $inSell, 'TITAN.NS lands on exactly one side, whichever it scores higher on, when it appears at all');
