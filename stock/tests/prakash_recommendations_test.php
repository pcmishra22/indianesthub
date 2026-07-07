<?php
require __DIR__ . '/../app/config.php';
require __DIR__ . '/../app/users.php';
require __DIR__ . '/../app/prakash_recommendations.php';

$tmpDir = __DIR__ . '/tmp_test';
if (!is_dir($tmpDir)) mkdir($tmpDir, 0755, true);
$statePath       = $tmpDir . '/state.json';
$historyPath     = $tmpDir . '/history.json';
$rankHistoryPath = $tmpDir . '/rank_history.json';
$topSeenPath     = $tmpDir . '/top_seen.json';
@unlink($statePath);
@unlink($historyPath);
@unlink($rankHistoryPath);
@unlink($topSeenPath);

function prakashTestAssert(bool $cond, string $msg): void
{
    if (!$cond) {
        fwrite(STDERR, "FAIL: $msg\n");
        exit(1);
    }
    fwrite(STDOUT, "  ok: $msg\n");
}

function prakashTestReset(string $statePath, string $historyPath, string $rankHistoryPath, ?string $topSeenPath = null): void
{
    @unlink($statePath);
    @unlink($historyPath);
    @unlink($rankHistoryPath);
    if ($topSeenPath !== null) @unlink($topSeenPath);
}

function prakashTestRun(array $stocks, string $statePath, string $historyPath, string $rankHistoryPath, ?string $topSeenPath = null): array
{
    return buildPrakashRecommendations($stocks, $statePath, $historyPath, null, $rankHistoryPath, $topSeenPath);
}

echo "── Initial iteration at 9:10 AM ───────────────────────────────\n";
prakashTestReset($statePath, $historyPath, $rankHistoryPath, $topSeenPath);
$stocks = [
    ['symbol' => 'AAA', 'price' => 100.0, 'change_pct' =>  2.5], // rank 1 → Buy
    ['symbol' => 'BBB', 'price' => 200.0, 'change_pct' =>  1.0], // rank 2
    ['symbol' => 'CCC', 'price' => 300.0, 'change_pct' => -1.5], // rank 3 → Sell
];
$out = prakashTestRun($stocks, $statePath, $historyPath, $rankHistoryPath, $topSeenPath);

prakashTestAssert($out['is_initial_iteration'] === true, 'is_initial_iteration=true on first call of the day');
prakashTestAssert($out['iteration'] === 1, 'iteration count = 1 on first call');
prakashTestAssert(($out['top_gainer']['symbol'] ?? '') === 'AAA', 'top_gainer = AAA');
prakashTestAssert(($out['top_loser']['symbol']  ?? '') === 'CCC', 'top_loser = CCC');
prakashTestAssert(($out['buy_recommendation']['symbol']  ?? '') === 'AAA', 'buy_recommendation = AAA (top of leaderboard)');
prakashTestAssert(($out['sell_recommendation']['symbol'] ?? '') === 'CCC', 'sell_recommendation = CCC (bottom of leaderboard)');
prakashTestAssert(($out['buy_recommendation']['reason']  ?? '') === 'Initial Top Gainer', 'buy reason = Initial Top Gainer');
prakashTestAssert(($out['sell_recommendation']['reason'] ?? '') === 'Initial Top Loser',  'sell reason = Initial Top Loser');
prakashTestAssert(!empty($out['buy_box']),  'buy_box has entries');
prakashTestAssert(!empty($out['sell_box']), 'sell_box has entries');
prakashTestAssert(($out['buy_box'][0]['symbol']  ?? '') === 'AAA.NS', 'buy_box top entry = AAA.NS');
prakashTestAssert(($out['sell_box'][0]['symbol'] ?? '') === 'CCC.NS', 'sell_box top entry = CCC.NS');
$daily = $out['daily_summary'];
prakashTestAssert($daily['total'] === 2, 'daily file has 2 entries (one Buy, one Sell)');
$entries = array_column($daily['recommendations'], null, 'symbol');
prakashTestAssert(isset($entries['AAA']), 'AAA logged in daily file');
prakashTestAssert(($entries['AAA']['side'] ?? '') === 'Buy',  'AAA side = Buy');
prakashTestAssert(($entries['AAA']['status'] ?? '') === 'Active', 'AAA status = Active (target not yet hit)');
prakashTestAssert(abs(($entries['AAA']['target_price'] ?? 0) - 101.0) < 0.001, 'AAA target_price = 101.0 (entry 100 × 1.01)');
prakashTestAssert(isset($entries['CCC']), 'CCC logged in daily file');
prakashTestAssert(($entries['CCC']['side'] ?? '') === 'Sell', 'CCC side = Sell');
prakashTestAssert(abs(($entries['CCC']['target_price'] ?? 0) - 297.0) < 0.001, 'CCC target_price = 297.0 (entry 300 × 0.99)');

