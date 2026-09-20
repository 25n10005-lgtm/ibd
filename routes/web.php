<?php

use App\Data\SiteContent;
use App\Enums\AccountStatus;
use App\Enums\Role;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dev\RtController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\WargaVerificationController;
use App\Models\IuranPembayaran;
use App\Models\KasTransaksi;
use App\Models\Kegiatan;
use App\Models\KegiatanHadir;
use App\Models\Keluarga;
use App\Models\Pengaduan;
use App\Models\Pengumuman;
use App\Models\SuratPengajuan;
use App\Models\User;
use App\Models\UserWargaLink;
use App\Models\Warga;
use App\Models\WargaClaim;
use App\Support\TablePage;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/tentang-kami', function () {
    return view('pages.tentang-kami');
})->name('tentang-kami');

Route::get('/kegiatan', function () {
    return view('pages.kegiatan');
})->name('kegiatan');

Route::get('/pengumuman', function () {
    return view('pages.pengumuman');
})->name('pengumuman');

Route::get('/kontak', function () {
    return view('pages.kontak');
})->name('kontak');

Route::get('/pengaduan', function () {
    return view('pages.pengaduan');
})->name('pengaduan');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:10,1')
    ->name('login.attempt');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])
    ->middleware('throttle:10,1')
    ->name('register.store');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route SSO Callback untuk Clerk
Route::get('/sso-callback', fn () => view('pages.sso-callback'))->name('sso-callback');

