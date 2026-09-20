<?php

namespace App\Http\Controllers;

use App\Enums\AccountStatus;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirect;

class SocialiteController extends Controller
{
    /**
     * Lempar ke halaman izin Google (server-side, tanpa JS SDK).
     */
    public function redirect(): SymfonyRedirect|RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Kembalian dari Google. Akun menyatu dengan email+password lewat
     * kolom email yang unik: satu baris user untuk kedua cara masuk.
     * Pendaftar baru via Google default Warga (aktif langsung).
     */
    public function callback(Request $request): RedirectResponse|JsonResponse
    {
        try {
            $google = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            Log::warning('Google login gagal', ['error' => $e->getMessage()]);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Login Google gagal. Silakan coba lagi.'], 422);
            }

            return redirect()->route('login')->with('error', 'Login Google gagal. Silakan coba lagi.');
        }

        $email = $google->getEmail();
        if (! $email) {
            return redirect()->route('login')->with('error', 'Akun Google Anda tidak membagikan email.');
        }

        $user = User::where('google_id', $google->getId())->first()
            ?? User::where('email', $email)->first();

        if ($user) {
            $user->update([
                'google_id' => $google->getId(),
                'avatar' => $user->avatar ?? $google->getAvatar(),
                'name' => $user->name ?: $google->getName(),
            ]);
        } else {
            $user = User::create([
                'name' => $google->getName() ?: strstr($email, '@', true),
                'email' => $email,
                'password' => Str::random(32),
                'google_id' => $google->getId(),
                'avatar' => $google->getAvatar(),
                'role' => Role::User,
                'requested_role' => null,
                'status' => AccountStatus::Active,
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        /** @var User $user */
        $user = $request->user();

        if ($user->status === AccountStatus::Rejected) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Akun Anda ditolak. Hubungi pengurus RT.');
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Berhasil masuk.', 'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role?->value,
                'status' => $user->status?->value,
            ]]);
        }

        if ($user->status === AccountStatus::Pending) {
            return redirect()->route('account.pending');
        }

        return redirect()->intended(route('dashboard'));
    }
}
