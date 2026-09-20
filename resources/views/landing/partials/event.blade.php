{{-- ===== Agenda & Event Section (Impeccable Civic Event Design) ===== --}}
<section id="agenda" class="group relative bg-slate-50/75 pt-14 pb-24 lg:pt-20 lg:pb-32">
    <div class="mx-auto max-w-[1240px] px-6 lg:px-8">

        {{-- Section Title --}}
        <div class="mb-10 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <h2 data-reveal class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                    Event & Agenda Warga RT 03/RW 02
                </h2>
                <p data-reveal data-delay="80" class="mt-2 max-w-[620px] text-[15px] leading-relaxed text-slate-500">
                    Dapatkan informasi kegiatan sosial, kerja bakti, musyawarah warga, dan pelayanan posyandu lingkungan terpadu.
                </p>
            </div>
            <a href="#agenda" data-reveal data-delay="140" class="inline-flex shrink-0 items-center gap-1.5 self-start rounded-full border border-slate-200 bg-white px-5 py-2 text-[13px] font-bold text-slate-600 shadow-sm transition-all duration-300 hover:border-cobalt-600 hover:text-cobalt-600 sm:self-end lg:translate-x-2 lg:opacity-0 lg:group-hover:translate-x-0 lg:group-hover:opacity-100">
                Lihat Semua
                <x-hi name="chev-right" class="h-4 w-4" />
            </a>
        </div>

        {{-- 2-Column Event Layout --}}
        <div class="grid items-stretch gap-8 lg:grid-cols-12">

            {{-- ===== LEFT COLUMN: Large Featured Event Card (Col 7) ===== --}}
            <div data-reveal="left" class="group relative flex min-h-[460px] flex-col justify-between overflow-hidden rounded-[24px] bg-slate-900 p-6 shadow-xl shadow-slate-900/10 ring-1 ring-slate-900/5 sm:p-8 lg:col-span-7 lg:min-h-[520px]">

                {{-- Background Image with Cinematic Gradient Overlay --}}
                <img src="{{ \App\Data\SiteContent::image('rt-3.png') }}" alt="Kerja Bakti & Penataan Lingkungan" class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/55 to-black/35"></div>

                {{-- Top Emblem & Official Header on Card --}}
                <div class="relative z-10 flex items-center justify-between">


                    <span class="hidden rounded-full bg-emerald-500/20 px-3 py-1 text-[11px] font-bold text-emerald-300 ring-1 ring-emerald-400/30 backdrop-blur-sm sm:inline-block">
                        Terbuka untuk Seluruh Warga
                    </span>
                </div>

                {{-- Bottom Content Overlay on Featured Card --}}
                <div class="relative z-10 mt-auto pt-16">

                    {{-- Badge Event Terbaru --}}
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-600 px-3.5 py-1 text-[11.5px] font-bold text-white shadow-md">
                        <span class="h-2 w-2 animate-ping rounded-full bg-white"></span>
                        Event Terbaru
                    </span>

                    {{-- Featured Title --}}
                    <h3 class="mt-3 text-2xl font-black tracking-tight text-white sm:text-3xl lg:text-[32px] lg:leading-snug">
                        Kerja Bakti Akbar & Normalisasi Saluran Lingkungan
                    </h3>

                    {{-- Subtitle / Description --}}
                    <p class="mt-2.5 max-w-[560px] text-[13.5px] leading-relaxed text-slate-200 sm:text-[14.5px]">
                        Gerakan serempak pembersihan drainase warga, pemilahan sampah organik, serta peremajaan tanaman balai RT guna mengantisipasi musim penghujan.
                    </p>

                    {{-- Date & Location Metadata --}}
                    <div class="mt-4 flex flex-wrap items-center gap-4 text-[13px] font-semibold text-slate-200">
                        <div class="flex items-center gap-1.5">
                            <x-hi name="cal" class="h-4 w-4 text-sand-300" />
                            <span>Minggu, 20 Sep 2026 (07.00 WIB)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <x-hi name="pin" class="h-4 w-4 text-cyan-300" />
                            <span>Seluruh Area Blok A – D</span>
                        </div>
                    </div>

                    {{-- Bottom Action Button / Survey Participation --}}
                    <div class="mt-6 flex flex-wrap items-center gap-3 border-t border-white/15 pt-4">
                        <a href="#fitur" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-[13px] font-bold text-slate-900 shadow-lg transition-all hover:scale-[1.02] hover:bg-slate-100">
                            <x-hi name="doc" class="h-4 w-4 text-cobalt-600" />
                            <span>Isi Konfirmasi Kehadiran</span>
                        </a>
                        <span class="text-[12px] text-slate-300">Disediakan konsumsi & peralatan gotong royong</span>
                    </div>

                </div>

            </div>

            {{-- ===== RIGHT COLUMN: Side Event List Card (Col 5) ===== --}}
            <div data-reveal="right" class="flex flex-col justify-between rounded-[24px] border border-slate-200/80 bg-white p-6 shadow-xl shadow-slate-900/5 sm:p-7 lg:col-span-5">

                {{-- Side Card Header --}}
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-xl font-bold tracking-tight text-slate-900">
                        Agenda Lingkungan
                    </h3>
                    <p class="mt-1 text-[13.5px] text-slate-500">
                        Dapatkan informasi terkait seluruh agenda dan kegiatan di lingkungan RT 03/RW 02.
                    </p>
                </div>

                {{-- Event Items List --}}
                <div class="my-4 space-y-4">
                    @php
                        $sideEvents = [
                            [
                                'img' => 'rt-4.png',
                                'title' => 'Senam Sehat & Pemeriksaan Kesehatan Lansia',
                                'status' => 'Agenda Mendatang',
                                'status_color' => 'bg-sky-50 text-sky-700 ring-sky-200',
                                'location' => 'Balai Warga RT 03',
                                'date' => 'Sabtu, 26 Sep 2026',
                            ],
                            [
                                'img' => 'rt-5.png',
                                'title' => 'Musyawarah Warga: Laporan Transparansi Kas Triwulan',
                                'status' => 'Agenda Mendatang',
                                'status_color' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                                'location' => 'Pendopo Warga Blok B',
                                'date' => 'Minggu, 27 Sep 2026',
                            ],
                            [
                                'img' => 'rt-6.png',
                                'title' => 'Pojok Belajar & Bimbingan Literasi Anak Lingkungan',
                                'status' => 'Telah Selesai',
                                'status_color' => 'bg-slate-100 text-slate-600 ring-slate-200',
                                'location' => 'Pojok Baca RT 03',
                                'date' => 'Minggu, 13 Sep 2026',
                            ],
                            [
                                'img' => 'rt-2.png',
                                'title' => 'Apel Ronda Siskamling & Evaluasi Keamanan Gerbang',
                                'status' => 'Telah Selesai',
                                'status_color' => 'bg-slate-100 text-slate-600 ring-slate-200',
                                'location' => 'Pos Ronda Utama Blok A',
                                'date' => 'Sabtu, 12 Sep 2026',
                            ],
                        ];
                    @endphp

                    @foreach ($sideEvents as $event)
                    <div class="group flex items-start gap-3.5 rounded-2xl p-2.5 transition-colors hover:bg-slate-50">
                        {{-- Thumbnail Image --}}
                        <div class="relative h-14 w-14 shrink-0 overflow-hidden rounded-xl bg-slate-100 shadow-sm ring-1 ring-slate-200/60">
                            <img src="{{ \App\Data\SiteContent::image($event['img']) }}" alt="{{ $event['title'] }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110">
                        </div>

                        {{-- Event Details --}}
                        <div class="flex-1 text-left">
                            <a href="#fitur" class="text-[13.5px] font-bold leading-snug text-slate-900 transition-colors group-hover:text-cobalt-600 line-clamp-2">
                                {{ $event['title'] }}
                            </a>

                            <div class="mt-1.5 flex flex-wrap items-center gap-2">
                                <span class="rounded-md px-2 py-0.5 text-[10.5px] font-bold ring-1 {{ $event['status_color'] }}">
                                    {{ $event['status'] }}
                                </span>
                                <span class="text-[11.5px] text-slate-400">
                                    {{ $event['location'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @if (!$loop->last)
                    <hr class="border-slate-100">
                    @endif
                    @endforeach
                </div>

                {{-- Bottom Link "Lihat Agenda Lainnya →" --}}
                <div class="border-t border-slate-100 pt-3 text-center sm:text-right">
                    <a href="#agenda" class="inline-flex items-center gap-1.5 text-[13.5px] font-bold text-cobalt-600 transition-all hover:gap-2.5 hover:text-cobalt-700">
                        Lihat Agenda Lainnya
                        <x-hi name="chev-right" class="h-4 w-4" />
                    </a>
                </div>

            </div>

        </div>

    </div>
</section>
