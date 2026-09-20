{{-- ===== Riwayat Iuran (Warga) ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Iuran — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php
    $user = auth()->user();
    $rupiah = fn ($n) => 'Rp '.number_format($n, 0, ',', '.');
    $toneOf = fn ($s) => $s === 'Lunas' ? 'bg-[#ECFDF5] text-[#047857]' : 'bg-amber-50 text-amber-600';
@endphp
<div class="flex min-h-screen">
    @include('user.partials.sidebar', ['user' => $user, 'active' => 'iuran-riwayat'])

    <main class="min-w-0 flex-1 p-7">
        <x-page-header title="Riwayat Iuran" subtitle="Semua pembayaran iuran Anda yang tercatat." />

        <div class="mt-5 grid gap-4 sm:grid-cols-2">
            <x-stat-card label="Periode Lunas" :value="$lunas" sub="Pembayaran lunas" icon="check" tone="text-[#047857] bg-[#ECFDF5]" />
            <x-stat-card label="Total Dibayar" :value="$rupiah($totalLunas)" sub="Akumulasi tercatat" icon="currency" tone="text-[#2563EB] bg-[#EFF6FF]" />
        </div>

        <section class="mt-4 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <h2 class="text-[14px] font-extrabold">Riwayat Pembayaran Saya</h2>
            <ul class="mt-3 space-y-2">
                @forelse ($history as $h)
                    <li class="flex items-center gap-3 rounded-xl border border-slate-100 px-4 py-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $toneOf($h->status) }}">
                            <x-hi name="cal" width="15" height="15" />
                        </span>
                        <div class="min-w-0">
                            <p class="text-[12px] font-bold">{{ \Carbon\Carbon::parse($h->periode.'-01')->translatedFormat('F Y') }}</p>
                            <p class="text-[10.5px] text-slate-400">{{ $h->tanggal_bayar?->translatedFormat('d M Y') ?? 'Belum dibayar' }}</p>
                        </div>
                        <p class="ml-auto text-[12px] font-extrabold">{{ $rupiah($h->jumlah) }}</p>
                        <span class="rounded px-2 py-1 text-[10px] font-semibold {{ $toneOf($h->status) }}">{{ $h->status }}</span>
                    </li>
                @empty
                    <li class="py-6 text-center text-[12px] text-slate-400">Belum ada riwayat pembayaran.</li>
                @endforelse
            </ul>
            @if ($history instanceof \Illuminate\Pagination\LengthAwarePaginator && $history->hasPages())
                <div class="mt-4 flex flex-wrap items-center gap-3 border-t border-slate-100 pt-4">
                    <p class="text-[12px] text-slate-400">Menampilkan {{ $history->firstItem() ?? 0 }}–{{ $history->lastItem() ?? 0 }} dari {{ $history->total() }} data</p>
                    <x-per-page :value="request('per_page', 10)" />
                    <x-pagination :rows="$history" />
                </div>
            @endif
        </section>
    </main>
</div>
</body>
</html>