<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visit;

class TrackVisits
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $path = $request->path();
        if (str_starts_with($path, 'admin')) {
            return $response;
        }

        $ip = $request->ip();
        $date = now()->toDateString();

        if (!Visit::where('ip', $ip)->where('date', $date)->exists()) {
            Visit::create([
                'ip' => $ip,
                'date' => $date,
                'path' => $path,
                'user_id' => optional($request->user())->id,
            ]);
        }

        return $response;
    }
}

