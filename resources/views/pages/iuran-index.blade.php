{{-- ===== Iuran (Admin) — Figma Smart-RT node 19:3026 ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iuran — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php
    $user = auth()->user();
    $periode = 'September 2026';
    $totalKk = $lunas + $menunggak->count();
    $totalTagihan = $totalKk * 50000;
    $pct = $totalTagihan > 0 ? round($terkumpul / $totalTagihan * 100) : 0;
    $pctLunas = $totalKk > 0 ? round($lunas / $totalKk * 100) : 0;
    $pctBelum = 100 - $pctLunas;
    $rupiah = fn ($n) => 'Rp '.number_format($n, 0, ',', '.');
    $r = 70; $c = 2 * pi() * $r;
@endphp
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['user' => $user, 'active' => 'iuran'])

    <main class="min-w-0 flex-1 p-7">
        <h1 class="text-2xl font-extrabold tracking-tight text-[#0F172A]">Iuran</h1>
        <p class="mt-1 text-[12px] text-slate-500">Kelola dan pantau pembayaran iuran lingkungan RT 04 / RW 02 dengan mudah.</p>

        {{-- 4 metric cards --}}
        <div class="mt-5 grid grid-cols-2 gap-4 xl:grid-cols-4">
            <div class="flex items-center gap-3 rounded-[13px] border border-slate-200/70 bg-white p-4 shadow-sm">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[10px] bg-[#ECFDF5] text-[#059669]">
                    <x-hi name="wallet" width="18" height="18" />
                </span>
                <div class="min-w-0">
                    <p class="flex items-center gap-2 text-[10px] font-medium text-slate-500">Status <span class="rounded-full bg-[#ECFDF5] px-2 py-0.5 text-[9px] font-semibold text-[#047857]">Aktif</span></p>
                    <p class="text-[15px] leading-5 font-extrabold text-[#0F172A]">September<br>2026</p>
                </div>
            </div>
            <div class="flex items-center gap-3 rounded-[13px] border border-slate-200/70 bg-white p-4 shadow-sm">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[10px] bg-[#EFF6FF] text-[#2563EB]">
                    <x-hi name="cal" width="18" height="18" />
                </span>
                <div class="min-w-0">
                    <p class="text-[10px] font-medium text-slate-500">Periode Aktif</p>
                    <p class="text-[15px] leading-5 font-extrabold text-[#0F172A]">September<br>2026</p>
                </div>
            </div>
            <div class="flex items-center gap-3 rounded-[13px] border border-slate-200/70 bg-white p-4 shadow-sm">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#FFFBEB] text-[12px] font-black text-[#D97706]">Rp</span>
                <div class="min-w-0">
                    <p class="text-[10px] font-medium text-slate-500">Nominal Iuran</p>
                    <p class="text-[15px] font-extrabold text-[#0F172A]">Rp 50.000</p>
                    <p class="text-[10px] text-slate-400">Per KK / Bulan</p>
                </div>
            </div>
            <div class="flex items-center gap-3 rounded-[13px] border border-slate-200/70 bg-white p-4 shadow-sm">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[10px] bg-[#FAF5FF] text-[#7E22CE]">
                    <x-hi name="users" width="18" height="18" />
                </span>
                <div class="min-w-0">
                    <p class="text-[10px] font-medium text-slate-500">Partisipasi Warga</p>
                    <p class="text-[15px] font-extrabold text-[#0F172A]">{{ $pct }}%</p>
                    <p class="text-[10px] text-slate-400">{{ $lunas }} dari {{ $totalKk }} KK</p>
                </div>
            </div>
        </div>

        <div class="mt-5 grid gap-5 xl:grid-cols-12">
            {{-- Ringkasan pembayaran --}}
            <section class="rounded-[13px] border border-slate-200/70 bg-white p-5 shadow-sm xl:col-span-7">
                <h2 class="text-[13px] font-bold text-[#0F172A]">Ringkasan Pembayaran</h2>
                <div class="mt-4 flex flex-col items-center gap-6 sm:flex-row">
                    <div class="relative h-44 w-44 shrink-0">
                        <svg viewBox="0 0 176 176" class="h-full w-full -rotate-90">
                            <circle cx="88" cy="88" r="{{ $r }}" fill="none" stroke="#F1F5F9" stroke-width="24"/>
                            <circle cx="88" cy="88" r="{{ $r }}" fill="none" stroke="#10B981" stroke-width="24" stroke-dasharray="{{ round($pctLunas / 100 * $c, 1) }} {{ $c }}"/>
                            <circle cx="88" cy="88" r="{{ $r }}" fill="none" stroke="#F59E0B" stroke-width="24" stroke-dasharray="{{ round($pctBelum / 100 * $c, 1) }} {{ $c }}" stroke-dashoffset="{{ -$pctLunas / 100 * $c }}"/>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <p class="text-3xl font-extrabold text-[#0F172A]">{{ $totalKk }}</p>
                            <p class="text-[12px] font-medium text-slate-400">Total KK</p>
                        </div>
                    </div>
                    <ul class="w-full min-w-0 flex-1 space-y-2.5 text-[12px]">
                        <li class="flex items-center gap-2"><span class="h-2 w-2 shrink-0 rounded-full bg-[#10B981]"></span><span class="font-medium text-slate-600">Lunas</span><span class="ml-auto font-bold">{{ $lunas }} KK</span><span class="w-9 shrink-0 text-right text-slate-500">{{ $pctLunas }}%</span></li>
                        <li class="flex items-center gap-2"><span class="h-2 w-2 shrink-0 rounded-full bg-[#F59E0B]"></span><span class="font-medium text-slate-600">Belum Bayar</span><span class="ml-auto font-bold">{{ $menunggak->count() }} KK</span><span class="w-9 shrink-0 text-right text-slate-500">{{ $pctBelum }}%</span></li>
                    </ul>
                </div>
                <div class="mt-5 flex items-center gap-3 rounded-[10px] border border-green-100 bg-[#F2FBF6] p-3">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-green-100 text-[13px]">✓</span>
                    <p class="text-[10px] leading-relaxed font-bold text-green-950">Terima kasih kepada seluruh warga yang sudah berpartisipasi. <span class="font-medium">Iuran Anda sangat berarti untuk kemajuan lingkungan kita.</span></p>
                </div>
            </section>

            {{-- CTA card + mockup --}}
            <section class="flex flex-col justify-between rounded-[13px] border border-blue-100 bg-gradient-to-br from-[#EFF6FF]/60 via-slate-50 to-[#DBEAFE]/40 p-5 shadow-sm xl:col-span-5">
                <div class="flex gap-4">
                    <div class="min-w-0 flex-1">
                        <span class="rounded-md bg-[#DBEAFE]/70 px-2.5 py-1 text-[10px] font-semibold text-[#1D4ED8]">{{ $menunggak->count() }} KK Belum Bayar Iuran</span>
                        <p class="mt-3 text-[12px] font-semibold text-slate-700">Yuk, ingatkan warga untuk</p>
                        <p class="text-[17px] font-extrabold text-[#0F172A]">{{ $periode }}</p>
                        <p class="mt-1 text-[10px] text-slate-400">Pembayaran sebelum 10 {{ $periode }}</p>
                        <p class="mt-2 text-xl font-black tracking-tight text-[#0F172A]">Rp 50.000</p>
                        <button type="button" class="mt-3 inline-flex items-center gap-2 rounded-[10px] bg-[#2563EB] px-5 py-2 text-[12px] font-semibold text-white shadow transition hover:bg-blue-700">
                            <x-hi name="wallet" width="13" height="13" />
                            Kirim Pengingat
                        </button>
                    </div>
                    <div class="hidden shrink-0 sm:block">
                        <div class="flex h-36 w-[74px] flex-col items-center justify-center gap-1 rounded-2xl border-2 border-slate-800 bg-white shadow-lg">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#10B981] text-sm font-bold text-white">✓</span>
                            <p class="px-1 text-center text-[7px] leading-tight font-bold text-slate-700">Pembayaran<br>Aman</p>
                        </div>
                    </div>
                </div>
                <p class="mt-4 flex items-center gap-1.5 border-t border-blue-100/50 pt-3 text-[10px] font-medium text-[#1D4ED8]">
                    <x-hi name="shield" width="12" height="12" />
                    Pembayaran aman & terverifikasi
                </p>
            </section>
        </div>

        {{-- Riwayat --}}
        <section class="mt-5 rounded-[13px] border border-slate-200/70 bg-white p-5 shadow-sm">
            <div class="flex items-center">
                <h2 class="text-[13px] font-bold text-[#0F172A]">Riwayat Pembayaran</h2>
                <a href="#" class="ml-auto text-[12px] font-semibold text-[#2563EB]">Lihat Semua</a>
            </div>
            <ul class="mt-3 space-y-2">
                @forelse ($history as $h)
                    <li class="flex items-center gap-3 rounded-xl border border-slate-100 px-4 py-3 transition hover:border-slate-200">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#ECFDF5] text-green-600">
                            <x-hi name="cal" width="15" height="15" />
                        </span>
                        <p class="text-[12px] font-bold text-[#0F172A]">{{ \Carbon\Carbon::parse($h->periode.'-01')->translatedFormat('F Y') }}</p>
                        <p class="ml-auto text-[12px] font-extrabold">{{ $rupiah($h->jumlah) }}</p>
                        <span class="rounded bg-[#ECFDF5] px-2 py-1 text-[10px] font-semibold text-[#047857]">{{ $h->status }}</span>
                        <div class="hidden text-right sm:block">
                            <p class="text-[10px] text-slate-500">{{ $h->tanggal_bayar ? \Carbon\Carbon::parse($h->tanggal_bayar)->translatedFormat('d M Y, H:i') : '—' }}</p>
                            <p class="text-[10px] text-slate-400">Transfer Bank</p>
                        </div>
                        <span class="text-slate-300">›</span>
                    </li>
                @empty
                    <li class="py-6 text-center text-[12px] text-slate-400">Belum ada riwayat pembayaran.</li>
                @endforelse
            </ul>
        </section>
    </main>
</div>
</body>
</html>