echo "── Build momentum: BBB climbs ranks over 5 iterations ─────────\n";
prakashTestReset($statePath, $historyPath, $rankHistoryPath, $topSeenPath);
// Iteration 1: BBB at rank 3
$out1 = prakashTestRun([
    ['symbol' => 'AAA', 'price' => 100.0, 'change_pct' =>  3.0], // rank 1
    ['symbol' => 'BBB', 'price' => 200.0, 'change_pct' =>  1.0], // rank 3
    ['symbol' => 'CCC', 'price' => 300.0, 'change_pct' =>  0.0], // rank 2
    ['symbol' => 'DDD', 'price' => 400.0, 'change_pct' => -2.0], // rank 4
], $statePath, $historyPath, $rankHistoryPath, $topSeenPath);
prakashTestAssert($out1['iteration'] === 1, 'iter 1/5');
prakashTestAssert($out1['is_initial_iteration'] === true, 'first call still initial');

// Iteration 2: BBB at rank 2 (climbed 3→2)
$out2 = prakashTestRun([
    ['symbol' => 'AAA', 'price' => 100.0, 'change_pct' =>  3.0], // rank 1
    ['symbol' => 'BBB', 'price' => 200.0, 'change_pct' =>  2.0], // rank 2 (climbed)
    ['symbol' => 'CCC', 'price' => 300.0, 'change_pct' =>  1.0], // rank 3 (fell)
    ['symbol' => 'DDD', 'price' => 400.0, 'change_pct' => -1.0], // rank 4
], $statePath, $historyPath, $rankHistoryPath, $topSeenPath);
prakashTestAssert($out2['iteration'] === 2, 'iter 2/5');
prakashTestAssert($out2['is_initial_iteration'] === false, 'not initial any more');
prakashTestAssert(empty($out2['momentum_up']), 'no momentum yet (only 2 of 5 iterations)');
prakashTestAssert(empty($out2['momentum_down']), 'no momentum yet (only 2 of 5 iterations)');
prakashTestAssert(($out2['rank_movement_buy']['symbol'] ?? '') === 'BBB.NS', 'single-refresh rank_movement_buy = BBB (3→2)');

// Iteration 3: BBB at rank 1 (climbed 2→1)
$out3 = prakashTestRun([
    ['symbol' => 'AAA', 'price' => 100.0, 'change_pct' =>  2.0], // rank 2 (fell from 1)
    ['symbol' => 'BBB', 'price' => 200.0, 'change_pct' =>  3.0], // rank 1 (climbed from 2)
    ['symbol' => 'CCC', 'price' => 300.0, 'change_pct' =>  0.5], // rank 3
    ['symbol' => 'DDD', 'price' => 400.0, 'change_pct' => -2.5], // rank 4
], $statePath, $historyPath, $rankHistoryPath, $topSeenPath);
prakashTestAssert($out3['iteration'] === 3, 'iter 3/5');
prakashTestAssert(empty($out3['momentum_up']), 'no momentum yet (only 3 of 5 iterations)');

// Iteration 4: BBB stays at rank 1 (monotonic check: must keep climbing)
// If BBB is still rank 1, it's no longer strictly climbing — should NOT trigger.
$out4 = prakashTestRun([
    ['symbol' => 'AAA', 'price' => 100.0, 'change_pct' =>  1.5], // rank 2
    ['symbol' => 'BBB', 'price' => 200.0, 'change_pct' =>  2.5], // rank 1 (held)
    ['symbol' => 'CCC', 'price' => 300.0, 'change_pct' =>  0.0], // rank 3
    ['symbol' => 'DDD', 'price' => 400.0, 'change_pct' => -3.0], // rank 4
], $statePath, $historyPath, $rankHistoryPath, $topSeenPath);
prakashTestAssert($out4['iteration'] === 4, 'iter 4/5');
prakashTestAssert(empty($out4['momentum_up']), 'no momentum yet (only 4 of 5 iterations) — and rank held, not climbing');

// Iteration 5: DDD drops (4→3→2 then we'd need it to keep falling). Build a scenario where
// DDD has been falling ranks 4→3→2 across the last 3 refreshes PLUS it must have
// been higher-ranked in iterations 1 and 2 to make 5-step momentum. Restart the test
// for a cleaner sustained-down scenario.
prakashTestReset($statePath, $historyPath, $rankHistoryPath, $topSeenPath);
$baseStocks = [
    ['symbol' => 'AAA', 'price' => 100.0, 'change_pct' =>  5.0], // rank 1
    ['symbol' => 'BBB', 'price' => 200.0, 'change_pct' =>  4.0], // rank 2
    ['symbol' => 'CCC', 'price' => 300.0, 'change_pct' =>  3.0], // rank 3
    ['symbol' => 'DDD', 'price' => 400.0, 'change_pct' =>  2.0], // rank 4
];
prakashTestRun($baseStocks, $statePath, $historyPath, $rankHistoryPath, $topSeenPath);                          // 1
prakashTestRun([$baseStocks[0], $baseStocks[1], $baseStocks[2], ['symbol'=>'DDD','price'=>400.0,'change_pct'=>1.5]], $statePath, $historyPath, $rankHistoryPath, $topSeenPath); // 2
prakashTestRun([$baseStocks[0], $baseStocks[1], $baseStocks[2], ['symbol'=>'DDD','price'=>400.0,'change_pct'=>1.0]], $statePath, $historyPath, $rankHistoryPath, $topSeenPath); // 3
prakashTestRun([$baseStocks[0], $baseStocks[1], $baseStocks[2], ['symbol'=>'DDD','price'=>400.0,'change_pct'=>0.5]], $statePath, $historyPath, $rankHistoryPath, $topSeenPath); // 4
// iter 5: DDD falls below CCC and BBB — i.e. rank 4→4→4→4→1 would not work
// because that's not strictly monotonic. The DDD rank needs to keep *increasing*:
// 4 → 4 → 4 → 4 → X (where X>4) is also not strictly monotonic. Reset again.

