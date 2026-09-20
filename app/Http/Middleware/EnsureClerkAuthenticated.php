<?php

namespace App\Http\Middleware;

use App\Enums\AccountStatus;
use App\Enums\Role;
use App\Models\User;
use App\Services\ClerkSessionVerifier;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class EnsureClerkAuthenticated
{
    public function __construct(private ClerkSessionVerifier $verifier) {}

    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $payload = null;
        if ($request->bearerToken()) {
            $payload = $this->verifier->verify($request->bearerToken());
        }

        // Cookie sesi Clerk v6: __clerk_db_jwt (+ sufiks acak per instance).
        // Legacy: __session. Coba satu per satu, yang lolos verifikasi dipakai.
        if (! $payload || empty($payload->sub)) {
            $payload = null;
            foreach ($this->sessionTokenCandidates($request) as $candidate) {
                $verified = $this->verifier->verify($candidate);
                if ($verified && ! empty($verified->sub)) {
                    $payload = $verified;
                    break;
                }
            }
        }

        // Login native (email+password) memakai sesi Laravel biasa:
        // tidak ada token Clerk, pakai user sesi yang sudah ada.
        if (! $payload) {
            $sessionUser = Auth::user();

            if ($sessionUser instanceof User) {
                $sessionUser = $sessionUser->fresh() ?? $sessionUser;

                if ($sessionUser->status === AccountStatus::Rejected) {
                    Auth::logout();

                    return $this->deny($request, 'Akun Anda ditolak. Hubungi pengurus RT.');
                }

                if ($sessionUser->status === AccountStatus::Pending && ! $request->routeIs('account.pending')) {
                    return redirect()->route('account.pending');
                }

                return $next($request);
            }

            // Belum ada sesi sama sekali (mis. auto-redirect JS saat
            // cookie Clerk belum siap): kembalikan ke login TANPA flash
            // error agar tidak muncul tulisan "Silakan masuk terlebih
            // dahulu" sekilas saat alur SSO sedang berjalan.
            return redirect()->route('login');
        }

        $user = $this->provision($request, $payload);

        Auth::login($user);

        if ($user->status === AccountStatus::Rejected) {
            Auth::logout();

            return $this->deny($request, 'Akun Anda ditolak. Hubungi pengurus RT.');
        }

        if ($user->status === AccountStatus::Pending && ! $request->routeIs('account.pending')) {
            return redirect()->route('account.pending');
        }

        return $next($request);
    }

    /**
     * Kumpulkan kandidat token sesi dari cookie.
     * Nama cookie sesi Clerk v6 memakai sufiks acak per instance
     * (__clerk_db_jwt_xxx) yang tidak bisa dikecualikan satu per satu
     * dari EncryptCookies, jadi baca juga header Cookie mentah
     * (tidak tersentuh dekripsi). Key = nama cookie (unik).
     *
     * @return array<string, string>
     */
    private function sessionTokenCandidates(Request $request): array
    {
        $found = [];

        // 1. Tas cookie Laravel (sudah didekripsi; hanya nama eksak
        // yang dikecualikan dari enkripsi).
        foreach (['__clerk_db_jwt', '__session'] as $name) {
            $value = $request->cookies->get($name);
            if (is_string($value) && $value !== '') {
                $found[$name] = $value;
            }
        }

        // 2. Header Cookie mentah (mencakup varian bersufiks).
        $header = (string) $request->headers->get('Cookie', '');
        foreach (explode(';', $header) as $pair) {
            $pair = trim($pair);
            if ($pair === '' || ! str_contains($pair, '=')) {
                continue;
            }
            [$name, $value] = explode('=', $pair, 2);
            $name = trim($name);
            $value = trim($value, " \t\n\r\0\x0B\"");
            if ($value === '' || ! is_string($value)) {
                continue;
            }
            if ($name === '__clerk_db_jwt' || $name === '__session'
                || str_starts_with($name, '__clerk_db_jwt_')
                || str_starts_with($name, '__session_')) {
                $found[$name] = $value;
            }
        }

        return $found;
    }

    /**
     * Just-in-time provisioning: buat user lokal saat pertama kali login via Clerk.
     * Penyatuan akun lewat email: daftar native dulu lalu masuk via Google
     * (atau sebaliknya) tetap memakai satu baris user yang sama.
     * Role default Warga (aktif langsung). Klaim role admin+ masuk antrean approval.
     */
    private function provision(Request $request, object $payload): User
    {
        $clerkId = (string) $payload->sub;

        $user = User::where('clerk_id', $clerkId)->first();
        if ($user) {
            return $this->applyUpgradeRequest($request, $user);
        }

        $email = $this->resolveEmail($payload);
        $name = $this->resolveName($payload, $email);
        $avatar = is_string($payload->image_url ?? null) ? $payload->image_url : null;

        // Profil remote hanya diambil bila ada data yang kurang —
        // menghemat satu network call ke api.clerk.com di tiap login baru.
        if (! $email || ! $avatar) {
            $profile = $this->fetchProfile($clerkId);
            $email ??= data_get($profile, 'primary_email');
            $avatar ??= data_get($profile, 'image_url');
            if ($name === 'Warga' || ($email && $name === strstr($email, '@', true))) {
                $name = data_get($profile, 'name') ?? $name;
            }
        }
        $existing = $email ? User::where('email', $email)->first() : null;
        if ($existing) {
            $existing->update(['clerk_id' => $clerkId]);

            return $this->applyUpgradeRequest($request, $existing->fresh());
        }

        // Role yang diminta saat daftar (warga/admin):
        // 1. unsafe_metadata dari Clerk, 2. query ?requested_role= (pilihan di halaman login).
        $requested = $this->normalizeRole(data_get((array) ($payload->unsafe_metadata ?? []), 'requested_role'))
            ?? $this->normalizeRole($request->query('requested_role'));

        // Admin ke atas wajib validasi Super Admin: akun pending + antrean.
        if ($requested && $requested->level() >= Role::Admin->level()) {
            $status = AccountStatus::Pending;
            $role = Role::User;
        } else {
            $status = AccountStatus::Active;
            $role = Role::User;
        }

        return User::create([
            'clerk_id' => $clerkId,
            'name' => $name ?? data_get($profile ?? [], 'name'),
            'email' => $email ?? $clerkId.'@clerk.local',
            'avatar' => $avatar,
            'password' => bin2hex(random_bytes(32)),
            'role' => $role,
            'requested_role' => $requested && $requested !== Role::User ? $requested : null,
            'status' => $status,
        ]);
    }

    /**
     * Ambil profil (email utama, nama, avatar) dari Clerk Backend API.
     * Token sesi tidak selalu membawa klaim ini.
     */
    private function fetchProfile(string $clerkId): array
    {
        $secret = config('clerk.secret_key');
        if (! $secret) {
            return [];
        }

        try {
            $response = Http::withToken($secret)
                ->timeout(4)
                ->get(rtrim(config('clerk.api_url'), '/').'/v1/users/'.$clerkId);

            if (! $response->successful()) {
                return [];
            }

            $data = $response->json();
            $primaryId = data_get($data, 'primary_email_address_id');
            $primaryEmail = collect(data_get($data, 'email_addresses', []))
                ->firstWhere('id', $primaryId)['email_address']
                ?? data_get($data, 'email_addresses.0.email_address');

            $first = trim((string) data_get($data, 'first_name', ''));
            $last = trim((string) data_get($data, 'last_name', ''));

            return [
                'primary_email' => $primaryEmail,
                'name' => trim($first.' '.$last) ?: null,
                'image_url' => data_get($data, 'image_url'),
            ];
        } catch (\Throwable) {
            return [];
        }
    }

    private function resolveEmail(object $payload): ?string
    {
        foreach ((array) ($payload->email_addresses ?? []) as $item) {
            $item = (array) $item;
            if (! empty($item['email_address'])) {
                return $item['email_address'];
            }
        }

        return is_string($payload->email ?? null) ? $payload->email : null;
    }

    private function resolveName(object $payload, ?string $email): string
    {
        $first = trim((string) ($payload->first_name ?? ''));
        $last = trim((string) ($payload->last_name ?? ''));
        $full = trim($first.' '.$last);

        if ($full !== '') {
            return $full;
        }

        if (is_string($payload->username ?? null) && $payload->username !== '') {
            return $payload->username;
        }

        return $email ? strstr($email, '@', true) : 'Warga';
    }

    /**
     * Pengguna lama yang login ulang sambil memilih "Admin" di halaman
     * login langsung dicatat sebagai pengajuan upgrade (tetap aktif
     * sebagai Warga selama menunggu validasi).
     */
    private function applyUpgradeRequest(Request $request, User $user): User
    {
        $requested = $this->normalizeRole($request->query('requested_role'));

        if ($requested && $requested->level() >= Role::Admin->level()
            && $user->status === AccountStatus::Active
            && ! $user->hasAtLeastRole(Role::Admin)
            && $user->requested_role === null) {
            $user->update(['requested_role' => $requested]);
        }

        return $user->fresh() ?? $user;
    }

    private function normalizeRole(mixed $value): ?Role
    {
        if (! is_string($value)) {
            return null;
        }

        $value = strtolower(trim($value));

        return match ($value) {
            'admin', 'pengelola' => Role::Admin,
            'super_admin', 'superadmin', 'ketua' => Role::SuperAdmin,
            'developer' => Role::Developer,
            default => Role::User,
        };
    }

    private function deny(Request $request, string $message): Response
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['message' => $message], 401);
        }

        return redirect()->route('login')->with('error', $message);
    }
}
