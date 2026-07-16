<?php
/**
 * Standalone end-to-end diagnostic for the City Import feature.
 *
 * Tests the EXACT same endpoints and logic as CityDataDiscoveryService,
 * with zero Laravel dependencies, so you can run it directly from SSH
 * and see exactly what's happening — no ambiguity, no swallowed errors.
 *
 * USAGE (from SSH, in your project root or anywhere with PHP + curl):
 *   php test-city-import.php
 *   php test-city-import.php "Mumbai"       (test a different city)
 *
 * SAFE TO DELETE after you're done — it doesn't touch your database or
 * write any files, it only makes outbound HTTP requests and prints results.
 *
 * If you'd rather run this via browser instead of SSH, upload it to your
 * public web root temporarily, visit it once, then DELETE IT — it has no
 * authentication and would otherwise expose your Mappls credentials to
 * anyone who finds the URL.
 */

// ---- CONFIG: fill this in, or export as an environment variable before running ----
$MAPPLS_API_KEY = getenv('MAPPLS_API_KEY') ?: 'PASTE_YOUR_STATIC_KEY_HERE';
// ------------------------------------------------------------------------------

$city = $argv[1] ?? 'lucknow';
$city = strtolower(trim($city));

function line($char = '-', $len = 70) { echo str_repeat($char, $len) . "\n"; }
function section($title) { echo "\n"; line('='); echo "  $title\n"; line('='); }

