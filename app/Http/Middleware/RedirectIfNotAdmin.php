<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Check if the user is not an admin
            if (!Auth::user()->isAdmin() || !Auth::user()->isSubAdmin()) {
                // Redirect to user dashboard or homepage
                return response()->view('errors.no-access', [], 403);
            }
        } else {
            // User is not authenticated, redirect to login page
            return redirect()->route('login');
        }
        return $next($request);
    }
}
