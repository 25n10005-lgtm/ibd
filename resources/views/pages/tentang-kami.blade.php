{{-- ===== Tentang Kami (rekreasi piksel dari frame Figma) ===== --}}
@extends('layouts.landing')

@section('title', 'Tentang Kami — Smart RT')

@section('content')
@include('landing.partials.navbar')

{{-- Hero --}}
<section class="relative overflow-hidden bg-white">
    <div class="relative mx-auto grid max-w-[1240px] items-center gap-10 px-6 pt-12 pb-16 lg:grid-cols-2 lg:px-8 lg:pt-16 lg:pb-20">
        <div>
            <p data-reveal class="flex items-center gap-3 text-[13px] font-bold tracking-[0.14em] text-[#4A7BD4]">
                TENTANG KAMI
                <span class="inline-block h-[2px] w-8 rounded-full bg-[#4A7BD4]/60"></span>
            </p>
            <h1 data-reveal data-delay="60" class="mt-4 text-[40px] font-extrabold leading-[1.12] tracking-tight sm:text-[46px]">
                <span class="block text-[#1F2A44]">Smart RT untuk</span>
                <span class="block text-[#2F6BDE]">Warga yang Lebih Terhubung</span>
            </h1>
            <p data-reveal data-delay="120" class="mt-5 max-w-[540px] text-[15px] leading-[1.7] text-[#8A94A8]">
                Smart RT adalah platform digital resmi yang dirancang untuk
                mempermudah pengelolaan administrasi, memperkuat komunikasi
                warga, dan mendorong partisipasi aktif dalam membangun lingkungan
                RT yang lebih tertib, transparan, dan harmonis.
            </p>
            <div data-reveal data-delay="180" class="mt-8 flex flex-wrap items-center gap-6">
                <a href="#nilai" class="inline-flex items-center gap-3 rounded-full bg-[#1E5BD6] px-7 py-3.5 text-[14px] font-semibold text-white shadow-lg shadow-blue-600/25 transition-all duration-300 hover:-translate-y-0.5 hover:bg-cobalt-600">
                    Pelajari Lebih Lanjut
                    <x-hi name="arrow-right" width="16" height="14" />
                </a>
                <a href="#video" class="group inline-flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-full border-2 border-[#1E5BD6] text-[#1E5BD6] transition-transform duration-300 group-hover:scale-110">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" class="translate-x-[1px]"><path d="M8 5.5v13l11-6.5z"/></svg>
                    </span>
                    <span class="leading-snug">
                        <span class="block text-[13.5px] font-bold text-[#3A4560]">Lihat Video Singkat</span>
                        <span class="block text-[12px] text-[#9AA3B5]">Tentang Smart RT</span>
                    </span>
                </a>
            </div>
        </div>

        {{-- Visual kanan --}}
        <div data-reveal="right" class="relative mx-auto h-[560px] w-full max-w-[560px] sm:h-[600px]">
            {{-- Blob biru muda di belakang --}}
            <div class="absolute top-2 right-0 h-[480px] w-[340px] rotate-6 rounded-[3rem] bg-[#DCE9FB]"></div>
            {{-- Kartu foto miring --}}
            <div class="absolute top-16 left-0 w-[240px] -rotate-6 overflow-hidden rounded-[1.8rem] shadow-2xl sm:w-[280px]">
                <img src="{{ \App\Data\SiteContent::image('hero.png') }}" alt="Suasana lingkungan" class="h-[380px] w-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-[#0A1F44]/80 via-transparent to-transparent"></div>
                <p class="absolute bottom-16 left-5 text-[13px] font-medium text-white/80">Dari</p>
                <p class="absolute bottom-8 left-5 text-[15px] font-semibold text-white">Warga Untuk Warga</p>
            </div>
            {{-- HP --}}
            <div class="absolute top-0 left-[200px] w-[240px] rotate-[5deg] rounded-[2.4rem] bg-[#101828] p-2 shadow-[0_35px_70px_-20px_rgba(15,23,42,0.45)] sm:left-[230px] sm:w-[260px]">
                <div class="overflow-hidden rounded-[1.9rem] bg-white">
                    <div class="flex items-center justify-between px-5 pt-3 text-[10px] font-semibold text-slate-800">
                        <span>9:41</span>
                        <span class="h-[18px] w-[70px] rounded-full bg-[#101828]"></span>
                        <span class="flex items-center gap-1 text-[8px]">📶 📡 🔋</span>
                    </div>
                    <div class="flex items-start justify-between px-4 pt-2">
                        <div>
                            <p class="text-[10px] text-slate-400">Selamat Pagi,</p>
                            <p class="text-[15px] font-extrabold text-slate-900">Warga RT 03</p>
                            <p class="text-[9px] leading-snug text-slate-400">Bersama membangun<br>lingkungan yang lebih baik</p>
                        </div>
                        <span class="relative flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                            <x-hi name="bell" width="14" height="14" />
                            <span class="absolute top-1.5 right-2 h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-2 px-4 pt-3">
                        @foreach ([['Pengajuan Surat', 'M4 5h16v14H4z M8 9h8M8 13h5'], ['Kas & Iuran', 'M4 8h16v11H4zM4 8l8 6 8-6'], ['Agenda Kegiatan', 'M4 6h16v14H4zM8 3v4M16 3v4'], ['Laporan Warga', 'M12 3v10m0 0 4-4m-4 4-4-4M4 17v3h16v-3'], ['Informasi RT', 'M12 8v5m0 3v.5M12 3a9 9 0 100 18 9 9 0 000-18'], ['Kontak Pengurus', 'M4 5h16v11H4zM8 21h8']] as [$label, $path])
                            <div class="flex flex-col items-center gap-1 rounded-xl bg-[#F4F7FD] px-1 py-2.5">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2F6BDE" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $path }}"/></svg>
                                <span class="text-center text-[8px] leading-tight font-semibold text-slate-600">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="px-4 pt-3 pb-4">
                        <div class="flex items-center justify-between">
                            <p class="text-[10px] font-bold text-slate-700">Pengumuman Terbaru</p>
                            <span class="text-[9px] font-semibold text-[#7EA4EC]">Lihat Semua</span>
                        </div>
                        <div class="mt-2 flex items-center gap-2.5 rounded-xl bg-[#F4F7FD] p-2.5">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#1E5BD6] text-white">
                                <x-hi name="cal" width="14" height="14" />
                            </span>
                            <div class="min-w-0">
                                <p class="truncate text-[9.5px] font-bold text-slate-700">Kerja Bakti Lingkungan</p>
                                <p class="text-[8.5px] text-slate-400">Sabtu, 21 September 2024</p>
                            </div>
                            <x-hi name="chev-right" class="ml-auto h-3 w-3 shrink-0 text-slate-400" />
                        </div>
                    </div>
                </div>
            </div>
            {{-- Pil melayang --}}
            <div class="absolute top-[130px] right-0 flex items-center gap-2.5 rounded-2xl bg-[#E9F9EF] py-2.5 pr-5 pl-2.5 shadow-xl sm:right-2">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#22C55E] text-white">
                    <x-hi name="check" width="16" height="16" />
                </span>
                <span class="leading-tight text-[12px] font-bold text-slate-700">Layanan<br><span class="font-medium text-slate-500">lebih mudah</span></span>
            </div>
            <div class="absolute top-[250px] right-0 flex items-center gap-2.5 rounded-2xl bg-[#EAF3FE] py-2.5 pr-5 pl-2.5 shadow-xl sm:right-2">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#2F6BDE] text-white">
                    <x-hi name="users" width="16" height="16" />
                </span>
                <span class="leading-tight text-[12px] font-bold text-slate-700">Komunikasi<br><span class="font-medium text-slate-500">lebih dekat</span></span>
            </div>
            <div class="absolute top-[370px] right-0 flex items-center gap-2.5 rounded-2xl bg-[#FDEEE4] py-2.5 pr-5 pl-2.5 shadow-xl sm:right-2">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#F59E0B] text-white">
                    <x-hi name="chart" width="16" height="16" />
                </span>
                <span class="leading-tight text-[12px] font-bold text-slate-700">Lingkungan<br><span class="font-medium text-slate-500">lebih maju</span></span>
            </div>
        </div>
    </div>
