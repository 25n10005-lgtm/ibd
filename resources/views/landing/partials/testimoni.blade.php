{{-- ===== Testimoni ===== --}}
<section id="testimoni" class="mb-4 bg-white lg:mb-6">
    <div class="mx-auto max-w-[1240px] px-6 pt-14 pb-20 lg:px-8 lg:pt-20 lg:pb-28">

        {{-- Kartu testimoni: video + kutipan --}}
        <div class="grid items-stretch gap-6 lg:grid-cols-2">
            {{-- Video --}}
            <div data-reveal="left" id="testi-video" class="group relative h-[280px] cursor-pointer overflow-hidden rounded-2xl bg-slate-900 sm:h-[360px] lg:h-[400px]" data-youtube-id="dQw4w9WgXcQ">
                <img src="{{ \App\Data\SiteContent::image('testimoni-video.png') }}" alt="Testimoni Pak Hadi Supriyono" class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-slate-950/10 transition-colors group-hover:bg-slate-950/20"></div>
                {{-- Tombol play tepat di tengah --}}
                <button type="button" aria-label="Putar video testimoni" class="absolute top-1/2 left-1/2 flex h-16 w-16 -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full bg-white shadow-2xl transition-transform duration-300 group-hover:scale-110">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="#374151" class="translate-x-[1px]"><path d="M8 5.5v13l11-6.5z"/></svg>
                </button>
            </div>

            {{-- Kutipan --}}
            <div data-reveal="right" class="flex flex-col rounded-2xl bg-white p-8 shadow-[0_10px_40px_-12px_rgba(15,23,42,0.15)] ring-1 ring-slate-100 sm:p-10 lg:p-12">
                {{-- Ikon kutip ganda seperti Figma --}}
                <svg width="36" height="28" viewBox="0 0 36 28" fill="none" aria-hidden="true">
                    <path d="M0 28V16.8C0 7.47 5.6 1.4 14.84 0l1.68 5.04c-4.76 1.4-7.28 3.92-7.56 7.84H15.4V28H0Zm20.72 0V16.8C20.72 7.47 26.32 1.4 35.56 0l1.68 5.04c-4.76 1.4-7.28 3.92-7.56 7.84H36V28H20.72Z" fill="#2E9E5B" transform="scale(0.95)"/>
                </svg>
                <p id="testi-quote" class="mt-4 text-[15px] font-medium leading-relaxed text-slate-800 sm:text-[16px]">
                    Smart RT membantu kami bekerja lebih tertata dan warga jadi lebih mudah mendapatkan informasi. Semua kegiatan tercatat rapi, laporan kas transparan, dan pengumuman tersebar merata. Transparan, praktis, dan terpercaya untuk kebutuhan harian lingkungan.
                </p>
                {{-- Footer kartu: selalu menempel di bawah --}}
                <div class="mt-auto pt-6">
                    <p id="testi-name" class="text-[13px] font-semibold text-slate-900">Pak Hadi Supriyono</p>
                    <p id="testi-role" class="text-[12px] text-slate-400">Ketua RT 03 / RW 02, Kel. Pamulang Barat</p>
                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4">
                        <div id="testi-dots" class="flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-600 transition-colors"></span>
                            <span class="h-1.5 w-1.5 rounded-full bg-slate-200 transition-colors"></span>
                            <span class="h-1.5 w-1.5 rounded-full bg-slate-200 transition-colors"></span>
                            <span class="h-1.5 w-1.5 rounded-full bg-slate-200 transition-colors"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" id="testi-prev" aria-label="Testimoni sebelumnya" class="flex h-9 w-9 items-center justify-center rounded-full bg-white text-slate-500 shadow-sm ring-1 ring-slate-200 transition hover:bg-cobalt-500 hover:text-white hover:ring-cobalt-500 active:scale-95">
                                <x-hi name="chev-left" width="18" height="18" />
                            </button>
                            <button type="button" id="testi-next" aria-label="Testimoni berikutnya" class="flex h-9 w-9 items-center justify-center rounded-full bg-white text-slate-500 shadow-sm ring-1 ring-slate-200 transition hover:bg-cobalt-500 hover:text-white hover:ring-cobalt-500 active:scale-95">
                                <x-hi name="chev-right" width="18" height="18" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dipercaya RT --}}
        <div class="mt-10">
            @php
                $trustedRts = [
                    ['img' => 'rt-1.png', 'rt' => 'RT 01 / RW 04', 'loc' => 'Depok, Jawa Barat'],
                    ['img' => 'rt-2.png', 'rt' => 'RT 02 / RW 01', 'loc' => 'Sleman, DI Yogyakarta'],
                    ['img' => 'rt-3.png', 'rt' => 'RT 05 / RW 03', 'loc' => 'Malang, Jawa Timur'],
                    ['img' => 'rt-4.png', 'rt' => 'RT 04 / RW 02', 'loc' => 'Makassar, Sulawesi Selatan'],
                    ['img' => 'rt-5.png', 'rt' => 'RT 03 / RW 05', 'loc' => 'Balikpapan, Kaltim'],
                    ['img' => 'rt-6.png', 'rt' => 'RT 06 / RW 01', 'loc' => 'Bekasi, Jawa Barat'],
                ];
            @endphp
            <div class="grid grid-cols-3 gap-6 text-center sm:grid-cols-3 lg:grid-cols-6">
                @foreach ($trustedRts as $i => $rt)
                    <div data-reveal data-delay="{{ $i * 60 }}">
                        <span class="mx-auto block h-14 w-14 overflow-hidden rounded-2xl ring-1 ring-slate-100">
                            <img src="{{ \App\Data\SiteContent::image($rt['img']) }}" alt="{{ $rt['rt'] }}" loading="lazy" class="h-full w-full object-cover">
                        </span>
                        <p class="mt-3 text-[12px] font-semibold text-slate-800">{{ $rt['rt'] }}</p>
                        <p class="text-[11px] text-slate-400">{{ $rt['loc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</section>

{{-- Modal YouTube testimoni --}}
<div id="testi-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/70 p-4 backdrop-blur-sm">
    <div class="relative w-full max-w-3xl overflow-hidden rounded-2xl bg-black shadow-2xl">
        <button type="button" id="testi-close" aria-label="Tutup video" class="absolute top-3 right-3 z-10 flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-slate-800 transition hover:bg-white">
            <x-hi name="x" width="16" height="16" />
        </button>
        <div class="aspect-video w-full">
            <iframe id="testi-iframe" class="h-full w-full" src="" title="Video testimoni Smart RT" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const video = document.getElementById('testi-video');
        const modal = document.getElementById('testi-modal');
        const iframe = document.getElementById('testi-iframe');
        const closeBtn = document.getElementById('testi-close');
        if (!video || !modal || !iframe) return;

        const ytId = video.dataset.youtubeId || '';
        const openModal = () => {
            iframe.src = 'https://www.youtube.com/embed/' + ytId + '?autoplay=1&rel=0';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        };
        const closeModal = () => {
            iframe.src = ''; // menghentikan (pause) video
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        };
        video.addEventListener('click', openModal);
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal(); });

        // Carousel kutipan: chevron prev/next + dots
        const quotes = [
            { text: 'Smart RT membantu kami bekerja lebih tertata dan warga jadi lebih mudah mendapatkan informasi. Semua kegiatan tercatat rapi, laporan kas transparan, dan pengumuman tersebar merata. Transparan, praktis, dan terpercaya untuk kebutuhan harian lingkungan.', name: 'Pak Hadi Supriyono', role: 'Ketua RT 03 / RW 02, Kel. Pamulang Barat' },
            { text: 'Dulu rekap kas manual sering bikin pusing saat rapat bulanan. Sekarang semua iuran warga tercatat otomatis dan laporan kas bisa dicek kapan saja lewat HP. Rapat bulanan jadi jauh lebih tenang dan warga makin percaya.', name: 'Ibu Siti Nurhaliza', role: 'Bendahara RT 02 / RW 01, Sleman' },
            { text: 'Urus surat pengantar tidak perlu lagi antre ke rumah Pak RT. Cukup isi form dari HP, verifikasi otomatis, selesai dengan tanda tangan digital. Proses yang dulu berhari-hari kini cukup hitungan menit.', name: 'Bambang Wijayanto', role: 'Warga RT 05 / RW 03, Malang' },
            { text: 'Jadwal ronda dan pengumuman selalu sampai ke semua warga tepat waktu. Tidak ada lagi info yang terlewat atau simpang siur di grup chat. Lingkungan jadi lebih kompak, tertib, dan aman.', name: 'Pak Andi Pratama', role: 'Ketua RT 04 / RW 02, Makassar' },
        ];
        const qEl = document.getElementById('testi-quote');
        const nEl = document.getElementById('testi-name');
        const rEl = document.getElementById('testi-role');
        const dots = document.querySelectorAll('#testi-dots span');
        const prev = document.getElementById('testi-prev');
        const next = document.getElementById('testi-next');
        let idx = 0;
        const show = (i) => {
            idx = (i + quotes.length) % quotes.length;
            if (qEl) qEl.textContent = quotes[idx].text;
            if (nEl) nEl.textContent = quotes[idx].name;
            if (rEl) rEl.textContent = quotes[idx].role;
            dots.forEach((d, di) => {
                d.classList.toggle('bg-emerald-600', di === idx);
                d.classList.toggle('bg-slate-200', di !== idx);
            });
        };
        if (prev) prev.addEventListener('click', () => show(idx - 1));
        if (next) next.addEventListener('click', () => show(idx + 1));
        dots.forEach((d, di) => d.addEventListener('click', () => show(di)));
    });
</script>
