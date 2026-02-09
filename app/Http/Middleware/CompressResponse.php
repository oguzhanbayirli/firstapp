<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompressResponse
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only compress if the client accepts gzip
        if (!strpos($request->header('Accept-Encoding'), 'gzip') !== false) {
            return $response;
        }

        // Don't compress already compressed responses
        if ($response->headers->has('Content-Encoding')) {
            return $response;
        }

        // Only compress text-based content
        $contentType = $response->headers->get('Content-Type', '');
        $compressibleTypes = [
            'text/html',
            'text/css',
            'text/javascript',
            'application/javascript',
            'application/json',
            'application/xml',
            'text/xml',
        ];

        $shouldCompress = false;
        foreach ($compressibleTypes as $type) {
            if (strpos($contentType, $type) !== false) {
                $shouldCompress = true;
                break;
            }
        }

        if (!$shouldCompress) {
            return $response;
        }

        // Compress the content
        $content = $response->getContent();
        if ($content && strlen($content) > 1024) { // Only compress if > 1KB
            $compressed = gzencode($content, 6);
            
            if ($compressed !== false) {
                $response->setContent($compressed);
                $response->headers->set('Content-Encoding', 'gzip');
                $response->headers->set('Content-Length', strlen($compressed));
            }
        }

        return $response;
    }
}
