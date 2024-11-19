<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiToken
{
    public function handle(Request $request, Closure $next)
    {
        $configuredToken = config('app.api_token');
        
        if (!$configuredToken) {
            return response()->json(['error' => 'API token not configured'], 500);
        }

        $token = $request->header('X-API-Token');
        
        if ($token !== $configuredToken) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
} 