// Login Google server-side via Laravel Socialite (tanpa JS SDK).
Route::get('/auth/google/redirect', [SocialiteController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [SocialiteController::class, 'callback'])->name('google.callback');

Route::middleware('clerk.auth')->group(function (): void {
    Route::get('/account/pending', fn () => view('pages.pending'))->name('account.pending');
    // Admin/Pengelola ke dashboard admin, Warga ke dashboard biasa.
    Route::get('/dashboard', function () {
        if (auth()->user()?->hasAtLeastRole(Role::Admin)) {
            $rtId = auth()->user()->scopeRtId();
            $stats = Cache::remember('dash:stats:'.$rtId, 120, function () use ($rtId) {
                $masuk = KasTransaksi::forRt($rtId)->where('arah', 'masuk')->sum('jumlah');
                $keluar = KasTransaksi::forRt($rtId)->where('arah', 'keluar')->sum('jumlah');
                $suratMenunggu = SuratPengajuan::forRt($rtId)->where('status', 'Menunggu')->count();
                $aduanBelum = Pengaduan::forRt($rtId)->whereIn('status', ['Belum', 'Proses'])->count();

                return [
                    'totalWarga' => Warga::whereHas('keluarga', fn ($q) => $q->forRt($rtId))->count(),
                    'suratMenunggu' => $suratMenunggu,
                    'aduanBelum' => $aduanBelum,
                    'perluDitangani' => $suratMenunggu + $aduanBelum,
                    'saldo' => $masuk - $keluar,
                    'masuk' => $masuk,
                    'keluar' => $keluar,
                ];
            });

            return view('pages.dashboard-admin', $stats + [
                'transaksi' => KasTransaksi::forRt($rtId)->latest('tanggal')->take(6)->get(),
                'suratTerbaru' => SuratPengajuan::forRt($rtId)->latest()->take(2)->get(),
                'agenda' => Kegiatan::forRt($rtId)->orderBy('tanggal')->whereDate('tanggal', '>=', today())->take(3)->get(),
                'aduanTerbaru' => Pengaduan::forRt($rtId)->latest()->take(2)->get(),
            ]);
        }

        return redirect()->route('saya.dashboard');
    })->name('dashboard');
    Route::get('/auth/me', [AuthController::class, 'me'])->name('auth.me');
    Route::post('/account/request-role', [AuthController::class, 'requestRole'])->name('account.request-role');
    Route::post('/account/password', [AuthController::class, 'updatePassword'])->name('account.password');

    Route::middleware('role:super_admin')->prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals');
        Route::post('/approvals/{user}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
        Route::post('/approvals/{user}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');
    });

    // Daftar pengurus (Ketua RT / Super Admin): seluruh admin beserta role.
    Route::middleware('role:super_admin')->group(function (): void {
        Route::get('/pengurus', function (Request $request) {
            $query = User::where('role', '!=', Role::User)->latest();

            return view('pages.pengurus-index', [
                'pengurus' => $query->paginate(TablePage::perPage($request, User::where('role', '!=', Role::User)))->withQueryString(),
                'aktif' => (clone $query)->where('status', AccountStatus::Active)->count(),
                'menunggu' => User::whereNotNull('requested_role')->count(),
            ]);
        })->name('pengurus.index');
    });

    // Area warga (semua peran yang login). Data milik sendiri.
    // Identitas warga diutamakan dari tautan verifikasi NIK
    // (user_warga_links); cocok nama hanya fallback transisi.
    Route::prefix('warga')->name('saya.')->group(function (): void {
        $wargaSaya = fn () => auth()->user()->linkedWarga()
            ?? Warga::where('nama', auth()->user()->name)->first();

        Route::get('/dashboard', function () use ($wargaSaya) {
            $nama = auth()->user()->name;
            $warga = $wargaSaya();
            $rtId = auth()->user()->scopeRtId();

            return view('pages.user-dashboard', [
                'tagihan' => IuranPembayaran::where('periode', '2026-09')->where(fn ($q) => $q->where('nama_pembayar', $nama)->orWhere('warga_id', $warga?->id))->where('status', 'Menunggak')->count(),
                'suratAktif' => SuratPengajuan::where('pemohon', $nama)->whereIn('status', ['Menunggu', 'Diproses'])->count(),
                'suratSelesai' => SuratPengajuan::where('pemohon', $nama)->where('status', 'Selesai')->count(),
                'aduanAktif' => Pengaduan::where('pelapor', $nama)->whereIn('status', ['Belum', 'Proses'])->count(),
                'agenda' => Kegiatan::forRt($rtId)->orderBy('tanggal')->whereDate('tanggal', '>=', today())->take(3)->get(),
                'pengumuman' => Pengumuman::forRt($rtId)->where('status', 'terbit')->latest('published_at')->take(3)->get(),
            ]);
        })->name('dashboard');

        Route::get('/keluarga', function () use ($wargaSaya) {
            $user = auth()->user();
            $warga = $wargaSaya();
            $keluarga = $warga?->keluarga()->with('wargas')->first();

            return view('pages.user-keluarga', [
                'warga' => $warga,
                'keluarga' => $keluarga,
                'link' => UserWargaLink::where('user_id', $user->id)->first(),
                'openClaim' => WargaClaim::where('user_id', $user->id)
                    ->where('status', WargaClaim::STATUS_DIAJUKAN)->latest()->first(),
                'lastClaim' => WargaClaim::where('user_id', $user->id)
                    ->with('decision')->latest()->first(),
            ]);
        })->name('keluarga');

        // Verifikasi NIK mandiri: form -> cek 4 gate -> konfirmasi -> ajukan.
        Route::get('/keluarga/verifikasi', [WargaVerificationController::class, 'create'])->name('keluarga.verifikasi');
        Route::post('/keluarga/verifikasi/cek', [WargaVerificationController::class, 'check'])
            ->middleware('throttle:10,1')
            ->name('keluarga.verifikasi.cek');
        Route::post('/keluarga/verifikasi/ajukan', [WargaVerificationController::class, 'store'])
            ->middleware('throttle:10,1')
            ->name('keluarga.verifikasi.ajukan');
        Route::post('/keluarga/verifikasi/{claim}/batal', [WargaVerificationController::class, 'cancel'])
            ->name('keluarga.verifikasi.batal');

        Route::get('/pengajuan-surat', function (Request $request) {
            $nama = auth()->user()->name;
            $query = SuratPengajuan::where('pemohon', $nama);

            return view('pages.user-surat', [
                'rows' => (clone $query)->latest()->paginate(TablePage::perPage($request, SuratPengajuan::where('pemohon', $nama), 5))->withQueryString(),
                'menunggu' => (clone $query)->where('status', 'Menunggu')->count(),
                'selesai' => (clone $query)->where('status', 'Selesai')->count(),
            ]);
        })->name('surat');

        // Form tambah di halaman terpisah; modal hanya untuk preview.
        Route::get('/pengajuan-surat/baru', fn () => view('pages.user-surat-create'))->name('surat.create');

        Route::post('/pengajuan-surat', function (Request $request) use ($wargaSaya) {
            $data = $request->validate([
                'jenis' => 'required|string|max:100',
                'keperluan' => 'required|string|min:10|max:1000',
            ]);
            $warga = $wargaSaya();

            SuratPengajuan::create([
                'rt_id' => auth()->user()->scopeRtId(),
                'no_surat' => 'SUR-'.now()->format('Ymd').'-'.str_pad((string) (SuratPengajuan::count() + 1), 4, '0', STR_PAD_LEFT),
                'jenis' => $data['jenis'],
                'pemohon' => auth()->user()->name,
                'blok' => $warga?->alamat,
                'lampiran' => '-',
                'status' => 'Menunggu',
                'catatan' => $data['keperluan'],
            ]);

            return back()->with('success', 'Pengajuan surat berhasil dikirim.');
        })->name('surat.store');

        Route::get('/pengumuman', function (Request $request) {
            $query = Pengumuman::forRt(auth()->user()->scopeRtId())->where('status', 'terbit');

            return view('pages.user-pengumuman', [
                'items' => (clone $query)->latest('published_at')->paginate(TablePage::perPage($request, $query, 6))->withQueryString(),
            ]);
        })->name('pengumuman');

        Route::get('/pengumuman/{pengumuman}', function (Pengumuman $pengumuman) {
            abort_if($pengumuman->status !== 'terbit', 404);
            abort_if($pengumuman->rt_id && ! auth()->user()->canAccessRt($pengumuman->rt_id), 403, 'Akses ke RT ini ditolak.');

            $pengumuman->increment('views');

            $lainnya = Pengumuman::forRt(auth()->user()->scopeRtId())
                ->where('status', 'terbit')->where('id', '!=', $pengumuman->id)
                ->latest('published_at')->take(3)->get();

            return view('pages.user-pengumuman-show', ['item' => $pengumuman->fresh(), 'lainnya' => $lainnya]);
        })->name('pengumuman.show');

        Route::get('/kegiatan', function (Request $request) {
            $rtId = auth()->user()->scopeRtId();
            $userId = auth()->id();
            $denganHadir = fn ($q) => $q->withCount(['hadirs as hadir_count' => fn ($h) => $h->where('status', KegiatanHadir::HADIR)]);

            $mendatang = $denganHadir(Kegiatan::forRt($rtId))->orderBy('tanggal')->whereDate('tanggal', '>=', today())->take(3)->get();
            $query = Kegiatan::forRt($rtId)->when($request->kategori, fn ($q, $v) => $q->where('kategori', $v));
            $items = $denganHadir(clone $query)->orderBy('tanggal')->paginate(TablePage::perPage($request, $query, 5))->withQueryString();

            $ids = $mendatang->pluck('id')->merge($items->pluck('id'))->unique()->all();
            $hadirSaya = KegiatanHadir::where('user_id', $userId)->whereIn('kegiatan_id', $ids)->pluck('status', 'kegiatan_id')->all();

            return view('pages.user-kegiatan', [
                'mendatang' => $mendatang,
                'items' => $items,
                'kategoris' => Kegiatan::forRt($rtId)->distinct()->pluck('kategori'),
                'hadirSaya' => $hadirSaya,
            ]);
        })->name('kegiatan');

        // Konfirmasi kehadiran: hanya kegiatan RT sendiri yang akan datang.
        Route::post('/kegiatan/{kegiatan}/kehadiran', function (Request $request, Kegiatan $kegiatan) {
            abort_if($kegiatan->rt_id && ! auth()->user()->canAccessRt($kegiatan->rt_id), 403, 'Akses ke RT ini ditolak.');
            abort_if($kegiatan->statusEfektif() === 'dibatalkan' || ($kegiatan->tanggal && $kegiatan->tanggal->isPast()), 422, 'Kegiatan sudah selesai atau dibatalkan.');

            $data = $request->validate(['status' => ['required', 'in:hadir,tidak']]);

            KegiatanHadir::updateOrCreate(
                ['kegiatan_id' => $kegiatan->id, 'user_id' => auth()->id()],
                ['status' => $data['status']]
            );

            return back()->with('success', $data['status'] === 'hadir' ? 'Kehadiran dikonfirmasi. Sampai jumpa!' : 'Konfirmasi tersimpan: berhalangan hadir.');
        })->name('kegiatan.hadir');

        Route::delete('/kegiatan/{kegiatan}/kehadiran', function (Kegiatan $kegiatan) {
            abort_if($kegiatan->rt_id && ! auth()->user()->canAccessRt($kegiatan->rt_id), 403, 'Akses ke RT ini ditolak.');

            KegiatanHadir::where('kegiatan_id', $kegiatan->id)->where('user_id', auth()->id())->delete();

            return back()->with('success', 'Konfirmasi kehadiran dibatalkan.');
        })->name('kegiatan.hadir.batal');

        Route::get('/iuran', function () use ($wargaSaya) {
            $nama = auth()->user()->name;
            $warga = $wargaSaya();
            $milik = fn () => IuranPembayaran::where(fn ($q) => $q->where('nama_pembayar', $nama)->orWhere('warga_id', $warga?->id));

            return view('pages.user-iuran', [
                'tagihan' => (clone $milik())->where('status', 'Menunggak')->orderBy('periode')->get(),
                'menunggak' => (clone $milik())->where('status', 'Menunggak')->count(),
                'lunas' => (clone $milik())->where('status', 'Lunas')->count(),
                'totalTagihan' => (clone $milik())->where('status', 'Menunggak')->sum('jumlah'),
            ]);
        })->name('iuran');

        Route::get('/iuran/riwayat', function (Request $request) use ($wargaSaya) {
            $nama = auth()->user()->name;
            $warga = $wargaSaya();
            $query = IuranPembayaran::where(fn ($q) => $q->where('nama_pembayar', $nama)->orWhere('warga_id', $warga?->id));

            return view('pages.user-iuran-riwayat', [
                'history' => (clone $query)->latest('tanggal_bayar')->latest('id')->paginate(TablePage::perPage($request, $query, 10))->withQueryString(),
                'lunas' => (clone $query)->where('status', 'Lunas')->count(),
                'totalLunas' => (clone $query)->where('status', 'Lunas')->sum('jumlah'),
            ]);
        })->name('iuran.riwayat');

        Route::get('/pengaduan', function (Request $request) {
            $nama = auth()->user()->name;
            $rtId = auth()->user()->scopeRtId();
            $milik = fn () => Pengaduan::where('pelapor', $nama);

            $status = $request->query('status');
            $rows = (clone $milik())
                ->when(in_array($status, ['Belum', 'Proses', 'Selesai', 'Ditolak'], true), fn ($q) => $q->where('status', $status))
                ->latest()->paginate(TablePage::perPage($request, (clone $milik()), 6))->withQueryString();

            return view('pages.user-aduan', [
                'rows' => $rows,
                'statusAktif' => $status,
                'belum' => (clone $milik())->where('status', 'Belum')->count(),
                'proses' => (clone $milik())->where('status', 'Proses')->count(),
                'selesai' => (clone $milik())->where('status', 'Selesai')->count(),
                'ditolak' => (clone $milik())->where('status', 'Ditolak')->count(),
                'total' => (clone $milik())->count(),
                // Laporan warga lain di RT yang sama (transparansi komunitas).
                'komunitas' => Pengaduan::forRt($rtId)->where('pelapor', '!=', $nama)
                    ->latest()->take(4)->get(),
            ]);
        })->name('aduan');

        // Form tambah di halaman terpisah; modal hanya untuk preview.
        Route::get('/pengaduan/baru', fn () => view('pages.user-aduan-create'))->name('aduan.create');

        Route::post('/pengaduan', function (Request $request) {
            $data = $request->validate([
                'judul' => 'required|string|max:255',
                'lokasi' => 'required|string|max:255',
                'deskripsi' => 'nullable|string|max:2000',
            ]);

            Pengaduan::create($data + ['pelapor' => auth()->user()->name, 'status' => 'Belum', 'rt_id' => auth()->user()->scopeRtId()]);

            return back()->with('success', 'Pengaduan berhasil dikirim.');
        })->name('aduan.store');

        Route::get('/pengaturan', fn () => view('pages.user-pengaturan'))->name('pengaturan');
    });

    // Developer: manajemen multi-tenant RT + otorisasi per RT.
    Route::middleware('role:developer')->prefix('dev')->name('dev.')->group(function (): void {
        Route::get('/rt', [RtController::class, 'index'])->name('rt.index');
        Route::post('/rt', [RtController::class, 'store'])->name('rt.store');
        Route::get('/rt/{rt}', [RtController::class, 'show'])->name('rt.show');
        Route::put('/rt/{rt}', [RtController::class, 'update'])->name('rt.update');
        Route::post('/rt/{rt}/admin', [RtController::class, 'assignAdmin'])->name('rt.assign-admin');
        Route::delete('/rt/{rt}/admin/{user}', [RtController::class, 'unassignAdmin'])->name('rt.unassign-admin');
        Route::post('/rt/{rt}/superadmin', [RtController::class, 'attachSuperAdmin'])->name('rt.attach-superadmin');
        Route::delete('/rt/{rt}/superadmin/{user}', [RtController::class, 'detachSuperAdmin'])->name('rt.detach-superadmin');
    });

    // Halaman admin (minimal peran Pengelola). Data dari database.
    Route::middleware('role:admin')->group(function (): void {
        // Antrean verifikasi NIK: hanya yang lolos gate otomatis.
        Route::get('/verifikasi-nik', [WargaVerificationController::class, 'index'])->name('verifikasi.index');
        Route::post('/verifikasi-nik/{claim}/setujui', [WargaVerificationController::class, 'approve'])->name('verifikasi.approve');
        Route::post('/verifikasi-nik/{claim}/tolak', [WargaVerificationController::class, 'reject'])->name('verifikasi.reject');
        Route::get('/data-warga', [WargaController::class, 'index'])->name('warga.index');
        Route::get('/data-keluarga', function (Request $request) {
            $rtId = auth()->user()->scopeRtId();
            $query = Keluarga::forRt($rtId)->withCount('wargas')->orderBy('kepala_keluarga')
                ->when($request->q, fn ($q, $v) => $q->where(fn ($q) => $q->where('no_kk', 'like', "%{$v}%")->orWhere('kepala_keluarga', 'like', "%{$v}%")));

            return view('pages.data-keluarga', [
                'keluargas' => (clone $query)->paginate(TablePage::perPage($request, $query))->withQueryString(),
                'totalKk' => Keluarga::count(),
            ]);
        })->name('keluarga.index');
        Route::get('/administrasi-surat', function () {
            $prioritas = "CASE status WHEN 'Menunggu' THEN 0 WHEN 'Diproses' THEN 1 WHEN 'Selesai' THEN 2 ELSE 3 END";
            $rtId = auth()->user()->scopeRtId();
            $counts = Cache::remember('surat:counts:'.$rtId, 60, fn () => [
                'masuk' => SuratPengajuan::forRt($rtId)->where('status', 'Menunggu')->count(),
                'diproses' => SuratPengajuan::forRt($rtId)->where('status', 'Diproses')->count(),
                'selesai' => SuratPengajuan::forRt($rtId)->where('status', 'Selesai')->count(),
                'ditolak' => SuratPengajuan::forRt($rtId)->where('status', 'Ditolak')->count(),
            ]);

            return view('pages.surat-index', $counts + [
                'rows' => SuratPengajuan::forRt($rtId)->orderByRaw($prioritas)->latest()->take(8)->get(),
            ]);
        })->name('surat.index');
        Route::post('/administrasi-surat/{surat}/tolak', function (Request $request, SuratPengajuan $surat) {
            abort_if($surat->status !== 'Menunggu', 422, 'Hanya pengajuan Menunggu yang dapat ditolak.');

            $data = $request->validate(['alasan' => 'required|string|min:10']);

            $surat->update(['status' => 'Ditolak', 'catatan' => strip_tags($data['alasan'], '<b><i><u><ul><ol><li><p><br>')]);
            Cache::forget('surat:counts:'.auth()->user()->scopeRtId());
            Cache::forget('dash:stats:'.auth()->user()->scopeRtId());

            return back()->with('success', "Pengajuan {$surat->pemohon} ditolak.");
        })->name('surat.reject');
        Route::get('/pengajuan-surat', function () {
            return view('pages.surat-pengajuan', [
                'antrean' => SuratPengajuan::whereIn('status', ['Menunggu', 'Diproses'])->latest()->get(),
                'menunggu' => SuratPengajuan::where('status', 'Menunggu')->count(),
                'diproses' => SuratPengajuan::where('status', 'Diproses')->count(),
                'selesaiHariIni' => SuratPengajuan::where('status', 'Selesai')->whereDate('updated_at', today())->count(),
            ]);
        })->name('surat.pengajuan');
        Route::get('/semua-pengajuan', function (Request $request) {
            $rtId = auth()->user()->scopeRtId();
            $query = SuratPengajuan::forRt($rtId)
                ->when($request->tanggal, fn ($q, $v) => $q->whereDate('created_at', $v));
            $rows = (clone $query)->latest()->paginate(TablePage::perPage($request, $query))->withQueryString();
            $counts = Cache::remember('surat:counts:'.$rtId, 60, fn () => [
                'total' => SuratPengajuan::forRt($rtId)->count(),
                'menunggu' => SuratPengajuan::forRt($rtId)->where('status', 'Menunggu')->count(),
                'diproses' => SuratPengajuan::forRt($rtId)->where('status', 'Diproses')->count(),
                'selesai' => SuratPengajuan::forRt($rtId)->where('status', 'Selesai')->count(),
            ]);

            return view('pages.surat-semua', ['rows' => $rows] + $counts);
        })->name('surat.semua');
        Route::get('/pengajuan-surat/{surat}', function (SuratPengajuan $surat) {
            abort_if($surat->rt_id && ! auth()->user()->canAccessRt($surat->rt_id), 403, 'Akses ke RT ini ditolak.');

            return view('pages.surat-detail', ['surat' => $surat]);
        })->name('surat.detail');
        Route::get('/pengaduan-warga', function () {
            $rtId = auth()->user()->scopeRtId();
            $counts = Cache::remember('aduan:counts:'.$rtId, 60, fn () => [
                'belum' => Pengaduan::forRt($rtId)->where('status', 'Belum')->count(),
                'proses' => Pengaduan::forRt($rtId)->where('status', 'Proses')->count(),
                'selesai' => Pengaduan::forRt($rtId)->where('status', 'Selesai')->count(),
                'ditolak' => Pengaduan::forRt($rtId)->where('status', 'Ditolak')->count(),
                'total' => Pengaduan::forRt($rtId)->count(),
            ]);

            return view('pages.aduan-index', ['cards' => Pengaduan::forRt($rtId)->latest()->take(8)->get()] + $counts);
        })->name('aduan.index');
        Route::delete('/pengaduan-warga/{pengaduan}', function (Pengaduan $pengaduan) {
            $pengaduan->delete();
            Cache::forget('aduan:counts:'.auth()->user()->scopeRtId());
            Cache::forget('dash:stats:'.auth()->user()->scopeRtId());

            return back()->with('success', 'Pengaduan berhasil dihapus.');
        })->name('aduan.destroy');
        Route::get('/iuran', function () {
            $periode = '2026-09';
            $rtId = auth()->user()->scopeRtId();
            $sums = Cache::remember("iuran:sums:{$periode}:{$rtId}", 120, fn () => [
                'terkumpul' => IuranPembayaran::forRt($rtId)->where('periode', $periode)->where('status', 'Lunas')->sum('jumlah'),
                'lunas' => IuranPembayaran::forRt($rtId)->where('periode', $periode)->where('status', 'Lunas')->count(),
            ]);

            return view('pages.iuran-index', $sums + [
                'menunggak' => IuranPembayaran::forRt($rtId)->where('periode', $periode)->where('status', 'Menunggak')->get(),
                'history' => IuranPembayaran::forRt($rtId)->where('periode', $periode)->where('status', 'Lunas')->latest('tanggal_bayar')->take(5)->get(),
            ]);
        })->name('iuran.index');
        Route::get('/kas-rt', function () {
            $rtId = auth()->user()->scopeRtId();
            $series = Cache::remember('kas:series:'.$rtId, 120, function () use ($rtId) {
                return collect(range(5, 0))->map(function ($i) use ($rtId) {
                    $start = today()->subMonths($i)->startOfMonth();
                    $end = today()->subMonths($i)->endOfMonth();
                    $q = KasTransaksi::forRt($rtId)->whereBetween('tanggal', [$start, $end]);

                    return [
                        'label' => $start->translatedFormat('M'),
                        'masuk' => (clone $q)->where('arah', 'masuk')->sum('jumlah'),
                        'keluar' => (clone $q)->where('arah', 'keluar')->sum('jumlah'),
                    ];
                });
            });
            $sums = Cache::remember('kas:sums:'.$rtId, 120, fn () => [
                'masuk' => KasTransaksi::forRt($rtId)->where('arah', 'masuk')->sum('jumlah'),
                'keluar' => KasTransaksi::forRt($rtId)->where('arah', 'keluar')->sum('jumlah'),
                'jumlahTransaksi' => KasTransaksi::forRt($rtId)->count(),
            ]);

            return view('pages.kas-index', $sums + [
                'transaksi' => KasTransaksi::forRt($rtId)->latest('tanggal')->take(5)->get(),
                'besar' => KasTransaksi::forRt($rtId)->where('arah', 'keluar')->orderByDesc('jumlah')->take(3)->get(),
                'series' => $series,
            ]);
        })->name('kas.index');
        Route::get('/kegiatan-warga', function (Request $request) {
            $rtId = auth()->user()->scopeRtId();
            $semua = Kegiatan::forRt($rtId)->orderBy('tanggal')->get();
            $denganStatus = $semua->map(fn ($k) => ['model' => $k, 'status' => $k->statusEfektif()]);

            $total = $semua->count();
            $selesai = $denganStatus->where('status', 'selesai')->count();
            $akanDatang = $denganStatus->where('status', 'akan_datang')->count();
            $dibatalkan = $denganStatus->where('status', 'dibatalkan')->count();

            $katCount = $semua->groupBy('kategori')->map->count()->sortDesc();
            $mendatang = $denganStatus->where('status', 'akan_datang')->sortBy('model.tanggal')->take(3)->map->model;

            $bulan = $request->filled('bulan')
                ? Carbon::parse($request->bulan)->startOfMonth()
                : today()->startOfMonth();
            $awal = $bulan->copy()->startOfMonth();
            $offset = ($awal->dayOfWeek + 6) % 7; // Senin = 0
            $mulai = $awal->copy()->subDays($offset);
            $kalender = collect(range(0, 41))->map(fn ($i) => $mulai->copy()->addDays($i));
            $acara = $semua->groupBy(fn ($k) => $k->tanggal->toDateString());

            $terbaru = Kegiatan::forRt($rtId)->when($request->kategori, fn ($q, $v) => $q->where('kategori', $v))
                ->latest('tanggal')->paginate(TablePage::perPage($request, Kegiatan::forRt($rtId), 5))->withQueryString();

            return view('pages.agenda-index', [
                'total' => $total,
                'selesai' => $selesai,
                'akanDatang' => $akanDatang,
                'dibatalkan' => $dibatalkan,
                'katCount' => $katCount,
                'mendatang' => $mendatang,
                'kalender' => $kalender,
                'acara' => $acara,
                'bulan' => $bulan,
                'terbaru' => $terbaru,
                'kategoris' => Kegiatan::forRt($rtId)->distinct()->pluck('kategori'),
            ]);
        })->name('agenda.index');
        Route::get('/pengaturan', fn () => view('pages.pengaturan-index'))->name('pengaturan.index');
        Route::get('/laporan-keuangan', function (Request $request) {
            $rtId = auth()->user()->scopeRtId();
            $query = KasTransaksi::forRt($rtId)
                ->when($request->q, fn ($q, $v) => $q->where('deskripsi', 'like', "%{$v}%"))
                ->when($request->arah, fn ($q, $v) => $q->where('arah', $v));
            $transaksi = (clone $query)->latest('tanggal')->paginate(TablePage::perPage($request, $query))->withQueryString();

            return view('pages.laporan-index', Cache::remember('lap:sums:'.$rtId, 120, fn () => [
                'masuk' => KasTransaksi::forRt($rtId)->where('arah', 'masuk')->sum('jumlah'),
                'keluar' => KasTransaksi::forRt($rtId)->where('arah', 'keluar')->sum('jumlah'),
            ]) + [
                'transaksi' => $transaksi,
            ]);
        })->name('laporan.index');
        Route::post('/laporan-keuangan', function (Request $request) {
            $data = $request->validate([
                'tanggal' => 'required|date',
                'deskripsi' => 'required|string|max:255',
                'kategori' => 'nullable|string|max:100',
                'arah' => 'required|in:masuk,keluar',
                'jumlah' => 'required|integer|min:1',
            ]);

            KasTransaksi::create($data + ['rt_id' => auth()->user()->scopeRtId()]);
            Cache::forget('kas:sums:'.auth()->user()->scopeRtId());
            Cache::forget('lap:sums:'.auth()->user()->scopeRtId());
            Cache::forget('kas:series:'.auth()->user()->scopeRtId());
            Cache::forget('dash:stats:'.auth()->user()->scopeRtId());

            return back()->with('success', 'Transaksi berhasil ditambahkan.');
        })->name('laporan.store');
        Route::get('/laporan-keuangan/export', function (Request $request) {
            $rows = KasTransaksi::forRt(auth()->user()->scopeRtId())
                ->when($request->q, fn ($q, $v) => $q->where('deskripsi', 'like', "%{$v}%"))
                ->when($request->arah, fn ($q, $v) => $q->where('arah', $v))
                ->latest('tanggal')->get();

            $csv = "tanggal,deskripsi,kategori,arah,jumlah\n";
            foreach ($rows as $r) {
                $csv .= implode(',', [$r->tanggal->toDateString(), '"'.str_replace('"', '""', $r->deskripsi).'"', '"'.str_replace('"', '""', $r->kategori ?? '').'"', $r->arah, $r->jumlah])."\n";
            }

            return response($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="laporan-keuangan.csv"',
            ]);
        })->name('laporan.export');
        Route::post('/laporan-keuangan/import', function (Request $request) {
            $request->validate(['file' => 'required|file|mimes:csv,txt|max:2048']);

            $count = 0;
            $handle = fopen($request->file('file')->getRealPath(), 'r');
            $header = fgetcsv($handle);
            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($header, $row);
                if (! $data || ($data['arah'] ?? null) !== 'masuk' && ($data['arah'] ?? null) !== 'keluar') {
                    continue;
                }
                if (empty($data['deskripsi']) || ! is_numeric($data['jumlah'] ?? null)) {
                    continue;
                }
                KasTransaksi::create([
                    'rt_id' => auth()->user()->scopeRtId(),
                    'tanggal' => $data['tanggal'] ?? today()->toDateString(),
                    'deskripsi' => $data['deskripsi'],
                    'kategori' => $data['kategori'] ?? null,
                    'arah' => $data['arah'],
                    'jumlah' => (int) $data['jumlah'],
                ]);
                $count++;
            }
            fclose($handle);
            Cache::forget('kas:sums:'.auth()->user()->scopeRtId());
            Cache::forget('lap:sums:'.auth()->user()->scopeRtId());
            Cache::forget('kas:series:'.auth()->user()->scopeRtId());
            Cache::forget('dash:stats:'.auth()->user()->scopeRtId());

            return back()->with('success', "{$count} transaksi berhasil diimpor.");
        })->name('laporan.import');
        Route::get('/pengumuman-admin', function (Request $request) {
            $rtId = auth()->user()->scopeRtId();
            $query = Pengumuman::forRt($rtId)
                ->when($request->q, fn ($q, $v) => $q->where('judul', 'like', "%{$v}%"))
                ->when($request->kategori, fn ($q, $v) => $q->where('kategori', $v))
                ->when($request->status, fn ($q, $v) => $q->where('status', $v));

            $sort = $request->sort === 'terlama' ? 'asc' : 'desc';
            $items = (clone $query)->orderBy('published_at', $sort)->orderBy('id', $sort)->paginate(TablePage::perPage($request, $query, 5))->withQueryString();

            return view('pages.pengumuman-index', [
                'items' => $items,
                'total' => Pengumuman::forRt($rtId)->count(),
                'terbit' => Pengumuman::forRt($rtId)->where('status', 'terbit')->count(),
                'terjadwal' => Pengumuman::forRt($rtId)->where('status', 'terjadwal')->count(),
                'draf' => Pengumuman::forRt($rtId)->where('status', 'draf')->count(),
                'kategoris' => Pengumuman::forRt($rtId)->distinct()->pluck('kategori'),
            ]);
        })->name('pengumuman.index');
        Route::get('/galeri', function () {
            return view('pages.galeri-index', [
                'items' => SiteContent::posts(),
            ]);
        })->name('galeri.index');
    });
});