prakashTestReset($statePath, $historyPath, $rankHistoryPath, $topSeenPath);
// DDD needs to fall ranks 1→2→3→4→5. With 4 stocks that's not possible, so use 5 stocks.
$base5 = [
    ['symbol' => 'AAA', 'price' => 100.0, 'change_pct' =>  5.0], // rank 1
    ['symbol' => 'BBB', 'price' => 200.0, 'change_pct' =>  4.0], // rank 2
    ['symbol' => 'CCC', 'price' => 300.0, 'change_pct' =>  3.0], // rank 3
    ['symbol' => 'DDD', 'price' => 400.0, 'change_pct' =>  2.0], // rank 4
    ['symbol' => 'EEE', 'price' => 500.0, 'change_pct' =>  1.0], // rank 5
];
$iterations = [
    $base5,                                                                                                                            // DDD rank 4
    [$base5[0], $base5[1], $base5[2], $base5[4], ['symbol'=>'DDD','price'=>400.0,'change_pct'=>1.0]],                                    // DDD rank 5
    [$base5[0], $base5[1], $base5[2], $base5[4], ['symbol'=>'DDD','price'=>400.0,'change_pct'=>0.0]],                                    // DDD rank 5
    [$base5[0], $base5[1], $base5[2], $base5[4], ['symbol'=>'DDD','price'=>400.0,'change_pct'=>-1.0]],                                   // DDD rank 5
    [$base5[0], $base5[1], $base5[2], $base5[4], ['symbol'=>'DDD','price'=>400.0,'change_pct'=>-2.0]],                                   // DDD rank 5
];
// That's not strictly monotonic either (4→5→5→5→5).
// Let me make a real strictly-monotonic fall. Use 6 stocks.
prakashTestReset($statePath, $historyPath, $rankHistoryPath, $topSeenPath);
$stocks6 = function(array $pcts) {
    $out = [];
    foreach ($pcts as $i => $p) {
        $out[] = ['symbol' => 'S' . chr(65+$i), 'price' => 100.0 * ($i+1), 'change_pct' => $p];
    }
    return $out;
};
// Order: highest change_pct first. SA at top.
// DDD = S3, want ranks 1→2→3→4→5: change_pct of DDD must go from highest to 5th.
$rows = [
    $stocks6([10.0, 8.0, 7.0, 5.0, 3.0, 1.0]),   // DDD = S3 at change_pct 7, rank 3
    $stocks6([10.0, 9.0, 5.0, 4.0, 3.0, 1.0]),   // DDD = S3 at change_pct 5, rank 4
    $stocks6([10.0, 9.0, 4.0, 3.5, 3.0, 1.0]),   // DDD = S3 at change_pct 4, rank 5
    $stocks6([10.0, 9.0, 3.0, 2.5, 2.0, 1.0]),   // DDD = S3 at change_pct 3, rank 5 — NOT strictly monotonic (5→5)
    $stocks6([10.0, 9.0, 2.0, 1.5, 1.0, 0.0]),   // DDD = S3 at change_pct 2, rank 5 — also 5
];
// Still not monotonic. Drop SA so DDD starts at rank 2:
//   S0 > S3 > S1 > S2 > S4 > S5
// Need S3 to climb ranks 1→2→3→4→5 strictly.
// In iter 1: S3 at change_pct X1, must be rank 1
// In iter 2: S3 at change_pct X2 < X1, must be rank 2 (one stock above it)
// In iter 3: S3 at change_pct X3 < X2, must be rank 3 (two stocks above)
// In iter 4: S3 at change_pct X4 < X3, must be rank 4
// In iter 5: S3 at change_pct X5 < X4, must be rank 5
prakashTestReset($statePath, $historyPath, $rankHistoryPath, $topSeenPath);
$rowFor = function(array $ddPct) {
    // 6 stocks: S0..S5. We want S3 (index 3) at $ddPct.
    // S0 stays at 10. S1, S2, S4, S5 must straddle S3 so S3 lands at the desired rank.
    // rank(X) = number of stocks strictly greater than X's change_pct, plus 1.
    // For S3 at rank 1: 0 stocks above → 5 stocks at or below.
    // For S3 at rank 2: 1 stock above, 4 below.
    // ...
    // Simpler: just put change_pct values in fixed positions; we control S3's pct each iter.
    // For S3 rank R, we need exactly (R-1) of {S0,S1,S2,S4,S5} above S3, and (6-R) below.
    $pcts = [0.0, 0.0, 0.0, $ddPct, 0.0, 0.0];
    // Place (R-1) stocks above S3 with descending values, (6-R) below with ascending values.
    $above = []; $below = [];
    for ($i = 0; $i < 5; $i++) {
        $idx = ($i < 3) ? $i : $i + 1; // skip S3
        if (count($above) < 0) {} // noop
    }
    // Instead: enumerate ranks directly.
    return null; // unused; use the table below
};

