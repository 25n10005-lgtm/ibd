{{-- ===== Pengumuman (Warga) ===== --}}
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
    $catPill = ['Kegiatan' => 'bg-[#ECFDF5] text-[#047857]', 'Keuangan' => 'bg-[#F5F3FF] text-[#7C3AED]', 'Informasi' => 'bg-[#EFF6FF] text-[#2563EB]', 'Rapat' => 'bg-[#FFFBEB] text-[#B45309]', 'Kesehatan' => 'bg-[#ECFDF5] text-[#047857]'];
    $catGrad = ['Kegiatan' => 'from-[#D1FAE5] via-[#ECFDF5] to-white', 'Keuangan' => 'from-[#FCE7E7] via-[#FFF1F2] to-white', 'Informasi' => 'from-[#DBEAFE] via-[#EFF6FF] to-white', 'Rapat' => 'from-[#FEF3C7] via-[#FFFBEB] to-white', 'Kesehatan' => 'from-[#CCFBF1] via-[#ECFDF5] to-white'];
@endphp
<div class="flex min-h-screen">
    @include('user.partials.sidebar', ['user' => $user, 'active' => 'pengumuman'])

    <main class="min-w-0 flex-1 p-7">
        <x-page-header title="Pengumuman" subtitle="Informasi resmi dari pengurus RT 04 / RW 02. Klik kartu untuk baca selengkapnya." />

        <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($items as $a)
                <a href="{{ route('saya.pengumuman.show', $a) }}" class="group flex flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    @if ($a->gambarUrl() !== '')
                        <div class="relative h-40 overflow-hidden">
                            <img src="{{ $a->gambarUrl() }}" alt="{{ $a->judul }}" loading="lazy" class="h-full w-full object-cover transition group-hover:scale-105">
                            <span class="absolute top-2.5 left-2.5 rounded-lg px-2.5 py-1 text-[11px] font-bold shadow {{ $catPill[$a->kategori] ?? 'bg-slate-100 text-slate-500' }}">{{ $a->kategori }}</span>
                        </div>
                    @else
                        <div class="relative flex h-40 items-center justify-center overflow-hidden bg-gradient-to-br {{ $catGrad[$a->kategori] ?? 'from-slate-100 via-slate-50 to-white' }}">
                            <x-hi name="mega" width="44" height="44" class="opacity-60 text-slate-400" />
                            <span class="absolute top-2.5 left-2.5 rounded-lg px-2.5 py-1 text-[11px] font-bold shadow {{ $catPill[$a->kategori] ?? 'bg-slate-100 text-slate-500' }}">{{ $a->kategori }}</span>
                        </div>
                    @endif
                    <div class="flex flex-1 flex-col p-4">
                        <h2 class="line-clamp-2 text-[14px] font-extrabold transition group-hover:text-[#047857]">{{ $a->judul }}</h2>
                        <p class="mt-1 line-clamp-2 flex-1 text-[12.5px] text-slate-500">{{ $a->ringkasan }}</p>
                        <p class="mt-3 flex items-center gap-1 text-[11px] text-slate-400">
                            <x-hi name="cal" width="12" height="12" /> {{ $a->published_at?->translatedFormat('d M Y, H:i') ?? '—' }}
                            <span class="mx-1">•</span>
                            <x-hi name="eye" width="12" height="12" /> {{ $a->views }} dilihat
                            <span class="ml-auto font-bold text-[#047857]">Baca →</span>
                        </p>
                    </div>
                </a>
            @empty
                <div class="col-span-full rounded-2xl border border-slate-100 bg-white p-10 text-center shadow-sm">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                        <x-hi name="mega" width="22" height="22" />
                    </span>
                    <p class="mt-3 text-[13px] font-bold">Belum ada pengumuman</p>
                    <p class="mt-1 text-[12px] text-slate-400">Informasi resmi dari pengurus akan tampil di sini.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-5 flex flex-wrap items-center gap-3">
            <p class="text-[12px] text-slate-400">Menampilkan {{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }} dari {{ $items->total() }} data</p>
            <x-per-page :value="request('per_page', 5)" />
            <x-pagination :rows="$items" />
        </div>
    </main>
</div>
</body>
</html>
