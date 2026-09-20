{{-- ===== Dashboard Admin (Ketua RT / Pengelola) ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f3f5fb] font-sans text-slate-900 antialiased">
@php
    $user = auth()->user();
    $firstName = str($user->name)->before(' ')->toString();
    $hour = (int) now()->format('H');
    $greeting = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 19 ? 'Selamat sore' : 'Selamat malam'));
    $rupiah = fn ($n) => 'Rp '.number_format($n, 0, ',', '.');
    $iuranLunas = \App\Models\IuranPembayaran::where('periode', '2026-09')->where('status', 'Lunas')->count();
    $iuranTotal = \App\Models\IuranPembayaran::where('periode', '2026-09')->count();
    $iuranPct = $iuranTotal > 0 ? round($iuranLunas / $iuranTotal * 100) : 0;
@endphp
<div class="flex min-h-screen">

    @include('admin.partials.sidebar', ['user' => $user, 'active' => 'dashboard'])

    {{-- ===== Main ===== --}}
    <main class="min-w-0 flex-1 px-5 py-5 sm:px-7">
        {{-- Topbar --}}
        <div class="flex flex-wrap items-center gap-3">
            <div class="mr-auto">
                <h1 class="text-xl font-extrabold tracking-tight sm:text-2xl">{{ $greeting }}, {{ $firstName }}👋</h1>
                <p class="mt-0.5 text-[12.5px] text-slate-500">Berikut ringkasan kondisi dan aktivitas RT 04 hari ini.</p>
            </div>
            <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-[12.5px] font-semibold text-slate-600">
                {{ now()->translatedFormat('l, d F Y') }}
                <x-hi name="cal" width="14" height="14" />
            </span>
            <button type="button" class="relative flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:text-slate-800" title="Notifikasi">
                <x-hi name="bell" width="17" height="17" />
                <span class="absolute -top-0.5 -right-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white">3</span>
            </button>
            <button type="button" class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-4 py-2 text-[12.5px] font-bold">
                RT 04 / RW 02
                <x-hi name="chev-down" width="13" height="13" />
            </button>
        </div>

        {{-- Stat cards --}}
        <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['Total Warga', $totalWarga, $totalWarga.' warga terdata', 'text-cobalt-600 bg-cobalt-50', 'users'],
                ['Iuran September', $iuranPct.'%', $iuranLunas.' dari '.$iuranTotal.' warga', 'text-forest-600 bg-forest-50', 'wallet'],
                ['Saldo Kas', $rupiah($saldo), 'Kas RT terkini', 'text-amber-500 bg-amber-50', 'wallet'],
                ['Perlu Ditangani', $perluDitangani, 'Surat & Pengaduan', 'text-rose-500 bg-rose-50', 'alert'],
            ] as [$label, $value, $sub, $tone, $icon])
                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_10px_30px_-18px_rgba(15,23,42,0.25)]">
                    <div class="flex items-center gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $tone }}">
                            <x-hi name="{{ $icon }}" width="19" height="19" />
                        </span>
                        <div class="min-w-0">
                            <p class="text-[12.5px] font-semibold text-slate-500">{{ $label }}</p>
                            <p class="text-[22px] leading-7 font-extrabold tracking-tight">{{ $value }}</p>
                        </div>
                    </div>
                    @if ($label === 'Iuran September')
                        <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-forest-500" style="width: {{ $iuranPct }}%"></div>
                        </div>
                        <p class="mt-1.5 text-[11.5px] text-slate-400">{{ $iuranLunas }} lunas / {{ $iuranTotal }} tagihan</p>
                    @elseif ($sub)
                        <p class="mt-2 text-[11.5px] font-medium {{ str_starts_with($sub, '↗') ? 'text-forest-600' : 'text-slate-400' }}">{{ $sub }}</p>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Middle row --}}
        <div class="mt-4 grid gap-4 xl:grid-cols-2">
            {{-- Perlu perhatian --}}
            <section class="rounded-2xl border border-slate-100 bg-white p-5">
                <div class="flex items-center gap-2">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-amber-400 text-[11px] font-extrabold text-white">!</span>
                    <h2 class="text-[15px] font-extrabold">Perlu perhatian</h2>
                    <a href="#" class="ml-auto text-[12.5px] font-bold text-cobalt-600 hover:text-cobalt-700">Lihat semua ›</a>
                </div>
                <div class="mt-4 space-y-3">
                    @foreach ([
                        ['border-rose-100 bg-rose-50/40', 'bg-rose-100 text-rose-500', 'doc', $suratMenunggu.' pengajuan surat menunggu diproses', 'Periksa antrean pengajuan', route('surat.pengajuan'), 'text-red-600'],
                        ['border-rose-100 bg-rose-50/40', 'bg-rose-100 text-rose-500', 'chat', $aduanBelum.' pengaduan belum ditangani', 'Tinjau pengaduan warga', route('aduan.index'), 'text-red-600'],
                        ['border-amber-100 bg-amber-50/40', 'bg-amber-100 text-amber-500', 'users', $iuranTotal - $iuranLunas.' warga belum membayar iuran September', 'Lihat daftar tunggakan', route('iuran.index'), 'text-amber-500'],
                    ] as [$cardClass, $iconClass, $icon, $title, $sub, $href, $linkColor])
                        <div class="flex items-center gap-3.5 rounded-xl border px-4 py-3.5 {{ $cardClass }}">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $iconClass }}">
                                <x-hi name="{{ $icon }}" width="17" height="17" />
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-[13px] font-bold">{{ $title }}</p>
                                <p class="text-[11.5px] text-slate-400">{{ $sub }}</p>
                            </div>
                            <a href="{{ $href }}" class="shrink-0 text-[12px] font-bold {{ $linkColor }}">Periksa →</a>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Ringkasan keuangan --}}
            <section class="rounded-2xl border border-slate-100 bg-white p-5">
                <div class="flex items-start">
                    <h2 class="text-[15px] font-extrabold">Ringkasan Keuangan</h2>
                    <div class="ml-auto text-right">
                        <p class="text-[12px] text-slate-400">September 2026</p>
                        <p class="mt-1 flex items-center justify-end gap-3 text-[11px] font-medium text-slate-500">
                            <span class="inline-flex items-center gap-1"><span class="h-2 w-2 rounded-sm bg-forest-500"></span>Pemasukan</span>
                            <span class="inline-flex items-center gap-1"><span class="h-2 w-2 rounded-sm bg-rose-500"></span>Pengeluaran</span>
                        </p>
                    </div>
                </div>
                <div class="mt-2 flex flex-wrap items-end gap-x-6">
                    <div>
                        <p class="text-[12px] text-slate-400">Saldo Kas</p>
                        <p class="text-[26px] leading-8 font-extrabold tracking-tight">{{ $rupiah($saldo) }}</p>
                    </div>
                    <svg viewBox="0 0 300 110" class="h-24 min-w-0 flex-1" preserveAspectRatio="none">
                        <text x="292" y="12" font-size="9" fill="#94a3b8">6 jt</text>
                        <text x="292" y="58" font-size="9" fill="#94a3b8">2 jt</text>
                        <polyline points="0,70 40,55 80,62 120,48 160,30 200,36 240,22 280,12" fill="none" stroke="#20a774" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <polyline points="0,80 40,78 80,80 120,74 160,70 200,72 240,66 280,62" fill="none" stroke="#f43f5e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="280" cy="12" r="3.5" fill="#20a774"/>
                        <circle cx="280" cy="62" r="3.5" fill="#f43f5e"/>
                    </svg>
                </div>
                <div class="mt-1 flex gap-8 text-[12px]">
                    <p class="text-slate-400">Pemasukan<br><span class="text-[13px] font-extrabold text-forest-600">{{ $rupiah($masuk) }}</span></p>
                    <p class="text-slate-400">Pengeluaran<br><span class="text-[13px] font-extrabold text-rose-500">{{ $rupiah($keluar) }}</span></p>
                </div>
                <div class="mt-2 flex items-center text-[11px] text-slate-300">
                    <span>1 Sep</span><span class="mx-auto">10 Sep</span><span class="mx-auto">20 Sep</span><span>30 Sep</span>
                </div>

                <div class="mt-4 border-t border-slate-100 pt-3">
                    <div class="flex items-center">
                        <h3 class="text-[13.5px] font-extrabold">Transaksi terbaru</h3>
                        <a href="#" class="ml-auto text-[12px] font-bold text-cobalt-600">Lihat semua</a>
                    </div>
                    <ul class="mt-2 divide-y divide-slate-50 text-[13px]">
                        @forelse ($transaksi as $trx)
                            <li class="flex items-center gap-2 py-2">
                                <span class="w-3 font-bold {{ $trx->arah === 'masuk' ? 'text-forest-500' : 'text-rose-400' }}">{{ $trx->arah === 'masuk' ? '+' : '−' }}</span>
                                <span class="min-w-0 flex-1 truncate text-slate-600">{{ $trx->deskripsi }}</span>
                                <span class="font-bold">{{ $rupiah($trx->jumlah) }}</span>
                                <span class="w-14 text-right text-[11.5px] text-slate-400">{{ $trx->tanggal->translatedFormat('d M') }}</span>
                            </li>
                        @empty
                            <li class="py-4 text-center text-[12px] text-slate-400">Belum ada transaksi.</li>
                        @endforelse
                    </ul>
                </div>
            </section>
        </div>

        {{-- Bottom row --}}
        <div class="mt-4 grid gap-4 xl:grid-cols-3">
            <section class="rounded-2xl border border-slate-100 bg-white p-5">
                <div class="flex items-center">
                    <h2 class="text-[14.5px] font-extrabold">Administrasi Surat</h2>
                    <a href="#" class="ml-auto text-[12.5px] font-bold text-cobalt-600">Lihat semua</a>
                </div>
                <div class="mt-3 space-y-3">
                    @forelse ($suratTerbaru as $surat)
                        <div class="rounded-xl border border-slate-100 p-3.5">
                            <div class="flex items-start gap-2">
                                <p class="flex-1 text-[13px] font-bold">{{ $surat->jenis }}</p>
                                <span class="rounded-lg px-3 py-1.5 text-[10.5px] font-bold {{ $surat->status === 'Selesai' ? 'bg-forest-50 text-forest-600' : ($surat->status === 'Menunggu' ? 'bg-amber-50 text-amber-600' : 'bg-cobalt-50 text-cobalt-600') }}">{{ $surat->status }}</span>
                            </div>
                            <p class="mt-1 text-[11.5px] text-slate-400">{{ $surat->pemohon }}{{ $surat->blok ? ' ('.$surat->blok.')' : '' }}</p>
                        </div>
                    @empty
                        <p class="py-4 text-center text-[12px] text-slate-400">Belum ada surat.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-2xl border border-slate-100 bg-white p-5">
                <div class="flex items-center">
                    <h2 class="text-[14.5px] font-extrabold">Agenda</h2>
                    <a href="#" class="ml-auto text-[12.5px] font-bold text-cobalt-600">Lihat semua</a>
                </div>
                <div class="mt-3 space-y-3">
                    @forelse ($agenda as $item)
                        <div class="flex items-center gap-3">
                            <span class="flex h-11 w-11 shrink-0 flex-col items-center justify-center rounded-xl bg-forest-50 leading-none">
                                <span class="text-[14px] font-extrabold text-forest-600">{{ $item->tanggal->format('d') }}</span>
                                <span class="text-[8.5px] font-bold text-forest-500">{{ strtoupper($item->tanggal->translatedFormat('M')) }}</span>
                            </span>
                            <div class="min-w-0">
                                <p class="truncate text-[13px] font-bold">{{ $item->judul }}</p>
                                <p class="text-[11.5px] text-slate-400">{{ $item->tanggal->translatedFormat('l, d M Y') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="py-4 text-center text-[12px] text-slate-400">Tidak ada agenda mendatang.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-2xl border border-slate-100 bg-white p-5">
                <div class="flex items-center">
                    <h2 class="text-[14.5px] font-extrabold">Pengaduan Warga</h2>
                    <a href="#" class="ml-auto text-[12.5px] font-bold text-cobalt-600">Lihat semua</a>
                </div>
                <div class="mt-3 space-y-3">
                    @forelse ($aduanTerbaru as $aduan)
                        <div class="rounded-xl border border-slate-100 p-3.5">
                            <div class="flex items-start gap-2">
                                <p class="flex-1 text-[13px] font-bold">{{ $aduan->judul }}</p>
                                <span class="rounded-lg px-3 py-1.5 text-[10.5px] font-bold {{ $aduan->status === 'Selesai' ? 'bg-forest-50 text-forest-600' : ($aduan->status === 'Belum' ? 'bg-rose-50 text-rose-500' : 'bg-amber-50 text-amber-600') }}">{{ $aduan->status }}</span>
                            </div>
                            <p class="mt-1 text-[11.5px] text-slate-400">{{ $aduan->lokasi }}</p>
                        </div>
                    @empty
                        <p class="py-4 text-center text-[12px] text-slate-400">Belum ada pengaduan.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </main>
</div>
</body>
</html>
