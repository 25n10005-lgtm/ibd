<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserWargaLink;
use App\Models\Warga;
use App\Models\WargaClaim;
use Illuminate\Support\Carbon;

class NikVerificationService
{
    /**
     * 4 gate otomatis sebelum warga boleh mengajukan verifikasi:
     * NIK ditemukan -> belum terhubung akun lain -> RT sama ->
     * data dasar cocok (No KK + nama + tanggal lahir).
     *
     * @param  array{nik: string, no_kk: string, nama: string, tanggal_lahir: string}  $input
     * @return array{ok: bool, failed_gate: ?string, message: string, warga: ?Warga, checks: array<string, array{passed: bool, detail: string}>}
     */
    public function check(User $user, array $input): array
    {
        $nik = preg_replace('/\D/', '', (string) $input['nik']);
        $noKk = preg_replace('/\D/', '', (string) $input['no_kk']);
        $nama = $this->normalizeName((string) $input['nama']);
        $tanggalLahir = Carbon::parse($input['tanggal_lahir'])->toDateString();

        $checks = [];
        $fail = fn (string $gate, string $message, ?Warga $warga = null) => [
            'ok' => false,
            'failed_gate' => $gate,
            'message' => $message,
            'warga' => $warga,
            'checks' => $checks,
        ];

        // Gate 1: NIK ditemukan.
        $warga = Warga::where('nik', $nik)->with('keluarga')->first();
        $checks['nik_found'] = [
            'passed' => $warga !== null,
            'detail' => $warga !== null ? '' : 'NIK tidak terdaftar.',
        ];
        if ($warga === null) {
            return $fail('nik_found', 'Data tidak ditemukan. Periksa kembali NIK dan No KK, atau hubungi pengurus RT.');
        }

        // Gate 2: NIK belum terhubung ke akun lain.
        $linked = UserWargaLink::where('warga_id', $warga->id)->exists();
        $checks['not_linked'] = [
            'passed' => ! $linked,
            'detail' => $linked ? 'NIK sudah terhubung akun lain.' : '',
        ];
        if ($linked) {
            return $fail('not_linked', 'NIK ini sudah terhubung dengan akun lain. Hubungi pengurus RT bila ini data Anda.', $warga);
        }

        // Gate 3: NIK berada di RT yang sama dengan akun.
        $wargaRtId = $warga->keluarga?->rt_id;
        $userRtId = $user->scopeRtId();
        $sameRt = $wargaRtId !== null && $userRtId !== null && (int) $wargaRtId === (int) $userRtId;
        $checks['same_rt'] = [
            'passed' => $sameRt,
            'detail' => $sameRt ? '' : 'NIK terdaftar di RT lain.',
        ];
        if (! $sameRt) {
            return $fail('same_rt', 'NIK ini terdaftar di RT lain dan tidak dapat diverifikasi dengan akun Anda.', $warga);
        }

        // Gate 4: data dasar cocok — No KK + nama + tanggal lahir.
        // Anggota keluarga yang belum punya NIK tidak diverifikasi satu per
        // satu; cukup NIK pendaftar sebagai jangkar verifikasi.
        $kkMatch = $warga->keluarga !== null && preg_replace('/\D/', '', $warga->keluarga->no_kk) === $noKk;
        $namaMatch = $this->normalizeName($warga->nama) === $nama;
        $tglMatch = $warga->tanggal_lahir === null || $warga->tanggal_lahir->toDateString() === $tanggalLahir;
        $basicOk = $kkMatch && $namaMatch && $tglMatch;
        $checks['basic_match'] = [
            'passed' => $basicOk,
            'detail' => $basicOk ? '' : 'No KK, nama, atau tanggal lahir tidak cocok.',
        ];
        if (! $basicOk) {
            return $fail('basic_match', 'Data tidak cocok. Pastikan No KK, nama lengkap, dan tanggal lahir sesuai KK/KTP.', $warga);
        }

        // Tertaut NIK yang sama -> tolak. Tertaut NIK berbeda ->
        // boleh lanjut sebagai penggantian (ganti data via verifikasi
        // ulang; tautan lama diganti saat pengajuan disetujui).
        $ownLink = UserWargaLink::where('user_id', $user->id)->first();
        if ($ownLink && (int) $ownLink->warga_id === (int) $warga->id) {
            return [
                'ok' => false,
                'failed_gate' => 'already_linked',
                'message' => 'Akun Anda sudah terhubung dengan NIK ini.',
                'warga' => $warga,
                'checks' => $checks,
            ];
        }

        if (WargaClaim::where('user_id', $user->id)->where('status', WargaClaim::STATUS_DIAJUKAN)->exists()) {
            return [
                'ok' => false,
                'failed_gate' => 'claim_open',
                'message' => 'Anda masih memiliki pengajuan yang menunggu verifikasi pengurus.',
                'warga' => $warga,
                'checks' => $checks,
            ];
        }

        return [
            'ok' => true,
            'failed_gate' => null,
            'message' => 'Data cocok. Silakan konfirmasi dan ajukan verifikasi.',
            'warga' => $warga,
            'checks' => $checks,
        ];
    }

    public function normalizeName(string $nama): string
    {
        return (string) preg_replace('/\s+/', ' ', mb_strtolower(trim($nama)));
    }

    /**
     * Sensor PII untuk layar konfirmasi: Andi Saputra -> A*** S******.
     */
    public function maskName(string $nama): string
    {
        $parts = preg_split('/\s+/', trim($nama), -1, PREG_SPLIT_NO_EMPTY);

        return collect($parts)->map(function (string $part): string {
            $first = mb_substr($part, 0, 1);

            return mb_strtoupper($first).str_repeat('*', max(2, mb_strlen($part) - 1));
        })->implode(' ');
    }

    /**
     * Sensor NIK: 3174051201900001 -> 3174********0001.
     */
    public function maskNik(string $nik): string
    {
        $digits = preg_replace('/\D/', '', $nik);
        if (strlen($digits) !== 16) {
            return '****';
        }

        return substr($digits, 0, 4).str_repeat('*', 8).substr($digits, -4);
    }
}
