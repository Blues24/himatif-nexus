<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdminRole
{
    /**
     * Handle an incoming request.
     *
     * Roles permitted to access the `/admin/*` panel: BPH and Infokom.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user === null || (! $user->isBph() && ! $user->hasRole('Infokom'))) {
            abort(403);
        }

        return $next($request);
    }
}