<?php
declare(strict_types=1);

function getStorageBasePath(): string
{
    $preferred = defined('STORAGE') ? STORAGE : dirname(__DIR__) . '/storage';
    if (is_dir($preferred) && is_writable($preferred)) return $preferred;
    if (!is_dir($preferred) && @mkdir($preferred, 0755, true)) return $preferred;

    $fallback = sys_get_temp_dir() . '/stock_storage';
    if (!is_dir($fallback) && @mkdir($fallback, 0755, true)) return $fallback;
    if (is_dir($fallback) && is_writable($fallback)) return $fallback;
    return $preferred;
}

function getUsersStorePath(): string
{
    return getStorageBasePath() . '/users.json';
}

function getUserDataDir(): string
{
    $dir = getStorageBasePath() . '/users_data';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    return $dir;
}

function getUserWatchlistPath(string $username): string
{
    $safe = preg_replace('/[^a-z0-9._-]/i', '_', trim($username));
    return getUserDataDir() . '/' . ($safe ?: 'default') . '_watchlist.json';
}

function getUserRecommendationStatePath(string $username): string
{
    $safe = preg_replace('/[^a-z0-9._-]/i', '_', trim($username));
    return getUserDataDir() . '/' . ($safe ?: 'default') . '_prakash_state.json';
}

function getUserRecommendationHistoryPath(string $username): string
{
    $safe = preg_replace('/[^a-z0-9._-]/i', '_', trim($username));
    return getUserDataDir() . '/' . ($safe ?: 'default') . '_prakash_history.json';
}

function getUserAiRecommendationStatePath(string $username): string
{
    $safe = preg_replace('/[^a-z0-9._-]/i', '_', trim($username));
    return getUserDataDir() . '/' . ($safe ?: 'default') . '_ai_state.json';
}

function getUserAiRecommendationHistoryPath(string $username): string
{
    $safe = preg_replace('/[^a-z0-9._-]/i', '_', trim($username));
    return getUserDataDir() . '/' . ($safe ?: 'default') . '_ai_history.json';
}

function getUserWatchlistCachePath(string $username): string
{
    $safe = preg_replace('/[^a-z0-9._-]/i', '_', trim($username));
    return getStorageBasePath() . '/' . ($safe ?: 'default') . '_watchlist_cache.json';
}

function getDefaultUserCredentials(): array
{
    global $USER, $PASS;
    return [
        'username' => trim((string)($USER ?? 'admin')),
        'password' => (string)($PASS ?? 'stockpass123'),
    ];
}

// Extra demo users beyond the primary DEMO_USER/DEMO_PASS, read from
// DEMO_USER_2/DEMO_PASS_2 .. DEMO_USER_5/DEMO_PASS_5 in .env. Each gets
// their own watchlist, Prakash/AI recommendation history, and success/
// failure rate — fully isolated per-username (see getUserWatchlistPath(),
// getUserRecommendationStatePath(), aiDailyFile(), etc.).
function getExtraDemoUsers(): array
{
    $extras = [];
    for ($i = 2; $i <= 5; $i++) {
        $extras[] = [
            'username' => getenv("DEMO_USER_{$i}") ?: '',
            'password' => getenv("DEMO_PASS_{$i}") ?: '',
        ];
    }
    return $extras;
}

function ensureUsersStore(): array
{
    $storePath = getUsersStorePath();
    if (file_exists($storePath)) {
        $decoded = json_decode((string)file_get_contents($storePath), true);
        if (is_array($decoded)) {
            $default = getDefaultUserCredentials();
            if (!isset($decoded['users'][$default['username']])) {
                $decoded['users'][$default['username']] = [
                    'username' => $default['username'],
                    'password' => password_hash($default['password'], PASSWORD_DEFAULT),
                    'role' => 'admin',
                ];
                saveUsersStore($decoded);
            }

            foreach (getExtraDemoUsers() as $extra) {
                $u = trim((string)($extra['username'] ?? ''));
                $p = trim((string)($extra['password'] ?? ''));
                if ($u !== '' && $p !== '' && !isset($decoded['users'][$u])) {
                    $decoded['users'][$u] = [
                        'username' => $u,
                        'password' => password_hash($p, PASSWORD_DEFAULT),
                        'role' => 'user',
                    ];
                }
            }
            saveUsersStore($decoded);
            return $decoded;
        }
    }

    $default = getDefaultUserCredentials();
    $store = [
        'users' => [
            $default['username'] => [
                'username' => $default['username'],
                'password' => password_hash($default['password'], PASSWORD_DEFAULT),
                'role' => 'admin',
            ],
        ],
    ];

    foreach (getExtraDemoUsers() as $extra) {
        $u = trim((string)($extra['username'] ?? ''));
        $p = trim((string)($extra['password'] ?? ''));
        if ($u !== '' && $p !== '') {
            $store['users'][$u] = [
                'username' => $u,
                'password' => password_hash($p, PASSWORD_DEFAULT),
                'role' => 'user',
            ];
        }
    }

    $base = getStorageBasePath();
    if (!is_dir($base)) mkdir($base, 0755, true);
    file_put_contents($storePath, json_encode($store, JSON_PRETTY_PRINT));
    return $store;
}

function saveUsersStore(array $store): void
{
    $base = getStorageBasePath();
    if (!is_dir($base)) mkdir($base, 0755, true);
    file_put_contents(getUsersStorePath(), json_encode($store, JSON_PRETTY_PRINT));
}

function authenticateUser(string $username, string $password): ?array
{
    $store = ensureUsersStore();
    $user = $store['users'][$username] ?? null;
    if (!$user) return null;
    $storedPassword = $user['password'] ?? '';
    if (password_verify($password, $storedPassword)) return $user;
    if ($storedPassword !== '' && hash_equals($storedPassword, $password)) return $user;
    return null;
}

function createUser(string $username, string $password, string $role = 'user'): ?array
{
    $username = trim($username);
    if ($username === '') return null;
    $store = ensureUsersStore();
    if (isset($store['users'][$username])) return null;
    $store['users'][$username] = [
        'username' => $username,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'role' => $role,
    ];
    saveUsersStore($store);
    return $store['users'][$username];
}

function getCurrentUser(): string
{
    return trim((string)($_SESSION['user'] ?? ''));
}

function getUserWatchlist(string $username): array
{
    $path = getUserWatchlistPath($username);
    if (!file_exists($path)) {
        $default = defined('WATCHLIST_SYMBOLS') ? WATCHLIST_SYMBOLS : [];
        $saved = [];
        if (!empty($default)) {
            $saved = array_values(array_unique($default));
        }
        file_put_contents($path, json_encode($saved));
        return $saved;
    }
    $decoded = json_decode((string)file_get_contents($path), true);
    return is_array($decoded) ? array_values(array_filter($decoded, fn($item) => is_string($item) && trim($item) !== '')) : [];
}

function saveUserWatchlist(string $username, array $watchlist): void
{
    $path = getUserWatchlistPath($username);
    $normalized = [];
    foreach ($watchlist as $item) {
        if (!is_string($item)) continue;
        $trimmed = trim($item);
        if ($trimmed === '') continue;
        $normalized[] = strtoupper($trimmed);
    }
    $normalized = array_values(array_unique($normalized));
    $base = getStorageBasePath();
    if (!is_dir($base)) mkdir($base, 0755, true);
    file_put_contents($path, json_encode($normalized));
}

function getUserRecommendationsStatePath(string $username): string
{
    return getUserRecommendationStatePath($username);
}

function getUserRecommendationsHistoryPath(string $username): string
{
    return getUserRecommendationHistoryPath($username);
}
