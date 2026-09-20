{{-- ===== Semua Pengajuan — Figma node 7:2597 ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Semua Pengajuan — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php
    $user = auth()->user();
    $cards = [['Total Pengajuan', $total ?? 0, 'bg-slate-100 text-[#2863D1]'], ['Menunggu', $menunggu ?? 0, 'bg-amber-100 text-amber-600'], ['Diproses', $diproses ?? 0, 'bg-indigo-100 text-indigo-600'], ['Selesai', $selesai ?? 0, 'bg-green-100 text-green-700']];
    $pill = ['Menunggu' => 'bg-amber-100 text-amber-700', 'Diproses' => 'bg-indigo-100 text-indigo-700', 'Selesai' => 'bg-green-100 text-green-800', 'Ditolak' => 'bg-rose-100 text-rose-600'];
@endphp
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['user' => $user, 'active' => 'surat-semua'])
    <main class="min-w-0 flex-1 p-6">
        <h1 class="text-3xl font-bold tracking-tight">Semua Pengajuan</h1>
        <p class="mt-1 text-slate-500">Kelola semua pengajuan surat warga dalam satu tempat.</p>

        <div class="mt-6 grid grid-cols-4 gap-4">
            @foreach ($cards as [$l, $v, $tone])
                <div class="rounded-2xl border bg-white/80 p-6 shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ $tone }}">
                            <x-hi name="doc" width="16" height="16" />
                        </span>
                        <p class="text-sm font-semibold text-slate-600">{{ $l }}</p>
                    </div>
                    <p class="mt-2 text-3xl font-bold">{{ number_format($v) }}</p>
                </div>
            @endforeach
        </div>

        <section class="mt-6 rounded-2xl border bg-white/85 shadow-sm">
            <form method="GET" data-loading class="flex flex-wrap items-center gap-3 border-b p-4">
                <label class="flex min-w-0 flex-1 items-center gap-2 rounded-lg border bg-white px-3 py-2">
                    <x-hi name="search" width="14" height="14" class="text-slate-400" />
                    <input type="search" placeholder="Cari nama pemohon..." class="w-full bg-transparent text-sm outline-none">
                </label>
                <label class="inline-flex items-center gap-2 rounded-lg border bg-white px-3 py-2 text-sm text-slate-600">
                    <x-hi name="cal" width="14" height="14" class="text-slate-400" />
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" onchange="this.form.submit()" class="bg-transparent text-sm outline-none">
                </label>
                <a href="{{ route('surat.pengajuan') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-[#2863D1] px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                    <span class="text-base leading-none">+</span> Tambah Surat
                </a>
            </form>
            <x-loading-wrap :rows="5" :cols="6">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] text-left text-sm">
                    <thead><tr class="bg-slate-50/60 text-sm font-semibold text-slate-600">
                        <th class="px-6 py-4">No</th><th class="px-6 py-4">Nama Pemohon</th><th class="px-6 py-4">Jenis Surat</th><th class="px-6 py-4">Tanggal Pengajuan</th><th class="px-6 py-4">Status</th><th class="px-6 py-4 text-right">Aksi</th>
                    </tr></thead>
                    <tbody class="divide-y">
                        @forelse ($rows ?? [] as $i => $s)
                            <tr>
                                <td class="px-6 py-5 text-slate-400">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-6 py-5 font-medium">{{ $s->pemohon }}</td>
                                <td class="px-6 py-5">{{ $s->jenis }}</td>
                                <td class="px-6 py-5">{{ $s->created_at->translatedFormat('d M Y, H:i') }}</td>
                                <td class="px-6 py-5"><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $pill[$s->status] ?? 'bg-slate-100' }}">{{ $s->status }}</span></td>
                                <td class="px-6 py-5 text-right"><a href="{{ route('surat.detail', $s->id) }}" class="text-sm font-semibold text-[#2863D1]">Detail</a></td>
                            </tr>
                        @empty
                            <x-empty-state message="Belum ada pengajuan." :colspan="6" />
                        @endforelse
                    </tbody>
                </table>
            </div>
            </x-loading-wrap>
            <div class="flex flex-wrap items-center justify-between gap-3 border-t p-6 text-sm text-slate-500">
                <p>Menampilkan {{ $rows->firstItem() ?? 0 }}-{{ $rows->lastItem() ?? 0 }} dari {{ number_format($rows->total()) }} data</p>
                <x-per-page :value="request('per_page', 10)" />
                <x-pagination :rows="$rows" />
            </div>
        </section>
    </main>
</div>
</body>
</html>
