<?php
declare(strict_types=1);
/**
 * api/alerts.php — price alert checking (above/below trigger conditions).
 */

function checkAlerts(): array
{
    if (!file_exists(ALERT_FILE)) return ['triggered' => []];
    $alerts    = json_decode(file_get_contents(ALERT_FILE), true) ?? [];
    $triggered = [];
    $changed   = false;
    foreach ($alerts as &$a) {
        if ($a['triggered']) continue;
        $q = yahooQuote($a['symbol'] . (str_ends_with($a['symbol'],'.NS')?'':'.NS'));
        if (!$q) continue;
        $price = $q['regularMarketPrice'] ?? 0;
        $hit = ($a['condition'] === 'above' && $price >= $a['price'])
            || ($a['condition'] === 'below' && $price <= $a['price']);
        if ($hit) {
            $a['triggered'] = true; $a['triggered_at'] = time(); $a['triggered_price'] = $price;
            $triggered[] = $a;
            $changed = true;
        }
    }
    if ($changed) file_put_contents(ALERT_FILE, json_encode($alerts));
    return ['triggered' => $triggered, 'total' => count($alerts), 'pending' => count(array_filter($alerts, fn($a)=>!$a['triggered']))];
}

