{{-- ===== Pengajuan Surat (wizard) — Figma node 7:2055 ===== --}}
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
    $steps = [['Jenis Surat', 'Pilih jenis surat'], ['Data Pemohon', 'Isi data pemohon'], ['Keperluan & Lampiran', 'Isi keperluan dan lampiran'], ['Review & Kirim', 'Periksa dan kirim pengajuan']];
    $jenis = [
        ['Surat Pengantar', 'Surat pengantar untuk keperluan berbagai administrasi.', 'bg-blue-100 text-[#2863D1]', true],
        ['Surat Domisili', 'Surat keterangan domisili untuk keperluan administrasi.', 'bg-green-100 text-green-600', false],
        ['Surat Keterangan Usaha', 'Surat keterangan usaha untuk keperluan perizinan / usaha.', 'bg-orange-100 text-orange-500', false],
        ['Surat Keterangan Belum Menikah', 'Surat keterangan belum menikah untuk keperluan administrasi.', 'bg-purple-100 text-purple-600', false],
        ['Surat Keterangan Ahli Waris', 'Surat keterangan ahli waris untuk keperluan administrasi.', 'bg-teal-100 text-teal-600', false],
        ['Lainnya', 'Jenis surat lainnya yang tidak tercantum di atas.', 'bg-slate-100 text-slate-500', false],
    ];
    $alur = [['Jenis Surat', 'Pilih jenis surat yang akan diajukan', true], ['Data Pemohon', 'Isi data diri pemohon surat', false], ['Keperluan & Lampiran', 'Isi keperluan surat dan unggah dokumen pendukung', false], ['Review & Kirim', 'Periksa data dan kirim pengajuan', false], ['Diproses', 'Pengurus akan memproses pengajuan Anda', false], ['Selesai', 'Surat siap diambil / diunduh', false]];
@endphp
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['user' => $user, 'active' => 'surat-pengajuan'])
    <main class="min-w-0 flex-1 p-8">
        <h1 class="text-2xl font-bold tracking-tight">Pengajuan Surat</h1>
        <p class="mt-1 text-sm text-slate-500">Buat pengajuan surat baru dengan mudah dan cepat.</p>

        {{-- Stepper --}}
        <div class="mt-6 rounded-xl border bg-white px-8 py-5">
            <div class="relative flex justify-between">
                <div class="absolute top-4 right-10 left-10 h-px bg-slate-200"></div>
                @foreach ($steps as $i => [$t, $s])
                    <div class="relative flex w-32 flex-col items-center gap-2 text-center">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full text-sm font-bold {{ $i === 0 ? 'bg-[#2863D1] text-white' : 'bg-slate-100 text-slate-500' }}">{{ $i + 1 }}</span>
                        <div><p class="text-xs font-semibold {{ $i === 0 ? 'text-[#2863D1]' : '' }}">{{ $t }}</p><p class="text-[11px] text-slate-400">{{ $s }}</p></div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-5 flex gap-5">
            {{-- Form kiri --}}
            <section class="flex-1 rounded-xl border bg-white p-6">
                <h2 class="text-sm font-bold">1. Pilih Jenis Surat</h2>
                <p class="text-xs text-slate-500">Pilih jenis surat yang ingin diajukan.</p>
                <div class="mt-4 grid grid-cols-3 gap-3">
                    @foreach ($jenis as [$t, $d, $tone, $sel])
                        <button type="button" class="relative flex flex-col items-center rounded-xl border-2 p-5 text-center transition {{ $sel ? 'border-[#2863D1] bg-blue-50/50' : 'border-slate-200 hover:border-slate-300' }}">
                            @if ($sel)<span class="absolute top-2.5 right-2.5 flex h-4 w-4 items-center justify-center rounded-full bg-[#2863D1] text-[10px] text-white">✓</span>@endif
                            <span class="flex h-11 w-11 items-center justify-center rounded-lg {{ $tone }}">
                                <x-hi name="doc" width="18" height="18" />
                            </span>
                            <p class="mt-3 text-xs font-semibold">{{ $t }}</p>
                            <p class="mt-1 text-[11px] leading-snug text-slate-400">{{ $d }}</p>
                        </button>
                    @endforeach
                </div>
                <div class="mt-6 flex justify-between border-t pt-5">
                    <button type="button" class="rounded-lg border px-5 py-2 text-sm font-medium">Batal</button>
                    <a href="{{ route('surat.semua') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#2863D1] px-5 py-2 text-sm font-medium text-white">Selanjutnya →</a>
                </div>
            </section>

            {{-- Sidebar kanan --}}
            <aside class="w-64 shrink-0 space-y-4">
                <div class="rounded-xl border bg-white p-5">
                    <h3 class="text-xs font-bold">Alur Pengajuan</h3>
                    <div class="relative mt-4 space-y-5 before:absolute before:top-2 before:bottom-2 before:left-3 before:w-px before:bg-slate-200">
                        @foreach ($alur as $i => [$t, $d, $act])
                            <div class="relative flex gap-3 pl-0">
                                <span class="relative z-10 flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-[11px] font-bold {{ $act ? 'bg-[#2863D1] text-white' : 'bg-slate-100 text-slate-500' }}">{{ $i + 1 }}</span>
                                <div><p class="text-xs font-semibold {{ $act ? 'text-[#2863D1]' : '' }}">{{ $t }}</p><p class="text-[11px] text-slate-400">{{ $d }}</p></div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="flex gap-3 rounded-xl border bg-white p-4">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-green-100 text-lg">🙋</span>
                    <div>
                        <p class="text-xs font-bold">Butuh bantuan?</p>
                        <p class="text-[11px] text-slate-500">Hubungi pengurus RT jika mengalami kendala.</p>
                        <button type="button" class="mt-2 w-full rounded-lg border py-1.5 text-[11px] font-semibold">🟢 Hubungi Pengurus</button>
                    </div>
                </div>
            </aside>
        </div>
    </main>
</div>
</body>
</html>
