<?php

namespace App\Http\Middleware;

use App\Models\ModuleAccessModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoadModuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $moduleCode): Response
    {
        if (Auth::check() && in_array($moduleCode, Auth::user()->module_access()) || Auth::user()->isAdmin()) {
            return $next($request);
        }

        // Optionally, redirect or abort with 403
        return response()->view('errors.no-access', [], 403);
    }
}
