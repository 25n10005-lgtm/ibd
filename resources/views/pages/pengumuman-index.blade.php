{{-- ===== Pengumuman (Admin side, Pengurus RT) ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengumuman — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php
    $user = auth()->user();
    $cards = [
        ['Total', $total, 'Semua waktu', 'bg-[#EFF6FF] text-[#2563EB]', 'mega'],
        ['Terbit', $terbit, 'Sedang ditampilkan', 'bg-[#FFFBEB] text-[#D97706]', 'alert'],
        ['Terjadwal', $terjadwal, 'Akan tayang', 'bg-[#ECFDF5] text-[#059669]', 'cal'],
        ['Draf', $draf, 'Belum diterbitkan', 'bg-slate-100 text-slate-500', 'doc'],
    ];
    $thumb = ['Kegiatan' => 'bg-[#D1FAE5] text-[#065F46]', 'Keuangan' => 'bg-[#FCE7E7] text-[#B91C1C]', 'Informasi' => 'bg-[#FEF3C7] text-[#92400E]', 'Rapat' => 'bg-[#DBEAFE] text-[#1E40AF]', 'Kesehatan' => 'bg-[#CCFBF1] text-[#0F766E]'];
    $catPill = ['Kegiatan' => 'bg-[#ECFDF5] text-[#047857]', 'Keuangan' => 'bg-[#F5F3FF] text-[#7C3AED]', 'Informasi' => 'bg-[#EFF6FF] text-[#2563EB]', 'Rapat' => 'bg-[#FFFBEB] text-[#B45309]', 'Kesehatan' => 'bg-[#ECFDF5] text-[#047857]'];
    $stPill = ['terbit' => 'bg-[#ECFDF5] text-[#047857]', 'terjadwal' => 'bg-[#FFFBEB] text-[#B45309]', 'draf' => 'bg-slate-100 text-slate-500'];
    $stLabel = ['terbit' => 'Terbit', 'terjadwal' => 'Terjadwal', 'draf' => 'Draf'];
    $q = request('q'); $fKat = request('kategori'); $fSt = request('status'); $sort = request('sort', 'terbaru');
@endphp
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['user' => $user, 'active' => 'pengumuman'])

    <main class="min-w-0 flex-1 p-7">
        <span class="rounded-full bg-[#EFF6FF] px-3 py-1 text-[11px] font-semibold text-[#2563EB]">ADMIN SIDE (Pengurus RT)</span>
        <div class="mt-2 flex flex-wrap items-start gap-3">
            <div class="mr-auto">
                <h1 class="text-3xl font-extrabold tracking-tight text-[#0F172A]">Pengumuman</h1>
                <p class="mt-1 max-w-md text-[13px] text-slate-500">Kelola dan sampaikan informasi penting untuk warga RT 04 / RW 02</p>
            </div>
            <button type="button" class="inline-flex items-center gap-1.5 rounded-lg bg-[#2563EB] px-4 py-2.5 text-[13px] font-semibold text-white transition hover:bg-blue-700">
                <span class="text-base leading-none">+</span> Buat Pengumuman
            </button>
        </div>

        <div class="mt-5 grid grid-cols-2 gap-4 xl:grid-cols-4">
            @foreach ($cards as [$label, $value, $sub, $tone, $icon])
                <div class="flex items-center gap-3 rounded-xl border border-slate-200/70 bg-white p-4 shadow-sm">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg {{ $tone }}">
                        <x-hi name="{{ $icon }}" width="19" height="19" />
                    </span>
                    <div class="min-w-0">
                        <p class="text-[11px] text-slate-500">{{ $label }}</p>
                        <p class="text-[22px] leading-7 font-extrabold text-[#0F172A]">{{ $value }}</p>
                        <p class="text-[10px] leading-tight text-slate-400">{{ $sub }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <form method="GET" data-loading class="mt-5 space-y-3">
            <label class="flex items-center gap-2 rounded-xl border border-slate-200/70 bg-white px-4 py-2.5 shadow-sm">
                <x-hi name="search" width="15" height="15" class="shrink-0 text-slate-400" />
                <input type="search" name="q" value="{{ $q }}" placeholder="Cari judul pengumuman..." class="w-full bg-transparent text-[13px] outline-none placeholder:text-slate-400">
            </label>
            <div class="flex flex-wrap gap-3">
                <select name="kategori" onchange="this.form.submit()" class="rounded-lg border border-slate-200/70 bg-white px-3 py-2 text-[12.5px] text-slate-600 shadow-sm outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoris as $k)
                        <option value="{{ $k }}" @selected($fKat === $k)>{{ $k }}</option>
                    @endforeach
                </select>
                <select name="status" onchange="this.form.submit()" class="rounded-lg border border-slate-200/70 bg-white px-3 py-2 text-[12.5px] text-slate-600 shadow-sm outline-none">
                    <option value="">Semua Status</option>
                    @foreach ($stLabel as $v => $l)
                        <option value="{{ $v }}" @selected($fSt === $v)>{{ $l }}</option>
                    @endforeach
                </select>
                <select name="sort" onchange="this.form.submit()" class="rounded-lg border border-slate-200/70 bg-white px-3 py-2 text-[12.5px] text-slate-600 shadow-sm outline-none">
                    <option value="terbaru" @selected($sort === 'terbaru')>Urutkan: Terbaru</option>
                    <option value="terlama" @selected($sort === 'terlama')>Urutkan: Terlama</option>
                </select>
            </div>
        </form>

        <x-loading-wrap :rows="5" :cols="3">
        <div class="mt-4 space-y-3.5">
            @forelse ($items as $a)
                <article class="flex gap-4 rounded-xl border border-slate-200/70 bg-white p-4 shadow-sm">
                    <div class="flex h-24 w-24 shrink-0 flex-col items-center justify-center gap-1 rounded-lg p-2 text-center {{ $thumb[$a->kategori] ?? 'bg-slate-100 text-slate-500' }}">
                        <p class="text-[8px] leading-tight font-extrabold tracking-wide uppercase">{{ $a->kategori === 'Kegiatan' ? 'Kerja Bakti Lingkungan' : $a->judul }}</p>
                        <x-hi name="users" width="16" height="16" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="truncate text-[14px] font-bold text-[#0F172A]">{{ $a->judul }}</h2>
                        <p class="truncate text-[12px] text-slate-500">{{ $a->ringkasan }}</p>
                        <div class="mt-1.5 flex flex-wrap items-center gap-2 text-[11px]">
                            <span class="rounded px-2 py-0.5 font-semibold {{ $catPill[$a->kategori] ?? 'bg-slate-100 text-slate-500' }}">{{ $a->kategori }}</span>
                            @if ($a->status === 'draf')
                                <span class="text-slate-400">Draf: Disimpan {{ $a->updated_at->diffForHumans() }}</span>
                            @else
                                <span class="inline-flex items-center gap-1 text-slate-400"><x-hi name="cal" width="11" height="11" /> {{ $a->status === 'terjadwal' ? 'Terjadwal' : 'Diterbitkan' }}: {{ $a->published_at?->translatedFormat('d M Y, H:i') ?? '—' }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex shrink-0 flex-col items-end gap-1">
                        <span class="rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $stPill[$a->status] }}">{{ $stLabel[$a->status] }}</span>
                        @if ($a->status === 'terbit')
                            <p class="inline-flex items-center gap-1 text-[10px] text-slate-400"><x-hi name="eye" width="11" height="11" /> {{ $a->views }} dilihat</p>
                            <p class="inline-flex items-center gap-1 text-[10px] text-slate-400"><x-hi name="users" width="11" height="11" /> {{ $a->target_warga }} warga</p>
                        @else
                            <p class="inline-flex items-center gap-1 text-[10px] text-slate-300"><x-hi name="eye" width="11" height="11" /> -</p>
                            <p class="inline-flex items-center gap-1 text-[10px] text-slate-300"><x-hi name="users" width="11" height="11" /> -</p>
                        @endif
                    </div>
                    <button type="button" title="Opsi" class="flex h-fit shrink-0 items-center rounded-lg border border-slate-200 px-1.5 py-1.5 text-slate-400 transition hover:border-slate-300">
                        <x-hi name="dots" width="13" height="13" />
                    </button>
                </article>
            @empty
                <p class="py-10 text-center text-[13px] text-slate-400">Tidak ada pengumuman yang cocok.</p>
            @endforelse
        </div>
        </x-loading-wrap>

        <div class="mt-5 flex flex-wrap items-center gap-3 text-[12px] text-slate-500">
            <p>Menampilkan {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} dari {{ $items->total() }} pengumuman</p>
            <x-per-page :value="request('per_page', 5)" />
            <x-pagination :rows="$items" />
        </div>
    </main>
</div>
</body>
</html>
