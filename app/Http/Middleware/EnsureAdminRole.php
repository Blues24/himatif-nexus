<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminRole
{
    /**
     * Handle an incoming request.
     *
     * Roles permitted access to `/admin/*` panel: BPH, Infokom.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null || !$user->isBph() && !$user->hasRole('Infokom')) {
            abort(403, 'Akses ditolak. Hanya BPH dan Infokom yang dapat mengakses panel admin.');
        }

        return $next($request);
    }
}