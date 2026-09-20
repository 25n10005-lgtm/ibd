{{-- ===== Pengaduan ===== --}}
@extends('layouts.landing')

@section('title', 'Pengaduan — Smart RT')

@section('content')
@include('landing.partials.navbar')

@php
    $reports = [
        ['id' => 1, 'title' => 'Lampu jalan mati di depan Jl. Melati No. 12', 'loc' => 'Jl. Melati No. 12', 'reporter' => 'Siti Rahma', 'time' => '2 jam lalu', 'status' => 'Menunggu', 'cat' => 'Fasilitas Umum', 'action' => 'Tinjau', 'img' => 'hero.png', 'desc' => 'Lampu penerangan jalan di depan rumah sudah mati selama 3 malam. Kondisi gang menjadi gelap dan rawan. Mohon segera diperbaiki oleh seksi sarana.'],
        ['id' => 2, 'title' => 'Sampah menumpuk di depan pos ronda', 'loc' => 'Jl. Anggrek No. 7', 'reporter' => 'Andi Wijaya', 'time' => '5 jam lalu', 'status' => 'Diproses', 'cat' => 'Kebersihan', 'action' => 'Lihat', 'img' => 'untuk-warga.png', 'desc' => 'Tumpukan sampah rumah tangga meluber ke badan jalan dan menimbulkan bau. Diduga armada kebersihan terlewat dua hari berturut-turut.'],
        ['id' => 3, 'title' => 'Drainase tersumbat saat hujan', 'loc' => 'Jl. Kenanga No. 3', 'reporter' => 'Dewi Lestari', 'time' => '8 jam lalu', 'status' => 'Diproses', 'cat' => 'Drainase', 'action' => 'Lihat', 'img' => 'untuk-pengurus.png', 'desc' => 'Setiap hujan deras air meluap ke halaman rumah karena gorong-gorong tersumbat lumpur dan sampah plastik. Perlu pengerukan segera sebelum musim hujan puncak.'],
        ['id' => 4, 'title' => 'Anak-anak bermain bola di jalan', 'loc' => 'Jl. Flamboyan No. 5', 'reporter' => 'Rafi Wijaya', 'time' => '1 hari lalu', 'status' => 'Selesai', 'cat' => 'Ketertiban', 'action' => 'Detail', 'img' => 'untuk-admin.png', 'desc' => 'Laporan ditindaklanjuti dengan pemasangan papan imbauan dan penjadwalan lapangan untuk latihan sore. Orang tua anak-anak sudah dihubungi dan situasi kondusif.'],
        ['id' => 5, 'title' => 'Pohon tumbang menutup jalan', 'loc' => 'Jl. Melati No. 15', 'reporter' => 'Tono Susanto', 'time' => '2 hari lalu', 'status' => 'Selesai', 'cat' => 'Fasilitas Umum', 'action' => 'Detail', 'img' => 'hero.png', 'desc' => 'Pohon mahoni tumbang akibat angin kencang dan menutup akses jalan. Tim gabungan warga mengevakuasi batang pohon dalam 4 jam. Akses kembali normal.'],
        ['id' => 6, 'title' => 'Gang gelap, lampu kurang', 'loc' => 'Jl. Melati No. 22', 'reporter' => 'Yuniarti', 'time' => '1 hari lalu', 'status' => 'Ditolak', 'cat' => 'Fasilitas Umum', 'action' => 'Detail', 'img' => 'untuk-bendahara.png', 'desc' => 'Laporan ditolak karena titik yang dimaksud berada di luar wilayah RT 03 dan sudah masuk kewenangan RT tetangga. Pelapor diarahkan melapor ke RT setempat.'],
        ['id' => 7, 'title' => 'Pipa bocor di depan rumah warga', 'loc' => 'Jl. Anggrek No. 9', 'reporter' => 'Budi Santoso', 'time' => '3 hari lalu', 'status' => 'Selesai', 'cat' => 'Drainase', 'action' => 'Detail', 'img' => 'untuk-warga.png', 'desc' => 'Kebocoran pipa air bersih diperbaiki bersama teknisi PDAM. Jalan yang sempat digali sudah ditutup kembali dan dipadatkan.'],
        ['id' => 8, 'title' => 'Kucing liar sering berkeliaran', 'loc' => 'Jl. Kenanga No. 11', 'reporter' => 'Maya Sari', 'time' => '3 hari lalu', 'status' => 'Diproses', 'cat' => 'Ketertiban', 'action' => 'Lihat', 'img' => 'untuk-pengurus.png', 'desc' => 'Kawanan kucing liar membongkar tempat sampah dan masuk ke dapur rumah. Sedang dikoordinasikan dengan puskeswan untuk sterilisasi dan relokasi.'],
        ['id' => 9, 'title' => 'Jalan berlubang membahayakan pengendara', 'loc' => 'Jl. Flamboyan No. 2', 'reporter' => 'Agus Setiawan', 'time' => '4 hari lalu', 'status' => 'Menunggu', 'cat' => 'Fasilitas Umum', 'action' => 'Tinjau', 'img' => 'untuk-admin.png', 'desc' => 'Lubang sedalam 15 cm di tengah jalan sudah menyebabkan dua pengendara motor terjatuh. Perlu penambalan darurat dan rambu peringatan sementara.'],
        ['id' => 10, 'title' => 'Got meluap ke halaman rumah', 'loc' => 'Jl. Anggrek No. 14', 'reporter' => 'Rina Marlina', 'time' => '4 hari lalu', 'status' => 'Diproses', 'cat' => 'Drainase', 'action' => 'Lihat', 'img' => 'hero.png', 'desc' => 'Air got meluap setiap hujan dan masuk ke teras rumah. Petugas sudah survei dan normalisasi saluran dijadwalkan pekan depan.'],
        ['id' => 11, 'title' => 'Sampah dedaunan menumpuk di selokan', 'loc' => 'Jl. Melati No. 8', 'reporter' => 'Hendra Gunawan', 'time' => '5 hari lalu', 'status' => 'Selesai', 'cat' => 'Kebersihan', 'action' => 'Detail', 'img' => 'untuk-bendahara.png', 'desc' => 'Tumpukan daun kering dibersihkan dalam giat Jumat bersih. Selokan kembali lancar dan area disemprot disinfektan.'],
        ['id' => 12, 'title' => 'Knalpot brong meresahkan malam hari', 'loc' => 'Jl. Kenanga No. 20', 'reporter' => 'Dian Puspita', 'time' => '6 hari lalu', 'status' => 'Ditolak', 'cat' => 'Ketertiban', 'action' => 'Detail', 'img' => 'untuk-warga.png', 'desc' => 'Laporan tidak dapat diproses karena membutuhkan bukti (foto/video pelat nomor). Pelapor diminta melengkapi bukti dan membuat laporan ulang.'],
    ];
    $statusMeta = [
        'Menunggu' => ['badge' => 'bg-cobalt-50 text-cobalt-600', 'iconBg' => 'bg-cobalt-50 text-cobalt-500', 'icon' => 'M4 5h16v14H4z'],
        'Diproses' => ['badge' => 'bg-amber-100 text-amber-700', 'iconBg' => 'bg-amber-50 text-amber-500', 'icon' => 'M12 8v5l3 2M12 21a9 9 0 100-18 9 9 0 000 18z'],
        'Selesai' => ['badge' => 'bg-forest-50 text-forest-600', 'iconBg' => 'bg-forest-50 text-forest-500', 'icon' => 'M20 6 9 17l-5-5'],
        'Ditolak' => ['badge' => 'bg-rose-50 text-rose-600', 'iconBg' => 'bg-rose-50 text-rose-500', 'icon' => 'M18 6 6 18M6 6l12 12'],
    ];
    $catStyle = ['Fasilitas Umum' => 'bg-amber-50 text-amber-600', 'Kebersihan' => 'bg-forest-50 text-forest-600', 'Drainase' => 'bg-cobalt-50 text-cobalt-600', 'Ketertiban' => 'bg-violet-50 text-violet-600'];
    $count = fn ($s) => count(array_filter($reports, fn ($r) => $r['status'] === $s));
    foreach ($reports as $i => &$r) {
        $r['ts'] = 20260915 - intdiv($i, 2);
    }
    unset($r);
