<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CleanSeoUrls
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $uri = $request->getRequestUri();

        /**
         * Regex matches:
         * 1. (.*?) - The clean URL part
         * 2. (?:"|,) - A separator (either a double quote or a comma)
         * 3. \d{4}-\d{2}-\d{2} - The trailing date pattern (YYYY-MM-DD)
         */
        if (preg_match('/^(.*?)(?:"|,)\d{4}-\d{2}-\d{2}$/', $uri, $matches)) {
            $cleanPathAndQuery = $matches[1];
            
            // Reconstruct the full URL
            $fullUrl = $request->getSchemeAndHttpHost() . $cleanPathAndQuery;

            return redirect()->to($fullUrl, 301);
        }

        return $next($request);
    }
}