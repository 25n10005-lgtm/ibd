{{-- ===== Data Keluarga (Warga) ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Keluarga — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php $user = auth()->user(); @endphp
<div class="flex min-h-screen">
    @include('user.partials.sidebar', ['user' => $user, 'active' => 'keluarga'])

    <main class="min-w-0 flex-1 p-7">
        <x-page-header title="Data Keluarga" subtitle="Kartu keluarga dan anggota rumah tangga Anda.">
            @if (($link ?? null) || ($keluarga ?? null))
                <a href="{{ route('saya.keluarga.verifikasi') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-[12.5px] font-bold text-slate-600 transition hover:bg-slate-50">
                    <x-hi name="pencil" width="14" height="14" /> Ganti / Perbarui
                </a>
            @endif
        </x-page-header>

        @if (session('success'))
            <p class="mt-4 rounded-xl bg-green-50 px-4 py-3 text-[13px] font-medium text-green-700">{{ session('success') }}</p>
        @endif

        @if (($link ?? null))
            <p class="mt-5 inline-flex items-center gap-1.5 rounded-xl bg-[#ECFDF5] px-4 py-2.5 text-[12.5px] font-bold text-[#047857]">
                <x-hi name="shield" width="15" height="15" /> NIK terverifikasi sejak {{ $link->verified_at?->translatedFormat('d M Y') }} — ubah data melalui verifikasi ulang
            </p>
        @elseif (($openClaim ?? null))
            <div class="mt-5 flex flex-wrap items-center gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-5">
                <div class="min-w-0 flex-1">
                    <p class="text-[14px] font-bold text-amber-800">Pengajuan verifikasi sedang diproses</p>
                    <p class="mt-0.5 text-[12.5px] text-amber-700">Diajukan {{ $openClaim->created_at->diffForHumans() }}. Menunggu verifikasi pengurus RT.</p>
                </div>
                <form method="POST" action="{{ route('saya.keluarga.verifikasi.batal', $openClaim) }}">
                    @csrf
                    <button type="submit" class="rounded-xl border border-amber-300 bg-white px-4 py-2 text-[12px] font-bold text-amber-700 transition hover:bg-amber-100">Batalkan</button>
                </form>
            </div>
        @elseif (($lastClaim ?? null) && $lastClaim->status === 'ditolak')
            <div class="mt-5 rounded-2xl border border-rose-200 bg-rose-50 p-5">
                <p class="text-[14px] font-bold text-rose-700">Pengajuan terakhir ditolak</p>
                <p class="mt-0.5 text-[12.5px] text-rose-600">{{ $lastClaim->decision?->reason }}</p>
                <a href="{{ route('saya.keluarga.verifikasi') }}" class="mt-3 inline-block rounded-xl bg-rose-600 px-5 py-2 text-[12.5px] font-bold text-white transition hover:bg-rose-700">Coba Lagi</a>
            </div>
        @endif

        @if (! $keluarga)
            {{-- Struktur kosong: kerangka KK + anggota tampil dulu, CTA mengarah ke verifikasi. --}}
            <section class="mt-5 rounded-2xl border border-dashed border-slate-300 bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                        <x-hi name="building" width="22" height="22" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="h-4 w-48 animate-pulse rounded bg-slate-100"></div>
                        <div class="mt-2 h-3 w-64 animate-pulse rounded bg-slate-50"></div>
                    </div>
                    <span class="rounded-lg bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-400">0 anggota</span>
                </div>
            </section>

            <section class="mt-4 overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="space-y-3 p-5">
                    @for ($i = 0; $i < 3; $i++)
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 shrink-0 animate-pulse rounded-full bg-slate-100"></div>
                            <div class="flex-1">
                                <div class="h-3.5 w-2/5 animate-pulse rounded bg-slate-100"></div>
                                <div class="mt-1.5 h-3 w-3/5 animate-pulse rounded bg-slate-50"></div>
                            </div>
                            <div class="h-6 w-16 animate-pulse rounded-lg bg-slate-100"></div>
                        </div>
                    @endfor
                </div>
                <div class="border-t border-slate-100 bg-slate-50/60 p-5 text-center">
                    <p class="text-[14px] font-bold">Data keluarga belum terhubung</p>
                    <p class="mx-auto mt-1 max-w-md text-[12.5px] text-slate-500">Hubungkan NIK Anda secara mandiri — cukup isi NIK, No KK, nama, dan tanggal lahir sesuai KK/KTP. Sistem mencocokkan otomatis, pengurus tinggal menyetujui.</p>
                    <a href="{{ route('saya.keluarga.verifikasi') }}" class="mt-4 inline-block rounded-xl bg-[#047857] px-6 py-2.5 text-[12.5px] font-bold text-white transition hover:bg-green-800">Hubungkan NIK Saya</a>
                </div>
            </section>
        @else
            <section class="mt-5 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#ECFDF5] text-[#047857]">
                        <x-hi name="building" width="22" height="22" />
                    </span>
                    <div>
                        <p class="text-[15px] font-extrabold">KK {{ $keluarga->no_kk }}</p>
                        <p class="text-[12px] text-slate-500">{{ $keluarga->kepala_keluarga }} • {{ $keluarga->alamat }}</p>
                    </div>
                    <span class="ml-auto rounded-lg bg-[#ECFDF5] px-3 py-1 text-[11px] font-bold text-[#047857]">{{ $keluarga->wargas->count() }} anggota</span>
                </div>
            </section>

            <section class="mt-4 overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[560px] text-left text-[12.5px]">
                        <thead>
                            <tr class="border-y border-slate-100 text-[10.5px] font-bold tracking-wider text-slate-400 uppercase">
                                <th class="px-5 py-3">Nama</th>
                                <th class="px-5 py-3">NIK</th>
                                <th class="px-5 py-3">Status Tinggal</th>
                                <th class="px-5 py-3">No. HP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($keluarga->wargas as $w)
                                @php $isSaya = ($link ?? null)?->warga_id === $w->id || $w->nama === $user->name; @endphp
                                <tr class="transition hover:bg-slate-50/60">
                                    <td class="px-5 py-3 font-bold">{{ $w->nama }}{{ $isSaya ? ' (Saya)' : '' }}</td>
                                    <td class="px-5 py-3 font-mono text-slate-500">{{ $isSaya ? $w->nik : substr($w->nik, 0, 4).str_repeat('*', 8).substr($w->nik, -4) }}</td>
                                    <td class="px-5 py-3"><span class="rounded-lg px-2.5 py-1 text-[11px] font-bold {{ $w->status_tinggal === 'Tetap' ? 'bg-[#ECFDF5] text-[#047857]' : 'bg-amber-50 text-amber-600' }}">{{ $w->status_tinggal }}</span></td>
                                    <td class="px-5 py-3 text-slate-500">{{ $w->no_hp ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
    </main>
</div>
</body>
</html>