@endphp

<section class="bg-[#F7F9FC]">
    <div class="mx-auto max-w-[1240px] px-6 pt-12 pb-16 lg:px-8 lg:pt-16 lg:pb-20">
        {{-- Header --}}
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
            <div>
                <h1 data-reveal class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Pengaduan</h1>
                <p data-reveal data-delay="60" class="mt-1 max-w-[480px] text-[13.5px] text-slate-500">Kelola laporan dan pengaduan warga dengan cepat dan tuntas.</p>
            </div>
            <button type="button" id="report-new-btn" data-reveal data-delay="120" class="inline-flex shrink-0 items-center gap-2 self-start rounded-xl bg-cobalt-600 px-5 py-2.5 text-[13.5px] font-bold text-white shadow-lg shadow-cobalt-600/25 transition hover:bg-cobalt-700 active:scale-[0.98] sm:self-auto">
                <x-hi name="plus" width="15" height="15" />
                Pengaduan Baru
            </button>
        </div>

        {{-- Statistik --}}
        <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
            @foreach ([['Menunggu', 'Perlu diperiksa'], ['Diproses', 'Dalam proses'], ['Selesai', 'Selesai ditangani'], ['Ditolak', 'Tidak memenuhi syarat']] as [$st, $cap])
                <div data-reveal data-delay="{{ $loop->index * 60 }}" class="flex items-center gap-3.5 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $statusMeta[$st]['iconBg'] }}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $statusMeta[$st]['icon'] }}"/></svg>
                    </span>
                    <span>
                        <span class="block text-[11.5px] font-medium text-slate-500">{{ $st === 'Menunggu' ? 'Menunggu Tinjauan' : ($st === 'Diproses' ? 'Sedang Ditangani' : $st) }}</span>
                        <span class="block text-[22px] font-extrabold leading-tight text-slate-900">{{ $count($st) }}</span>
                        <span class="block text-[10.5px] text-slate-400">{{ $cap }}</span>
                    </span>
                </div>
            @endforeach
            <div data-reveal data-delay="240" class="flex items-center gap-3.5 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-500">
                    <x-hi name="cal" width="20" height="20" />
                </span>
                <span>
                    <span class="block text-[11.5px] font-medium text-slate-500">Total Pengaduan</span>
                    <span id="stat-total" class="block text-[22px] font-extrabold leading-tight text-slate-900">{{ count($reports) }}</span>
                    <span class="block text-[10.5px] text-slate-400">Semua laporan</span>
                </span>
            </div>
        </div>

        {{-- Tab filter + tanggal --}}
        <div data-reveal class="mt-6 flex flex-wrap items-center gap-2 rounded-2xl border border-slate-100 bg-white p-2.5 shadow-sm">
            <div id="report-tabs" class="flex flex-wrap items-center gap-1">
                @foreach ([['Semua', count($reports)], ['Menunggu', $count('Menunggu')], ['Diproses', $count('Diproses')], ['Selesai', $count('Selesai')], ['Ditolak', $count('Ditolak')]] as [$tab, $n])
                    <button type="button" data-tab="{{ $tab }}" class="report-tab inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-[12.5px] font-semibold transition {{ $tab === 'Semua' ? 'bg-cobalt-50 text-cobalt-600' : 'text-slate-500 hover:bg-slate-50' }}">
                        {{ $tab }} <span class="rounded-full bg-slate-100 px-1.5 text-[10.5px] font-bold text-slate-500">{{ $n }}</span>
                    </button>
                @endforeach
            </div>
            <div class="ml-auto flex items-center gap-2">
                <div class="relative hidden sm:block">
                    <button type="button" id="report-date-btn" aria-expanded="false" class="flex items-center gap-1.5 rounded-xl border border-slate-200 px-3 py-2 text-[12px] font-medium text-slate-500 transition hover:border-cobalt-500 hover:text-cobalt-600">
                        <x-hi name="cal" width="14" height="14" />
                        <span id="report-date-label">01/09/2026 –</span>
                    </button>
                    <div id="report-date-menu" class="absolute right-0 z-50 mt-2 hidden w-64 rounded-xl border border-slate-100 bg-white p-4 shadow-xl">
                        <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Rentang Tanggal</p>
                        <div class="mt-2 grid grid-cols-2 gap-2">
                            <label class="block">
                                <span class="mb-1 block text-[11px] font-semibold text-slate-600">Dari</span>
                                <input id="report-date-from" type="date" value="2026-09-01" class="w-full rounded-lg border border-slate-200 px-2 py-2 text-[12px] text-slate-700 focus:border-cobalt-500 focus:outline-none">
                            </label>
                            <label class="block">
                                <span class="mb-1 block text-[11px] font-semibold text-slate-600">Sampai</span>
                                <input id="report-date-to" type="date" value="2026-09-30" class="w-full rounded-lg border border-slate-200 px-2 py-2 text-[12px] text-slate-700 focus:border-cobalt-500 focus:outline-none">
                            </label>
                        </div>
                        <p id="report-date-error" class="mt-1.5 hidden text-[11.5px] font-medium text-rose-600">Tanggal "Dari" tidak boleh lebih besar dari "Sampai".</p>
                        <div class="mt-2.5 flex gap-2">
                            <button type="button" id="report-date-reset" class="flex-1 rounded-lg border border-slate-200 py-2 text-[12px] font-bold text-slate-500 transition hover:border-cobalt-500 hover:text-cobalt-600">Atur Ulang</button>
                            <button type="button" id="report-date-apply" class="flex-1 rounded-lg bg-cobalt-600 py-2 text-[12px] font-bold text-white transition hover:bg-cobalt-700">Terapkan</button>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <button type="button" id="report-cat-btn" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 px-3 py-2 text-[12px] font-semibold text-slate-500 transition hover:border-cobalt-500 hover:text-cobalt-600">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 5h10M11 9h7M11 13h4M3 17l3 3 3-3M6 18V4"/></svg>
                        Filter Lainnya
                    </button>
                    <span id="report-cat-dot" class="absolute -top-1 -right-1 hidden h-3 w-3 rounded-full bg-cobalt-500 ring-2 ring-white"></span>
                    <div id="report-filter-menu" class="absolute right-0 z-50 mt-2 hidden w-48 overflow-hidden rounded-xl border border-slate-100 bg-white shadow-xl">
                        <p class="px-4 pt-3 pb-1 text-[11px] font-bold tracking-wider text-slate-400 uppercase">Kategori</p>
                        <div class="p-1.5">
                            @foreach (['Semua', 'Fasilitas Umum', 'Kebersihan', 'Drainase', 'Ketertiban'] as $cat)
                                <button type="button" data-filter-cat="{{ $cat }}" class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-[13px] font-medium transition {{ $cat === 'Semua' ? 'bg-cobalt-50 text-cobalt-600' : 'text-slate-600 hover:bg-cobalt-50 hover:text-cobalt-600' }}">{{ $cat }}<x-hi name="check" class="cat-check h-4 w-4 text-cobalt-600 {{ $cat === 'Semua' ? '' : 'hidden' }}" /></button>
                            @endforeach
                        </div>
                        <div class="border-t border-slate-100 p-1.5">
                            <button type="button" id="report-filter-reset" class="w-full rounded-lg px-3 py-2 text-[12px] font-bold text-slate-400 transition hover:bg-slate-50 hover:text-slate-600">Atur Ulang</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grid laporan --}}
        <div id="report-grid" class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-4"></div>
        <p id="report-empty" class="hidden py-12 text-center text-[14px] text-slate-400">Tidak ada laporan pada filter ini.</p>

        <div class="mt-8 text-center">
            <button type="button" id="report-more" class="hidden items-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-2.5 text-[13px] font-bold text-slate-600 shadow-sm transition hover:border-cobalt-500 hover:text-cobalt-600">
                Muat lebih banyak
                <x-hi name="chev-down" width="14" height="14" />
            </button>
        </div>
    </div>