$statusBadge = fn ($s) => match ($s) {
    'Berlangsung' => 'bg-rose-600',
    'Segera' => 'bg-cobalt-500',
    'Selesai' => 'bg-slate-400',
    default => 'bg-slate-400',
};

Route::get('/kegiatan/{id}', function ($id) use ($statusBadge) {
    $item = collect(SiteContent::events())->firstWhere('id', (int) $id);
    abort_if(! $item, 404);
    $related = collect(SiteContent::events())
        ->where('id', '!=', $item['id'])->take(3)
        ->map(fn ($e) => ['url' => route('kegiatan.show', $e['id']), 'img' => $e['img'], 'date' => $e['date'], 'title' => $e['title']])
        ->values()->all();

    return view('pages.detail', [
        'title' => $item['title'],
        'section' => 'Kegiatan',
        'sectionUrl' => route('kegiatan'),
        'img' => $item['img'],
        'date' => $item['date'].' • '.$item['time'],
        'badge' => $item['status'],
        'badgeClass' => $statusBadge($item['status']),
        'extraMeta' => $item['loc'],
        'paragraphs' => [$item['desc']],
        'related' => $related,
    ]);
})->whereNumber('id')->name('kegiatan.show');

$catBadge = fn ($c) => match ($c) {
    'Pengumuman' => 'bg-cobalt-500',
    'Informasi' => 'bg-forest-500',
    'Peringatan' => 'bg-amber-500',
    default => 'bg-slate-400',
};

