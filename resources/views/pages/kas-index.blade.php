{{-- ===== Kas RT (Admin) — Figma Smart-RT node 20:4919 ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kas RT — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php
    $user = auth()->user();
    $saldo = $masuk - $keluar;
    $rupiah = fn ($n) => 'Rp '.number_format($n, 0, ',', '.');
    $short = fn ($n) => $n >= 1000000 ? 'Rp '.rtrim(rtrim(number_format($n / 1000000, 2, ',', '.'), '0'), ',').' jt' : $rupiah($n);
    $maxKeluar = $besar->max('jumlah') ?: 1;
    $pctSisa = $masuk > 0 ? round($saldo / $masuk * 100) : 0;
    $cards = [
        ['Saldo Kas Saat Ini', $short($saldo), 'Per '.today()->translatedFormat('d M Y'), 'bg-[#E6F9F0] text-[#059669]', 'currency'],
        ['Total Pemasukan', $short($masuk), $jumlahTransaksi.' Transaksi', 'bg-[#EFF6FF] text-[#2563EB]', 'arrow-right'],
        ['Total Pengeluaran', $short($keluar), $besar->count().' Transaksi', 'bg-[#FFF7ED] text-[#F97316]', 'arrow-left'],
        ['Sisa Anggaran', $short($saldo), $pctSisa.'% dari pemasukan', 'bg-[#F5F3FF] text-[#8B5CF6]', 'chart'],
    ];
    $r = 70; $c = 2 * pi() * $r;
    $tot = max($masuk + $keluar, 1);
    $pMasuk = round($masuk / $tot * 100); $pKeluar = 100 - $pMasuk;
    $maxBar = max($series->max('masuk'), $series->max('keluar'), 1);
@endphp
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['user' => $user, 'active' => 'kas'])

    <main class="min-w-0 flex-1 p-7">
        <h1 class="text-xl font-extrabold tracking-tight text-[#0F172A]">Kas RT</h1>
        <p class="mt-1 text-[11px] text-slate-500">Kelola pemasukan dan pengeluaran kas RT secara transparan dan akuntabel.</p>

        {{-- 4 metric cards --}}
        <div class="mt-5 grid grid-cols-2 gap-3.5 xl:grid-cols-4">
            @foreach ($cards as [$label, $value, $sub, $tone, $icon])
                <div class="flex items-center gap-3 rounded-[14px] border border-slate-100 bg-white p-4 shadow-sm">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $tone }}">
                        <x-hi name="{{ $icon }}" width="17" height="17" />
                    </span>
                    <div class="min-w-0">
                        <p class="text-[10px] font-semibold text-slate-500">{{ $label }}</p>
                        <p class="truncate text-[15px] font-extrabold tracking-tight text-[#0F172A]">{{ $value }}</p>
                        <p class="text-[9px] text-slate-400">{{ $sub }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5 grid gap-5 xl:grid-cols-12">
            {{-- Ringkasan kas donut --}}
            <section class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm xl:col-span-5">
                <h2 class="text-[13px] font-bold text-[#0F172A]">Ringkasan Kas</h2>
                <div class="mt-2 flex flex-col items-center gap-4 sm:flex-row sm:gap-6">
                    <div class="relative h-44 w-44 shrink-0">
                        <svg id="kasDonut" viewBox="0 0 176 176" class="h-full w-full -rotate-90">
                            <circle cx="88" cy="88" r="{{ $r }}" fill="none" stroke="#F1F5F9" stroke-width="24"/>
                            <circle class="cursor-pointer transition-opacity" data-tip="Pemasukan|{{ $rupiah($masuk) }}|{{ $pMasuk }}% dari total arus kas" cx="88" cy="88" r="{{ $r }}" fill="none" stroke="#10B981" stroke-width="24" stroke-dasharray="{{ round($pMasuk / 100 * $c, 1) }} {{ $c }}"/>
                            <circle class="cursor-pointer transition-opacity" data-tip="Pengeluaran|{{ $rupiah($keluar) }}|{{ $pKeluar }}% dari total arus kas" cx="88" cy="88" r="{{ $r }}" fill="none" stroke="#F59E0B" stroke-width="24" stroke-dasharray="{{ round($pKeluar / 100 * $c, 1) }} {{ $c }}" stroke-dashoffset="{{ -$pMasuk / 100 * $c }}"/>
                        </svg>
                        <div id="kasTip" class="pointer-events-none absolute z-10 hidden w-44 rounded-lg border border-slate-200 bg-white/95 p-2.5 text-[11px] shadow-lg backdrop-blur"></div>
                        <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
                            <p class="text-base font-extrabold text-[#0F172A]">{{ $short($saldo) }}</p>
                            <p class="text-[11px] font-semibold text-slate-400">Saldo Saat Ini</p>
                        </div>
                    </div>
                    <ul class="w-full min-w-0 flex-1 space-y-3 text-[12px]">
                        <li><button type="button" data-tip="Pemasukan|{{ $rupiah($masuk) }}|{{ $pMasuk }}% dari total arus kas" class="kas-legend block w-full rounded-lg px-2 py-1.5 text-left transition hover:bg-slate-50">
                            <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 shrink-0 rounded-full bg-[#10B981]"></span><span class="font-medium text-slate-600">Pemasukan</span><span class="ml-auto font-bold text-slate-400">{{ $pMasuk }}%</span></span>
                            <span class="mt-0.5 block pl-[18px] font-bold text-slate-800">{{ $rupiah($masuk) }}</span>
                        </button></li>
                        <li><button type="button" data-tip="Pengeluaran|{{ $rupiah($keluar) }}|{{ $pKeluar }}% dari total arus kas" class="kas-legend block w-full rounded-lg px-2 py-1.5 text-left transition hover:bg-slate-50">
                            <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 shrink-0 rounded-full bg-[#F59E0B]"></span><span class="font-medium text-slate-600">Pengeluaran</span><span class="ml-auto font-bold text-slate-400">{{ $pKeluar }}%</span></span>
                            <span class="mt-0.5 block pl-[18px] font-bold text-slate-800">{{ $rupiah($keluar) }}</span>
                        </button></li>
                        <li><button type="button" data-tip="Saldo|{{ $rupiah($saldo) }}|{{ $pctSisa }}% dari pemasukan" class="kas-legend block w-full rounded-lg px-2 py-1.5 text-left transition hover:bg-slate-50">
                            <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 shrink-0 rounded-full bg-slate-400"></span><span class="font-medium text-slate-600">Saldo</span><span class="ml-auto font-bold text-slate-400">{{ $pctSisa }}%</span></span>
                            <span class="mt-0.5 block pl-[18px] font-bold text-slate-800">{{ $rupiah($saldo) }}</span>
                        </button></li>
                    </ul>
                </div>
                <script>
                    (() => {
                        const tip = document.getElementById('kasTip');
                        const donut = document.getElementById('kasDonut');
                        const show = (el, x, y) => {
                            const [t, n, d] = el.dataset.tip.split('|');
                            tip.innerHTML = `<p class="font-bold text-slate-800">${t}</p><p class="font-extrabold text-slate-900">${n}</p><p class="text-slate-500">${d}</p>`;
                            tip.style.left = Math.min(x + 12, donut.clientWidth - 180) + 'px';
                            tip.style.top = Math.max(y - 10, 0) + 'px';
                            tip.classList.remove('hidden');
                        };
                        const hide = () => tip.classList.add('hidden');
                        donut.querySelectorAll('[data-tip]').forEach(seg => {
                            seg.addEventListener('mousemove', e => {
                                const r = donut.getBoundingClientRect();
                                show(seg, e.clientX - r.left, e.clientY - r.top);
                            });
                            seg.addEventListener('mouseleave', hide);
                            seg.addEventListener('click', e => {
                                const r = donut.getBoundingClientRect();
                                tip.classList.contains('hidden') ? show(seg, e.clientX - r.left, e.clientY - r.top) : hide();
                            });
                        });
                        document.querySelectorAll('.kas-legend').forEach(btn => {
                            btn.addEventListener('mouseenter', () => {
                                const [t, n, d] = btn.dataset.tip.split('|');
                                tip.innerHTML = `<p class="font-bold text-slate-800">${t}</p><p class="font-extrabold text-slate-900">${n}</p><p class="text-slate-500">${d}</p>`;
                                tip.style.left = '8px'; tip.style.top = '8px';
                                tip.classList.remove('hidden');
                            });
                            btn.addEventListener('mouseleave', hide);
                            btn.addEventListener('click', () => tip.classList.toggle('hidden'));
                        });
                    })();
                </script>
            </section>

            {{-- Grafik arus kas --}}
            <section class="rounded-[14px] border border-slate-100 bg-white p-5 shadow-sm xl:col-span-7">
                <div class="flex items-center gap-3">
                    <h2 class="text-[12px] font-extrabold text-[#0F172A]">Grafik Arus Kas</h2>
                    <button type="button" class="ml-auto rounded-[10px] border border-slate-200 px-3 py-1.5 text-[10px] font-semibold">6 Bulan Terakhir ▾</button>
                </div>
                <div class="mt-2 flex items-center gap-4 text-[10px] font-medium text-slate-500">
                    <span class="inline-flex items-center gap-1.5"><span class="h-2 w-3 rounded-full bg-[#10B981]"></span>Pemasukan</span>
                    <span class="inline-flex items-center gap-1.5"><span class="h-2 w-3 rounded-full bg-[#F59E0B]"></span>Pengeluaran</span>
                </div>
                <div class="mt-3 flex h-40 items-end justify-between gap-2 border-b border-slate-100 px-7 pb-0">
                    @foreach ($series as $m)
                        <div class="flex h-full flex-1 flex-col items-center justify-end gap-1">
                            <div class="flex w-full items-end justify-center gap-1" style="height: 88%">
                                <div class="w-3 rounded-t bg-[#10B981]" style="height: {{ round($m['masuk'] / $maxBar * 100) }}%" title="Masuk {{ $short($m['masuk']) }}"></div>
                                <div class="w-3 rounded-t bg-[#F59E0B]" style="height: {{ round($m['keluar'] / $maxBar * 100) }}%" title="Keluar {{ $short($m['keluar']) }}"></div>
                            </div>
                            <span class="pb-1 text-[9px] font-semibold text-slate-400">{{ $m['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        <div class="mt-5 grid gap-5 xl:grid-cols-12">
            {{-- Transaksi terbaru --}}
            <section class="rounded-[14px] border border-slate-100 bg-white p-5 shadow-sm xl:col-span-8">
                <div class="flex items-center">
                    <h2 class="text-[12px] font-extrabold text-[#0F172A]">Transaksi Terbaru</h2>
                    <a href="{{ route('laporan.index') }}" class="ml-auto text-[11px] font-semibold text-[#2563EB]">Lihat Semua</a>
                </div>
                <ul class="mt-3 space-y-3">
                    @forelse ($transaksi as $trx)
                        <li class="flex items-center gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $trx->isMasuk() ? 'bg-[#ECFDF5] text-[#059669]' : 'bg-[#FFF7ED] text-[#F97316]' }}">
                                <x-hi name="{{ $trx->isMasuk() ? 'arrow-right' : 'arrow-left' }}" width="15" height="15" />
                            </span>
                            <div class="min-w-0">
                                <p class="truncate text-[11px] font-bold text-[#0F172A]">{{ $trx->deskripsi }}</p>
                                <p class="text-[10px] text-slate-400">{{ $trx->tanggal->translatedFormat('d M Y') }}</p>
                            </div>
                            <div class="ml-auto text-right">
                                <p class="text-[11px] font-extrabold {{ $trx->isMasuk() ? 'text-[#10B981]' : '' }}">{{ $trx->isMasuk() ? '+' : '−' }} {{ $rupiah($trx->jumlah) }}</p>
                                <p class="text-[10px] text-slate-400">{{ $trx->tanggal->translatedFormat('d M Y, H:i') }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="py-6 text-center text-[12px] text-slate-400">Belum ada transaksi.</li>
                    @endforelse
                </ul>
                <a href="{{ route('laporan.index') }}" class="mt-4 flex items-center justify-center gap-1 text-[11px] font-bold text-[#2563EB]">Lihat Semua Transaksi →</a>
            </section>

            {{-- Pengeluaran terbesar --}}
            <section class="rounded-[14px] border border-slate-100 bg-white p-5 shadow-sm xl:col-span-4">
                <div class="flex items-center">
                    <h2 class="text-[12px] font-extrabold text-[#0F172A]">Pengeluaran Terbesar</h2>
                    <a href="{{ route('laporan.index') }}" class="ml-auto text-[11px] font-semibold text-[#2563EB]">Lihat</a>
                </div>
                <ul class="mt-3 space-y-3">
                    @forelse ($besar as $item)
                        <li>
                            <div class="flex text-[11px]"><span class="font-semibold text-slate-600">{{ $item->deskripsi }}</span><span class="ml-auto font-bold">{{ $short($item->jumlah) }}</span></div>
                            <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-[#F59E0B]" style="width: {{ round($item->jumlah / $maxKeluar * 100) }}%"></div></div>
                        </li>
                    @empty
                        <li class="py-4 text-center text-[11px] text-slate-400">Belum ada pengeluaran.</li>
                    @endforelse
                </ul>
            </section>
        </div>
    </main>
</div>
</body>
</html>
