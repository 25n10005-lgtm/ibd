{{-- ===== Data Warga (Admin) — Figma Smart-RT node 6:488 ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Warga — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f3f5fb] font-sans text-slate-900 antialiased">
@php
    $user = auth()->user();
    $pctAktif = $total > 0 ? round($aktifCount / $total * 100) : 0;
@endphp
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['user' => $user, 'active' => 'warga'])

    {{-- ===== Main ===== --}}
    <main class="min-w-0 flex-1 px-5 py-5 sm:px-7">
        <div class="flex flex-wrap items-center gap-3">
            <div class="mr-auto">
                <h1 class="text-xl font-extrabold tracking-tight sm:text-2xl">Data Warga</h1>
                <p class="mt-0.5 text-[12.5px] text-slate-500">Kelola data warga RT 04 / RW 02 dengan mudah dan terorganisir.</p>
            </div>
            <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[12.5px] font-bold text-slate-600 transition hover:border-slate-300">
                <x-hi name="download" width="14" height="14" />
                Import Warga
            </button>
            <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-cobalt-600 px-4 py-2.5 text-[12.5px] font-bold text-white transition hover:bg-cobalt-700">
                <span class="text-base leading-none">+</span>
                Tambah Warga
            </button>
        </div>

        {{-- Metrics --}}
        <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['Total Warga', $total, $total.' warga terdata', 'text-cobalt-600 bg-cobalt-50', 'users'],
                ['Warga Aktif', $aktifCount, $pctAktif.'% dari total warga', 'text-forest-600 bg-forest-50', 'check'],
                ['Warga Non-Aktif', $nonAktifCount, (100 - $pctAktif).'% dari total warga', 'text-rose-500 bg-rose-50', 'users'],
                ['Total KK', \App\Models\Keluarga::count(), 'Kartu Keluarga terdaftar', 'text-amber-500 bg-amber-50', 'building'],
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
                    <p class="mt-2 text-[11.5px] font-medium text-slate-400">{{ $sub }}</p>
                </div>
            @endforeach
        </div>

        {{-- Filter + table --}}
        <section class="mt-4 rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_10px_30px_-18px_rgba(15,23,42,0.25)]">
            <form method="GET" action="{{ route('warga.index') }}" data-loading class="flex flex-wrap items-center gap-3">
                <label class="flex min-w-0 flex-1 items-center gap-2 rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-2.5 sm:max-w-xs">
                    <x-hi name="search" width="15" height="15" class="shrink-0 text-slate-400" />
                    <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari nama / NIK / alamat..." class="w-full bg-transparent text-[12.5px] outline-none placeholder:text-slate-400">
                </label>
                <select name="status_tinggal" onchange="this.form.submit()" class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[12px] font-bold text-slate-600 outline-none">
                    <option value="">Semua status</option>
                    @foreach (['Tetap', 'Kontrak', 'Kos'] as $opt)
                        <option value="{{ $opt }}" @selected(($filters['status_tinggal'] ?? '') === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
                <select name="aktif" onchange="this.form.submit()" class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[12px] font-bold text-slate-600 outline-none">
                    <option value="">Aktif + Non-aktif</option>
                    <option value="1" @selected(($filters['aktif'] ?? '') === '1')>Aktif</option>
                    <option value="0" @selected(($filters['aktif'] ?? '') === '0')>Non-aktif</option>
                </select>
                @if (! empty(array_filter($filters ?? [])))
                    <a href="{{ route('warga.index') }}" class="text-[12px] font-bold text-rose-500 hover:text-rose-600">Reset</a>
                @endif
            </form>

            <x-loading-wrap :rows="5" :cols="6">
            <div class="mt-4 overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-[12.5px]">
                    <thead>
                        <tr class="border-y border-slate-100 text-[10.5px] font-bold tracking-[0.08em] text-slate-400">
                            <th class="w-10 py-3 pr-2"><input type="checkbox" class="h-4 w-4 rounded border-slate-300 accent-cobalt-600"></th>
                            <th class="py-3 pr-4">NAMA WARGA</th>
                            <th class="py-3 pr-4">NO. KK / NIK</th>
                            <th class="py-3 pr-4">JENIS KELAMIN</th>
                            <th class="py-3 pr-4">USIA</th>
                            <th class="py-3 pr-4">STATUS TINGGAL</th>
                            <th class="py-3 pr-4">NO. HP</th>
                            <th class="py-3 pr-4">ALAMAT</th>
                            <th class="py-3">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($wargas as $warga)
                            <tr class="transition hover:bg-slate-50/60">
                                <td class="py-3 pr-2"><input type="checkbox" class="h-4 w-4 rounded border-slate-300 accent-cobalt-600"></td>
                                <td class="py-3 pr-4">
                                    <div class="flex items-center gap-2.5">
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-[11px] font-extrabold {{ $warga->aktif ? 'bg-cobalt-50 text-cobalt-600' : 'bg-slate-100 text-slate-400' }}">{{ strtoupper(substr($warga->nama, 0, 1)) }}</span>
                                        <span class="font-bold">{{ $warga->nama }}</span>
                                    </div>
                                </td>
                                <td class="py-3 pr-4">
                                    <p class="text-slate-500">{{ $warga->keluarga?->kepala_keluarga ?? '—' }}</p>
                                    <p class="font-semibold">{{ $warga->nik }}</p>
                                </td>
                                <td class="py-3 pr-4">
                                    <span class="inline-flex rounded-lg px-2.5 py-1 text-[11px] font-bold {{ $warga->jenis_kelamin === 'L' ? 'bg-cobalt-50 text-cobalt-600' : 'bg-rose-50 text-rose-500' }}">{{ $warga->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                </td>
                                <td class="py-3 pr-4 font-semibold">{{ $warga->usia ?? '—' }}</td>
                                <td class="py-3 pr-4">
                                    <span class="inline-flex rounded-lg px-2.5 py-1 text-[11px] font-bold {{ $warga->status_tinggal === 'Tetap' ? 'bg-forest-50 text-forest-600' : 'bg-amber-50 text-amber-600' }}">{{ $warga->status_tinggal }}</span>
                                </td>
                                <td class="py-3 pr-4 whitespace-nowrap">{{ $warga->no_hp ?? '—' }}</td>
                                <td class="py-3 pr-4 text-slate-500">{{ $warga->alamat }}</td>
                                <td class="py-3">
                                    <button type="button" title="Aksi" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                                        <x-hi name="dots" width="15" height="15" />
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="py-8 text-center text-[13px] text-slate-400">Tidak ada data warga yang cocok.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            </x-loading-wrap>

            <div class="mt-4 flex flex-wrap items-center gap-3">
                <p class="text-[12px] text-slate-400">Menampilkan {{ $wargas->firstItem() ?? 0 }} - {{ $wargas->lastItem() ?? 0 }} dari {{ $wargas->total() }} data</p>
                <x-per-page :value="request('per_page', 10)" />
                <x-pagination :rows="$wargas" />
            </div>
        </section>
    </main>
</div>
</body>
</html>
