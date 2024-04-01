<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
     {

        if (auth('sanctum')->check() && auth('admin')->check()) {
            $userRole = Auth::user()->role;
            if ($userRole == 'super-admin') {
                // User is an admin, allow access
                return $next($request);
            } else {
                return response()->json(['message' => 'not allow access'], 401);
            }
        }
        return response()->json(['message' => 'Unauthorised'], 401);
     }
}
