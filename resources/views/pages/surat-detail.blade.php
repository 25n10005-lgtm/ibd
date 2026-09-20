{{-- ===== Detail Pengajuan Surat — Figma node 15:1139 ("Peganjuan Surat") ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengajuan Surat — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFDFE] font-sans text-slate-900 antialiased">
@php
    $user = auth()->user();
    $s = $surat;
    $flow = [['Diajukan', $s->created_at->translatedFormat('d M Y, H:i'), true], ['Diproses', 'Menunggu', false], ['Disetujui', 'Menunggu', false], ['Selesai', 'Menunggu', false]];
@endphp
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['user' => $user, 'active' => 'surat-detail'])
    <main class="min-w-0 flex-1 p-6">
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold">Pengajuan Surat</h1>
            <span class="rounded bg-amber-100 px-2 py-1 text-xs font-bold text-amber-700">{{ $s->status }}</span>
        </div>
        <p class="mt-1 text-sm text-slate-500">No. Pengajuan: <span class="font-semibold text-slate-700">{{ $s->no_surat ?? 'SUR-'.$s->id }}</span></p>

        {{-- Status flow --}}
        <section class="mt-4 rounded bg-white p-6">
            <h2 class="text-sm font-bold text-slate-500">Status Pengajuan</h2>
            <div class="mt-4 flex">
                @foreach ($flow as $i => [$t, $sub, $done])
                    <div class="flex flex-1 items-start">
                        <div class="flex flex-col items-center text-center">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold {{ $done ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-400' }}">{{ $i + 1 }}</span>
                            <p class="mt-2 text-xs font-bold">{{ $t }}</p>
                            <p class="text-[11px] text-slate-400">{{ $sub }}</p>
                        </div>
                        @if ($i < 3)<div class="mx-2 mt-5 h-px flex-1 bg-slate-200"></div>@endif
                    </div>
                @endforeach
            </div>
        </section>

        <div class="mt-4 grid grid-cols-3 gap-4">
            <section class="col-span-2 space-y-4">
                <div class="rounded bg-white p-6">
                    <h2 class="text-sm font-bold">Data Pemohon</h2>
                    <div class="mt-4 grid grid-cols-2 gap-x-8 gap-y-3 text-sm">
                        <div><p class="text-xs text-slate-400">Nama Lengkap</p><p class="font-semibold">{{ $s->pemohon }}</p></div>
                        <div><p class="text-xs text-slate-400">Alamat</p><p class="font-semibold">{{ $s->blok ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400">No. KK</p><p>{{ $s->no_kk ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400">Status dalam Keluarga</p><p>{{ $s->status_keluarga ?? 'Kepala Keluarga' }}</p></div>
                        <div><p class="text-xs text-slate-400">No. HP</p><p>{{ $s->no_hp ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400">Jumlah Anggota Keluarga</p><p>{{ $s->anggota ?? '—' }}</p></div>
                    </div>
                </div>
                <div class="rounded bg-white p-6">
                    <h2 class="text-sm font-bold">Detail Pengajuan</h2>
                    <div class="mt-4 grid grid-cols-2 gap-x-8 gap-y-3 text-sm">
                        <div><p class="text-xs text-slate-400">Jenis Surat</p><p>{{ $s->jenis }}</p></div>
                        <div><p class="text-xs text-slate-400">Tanggal Pengajuan</p><p>{{ $s->created_at->translatedFormat('d M Y, H:i') }}</p></div>
                        <div><p class="text-xs text-slate-400">Keperluan/ Tujuan</p><p>{{ $s->keperluan ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400">Catatan Pemohon</p><p>{{ $s->catatan ?? '—' }}</p></div>
                    </div>
                    <p class="mt-4 text-xs text-slate-400">Lampiran</p>
                    <div class="mt-2 flex items-center gap-2 rounded border p-3 text-sm">📄 {{ $s->lampiran ?? 'dokumen.pdf' }} <span class="ml-auto text-xs text-slate-400">812KB</span></div>
                </div>
                <div class="flex gap-3">
                    <button class="flex-1 rounded border bg-white py-3 text-sm font-bold text-slate-500">✕ Tolak Pengajuan</button>
                    <button class="flex-1 rounded bg-blue-600 py-3 text-sm font-bold text-white">Proses Pengajuan →</button>
                </div>
            </section>

            <aside class="space-y-4">
                <div class="rounded bg-white p-5 text-sm">
                    <h2 class="font-bold">Informasi Pengajuan</h2>
                    <dl class="mt-3 space-y-2 text-[13px]">
                        <div class="flex justify-between"><dt class="text-slate-400">No. Pengajuan</dt><dd>{{ $s->no_surat ?? '—' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-400">Tanggal Pengajuan</dt><dd>{{ $s->created_at->translatedFormat('d M Y, H:i') }}</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-400">Diajukan Oleh</dt><dd>{{ $s->pemohon }} (Warga)</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-400">Jenis Surat</dt><dd>{{ $s->jenis }}</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-400">Status Saat Ini</dt><dd><span class="rounded bg-amber-100 px-2 py-0.5 text-xs font-bold text-amber-700">Menunggu</span></dd></div>
                        <div class="flex justify-between"><dt class="text-slate-400">Prioritas</dt><dd><span class="rounded bg-blue-50 px-2 py-0.5 text-xs text-blue-500">Normal</span></dd></div>
                    </dl>
                </div>
                <div class="rounded bg-white p-5 text-sm">
                    <h2 class="font-bold">Riwayat Status</h2>
                    <div class="mt-3 space-y-3 border-l-2 border-slate-100 pl-4">
                        <div class="rounded bg-blue-50/60 p-3"><p class="font-bold text-blue-600">Diajukan</p><p class="text-xs text-slate-400">{{ $s->created_at->translatedFormat('d M Y, H:i') }}</p><p class="text-xs">Oleh {{ $s->pemohon }} (Warga)</p></div>
                        <p class="text-xs text-slate-400">● Diproses — Menunggu</p>
                        <p class="text-xs text-slate-400">● Disetujui — Menunggu</p>
                        <p class="text-xs text-slate-400">● Selesai — Menunggu</p>
                    </div>
                </div>
                <div class="rounded bg-white p-5 text-sm">
                    <h2 class="font-bold">Catatan Internal</h2>
                    <textarea rows="3" placeholder="Tulis catatan untuk pengajuan ini..." class="mt-2 w-full rounded border p-2 text-[13px] outline-none"></textarea>
                    <p class="mt-1 text-[11px] text-slate-400">Catatan ini hanya terlihat oleh pengurus.</p>
                </div>
            </aside>
        </div>
    </main>
</div>
</body>
</html>
