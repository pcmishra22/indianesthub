<?php
declare(strict_types=1);
/**
 * api/news.php — RSS news aggregation from Economic Times / Moneycontrol,
 * with a simple bullish/bearish keyword heuristic.
 */

function apiNews(): array
{
    $cacheFile = STORAGE . '/news_cache.json';
    if (file_exists($cacheFile)) {
        $cached = json_decode(file_get_contents($cacheFile), true);
        if ($cached && (time() - ($cached['ts'] ?? 0)) < 600) return $cached;
    }

    $feeds = [
        ['url' => 'https://economictimes.indiatimes.com/markets/stocks/rssfeeds/2146842.cms', 'label' => 'Economic Times'],
        ['url' => 'https://www.moneycontrol.com/rss/MCtopnews.xml', 'label' => 'Moneycontrol'],
    ];

    $newsItems = [];
    foreach ($feeds as $feed) {
        $xml = httpGet($feed['url'], 10);
        if (!$xml) continue;
        // Strip namespace prefixes
        $xml = preg_replace('/[a-z0-9]+:[a-z0-9]+=/i', '=', $xml);
        $xml = preg_replace('/<([a-z0-9]+):([a-z0-9]+)/i', '<$2', $xml);
        $xml = preg_replace('/<\/([a-z0-9]+):([a-z0-9]+)/i', '</$2', $xml);

        libxml_use_internal_errors(true);
        $obj = simplexml_load_string($xml);
        if (!$obj) continue;

        $items = $obj->channel->item ?? [];
        foreach ($items as $item) {
            $title = strip_tags((string)($item->title ?? ''));
            $desc  = strip_tags((string)($item->description ?? ''));
            if (!$title) continue;

            // Simple impact heuristic
            $t = strtolower($title . ' ' . $desc);
            $impact = 'Neutral';
            if (preg_match('/surge|rally|rise|gain|bull|positive|profit|revenue|record|high|breakout|buy/i', $t)) $impact = 'Bullish';
            elseif (preg_match('/fall|drop|crash|loss|bear|decline|weak|concern|risk|sell|warning|cut/i', $t)) $impact = 'Bearish';

            // Extract stock mentions (CAPS words 2-15 chars)
            preg_match_all('/\b([A-Z]{2,15})\b/', $title, $m);
            $nseWords = ['NSE', 'BSE', 'IPO', 'FII', 'DII', 'RBI', 'SEBI', 'GDP', 'CPI', 'EMI', 'SBI', 'LIC', 'HDFC', 'ICICI'];
            $stocks = array_unique(array_filter($m[1] ?? [], fn($w) => strlen($w) >= 3 && !in_array($w, $nseWords)));

            $newsItems[] = [
                'headline'       => $title,
                'summary'        => $desc ? substr($desc, 0, 200) . '...' : 'Read full article for details.',
                'impact'         => $impact,
                'sector'         => 'Markets',
                'stocks_affected'=> array_values(array_slice($stocks, 0, 4)),
                'source'         => $feed['label'],
            ];
            if (count($newsItems) >= 16) break 2;
        }
    }

    // Fallback if no news fetched
    if (!$newsItems) {
        $newsItems = [
            ['headline' => 'Markets open for trading', 'summary' => 'Indian equity markets are open. Check NSE/BSE for live updates.', 'impact' => 'Neutral', 'sector' => 'Markets', 'stocks_affected' => [], 'source' => 'System'],
        ];
    }

    $result = ['news' => $newsItems, 'ts' => time(), 'source' => 'RSS (free)'];
    if (!is_dir(STORAGE)) mkdir(STORAGE, 0755, true);
    file_put_contents($cacheFile, json_encode($result));
    return $result;
}