// Easier: hardcode the 5 iterations as exact sorted rows.
prakashTestReset($statePath, $historyPath, $rankHistoryPath, $topSeenPath);
$iters = [
    // iter 1: S3 at change_pct 9 (rank 1)
    [['symbol'=>'S0','price'=>100.0,'change_pct'=>8.0],
     ['symbol'=>'S1','price'=>100.0,'change_pct'=>7.0],
     ['symbol'=>'S2','price'=>100.0,'change_pct'=>6.0],
     ['symbol'=>'S3','price'=>100.0,'change_pct'=>9.0],
     ['symbol'=>'S4','price'=>100.0,'change_pct'=>5.0],
     ['symbol'=>'S5','price'=>100.0,'change_pct'=>4.0]],
    // iter 2: S3 at change_pct 8 (rank 2)
    [['symbol'=>'S0','price'=>100.0,'change_pct'=>8.0],
     ['symbol'=>'S3','price'=>100.0,'change_pct'=>8.0],
     ['symbol'=>'S1','price'=>100.0,'change_pct'=>7.0],
     ['symbol'=>'S2','price'=>100.0,'change_pct'=>6.0],
     ['symbol'=>'S4','price'=>100.0,'change_pct'=>5.0],
     ['symbol'=>'S5','price'=>100.0,'change_pct'=>4.0]],
    // iter 3: S3 at change_pct 7 (rank 3)
    [['symbol'=>'S0','price'=>100.0,'change_pct'=>8.0],
     ['symbol'=>'S1','price'=>100.0,'change_pct'=>7.5],
     ['symbol'=>'S3','price'=>100.0,'change_pct'=>7.0],
     ['symbol'=>'S2','price'=>100.0,'change_pct'=>6.0],
     ['symbol'=>'S4','price'=>100.0,'change_pct'=>5.0],
     ['symbol'=>'S5','price'=>100.0,'change_pct'=>4.0]],
    // iter 4: S3 at change_pct 6 (rank 4) — tied with S2 — depends on sort stability
    // To avoid tie, use 5.5
    [['symbol'=>'S0','price'=>100.0,'change_pct'=>8.0],
     ['symbol'=>'S1','price'=>100.0,'change_pct'=>7.5],
     ['symbol'=>'S2','price'=>100.0,'change_pct'=>6.5],
     ['symbol'=>'S3','price'=>100.0,'change_pct'=>5.5],
     ['symbol'=>'S4','price'=>100.0,'change_pct'=>5.0],
     ['symbol'=>'S5','price'=>100.0,'change_pct'=>4.0]],
    // iter 5: S3 at change_pct 4.5 (rank 5) — between S4 (5) and S5 (4)
    [['symbol'=>'S0','price'=>100.0,'change_pct'=>8.0],
     ['symbol'=>'S1','price'=>100.0,'change_pct'=>7.5],
     ['symbol'=>'S2','price'=>100.0,'change_pct'=>6.5],
     ['symbol'=>'S4','price'=>100.0,'change_pct'=>5.0],
     ['symbol'=>'S3','price'=>100.0,'change_pct'=>4.5],
     ['symbol'=>'S5','price'=>100.0,'change_pct'=>4.0]],
];
foreach ($iters as $i => $row) {
    $o = prakashTestRun($row, $statePath, $historyPath, $rankHistoryPath, $topSeenPath);
    if ($i < 4) {
        // During the build-up we should NOT see momentum yet (window is still filling or not strictly monotonic).
        // For S3, the rank series we just constructed IS strictly decreasing 1→2→3→4→5.
        // At iter 5 (last call above) the detector should fire.
    }
}
$out5 = $o;
prakashTestAssert($out5['iteration'] === 5, '5th iteration has iteration=5');
prakashTestAssert(!empty($out5['momentum_down']), 'S3 (DDD) fires downward momentum at iteration 5');
$s3Key = null;
foreach ($out5['momentum_down'] as $m) {
    if ($m['symbol'] === 'S3.NS') { $s3Key = $m; break; }
}
prakashTestAssert($s3Key !== null, 'S3.NS in momentum_down');
prakashTestAssert($s3Key['current_rank'] === 5, 'S3.NS current_rank=5');
prakashTestAssert($s3Key['momentum'] === 4, 'S3.NS momentum=4 (1→2→3→4→5 = +4)');
prakashTestAssert(!empty($out5['sell_box']), 'sell_box populated by momentum detector');
$sellSyms = array_column($out5['sell_box'], 'symbol');
prakashTestAssert(in_array('S3.NS', $sellSyms, true), 'S3.NS in sell_box');
prakashTestAssert(($out5['sell_recommendation']['symbol'] ?? '') === 'S3', 'sell_recommendation = S3');
prakashTestAssert(($out5['sell_recommendation']['reason'] ?? '') === 'Sustained Downward Momentum', 'sell reason = Sustained Downward Momentum');
prakashTestAssert(($out5['sell_recommendation']['previous_rank'] ?? null) === 1, 'sell previous_rank = 1 (oldest of the 5)');

