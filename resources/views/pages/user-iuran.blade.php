{{-- ===== Iuran Saya (Warga): kartu tagihan ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iuran Saya — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php
    $user = auth()->user();
    $rupiah = fn ($n) => 'Rp '.number_format($n, 0, ',', '.');
    $periodeOf = fn ($p) => \Carbon\Carbon::parse($p.'-01')->translatedFormat('F Y');
@endphp
<div class="flex min-h-screen">
    @include('user.partials.sidebar', ['user' => $user, 'active' => 'iuran'])

    <main class="min-w-0 flex-1 p-7">
        <x-page-header title="Iuran Saya" subtitle="Tagihan iuran yang perlu Anda bayar." />

        <div class="mt-5 grid gap-4 sm:grid-cols-3">
            <x-stat-card label="Menunggak" :value="$menunggak" sub="Tagihan belum dibayar" icon="alert" tone="text-amber-600 bg-amber-50" />
            <x-stat-card label="Lunas" :value="$lunas" sub="Periode terbayar" icon="check" tone="text-[#047857] bg-[#ECFDF5]" />
            <x-stat-card label="Total Tunggakan" :value="$rupiah($totalTagihan)" sub="Perlu dibayar" icon="currency" tone="text-[#2563EB] bg-[#EFF6FF]" />
        </div>

        <div class="mt-5 grid gap-4 sm:grid-cols-2">
            @forelse ($tagihan as $t)
                <article class="flex flex-col overflow-hidden rounded-2xl border border-amber-200/70 bg-white shadow-sm transition hover:shadow-md">
                    <div class="flex items-center gap-4 bg-amber-50/60 p-5">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                            <x-hi name="currency" width="20" height="20" />
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-bold tracking-wider text-amber-600 uppercase">Iuran {{ $periodeOf($t->periode) }}</p>
                            <p class="text-xl font-extrabold tracking-tight">{{ $rupiah($t->jumlah) }}</p>
                        </div>
                        <span class="rounded-lg bg-amber-100 px-2.5 py-1 text-[11px] font-bold text-amber-700">{{ $t->status }}</span>
                    </div>
                    <dl class="grid grid-cols-2 gap-2 px-5 py-3 text-[12px]">
                        <div><dt class="text-slate-400">Periode</dt><dd class="font-bold">{{ $periodeOf($t->periode) }}</dd></div>
                        <div><dt class="text-slate-400">Pembayar</dt><dd class="truncate font-bold">{{ $t->nama_pembayar }}</dd></div>
                        <div><dt class="text-slate-400">Tanggal bayar</dt><dd class="font-bold">{{ $t->tanggal_bayar?->translatedFormat('d M Y') ?? '—' }}</dd></div>
                        <div><dt class="text-slate-400">Status</dt><dd class="font-bold text-amber-600">{{ $t->status }}</dd></div>
                    </dl>
                    <div class="border-t border-slate-100 px-5 py-3">
                        <button type="button" data-bayar-open class="w-full rounded-xl bg-[#047857] py-2.5 text-[12.5px] font-bold text-white transition hover:bg-green-800">Bayar Sekarang</button>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-green-200 bg-green-50 p-8 text-center shadow-sm">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-green-100 text-green-700">
                        <x-hi name="check" width="22" height="22" />
                    </span>
                    <p class="mt-3 text-[14px] font-bold text-green-800">Tidak ada tunggakan. Terima kasih!</p>
                    <p class="mt-1 text-[12.5px] text-green-700">Semua iuran Anda sudah lunas. Lihat <a href="{{ route('saya.iuran.riwayat') }}" class="font-bold underline">Riwayat Iuran</a> untuk bukti pembayaran.</p>
                </div>
            @endforelse
        </div>
    </main>
</div>

<div id="bayarModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-5 shadow-2xl">
        <div class="flex items-center justify-between">
            <h2 class="text-[15px] font-extrabold">Cara Pembayaran</h2>
            <button type="button" data-bayar-close class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100"><x-hi name="x" width="16" height="16" /></button>
        </div>
        <p class="mt-2 text-[13px] text-slate-500">Transfer ke rekening bendahara RT atau bayar tunai, lalu unggah bukti via WhatsApp pengurus. Status lunas diperbarui maksimal 1×24 jam.</p>
        <button type="button" data-bayar-close class="mt-4 w-full rounded-xl bg-[#047857] py-2.5 text-[13px] font-bold text-white">Mengerti</button>
    </div>
</div>
<script>
    (() => {
        const m = document.getElementById('bayarModal');
        document.querySelectorAll('[data-bayar-open]').forEach(b => b.addEventListener('click', () => { m.classList.remove('hidden'); m.classList.add('flex'); }));
        m.querySelectorAll('[data-bayar-close]').forEach(b => b.addEventListener('click', () => { m.classList.add('hidden'); m.classList.remove('flex'); }));
        m.addEventListener('click', e => { if (e.target === m) { m.classList.add('hidden'); m.classList.remove('flex'); } });
    })();
</script>
</body>
</html>