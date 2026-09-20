<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $minimum): Response
    {
        $user = $request->user();
        $required = Role::tryFrom(strtolower($minimum));

        if (! $user || ! $required || ! $user->hasAtLeastRole($required)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Akses ditolak.'], 403);
            }

            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
