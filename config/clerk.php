<?php

return [
    'publishable_key' => env('VITE_CLERK_PUBLISHABLE_KEY', env('CLERK_PUBLISHABLE_KEY')),

    // Salah satu wajib diisi untuk verifikasi server-side:
    // - CLERK_JWT_KEY: PEM public key (verifikasi lokal, tanpa network call)
    // - CLERK_SECRET_KEY: dipakai untuk mengambil JWKS dari Clerk API (dengan cache)
    'secret_key' => env('CLERK_SECRET_KEY'),
    'jwt_key' => env('CLERK_JWT_KEY'),

    'api_url' => env('CLERK_API_URL', 'https://api.clerk.com'),
    'jwks_cache_ttl' => (int) env('CLERK_JWKS_CACHE_TTL', 3600),

    // Origin yang diizinkan men-generate token (klaim azp). Kosongkan untuk skip.
    'authorized_parties' => array_filter(array_map('trim', explode(',', (string) env('CLERK_AUTHORIZED_PARTIES', '')))),
];