function httpRequest(string $method, string $url, array $options = []): array
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, $options['timeout'] ?? 20);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HEADER, false);

    $headers = $options['headers'] ?? [];
    if (!empty($headers)) {
        $formatted = [];
        foreach ($headers as $k => $v) {
            $formatted[] = "$k: $v";
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $formatted);
    }

    if (isset($options['form'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($options['form']));
    } elseif (isset($options['json'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($options['json']));
    }

    $body = curl_exec($ch);
    $errno = curl_errno($ch);
    $error = curl_error($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'ok' => $errno === 0,
        'curl_errno' => $errno,
        'curl_error' => $error,
        'status' => $status,
        'body' => $body,
    ];
}

echo "City Import End-to-End Diagnostic\n";
echo "Testing city: \"$city\", type: builder\n";
echo "PHP version: " . PHP_VERSION . "\n";
echo "curl extension: " . (extension_loaded('curl') ? 'available' : 'MISSING — install php-curl') . "\n";

// ============================================================
// TEST 1: Mappls — Static Key Search (Autosuggest API)
// ============================================================
section('TEST 1: Mappls — Static Key Search');

if ($MAPPLS_API_KEY === 'PASTE_YOUR_STATIC_KEY_HERE') {
    echo "SKIPPED — no key filled in at the top of this script.\n";
    echo "(This is fine if you haven't set up Mappls yet — the app will just use the free OSM fallback.)\n";
} else {
    // Lucknow's approximate coordinates, used as a location bias.
    $coords = '26.8467,80.9462';
    $url = 'https://atlas.mappls.com/api/places/search/json?' . http_build_query([
        'query' => 'real estate builders and developers',
        'region' => 'ind',
        'location' => $coords,
        'access_token' => $MAPPLS_API_KEY,
    ]);
    echo "Requesting: " . preg_replace('/access_token=[^&]+/', 'access_token=***', $url) . "\n";
    $result = httpRequest('GET', $url);

    if (!$result['ok']) {
        echo "❌ CONNECTION FAILED (curl errno {$result['curl_errno']}): {$result['curl_error']}\n";
        echo "   -> Your server cannot reach atlas.mappls.com at all. Check outbound firewall rules.\n";
    } else {
        echo "HTTP Status: {$result['status']}\n";
        echo "Raw response:\n{$result['body']}\n";
        $decoded = json_decode($result['body'], true);
        $count = count($decoded['suggestedLocations'] ?? []);
        if ($result['status'] === 200 && $count > 0) {
            echo "✅ SUCCESS — found $count result(s).\n";
        } elseif ($result['status'] === 200) {
            echo "⚠️  Request succeeded but found 0 results for this query/location.\n";
        } elseif ($result['status'] === 401 || $result['status'] === 403) {
            echo "❌ FAILED — HTTP {$result['status']}. The key was rejected — check it's correct, active, and\n";
            echo "   not restricted to a different domain/IP under Whitelisting in the Mappls Console.\n";
        } else {
            echo "❌ FAILED — HTTP {$result['status']}.\n";
        }
    }
}

// ============================================================
// TEST 2: Nominatim geocoding (OSM fallback path, step 1)
// ============================================================
section('TEST 2: OpenStreetMap — Nominatim Geocoding');

$nominatimUrl = 'https://nominatim.openstreetmap.org/search?' . http_build_query([
    'q' => $city . ', India',
    'format' => 'json',
    'limit' => 1,
    'addressdetails' => 1,
]);
echo "Requesting: $nominatimUrl\n";
$result = httpRequest('GET', $nominatimUrl, [
    'headers' => ['User-Agent' => 'IndianEstHub-CityImport/1.0 (contact: admin@indianesthub.com)'],
]);

$geocodedLat = null;
$geocodedLon = null;
if (!$result['ok']) {
    echo "❌ CONNECTION FAILED (curl errno {$result['curl_errno']}): {$result['curl_error']}\n";
    echo "   -> Your server cannot reach nominatim.openstreetmap.org. This is the most common cause of\n";
    echo "      'no candidates found' on shared hosting — check your outbound firewall / ask your host to\n";
    echo "      whitelist this domain.\n";
} else {
    echo "HTTP Status: {$result['status']}\n";
    echo "Raw response:\n{$result['body']}\n";
    $decoded = json_decode($result['body'], true);
    if ($result['status'] === 200 && !empty($decoded)) {
        $geocodedLat = $decoded[0]['lat'] ?? null;
        $geocodedLon = $decoded[0]['lon'] ?? null;
        echo "✅ SUCCESS — geocoded to lat=$geocodedLat, lon=$geocodedLon\n";
    } elseif ($result['status'] !== 200) {
        echo "❌ FAILED — HTTP {$result['status']} (not 200), so this is a request-level failure — likely blocked\n";
        echo "   by a firewall/proxy/rate-limit rather than genuinely finding no match. The raw response above\n";
        echo "   should say why (e.g. an allowlist/robots message, a rate-limit notice, etc).\n";
    } else {
        echo "❌ FAILED — request succeeded (HTTP 200) but Nominatim returned no match for \"$city\".\n";
    }
}

// ============================================================
// TEST 3: Overpass query (OSM fallback path, step 2)
// ============================================================
section('TEST 3: OpenStreetMap — Overpass Business Search');

$lat = $geocodedLat ?: '26.8467'; // fall back to Lucknow's known coordinates if geocoding failed
$lon = $geocodedLon ?: '80.9462';

function runOverpassQuery(string $label, string $query): void
{
    echo "\n--- $label ---\n";
    echo "Query:\n$query\n\n";
    echo "Requesting: https://overpass-api.de/api/interpreter ...\n";

    $result = httpRequest('POST', 'https://overpass-api.de/api/interpreter', [
        'headers' => [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'User-Agent' => 'IndianEstHub-CityImport/1.0 (contact: admin@indianesthub.com)',
        ],
        'form' => ['data' => $query],
        'timeout' => 25,
    ]);

    if (!$result['ok']) {
        echo "❌ CONNECTION FAILED (curl errno {$result['curl_errno']}): {$result['curl_error']}\n";
        echo "   -> Your server cannot reach overpass-api.de. Check outbound firewall / ask your host to\n";
        echo "      whitelist this domain.\n";
        return;
    }

    echo "HTTP Status: {$result['status']}\n";
    $bodyPreview = strlen($result['body']) > 2000 ? substr($result['body'], 0, 2000) . "\n... (truncated)" : $result['body'];
    echo "Raw response:\n$bodyPreview\n";

    if ($result['status'] === 504) {
        echo "❌ TIMEOUT (HTTP 504) — the public Overpass server is overloaded/slow for this query. Usually\n";
        echo "   transient; try again in a minute. This is a request-level failure, not \"no data\".\n";
        return;
    }
    if ($result['status'] === 429) {
        echo "❌ RATE LIMITED (HTTP 429) — too many requests recently. Wait a minute and try again.\n";
        return;
    }

    $decoded = json_decode($result['body'], true);
    $count = count($decoded['elements'] ?? []);
    if ($result['status'] === 200 && $count > 0) {
        echo "✅ SUCCESS — found $count element(s) in OpenStreetMap.\n";
    } elseif ($result['status'] === 200) {
        echo "⚠️  Request succeeded but found 0 matches for this query.\n";
    } else {
        echo "❌ FAILED — HTTP {$result['status']}.\n";
    }
}

echo "Using coordinates: lat=$lat, lon=$lon\n";

// Phase 1: cheap tag-only query at the full 15km radius.
$tagQuery = "[out:json][timeout:25];\n(\n"
    . "  nwr[\"office\"=\"estate_agent\"](around:15000,$lat,$lon);\n"
    . "  nwr[\"amenity\"=\"real_estate_agency\"](around:15000,$lat,$lon);\n"
    . "  nwr[\"shop\"=\"real_estate_agency\"](around:15000,$lat,$lon);\n"
    . ");\nout center;";
runOverpassQuery('Phase 1: Tag-only query (15km radius, fast)', $tagQuery);

// Phase 2: pricier name-regex query at a smaller 5km radius (only run in the
// real app if Phase 1 finds nothing — run here too so you can see both).
$nameQuery = "[out:json][timeout:25];\n(\n"
    . "  nwr[\"name\"~\"builders|developers|realty|properties|infra|construction\",i](around:5000,$lat,$lon);\n"
    . ");\nout center;";
runOverpassQuery('Phase 2: Name-regex query (5km radius, slower)', $nameQuery);

// ============================================================
section('SUMMARY');
echo "Send me everything this script printed and I can tell you exactly what's happening.\n";
