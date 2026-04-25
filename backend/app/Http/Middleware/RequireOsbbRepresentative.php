<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireOsbbRepresentative
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasRole('OSBBRepresentative')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
