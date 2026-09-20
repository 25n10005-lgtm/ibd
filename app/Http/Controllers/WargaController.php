<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use App\Support\TablePage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WargaController extends Controller
{
    /**
     * Tabel warga dengan cari, filter status tinggal, dan paginasi.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status_tinggal' => ['nullable', 'string', 'in:Tetap,Kontrak,Kos'],
            'aktif' => ['nullable', 'string', 'in:1,0'],
        ]);

        $rtId = $request->user()->scopeRtId();

        $wargas = Warga::query()
            ->with('keluarga')
            ->whereHas('keluarga', fn ($query) => $query->forRt($rtId))
            ->when($filters['q'] ?? null, fn ($query, $q) => $query->where(
                fn ($query) => $query->where('nama', 'like', "%{$q}%")
                    ->orWhere('nik', 'like', "%{$q}%")
                    ->orWhere('alamat', 'like', "%{$q}%")
            ))
            ->when($filters['status_tinggal'] ?? null, fn ($query, $status) => $query->where('status_tinggal', $status))
            ->when(isset($filters['aktif']), fn ($query) => $query->where('aktif', $filters['aktif'] === '1'))
            ->orderBy('nama');
        $wargas = $wargas->paginate(TablePage::perPage($request, Warga::query()))->withQueryString();

        return view('pages.data-warga', [
            'wargas' => $wargas,
            'filters' => $filters,
            'total' => Warga::count(),
            'aktifCount' => Warga::where('aktif', true)->count(),
            'nonAktifCount' => Warga::where('aktif', false)->count(),
        ]);
    }
}
