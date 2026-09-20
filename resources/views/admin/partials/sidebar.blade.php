{{-- ===== Sidebar admin (dipakai semua halaman admin) ===== --}}
{{-- Param: $user (User login), $active (string key menu aktif) --}}
@php
    $isSurat = in_array($active ?? '', ['surat', 'surat-semua', 'surat-pengajuan', 'surat-detail'], true);
    $menus = [
        'WARGA' => [
            ['key' => 'warga', 'label' => 'Data Warga', 'href' => route('warga.index'), 'icon' => 'users'],
            ['key' => 'keluarga', 'label' => 'Data Keluarga', 'href' => route('keluarga.index'), 'icon' => 'users'],
            ['key' => 'verifikasi', 'label' => 'Verifikasi NIK', 'href' => route('verifikasi.index'), 'icon' => 'shield'],
        ],
        'LAYANAN' => [
            ['key' => 'surat', 'label' => 'Administrasi Surat', 'href' => route('surat.index'), 'icon' => 'doc'],
            ['key' => 'pengaduan', 'label' => 'Pengaduan', 'href' => route('aduan.index'), 'icon' => 'chat'],
        ],
        'KEUANGAN' => [
            ['key' => 'iuran', 'label' => 'Iuran', 'href' => route('iuran.index'), 'icon' => 'currency'],
            ['key' => 'kas', 'label' => 'Kas RT', 'href' => route('kas.index'), 'icon' => 'wallet'],
            ['key' => 'laporan-keuangan', 'label' => 'Laporan Keuangan', 'href' => route('laporan.index'), 'icon' => 'chart'],
        ],
        'KOMUNITAS' => [
            ['key' => 'pengumuman', 'label' => 'Pengumuman', 'href' => route('pengumuman.index'), 'icon' => 'mega'],
            ['key' => 'kegiatan', 'label' => 'Kegiatan', 'href' => route('agenda.index'), 'icon' => 'cal'],
            ['key' => 'galeri', 'label' => 'Galeri', 'href' => route('galeri.index'), 'icon' => 'photo'],
        ],
        'PENGATURAN' => [
            ['key' => 'pengaturan', 'label' => 'Pengaturan RT', 'href' => route('pengaturan.index'), 'icon' => 'cog'],
        ],
    ];
        $subSurat = [
            ['key' => 'surat-semua', 'label' => 'Semua Pengajuan', 'href' => route('surat.semua')],
            ['key' => 'surat-pengajuan', 'label' => 'Pengajuan Baru', 'href' => route('surat.pengajuan')],
        ];
    // Pengurus: hanya Ketua RT (Super Admin) ke atas. Sub-navigate:
    // Daftar Pengurus + Pengajuan (persetujuan kenaikan peran).
    if (($user ?? null)?->hasAtLeastRole(\App\Enums\Role::SuperAdmin)) {
        $menus['PENGURUS'] = [
            ['key' => 'pengurus', 'label' => 'Pengurus', 'href' => route('pengurus.index'), 'icon' => 'users'],
        ];
        $subPengurus = [
            ['key' => 'pengurus-daftar', 'label' => 'Daftar Pengurus', 'href' => route('pengurus.index')],
            ['key' => 'pengurus-pengajuan', 'label' => 'Pengajuan', 'href' => route('admin.approvals')],
        ];
    } else {
        $subPengurus = [];
    }
    $isPengurus = in_array($active ?? '', ['pengurus', 'pengurus-daftar', 'pengurus-pengajuan'], true);
    // Manajemen RT: hanya Developer.
    if (($user ?? null)?->hasRole(\App\Enums\Role::Developer)) {
        $menus['DEVELOPER'] = [
            ['key' => 'rt', 'label' => 'Manajemen RT', 'href' => route('dev.rt.index'), 'icon' => 'building'],
        ];
    }
