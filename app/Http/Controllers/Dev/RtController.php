<?php

namespace App\Http\Controllers\Dev;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Rt;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RtController extends Controller
{
    /**
     * Daftar RT + jumlah penghuni untuk Developer.
     */
    public function index(): View
    {
        $rts = Rt::withCount(['users'])->orderBy('kode')->paginate(10);

        return view('pages.dev.rt-index', ['rts' => $rts]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kode' => 'required|string|max:20|unique:rts,kode',
            'nama' => 'required|string|max:100',
            'rw' => 'nullable|string|max:10',
            'alamat' => 'nullable|string|max:255',
        ]);

        Rt::create($data + ['aktif' => true]);

        return back()->with('success', 'RT berhasil ditambahkan.');
    }

    /**
     * Detail RT: daftar admin + super admin yang punya akses.
     */
    public function show(Rt $rt): View
    {
        $rt->load(['users' => fn ($q) => $q->orderBy('name')]);

        return view('pages.dev.rt-show', [
            'rt' => $rt,
            'admins' => User::where('role', Role::Admin)->orderBy('name')->get(),
            'superAdmins' => User::where('role', Role::SuperAdmin)->with(['rts', 'rt'])->orderBy('name')->get(),
            'semuaRt' => Rt::where('aktif', true)->orderBy('kode')->get(),
        ]);
    }

    public function update(Request $request, Rt $rt): RedirectResponse
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'rw' => 'nullable|string|max:10',
            'alamat' => 'nullable|string|max:255',
            'aktif' => 'nullable|boolean',
        ]);

        $rt->update($data + ['aktif' => $request->boolean('aktif')]);

        return back()->with('success', 'RT berhasil diperbarui.');
    }

    /**
     * Tetapkan Admin ke satu RT (otorisasi: hanya RT itu).
     */
    public function assignAdmin(Request $request, Rt $rt): RedirectResponse
    {
        $data = $request->validate(['user_id' => 'required|exists:users,id']);
        $user = User::findOrFail($data['user_id']);
        abort_if($user->role !== Role::Admin, 422, 'Hanya Admin yang bisa ditetapkan ke RT.');

        $user->update(['rt_id' => $rt->id]);

        return back()->with('success', "{$user->name} kini mengelola {$rt->label()}.");
    }

    public function unassignAdmin(Rt $rt, User $user): RedirectResponse
    {
        abort_if($user->rt_id !== $rt->id, 422, 'Pengguna bukan admin RT ini.');

        $user->update(['rt_id' => null]);

        return back()->with('success', "Akses {$user->name} ke {$rt->label()} dicabut.");
    }

    /**
     * Batasi/tambah RT untuk Super Admin (multi-RT via pivot).
     */
    public function attachSuperAdmin(Request $request, Rt $rt): RedirectResponse
    {
        $data = $request->validate(['user_id' => 'required|exists:users,id']);
        $user = User::findOrFail($data['user_id']);
        abort_if($user->role !== Role::SuperAdmin, 422, 'Hanya Super Admin yang bisa dibatasi multi-RT.');

        $user->rts()->syncWithoutDetaching([$rt->id]);

        return back()->with('success', "{$user->name} diberi akses ke {$rt->label()}.");
    }

    public function detachSuperAdmin(Rt $rt, User $user): RedirectResponse
    {
        $user->rts()->detach($rt->id);

        return back()->with('success', "Akses {$user->name} ke {$rt->label()} dicabut.");
    }
}
