{{-- ===== Galeri (Admin) ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Galeri — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php $user = auth()->user(); @endphp
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['user' => $user, 'active' => 'galeri'])

    <main class="min-w-0 flex-1 px-5 py-5 sm:px-7">
        <div class="flex flex-wrap items-center gap-3">
            <div class="mr-auto">
                <h1 class="text-xl font-extrabold tracking-tight sm:text-2xl">Galeri</h1>
                <p class="mt-0.5 text-[12.5px] text-slate-500">Dokumentasi kegiatan warga RT 04 / RW 02.</p>
            </div>
            <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-[#2863D1] px-4 py-2.5 text-[12.5px] font-bold text-white"><span class="text-base leading-none">+</span>Unggah Foto</button>
        </div>

        <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @forelse ($items as $g)
                <figure class="group overflow-hidden rounded-2xl border bg-white shadow-sm">
                    <img src="{{ \App\Data\SiteContent::image($g['img']) }}" alt="{{ $g['title'] }}" class="h-40 w-full object-cover transition group-hover:scale-105">
                    <figcaption class="p-3">
                        <p class="text-[13px] font-bold">{{ $g['title'] }}</p>
                        <p class="text-[11.5px] text-slate-400">{{ $g['date'] }}</p>
                    </figcaption>
                </figure>
            @empty
                <p class="col-span-full py-10 text-center text-[13px] text-slate-400">Galeri masih kosong.</p>
            @endforelse
        </div>
    </main>
</div>
</body>
</html>