echo "── Build upward momentum: SA (S0) climbs ranks 5→4→3→2→1 ──────\n";
prakashTestReset($statePath, $historyPath, $rankHistoryPath, $topSeenPath);
$upIters = [
    // iter 1: S0 at rank 5 (change_pct 1.0)
    [['symbol'=>'S4','price'=>100.0,'change_pct'=>9.0],
     ['symbol'=>'S3','price'=>100.0,'change_pct'=>8.0],
     ['symbol'=>'S2','price'=>100.0,'change_pct'=>7.0],
     ['symbol'=>'S1','price'=>100.0,'change_pct'=>5.0],
     ['symbol'=>'S0','price'=>100.0,'change_pct'=>1.0],
     ['symbol'=>'S5','price'=>100.0,'change_pct'=>-1.0]],
    // iter 2: S0 at rank 4
    [['symbol'=>'S4','price'=>100.0,'change_pct'=>9.0],
     ['symbol'=>'S3','price'=>100.0,'change_pct'=>8.0],
     ['symbol'=>'S2','price'=>100.0,'change_pct'=>7.0],
     ['symbol'=>'S0','price'=>100.0,'change_pct'=>5.5],
     ['symbol'=>'S1','price'=>100.0,'change_pct'=>5.0],
     ['symbol'=>'S5','price'=>100.0,'change_pct'=>-1.0]],
    // iter 3: S0 at rank 3
    [['symbol'=>'S4','price'=>100.0,'change_pct'=>9.0],
     ['symbol'=>'S3','price'=>100.0,'change_pct'=>8.0],
     ['symbol'=>'S0','price'=>100.0,'change_pct'=>7.5],
     ['symbol'=>'S2','price'=>100.0,'change_pct'=>7.0],
     ['symbol'=>'S1','price'=>100.0,'change_pct'=>5.0],
     ['symbol'=>'S5','price'=>100.0,'change_pct'=>-1.0]],
    // iter 4: S0 at rank 2
    [['symbol'=>'S4','price'=>100.0,'change_pct'=>9.0],
     ['symbol'=>'S0','price'=>100.0,'change_pct'=>8.5],
     ['symbol'=>'S3','price'=>100.0,'change_pct'=>8.0],
     ['symbol'=>'S2','price'=>100.0,'change_pct'=>7.0],
     ['symbol'=>'S1','price'=>100.0,'change_pct'=>5.0],
     ['symbol'=>'S5','price'=>100.0,'change_pct'=>-1.0]],
    // iter 5: S0 at rank 1
    [['symbol'=>'S0','price'=>100.0,'change_pct'=>9.5],
     ['symbol'=>'S4','price'=>100.0,'change_pct'=>9.0],
     ['symbol'=>'S3','price'=>100.0,'change_pct'=>8.0],
     ['symbol'=>'S2','price'=>100.0,'change_pct'=>7.0],
     ['symbol'=>'S1','price'=>100.0,'change_pct'=>5.0],
     ['symbol'=>'S5','price'=>100.0,'change_pct'=>-1.0]],
];
foreach ($upIters as $row) {
    $o = prakashTestRun($row, $statePath, $historyPath, $rankHistoryPath, $topSeenPath);
}
prakashTestAssert(!empty($o['momentum_up']), 'S0 fires upward momentum at iteration 5');
$s0 = null;
foreach ($o['momentum_up'] as $m) { if ($m['symbol'] === 'S0.NS') { $s0 = $m; break; } }
prakashTestAssert($s0 !== null, 'S0.NS in momentum_up');
prakashTestAssert($s0['current_rank'] === 1, 'S0.NS current_rank=1');
prakashTestAssert($s0['momentum'] === 4, 'S0.NS momentum=4 (5→4→3→2→1 = +4)');
prakashTestAssert(($o['buy_recommendation']['symbol'] ?? '') === 'S0', 'buy_recommendation = S0');
prakashTestAssert(($o['buy_recommendation']['reason'] ?? '') === 'Sustained Upward Momentum', 'buy reason = Sustained Upward Momentum');
prakashTestAssert(($o['buy_recommendation']['previous_rank'] ?? null) === 5, 'buy previous_rank = 5 (oldest of the 5)');
$buySyms = array_column($o['buy_box'], 'symbol');
prakashTestAssert(in_array('S0.NS', $buySyms, true), 'S0.NS in buy_box');