@endphp
{{-- ===== Sidebar admin — Figma Dashboard node 6:3 (w-64, header 80px, nav rounded-xl, active #EFF6FF/#2863D1) ===== --}}
<style>
    #adminSidebar { transition: width .25s ease; }
    #adminSidebar[data-collapsed="true"] { width: 5rem; }
    #adminSidebar[data-collapsed="true"] .sb-text,
    #adminSidebar[data-collapsed="true"] .sb-group,
    #adminSidebar[data-collapsed="true"] .sb-sub,
    #adminSidebar[data-collapsed="true"] .sb-chev,
    #adminSidebar[data-collapsed="true"] .sb-user-meta { display: none; }
    #adminSidebar[data-collapsed="true"] .sb-link { justify-content: center; padding-left: 0; padding-right: 0; }
    #adminSidebar[data-collapsed="true"] .sb-head { padding-left: 0; padding-right: 0; justify-content: center; }
    #adminSidebar[data-collapsed="true"] .sb-user { justify-content: center; }
    #adminSidebar .sb-toggle svg { transition: transform .25s ease; }
    #adminSidebar[data-collapsed="true"] .sb-toggle svg { transform: rotate(180deg); }
</style>
<aside id="adminSidebar" class="relative hidden w-64 shrink-0 flex-col bg-white lg:flex">
    <button type="button" id="sidebarToggle" title="Ciutkan / lebarkan sidebar" class="sb-toggle absolute top-20 -right-3 z-10 flex h-6 w-6 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:text-slate-800">
        <x-hi name="chev-left" width="12" height="12" />
    </button>
    <a href="{{ url('/') }}" class="sb-head flex h-20 items-center gap-3 border-b border-slate-50 px-6">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-green-100 bg-green-50 text-green-600">
            <x-hi name="home" width="18" height="18" />
        </span>
        <span class="sb-text leading-tight">
            <span class="block text-base font-bold">SMART RT</span>
            <span class="block text-[10px] font-medium text-slate-500">{{ $user->rt?->label() ?? 'RT 04 / RW 02' }}</span>
        </span>
    </a>

    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4 text-sm font-medium">
        <a href="{{ route('dashboard') }}" title="Dashboard" class="sb-link flex items-center gap-3 rounded-xl px-4 py-2.5 transition {{ ($active ?? '') === 'dashboard' ? 'bg-[#EFF6FF] font-medium text-[#2863D1]' : 'text-slate-600 hover:bg-slate-50' }}">
            <x-hi name="squares" width="17" height="17" class="shrink-0" />
            <span class="sb-text">Dashboard</span>
        </a>

        @foreach ($menus as $group => $items)
            <p class="sb-group px-4 pt-4 pb-1 text-[12px] font-semibold tracking-wider text-slate-400 uppercase">{{ $group }}</p>
            @foreach ($items as $item)
                @php $on = ($active ?? '') === $item['key'] || ($item['key'] === 'surat' && $isSurat) || ($item['key'] === 'pengurus' && $isPengurus); @endphp
                <a href="{{ $item['href'] }}" title="{{ $item['label'] }}" class="sb-link flex items-center gap-3 rounded-xl px-4 py-2.5 transition {{ $on ? 'bg-[#EFF6FF] font-medium text-[#2863D1]' : 'text-slate-600 hover:bg-slate-50' }}">
                    <x-hi :name="$item['icon']" width="17" height="17" class="shrink-0" />
                    <span class="sb-text flex-1">{{ $item['label'] }}</span>
                    @if ($item['key'] === 'surat')
                        <x-hi name="chev-down" width="10" height="10" class="sb-chev {{ $isSurat ? 'rotate-180' : '' }}" />
                    @endif
                    @if ($item['key'] === 'pengurus')
                        <x-hi name="chev-down" width="10" height="10" class="sb-chev {{ $isPengurus ? 'rotate-180' : '' }}" />
                    @endif
                </a>
                @if ($item['key'] === 'surat' && $isSurat)
                    <div class="sb-sub mt-0.5 space-y-0.5 pr-2 pb-1 pl-12">
                        @foreach ($subSurat as $sub)
                            <a href="{{ $sub['href'] }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-[13px] transition {{ ($active ?? '') === $sub['key'] ? 'bg-[#EFF6FF] font-semibold text-[#2863D1]' : 'text-slate-500 hover:text-slate-800' }}">
                                <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ ($active ?? '') === $sub['key'] ? 'bg-[#2863D1]' : 'bg-slate-300' }}"></span>
                                {{ $sub['label'] }}
                            </a>
                        @endforeach
                    </div>
                @endif
                @if ($item['key'] === 'pengurus' && $isPengurus)
                    <div class="sb-sub mt-0.5 space-y-0.5 pr-2 pb-1 pl-12">
                        @foreach ($subPengurus as $sub)
                            <a href="{{ $sub['href'] }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-[13px] transition {{ ($active ?? '') === $sub['key'] ? 'bg-[#EFF6FF] font-semibold text-[#2863D1]' : 'text-slate-500 hover:text-slate-800' }}">
                                <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ ($active ?? '') === $sub['key'] ? 'bg-[#2863D1]' : 'bg-slate-300' }}"></span>
                                {{ $sub['label'] }}
                            </a>
                        @endforeach
                    </div>
                @endif
            @endforeach
        @endforeach
    </nav>

    <div class="relative border-t p-2">
        <button type="button" id="profileBtn" class="sb-user flex w-full items-center gap-3 rounded-xl px-2 py-2 text-left transition hover:bg-slate-50">
            <img src="{{ $user->avatar ?? asset('images/landing/logo.png') }}" alt="{{ $user->name }}" title="{{ $user->name }}" class="h-10 w-10 shrink-0 rounded-full object-cover">
            <div class="sb-user-meta min-w-0 flex-1 leading-tight">
                <p class="truncate text-sm font-bold">{{ $user->name }}</p>
                <p class="text-xs text-slate-500">{{ $user->role?->label() }} RT 04</p>
            </div>
            <span class="sb-user-meta text-slate-300">
                <x-hi name="chev-down" width="12" height="12" />
            </span>
        </button>
        <div id="profileMenu" class="absolute right-2 bottom-full left-2 mb-1 hidden overflow-hidden rounded-xl border border-slate-200 bg-white py-1 shadow-lg">
            <a href="{{ route('pengaturan.index') }}" class="flex items-center gap-2 px-4 py-2 text-[13px] font-medium text-slate-600 transition hover:bg-slate-50">
                <x-hi name="user" width="14" height="14" />
                Profil Saya
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-left text-[13px] font-medium text-rose-600 transition hover:bg-rose-50">
                    <x-hi name="logout" width="14" height="14" />
                    Logout
                </button>
            </form>
        </div>
    </div>
</aside>
<script>
    (() => {
        const sb = document.getElementById('adminSidebar');
        const btn = document.getElementById('sidebarToggle');
        if (!sb || !btn) return;
        try {
            if (localStorage.getItem('sb-collapsed') === '1') sb.dataset.collapsed = 'true';
        } catch (e) {}
        btn.addEventListener('click', () => {
            const collapsed = sb.dataset.collapsed === 'true';
            if (collapsed) {
                delete sb.dataset.collapsed;
            } else {
                sb.dataset.collapsed = 'true';
            }
            try {
                localStorage.setItem('sb-collapsed', collapsed ? '0' : '1');
            } catch (e) {}
        });
        // Dropdown profil: Profil Saya + Logout
        const profileBtn = document.getElementById('profileBtn');
        const profileMenu = document.getElementById('profileMenu');
        if (profileBtn && profileMenu) {
            profileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                profileMenu.classList.toggle('hidden');
            });
            document.addEventListener('click', (e) => {
                if (!profileMenu.classList.contains('hidden') && !e.target.closest('#profileMenu')) {
                    profileMenu.classList.add('hidden');
                }
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') profileMenu.classList.add('hidden');
            });
        }
    })();
</script>
