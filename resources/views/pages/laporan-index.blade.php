{{-- ===== Laporan Keuangan (Admin) ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Keuangan — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f3f5fb] font-sans text-slate-900 antialiased">
@php
    $user = auth()->user();
    $rupiah = fn ($n) => 'Rp '.number_format($n, 0, ',', '.');
    $saldo = $masuk - $keluar;
@endphp
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['user' => $user, 'active' => 'laporan-keuangan'])

    <main class="min-w-0 flex-1 px-5 py-5 sm:px-7">
        <x-page-header title="Laporan Keuangan" subtitle="Rekap arus kas RT 04 / RW 02.">
            <x-icon-btn icon="download" tooltip="Export CSV" :href="route('laporan.export', request()->only(['q', 'arah']))" class="!h-10 !w-10 !rounded-xl" />
            <span class="group relative inline-flex">
                <button type="button" data-import-open title="Import CSV" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:border-cobalt-500 hover:text-cobalt-600">
                    <x-hi name="upload" width="16" height="16" />
                </button>
                <span class="pointer-events-none absolute -top-8 left-1/2 -translate-x-1/2 rounded-md bg-slate-900 px-2 py-1 text-[10px] font-semibold whitespace-nowrap text-white opacity-0 transition group-hover:opacity-100">Import CSV</span>
            </span>
            <button type="button" data-add-open class="inline-flex items-center gap-2 rounded-xl bg-cobalt-600 px-4 py-2.5 text-[12.5px] font-bold text-white transition hover:bg-cobalt-700">
                <span class="text-base leading-none">+</span>
                Tambah
            </button>
        </x-page-header>

        @if (session('success'))
            <p class="mt-4 rounded-xl bg-green-50 px-4 py-3 text-[13px] font-medium text-green-700">{{ session('success') }}</p>
        @endif
        @if ($errors->any())
            <p class="mt-4 rounded-xl bg-rose-50 px-4 py-3 text-[13px] font-medium text-rose-600">{{ $errors->first() }}</p>
        @endif

        <div class="mt-5 grid gap-4 sm:grid-cols-3">
            <x-stat-card label="Total Pemasukan" :value="$rupiah($masuk)" icon="arrow-right" tone="text-green-600 bg-green-50" />
            <x-stat-card label="Total Pengeluaran" :value="$rupiah($keluar)" icon="arrow-left" tone="text-rose-500 bg-rose-50" />
            <x-stat-card label="Saldo Akhir" :value="$rupiah($saldo)" icon="chart" tone="text-cobalt-600 bg-cobalt-50" />
        </div>

        <section class="mt-4 rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_10px_30px_-18px_rgba(15,23,42,0.25)]">
            <form method="GET" data-loading class="flex flex-wrap items-center gap-3">
                <x-search-bar name="q" :value="request('q')" placeholder="Cari keterangan..." />
                <x-filter-select name="arah" :value="request('arah')" all="Semua Arah" :options="['masuk' => 'Masuk', 'keluar' => 'Keluar']" />
                @if (request('q') || request('arah'))
                    <a href="{{ route('laporan.index') }}" class="text-[12px] font-bold text-slate-400 hover:text-slate-600">Reset</a>
                @endif
            </form>

            <x-loading-wrap :rows="5" :cols="4">
            <div class="mt-4 overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-[12.5px]">
                    <thead>
                        <tr class="border-y border-slate-100 text-[10.5px] font-bold tracking-[0.08em] text-slate-400">
                            <th class="py-3 pr-4">NO</th>
                            <th class="py-3 pr-4">TANGGAL</th>
                            <th class="py-3 pr-4">KETERANGAN</th>
                            <th class="py-3 pr-4">KATEGORI</th>
                            <th class="py-3 pr-4">ARAH</th>
                            <th class="py-3 text-right">JUMLAH</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($transaksi as $i => $t)
                            <tr class="transition hover:bg-slate-50/60">
                                <td class="py-3 pr-4 text-slate-400">{{ $transaksi->firstItem() + $i }}</td>
                                <td class="py-3 pr-4 whitespace-nowrap text-slate-500">{{ $t->tanggal->translatedFormat('d M Y') }}</td>
                                <td class="py-3 pr-4 font-bold">{{ $t->deskripsi }}</td>
                                <td class="py-3 pr-4 text-slate-500">{{ $t->kategori ?? '—' }}</td>
                                <td class="py-3 pr-4"><span class="inline-flex rounded-lg px-2.5 py-1 text-[11px] font-bold {{ $t->arah === 'masuk' ? 'bg-green-50 text-green-600' : 'bg-rose-50 text-rose-500' }}">{{ ucfirst($t->arah) }}</span></td>
                                <td class="py-3 text-right font-bold {{ $t->arah === 'masuk' ? 'text-green-600' : 'text-rose-500' }}">{{ $t->arah === 'masuk' ? '+' : '−' }}{{ $rupiah($t->jumlah) }}</td>
                            </tr>
                        @empty
                            <x-empty-state message="Belum ada transaksi." :colspan="6" />
                        @endforelse
                    </tbody>
                </table>
            </div>
            </x-loading-wrap>

            <div class="mt-4 flex flex-wrap items-center gap-3">
                <p class="text-[12px] text-slate-400">Menampilkan {{ $transaksi->firstItem() ?? 0 }}–{{ $transaksi->lastItem() ?? 0 }} dari {{ $transaksi->total() }} data</p>
                <x-per-page :value="request('per_page', 10)" />
                <x-pagination :rows="$transaksi" />
            </div>
        </section>
    </main>
