<?php

namespace App\Http\Controllers;

use App\Models\UserWargaLink;
use App\Models\WargaClaim;
use App\Models\WargaClaimCheck;
use App\Models\WargaClaimDecision;
use App\Services\NikVerificationService;
use App\Support\TablePage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WargaVerificationController extends Controller
{
    public function __construct(private NikVerificationService $verifier) {}

    /**
     * Form hubungkan NIK (langkah 1). Akun tertaut boleh membuka
     * untuk ganti NIK; persetujuan baru menggantikan tautan lama.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        $openClaim = WargaClaim::where('user_id', $user->id)
            ->where('status', WargaClaim::STATUS_DIAJUKAN)
            ->latest()
            ->first();

        return view('pages.user-keluarga-verifikasi', [
            'openClaim' => $openClaim,
            'link' => UserWargaLink::where('user_id', $user->id)->with('warga')->first(),
        ]);
    }

    /**
     * Jalankan 4 gate otomatis. Lolos -> layar konfirmasi tersensor
     * (data disimpan di sesi, bukan DB). Gagal -> kembali + alasan.
     */
    public function check(Request $request): View|RedirectResponse
    {
        $data = $request->validate([
            'nik' => ['required', 'regex:/^\d{16}$/'],
            'no_kk' => ['required', 'regex:/^\d{16}$/'],
            'nama' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
        ], [
            'nik.regex' => 'NIK harus 16 digit angka.',
            'no_kk.regex' => 'No KK harus 16 digit angka.',
        ]);

        $result = $this->verifier->check($request->user(), $data);

        if (! $result['ok']) {
            return back()->withErrors(['nik' => $result['message']])->onlyInput('nik', 'no_kk', 'nama', 'tanggal_lahir');
        }

        $warga = $result['warga'];
        $request->session()->put('nik_claim', [
            'warga_id' => $warga->id,
            'nik' => $data['nik'],
            'no_kk' => $data['no_kk'],
            'nama' => $data['nama'],
            'tanggal_lahir' => $data['tanggal_lahir'],
        ]);

        return view('pages.user-keluarga-konfirmasi', [
            'maskedNama' => $this->verifier->maskName($warga->nama),
            'maskedNik' => $this->verifier->maskNik($warga->nik),
            'keluargaKk' => $warga->keluarga?->no_kk,
            'input' => $data,
        ]);
    }

    /**
     * Ajukan verifikasi ke pengurus. Gate dihitung ulang dari sesi
     * agar permintaan tidak bisa dipalsukan dari form konfirmasi.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $pending = $request->session()->get('nik_claim');
        if (! is_array($pending)) {
            return redirect()->route('saya.keluarga.verifikasi');
        }

        $user = $request->user();
        $result = $this->verifier->check($user, $pending);

        if (! $result['ok']) {
            $request->session()->forget('nik_claim');

            if ($request->expectsJson()) {
                return response()->json(['message' => $result['message']], 422);
            }

            return redirect()->route('saya.keluarga.verifikasi')->withErrors(['nik' => $result['message']]);
        }

        $claim = DB::transaction(function () use ($user, $result): WargaClaim {
            $claim = WargaClaim::create([
                'user_id' => $user->id,
                'warga_id' => $result['warga']->id,
                'status' => WargaClaim::STATUS_DIAJUKAN,
            ]);

            foreach ($result['checks'] as $type => $check) {
                WargaClaimCheck::create([
                    'claim_id' => $claim->id,
                    'check_type' => $type,
                    'passed' => $check['passed'],
                    'detail' => $check['detail'] ?? '',
                ]);
            }

            return $claim;
        });

        $request->session()->forget('nik_claim');

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Pengajuan verifikasi dikirim. Menunggu verifikasi pengurus.', 'claim_id' => $claim->id], 201);
        }

        return redirect()->route('saya.keluarga')->with('success', 'Pengajuan verifikasi dikirim. Menunggu verifikasi pengurus RT.');
    }

    /**
     * Batalkan antrean milik sendiri.
     */
    public function cancel(Request $request, WargaClaim $claim): RedirectResponse|JsonResponse
    {
        abort_if($claim->user_id !== $request->user()->id, 403);
        abort_if(! $claim->isOpen(), 422, 'Pengajuan sudah diproses.');

        $claim->update(['status' => WargaClaim::STATUS_DIBATALKAN]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Pengajuan dibatalkan.']);
        }

        return back()->with('success', 'Pengajuan verifikasi dibatalkan.');
    }

    /**
     * Antrean verifikasi untuk pengurus: hanya yang diajukan dan
     * sudah lolos gate otomatis (beban sortir minimal).
     */
    public function index(Request $request): View|JsonResponse
    {
        $rtId = $request->user()->scopeRtId();

        $query = WargaClaim::with(['user:id,name,email', 'warga.keluarga', 'checks'])
            ->where('status', WargaClaim::STATUS_DIAJUKAN)
            ->whereHas('warga.keluarga', fn ($q) => $q->forRt($rtId))
            ->latest();

        $claims = $query->paginate(TablePage::perPage($request, (clone $query)))->withQueryString();

        if ($request->expectsJson()) {
            return response()->json($claims);
        }

        return view('pages.verifikasi-index', ['claims' => $claims]);
    }

    /**
     * Setujui: buat tautan 1 akun <-> 1 NIK + catat keputusan.
     */
    public function approve(Request $request, WargaClaim $claim): RedirectResponse|JsonResponse
    {
        abort_if(! $claim->isOpen(), 422, 'Pengajuan sudah diproses.');
        abort_if(! $request->user()->canAccessRt((int) $claim->warga->keluarga?->rt_id), 403, 'Akses ke RT ini ditolak.');

        DB::transaction(function () use ($request, $claim): void {
            // Penggantian: hapus tautan lama akun ini lebih dulu
            // (UNIQUE user_id/warga_id melarang duplikat).
            UserWargaLink::where('user_id', $claim->user_id)->delete();

            UserWargaLink::create([
                'user_id' => $claim->user_id,
                'warga_id' => $claim->warga_id,
                'verified_by' => $request->user()->id,
                'verified_at' => now(),
            ]);

            // Isi RT akun yang masih kosong dari data warga.
            if ($claim->user->rt_id === null && $claim->warga->keluarga?->rt_id) {
                $claim->user->update(['rt_id' => $claim->warga->keluarga->rt_id]);
            }

            $claim->update(['status' => WargaClaim::STATUS_DISETUJUI]);

            WargaClaimDecision::create([
                'claim_id' => $claim->id,
                'decided_by' => $request->user()->id,
                'decision' => WargaClaim::STATUS_DISETUJUI,
                'reason' => '',
                'decided_at' => now(),
            ]);
        });

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Verifikasi disetujui. Akun tertaut dengan NIK.']);
        }

        return back()->with('success', 'Verifikasi disetujui. Akun warga tertaut dengan NIK.');
    }

    /**
     * Tolak dengan alasan wajib (template agar cepat).
     */
    public function reject(Request $request, WargaClaim $claim): RedirectResponse|JsonResponse
    {
        abort_if(! $claim->isOpen(), 422, 'Pengajuan sudah diproses.');

        $data = $request->validate([
            'reason' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        DB::transaction(function () use ($request, $claim, $data): void {
            $claim->update(['status' => WargaClaim::STATUS_DITOLAK]);

            WargaClaimDecision::create([
                'claim_id' => $claim->id,
                'decided_by' => $request->user()->id,
                'decision' => WargaClaim::STATUS_DITOLAK,
                'reason' => $data['reason'],
                'decided_at' => now(),
            ]);
        });

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Pengajuan ditolak.']);
        }

        return back()->with('success', 'Pengajuan verifikasi ditolak.');
    }
}
