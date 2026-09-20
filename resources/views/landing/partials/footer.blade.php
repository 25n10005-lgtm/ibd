{{-- ===== Footer ===== --}}
<footer id="kontak" class="bg-slate-900 text-white">
    <div class="mx-auto grid max-w-[1240px] gap-10 px-6 py-14 md:grid-cols-2 lg:grid-cols-4 lg:px-8">
        <div>
            <div class="flex items-center gap-3">
                <span class="flex h-[47px] w-[47px] items-center justify-center rounded-xl bg-white/10">
                    <x-hi name="home" width="26" height="26" class="text-[#F6C66A]" />
                </span>
                <span class="text-[20px] font-semibold">SMART RT</span>
            </div>
            <p class="mt-4 max-w-[300px] text-[15px] leading-relaxed text-white/70">Smart RT adalah platform digital untuk membantu pengurus RT mengelola lingkungan dengan lebih mudah, transparan, dan terhubung.</p>
            <div class="mt-5 flex gap-3">
                @foreach (['M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z', 'M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1a10.66 10.66 0 0 1-9-4.54s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z', 'M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4V8h4v2a6 6 0 0 1 2-2zM2 9h4v12H2zM4 2a2 2 0 1 1 0 4 2 2 0 0 1 0-4z'] as $icon)
                <a href="#" class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition-all duration-300 hover:-translate-y-1 hover:bg-cobalt-500">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="{{ $icon }}"/></svg>
                </a>
                @endforeach
            </div>
        </div>
        <div>
            <h4 class="text-[19px] font-semibold">Menu</h4>
            <ul class="mt-4 space-y-3 text-[15px] text-white/70">
                <li><a href="#" class="transition-all hover:pl-1 hover:text-white">Beranda</a></li>
                <li><a href="#fitur" class="transition-all hover:pl-1 hover:text-white">Fitur</a></li>
                <li><a href="#untuk-siapa" class="transition-all hover:pl-1 hover:text-white">Manfaat</a></li>
                <li><a href="#" class="transition-all hover:pl-1 hover:text-white">Tentang</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-[19px] font-semibold">Bantuan</h4>
            <ul class="mt-4 space-y-3 text-[15px] text-white/70">
                <li><a href="#" class="transition-all hover:pl-1 hover:text-white">FAQ</a></li>
                <li><a href="#" class="transition-all hover:pl-1 hover:text-white">Panduan Pengguna</a></li>
                <li><a href="#" class="transition-all hover:pl-1 hover:text-white">Kebijakan Privasi</a></li>
                <li><a href="#" class="transition-all hover:pl-1 hover:text-white">Syarat & Ketentuan</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-[19px] font-semibold">Kontak</h4>
            <ul class="mt-4 space-y-3 text-[15px] text-white/70">
                <li>Jl. Kebersamaan No. 10, Pamulang, Tangerang Selatan</li>
                <li>0812-3456-7890</li>
                <li>info@smartrt.id</li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/10">
        <p class="mx-auto max-w-[1240px] px-6 py-5 text-center text-[13px] text-white/50 lg:px-8">Untuk Lingkungan yang Lebih Baik &copy; {{ date('Y') }} Smart RT.</p>
    </div>
</footer>