</div>

{{-- Modal tambah transaksi --}}
<div id="addModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-[2px]">
    <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <h2 class="text-[15px] font-extrabold">Tambah Transaksi</h2>
            <button type="button" data-add-close title="Tutup" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                <x-hi name="x" width="16" height="16" />
            </button>
        </div>
        <form method="POST" action="{{ route('laporan.store') }}" class="space-y-4 px-5 py-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <label class="block"><span class="mb-1.5 block text-[12px] font-bold text-slate-600">Tanggal</span><input type="date" name="tanggal" value="{{ today()->toDateString() }}" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] outline-none focus:border-cobalt-500"></label>
                <label class="block"><span class="mb-1.5 block text-[12px] font-bold text-slate-600">Arah</span><select name="arah" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] outline-none focus:border-cobalt-500"><option value="masuk">Masuk</option><option value="keluar">Keluar</option></select></label>
            </div>
            <label class="block"><span class="mb-1.5 block text-[12px] font-bold text-slate-600">Keterangan</span><input type="text" name="deskripsi" required maxlength="255" placeholder="cth: Iuran warga – Blok A" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] outline-none placeholder:text-slate-400 focus:border-cobalt-500"></label>
            <div class="grid grid-cols-2 gap-3">
                <label class="block"><span class="mb-1.5 block text-[12px] font-bold text-slate-600">Kategori</span><input type="text" name="kategori" maxlength="100" placeholder="cth: Iuran" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] outline-none placeholder:text-slate-400 focus:border-cobalt-500"></label>
                <label class="block"><span class="mb-1.5 block text-[12px] font-bold text-slate-600">Jumlah (Rp)</span><input type="number" name="jumlah" required min="1" placeholder="50000" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] outline-none placeholder:text-slate-400 focus:border-cobalt-500"></label>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" data-add-close class="rounded-xl border border-slate-200 px-5 py-2.5 text-[12.5px] font-bold text-slate-600 transition hover:border-slate-300">Batal</button>
                <button type="submit" class="rounded-xl bg-cobalt-600 px-5 py-2.5 text-[12.5px] font-bold text-white transition hover:bg-cobalt-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal import CSV --}}
<div id="importModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-[2px]">
    <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <h2 class="text-[15px] font-extrabold">Import CSV</h2>
            <button type="button" data-import-close title="Tutup" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                <x-hi name="x" width="16" height="16" />
            </button>
        </div>
        <form method="POST" action="{{ route('laporan.import') }}" enctype="multipart/form-data" class="space-y-4 px-5 py-4">
            @csrf
            <p class="text-[12.5px] text-slate-500">Format kolom: <code class="rounded bg-slate-100 px-1.5 py-0.5 text-[11.5px]">tanggal,deskripsi,kategori,arah,jumlah</code></p>
            <label class="block"><span class="mb-1.5 block text-[12px] font-bold text-slate-600">File CSV</span><input type="file" name="file" accept=".csv,.txt" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:text-[12px] file:font-bold"></label>
            <div class="flex justify-end gap-2">
                <button type="button" data-import-close class="rounded-xl border border-slate-200 px-5 py-2.5 text-[12.5px] font-bold text-slate-600 transition hover:border-slate-300">Batal</button>
                <button type="submit" class="rounded-xl bg-cobalt-600 px-5 py-2.5 text-[12.5px] font-bold text-white transition hover:bg-cobalt-700">Import</button>
            </div>
        </form>
    </div>
</div>

<script>
    (() => {
        const bind = (openSel, modalId, closeSel) => {
            const modal = document.getElementById(modalId);
            document.querySelectorAll(openSel).forEach(b => b.addEventListener('click', () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }));
            modal.querySelectorAll(closeSel).forEach(b => b.addEventListener('click', () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }));
            modal.addEventListener('click', e => { if (e.target === modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); } });
        };
        bind('[data-add-open]', 'addModal', '[data-add-close]');
        bind('[data-import-open]', 'importModal', '[data-import-close]');
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') document.querySelectorAll('#addModal, #importModal').forEach(m => { m.classList.add('hidden'); m.classList.remove('flex'); });
        });
    })();
</script>
</body>
</html>
