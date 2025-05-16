<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheHeaders
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
        $response = $next($request);
        
        // Only add cache headers to responses that don't already have them
        if (!$response->headers->has('Cache-Control') && $request->isMethod('GET')) {
            // For static assets (images, CSS, JS)
            if (preg_match('/\.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$/i', $request->getPathInfo())) {
                $response->headers->set('Cache-Control', 'public, max-age=31536000'); // 1 year
                $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
            }
            // For API responses and dynamic content
            else {
                $response->headers->set('Cache-Control', 'no-cache, private');
                $response->headers->set('Pragma', 'no-cache');
            }
        }
        
        return $response;
    }
}
