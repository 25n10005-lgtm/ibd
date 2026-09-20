{{-- ===== Pengaduan (Admin) — Figma Smart-RT node 19:2437 ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaduan — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php
    $user = auth()->user();
    $metrics = [
        ['Menunggu Tinjauan', $belum, 'Perlu diperiksa', 'bg-[#EFF6FF] text-[#2563EB]', 'alert'],
        ['Sedang Ditangani', $proses, 'Dalam proses', 'bg-[#FFFBEB] text-[#D97706]', 'clock'],
        ['Selesai', $selesai, 'Selesai ditangani', 'bg-[#ECFDF5] text-[#059669]', 'check'],
        ['Ditolak', $ditolak, 'Tidak memenuhi syarat', 'bg-[#FFF1F2] text-[#E11D48]', 'x'],
        ['Total Pengaduan', $total, 'Semua laporan', 'bg-[#FAF5FF] text-[#7E22CE]', 'chat'],
    ];
    $tabs = [['Semua', $total], ['Menunggu', $belum], ['Diproses', $proses], ['Selesai', $selesai], ['Ditolak', $ditolak]];
    $badge = ['Belum' => 'bg-[#EFF6FF] text-[#2563EB]', 'Proses' => 'bg-[#FFFBEB] text-[#B45309]', 'Selesai' => 'bg-[#ECFDF5] text-[#047857]', 'Ditolak' => 'bg-[#FFF1F2] text-[#E11D48]'];
    $catTone = ['Belum' => 'bg-[#EFF6FF] text-[#2563EB]', 'Proses' => 'bg-[#ECFDF5] text-[#047857]', 'Selesai' => 'bg-[#ECFDF5] text-[#047857]', 'Ditolak' => 'bg-[#FFF1F2] text-[#E11D48]'];
    $initials = fn ($n) => collect(explode(' ', trim($n)))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('');
@endphp
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['user' => $user, 'active' => 'pengaduan'])

    <main class="min-w-0 flex-1 p-7">
        <div class="flex flex-wrap items-center gap-3">
            <div class="mr-auto">
                <h1 class="text-xl font-bold tracking-tight text-[#0F172A]">Pengaduan</h1>
                <p class="mt-0.5 text-[12px] text-slate-500">Kelola laporan dan pengaduan warga dengan cepat dan tuntas.</p>
            </div>
            <button type="button" class="inline-flex items-center gap-2 rounded-[10px] bg-[#2563EB] px-4 py-2 text-[12px] font-semibold text-white shadow-[0_1px_2px_rgba(59,130,246,0.3)] transition hover:bg-blue-700">
                <span class="text-sm leading-none font-bold">+</span>
                Pengaduan Baru
            </button>
        </div>

        @if (session('success'))
            <p class="mt-4 rounded-xl bg-green-50 px-4 py-3 text-[13px] font-medium text-green-700">{{ session('success') }}</p>
        @endif

        {{-- Metric cards --}}
        <div class="mt-5 grid grid-cols-2 gap-3.5 xl:grid-cols-5">
            @foreach ($metrics as [$label, $value, $sub, $tone, $icon])
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

        {{-- Filter bar --}}
        <div class="mt-4 flex flex-wrap items-center gap-3 rounded-[13px] border border-slate-200/70 bg-white p-2.5 shadow-sm">
            <div class="flex flex-wrap items-center gap-1.5">
                @foreach ($tabs as $i => [$label, $count])
                    <button type="button" class="inline-flex items-center gap-1.5 rounded-[10px] px-3 py-1.5 text-[12px] font-semibold transition {{ $i === 0 ? 'bg-[#EFF6FF] text-[#2563EB]' : 'text-slate-500 hover:bg-slate-50' }}">
                        {{ $label }}
                        <span class="rounded-full px-1.5 py-0.5 text-[8px] font-bold {{ $i === 0 ? 'bg-[#DBEAFE] text-[#1E40AF]' : 'bg-slate-100 text-slate-500' }}">{{ $count }}</span>
                    </button>
                @endforeach
            </div>
            <div class="ml-auto flex items-center gap-2">
                <button type="button" class="inline-flex items-center gap-1.5 rounded-[10px] border border-slate-200 px-2.5 py-1.5 text-[12px] font-medium text-slate-600">
                    <x-hi name="cal" width="12" height="12" />
                    01/09/2026 -
                </button>
                <button type="button" class="inline-flex items-center gap-1.5 rounded-[10px] border border-slate-200 px-2.5 py-1.5 text-[12px] font-medium text-slate-600">
                    <x-hi name="funnel" width="12" height="12" />
                    Filter Lainnya
                </button>
            </div>
        </div>

        {{-- Cards grid --}}
        <div class="mt-4 grid gap-3.5 sm:grid-cols-2 xl:grid-cols-4">
            @forelse ($cards as $aduan)
                @php
                    $grad = ['Belum' => 'from-[#DBEAFE] via-[#EFF6FF] to-white', 'Proses' => 'from-[#FEF3C7] via-[#FFFBEB] to-white', 'Selesai' => 'from-[#D1FAE5] via-[#ECFDF5] to-white', 'Ditolak' => 'from-[#FCE7E7] via-[#FFF1F2] to-white'][$aduan->status];
                @endphp
                <article class="flex flex-col overflow-hidden rounded-xl border border-slate-200/70 bg-white shadow-sm transition hover:shadow-md">
                    <div class="relative flex h-32 items-center justify-center overflow-hidden bg-gradient-to-br {{ $grad }}">
                        <x-hi name="chat" width="44" height="44" class="opacity-70 text-[#94A3B8]" />
                        <span class="absolute top-2 left-2 rounded-md bg-white/95 px-2 py-1 text-[9px] font-semibold shadow {{ $badge[$aduan->status] }}">{{ $aduan->status === 'Belum' ? 'Menunggu Tinjauan' : ($aduan->status === 'Proses' ? 'Diproses' : $aduan->status) }}</span>
                        <span class="absolute top-2 right-2 rounded-md bg-white/90 px-2 py-1 text-[9px] font-medium text-slate-500 shadow">{{ $aduan->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex flex-1 flex-col p-3">
                        <p class="line-clamp-2 min-h-8 text-[12px] leading-4 font-bold text-[#0F172A]">{{ $aduan->judul }}</p>
                        <p class="mt-1.5 flex items-center gap-1 text-[10px] text-slate-400">
                            <x-hi name="pin" width="10" height="10" />
                            {{ $aduan->lokasi }}
                        </p>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-200 text-[8px] font-bold text-slate-600">{{ $initials($aduan->pelapor) }}</span>
                            <p class="text-[10px] font-semibold text-slate-700">{{ $aduan->pelapor }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between gap-2 border-t border-slate-100 bg-slate-50/40 px-3 py-2.5">
                        <span class="rounded px-2 py-1 text-[9px] font-semibold {{ $catTone[$aduan->status] }}">{{ $aduan->status }}</span>
                        <span class="flex items-center gap-1.5">
                            <button type="button" class="rounded-md border border-[#2563EB] px-4 py-1.5 text-[10px] font-semibold text-[#2563EB] transition hover:bg-blue-50">Tinjau</button>
                            <span class="relative inline-flex">
                                <button type="button" title="Opsi" data-dots class="flex h-7 w-7 items-center justify-center rounded-md border border-slate-200 text-slate-400 transition hover:border-slate-300">
                                    <x-hi name="dots" width="14" height="14" />
                                </button>
                                <span data-menu class="absolute right-0 bottom-full z-10 mb-1 hidden w-28 overflow-hidden rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                                    <form method="POST" action="{{ route('aduan.destroy', $aduan) }}" onsubmit="return confirm('Hapus pengaduan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex w-full items-center gap-2 px-3 py-1.5 text-left text-[11px] font-semibold text-rose-600 transition hover:bg-rose-50">
                                            <x-hi name="trash" width="13" height="13" />
                                            Hapus
                                        </button>
                                    </form>
                                </span>
                            </span>
                        </span>
                    </div>
                </article>
            @empty
                <p class="col-span-full py-8 text-center text-[13px] text-slate-400">Belum ada pengaduan.</p>
            @endforelse
        </div>

        <div class="mt-5 text-center">
            <button type="button" class="rounded-[10px] border border-slate-200 bg-white px-5 py-2 text-[12px] font-semibold text-slate-600 shadow-sm transition hover:border-slate-300">Muat lebih banyak ↓</button>
        </div>
    </main>
</div>
<script>
    (() => {
        const closeAll = () => document.querySelectorAll('[data-menu]').forEach(m => m.classList.add('hidden'));
        document.querySelectorAll('[data-dots]').forEach(btn => btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const menu = btn.parentElement.querySelector('[data-menu]');
            const wasHidden = menu.classList.contains('hidden');
            closeAll();
            if (wasHidden) menu.classList.remove('hidden');
        }));
        document.addEventListener('click', closeAll);
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAll(); });
    })();
</script>
</body>
</html>
