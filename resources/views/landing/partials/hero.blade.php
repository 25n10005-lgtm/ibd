{{-- ===== Masthead Header Section (Ala Portal Resmi / DKI Jakarta) ===== --}}
<header class="masthead relative min-h-[460px] w-full overflow-hidden bg-[#071d49] bg-cover bg-center text-white lg:min-h-[520px]" style="background-image: url('{{ \App\Data\SiteContent::image('hero.png') }}');">
    {{-- Dark Backdrop Overlay --}}
    <div class="absolute inset-0 flex flex-col justify-end bg-gradient-to-b from-[#051535]/80 via-[#072152]/75 to-[#041026]/90 px-6 pb-[160px] pt-16 text-center lg:pb-[180px] lg:pt-20">
        <div class="mx-auto w-full max-w-[1240px]">
            <div class="mx-auto max-w-[860px]">

                {{-- Official Badge --}}
                <div data-reveal class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/15 px-4 py-1 text-[12px] font-semibold text-white shadow-md backdrop-blur-md">
                    <span class="text-[11px]">🏛️</span>
                    <span>PORTAL RESMI RUKUN TETANGGA 03 / RW 02</span>
                    <span class="opacity-60">•</span>
                    <span class="text-white/90">Kel. Pamulang Barat</span>
                </div>

                {{-- Masthead Headline with Text Shadow --}}
                <h1 data-reveal data-delay="80" class="mt-4 text-3xl font-extrabold tracking-tight text-white drop-shadow-[2px_2px_4px_rgba(0,0,0,0.8)] sm:text-4xl lg:text-[42px] lg:leading-tight">
                    Lingkungan Rukun, Guyub, dan Mandiri
                </h1>

                {{-- Masthead Subtitle with Text Shadow --}}
                <p data-reveal data-delay="140" class="mt-3 text-[14.5px] leading-relaxed text-slate-100 drop-shadow-[1px_1px_3px_rgba(0,0,0,0.8)] sm:text-[15.5px] lg:text-[16.5px]">
                    Kehidupan bertetangga yang guyub dengan transparansi informasi, layanan administrasi digital kilat, inovasi lingkungan, serta partisipasi aktif warga. Selamat datang di Portal Resmi Lingkungan!
                </p>

            </div>
        </div>
    </div>
</header>

