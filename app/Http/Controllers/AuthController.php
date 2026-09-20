<?php

namespace App\Http\Controllers;

use App\Enums\AccountStatus;
use App\Enums\Role;
use App\Models\Rt;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('pages.login');
    }

    public function showRegister(): View
    {
        return view('pages.register', [
            'rts' => Rt::where('aktif', true)->orderBy('kode')->get(),
        ]);
    }

    /**
     * Login native email + password.
     * Akun menyatu dengan Google lewat kolom email yang unik:
     * baris user yang sama dipakai kedua metode.
     */
    public function login(Request $request): RedirectResponse|JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (! Auth::attempt(
            ['email' => $credentials['email'], 'password' => $credentials['password']],
            $request->boolean('remember')
        )) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Email atau password salah.'], 422);
            }

            return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        /** @var User $user */
        $user = $request->user();

        if ($user->status === AccountStatus::Rejected) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akun Anda ditolak. Hubungi pengurus RT.'], 403);
            }

            return redirect()->route('login')->with('error', 'Akun Anda ditolak. Hubungi pengurus RT.');
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Berhasil masuk.', 'user' => $this->payload($user)]);
        }

        if ($user->status === AccountStatus::Pending) {
            return redirect()->route('account.pending');
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Register native. Pilihan role: warga (aktif langsung)
     * atau admin (masuk antrean approval).
     */
    public function register(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:user,warga,admin,pengelola'],
            'rt_id' => ['required', 'integer', 'exists:rts,id'],
        ], [
            'email.unique' => 'Email sudah terdaftar. Masuk dengan Google lalu atur password di dashboard untuk mengaktifkan login email.',
            'rt_id.required' => 'Pilih RT tempat tinggal Anda.',
            'rt_id.exists' => 'RT yang dipilih tidak valid.',
        ]);

        $wantsAdmin = in_array(strtolower($data['role']), ['admin', 'pengelola'], true);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => Role::User,
            'rt_id' => $data['rt_id'],
            // Pendaftar admin langsung "hit" antrean approval:
            // status pending + requested_role terisi.
            'requested_role' => $wantsAdmin ? Role::Admin : null,
            'status' => $wantsAdmin ? AccountStatus::Pending : AccountStatus::Active,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $wantsAdmin
                    ? 'Pendaftaran diterima. Pengajuan Admin menunggu validasi.'
                    : 'Pendaftaran berhasil. Selamat datang!',
                'user' => $this->payload($user->fresh()),
            ], 201);
        }

        if ($wantsAdmin) {
            return redirect()->route('account.pending')
                ->with('success', 'Pengajuan peran Admin dikirim. Menunggu validasi Super Admin.');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Pengajuan naik peran bagi akun yang sudah aktif
     * (mis. Warga ingin jadi Admin, atau Admin ingin jadi Ketua RT).
     * Status tetap aktif supaya akun tetap bisa dipakai sambil menunggu.
     */
    public function requestRole(Request $request): RedirectResponse|JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $data = $request->validate([
            'role' => ['required', 'string', 'in:admin,pengelola,super_admin,ketua'],
        ]);

        $target = in_array(strtolower($data['role']), ['super_admin', 'ketua'], true)
            ? Role::SuperAdmin
            : Role::Admin;

        if ($user->hasAtLeastRole($target)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Anda sudah memiliki peran tersebut.'], 422);
            }

            return back()->with('error', 'Anda sudah memiliki peran tersebut.');
        }

        $label = $target->label();

        $user->update([
            'requested_role' => $target,
            // Akun baru yang masih pending tetap pending;
            // akun aktif tetap aktif selama menunggu validasi.
            'status' => $user->status === AccountStatus::Active
                ? AccountStatus::Active
                : AccountStatus::Pending,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => "Pengajuan peran {$label} dikirim. Menunggu validasi.",
                'user' => $this->payload($user->fresh()),
            ]);
        }

        if ($user->status === AccountStatus::Pending) {
            return redirect()->route('account.pending')
                ->with('success', "Pengajuan peran {$label} dikirim. Menunggu validasi Super Admin.");
        }

        return back()->with('success', "Pengajuan peran {$label} dikirim. Menunggu validasi Super Admin.");
    }

    /**
     * Atur/ganti password native. Akun Google (tanpa password yang
     * diketahui) boleh mengosongkan "current_password" untuk
     * mengaktifkan login email+password pada akun yang sama.
     */
    public function updatePassword(Request $request): RedirectResponse|JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $data = $request->validate([
            'current_password' => ['nullable', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! empty($data['current_password']) && ! Hash::check($data['current_password'], $user->password)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Password saat ini salah.'], 422);
            }

            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->update(['password' => $data['password']]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Password berhasil diperbarui.']);
        }

        return back()->with('success', 'Password berhasil diperbarui. Kini Anda bisa masuk via email+password maupun Google.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login', ['signed_out' => 1]);
    }

    public function me(Request $request)
    {
        return response()->json($this->payload($request->user()));
    }

    /** @return array<string, mixed> */
    private function payload(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'role' => $user->role?->value,
            'role_label' => $user->role?->label(),
            'rt_id' => $user->rt_id,
            'status' => $user->status?->value,
            'requested_role' => $user->requested_role?->value,
        ];
    }
}
