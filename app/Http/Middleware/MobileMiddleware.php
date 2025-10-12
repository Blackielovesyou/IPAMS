<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MobileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->hasHeader('X-Client-Type') || $request->header('X-Client-Type') !== 'mobile') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Mobile access only.'
            ], 401);
        }

        return $next($request);
    }
}
