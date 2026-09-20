{{-- ===== FAQ ===== --}}
<section class="bg-slate-50/75">
    <div class="mx-auto max-w-[1240px] px-6 pt-14 pb-24 lg:px-8 lg:pt-20 lg:pb-32">
        <div class="mb-10">
            <h2 data-reveal class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Pertanyaan yang sering ditanyakan</h2>
            <p data-reveal data-delay="80" class="mt-2 max-w-[620px] text-[15px] leading-relaxed text-slate-500">Tidak menemukan jawaban? Hubungi pengurus RT atau tim Smart RT.</p>
        </div>
        <div class="space-y-4">
            @foreach ([
                ['q' => 'Apakah Smart RT dikenakan biaya?', 'a' => 'Smart RT dapat digunakan secara gratis untuk operasional dasar RT. Hubungi tim kami untuk informasi paket lanjutan.'],
                ['q' => 'Apakah data warga aman?', 'a' => 'Ya. Data warga disimpan dengan enkripsi, hak akses berbasis peran, dan isolasi data antar-RT di sisi server.'],
                ['q' => 'Siapa saja yang bisa menggunakan Smart RT?', 'a' => 'Pengurus RT (Ketua, Sekretaris, Bendahara) dan seluruh warga yang terdaftar di lingkungan RT.'],
                ['q' => 'Bagaimana cara memulai menggunakan Smart RT?', 'a' => 'Daftarkan RT Anda melalui halaman ini, lengkapi data lingkungan, lalu undang warga untuk bergabung.'],
                ['q' => 'Apakah bisa digunakan di semua perangkat?', 'a' => 'Bisa. Smart RT adalah Progressive Web App yang berjalan di HP, tablet, dan desktop serta dapat dipasang ke layar utama.'],
            ] as $i => $faq)
            <details data-reveal data-delay="{{ $i * 60 }}" class="group rounded-2xl border border-slate-200 bg-white px-6 py-5 shadow-sm transition-all duration-300 open:border-cobalt-100 open:shadow-[0_15px_40px_-15px_rgba(40,99,209,0.3)] hover:border-cobalt-100">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-[18px] text-slate-800 transition-colors hover:text-cobalt-600 [&::-webkit-details-marker]:hidden">
                    {{ $faq['q'] }}
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white text-cobalt-500 shadow-sm transition-all duration-300 group-open:rotate-45 group-open:bg-cobalt-500 group-open:text-white">
                        <x-hi name="plus" width="14" height="14" />
                    </span>
                </summary>
                <p class="mt-3 text-[15px] leading-relaxed text-slate-500">{{ $faq['a'] }}</p>
            </details>
            @endforeach
        </div>
    </div>
</section>