echo "── Non-monotonic ranks do NOT trigger momentum ─────────────────\n";
prakashTestReset($statePath, $historyPath, $rankHistoryPath, $topSeenPath);
$nonMonotonic = [
    [['symbol'=>'A','price'=>100.0,'change_pct'=>5.0],['symbol'=>'B','price'=>100.0,'change_pct'=>1.0]], // B rank 2
    [['symbol'=>'A','price'=>100.0,'change_pct'=>5.0],['symbol'=>'B','price'=>100.0,'change_pct'=>3.0]], // B rank 2 (no change)
    [['symbol'=>'A','price'=>100.0,'change_pct'=>5.0],['symbol'=>'B','price'=>100.0,'change_pct'=>4.0]], // B rank 2
    [['symbol'=>'A','price'=>100.0,'change_pct'=>5.0],['symbol'=>'B','price'=>100.0,'change_pct'=>6.0]], // B rank 1
    [['symbol'=>'A','price'=>100.0,'change_pct'=>5.0],['symbol'=>'B','price'=>100.0,'change_pct'=>7.0]], // B rank 1
];
foreach ($nonMonotonic as $row) {
    $o = prakashTestRun($row, $statePath, $historyPath, $rankHistoryPath, $topSeenPath);
}
prakashTestAssert(empty($o['momentum_up']), 'no upward momentum (rank series 2,2,2,1,1 not strictly monotonic)');
prakashTestAssert(empty($o['momentum_down']), 'no downward momentum');

echo "── Target Hit status when live price reaches the target ────────\n";
prakashTestReset($statePath, $historyPath, $rankHistoryPath, $topSeenPath);
// Initial iteration: ABC at 100 → Buy recommendation, target = 101
$o1 = prakashTestRun([
    ['symbol' => 'ABC', 'price' => 100.0, 'change_pct' =>  2.0],
    ['symbol' => 'XYZ', 'price' => 200.0, 'change_pct' => -2.0],
], $statePath, $historyPath, $rankHistoryPath, $topSeenPath);
prakashTestAssert(($o1['daily_summary']['recommendations'][0]['status'] ?? '') === 'Active', 'ABC status=Active after initial iteration');
prakashTestAssert(($o1['daily_summary']['recommendations'][0]['achieved'] ?? false) === false, 'ABC achieved=false');

// Now ABC price moves up to 101.5 (target=101) — should flip to Target Hit.
$o2 = prakashTestRun([
    ['symbol' => 'ABC', 'price' => 101.5, 'change_pct' =>  3.5],
    ['symbol' => 'XYZ', 'price' => 200.0, 'change_pct' => -2.0],
], $statePath, $historyPath, $rankHistoryPath, $topSeenPath);
$entries = array_column($o2['daily_summary']['recommendations'], null, 'symbol');
prakashTestAssert(($entries['ABC']['status'] ?? '') === 'Target Hit', 'ABC status=Target Hit when live price ≥ target');
prakashTestAssert(($entries['ABC']['achieved'] ?? false) === true, 'ABC achieved=true');
prakashTestAssert(($entries['ABC']['achieved_price'] ?? null) === 101.5, 'ABC achieved_price=101.5');

// Entry/target price must not move once locked in.
prakashTestAssert(($entries['ABC']['entry_price']  ?? null) === 100.0, 'ABC entry_price locked at 100.0');
prakashTestAssert(($entries['ABC']['target_price'] ?? null) === 101.0, 'ABC target_price locked at 101.0');

echo "── Sell-side target Hit when price falls to target ─────────────\n";
prakashTestReset($statePath, $historyPath, $rankHistoryPath, $topSeenPath);
$o1 = prakashTestRun([
    ['symbol' => 'ABC', 'price' => 100.0, 'change_pct' =>  2.0],
    ['symbol' => 'XYZ', 'price' => 200.0, 'change_pct' => -2.0],
], $statePath, $historyPath, $rankHistoryPath, $topSeenPath);
// XYZ = Sell, target = 200 × 0.99 = 198
// Push XYZ price to 197.5 (≤ 198)
$o2 = prakashTestRun([
    ['symbol' => 'ABC', 'price' => 100.0, 'change_pct' =>  2.0],
    ['symbol' => 'XYZ', 'price' => 197.5, 'change_pct' => -3.2],
], $statePath, $historyPath, $rankHistoryPath, $topSeenPath);
$entries = array_column($o2['daily_summary']['recommendations'], null, 'symbol');
prakashTestAssert(($entries['XYZ']['side'] ?? '') === 'Sell', 'XYZ is a Sell recommendation');
prakashTestAssert(($entries['XYZ']['status'] ?? '') === 'Target Hit', 'XYZ status=Target Hit when Sell target reached (price ≤ target)');
prakashTestAssert(($entries['XYZ']['target_price'] ?? null) === 198.0, 'XYZ target_price = 198.0 (entry 200 × 0.99)');

