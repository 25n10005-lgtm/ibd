{{-- ===== Dipercaya ===== --}}
<section class=">
    <div class="mx-auto max-w-[1240px] px-6 py-16 lg:px-8 lg:py-24">
        <div class="mb-10">
            <h2 data-reveal class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Dipercaya oleh banyak RT di berbagai daerah</h2>
            <p data-reveal data-delay="80" class="mt-2 max-w-[620px] text-[15px] leading-relaxed text-slate-500">Bersama membangun lingkungan yang lebih tertata dan harmonis.</p>
        </div>
        <div class="grid grid-cols-3 gap-8 lg:grid-cols-6">
            @foreach ([
                ['img' => 'rt-1.png', 'rt' => 'RT 01 / RW 04', 'daerah' => 'Depok, Jawa Barat'],
                ['img' => 'rt-2.png', 'rt' => 'RT 02 / RW 01', 'daerah' => 'Sleman, DI Yogyakarta'],
                ['img' => 'rt-3.png', 'rt' => 'RT 05 / RW 03', 'daerah' => 'Malang, Jawa Timur'],
                ['img' => 'rt-4.png', 'rt' => 'RT 04 / RW 02', 'daerah' => 'Makassar, Sulawesi Selatan'],
                ['img' => 'rt-5.png', 'rt' => 'RT 03 / RW 05', 'daerah' => 'Balikpapan, Kaltim'],
                ['img' => 'rt-6.png', 'rt' => 'RT 06 / RW 01', 'daerah' => 'Bekasi, Jawa Barat'],
            ] as $i => $rt)
            <div data-reveal data-delay="{{ $i * 70 }}" class="group">
                <span class="mx-auto block h-[85px] w-[70px] overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 transition-all duration-300 group-hover:-translate-y-1.5 group-hover:shadow-lg group-hover:ring-cobalt-100">
                    <img src="{{ \App\Data\SiteContent::image($rt['img']) }}" alt="{{ $rt['rt'] }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110">
                </span>
                <p class="mt-3 text-[15px] font-semibold text-slate-800 transition-colors group-hover:text-cobalt-600">{{ $rt['rt'] }}</p>
                <p class="text-[14px] text-slate-400">{{ $rt['daerah'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
