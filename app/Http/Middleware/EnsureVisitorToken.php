<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EnsureVisitorToken
{
    public function handle(Request $request, Closure $next)
    {
        // Token lifetime: 180 days
        $cookieName = 'visitor_token';
        $token = $request->cookie($cookieName);

        if (!$token) {
            $token = (string) Str::uuid();

            $response = $next($request);

            return $response->cookie($cookieName, $token, [
                'expires' => now()->addDays(180),
                'path'     => '/',
                'secure'   => (bool) config('session.secure', false),
                'httpOnly' => true,
                'sameSite' => 'Lax',
            ]);
        }

        return $next($request);
    }
}