{{-- ===== Hero Overlapping Section (-150px) ===== --}}
<section class="relative z-20 mx-auto max-w-[1240px] px-4 sm:px-6" style="margin-top: -150px;">
    <div class="grid gap-6 lg:grid-cols-12">

        {{-- ===== LEFT COLUMN: Carousel Slider (Col 8) ===== --}}
        <div data-reveal="left" class="lg:col-span-8">
            <div id="smart-rt-slider" class="relative overflow-hidden rounded-[8px] bg-white shadow-[0px_10px_30px_#D6E5FC] transition-all duration-300">

                {{-- Slide Container --}}
                <div class="relative w-full">

                    {{-- ===== Slide 1: Inovasi Smart RT ===== --}}
                    <div data-slide-item="0" class="slide-item grid min-h-[350px] transition-opacity duration-500 md:grid-cols-12">
                        {{-- Banner Image with Explore Hover Overlay (Col 5) --}}
                        <div class="group relative min-h-[240px] overflow-hidden bg-slate-800 md:col-span-5 md:min-h-[350px]">
                            <img src="{{ \App\Data\SiteContent::image('rt-1.png') }}" alt="Smart RT Digital" class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>

                            {{-- RT Badge on Image --}}
                            <div class="absolute top-3 left-3 rounded-md bg-cobalt-600/90 px-2.5 py-1 text-[11px] font-bold text-white shadow">
                                RT 03/RW 02 DIGITAL
                            </div>

                            {{-- Hover "EXPLORE NOW" Overlay --}}
                            <a href="#layanan" class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 backdrop-blur-[2px] transition-all duration-300 group-hover:opacity-100">
                                <span class="rounded-full border-2 border-white bg-white/70 px-7 py-2.5 text-center text-xs font-black tracking-wider text-slate-900 shadow-xl backdrop-blur-md transition-transform duration-300 hover:scale-105 hover:bg-white">
                                    EXPLORE NOW
                                </span>
                            </a>
                        </div>

                        {{-- Caption Banner (Col 7) --}}
                        <div class="flex flex-col justify-between p-6 sm:p-7 md:col-span-7 md:h-[350px]">
                            <div class="custom-scrollbar h-[260px] overflow-y-auto pr-2 text-left">
                                <a href="#layanan" class="text-xl font-bold leading-snug text-slate-900 transition-colors hover:text-cobalt-600 sm:text-[22px]">
                                    Transformasi Layanan Digital Warga RT 03/RW 02
                                </a>

                                <p class="mt-3 text-[13.5px] leading-relaxed text-slate-600 sm:text-[14px]">
                                    Perjalanan RT 03/RW 02 menuju lingkungan yang guyub, aman, dan transparan terus berkembang pesat. Melalui platform Smart RT terpadu, warga kini dapat menikmati kemudahan administrasi surat pengantar mandiri dalam hitungan menit, transparansi catatan iuran dan kas secara real-time, serta wadah aspirasi lingkungan yang langsung ditindaklanjuti oleh jajaran pengurus.
                                </p>
                                <p class="mt-2 text-[13.5px] leading-relaxed text-slate-600 sm:text-[14px]">
                                    Inisiatif ini dirancang agar setiap warga memiliki akses setara terhadap informasi lingkungan, jadwal gotong royong, dan laporan pertanggungjawaban kas yang terbuka.
                                </p>
                            </div>

                            {{-- Bottom Link --}}
                            <div class="mt-2 border-t border-slate-100 pt-2 text-right">
                                <a href="#layanan" class="inline-flex items-center gap-1 text-[12.5px] font-bold text-cobalt-600 hover:underline">
                                    Pelajari Layanan Lengkap →
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- ===== Slide 2: Program Padat Karya & Gotong Royong ===== --}}
                    <div data-slide-item="1" class="slide-item hidden grid min-h-[350px] transition-opacity duration-500 md:grid-cols-12">
                        {{-- Banner Image with Explore Hover Overlay (Col 5) --}}
                        <div class="group relative min-h-[240px] overflow-hidden bg-slate-800 md:col-span-5 md:min-h-[350px]">
                            <img src="{{ \App\Data\SiteContent::image('rt-2.png') }}" alt="Gotong Royong & Kerja Bakti" class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>

                            {{-- RT Badge on Image --}}
                            <div class="absolute top-3 left-3 rounded-md bg-forest-600/90 px-2.5 py-1 text-[11px] font-bold text-white shadow">
                                AGENDA LINGKUNGAN
                            </div>

                            {{-- Hover "EXPLORE NOW" Overlay --}}
                            <a href="#layanan" class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 backdrop-blur-[2px] transition-all duration-300 group-hover:opacity-100">
                                <span class="rounded-full border-2 border-white bg-white/70 px-7 py-2.5 text-center text-xs font-black tracking-wider text-slate-900 shadow-xl backdrop-blur-md transition-transform duration-300 hover:scale-105 hover:bg-white">
                                    EXPLORE NOW
                                </span>
                            </a>
                        </div>

                        {{-- Caption Banner (Col 7) --}}
                        <div class="flex flex-col justify-between p-6 sm:p-7 md:col-span-7 md:h-[350px]">
                            <div class="custom-scrollbar h-[260px] overflow-y-auto pr-2 text-left">
                                <a href="#layanan" class="text-xl font-bold leading-snug text-slate-900 transition-colors hover:text-cobalt-600 sm:text-[22px]">
                                    Program Gotong Royong & Padat Karya Kebersihan RT
                                </a>

                                <p class="mt-3 text-[13.5px] leading-relaxed text-slate-600 sm:text-[14px]">
                                    Pengurus RT membuka kesempatan partisipasi warga dalam program pemeliharaan lingkungan, pemilahan bank sampah organik/anorganik, serta peremajaan fasilitas umum lingkungan. Kegiatan berkala ini dilaksanakan untuk memastikan lingkungan tetap asri, drainase lancar bebas genangan, dan pos ronda malam berfungsi optimal.
                                </p>
                                <p class="mt-2 text-[13.5px] leading-relaxed text-slate-600 sm:text-[14px]">
                                    Seluruh warga dipersilakan mendaftarkan diri atau memantau jadwal kegiatan langsung di portal informasi RT.
                                </p>
                            </div>

                            {{-- Bottom Link --}}
                            <div class="mt-2 border-t border-slate-100 pt-2 text-right">
                                <a href="#layanan" class="inline-flex items-center gap-1 text-[12.5px] font-bold text-forest-600 hover:underline">
                                    Lihat Jadwal Agenda →
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Carousel Dot Indicators --}}
                <div class="absolute bottom-3.5 left-1/2 flex -translate-x-1/2 items-center gap-2 md:left-[45%] md:translate-x-0">
                    <button type="button" data-slide-btn="0" class="h-2 w-7 rounded-full bg-cobalt-500 transition-all duration-300" aria-label="Slide 1"></button>
                    <button type="button" data-slide-btn="1" class="h-2 w-2 rounded-full bg-slate-300 transition-all duration-300" aria-label="Slide 2"></button>
                </div>

            </div>
        </div>

        {{-- ===== RIGHT COLUMN: Tabs Populer & Terbaru Box (Col 4) ===== --}}
        <div data-reveal="right" class="lg:col-span-4">
            <div class="relative flex h-[350px] w-full flex-col overflow-hidden rounded-[8px] bg-[#003D81] text-white shadow-[0px_10px_30px_#D6E5FC94]">

                {{-- Nav Tabs Bar --}}
                <div class="flex h-[48px] border-b border-[#003D81] bg-[#eee] text-[13px] font-bold uppercase tracking-wider">
                    <button type="button" data-tab-btn="populer" class="tab-button active flex flex-1 items-center justify-center bg-[#003D81] text-white transition-colors duration-200">
                        Populer
                    </button>
                    <button type="button" data-tab-btn="terbaru" class="tab-button flex flex-1 items-center justify-center bg-[#eee] text-[#888] transition-colors duration-200 hover:text-slate-900">
                        Terbaru
                    </button>
                </div>

                {{-- Tab Content Container (Scrollable) --}}
                <div class="custom-scrollbar flex-1 overflow-y-auto px-5 py-3 text-left">

                    {{-- Tab 1: Populer --}}
                    <div id="tab-pane-populer" class="tab-pane block space-y-2.5 pb-6">
                        @php
                            $populerNews = [
                                ['id' => 1, 'title' => 'Smart RT Hadir dengan Fitur Pengajuan Surat Kilat Mandiri', 'href' => '#layanan'],
                                ['id' => 2, 'title' => 'Transparansi Kas & Rekap Iuran Warga Real-Time 24 Jam', 'href' => '#layanan'],
                                ['id' => 3, 'title' => 'Langkah Bersama: Pemilahan Sampah Organik & Bank Sampah RT', 'href' => '#layanan'],
                                ['id' => 4, 'title' => 'Layanan Siaga Darurat & Kontak Cepat Satpam Lingkungan', 'href' => '#layanan'],
                                ['id' => 5, 'title' => 'Membangun Kerukunan Warga Melalui Ruang Terbuka & Balai Pertemuan', 'href' => '#layanan'],
                            ];
                        @endphp

                        @foreach ($populerNews as $news)
                        <a href="{{ $news['href'] }}" class="group block py-0.5 text-white transition-opacity hover:opacity-90">
                            <div class="flex items-start gap-3">
                                <span class="w-6 shrink-0 text-left text-base font-extrabold text-white">{{ $news['id'] }}</span>
                                <span class="text-[13px] leading-snug font-medium text-white group-hover:underline">{{ $news['title'] }}</span>
                            </div>
                        </a>
                        @if (!$loop->last)
                        <hr class="border-[#8CA8C7]/50 my-1.5">
                        @endif
                        @endforeach
                    </div>

                    {{-- Tab 2: Terbaru --}}
                    <div id="tab-pane-terbaru" class="tab-pane hidden space-y-2.5 pb-6">
                        @php
                            $terbaruNews = [
                                ['id' => 1, 'title' => 'Jadwal Kerja Bakti Kebersihan Lingkungan Minggu Ini', 'href' => '#layanan'],
                                ['id' => 2, 'title' => 'Pendaftaran Program Bantuan Sosial & Pendataan Lansia RT', 'href' => '#layanan'],
                                ['id' => 3, 'title' => 'Pemasangan Titik CCTV Keamanan Baru di Blok C & Blok D', 'href' => '#layanan'],
                                ['id' => 4, 'title' => 'Pelaksanaan Senam Pagi & Cek Kesehatan Gratis Warga', 'href' => '#layanan'],
                            ];
                        @endphp

                        @foreach ($terbaruNews as $news)
                        <a href="{{ $news['href'] }}" class="group block py-0.5 text-white transition-opacity hover:opacity-90">
                            <div class="flex items-start gap-3">
                                <span class="w-6 shrink-0 text-left text-base font-extrabold text-white">{{ $news['id'] }}</span>
                                <span class="text-[13px] leading-snug font-medium text-white group-hover:underline">{{ $news['title'] }}</span>
                            </div>
                        </a>
                        @if (!$loop->last)
                        <hr class="border-[#8CA8C7]/50 my-1.5">
                        @endif
                        @endforeach
                    </div>

                </div>

                {{-- Bottom Link "Selengkapnya..." --}}
                <div class="border-t border-[#8CA8C7]/30 bg-[#003D81] px-5 py-2 text-right">
                    <a href="#layanan" class="text-[12px] font-bold text-white/90 transition-colors hover:text-white hover:underline">
                        Selengkapnya...
                    </a>
                </div>

            </div>
        </div>

    </div>
