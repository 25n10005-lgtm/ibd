{{-- ===== CTA: Smart RT dalam Angka ===== --}}
<section class="relative mb-4 bg-gradient-to-r from-[#071D49] via-cobalt-700 to-cobalt-600 lg:mb-6">
    <div class="relative mx-auto max-w-[1240px] px-6 pt-14 pb-18 text-center lg:px-8 lg:pt-20 lg:pb-20">
        <p data-reveal class="text-[13px] font-bold tracking-[0.18em] text-white/80">DATA & FAKTA</p>
        <h2 data-reveal data-delay="80" class="mt-3 text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-[40px]">Smart RT dalam Angka</h2>

        @php
            $stats = [
                ['value' => '12', 'label' => 'RT Bergabung'],
                ['value' => '1.250+', 'label' => 'KK Terdata'],
                ['value' => '4.800+', 'label' => 'Warga Terdaftar'],
                ['value' => '350+', 'label' => 'Surat Diterbitkan'],
                ['value' => '96%', 'label' => 'Iuran Tepat Waktu'],
                ['value' => '120+', 'label' => 'Kegiatan Terlaksana'],
            ];
        @endphp
        <div class="mt-12 grid grid-cols-2 gap-y-10 sm:grid-cols-3 lg:grid-cols-6">
            @foreach ($stats as $i => $stat)
                <div data-reveal data-delay="{{ $i * 70 }}" class="group rounded-2xl px-4 py-3 transition-all duration-300 hover:-translate-y-1 hover:bg-white/10 lg:border-l lg:border-white/20 lg:first:border-l-0">
                    <p class="text-3xl font-extrabold tracking-tight text-white transition-transform duration-300 group-hover:scale-105 lg:text-[36px]">{{ $stat['value'] }}</p>
                    <p class="mt-1.5 text-[13px] font-medium text-white/80">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
