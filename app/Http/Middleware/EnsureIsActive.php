<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && ! $request->user()->is_active) {
            if ($request->routeIs('verification.notice') || $request->routeIs('verification.verify') || $request->routeIs('verification.send')) {
                return $next($request);
            }

            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
