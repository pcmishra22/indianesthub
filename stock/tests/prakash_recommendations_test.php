<?php
require __DIR__ . '/../app/config.php';
require __DIR__ . '/../app/prakash_recommendations.php';

$tmpDir = __DIR__ . '/tmp_test';
if (!is_dir($tmpDir)) mkdir($tmpDir, 0755, true);
$statePath = $tmpDir . '/state.json';
$historyPath = $tmpDir . '/history.json';
@unlink($statePath);
@unlink($historyPath);

$firstStocks = [
    ['symbol' => 'AAA', 'price' => 100.0, 'change_pct' => 2.5],
    ['symbol' => 'BBB', 'price' => 200.0, 'change_pct' => 1.0],
    ['symbol' => 'CCC', 'price' => 300.0, 'change_pct' => -1.5],
];

$first = buildPrakashRecommendations($firstStocks, $statePath, $historyPath);
if (($first['top_gainer']['symbol'] ?? '') !== 'AAA') {
    fwrite(STDERR, "Expected top gainer AAA\n");
    exit(1);
}
if (($first['top_loser']['symbol'] ?? '') !== 'CCC') {
    fwrite(STDERR, "Expected top loser CCC\n");
    exit(1);
}

$secondStocks = [
    ['symbol' => 'AAA', 'price' => 100.0, 'change_pct' => 0.4],
    ['symbol' => 'BBB', 'price' => 200.0, 'change_pct' => 3.1],
    ['symbol' => 'CCC', 'price' => 300.0, 'change_pct' => -2.0],
];

$second = buildPrakashRecommendations($secondStocks, $statePath, $historyPath);
if (($second['rank_movement_buy']['symbol'] ?? '') !== 'BBB') {
    fwrite(STDERR, "Expected rank movement buy BBB\n");
    exit(1);
}
if (($second['rank_movement_sell']['symbol'] ?? '') !== 'AAA') {
    fwrite(STDERR, "Expected rank movement sell AAA\n");
    exit(1);
}

$history = json_decode(file_get_contents($historyPath), true);
if (!is_array($history) || count($history) < 4) {
    fwrite(STDERR, "Expected recommendation history entries\n");
    exit(1);
}

echo "Prakash recommendation tests passed\n";