</section>

@include('landing.partials.footer')

{{-- Modal detail laporan --}}
<div id="report-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/70 p-4 backdrop-blur-sm">
    <div class="relative max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-lg bg-white shadow-2xl">
        <button type="button" id="report-modal-close" aria-label="Tutup laporan" class="absolute top-3 right-3 z-10 flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-slate-800 shadow transition hover:bg-white">
            <x-hi name="x" width="16" height="16" />
        </button>
        <div class="h-60 overflow-hidden sm:h-72">
            <img id="report-modal-img" src="" alt="" class="h-full w-full object-cover">
        </div>
        <div class="p-6 sm:p-8">
            <div class="flex flex-wrap items-center gap-2">
                <span id="report-modal-status" class="inline-block rounded px-2.5 py-1 text-[11px] font-bold"></span>
                <span id="report-modal-cat" class="inline-block rounded px-2.5 py-1 text-[11px] font-bold"></span>
                <span id="report-modal-time" class="text-[12.5px] font-medium text-slate-400"></span>
            </div>
            <h3 id="report-modal-title" class="mt-3 text-2xl font-extrabold text-slate-900"></h3>
            <p id="report-modal-meta" class="mt-2 text-[13px] text-slate-500"></p>
            <p id="report-modal-desc" class="mt-4 text-[14px] leading-relaxed text-slate-600"></p>
        </div>
    </div>
