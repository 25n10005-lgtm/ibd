<?php

namespace App\Http\Controllers;

use App\Enums\AccountStatus;
use App\Enums\Role;
use App\Models\User;
use App\Support\TablePage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApprovalController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        // Antrean = semua akun dengan requested_role, baik pendaftar baru
        // (status pending) maupun warga aktif yang mengajukan upgrade.
        $pending = User::whereNotNull('requested_role')
            ->orderByDesc('created_at')
            ->paginate(TablePage::perPage($request, User::whereNotNull('requested_role')));

        if ($request->expectsJson()) {
            return response()->json($pending);
        }

        return view('pages.admin.approvals', ['pending' => $pending]);
    }

    public function approve(Request $request, User $user): RedirectResponse|JsonResponse
    {
        abort_if($user->requested_role === null, 422, 'Tidak ada permintaan peran.');

        $granted = $user->requested_role;

        // Tidak boleh memberikan peran setara/lebih tinggi dari diri sendiri
        // kecuali Developer.
        $me = $request->user();
        if (! $me->hasRole(Role::Developer) && $me->role->level() <= $granted->level()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Anda tidak dapat menyetujui peran setara atau lebih tinggi.'], 403);
            }

            return back()->with('error', 'Anda tidak dapat menyetujui peran setara atau lebih tinggi.');
        }

        $user->update([
            'role' => $granted,
            'requested_role' => null,
            'status' => AccountStatus::Active,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => "Peran {$granted->label()} disetujui untuk {$user->name}."]);
        }

        return back()->with('success', "Peran {$granted->label()} disetujui untuk {$user->name}.");
    }

    public function reject(Request $request, User $user): RedirectResponse|JsonResponse
    {
        // Penolakan upgrade (akun sudah aktif) mengembalikan akun ke
        // status semula; hanya pendaftar baru yang ditolak penuh.
        $wasActive = $user->status === AccountStatus::Active;

        $user->update([
            'requested_role' => null,
            'status' => $wasActive ? AccountStatus::Active : AccountStatus::Rejected,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => "Pengajuan {$user->name} ditolak."]);
        }

        return back()->with('success', "Pengajuan {$user->name} ditolak.");
    }
}
