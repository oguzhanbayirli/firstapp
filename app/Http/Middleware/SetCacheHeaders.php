<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCacheHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Cache static assets for 1 year (far future)
        if ($request->is('build/*') || $request->is('storage/*')) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
        }
        // Cache API responses for 5 minutes
        elseif ($request->is('api/*')) {
            $response->headers->set('Cache-Control', 'public, max-age=300');
        }
        // Don't cache dynamic HTML pages
        elseif ($response->isSuccessful() && !$response->isNotFound()) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }
}
