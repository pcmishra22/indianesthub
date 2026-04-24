<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class LocalizationMiddleware
{
    public function handle($request, Closure $next)
    {
        $locale = $request->get('lang', 'en');
        App::setLocale($locale);
        return $next($request);
    }
}
