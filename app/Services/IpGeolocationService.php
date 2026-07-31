<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IpGeolocationService
{
    /**
     * Best-effort IP → country lookup. Free, no API key required
     * (ipwho.is). Deliberately fails silently and returns null on any
     * error — given this server's history of intermittent outbound
     * connectivity to some third-party hosts, this must NEVER be allowed
     * to break page rendering or view tracking. Callers should always run
     * this outside the main request/response cycle (see
     * PropertyDetailsController's use of dispatch(...)->afterResponse()),
     * so even a slow/hanging lookup never delays what the visitor sees.
     *
     * Cached per IP for 30 days — an IP's country essentially never
     * changes, so there's no reason to re-look it up on every visit.
     *
     * @return array{country: ?string, country_code: ?string}|null
     */
    public function lookup(string $ip): ?array
    {
        if (!$ip || in_array($ip, ['127.0.0.1', '::1'])) {
            return null; // local/dev traffic — nothing meaningful to look up
        }

        $cacheKey = 'ip-country:' . $ip;

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($ip) {
            try {
                $response = Http::timeout(3)->get("https://ipwho.is/{$ip}", [
                    'fields' => 'success,country,country_code',
                ]);

                if (!$response->successful() || !$response->json('success')) {
                    return null;
                }

                return [
                    'country'      => $response->json('country'),
                    'country_code' => $response->json('country_code'),
                ];
            } catch (\Throwable $e) {
                Log::info('IP geolocation lookup skipped: ' . $e->getMessage());
                return null;
            }
        });
    }
}