</section>

{{-- Custom CSS for Scrollbars & Slider/Tabs --}}
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.45);
        border-radius: 10px;
    }
</style>

{{-- Interactive Slider and Tabs Script --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Tab Switcher Logic
        const tabBtns = document.querySelectorAll('[data-tab-btn]');
        const tabPanes = {
            populer: document.getElementById('tab-pane-populer'),
            terbaru: document.getElementById('tab-pane-terbaru')
        };

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.dataset.tabBtn;

                tabBtns.forEach(b => {
                    b.classList.remove('active', 'bg-[#003D81]', 'text-white');
                    b.classList.add('bg-[#eee]', 'text-[#888]');
                });

                btn.classList.add('active', 'bg-[#003D81]', 'text-white');
                btn.classList.remove('bg-[#eee]', 'text-[#888]');

                Object.keys(tabPanes).forEach(k => {
                    if (k === target) {
                        tabPanes[k]?.classList.remove('hidden');
                        tabPanes[k]?.classList.add('block');
                    } else {
                        tabPanes[k]?.classList.add('hidden');
                        tabPanes[k]?.classList.remove('block');
                    }
                });
            });
        });

        // Carousel Slider Logic
        const slideItems = document.querySelectorAll('.slide-item');
        const slideBtns = document.querySelectorAll('[data-slide-btn]');
        let currentSlide = 0;

        function goToSlide(index) {
            slideItems.forEach((item, i) => {
                if (i === index) {
                    item.classList.remove('hidden');
                    item.classList.add('grid');
                } else {
                    item.classList.add('hidden');
                    item.classList.remove('grid');
                }
            });

            slideBtns.forEach((btn, i) => {
                if (i === index) {
                    btn.classList.remove('w-2', 'bg-slate-300');
                    btn.classList.add('w-7', 'bg-cobalt-500');
                } else {
                    btn.classList.remove('w-7', 'bg-cobalt-500');
                    btn.classList.add('w-2', 'bg-slate-300');
                }
            });

            currentSlide = index;
        }

        slideBtns.forEach((btn, i) => {
            btn.addEventListener('click', () => goToSlide(i));
        });

        // Auto slide every 6s
        setInterval(() => {
            const next = (currentSlide + 1) % slideItems.length;
            goToSlide(next);
        }, 6000);
    });
</script>
