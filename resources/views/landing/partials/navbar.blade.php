{{-- ===== Navbar ===== --}}
<header data-navbar class="sticky top-0 z-50">
    {{-- Topbar info --}}
    <div class="bg-cobalt-700 text-white">
        <div class="mx-auto flex h-9 max-w-[1240px] items-center justify-between px-6 text-[12px] font-medium lg:px-8">
            <p class="tracking-wide">Senin–Jumat: 08:00 – 16:00 WITA</p>
            <p class="hidden items-center gap-4 sm:flex">
                <a href="tel:(0411)112" class="transition-colors hover:text-white/70">(0411) 112</a>
                <span class="h-3 w-px bg-white/30"></span>
                <a href="mailto:info@smartrt.id" class="transition-colors hover:text-white/70">info@smartrt.id</a>
            </p>
        </div>
    </div>

    {{-- Mainbar --}}
    <div class="border-b border-slate-100 bg-white/95 backdrop-blur">
        <div class="mx-auto flex h-[76px] max-w-[1240px] items-center gap-8 px-6 lg:px-8">
            <a href="#" class="group flex shrink-0 items-center gap-3">
                <img src="{{ asset('images/landing/logo.png') }}" alt="Logo Smart RT" class="h-11 w-11 object-contain transition-transform duration-300 group-hover:scale-105">
                <span class="leading-tight">
                    <span class="block text-[19px] font-extrabold tracking-tight text-slate-900">Smart RT</span>
                    <span class="block text-[11.5px] font-medium text-slate-500">Portal Resmi RT 03 / RW 02 Pamulang Barat</span>
                </span>
            </a>
            @php
                $isHome = request()->is('/');
                $isTentang = request()->routeIs('tentang-kami');
                $isKegiatan = request()->routeIs('kegiatan');
                $isPengumuman = request()->routeIs('pengumuman');
                $isKontak = request()->routeIs('kontak');
                $isPengaduan = request()->routeIs('pengaduan');
                $navActive = 'relative font-bold text-slate-900 after:absolute after:-bottom-1.5 after:left-0 after:h-0.5 after:w-full after:rounded-full after:bg-cobalt-500';
                $navIdle = 'relative text-slate-500 transition-colors hover:text-cobalt-600 after:absolute after:-bottom-1.5 after:left-0 after:h-0.5 after:w-0 after:rounded-full after:bg-cobalt-500 after:transition-all after:duration-300 hover:after:w-full';
            @endphp
            <nav class="ml-auto hidden items-center gap-7 text-[15px] font-medium lg:flex">
                <a href="{{ url('/') }}" class="{{ $isHome ? $navActive : $navIdle }}">Beranda</a>
                <a href="{{ route('tentang-kami') }}" class="{{ $isTentang ? $navActive : $navIdle }}">Tentang Kami</a>
                <a href="{{ route('kegiatan') }}" class="{{ $isKegiatan ? $navActive : $navIdle }}">Kegiatan</a>
                <a href="{{ route('pengaduan') }}" class="{{ $isPengaduan ? $navActive : $navIdle }}">Pengaduan</a>
                <a href="{{ route('pengumuman') }}" class="{{ $isPengumuman ? $navActive : $navIdle }}">Pengumuman</a>
                <a href="{{ route('kontak') }}" class="{{ $isKontak ? $navActive : $navIdle }}">Kontak</a>
            </nav>
            <div class="ml-auto flex items-center gap-3 lg:ml-0">
                @auth
                    <a href="{{ route('dashboard') }}" class="hidden rounded-full bg-cobalt-500 px-7 py-2.5 text-[15px] font-medium text-white shadow-lg shadow-cobalt-500/25 transition-all duration-300 hover:-translate-y-0.5 hover:bg-cobalt-600 hover:shadow-xl hover:shadow-cobalt-500/30 sm:inline-block">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hidden rounded-full bg-cobalt-500 px-7 py-2.5 text-[15px] font-medium text-white shadow-lg shadow-cobalt-500/25 transition-all duration-300 hover:-translate-y-0.5 hover:bg-cobalt-600 hover:shadow-xl hover:shadow-cobalt-500/30 sm:inline-block">Masuk</a>
                @endauth
                <button data-menu-btn aria-expanded="false" aria-label="Buka menu" class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 text-slate-700 lg:hidden">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
            </div>
        </div>
        <nav data-mobile-menu class="hidden border-t border-slate-100 bg-white px-6 py-4 lg:hidden">
            <div class="grid gap-1 text-[15px]">
                @foreach ([['Beranda', url('/')], ['Tentang Kami', route('tentang-kami')], ['Kegiatan', route('kegiatan')], ['Pengaduan', route('pengaduan')], ['Pengumuman', route('pengumuman')], ['Kontak', route('kontak')]] as [$label, $href])
                <a href="{{ $href }}" class="rounded-xl px-4 py-3 font-medium text-slate-600 transition-colors hover:bg-cobalt-50 hover:text-cobalt-600">{{ $label }}</a>
                @endforeach
                @auth
                    <a href="{{ route('dashboard') }}" class="mt-2 rounded-xl bg-cobalt-500 px-4 py-3 text-center font-semibold text-white">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="mt-2 rounded-xl bg-cobalt-500 px-4 py-3 text-center font-semibold text-white">Masuk</a>
                @endauth
            </div>
        </nav>
    </div>
</header>
