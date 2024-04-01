<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\CPU\Helpers;

class ModulePermissionsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $module_name)
    {
        if (Helpers::module_permission_check($module_name)) {
            return $next($request);
        }
        return response()->json(['error' => 'Unauthorised'], 401);
    }
}