</div>

{{-- Modal pengaduan baru --}}
<div id="report-new-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/70 p-4 backdrop-blur-sm">
    <div class="relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl sm:p-8">
        <button type="button" id="report-new-close" aria-label="Tutup form" class="absolute top-4 right-4 flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition hover:bg-slate-200">
            <x-hi name="x" width="16" height="16" />
        </button>
        <h3 class="text-xl font-extrabold text-slate-900">Pengaduan Baru</h3>
        <p class="mt-1 text-[13px] text-slate-500">Laporan Anda akan diteruskan ke pengurus RT.</p>
        <form id="report-new-form" class="mt-5 space-y-4">
            <input required id="nr-title" placeholder="Judul laporan" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm transition focus:border-cobalt-500 focus:outline-none focus:ring-4 focus:ring-cobalt-500/10">
            <div class="grid grid-cols-2 gap-4">
                <select id="nr-cat" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-cobalt-500 focus:outline-none">
                    <option>Fasilitas Umum</option>
                    <option>Kebersihan</option>
                    <option>Drainase</option>
                    <option>Ketertiban</option>
                </select>
                <input required id="nr-loc" placeholder="Lokasi" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm transition focus:border-cobalt-500 focus:outline-none focus:ring-4 focus:ring-cobalt-500/10">
            </div>
            <textarea required id="nr-desc" rows="4" placeholder="Deskripsikan masalah..." class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm transition focus:border-cobalt-500 focus:outline-none focus:ring-4 focus:ring-cobalt-500/10"></textarea>
            <div>
                <label for="nr-photo" class="flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3.5 text-[13px] font-medium text-slate-500 transition hover:border-cobalt-500 hover:bg-cobalt-50/50 hover:text-cobalt-600">
                    <x-hi name="camera" width="16" height="16" />
                    <span id="nr-photo-label">Unggah foto bukti (JPG/PNG/WebP, maks 2 MB)</span>
                </label>
                <input id="nr-photo" type="file" accept="image/jpeg,image/png,image/webp,.jpg,.jpeg,.png,.webp" class="sr-only">
                <p id="nr-photo-error" class="mt-1.5 hidden text-[12px] font-medium text-rose-600"></p>
                <div id="nr-photo-preview" class="mt-2 hidden items-center gap-3 rounded-xl border border-slate-200 p-2">
                    <img id="nr-photo-thumb" src="" alt="Pratinjau foto" class="h-14 w-14 rounded-lg object-cover">
                    <span id="nr-photo-name" class="min-w-0 flex-1 truncate text-[12px] text-slate-500"></span>
                    <button type="button" id="nr-photo-remove" aria-label="Hapus foto" class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-rose-50 hover:text-rose-600">
                        <x-hi name="x" width="13" height="13" />
                    </button>
                </div>
            </div>
            <button type="submit" class="w-full rounded-xl bg-cobalt-600 py-3 text-sm font-bold text-white transition hover:bg-cobalt-700 active:scale-[0.99]">Kirim Laporan</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        let reports = @json($reports);
        const assetBase = "{{ asset('images/landing') }}";
        const imgMap = @json(\App\Data\SiteContent::imageMap());
        const imgUrl = (n) => imgMap[n] || (assetBase + '/' + n);
        const statusMeta = @json($statusMeta);
        const catStyle = @json($catStyle);

        const grid = document.getElementById('report-grid');
        const emptyMsg = document.getElementById('report-empty');
        const moreBtn = document.getElementById('report-more');
        const perPage = 8;
        let visible = perPage;
        let tab = 'Semua';
        let cat = 'Semua';

        const initials = (name) => name.split(' ').map((w) => w[0]).slice(0, 2).join('').toUpperCase();

        const filtered = () => {
            let list = reports;
            if (tab !== 'Semua') list = list.filter((r) => r.status === tab);
            if (cat !== 'Semua') list = list.filter((r) => r.cat === cat);
            if (dateFrom || dateTo) {
                list = list.filter((r) => {
                    const ts = r.ts || 0;
                    if (dateFrom && ts < dateFrom) return false;
                    if (dateTo && ts > dateTo) return false;
                    return true;
                });
            }
            return list;
        };

        const cardHtml = (r) => {
            const sm = statusMeta[r.status] || statusMeta['Menunggu'];
            const cs = catStyle[r.cat] || 'bg-slate-100 text-slate-500';
            const imgSrc = r.imgLocal || imgUrl(r.img);
            return '<article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">' +
                '<div class="relative aspect-[4/3] max-h-44 w-full cursor-pointer overflow-hidden bg-slate-100" data-open-report="' + r.id + '">' +
                    '<img src="' + imgSrc + '" alt="' + r.title + '" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">' +
                    '<span class="absolute top-3 left-3 rounded-lg px-2.5 py-1 text-[11px] font-bold shadow ' + sm.badge + '">' + (r.status === 'Menunggu' ? 'Menunggu Tinjauan' : (r.status === 'Diproses' ? 'Sedang Ditangani' : r.status)) + '</span>' +
                    '<span class="absolute top-3 right-3 flex items-center gap-1.5 rounded-lg bg-slate-950/60 py-1 pr-1 pl-2 text-[10.5px] font-medium text-white">' + r.time + '<button type="button" aria-label="Opsi" class="rounded-md p-0.5 text-white/70 transition hover:bg-white/20 hover:text-white"><x-hi name="dots" width="14" height="14" /></button></span>' +
                '</div>' +
                '<div class="flex flex-1 flex-col p-4">' +
                    '<h3 class="line-clamp-2 min-h-[40px] text-[13.5px] font-bold leading-snug text-slate-900">' + r.title + '</h3>' +
                    '<p class="mt-1.5 flex items-center gap-1 text-[11.5px] text-slate-400"><x-hi name="pin" class="h-3 w-3" />' + r.loc + '</p>' +
                    '<div class="mt-2.5 flex items-center gap-2">' +
                        '<span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-cobalt-500 text-[10px] font-bold text-white">' + initials(r.reporter) + '</span>' +
                        '<span><span class="block text-[12px] font-bold leading-tight text-slate-800">' + r.reporter + '</span><span class="block text-[10.5px] text-slate-400">Warga</span></span>' +
                    '</div>' +
                    '<div class="mt-3 mb-3 flex items-center gap-2 border-t border-slate-100 pt-3">' +
                        '<span class="rounded-lg px-2.5 py-1 text-[10.5px] font-bold ' + cs + '">' + r.cat + '</span>' +
                        '<button type="button" data-open-report="' + r.id + '" class="ml-auto rounded-lg border border-cobalt-100 px-3 py-1 text-[11px] font-bold text-cobalt-600 transition hover:bg-cobalt-500 hover:text-white">' + r.action + '</button>' +
                    '</div>' +
                '</div></article>';
        };

        const render = (animate) => {
            const list = filtered();
            grid.innerHTML = list.slice(0, visible).map(cardHtml).join('');
            emptyMsg.classList.toggle('hidden', list.length > 0);
            const hasMore = list.length > visible;
            moreBtn.classList.toggle('hidden', !hasMore);
            moreBtn.classList.toggle('inline-flex', hasMore);
            if (animate !== false) {
                grid.querySelectorAll('article').forEach((card, i) => {
                    card.animate(
                        [{ opacity: 0, transform: 'translate(36px, 36px)' }, { opacity: 1, transform: 'translate(0, 0)' }],
                        { duration: 500, delay: Math.min(i, 7) * 80, easing: 'cubic-bezier(0.22, 1, 0.36, 1)', fill: 'backwards' }
                    );
                });
            }
        };

        document.querySelectorAll('.report-tab').forEach((b) => {
            b.addEventListener('click', () => {
                tab = b.dataset.tab;
                visible = perPage;
                document.querySelectorAll('.report-tab').forEach((x) => {
                    const on = x === b;
                    x.classList.toggle('bg-cobalt-50', on);
                    x.classList.toggle('text-cobalt-600', on);
                    x.classList.toggle('text-slate-500', !on);
                });
                render();
            });
        });

        // ===== Tooltip filter kategori =====
        const filterMenu = document.getElementById('report-filter-menu');
        const catDot = document.getElementById('report-cat-dot');
        const catBtn = document.getElementById('report-cat-btn');
        const dateMenu = document.getElementById('report-date-menu');
        const dateBtn = document.getElementById('report-date-btn');
        const closeMenus = () => {
            filterMenu.classList.add('hidden');
            dateMenu.classList.add('hidden');
            catBtn.setAttribute('aria-expanded', 'false');
            dateBtn.setAttribute('aria-expanded', 'false');
        };
        catBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const willOpen = filterMenu.classList.contains('hidden');
            closeMenus();
            if (willOpen) {
                filterMenu.classList.remove('hidden');
                catBtn.setAttribute('aria-expanded', 'true');
            }
        });
        document.querySelectorAll('[data-filter-cat]').forEach((b) => {
            b.addEventListener('click', () => {
                cat = b.dataset.filterCat;
                document.querySelectorAll('[data-filter-cat]').forEach((x) => {
                    const on = x === b;
                    x.querySelector('.cat-check').classList.toggle('hidden', !on);
                    x.classList.toggle('bg-cobalt-50', on);
                    x.classList.toggle('text-cobalt-600', on);
                    x.classList.toggle('text-slate-600', !on);
                });
                catDot.classList.toggle('hidden', cat === 'Semua');
                visible = perPage;
                render();
                closeMenus();
            });
        });
        document.getElementById('report-filter-reset').addEventListener('click', () => {
            document.querySelector('[data-filter-cat="Semua"]').click();
        });

        // ===== Tooltip rentang tanggal =====
        let dateFrom = 0;
        let dateTo = 0;
        const dateLabel = document.getElementById('report-date-label');
        const dateFromInput = document.getElementById('report-date-from');
        const dateToInput = document.getElementById('report-date-to');
        const dateError = document.getElementById('report-date-error');
        const fmtDate = (v) => { const p = v.split('-'); return p.length === 3 ? p[2] + '/' + p[1] + '/' + p[0] : v; };
        const toTs = (v) => parseInt(v.replaceAll('-', ''), 10);
        dateBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const willOpen = dateMenu.classList.contains('hidden');
            closeMenus();
            if (willOpen) {
                dateError.classList.add('hidden');
                dateMenu.classList.remove('hidden');
                dateBtn.setAttribute('aria-expanded', 'true');
            }
        });
        document.addEventListener('click', (e) => {
            if (!filterMenu.contains(e.target) && !dateMenu.contains(e.target)) closeMenus();
        });
        document.getElementById('report-date-apply').addEventListener('click', () => {
            const f = dateFromInput.value;
            const t = dateToInput.value;
            if (f && t && f > t) {
                dateError.classList.remove('hidden');
                return;
            }
            dateError.classList.add('hidden');
            dateFrom = f ? toTs(f) : 0;
            dateTo = t ? toTs(t) : 0;
            dateLabel.textContent = (f ? fmtDate(f) : '…') + ' – ' + (t ? fmtDate(t) : '…');
            visible = perPage;
            render();
            closeMenus();
        });
        document.getElementById('report-date-reset').addEventListener('click', () => {
            dateFromInput.value = '2026-09-01';
            dateToInput.value = '2026-09-30';
            dateFrom = 0;
            dateTo = 0;
            dateLabel.textContent = '01/09/2026 –';
            visible = perPage;
            render();
            closeMenus();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeMenus();
        });

        moreBtn.addEventListener('click', () => { visible += perPage; render(false); });

        // ===== Modal detail =====
        const modal = document.getElementById('report-modal');
        const openReport = (id) => {
            const r = reports.find((x) => x.id === parseInt(id, 10));
            if (!r) return;
            document.getElementById('report-modal-img').src = r.imgLocal || imgUrl(r.img);
            document.getElementById('report-modal-title').textContent = r.title;
            document.getElementById('report-modal-meta').textContent = r.loc + ' • Dilaporkan oleh ' + r.reporter + ' • ' + r.time;
            document.getElementById('report-modal-desc').textContent = r.desc;
            const sm = statusMeta[r.status];
            const st = document.getElementById('report-modal-status');
            st.textContent = r.status === 'Menunggu' ? 'Menunggu Tinjauan' : (r.status === 'Diproses' ? 'Sedang Ditangani' : r.status);
            st.className = 'inline-block rounded px-2.5 py-1 text-[11px] font-bold ' + sm.badge;
            const ct = document.getElementById('report-modal-cat');
            ct.textContent = r.cat;
            ct.className = 'inline-block rounded px-2.5 py-1 text-[11px] font-bold ' + (catStyle[r.cat] || '');
            document.getElementById('report-modal-time').textContent = r.time;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        };
        const closeReport = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); document.body.style.overflow = ''; };
        document.getElementById('report-modal-close').addEventListener('click', closeReport);
        modal.addEventListener('click', (e) => { if (e.target === modal) closeReport(); });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeReport(); });
        grid.addEventListener('click', (e) => {
            if (e.target.closest('[aria-label="Opsi"]')) return;
            const t = e.target.closest('[data-open-report]');
            if (t) openReport(t.dataset.openReport);
        });

        // ===== Modal pengaduan baru =====
        const newModal = document.getElementById('report-new-modal');
        const openNew = () => { newModal.classList.remove('hidden'); newModal.classList.add('flex'); document.body.style.overflow = 'hidden'; };
        const closeNew = () => { newModal.classList.add('hidden'); newModal.classList.remove('flex'); document.body.style.overflow = ''; };
        document.getElementById('report-new-btn').addEventListener('click', openNew);
        document.getElementById('report-new-close').addEventListener('click', closeNew);
        newModal.addEventListener('click', (e) => { if (e.target === newModal) closeNew(); });
        // ===== Upload foto: validasi + pratinjau =====
        // CATATAN KEAMANAN: validasi di sini hanya untuk UX. Validasi yang
        // menentukan WAJIB di server: cek MIME asli (finfo), ekstensi yang
        // diizinkan, batas ukuran, simpan dengan nama acak di luar web root
        // (atau disk privat) + strip metadata EXIF, dan tolak SVG/script.
        const ALLOWED_MIME = ['image/jpeg', 'image/png', 'image/webp'];
        const ALLOWED_EXT = ['jpg', 'jpeg', 'png', 'webp'];
        const MAX_BYTES = 2 * 1024 * 1024;
        const photoInput = document.getElementById('nr-photo');
        const photoError = document.getElementById('nr-photo-error');
        const photoPreview = document.getElementById('nr-photo-preview');
        const photoThumb = document.getElementById('nr-photo-thumb');
        const photoName = document.getElementById('nr-photo-name');
        const photoLabel = document.getElementById('nr-photo-label');
        let photoUrl = null;
        let photoValid = false;

        const resetPhoto = () => {
            photoInput.value = '';
            photoValid = false;
            if (photoUrl) { URL.revokeObjectURL(photoUrl); photoUrl = null; }
            photoError.classList.add('hidden');
            photoPreview.classList.add('hidden');
            photoPreview.classList.remove('flex');
            photoLabel.textContent = 'Unggah foto bukti (JPG/PNG/WebP, maks 2 MB)';
        };
        const showPhotoError = (msg) => {
            photoError.textContent = msg;
            photoError.classList.remove('hidden');
        };
        photoInput.addEventListener('change', () => {
            photoError.classList.add('hidden');
            photoValid = false;
            const file = photoInput.files[0];
            if (!file) { resetPhoto(); return; }
            const ext = (file.name.split('.').pop() || '').toLowerCase();
            if (!ALLOWED_MIME.includes(file.type) || !ALLOWED_EXT.includes(ext)) {
                resetPhoto();
                showPhotoError('Format tidak didukung. Gunakan JPG, PNG, atau WebP.');
                return;
            }
            if (file.size > MAX_BYTES) {
                resetPhoto();
                showPhotoError('Ukuran melebihi 2 MB. Pilih foto yang lebih kecil.');
                return;
            }
            // Verifikasi magic bytes (signature file) agar tidak bisa dikelabui
            // oleh ekstensi/MIME palsu — baca 12 byte pertama.
            const reader = new FileReader();
            reader.onload = () => {
                const bytes = new Uint8Array(reader.result);
                const isJpeg = bytes[0] === 0xFF && bytes[1] === 0xD8;
                const isPng = bytes[0] === 0x89 && bytes[1] === 0x50 && bytes[2] === 0x4E && bytes[3] === 0x47;
                const isWebp = bytes[0] === 0x52 && bytes[1] === 0x49 && bytes[2] === 0x46 && bytes[3] === 0x46 &&
                    bytes[8] === 0x57 && bytes[9] === 0x45 && bytes[10] === 0x42 && bytes[11] === 0x50;
                if (!(isJpeg || isPng || isWebp)) {
                    resetPhoto();
                    showPhotoError('Isi file bukan gambar yang valid.');
                    return;
                }
                // Verifikasi dimensi (tolak file korup / 0px)
                const probe = new Image();
                probe.onload = () => {
                    if (!probe.naturalWidth || !probe.naturalHeight) {
                        resetPhoto();
                        showPhotoError('Gambar rusak dan tidak bisa dibaca.');
                        return;
                    }
                    photoValid = true;
                    if (photoUrl) URL.revokeObjectURL(photoUrl);
                    photoUrl = URL.createObjectURL(file);
                    photoThumb.src = photoUrl;
                    photoName.textContent = file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
                    photoPreview.classList.remove('hidden');
                    photoPreview.classList.add('flex');
                    photoLabel.textContent = 'Ganti foto';
                };
                probe.onerror = () => {
                    resetPhoto();
                    showPhotoError('Gambar rusak dan tidak bisa dibaca.');
                };
                probe.src = URL.createObjectURL(file);
            };
            reader.onerror = () => {
                resetPhoto();
                showPhotoError('Gagal membaca file.');
            };
            reader.readAsArrayBuffer(file.slice(0, 12));
        });
        document.getElementById('nr-photo-remove').addEventListener('click', resetPhoto);

        document.getElementById('report-new-form').addEventListener('submit', (e) => {
            e.preventDefault();
            if (photoInput.files.length > 0 && !photoValid) {
                showPhotoError('Foto belum valid. Perbaiki atau hapus foto tersebut.');
                return;
            }
            const title = document.getElementById('nr-title').value.trim();
            const loc = document.getElementById('nr-loc').value.trim();
            const desc = document.getElementById('nr-desc').value.trim();
            const category = document.getElementById('nr-cat').value;
            if (!title || !loc || !desc) return;
            // TODO backend: kirim via FormData; server wajib validasi ulang
            // (MIME, ukuran, nama acak, simpan privat). Object URL di bawah
            // hanya pratinjau lokal sementara.
            const newItem = { id: Date.now(), ts: 20260916, title: title, loc: loc, reporter: 'Anda', time: 'Baru saja', status: 'Menunggu', cat: category, action: 'Tinjau', img: 'untuk-warga.png', desc: desc };
            if (photoValid && photoUrl) {
                newItem.imgLocal = photoUrl;
                photoUrl = null; // kepemilikan URL pindah ke kartu
            }
            reports.unshift(newItem);
            document.getElementById('stat-total').textContent = reports.length;
            tab = 'Semua';
            visible = perPage;
            render();
            e.target.reset();
            resetPhoto();
            closeNew();
        });

        render();
    });
</script>
@endsection
