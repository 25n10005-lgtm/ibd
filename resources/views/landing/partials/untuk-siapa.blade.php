{{-- ===== Untuk Siapa ===== --}}
<section id="untuk-siapa" class="relative mb-4 overflow-hidden bg-gradient-to-b from-cobalt-50/70 via-[#F2F6FD] to-white lg:mb-6">
    <div class="pointer-events-none absolute -right-32 top-10 h-80 w-80 rounded-full bg-cobalt-500/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -left-24 bottom-0 h-72 w-72 rounded-full bg-forest-500/10 blur-3xl"></div>
    <div class="relative mx-auto max-w-[1240px] px-6 pt-14 pb-20 lg:px-8 lg:pt-20 lg:pb-28">
        <div class="mb-10">
            <h2 data-reveal class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Untuk Siapa Smart RT?</h2>
            <p data-reveal data-delay="80" class="mt-2 max-w-[620px] text-[15px] leading-relaxed text-slate-500">Dari pengurus hingga warga, setiap peran mendapat akses dan kemudahan sesuai kebutuhannya.</p>
        </div>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['img' => 'untuk-pengurus.png', 'title' => 'Pengurus RT', 'desc' => 'Kelola administrasi, warga, keuangan, dan laporan dengan lebih mudah dan efisien.', 'chip' => 'bg-cobalt-50 text-cobalt-600', 'bar' => 'bg-cobalt-500'],
                ['img' => 'untuk-warga.png', 'title' => 'Warga', 'desc' => 'Akses informasi, bayar iuran, ajukan surat, dan dapatkan informasi lingkungan dengan cepat.', 'chip' => 'bg-forest-50 text-forest-600', 'bar' => 'bg-forest-500'],
                ['img' => 'untuk-bendahara.png', 'title' => 'Bendahara', 'desc' => 'Catat pemasukan & pengeluaran secara transparan, laporan rapi dan siap kapan saja.', 'chip' => 'bg-amber-50 text-amber-600', 'bar' => 'bg-sand-400'],
                ['img' => 'untuk-admin.png', 'title' => 'Admin/Super Admin', 'desc' => 'Atur sistem, kelola pengguna, dan pastikan data lingkungan tetap aman.', 'chip' => 'bg-violet-50 text-violet-600', 'bar' => 'bg-violet-500'],
            ] as $i => $card)
            <div data-reveal data-delay="{{ $i * 90 }}" class="group relative overflow-hidden rounded-3xl bg-white p-8 text-center shadow-[0_10px_40px_-15px_rgba(15,23,42,0.12)] transition-all duration-500 hover:-translate-y-2.5 hover:shadow-[0_25px_50px_-15px_rgba(40,99,209,0.35)]">
                <span class="absolute inset-x-8 top-0 h-1 origin-center scale-x-0 rounded-full {{ $card['bar'] }} transition-transform duration-500 group-hover:scale-x-100"></span>
                <span class="mx-auto flex h-32 items-center justify-center rounded-2xl {{ $card['chip'] }} transition-transform duration-500 group-hover:scale-105">
                    <img src="{{ \App\Data\SiteContent::image($card['img']) }}" alt="{{ $card['title'] }}" class="h-[110px] w-full rounded-xl object-cover">
                </span>
                <h3 class="mt-6 text-[17px] font-semibold text-slate-900 transition-colors group-hover:text-cobalt-600">{{ $card['title'] }}</h3>
                <p class="mt-2 text-[13px] leading-relaxed text-slate-500">{{ $card['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
