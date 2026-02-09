<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpCache
{
    /**
     * Handle an incoming request for HTTP caching.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only cache GET requests
        if (!$request->isMethod('GET')) {
            return $response;
        }

        // Cache API endpoints for shorter duration
        if ($request->is('api/*')) {
            $response->headers->set('Cache-Control', 'public, max-age=300, must-revalidate');
            $response->headers->set('ETag', '"' . md5($response->getContent()) . '"');
            return $response;
        }

        // Cache search results
        if ($request->is('search/*')) {
            $response->headers->set('Cache-Control', 'private, max-age=600, must-revalidate');
            return $response;
        }

        // Cache static pages
        if ($request->is('profile/*') || $request->is('post/*')) {
            $response->headers->set('Cache-Control', 'private, max-age=1800, must-revalidate');
            return $response;
        }

        // Don't cache home feed (changes frequently)
        if ($request->is('/') || $request->is('home')) {
            $response->headers->set('Cache-Control', 'no-cache, must-revalidate');
            return $response;
        }

        return $response;
    }
}