</section>

{{-- Panel bawah: quote + nilai + misi --}}
<section id="nilai" class="relative overflow-hidden bg-[#EAF2FD]">
    <div class="mx-auto grid max-w-[1240px] gap-10 px-6 py-16 lg:grid-cols-12 lg:px-8 lg:py-20">
        <div class="relative lg:col-span-3">
            <p data-reveal class="text-[22px] font-extrabold leading-[1.35] text-[#22314E]">&ldquo;Teknologi yang<br>Mendekatkan Warga,<br>Memajukan Lingkungan.&rdquo;</p>
            <span class="mt-5 block h-[3px] w-10 rounded-full bg-[#2F6BDE]/70"></span>
            <p data-reveal data-delay="80" class="mt-5 max-w-[280px] text-[13.5px] leading-[1.7] text-[#7E8AA0]">Kami percaya bahwa lingkungan yang baik berawal dari komunikasi yang terbuka, layanan yang mudah diakses, dan partisipasi warga yang aktif.</p>
            {{-- Ilustrasi rumah garis --}}
            <svg class="mt-8 w-full max-w-[280px] text-[#B9CDF0]" viewBox="0 0 280 90" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M20 78V48l26-20 26 20v30"/><path d="M32 78v-14h18v14"/><path d="M72 78V58l18-14 18 14v20"/><circle cx="150" cy="70" r="3"/><path d="M170 78V52l22-16 22 16v26"/><path d="M180 78v-12h14v12"/><path d="M228 78V60l14-10 14 10v18"/><path d="M10 78h260"/>
                <circle cx="248" cy="30" r="8"/><path d="M248 44v10M242 54h12"/>
            </svg>
        </div>

        <div class="lg:col-span-5">
            <p data-reveal class="flex items-center gap-3 text-[17px] font-extrabold text-[#22314E]">
                Nilai yang Kami Pegang
                <span class="inline-block h-[2px] w-8 rounded-full bg-[#2F6BDE]/60"></span>
            </p>
            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                @foreach ([
                    ['t' => 'Transparan', 'd' => 'Seluruh informasi dan layanan disajikan secara terbuka dan mudah diakses.', 'icon' => 'M12 2 4 6v6c0 5 3.4 8.4 8 10 4.6-1.6 8-5 8-10V6z|m9 12 2 2 4-4'],
                    ['t' => 'Partisipatif', 'd' => 'Mendorong keterlibatan aktif seluruh warga dalam setiap kegiatan RT.', 'icon' => 'M16 11a4 4 0 10-8 0 4 4 0 008 0z|M2 21v-1c0-3 4-5 6-5h4c2 0 6 2 6 5v1'],
                    ['t' => 'Efisien', 'd' => 'Proses administrasi lebih cepat, mudah, dan tanpa ribet', 'icon' => 'M12 8v5l3 2|M12 21a9 9 0 100-18 9 9 0 000 18z'],
                    ['t' => 'Berkelanjutan', 'd' => 'Membangun lingkungan yang nyaman dan harmonis untuk masa depan.', 'icon' => 'M5 21c0-9 5-15 14-16-1 9-6 14-14 16z|M5 21c3-5 7-9 11-11'],
                ] as $i => $nilai)
                    <div data-reveal data-delay="{{ $i * 70 }}" class="flex gap-4 rounded-2xl bg-white p-5 shadow-[0_10px_30px_-12px_rgba(47,107,222,0.18)] transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#EAF3FE] text-[#2F6BDE]">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                @foreach (explode('|', $nilai['icon']) as $pd)
                                    <path d="{{ $pd }}"/>
                                @endforeach
                            </svg>
                        </span>
                        <span>
                            <span class="block text-[14px] font-bold text-[#3A4560]">{{ $nilai['t'] }}</span>
                            <span class="mt-1 block text-[12px] leading-relaxed text-[#9AA3B5]">{{ $nilai['d'] }}</span>
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <div data-reveal="right" class="lg:col-span-4">
            <div class="relative h-full overflow-hidden rounded-[1.8rem] bg-[#0A2547] p-8 text-white shadow-2xl">
                <div class="pointer-events-none absolute -top-24 -right-24 h-64 w-64 rounded-full border-[28px] border-white/10"></div>
                <div class="pointer-events-none absolute -right-10 -bottom-28 h-72 w-72 rounded-full border-[34px] border-white/5"></div>
                <p class="relative flex items-center gap-3 text-[13px] font-bold text-white/70">
                    Misi Kami
                    <span class="inline-block h-[2px] w-8 rounded-full bg-white/40"></span>
                </p>
                <p class="relative mt-5 text-[24px] font-extrabold leading-[1.3]">Mewujudkan<br>RT yang Cerdas,<br>Mandiri, dan Harmonis</p>
                <span class="relative mt-6 block h-[2px] w-full rounded-full bg-white/15"></span>
                <p class="relative mt-6 text-[13.5px] leading-[1.75] text-white/65">Melalui pemanfaatan teknologi digital, Smart RT hadir untuk meningkatkan kualitas layanan, mempererat hubungan antarwarga, dan menciptakan lingkungan yang lebih tertib, aman, dan sejahtera</p>
            </div>
        </div>
    </div>
</section>

@include('landing.partials.footer')
@endsection
