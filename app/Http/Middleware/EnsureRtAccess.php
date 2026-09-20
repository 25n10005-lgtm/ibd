<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRtAccess
{
    /**
     * Pastikan pengguna boleh mengakses RT pada route parameter {rt}.
     * Developer selalu lolos; yang lain dicek via User::canAccessRt().
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $rt = $request->route('rt');
        $rtId = $rt?->id ?? (is_numeric($rt) ? (int) $rt : null);

        if (! $user || ! $rtId || ! $user->canAccessRt($rtId)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Akses ke RT ini ditolak.'], 403);
            }

            abort(403, 'Akses ke RT ini ditolak.');
        }

        return $next($request);
    }
}
