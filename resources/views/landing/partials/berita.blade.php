{{-- ===== Berita & Informasi Section (Publikasi Terkini) ===== --}}
<section id="berita" class="group relative mb-4 bg-white pt-14 pb-20 lg:mb-6 lg:pt-20 lg:pb-28">
    <div class="mx-auto max-w-[1240px] px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="mb-10 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <h2 data-reveal class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                    Berita & Informasi
                </h2>
                <p data-reveal data-delay="80" class="mt-2 max-w-[620px] text-[15px] leading-relaxed text-slate-500">
                    Kabar terbaru dari lingkungan RT 03/RW 02, dari pengumuman resmi hingga kegiatan warga.
                </p>
            </div>
            <a href="#berita" data-reveal data-delay="140" class="inline-flex shrink-0 items-center gap-1.5 self-start rounded-full border border-slate-200 bg-white px-5 py-2 text-[13px] font-bold text-slate-600 shadow-sm transition-all duration-300 hover:border-cobalt-600 hover:text-cobalt-600 sm:self-end lg:translate-x-2 lg:opacity-0 lg:group-hover:translate-x-0 lg:group-hover:opacity-100">
                Lihat Semua
                <x-hi name="chev-right" class="h-4 w-4" />
            </a>
        </div>

        {{-- News Grid (3 Columns x 2 Rows = 6 Cards) --}}
        <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
            @php
                $beritaList = \App\Data\SiteContent::posts();
            @endphp

            @foreach ($beritaList as $index => $item)
            <article data-reveal data-delay="{{ ($index % 3) * 80 }}" class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl">

                {{-- Thumbnail Image Container --}}
                <a href="{{ route('berita.show', $item['slug']) }}" class="relative block h-48 w-full overflow-hidden bg-slate-100 sm:h-52">
                    <img src="{{ \App\Data\SiteContent::image($item['img']) }}" alt="{{ $item['title'] }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                </a>

                {{-- Content Body --}}
                <div class="flex flex-1 flex-col justify-between p-5 text-left">
                    <div>
                        {{-- Date with Calendar Icon --}}
                        <div class="flex items-center gap-1.5 text-[12px] font-medium text-slate-400">
                            <x-hi name="cal" class="h-3.5 w-3.5" />
                            <span>{{ $item['date'] }}</span>
                        </div>

                        {{-- News Title --}}
                        <h3 class="mt-2.5 text-[15px] font-bold leading-snug text-slate-900 transition-colors group-hover:text-cobalt-600 sm:text-[16px]">
                            <a href="{{ route('berita.show', $item['slug']) }}" class="line-clamp-2">
                                {{ $item['title'] }}
                            </a>
                        </h3>

                        {{-- Excerpt Paragraph --}}
                        <p class="mt-2 text-[12.5px] leading-relaxed text-slate-500 line-clamp-2 sm:text-[13px]">
                            <strong class="font-bold text-slate-700">{{ $item['city'] }}</strong> — {{ $item['desc'] }}
                        </p>
                    </div>

                    {{-- Read More Link --}}
                    <div class="mt-4 pt-2">
                        <a href="{{ route('berita.show', $item['slug']) }}" class="group/more inline-flex items-center gap-1 text-[13px] font-bold text-cobalt-600 transition-colors hover:underline">
                            baca selengkapnya
                            <x-hi name="chev-right" class="h-3.5 w-3.5 transition-transform duration-300 group-hover/more:translate-x-1" />
                        </a>
                    </div>
                </div>

            </article>
            @endforeach
        </div>

    </div>
</section>
