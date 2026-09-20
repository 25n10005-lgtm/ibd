{{-- ===== Pengaduan Warga (Warga, ala admin) ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaduan Warga — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php
    $user = auth()->user();
    $badge = ['Belum' => 'bg-[#EFF6FF] text-[#2563EB]', 'Proses' => 'bg-[#FFFBEB] text-[#B45309]', 'Selesai' => 'bg-[#ECFDF5] text-[#047857]', 'Ditolak' => 'bg-[#FFF1F2] text-[#E11D48]'];
    $grad = ['Belum' => 'from-[#DBEAFE] via-[#EFF6FF] to-white', 'Proses' => 'from-[#FEF3C7] via-[#FFFBEB] to-white', 'Selesai' => 'from-[#D1FAE5] via-[#ECFDF5] to-white', 'Ditolak' => 'from-[#FCE7E7] via-[#FFF1F2] to-white'];
    $statusLabel = ['Belum' => 'Menunggu Tinjauan', 'Proses' => 'Diproses', 'Selesai' => 'Selesai', 'Ditolak' => 'Ditolak'];
    $tabs = [['', 'Semua', $total], ['Belum', 'Menunggu', $belum], ['Proses', 'Diproses', $proses], ['Selesai', 'Selesai', $selesai], ['Ditolak', 'Ditolak', $ditolak]];
    $initials = fn ($n) => collect(explode(' ', trim($n)))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('');
@endphp
<div class="flex min-h-screen">
    @include('user.partials.sidebar', ['user' => $user, 'active' => 'pengaduan'])

    <main class="min-w-0 flex-1 p-7">
        <x-page-header title="Pengaduan Warga" subtitle="Sampaikan laporan dan pantau tindak lanjutnya.">
            <a href="{{ route('saya.aduan.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-[#047857] px-4 py-2 text-[12.5px] font-bold text-white transition hover:bg-green-800">
                <x-hi name="plus" width="14" height="14" /> Tambah Pengaduan
            </a>
        </x-page-header>

        @if (session('success'))
            <p class="mt-4 rounded-xl bg-green-50 px-4 py-3 text-[13px] font-medium text-green-700">{{ session('success') }}</p>
        @endif
        @if ($errors->any())
            <p class="mt-4 rounded-xl bg-rose-50 px-4 py-3 text-[13px] font-medium text-rose-600">{{ $errors->first() }}</p>
        @endif

        <div class="mt-5 grid grid-cols-2 gap-3.5 xl:grid-cols-4">
            @foreach ([['Menunggu', $belum, 'Perlu diperiksa', 'bg-[#EFF6FF] text-[#2563EB]', 'alert'], ['Diproses', $proses, 'Dalam penanganan', 'bg-[#FFFBEB] text-[#D97706]', 'clock'], ['Selesai', $selesai, 'Sudah ditindaklanjuti', 'bg-[#ECFDF5] text-[#059669]', 'check'], ['Total Saya', $total, 'Semua laporan saya', 'bg-[#FAF5FF] text-[#7E22CE]', 'chat']] as [$label, $value, $sub, $tone, $icon])
                <div class="flex items-center gap-3 rounded-[13px] border border-slate-200/70 bg-white p-4 shadow-sm">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[13px] {{ $tone }}">
                        <x-hi name="{{ $icon }}" width="18" height="18" />
                    </span>
                    <div class="min-w-0">
                        <p class="text-[10px] leading-tight font-medium text-slate-500">{{ $label }}</p>
                        <p class="text-xl leading-6 font-extrabold text-[#0F172A]">{{ $value }}</p>
                        <p class="text-[9px] text-slate-400">{{ $sub }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 flex flex-wrap items-center gap-1.5 rounded-[13px] border border-slate-200/70 bg-white p-2.5 shadow-sm">
            @foreach ($tabs as [$value, $label, $count])
                <a href="{{ route('saya.aduan', $value === '' ? [] : ['status' => $value]) }}" class="inline-flex items-center gap-1.5 rounded-[10px] px-3 py-1.5 text-[12px] font-semibold transition {{ ($statusAktif ?? '') === $value ? 'bg-[#ECFDF5] text-[#047857]' : 'text-slate-500 hover:bg-slate-50' }}">
                    {{ $label }}
                    <span class="rounded-full px-1.5 py-0.5 text-[8px] font-bold {{ ($statusAktif ?? '') === $value ? 'bg-[#A7F3D0] text-[#065F46]' : 'bg-slate-100 text-slate-500' }}">{{ $count }}</span>
                </a>
            @endforeach
        </div>

        <div class="mt-4 grid gap-3.5 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($rows as $a)
                <article class="flex flex-col overflow-hidden rounded-xl border border-slate-200/70 bg-white shadow-sm transition hover:shadow-md">
                    <div class="relative flex h-28 items-center justify-center overflow-hidden bg-gradient-to-br {{ $grad[$a->status] ?? 'from-slate-100 to-white' }}">
                        <x-hi name="chat" width="40" height="40" class="opacity-70 text-[#94A3B8]" />
                        <span class="absolute top-2 left-2 rounded-md bg-white/95 px-2 py-1 text-[9px] font-semibold shadow {{ $badge[$a->status] ?? 'bg-slate-100 text-slate-500' }}">{{ $statusLabel[$a->status] ?? $a->status }}</span>
                        <span class="absolute top-2 right-2 rounded-md bg-white/90 px-2 py-1 text-[9px] font-medium text-slate-500 shadow">{{ $a->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex flex-1 flex-col p-3">
                        <p class="line-clamp-2 min-h-8 text-[12px] leading-4 font-bold text-[#0F172A]">{{ $a->judul }}</p>
                        <p class="mt-1.5 flex items-center gap-1 text-[10px] text-slate-400">
                            <x-hi name="pin" width="10" height="10" />
                            {{ $a->lokasi }}
                        </p>
                    </div>
                    <div class="flex items-center justify-between gap-2 border-t border-slate-100 bg-slate-50/40 px-3 py-2.5">
                        <span class="rounded px-2 py-1 text-[9px] font-semibold {{ $badge[$a->status] ?? 'bg-slate-100 text-slate-500' }}">{{ $a->status }}</span>
                        <button type="button" data-modal-open="previewAduan" data-judul="{{ $a->judul }}" data-status="{{ $a->status }}" data-meta="{{ $a->lokasi }} • {{ $a->created_at->translatedFormat('d M Y, H:i') }}" data-deskripsi="{{ $a->deskripsi ?? '—' }}" class="rounded-md border border-[#047857] px-4 py-1.5 text-[10px] font-semibold text-[#047857] transition hover:bg-green-50">Lihat</button>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-slate-100 bg-white p-8 text-center shadow-sm">
                    <p class="text-[13px] font-bold">Belum ada pengaduan{{ ($statusAktif ?? '') !== '' ? ' dengan status ini' : '' }}</p>
                    <p class="mt-1 text-[12px] text-slate-400">Laporkan lampu mati, jalan rusak, atau hal lain di lingkungan Anda.</p>
                    <a href="{{ route('saya.aduan.create') }}" class="mt-3 inline-block rounded-xl bg-[#047857] px-5 py-2 text-[12px] font-bold text-white transition hover:bg-green-800">Buat Pengaduan</a>
                </div>
            @endforelse
        </div>

        @if ($rows instanceof \Illuminate\Pagination\LengthAwarePaginator && $rows->hasPages())
            <div class="mt-4 flex flex-wrap items-center gap-3">
                <p class="text-[12px] text-slate-400">Menampilkan {{ $rows->firstItem() ?? 0 }}–{{ $rows->lastItem() ?? 0 }} dari {{ $rows->total() }} data</p>
                <x-per-page :value="request('per_page', 6)" />
                <x-pagination :rows="$rows" />
            </div>
        @endif

        <h2 class="mt-7 text-[14px] font-extrabold">Laporan warga lain</h2>
        <p class="mt-0.5 text-[12px] text-slate-400">Transparansi: laporan tetangga satu RT yang sedang berjalan.</p>
        <div class="mt-3 grid gap-3.5 sm:grid-cols-2 xl:grid-cols-4">
            @forelse ($komunitas as $k)
                <article class="flex flex-col overflow-hidden rounded-xl border border-slate-200/70 bg-white shadow-sm transition hover:shadow-md">
                    <div class="relative flex h-24 items-center justify-center overflow-hidden bg-gradient-to-br {{ $grad[$k->status] ?? 'from-slate-100 to-white' }}">
                        <x-hi name="chat" width="34" height="34" class="opacity-70 text-[#94A3B8]" />
                        <span class="absolute top-2 left-2 rounded-md bg-white/95 px-2 py-1 text-[9px] font-semibold shadow {{ $badge[$k->status] ?? 'bg-slate-100 text-slate-500' }}">{{ $statusLabel[$k->status] ?? $k->status }}</span>
                    </div>
                    <div class="flex flex-1 flex-col p-3">
                        <p class="line-clamp-2 min-h-8 text-[12px] leading-4 font-bold text-[#0F172A]">{{ $k->judul }}</p>
                        <p class="mt-1.5 flex items-center gap-1 text-[10px] text-slate-400">
                            <x-hi name="pin" width="10" height="10" />
                            {{ $k->lokasi }}
                        </p>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-200 text-[8px] font-bold text-slate-600">{{ $initials($k->pelapor) }}</span>
                            <p class="truncate text-[10px] font-semibold text-slate-700">{{ $k->pelapor }}</p>
                        </div>
                    </div>
                    <div class="border-t border-slate-100 bg-slate-50/40 px-3 py-2.5">
                        <button type="button" data-modal-open="previewAduan" data-judul="{{ $k->judul }}" data-status="{{ $k->status }} • {{ $k->pelapor }}" data-meta="{{ $k->lokasi }} • {{ $k->created_at->translatedFormat('d M Y, H:i') }}" data-deskripsi="{{ $k->deskripsi ?? '—' }}" class="w-full rounded-md border border-slate-200 px-4 py-1.5 text-[10px] font-semibold text-slate-600 transition hover:border-slate-300 hover:bg-white">Lihat</button>
                    </div>
                </article>
            @empty
                <p class="col-span-full rounded-2xl border border-slate-100 bg-white p-6 text-center text-[12px] text-slate-400">Belum ada laporan warga lain.</p>
            @endforelse
        </div>
    </main>
</div>

<x-detail-modal id="previewAduan" title="Pratinjau Pengaduan">
    <p class="text-[14px] font-extrabold" data-modal-field="judul"></p>
    <p class="mt-1 text-[11.5px] text-slate-400" data-modal-field="meta"></p>
    <p class="mt-1 inline-block rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-600" data-modal-field="status"></p>
    <div class="mt-2 rounded-xl bg-slate-50 p-3 text-[12.5px] text-slate-600" data-modal-field="deskripsi"></div>
</x-detail-modal>
</body>
</html>