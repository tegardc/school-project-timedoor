<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use Illuminate\Http\Request;

class CheckRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->guard('api')->user()->hasRole($roles)) {
            return ResponseHelper::notFound('Oops! You are not authorized to access this resource', '[UNAUTHORIZED]');
        }

        return $next($request);
    }
}
