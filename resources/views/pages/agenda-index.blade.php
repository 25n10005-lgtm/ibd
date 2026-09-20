{{-- ===== Kegiatan (Admin) — Figma admin-side ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kegiatan — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php
    $user = auth()->user();
    $pct = fn ($n) => $total > 0 ? round($n / $total * 100) : 0;
    $metrics = [
        ['Total Kegiatan', $total, 'Semua waktu', 'text-slate-500', 'bg-[#EFF6FF] text-[#2563EB]', 'cal'],
        ['Selesai', $selesai, $pct($selesai).'% dari total', 'text-green-600', 'bg-[#ECFDF5] text-[#059669]', 'check'],
        ['Akan Datang', $akanDatang, $pct($akanDatang).'% dari total', 'text-amber-600', 'bg-[#FFFBEB] text-[#D97706]', 'clock'],
        ['Dibatalkan', $dibatalkan, $pct($dibatalkan).'% dari total', 'text-purple-600', 'bg-[#FAF5FF] text-[#7E22CE]', 'x'],
    ];
    $katHex = ['Kebersihan' => '#10B981', 'Lingkungan' => '#10B981', 'Kesehatan' => '#A855F7', 'Rapat' => '#F59E0B', 'Sosial & Keagamaan' => '#2563EB', 'Keagamaan' => '#2563EB', 'Hiburan' => '#EC4899'];
    $katTone = ['Kebersihan' => 'bg-[#ECFDF5] text-[#047857]', 'Lingkungan' => 'bg-[#ECFDF5] text-[#047857]', 'Kesehatan' => 'bg-[#FAF5FF] text-[#7E22CE]', 'Rapat' => 'bg-[#FFFBEB] text-[#B45309]', 'Sosial & Keagamaan' => 'bg-[#EFF6FF] text-[#2563EB]', 'Keagamaan' => 'bg-[#EFF6FF] text-[#2563EB]', 'Hiburan' => 'bg-[#FDF2F8] text-[#DB2777]'];
    $hexOf = fn ($k) => $katHex[$k] ?? '#94A3B8';
    $toneOf = fn ($k) => $katTone[$k] ?? 'bg-slate-100 text-slate-500';
    // Donut segmen per kategori
    $r = 62; $c = 2 * pi() * $r; $off = 0; $segs = [];
    foreach ($katCount as $k => $n) {
        $len = $total > 0 ? $n / $total * $c : 0;
        $segs[] = ['kat' => $k, 'n' => $n, 'len' => $len, 'off' => -$off, 'hex' => $hexOf($k)];
        $off += $len;
    }
    $prevBulan = $bulan->copy()->subMonth()->format('Y-m');
    $nextBulan = $bulan->copy()->addMonth()->format('Y-m');
@endphp
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['user' => $user, 'active' => 'kegiatan'])

    <main class="min-w-0 flex-1 p-7">
        <div class="flex flex-wrap items-start gap-3">
            <div class="mr-auto">
                <h1 class="text-2xl font-extrabold tracking-tight text-[#0F172A]">Kegiatan</h1>
                <p class="mt-1 max-w-sm text-[12.5px] text-slate-500">Kelola dan pantau seluruh kegiatan di lingkungan RT 04 / RW 02</p>
            </div>
            <button type="button" class="inline-flex items-center gap-1.5 rounded-lg bg-[#2563EB] px-4 py-2.5 text-[13px] font-semibold text-white transition hover:bg-blue-700">
                <span class="text-base leading-none">+</span> Buat Kegiatan
            </button>
        </div>

        {{-- Metrik --}}
        <div class="mt-5 grid grid-cols-2 gap-4 xl:grid-cols-4">
            @foreach ($metrics as [$label, $value, $sub, $subTone, $tone, $icon])
                <div class="rounded-xl border border-slate-200/70 bg-white p-4 shadow-sm">
                    <div class="flex items-start justify-between">
                        <p class="text-[11px] leading-tight text-slate-500">{{ $label }}</p>
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ $tone }}">
                            <x-hi name="{{ $icon }}" width="15" height="15" />
                        </span>
                    </div>
                    <p class="mt-1 text-[22px] leading-7 font-extrabold text-[#0F172A]">{{ $value }}</p>
                    <p class="text-[10px] font-medium {{ $subTone }}">{{ $sub }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-5 grid gap-5 xl:grid-cols-12">
            {{-- Kalender --}}
            <section class="rounded-xl border border-slate-200/70 bg-white p-5 shadow-sm xl:col-span-7">
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('agenda.index', ['bulan' => $prevBulan, 'kategori' => request('kategori')]) }}" class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100">‹</a>
                    <a href="{{ route('agenda.index', ['bulan' => $nextBulan, 'kategori' => request('kategori')]) }}" class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100">›</a>
                    <p class="text-[13px] font-bold">{{ $bulan->translatedFormat('M Y') }}</p>
                    <span class="ml-auto hidden text-[11px] text-slate-300 sm:inline">▾</span>
                    <div class="flex items-center gap-1 rounded-lg bg-slate-50 p-1 text-[11px] font-semibold">
                        <span class="rounded-md bg-white px-2.5 py-1 text-[#2563EB] shadow-sm">Bulan</span>
                        <button type="button" class="px-2.5 py-1 text-slate-400">Minggu</button>
                        <button type="button" class="px-2.5 py-1 text-slate-400">Daftar</button>
                    </div>
                    <form method="GET" data-loading class="flex items-center gap-2">
                        <input type="hidden" name="bulan" value="{{ $bulan->format('Y-m') }}">
                        <select name="kategori" onchange="this.form.submit()" class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-[11px] text-slate-500 outline-none">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoris as $k)
                                <option value="{{ $k }}" @selected(request('kategori') === $k)>{{ $k }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-400">
                            <x-hi name="funnel" width="13" height="13" />
                        </button>
                    </form>
                </div>
                <div class="mt-4 grid grid-cols-7 text-center text-[11px] font-medium text-slate-400">
                    @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $d)
                        <span class="py-1">{{ $d }}</span>
                    @endforeach
                </div>
                <div class="grid grid-cols-7 text-center">
                    @foreach ($kalender as $hari)
                        @php
                            $diBulan = $hari->isSameMonth($bulan);
                            $ada = $acara[$hari->toDateString()] ?? collect();
                            $hariIni = $hari->isToday();
                        @endphp
                        <div class="flex min-h-11 flex-col items-center justify-center gap-0.5 rounded-lg py-1 {{ $hariIni ? 'bg-[#2563EB] font-bold text-white' : ($diBulan ? 'text-slate-600' : 'text-slate-300') }}">
                            <span class="text-[12px]">{{ $hari->day }}</span>
                            @if ($ada->isNotEmpty())
                                <span class="flex gap-0.5">
                                    @foreach ($ada->take(3) as $ev)
                                        <span class="h-1 w-1 rounded-full" style="background: {{ $hariIni ? '#fff' : $hexOf($ev->kategori) }}"></span>
                                    @endforeach
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Mendatang --}}
            <section class="rounded-xl border border-slate-200/70 bg-white p-5 shadow-sm xl:col-span-5">
                <h2 class="text-[13px] font-bold">Kegiatan Mendatang</h2>
                <ul class="mt-3 space-y-4">
                    @forelse ($mendatang as $k)
                        <li class="flex gap-3">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $toneOf($k->kategori) }}">
                                <x-hi name="users" width="18" height="18" />
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="truncate text-[12.5px] font-bold">{{ $k->judul }}</p>
                                    <span class="shrink-0 rounded px-1.5 py-0.5 text-[9px] font-semibold {{ $toneOf($k->kategori) }}">{{ $k->kategori }}</span>
                                </div>
                                <p class="mt-1 text-[10.5px] text-slate-400">📅 {{ $k->tanggal->translatedFormat('l, d M Y') }}</p>
                                <p class="text-[10.5px] text-slate-400">⏰ {{ $k->waktu ?? '—' }}</p>
                                <p class="text-[10.5px] text-slate-400">📍 {{ $k->lokasi ?? '—' }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="py-6 text-center text-[12px] text-slate-400">Tidak ada kegiatan mendatang.</li>
                    @endforelse
                </ul>
                <div class="mt-4 border-t border-slate-100 pt-3 text-center">
                    <button type="button" class="text-[12px] font-semibold text-[#2563EB]">Lihat Semua</button>
                </div>
            </section>
        </div>

        <div class="mt-5 grid gap-5 xl:grid-cols-12">
            {{-- Ringkasan donut --}}
            <section class="rounded-xl border border-slate-200/70 bg-white p-5 shadow-sm xl:col-span-6">
                <h2 class="text-[13px] font-bold">Ringkasan Kegiatan</h2>
                <div class="mt-3 flex flex-col items-center gap-5 sm:flex-row">
                    <div class="relative h-40 w-40 shrink-0">
                        <svg viewBox="0 0 160 160" class="h-full w-full -rotate-90">
                            <circle cx="80" cy="80" r="{{ $r }}" fill="none" stroke="#F1F5F9" stroke-width="20"/>
                            @foreach ($segs as $s)
                                <circle cx="80" cy="80" r="{{ $r }}" fill="none" stroke="{{ $s['hex'] }}" stroke-width="20" stroke-dasharray="{{ round($s['len'], 1) }} {{ round($c, 1) }}" stroke-dashoffset="{{ round($s['off'], 1) }}"/>
                            @endforeach
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <p class="text-2xl font-extrabold">{{ $total }}</p>
                            <p class="text-[10px] text-slate-400">Total Kegiatan</p>
                        </div>
                    </div>
                    <ul class="w-full min-w-0 flex-1 space-y-2 text-[12px]">
                        @foreach ($segs as $s)
                            <li class="flex items-center gap-2">
                                <span class="h-2 w-2 shrink-0 rounded-full" style="background: {{ $s['hex'] }}"></span>
                                <span class="text-slate-500">{{ $s['kat'] }}</span>
                                <span class="ml-auto text-right font-bold">{{ $s['n'] }} <span class="font-medium text-slate-400">({{ $pct($s['n']) }}%)</span></span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>

            {{-- Kategori tiles --}}
            <section class="rounded-xl border border-slate-200/70 bg-white p-5 shadow-sm xl:col-span-6">
                <h2 class="text-[13px] font-bold">Kategori Kegiatan</h2>
                <div class="mt-3 grid grid-cols-2 gap-3">
                    @foreach ($katCount as $k => $n)
                        <div class="flex items-center gap-2.5 rounded-xl border border-slate-100 p-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $toneOf($k) }}">
                                <x-hi name="users" width="15" height="15" />
                            </span>
                            <div class="min-w-0">
                                <p class="truncate text-[12px] font-bold">{{ $k }}</p>
                                <p class="text-[10.5px] text-slate-400">{{ $n }} Kegiatan</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        {{-- Daftar terbaru --}}
        <section class="mt-5 rounded-xl border border-slate-200/70 bg-white p-5 shadow-sm">
            <x-loading-wrap :rows="4" :cols="3">
            <div class="flex items-center">
                <h2 class="text-[13px] font-bold">Daftar Kegiatan Terbaru</h2>
                @if (request('kategori'))
                    <a href="{{ route('agenda.index', ['bulan' => $bulan->format('Y-m')]) }}" class="ml-2 rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-semibold text-slate-500">✕ {{ request('kategori') }}</a>
                @endif
            </div>
            <ul class="mt-3 divide-y divide-slate-50">
                @forelse ($terbaru as $k)
                    <li class="flex items-center gap-3 py-2.5">
                        <span class="flex h-9 w-9 shrink-0 flex-col items-center justify-center rounded-lg {{ $toneOf($k->kategori) }} leading-none">
                            <span class="text-[12px] font-extrabold">{{ $k->tanggal->format('d') }}</span>
                            <span class="text-[8px] font-bold">{{ strtoupper($k->tanggal->translatedFormat('M')) }}</span>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-[12.5px] font-bold">{{ $k->judul }}</p>
                            <p class="text-[10.5px] text-slate-400">{{ $k->tanggal->translatedFormat('l, d M Y') }}{{ $k->lokasi ? ' • '.$k->lokasi : '' }}</p>
                        </div>
                        <span class="hidden rounded px-2 py-0.5 text-[10px] font-semibold sm:inline {{ $toneOf($k->kategori) }}">{{ $k->kategori }}</span>
                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-semibold {{ $k->statusEfektif() === 'selesai' ? 'bg-[#ECFDF5] text-[#047857]' : ($k->statusEfektif() === 'dibatalkan' ? 'bg-[#FAF5FF] text-[#7E22CE]' : 'bg-[#FFFBEB] text-[#B45309]') }}">{{ $k->statusEfektif() === 'akan_datang' ? 'Akan Datang' : ucfirst($k->statusEfektif()) }}</span>
                    </li>
                @empty
                    <li class="py-6 text-center text-[12px] text-slate-400">Belum ada kegiatan.</li>
                @endforelse
            </ul>
            </x-loading-wrap>
            <div class="mt-3 flex flex-wrap items-center gap-3 border-t border-slate-100 pt-3">
                <p class="text-[11px] text-slate-400">Menampilkan {{ $terbaru->firstItem() ?? 0 }}–{{ $terbaru->lastItem() ?? 0 }} dari {{ $terbaru->total() }} kegiatan</p>
                <x-per-page :value="request('per_page', 5)" />
                <x-pagination :rows="$terbaru" />
            </div>
        </section>
    </main>
</div>
</body>
</html>
