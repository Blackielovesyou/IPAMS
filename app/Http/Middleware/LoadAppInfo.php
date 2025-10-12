<?php

namespace App\Http\Middleware;

use App\Helpers\AppInfoHelper;
use App\Models\admin\AppInfoModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class LoadAppInfo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $appInfo = AppInfoModel::find(1);
        AppInfoHelper::verify($appInfo);
        View::share('appInfo', $appInfo);
        return $next($request);
    }
}