echo "── No new box entries for stocks already in daily file ─────────\n";
prakashTestReset($statePath, $historyPath, $rankHistoryPath, $topSeenPath);
$o1 = prakashTestRun([
    ['symbol' => 'A', 'price' => 100.0, 'change_pct' =>  2.0],
    ['symbol' => 'B', 'price' => 100.0, 'change_pct' => -2.0],
], $statePath, $historyPath, $rankHistoryPath, $topSeenPath);
$countAfter1 = count($o1['daily_summary']['recommendations']);
$o2 = prakashTestRun([
    ['symbol' => 'A', 'price' => 100.0, 'change_pct' =>  2.0],
    ['symbol' => 'B', 'price' => 100.0, 'change_pct' => -2.0],
    ['symbol' => 'C', 'price' => 100.0, 'change_pct' =>  3.0],
], $statePath, $historyPath, $rankHistoryPath, $topSeenPath);
$countAfter2 = count($o2['daily_summary']['recommendations']);
prakashTestAssert($countAfter2 === $countAfter1 + 1, 'newly introduced C gets one new entry, A and B are not duplicated');

echo "── Recommendation details include target_price and status ──────\n";
prakashTestReset($statePath, $historyPath, $rankHistoryPath, $topSeenPath);
$o = prakashTestRun([
    ['symbol' => 'A', 'price' => 100.0, 'change_pct' =>  2.0],
    ['symbol' => 'B', 'price' => 100.0, 'change_pct' => -2.0],
], $statePath, $historyPath, $rankHistoryPath, $topSeenPath);
$entries = array_column($o['daily_summary']['recommendations'], null, 'symbol');
foreach (['A', 'B'] as $sym) {
    prakashTestAssert(isset($entries[$sym]['entry_price']),  "$sym has entry_price");
    prakashTestAssert(isset($entries[$sym]['target_price']), "$sym has target_price");
    prakashTestAssert(isset($entries[$sym]['status']),       "$sym has status");
    prakashTestAssert(isset($entries[$sym]['entry_time']),   "$sym has entry_time");
}

echo "\nAll Prakash recommendation tests passed ✓\n";

// ─────────────────────────────────────────────────────────────
// Regression: TWO independently-qualifying momentum stocks must BOTH end
// up as separately tracked daily recommendations (not just the single
// strongest "headline" pick). This was a real gap — the box/confidence
// score were computed for every qualifying stock but only ever the top
// one was actually registered into the tracked daily file.
// ─────────────────────────────────────────────────────────────
echo "── Multiple concurrent momentum picks are ALL tracked, not just one ──\n";
prakashTestReset($statePath, $historyPath, $rankHistoryPath, $topSeenPath);
$peers = ['F1' => 10.0, 'F2' => 9.0, 'F3' => 8.0, 'F4' => 7.0, 'F5' => 6.0, 'F6' => 5.0, 'F7' => 4.0, 'F8' => 3.0, 'F9' => 0.5];
$momSeq  = [2.2, 3.4, 4.6, 5.8, 7.1];  // strictly improving ranks, never touches top or bottom
$dropSeq = [6.8, 5.6, 4.4, 3.2, 1.9];  // strictly worsening ranks, never touches top or bottom
$last = null;
for ($i = 0; $i < 5; $i++) {
    $stocks = [];
    foreach ($peers as $sym => $chg) $stocks[] = ['symbol' => $sym, 'price' => 100.0, 'change_pct' => $chg];
    $stocks[] = ['symbol' => 'MOM', 'price' => 100.0, 'change_pct' => $momSeq[$i]];
    $stocks[] = ['symbol' => 'DROP', 'price' => 100.0, 'change_pct' => $dropSeq[$i]];
    $last = prakashTestRun($stocks, $statePath, $historyPath, $rankHistoryPath, $topSeenPath);
}
$entries = array_column($last['daily_summary']['recommendations'], null, 'symbol');
prakashTestAssert(isset($entries['MOM']), 'MOM (sustained upward momentum) is a separately tracked recommendation');
prakashTestAssert(($entries['MOM']['side'] ?? '') === 'Buy', 'MOM tracked as Buy');
prakashTestAssert(isset($entries['DROP']), 'DROP (sustained downward momentum) is a separately tracked recommendation');
prakashTestAssert(($entries['DROP']['side'] ?? '') === 'Sell', 'DROP tracked as Sell');
prakashTestAssert(isset($entries['F1']) && $entries['F1']['side'] === 'Buy', 'F1 (initial top gainer) still tracked as Buy');
prakashTestAssert(isset($entries['F9']) && $entries['F9']['side'] === 'Sell', 'F9 (initial bottom) still tracked as Sell');
prakashTestAssert(count($last['daily_summary']['recommendations']) === 4, 'Exactly 4 concurrent recommendations tracked today (initial Buy+Sell, momentum Buy+Sell)');
prakashTestAssert(($entries['MOM']['confidence'] ?? null) !== null && $entries['MOM']['confidence'] > 0, 'MOM has a non-zero confidence score attached');
prakashTestAssert(($entries['DROP']['confidence'] ?? null) !== null && $entries['DROP']['confidence'] > 0, 'DROP has a non-zero confidence score attached');

