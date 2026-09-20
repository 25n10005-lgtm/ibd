{{-- ===== Data Keluarga (Admin) — Figma Smart-RT node 6:896 ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Keluarga — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f3f5fb] font-sans text-slate-900 antialiased">
@php
    $user = auth()->user();
@endphp
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['user' => $user, 'active' => 'keluarga'])

    <main class="min-w-0 flex-1 px-5 py-5 sm:px-7">
        <div class="flex flex-wrap items-center gap-3">
            <div class="mr-auto">
                <h1 class="text-xl font-extrabold tracking-tight sm:text-2xl">Data Keluarga</h1>
                <p class="mt-0.5 text-[12.5px] text-slate-500">Kelola kartu keluarga RT 04 / RW 02 dengan mudah dan terorganisir.</p>
            </div>
            <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[12.5px] font-bold text-slate-600 transition hover:border-slate-300">
                <x-hi name="download" width="14" height="14" />
                Import KK
            </button>
            <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-cobalt-600 px-4 py-2.5 text-[12.5px] font-bold text-white transition hover:bg-cobalt-700">
                <span class="text-base leading-none">+</span>
                Tambah KK
            </button>
        </div>

@php
    $lengkap = $keluargas->filter(fn ($k) => $k->wargas_count > 0)->count();
@endphp
        <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['Total KK', $totalKk, $totalKk.' kartu terdaftar', 'text-cobalt-600 bg-cobalt-50', 'building'],
                ['KK Lengkap', $lengkap, 'Memiliki anggota terdata', 'text-forest-600 bg-forest-50', 'check'],
                ['Total Jiwa', \App\Models\Warga::count(), 'Terdata dalam KK', 'text-rose-500 bg-rose-50', 'users'],
                ['Rata-rata Jiwa', $totalKk > 0 ? round(\App\Models\Warga::count() / $totalKk, 1) : 0, 'Per kartu keluarga', 'text-amber-500 bg-amber-50', 'users'],
            ] as [$label, $value, $sub, $tone, $icon])
                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_10px_30px_-18px_rgba(15,23,42,0.25)]">
                    <div class="flex items-center gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $tone }}">
                            <x-hi name="{{ $icon }}" width="19" height="19" />
                        </span>
                        <div class="min-w-0">
                            <p class="text-[12.5px] font-semibold text-slate-500">{{ $label }}</p>
                            <p class="text-[22px] leading-7 font-extrabold tracking-tight">{{ $value }}</p>
                        </div>
                    </div>
                    <p class="mt-2 text-[11.5px] font-medium {{ str_starts_with($sub, '↗') ? 'text-forest-600' : 'text-slate-400' }}">{{ $sub }}</p>
                </div>
            @endforeach
        </div>

        <section class="mt-4 rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_10px_30px_-18px_rgba(15,23,42,0.25)]">
            <form method="GET" data-loading class="flex flex-wrap items-center gap-3">
                <x-search-bar placeholder="Cari no. KK / kepala keluarga..." />
            </form>

            <x-loading-wrap :rows="5" :cols="5">
            <div class="mt-4 overflow-x-auto">
                <table class="w-full min-w-[820px] text-left text-[12.5px]">
                    <thead>
                        <tr class="border-y border-slate-100 text-[10.5px] font-bold tracking-[0.08em] text-slate-400">
                            <th class="w-10 py-3 pr-2"><input type="checkbox" class="h-4 w-4 rounded border-slate-300 accent-cobalt-600"></th>
                            <th class="py-3 pr-4">NO. KK</th>
                            <th class="py-3 pr-4">KEPALA KELUARGA</th>
                            <th class="py-3 pr-4">ANGGOTA</th>
                            <th class="py-3 pr-4">ALAMAT</th>
                            <th class="py-3 pr-4">STATUS</th>
                            <th class="py-3">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($keluargas as $keluarga)
                            <tr class="transition hover:bg-slate-50/60">
                                <td class="py-3 pr-2"><input type="checkbox" class="h-4 w-4 rounded border-slate-300 accent-cobalt-600"></td>
                                <td class="py-3 pr-4 font-semibold">{{ $keluarga->no_kk }}</td>
                                <td class="py-3 pr-4">
                                    <div class="flex items-center gap-2.5">
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-cobalt-50 text-[11px] font-extrabold text-cobalt-600">{{ strtoupper(substr($keluarga->kepala_keluarga, 0, 1)) }}</span>
                                        <span class="font-bold">{{ $keluarga->kepala_keluarga }}</span>
                                    </div>
                                </td>
                                <td class="py-3 pr-4 font-semibold">{{ $keluarga->wargas_count }} jiwa</td>
                                <td class="py-3 pr-4 text-slate-500">{{ $keluarga->alamat }}</td>
                                <td class="py-3 pr-4">
                                    <span class="inline-flex rounded-lg px-2.5 py-1 text-[11px] font-bold {{ $keluarga->wargas_count > 0 ? 'bg-forest-50 text-forest-600' : 'bg-amber-50 text-amber-600' }}">{{ $keluarga->wargas_count > 0 ? 'Lengkap' : 'Kosong' }}</span>
                                </td>
                                <td class="py-3">
                                    <button type="button" title="Aksi" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                                        <x-hi name="dots" width="15" height="15" />
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="py-8 text-center text-[13px] text-slate-400">Belum ada data keluarga.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            </x-loading-wrap>

            <div class="mt-4 flex flex-wrap items-center gap-3">
                <p class="text-[12px] text-slate-400">Menampilkan {{ $keluargas->firstItem() ?? 0 }} - {{ $keluargas->lastItem() ?? 0 }} dari {{ $keluargas->total() }} data</p>
                <x-per-page :value="request('per_page', 10)" />
                <x-pagination :rows="$keluargas" />
            </div>
        </section>
    </main>
</div>
</body>
</html>
