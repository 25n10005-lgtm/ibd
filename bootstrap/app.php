<?php

use App\Http\Middleware\EnsureClerkAuthenticated;
use App\Http\Middleware\EnsureRole;
use App\Http\Middleware\EnsureRtAccess;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Cookie sesi Clerk berisi JWT mentah, bukan enkripsi Laravel.
        // Tanpa pengecualian ini, EncryptCookies me-null-kannya
        // sehingga login Google selalu mental ke halaman login.
        // (Varian bersufiks __clerk_db_jwt_xxx dibaca dari header mentah,
        // lihat EnsureClerkAuthenticated::sessionTokenCandidates.)
        $middleware->encryptCookies(except: ['__session', '__clerk_db_jwt', '__client_uat']);
        $middleware->alias([
            'clerk.auth' => EnsureClerkAuthenticated::class,
            'role' => EnsureRole::class,
            'rt.access' => EnsureRtAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