// ─────────────────────────────────────────────────────────────
// New-entry detection: a stock that jumps straight into the Top 10
// Gainers for the first time (without needing 5 iterations of sustained
// momentum) should fire a Buy signal immediately, per spec item 3.
// ─────────────────────────────────────────────────────────────
echo "\n── New Top-10 entry fires an immediate Buy signal ──────────────\n";
prakashTestReset($statePath, $historyPath, $rankHistoryPath, $topSeenPath);
// Iteration 1: establish a baseline of 12 stocks (so Top-10 is meaningfully
// smaller than the full universe) with NEWCOMER outside the Top 10.
$baseline = [];
for ($i = 1; $i <= 11; $i++) {
    $baseline[] = ['symbol' => "P$i", 'price' => 100.0, 'change_pct' => 9.0 - $i * 0.5];
}
$baseline[] = ['symbol' => 'NEWCOMER', 'price' => 100.0, 'change_pct' => -8.0]; // outside Top 10
$baseline[] = ['symbol' => 'FLOOR', 'price' => 100.0, 'change_pct' => -8.5];   // stays the actual bottom pick, not NEWCOMER
prakashTestRun($baseline, $statePath, $historyPath, $rankHistoryPath, $topSeenPath);

// Iteration 2: NEWCOMER jumps straight into the Top 10 (e.g. rank 5) —
// should fire immediately, without waiting for 5 sustained iterations.
$jump = $baseline;
foreach ($jump as &$s) { if ($s['symbol'] === 'NEWCOMER') $s['change_pct'] = 6.8; } // now ranks ~5th (between P4=7.0 and P5=6.5)
unset($s);
$o = prakashTestRun($jump, $statePath, $historyPath, $rankHistoryPath, $topSeenPath);
$entries = array_column($o['daily_summary']['recommendations'], null, 'symbol');
prakashTestAssert(isset($entries['NEWCOMER']), 'NEWCOMER is tracked the moment it enters the Top 10');
prakashTestAssert(($entries['NEWCOMER']['side'] ?? '') === 'Buy', 'NEWCOMER tracked as Buy (entered Top 10 Gainers)');
prakashTestAssert(str_contains($entries['NEWCOMER']['reason'] ?? '', 'New Top-10 Entry'), 'NEWCOMER reason tagged as a new Top-10 entry');

// ─────────────────────────────────────────────────────────────
// Bad-tick guard must also apply to momentum/new-entry candidates, not
// just the initial top/bottom pick. These candidates carry
// 'percentage_change' (not 'change_pct') internally — verify the
// plausibility check still catches an implausible change_pct (>12%
// implied move) for them, exactly as it would for a raw top/bottom pick.
// ─────────────────────────────────────────────────────────────
echo "\n── Bad-tick guard applies to momentum/new-entry picks too ──────\n";
prakashTestReset($statePath, $historyPath, $rankHistoryPath, $topSeenPath);
$baseline2 = [];
for ($i = 1; $i <= 11; $i++) {
    $baseline2[] = ['symbol' => "Q$i", 'price' => 100.0, 'change_pct' => 9.0 - $i * 0.5];
}
$baseline2[] = ['symbol' => 'GLITCH', 'price' => 100.0, 'change_pct' => -8.0];
$baseline2[] = ['symbol' => 'FLOOR2', 'price' => 100.0, 'change_pct' => -8.5]; // stays the actual bottom pick, not GLITCH
prakashTestRun($baseline2, $statePath, $historyPath, $rankHistoryPath, $topSeenPath);
// GLITCH "jumps" into the Top 10 but with an implausible >12% implied move.
$jump2 = $baseline2;
foreach ($jump2 as &$s) { if ($s['symbol'] === 'GLITCH') { $s['change_pct'] = 15.0; $s['price'] = 130.0; } } // >12% implied move — rejected as a bad tick, even though it would otherwise rank #1
unset($s);
$o2 = prakashTestRun($jump2, $statePath, $historyPath, $rankHistoryPath, $topSeenPath);
$entries2 = array_column($o2['daily_summary']['recommendations'], null, 'symbol');
prakashTestAssert(!isset($entries2['GLITCH']), 'GLITCH (implausible tick) is rejected and NOT tracked despite entering the Top 10');

echo "\nAll extended Prakash regression tests passed ✓\n";
