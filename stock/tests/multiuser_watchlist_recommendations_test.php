<?php
require __DIR__ . '/../app/config.php';
require __DIR__ . '/../app/users.php';
require __DIR__ . '/../app/prakash_recommendations.php';
require __DIR__ . '/../app/ai_recommendations.php';

function assertTrue(bool $cond, string $msg): void
{
    if (!$cond) {
        fwrite(STDERR, "FAIL: $msg\n");
        exit(1);
    }
    fwrite(STDOUT, "  ok: $msg\n");
}

$testDir = __DIR__ . '/tmp_test_multiuser';
if (!is_dir($testDir)) mkdir($testDir, 0755, true);

$storageBase = getStorageBasePath();
$usersDataDir = getUserDataDir();
if (!is_dir($usersDataDir)) mkdir($usersDataDir, 0755, true);

foreach (glob($usersDataDir . '/*_watchlist.json') ?: [] as $file) unlink($file);
foreach (glob($usersDataDir . '/*_prakash_daily_*.json') ?: [] as $file) unlink($file);
foreach (glob($usersDataDir . '/*_ai_daily_*.json') ?: [] as $file) unlink($file);
foreach (glob($usersDataDir . '/*_prakash_state.json') ?: [] as $file) unlink($file);
foreach (glob($usersDataDir . '/*_ai_state.json') ?: [] as $file) unlink($file);

saveUserWatchlist('alice', ['RELIANCE.NS', 'TCS.NS']);
saveUserWatchlist('bob', ['INFY.NS']);

$aliceWatchlist = getUserWatchlist('alice');
$bobWatchlist = getUserWatchlist('bob');
assertTrue($aliceWatchlist === ['RELIANCE.NS', 'TCS.NS'], 'alice gets her own watchlist');
assertTrue($bobWatchlist === ['INFY.NS'], 'bob gets his own watchlist');
assertTrue(getUserWatchlistPath('alice') !== getUserWatchlistPath('bob'), 'watchlist files are isolated per user');

$charlieMeta = getUserWatchlistMeta('charlie', false);
$emptyFallback = getUserWatchlist('charlie');
assertTrue(count($emptyFallback) > 0, 'empty watchlist falls back to a non-empty list');
assertTrue(in_array('RELIANCE.NS', $emptyFallback, true), 'empty watchlist fallback uses NIFTY50-style symbols');
assertTrue($charlieMeta['used_default_fallback'] === true, 'watchlist metadata marks default fallback as active');
assertTrue($charlieMeta['watchlist_source'] === 'default', 'watchlist metadata reports the default source');

$stocks = [
    ['symbol' => 'AAA', 'price' => 100.0, 'change_pct' => 2.5, 'signal' => 'Buy', 'confidence' => 80, 'momentum_score' => 10],
    ['symbol' => 'BBB', 'price' => 95.0, 'change_pct' => -1.5, 'signal' => 'Sell', 'confidence' => 75, 'momentum_score' => -8],
];

$prakashAlice = buildPrakashRecommendations($stocks, null, null, 'alice');
$aiAlice = buildAiRecommendations($stocks, null, null, 'alice');
$prakashBob = buildPrakashRecommendations($stocks, null, null, 'bob');
$aiBob = buildAiRecommendations($stocks, null, null, 'bob');

assertTrue(file_exists($prakashAlice['daily_file']), 'Prakash daily file created for alice');
assertTrue(file_exists($aiAlice['daily_file']), 'AI daily file created for alice');
assertTrue(file_exists($prakashBob['daily_file']), 'Prakash daily file created for bob');
assertTrue(file_exists($aiBob['daily_file']), 'AI daily file created for bob');
assertTrue($prakashAlice['daily_file'] !== $prakashBob['daily_file'], 'Prakash recommendations are isolated per user');
assertTrue($aiAlice['daily_file'] !== $aiBob['daily_file'], 'AI recommendations are isolated per user');
assertTrue(($prakashAlice['daily_summary']['total'] ?? 0) > 0, 'Prakash daily summary has entries for alice');
assertTrue(($aiAlice['daily_summary']['total'] ?? 0) > 0, 'AI daily summary has entries for alice');

fwrite(STDOUT, "All multi-user watchlist and recommendation tests passed ✓\n");