Route::get('/pengumuman/{id}', function ($id) use ($catBadge) {
    $item = collect(SiteContent::announcements())->firstWhere('id', (int) $id);
    abort_if(! $item, 404);
    $related = collect(SiteContent::announcements())
        ->where('id', '!=', $item['id'])->take(3)
        ->map(fn ($a) => ['url' => route('pengumuman.show', $a['id']), 'img' => $a['img'], 'date' => $a['date'], 'title' => $a['title']])
        ->values()->all();

    return view('pages.detail', [
        'title' => $item['title'],
        'section' => 'Pengumuman',
        'sectionUrl' => route('pengumuman'),
        'img' => $item['img'],
        'date' => $item['date'],
        'badge' => $item['cat'],
        'badgeClass' => $catBadge($item['cat']),
        'extraMeta' => null,
        'paragraphs' => [$item['body']],
        'related' => $related,
    ]);
})->whereNumber('id')->name('pengumuman.show');

Route::get('/berita/{slug}', function ($slug) {
    $item = collect(SiteContent::posts())->firstWhere('slug', $slug);
    abort_if(! $item, 404);
    $related = collect(SiteContent::posts())
        ->where('slug', '!=', $item['slug'])->take(3)
        ->map(fn ($p) => ['url' => route('berita.show', $p['slug']), 'img' => $p['img'], 'date' => $p['date'], 'title' => $p['title']])
        ->values()->all();

    return view('pages.detail', [
        'title' => $item['title'],
        'section' => 'Berita',
        'sectionUrl' => url('/#berita'),
        'img' => $item['img'],
        'date' => $item['date'],
        'badge' => null,
        'badgeClass' => null,
        'extraMeta' => $item['city'],
        'paragraphs' => [$item['desc']],
        'related' => $related,
    ]);
})->name('berita.show');
