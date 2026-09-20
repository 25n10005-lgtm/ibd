{{-- ===== Detail Pengumuman (Warga) ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $item->judul }} — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php
    $user = auth()->user();
    $catPill = ['Kegiatan' => 'bg-[#ECFDF5] text-[#047857]', 'Keuangan' => 'bg-[#F5F3FF] text-[#7C3AED]', 'Informasi' => 'bg-[#EFF6FF] text-[#2563EB]', 'Rapat' => 'bg-[#FFFBEB] text-[#B45309]', 'Kesehatan' => 'bg-[#ECFDF5] text-[#047857]'];
@endphp
<div class="flex min-h-screen">
    @include('user.partials.sidebar', ['user' => $user, 'active' => 'pengumuman'])

    <main class="min-w-0 flex-1 p-7">
        <x-page-header title="Detail Pengumuman" subtitle="Informasi resmi dari pengurus RT.">
            <a href="{{ route('saya.pengumuman') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-[12.5px] font-bold text-slate-600 transition hover:bg-slate-50">
                <x-hi name="arrow-left" width="14" height="14" /> Semua Pengumuman
            </a>
        </x-page-header>

        <article class="mx-auto mt-5 max-w-3xl overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            @if ($item->gambarUrl() !== '')
                <img src="{{ $item->gambarUrl() }}" alt="{{ $item->judul }}" class="max-h-96 w-full object-cover">
            @endif
            <div class="p-5 sm:p-7">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="rounded-lg px-2.5 py-1 text-[11px] font-bold {{ $catPill[$item->kategori] ?? 'bg-slate-100 text-slate-500' }}">{{ $item->kategori }}</span>
                    <span class="inline-flex items-center gap-1 text-[11.5px] text-slate-400"><x-hi name="cal" width="12" height="12" /> {{ $item->published_at?->translatedFormat('l, d M Y, H:i') ?? '—' }}</span>
                    <span class="inline-flex items-center gap-1 text-[11.5px] text-slate-400"><x-hi name="eye" width="12" height="12" /> {{ $item->views }} dilihat</span>
                </div>
                <h1 class="mt-3 text-xl font-extrabold tracking-tight sm:text-2xl">{{ $item->judul }}</h1>
                <p class="mt-3 text-[14px] leading-relaxed text-slate-600">{{ $item->ringkasan }}</p>
            </div>
        </article>

        @if ($lainnya->isNotEmpty())
            <h2 class="mx-auto mt-6 max-w-3xl text-[14px] font-extrabold">Pengumuman lainnya</h2>
            <div class="mx-auto mt-3 grid max-w-3xl gap-3 sm:grid-cols-3">
                @foreach ($lainnya as $lain)
                    <a href="{{ route('saya.pengumuman.show', $lain) }}" class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition hover:shadow-md">
                        <span class="rounded-lg px-2 py-0.5 text-[10.5px] font-bold {{ $catPill[$lain->kategori] ?? 'bg-slate-100 text-slate-500' }}">{{ $lain->kategori }}</span>
                        <p class="mt-1.5 line-clamp-2 text-[12.5px] font-bold">{{ $lain->judul }}</p>
                        <p class="mt-1 text-[11px] text-slate-400">{{ $lain->published_at?->translatedFormat('d M Y') }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </main>
</div>
</body>
</html>