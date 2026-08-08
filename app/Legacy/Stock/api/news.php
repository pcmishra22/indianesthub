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

            // pubDate (for real "latest first" ordering across BOTH feeds
            // merged together — the old code just capped at 16 items in
            // whatever order the two feeds happened to be concatenated in,
            // which is not the same as chronological). Fall back to "now"
            // if a feed omits it, so a dateless item doesn't wrongly sort
            // to the very top or bottom.
            $pubDateRaw = (string)($item->pubDate ?? '');
            $pubTs = $pubDateRaw ? strtotime($pubDateRaw) : false;
            if ($pubTs === false) $pubTs = time();

            $link = trim((string)($item->link ?? ''));

            $newsItems[] = [
                'headline'       => $title,
                // Full RSS teaser text, untruncated — this is already just
                // the publisher's own short summary/teaser (that's what an
                // RSS <description> is), not the full article body, so
                // showing it in full isn't reproducing the article itself.
                // The "Read full article" link goes to the original for
                // the actual story.
                'summary'        => $desc ?: 'Read the full article for details.',
                'impact'         => $impact,
                'sector'         => 'Markets',
                'stocks_affected'=> array_values(array_slice($stocks, 0, 4)),
                'source'         => $feed['label'],
                'link'           => $link,
                'pub_ts'         => $pubTs,
            ];
            if (count($newsItems) >= 40) break 2;
        }
    }

    // Latest first, across both feeds combined.
    usort($newsItems, fn($a, $b) => $b['pub_ts'] <=> $a['pub_ts']);
    $newsItems = array_slice($newsItems, 0, 24);

    // Fallback if no news fetched
    if (!$newsItems) {
        $newsItems = [
            ['headline' => 'Markets open for trading', 'summary' => 'Indian equity markets are open. Check NSE/BSE for live updates.', 'impact' => 'Neutral', 'sector' => 'Markets', 'stocks_affected' => [], 'source' => 'System', 'link' => '', 'pub_ts' => time()],
        ];
    }

    $result = ['news' => $newsItems, 'ts' => time(), 'source' => 'RSS (free)'];
    if (!is_dir(STORAGE)) mkdir(STORAGE, 0755, true);
    file_put_contents($cacheFile, json_encode($result));
    return $result;
}

/**
 * apiNewsFull($link) — fetches the original article page and extracts its
 * body text (readability-lite: strip nav/script/ad noise, pick the element
 * with the most substantial <p> content, join those paragraphs). Used by
 * the News tab so the featured panel can show the full story instead of
 * just the short RSS teaser.
 *
 * Restricted to the two feed domains only — this endpoint fetches a
 * caller-supplied URL, so without a domain allowlist it would be an open
 * fetch proxy.
 *
 * Note: unlike the RSS teaser (the publisher's own short summary), this
 * reproduces the full article body on our own pages. That's more
 * copyright/ToS exposure for the site than a teaser + outbound link,
 * since it can substitute for visiting the source. Worth keeping an eye
 * on if ET/Moneycontrol object.
 */
function apiNewsFull(string $link): array
{
    $host = parse_url($link, PHP_URL_HOST) ?: '';
    $allowed = ['economictimes.indiatimes.com', 'www.moneycontrol.com', 'moneycontrol.com'];
    $hostOk = false;
    foreach ($allowed as $a) {
        if ($host === $a || str_ends_with($host, '.' . $a)) { $hostOk = true; break; }
    }
    if (!$link || !$hostOk) {
        return ['ok' => false, 'error' => 'Unsupported or missing article URL'];
    }

    $cacheKey  = 'news_full_' . md5($link) . '.json';
    $cacheFile = STORAGE . '/' . $cacheKey;
    if (file_exists($cacheFile)) {
        $cached = json_decode(file_get_contents($cacheFile), true);
        // Articles don't change once published — cache for a full day.
        if ($cached && (time() - ($cached['ts'] ?? 0)) < 86400) return $cached;
    }

    $html = httpGet($link, 12);
    if (!$html) {
        return ['ok' => false, 'error' => 'Could not fetch article'];
    }

    $text = extractArticleText($html);
    if (!$text) {
        return ['ok' => false, 'error' => 'Could not extract article text'];
    }

    $result = ['ok' => true, 'text' => $text, 'ts' => time()];
    if (!is_dir(STORAGE)) mkdir(STORAGE, 0755, true);
    file_put_contents($cacheFile, json_encode($result));
    return $result;
}

/**
 * extractArticleText($html) — very small readability-lite extractor:
 * drop obvious noise tags, then score every <p>'s parent by combined
 * paragraph text length and pick the highest-scoring container.
 * Good enough for standard news article markup (ET/Moneycontrol both
 * use a single main content div); not a general-purpose parser.
 */
function extractArticleText(string $html): string
{
    libxml_use_internal_errors(true);
    $doc = new DOMDocument();
    $doc->loadHTML('<?xml encoding="utf-8" ?>' . $html);
    libxml_clear_errors();

    $xpath = new DOMXPath($doc);
    foreach ($xpath->query('//script|//style|//nav|//header|//footer|//aside|//iframe|//form|//noscript') as $node) {
        $node->parentNode?->removeChild($node);
    }

    $paragraphs = $xpath->query('//p');
    $scores = []; // spl_object_id(parent) => [score, parent]
    foreach ($paragraphs as $p) {
        $txt = trim($p->textContent);
        $len = strlen($txt);
        if ($len < 40) continue; // skip captions/bylines/short noise
        $parent = $p->parentNode;
        if (!$parent) continue;
        $id = spl_object_id($parent);
        if (!isset($scores[$id])) $scores[$id] = ['score' => 0, 'node' => $parent];
        $scores[$id]['score'] += $len;
    }
    if (!$scores) return '';

    usort($scores, fn($a, $b) => $b['score'] <=> $a['score']);
    $best = $scores[0]['node'];

    $out = [];
    foreach ($xpath->query('.//p', $best) as $p) {
        $txt = trim(preg_replace('/\s+/', ' ', $p->textContent));
        if (strlen($txt) < 40) continue;
        // Skip common boilerplate lines that leak in from around articles.
        if (preg_match('/^(also read|disclaimer|download the economic times|catch all the|subscribe to|follow us)/i', $txt)) continue;
        $out[] = $txt;
    }
    return trim(implode("\n\n", array_unique($out)));
}

