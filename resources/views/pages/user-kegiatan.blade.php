{{-- ===== Kegiatan RT (Warga, klik untuk pratinjau + konfirmasi hadir) ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kegiatan RT — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php
    $user = auth()->user();
    $toneOf = fn ($k) => ['Kebersihan' => 'bg-[#ECFDF5] text-[#047857]', 'Lingkungan' => 'bg-[#ECFDF5] text-[#047857]', 'Kesehatan' => 'bg-[#FAF5FF] text-[#7E22CE]', 'Rapat' => 'bg-[#FFFBEB] text-[#B45309]', 'Sosial & Keagamaan' => 'bg-[#EFF6FF] text-[#2563EB]', 'Keagamaan' => 'bg-[#EFF6FF] text-[#2563EB]', 'Hiburan' => 'bg-[#FDF2F8] text-[#DB2777]'][$k] ?? 'bg-slate-100 text-slate-500';
    $sayaBadge = ['hadir' => 'bg-[#ECFDF5] text-[#047857]', 'tidak' => 'bg-slate-100 text-slate-500'];
    $sayaLabel = ['hadir' => 'Anda akan hadir', 'tidak' => 'Anda berhalangan'];
@endphp
<div class="flex min-h-screen">
    @include('user.partials.sidebar', ['user' => $user, 'active' => 'kegiatan'])

    <main class="min-w-0 flex-1 p-7">
        <x-page-header title="Kegiatan RT" subtitle="Klik kegiatan untuk pratinjau dan konfirmasi kehadiran." />

        @if (session('success'))
            <p class="mt-4 rounded-xl bg-green-50 px-4 py-3 text-[13px] font-medium text-green-700">{{ session('success') }}</p>
        @endif
        @if ($errors->any())
            <p class="mt-4 rounded-xl bg-rose-50 px-4 py-3 text-[13px] font-medium text-rose-600">{{ $errors->first() }}</p>
        @endif

        <h2 class="mt-5 text-[14px] font-extrabold">Akan datang</h2>
        <div class="mt-3 grid gap-4 sm:grid-cols-3">
            @forelse ($mendatang as $k)
                <article class="cursor-pointer rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md" data-modal-open="previewKegiatan" data-judul="{{ $k->judul }}" data-kategori="{{ $k->kategori }}" data-meta="{{ $k->tanggal->translatedFormat('l, d M Y') }}{{ $k->waktu ? ' • '.$k->waktu : '' }}{{ $k->lokasi ? ' • '.$k->lokasi : '' }}" data-deskripsi="{{ $k->deskripsi ?? '—' }}" data-hadir-count="{{ $k->hadir_count }} warga akan hadir" data-saya="{{ $sayaLabel[$hadirSaya[$k->id] ?? ''] ?? 'Belum konfirmasi' }}" data-saya-key="{{ $hadirSaya[$k->id] ?? '' }}" data-hadir-url="{{ route('saya.kegiatan.hadir', $k) }}" data-batal-url="{{ route('saya.kegiatan.hadir.batal', $k) }}">
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 shrink-0 flex-col items-center justify-center rounded-xl {{ $toneOf($k->kategori) }} leading-none">
                            <span class="text-[14px] font-extrabold">{{ $k->tanggal->format('d') }}</span>
                            <span class="text-[8px] font-bold">{{ strtoupper($k->tanggal->translatedFormat('M')) }}</span>
                        </span>
                        <div class="min-w-0">
                            <p class="truncate text-[13px] font-bold">{{ $k->judul }}</p>
                            <p class="text-[11px] text-slate-400">{{ $k->tanggal->translatedFormat('l, d M Y') }}</p>
                        </div>
                    </div>
                    <p class="mt-2 text-[11.5px] text-slate-500">{{ $k->waktu ?? '' }}{{ $k->lokasi ? ' • '.$k->lokasi : '' }}</p>
                    <p class="mt-2 flex items-center gap-1.5 text-[11px] font-bold text-slate-400">
                        <x-hi name="users" width="12" height="12" /> {{ $k->hadir_count }} akan hadir
                        @if (($hadirSaya[$k->id] ?? null))
                            <span class="rounded px-2 py-0.5 text-[10px] {{ $sayaBadge[$hadirSaya[$k->id]] }}">{{ $sayaLabel[$hadirSaya[$k->id]] }}</span>
                        @endif
                    </p>
                </article>
            @empty
                <p class="col-span-full rounded-2xl border border-slate-100 bg-white p-8 text-center text-[12px] text-slate-400 shadow-sm">Tidak ada kegiatan mendatang.</p>
            @endforelse
        </div>

        <section class="mt-5 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <form method="GET" data-loading class="flex flex-wrap items-center gap-3">
                <h2 class="mr-auto text-[14px] font-extrabold">Semua kegiatan</h2>
                <x-filter-select name="kategori" :value="request('kategori')" all="Semua Kategori" :options="$kategoris->mapWithKeys(fn ($k) => [$k => $k])->all()" />
            </form>
            <x-loading-wrap :rows="4" :cols="3">
            <ul class="mt-3 divide-y divide-slate-50">
                @forelse ($items as $k)
                    <li class="flex cursor-pointer items-center gap-3 py-2.5 transition hover:bg-slate-50/60" data-modal-open="previewKegiatan" data-judul="{{ $k->judul }}" data-kategori="{{ $k->kategori }}" data-meta="{{ $k->tanggal->translatedFormat('l, d M Y') }}{{ $k->waktu ? ' • '.$k->waktu : '' }}{{ $k->lokasi ? ' • '.$k->lokasi : '' }}" data-deskripsi="{{ $k->deskripsi ?? '—' }}" data-hadir-count="{{ $k->hadir_count }} warga akan hadir" data-saya="{{ $sayaLabel[$hadirSaya[$k->id] ?? ''] ?? 'Belum konfirmasi' }}" data-saya-key="{{ $hadirSaya[$k->id] ?? '' }}" data-hadir-url="{{ route('saya.kegiatan.hadir', $k) }}" data-batal-url="{{ route('saya.kegiatan.hadir.batal', $k) }}">
                        <span class="flex h-9 w-9 shrink-0 flex-col items-center justify-center rounded-lg {{ $toneOf($k->kategori) }} leading-none">
                            <span class="text-[12px] font-extrabold">{{ $k->tanggal->format('d') }}</span>
                            <span class="text-[8px] font-bold">{{ strtoupper($k->tanggal->translatedFormat('M')) }}</span>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-[12.5px] font-bold">{{ $k->judul }}</p>
                            <p class="text-[10.5px] text-slate-400">{{ $k->tanggal->translatedFormat('l, d M Y') }}{{ $k->lokasi ? ' • '.$k->lokasi : '' }} • {{ $k->hadir_count }} hadir</p>
                        </div>
                        @if (($hadirSaya[$k->id] ?? null))
                            <span class="rounded px-2 py-0.5 text-[10px] font-bold {{ $sayaBadge[$hadirSaya[$k->id]] }}">{{ $sayaLabel[$hadirSaya[$k->id]] }}</span>
                        @endif
                        <span class="hidden rounded px-2 py-0.5 text-[10px] font-semibold sm:inline {{ $toneOf($k->kategori) }}">{{ $k->kategori }}</span>
                    </li>
                @empty
                    <li class="py-6 text-center text-[12px] text-slate-400">Belum ada kegiatan.</li>
                @endforelse
            </ul>
            </x-loading-wrap>
            <div class="mt-3 flex flex-wrap items-center gap-3 border-t border-slate-100 pt-3">
                <p class="text-[11px] text-slate-400">Menampilkan {{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }} dari {{ $items->total() }} kegiatan</p>
                <x-per-page :value="request('per_page', 5)" />
                <x-pagination :rows="$items" />
            </div>
        </section>
    </main>
</div>

<x-detail-modal id="previewKegiatan" title="Pratinjau Kegiatan">
    <p class="text-[15px] font-extrabold" data-modal-field="judul"></p>
    <p class="mt-1 inline-block rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-600" data-modal-field="kategori"></p>
    <p class="mt-2 text-[12px] text-slate-400" data-modal-field="meta"></p>
    <div class="mt-2 rounded-xl bg-slate-50 p-3 text-[12.5px] text-slate-600" data-modal-field="deskripsi"></div>
    <div class="mt-3 flex flex-wrap items-center gap-2 rounded-xl border border-slate-100 p-3">
        <p class="inline-flex items-center gap-1.5 text-[12px] font-bold text-slate-600"><x-hi name="users" width="14" height="14" /> <span data-modal-field="hadirCount"></span></p>
        <p class="ml-auto text-[12px] font-bold text-[#047857]" data-modal-field="saya"></p>
    </div>
    <div data-kehadiran-pilih class="mt-3 grid gap-2 sm:grid-cols-2">
        <form method="POST" data-form-hadir>
            @csrf
            <input type="hidden" name="status" value="hadir">
            <button type="submit" class="w-full rounded-xl bg-[#047857] py-2.5 text-[12.5px] font-bold text-white transition hover:bg-green-800">Saya Akan Hadir</button>
        </form>
        <form method="POST" data-form-hadir-tidak>
            @csrf
            <input type="hidden" name="status" value="tidak">
            <button type="submit" class="w-full rounded-xl border border-slate-200 bg-white py-2.5 text-[12.5px] font-bold text-slate-600 transition hover:bg-slate-50">Berhalangan</button>
        </form>
    </div>
    <form method="POST" data-form-batal class="mt-2 hidden">
        @csrf
        @method('DELETE')
        <button type="submit" class="w-full rounded-xl bg-slate-100 py-2 text-[12px] font-bold text-slate-500 transition hover:bg-rose-50 hover:text-rose-600">Batalkan konfirmasi</button>
    </form>
</x-detail-modal>
<script>
    document.addEventListener('click', (e) => {
        const t = e.target.closest('[data-modal-open="previewKegiatan"]');
        if (!t) return;
        const m = document.getElementById('previewKegiatan');
        if (!m) return;
        [m.querySelector('[data-form-hadir]'), m.querySelector('[data-form-hadir-tidak]')].forEach((f) => {
            if (f) f.action = t.dataset.hadirUrl;
        });
        const batal = m.querySelector('[data-form-batal]');
        if (batal) {
            batal.action = t.dataset.batalUrl;
            batal.classList.toggle('hidden', !t.dataset.sayaKey);
        }
        const pilih = m.querySelector('[data-kehadiran-pilih]');
        if (pilih) pilih.classList.toggle('hidden', !!t.dataset.sayaKey);
    });
</script>
</body>
</html>