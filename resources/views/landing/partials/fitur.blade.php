{{-- ===== Fitur ===== --}}
<section id="fitur" class="mb-4 bg-white lg:mb-6">
    <div class="mx-auto grid max-w-[1240px] items-center gap-12 px-6 pt-14 pb-20 lg:grid-cols-2 lg:px-8 lg:pt-20 lg:pb-28">
        <div data-reveal="left">
            <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Semua yang dibutuhkan RT, dalam satu aplikasi.</h2>
            <p class="mt-2 max-w-[620px] text-[15px] leading-relaxed text-slate-500">Smart RT dirancang untuk memudahkan kerja pengurus dan memberikan pengalaman terbaik untuk warga</p>
            <ul class="mt-8 space-y-3 text-[17px] text-slate-600">
                @foreach (['Dashboard ringkas dan mudah dipahami', 'Kelola data warga & kartu keluarga', 'Tagihan iuran dan pembayaran online', 'Pemasukan & pengeluaran kas RT', 'Pengajuan surat dan layanan administrasi', 'Laporan otomatis dengan data real-time', 'Pengumuman, agenda & kegiatan lingkungan'] as $i => $fitur)
                <li data-reveal data-delay="{{ $i * 60 }}" class="group flex items-center gap-4 rounded-2xl px-3 py-2 transition-all duration-300 hover:translate-x-1.5 hover:bg-cobalt-50/60">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-forest-50 transition-transform duration-300 group-hover:scale-110">
                        <x-hi name="check" width="16" height="16" class="text-[#20A774]" />
                    </span>
                    {{ $fitur }}
                </li>
                @endforeach
            </ul>
        </div>
        <div data-reveal="right" class="group rounded-3xl border border-slate-100 bg-white p-6 shadow-[0_20px_60px_-20px_rgba(40,99,209,0.25)] transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_30px_70px_-20px_rgba(40,99,209,0.4)]">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-cobalt-50 transition-transform duration-300 group-hover:scale-110">
                        <x-hi name="home" width="20" height="20" class="text-[#2863D1]" />
                    </span>
                    <div>
                        <p class="text-[15px] font-bold text-slate-900">Dashboard</p>
                        <p class="text-[13px] text-slate-400">Selamat pagi, Pak Ketua 👋</p>
                    </div>
                </div>
                <span class="text-[13px] text-slate-400">Per 20 Mei 2026</span>
            </div>
            <p class="mt-4 text-[13px] text-slate-500">Berikut ringkasan lingkungan Anda hari ini.</p>
            <div class="mt-4 grid grid-cols-3 gap-4">
                <div class="rounded-2xl bg-slate-50 p-4 transition-colors duration-300 hover:bg-cobalt-50">
                    <p class="text-[11px] text-slate-400">Total Warga</p>
                    <p class="text-[19px] font-bold text-slate-900">245</p>
                    <p class="text-[11px] font-medium text-forest-600">Sudah Bayar</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4 transition-colors duration-300 hover:bg-cobalt-50">
                    <p class="text-[11px] text-slate-400">Iuran Bulan Ini</p>
                    <p class="text-[19px] font-bold text-slate-900">86%</p>
                    <p class="text-[11px] text-slate-400">Batas s/d 10 Jun 2026</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4 transition-colors duration-300 hover:bg-cobalt-50">
                    <p class="text-[11px] text-slate-400">Saldo Kas RT</p>
                    <p class="text-[19px] font-bold text-slate-900">Rp12.450.000</p>
                    <p class="text-[11px] text-slate-400">Kapal Kuningan</p>
                </div>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-4">
                <div class="rounded-2xl bg-slate-50 p-4 transition-colors duration-300 hover:bg-cobalt-50">
                    <div class="flex items-center justify-between">
                        <p class="text-[13px] font-medium text-slate-600">Pengajuan Surat</p>
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-sand-300 text-[13px] font-bold text-white">7</span>
                    </div>
                    <div class="mt-2 flex h-2 overflow-hidden rounded-full bg-slate-200">
                        <div class="w-[66%] rounded-full bg-cobalt-500"></div>
                    </div>
                    <p class="mt-2 text-[11px] text-slate-400">211 (66%) Lunas</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4 transition-colors duration-300 hover:bg-cobalt-50">
                    <p class="text-[15px] text-slate-500">Iuran Bulan Ini</p>
                    <p class="text-[11px] text-slate-400">Pembayaran iuran Juni</p>
                    <p class="mt-1 text-[13px] text-slate-400">Minggu, 24 Mei 2026, 10.00 WIB</p>
                    <p class="text-[11px] font-medium text-slate-600">Kerja Bakti Lingkungan</p>
                </div>
            </div>
            <div class="mt-4 rounded-2xl bg-slate-50 p-4 transition-colors duration-300 hover:bg-cobalt-50">
                <div class="flex items-center justify-between">
                    <p class="text-[15px] text-slate-500">Pengumuman Terbaru</p>
                    <span class="rounded-full bg-red-50 px-2.5 py-0.5 text-[11px] font-medium text-red-500">Belum</span>
                </div>
                <p class="mt-1 text-[11px] text-slate-400">Lin Ourike</p>
                <p class="text-[11px] text-slate-400">Terkait <span class="text-slate-500">9 Nov</span></p>
            </div>
        </div>
    </div>
</section>
