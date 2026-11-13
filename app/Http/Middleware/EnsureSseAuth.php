<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSseAuth
{
    /**
     * Handle an incoming request for SSE routes.
     * Abort 401 if unauthenticated.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->guard()->check()) {
            abort(401, 'Unauthenticated.');
        }

        return $next($request);
    }
}
