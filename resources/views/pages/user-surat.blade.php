{{-- ===== Pengajuan Surat (Warga) ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengajuan Surat — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php
    $user = auth()->user();
    $badge = ['Menunggu' => 'bg-amber-50 text-amber-600', 'Diproses' => 'bg-[#EFF6FF] text-[#2563EB]', 'Selesai' => 'bg-[#ECFDF5] text-[#047857]', 'Ditolak' => 'bg-rose-50 text-rose-500'];
@endphp
<div class="flex min-h-screen">
    @include('user.partials.sidebar', ['user' => $user, 'active' => 'surat'])

    <main class="min-w-0 flex-1 p-7">
        <x-page-header title="Pengajuan Surat" subtitle="Ajukan surat dan pantau statusnya. Klik baris untuk pratinjau.">
            <a href="{{ route('saya.surat.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-[#047857] px-4 py-2 text-[12.5px] font-bold text-white transition hover:bg-green-800">
                <x-hi name="plus" width="14" height="14" /> Ajukan Surat
            </a>
        </x-page-header>

        @if (session('success'))
            <p class="mt-4 rounded-xl bg-green-50 px-4 py-3 text-[13px] font-medium text-green-700">{{ session('success') }}</p>
        @endif
        @if ($errors->any())
            <p class="mt-4 rounded-xl bg-rose-50 px-4 py-3 text-[13px] font-medium text-rose-600">{{ $errors->first() }}</p>
        @endif

        <div class="mt-5 grid gap-4 sm:grid-cols-2">
            <x-stat-card label="Menunggu / Diproses" :value="$menunggu" sub="Sedang ditangani pengurus" icon="clock" tone="text-amber-600 bg-amber-50" />
            <x-stat-card label="Selesai" :value="$selesai" sub="Siap diambil / diunduh" icon="check" tone="text-[#047857] bg-[#ECFDF5]" />
        </div>

        <section class="mt-4 overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-left text-[12.5px]">
                    <thead>
                        <tr class="border-y border-slate-100 text-[10.5px] font-bold tracking-wider text-slate-400 uppercase">
                            <th class="px-5 py-3">No. Surat</th>
                            <th class="px-5 py-3">Jenis</th>
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($rows as $s)
                            <tr class="cursor-pointer transition hover:bg-slate-50/60" data-modal-open="previewSurat" data-jenis="{{ $s->jenis }}" data-no="{{ $s->no_surat ?? '—' }}" data-tanggal="{{ $s->created_at->translatedFormat('d M Y') }}" data-status="{{ $s->status }}" data-keperluan="{{ $s->catatan ?? '—' }}">
                                <td class="px-5 py-3 font-bold text-[#047857]">{{ $s->no_surat ?? '—' }}</td>
                                <td class="px-5 py-3">{{ $s->jenis }}</td>
                                <td class="px-5 py-3 whitespace-nowrap text-slate-500">{{ $s->created_at->translatedFormat('d M Y') }}</td>
                                <td class="px-5 py-3"><span class="rounded-lg px-2.5 py-1 text-[11px] font-bold {{ $badge[$s->status] ?? 'bg-slate-100 text-slate-500' }}">{{ $s->status }}</span></td>
                            </tr>
                        @empty
                            <x-empty-state message="Belum ada pengajuan." :colspan="4" />
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex flex-wrap items-center gap-3 border-t p-4">
                <p class="text-[12px] text-slate-400">Menampilkan {{ $rows->firstItem() ?? 0 }}–{{ $rows->lastItem() ?? 0 }} dari {{ $rows->total() }} data</p>
                <x-per-page :value="request('per_page', 5)" />
                <x-pagination :rows="$rows" />
            </div>
        </section>
    </main>
</div>

<x-detail-modal id="previewSurat" title="Pratinjau Pengajuan">
    <dl class="grid gap-2.5 text-[13px]">
        <div class="flex gap-2"><dt class="w-24 shrink-0 text-slate-400">Jenis</dt><dd class="font-bold" data-modal-field="jenis"></dd></div>
        <div class="flex gap-2"><dt class="w-24 shrink-0 text-slate-400">No. Surat</dt><dd class="font-mono" data-modal-field="no"></dd></div>
        <div class="flex gap-2"><dt class="w-24 shrink-0 text-slate-400">Tanggal</dt><dd data-modal-field="tanggal"></dd></div>
        <div class="flex gap-2"><dt class="w-24 shrink-0 text-slate-400">Status</dt><dd class="font-bold" data-modal-field="status"></dd></div>
        <div class="rounded-xl bg-slate-50 p-3"><dt class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Keperluan / Catatan</dt><dd class="mt-1 text-slate-600" data-modal-field="keperluan"></dd></div>
    </dl>
</x-detail-modal>
</body>
</html>
