<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ClerkSessionVerifier
{
    /**
     * Verifikasi token sesi Clerk (cookie __session / Bearer).
     * Mengembalikan payload stdClass bila valid, null bila tidak.
     */
    public function verify(?string $token): ?object
    {
        if (! $token) {
            return null;
        }

        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        $header = json_decode(JWT::urlsafeB64Decode($parts[0]), true);
        if (! is_array($header) || ($header['alg'] ?? null) !== 'RS256' || empty($header['kid'])) {
            return null;
        }

        $publicKey = $this->resolvePublicKey($header['kid']);
        if (! $publicKey) {
            return null;
        }

        try {
            JWT::$leeway = 5;
            $payload = JWT::decode($token, new Key($publicKey, 'RS256'));
        } catch (\Throwable) {
            return null;
        }

        $now = time();
        if (($payload->exp ?? 0) < $now || ($payload->nbf ?? 0) > $now) {
            return null;
        }

        $parties = config('clerk.authorized_parties', []);
        if ($parties !== [] && isset($payload->azp) && ! in_array($payload->azp, $parties, true)) {
            return null;
        }

        return $payload;
    }

    private function resolvePublicKey(string $kid): ?string
    {
        // 1. Kunci lokal (networkless)
        $jwtKey = config('clerk.jwt_key');
        if ($jwtKey) {
            return $jwtKey;
        }

        // 2. JWKS remote via secret key (di-cache)
        $secret = config('clerk.secret_key');
        if (! $secret) {
            return null;
        }

        $jwks = Cache::remember('clerk.jwks', config('clerk.jwks_cache_ttl', 3600), function () use ($secret) {
            $response = Http::withToken($secret)
                ->timeout(10)
                ->get(rtrim(config('clerk.api_url'), '/').'/v1/jwks');

            return $response->successful() ? $response->json() : null;
        });

        foreach ((array) ($jwks['keys'] ?? []) as $key) {
            if (($key['kid'] ?? null) !== $kid || empty($key['n']) || empty($key['e'])) {
                continue;
            }

            $modulus = JWT::urlsafeB64Decode($key['n']);
            $exponent = JWT::urlsafeB64Decode($key['e']);

            // Bangun kunci publik PKCS#8 (DER) dari komponen RSA JWK.
            return $this->rsaToPkcs8($modulus, $exponent);
        }

        // Kemungkinan rotasi kunci: segarkan cache sekali lalu coba lagi.
        Cache::forget('clerk.jwks');

        return null;
    }

    private function rsaToPkcs8(string $modulus, string $exponent): string
    {
        $modulus = ltrim($modulus, "\x00");
        if ((ord($modulus[0]) & 0x80) !== 0) {
            $modulus = "\x00".$modulus;
        }

        $sequence = $this->der($modulus, 0x02).$this->der($exponent, 0x02);
        $bitString = "\x00".$this->der($sequence, 0x30);
        $rsaOid = "\x06\x09\x2a\x86\x48\x86\xf7\x0d\x01\x01\x01\x05\x00";
        $der = $this->der($rsaOid.$this->der($bitString, 0x03), 0x30);

        return "-----BEGIN PUBLIC KEY-----\n".chunk_split(base64_encode($der), 64, "\n")."-----END PUBLIC KEY-----\n";
    }

    private function der(string $data, int $tag): string
    {
        $len = strlen($data);
        if ($len < 128) {
            return chr($tag).chr($len).$data;
        }

        $lenBytes = ltrim(pack('N', $len), "\x00");

        return chr($tag).chr(0x80 | strlen($lenBytes)).$lenBytes.$data;
    }
}
