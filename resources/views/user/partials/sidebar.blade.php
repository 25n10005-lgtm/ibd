{{-- ===== Sidebar user/warga (area /warga) ===== --}}
{{-- Param: $user (User login), $active (string key menu aktif) --}}
@php
    $menus = [
        ['key' => 'dashboard', 'label' => 'Dashboard', 'href' => route('saya.dashboard'), 'icon' => 'squares'],
        ['key' => 'keluarga', 'label' => 'Data Keluarga', 'href' => route('saya.keluarga'), 'icon' => 'users'],
        ['key' => 'pengumuman', 'label' => 'Pengumuman', 'href' => route('saya.pengumuman'), 'icon' => 'mega'],
        ['key' => 'kegiatan', 'label' => 'Kegiatan RT', 'href' => route('saya.kegiatan'), 'icon' => 'cal'],
        ['key' => 'pengaturan', 'label' => 'Pengaturan', 'href' => route('saya.pengaturan'), 'icon' => 'cog'],
    ];
    $pengajuan = [
        ['key' => 'surat', 'label' => 'Pengajuan Surat', 'href' => route('saya.surat'), 'icon' => 'doc'],
        ['key' => 'pengaduan', 'label' => 'Pengaduan Warga', 'href' => route('saya.aduan'), 'icon' => 'chat'],
    ];
    $isPengajuan = in_array($active ?? '', ['surat', 'pengaduan'], true);
    $iuranSub = ['key' => 'iuran-riwayat', 'label' => 'Riwayat Iuran', 'href' => route('saya.iuran.riwayat')];
    $isIuran = in_array($active ?? '', ['iuran', 'iuran-riwayat'], true);
@endphp
<style>
    #userSidebar { transition: width .25s ease; }
    #userSidebar[data-collapsed="true"] { width: 5rem; }
    #userSidebar[data-collapsed="true"] .sb-text,
    #userSidebar[data-collapsed="true"] .sb-user-meta { display: none; }
    #userSidebar[data-collapsed="true"] .sb-link { justify-content: center; padding-left: 0; padding-right: 0; }
    #userSidebar[data-collapsed="true"] .sb-head { padding-left: 0; padding-right: 0; justify-content: center; }
    #userSidebar[data-collapsed="true"] .sb-user { justify-content: center; }
    #userSidebar .sb-toggle svg { transition: transform .25s ease; }
    #userSidebar[data-collapsed="true"] .sb-toggle svg { transform: rotate(180deg); }
</style>
<aside id="userSidebar" class="relative hidden w-64 shrink-0 flex-col bg-white lg:flex">
    <button type="button" id="userSidebarToggle" title="Ciutkan / lebarkan sidebar" class="sb-toggle absolute top-20 -right-3 z-10 flex h-6 w-6 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:text-slate-800">
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
        @foreach (array_slice($menus, 0, 2) as $item)
            <a href="{{ $item['href'] }}" title="{{ $item['label'] }}" class="sb-link flex items-center gap-3 rounded-xl px-4 py-2.5 transition {{ ($active ?? '') === $item['key'] ? 'bg-[#ECFDF5] font-medium text-[#047857]' : 'text-slate-600 hover:bg-slate-50' }}">
                <x-hi :name="$item['icon']" width="17" height="17" class="shrink-0" />
                <span class="sb-text">{{ $item['label'] }}</span>
            </a>
        @endforeach

        <div>
            <button type="button" id="pengajuanToggle" title="Pengajuan Saya" aria-expanded="{{ $isPengajuan ? 'true' : 'false' }}" class="sb-link flex w-full items-center gap-3 rounded-xl px-4 py-2.5 transition {{ $isPengajuan ? 'bg-[#ECFDF5] font-medium text-[#047857]' : 'text-slate-600 hover:bg-slate-50' }}">
                <x-hi name="doc" width="17" height="17" class="shrink-0" />
                <span class="sb-text flex-1 text-left">Pengajuan Saya</span>
                <x-hi name="chev-down" width="12" height="12" class="sb-text shrink-0 transition {{ $isPengajuan ? 'rotate-180' : '' }}" />
            </button>
            <div id="pengajuanSub" class="sb-text mt-0.5 space-y-0.5 pr-2 pb-1 pl-12 {{ $isPengajuan ? '' : 'hidden' }}">
                @foreach ($pengajuan as $sub)
                    <a href="{{ $sub['href'] }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-[13px] transition {{ ($active ?? '') === $sub['key'] ? 'bg-[#ECFDF5] font-semibold text-[#047857]' : 'text-slate-500 hover:text-slate-800' }}">
                        <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ ($active ?? '') === $sub['key'] ? 'bg-[#047857]' : 'bg-slate-300' }}"></span>
                        {{ $sub['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        @foreach (array_slice($menus, 2, 2) as $item)
            <a href="{{ $item['href'] }}" title="{{ $item['label'] }}" class="sb-link flex items-center gap-3 rounded-xl px-4 py-2.5 transition {{ ($active ?? '') === $item['key'] ? 'bg-[#ECFDF5] font-medium text-[#047857]' : 'text-slate-600 hover:bg-slate-50' }}">
                <x-hi :name="$item['icon']" width="17" height="17" class="shrink-0" />
                <span class="sb-text">{{ $item['label'] }}</span>
            </a>
        @endforeach

        <div>
            <div class="sb-link flex items-center gap-3 rounded-xl px-4 py-2.5 transition {{ $isIuran ? 'bg-[#ECFDF5] font-medium text-[#047857]' : 'text-slate-600 hover:bg-slate-50' }}">
                <a href="{{ route('saya.iuran') }}" title="Iuran" class="flex min-w-0 flex-1 items-center gap-3">
                    <x-hi name="currency" width="17" height="17" class="shrink-0" />
                    <span class="sb-text">Iuran</span>
                </a>
                <button type="button" id="iuranToggle" title="Tampilkan sub menu Iuran" aria-expanded="{{ $isIuran ? 'true' : 'false' }}" class="sb-text flex h-6 w-6 shrink-0 items-center justify-center rounded-lg transition hover:bg-black/5">
                    <x-hi name="chev-down" width="12" height="12" class="shrink-0 transition {{ $isIuran ? 'rotate-180' : '' }}" />
                </button>
            </div>
            <div id="iuranSub" class="sb-text mt-0.5 space-y-0.5 pr-2 pb-1 pl-12 {{ $isIuran ? '' : 'hidden' }}">
                <a href="{{ $iuranSub['href'] }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-[13px] transition {{ ($active ?? '') === $iuranSub['key'] ? 'bg-[#ECFDF5] font-semibold text-[#047857]' : 'text-slate-500 hover:text-slate-800' }}">
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ ($active ?? '') === $iuranSub['key'] ? 'bg-[#047857]' : 'bg-slate-300' }}"></span>
                    {{ $iuranSub['label'] }}
                </a>
            </div>
        </div>

        @foreach (array_slice($menus, 4) as $item)
            <a href="{{ $item['href'] }}" title="{{ $item['label'] }}" class="sb-link flex items-center gap-3 rounded-xl px-4 py-2.5 transition {{ ($active ?? '') === $item['key'] ? 'bg-[#ECFDF5] font-medium text-[#047857]' : 'text-slate-600 hover:bg-slate-50' }}">
                <x-hi :name="$item['icon']" width="17" height="17" class="shrink-0" />
                <span class="sb-text">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="relative border-t p-2">
        <button type="button" id="userProfileBtn" class="sb-user flex w-full items-center gap-3 rounded-xl px-2 py-2 text-left transition hover:bg-slate-50">
            <img src="{{ $user->avatar ?? asset('images/landing/logo.png') }}" alt="{{ $user->name }}" title="{{ $user->name }}" class="h-10 w-10 shrink-0 rounded-full object-cover">
            <div class="sb-user-meta min-w-0 flex-1 leading-tight">
                <p class="truncate text-sm font-bold">{{ $user->name }}</p>
                <p class="text-xs text-slate-500">{{ $user->role?->label() }}</p>
            </div>
            <span class="sb-user-meta text-slate-300">
                <x-hi name="chev-down" width="12" height="12" />
            </span>
        </button>
        <div id="userProfileMenu" class="absolute right-2 bottom-full left-2 mb-1 hidden overflow-hidden rounded-xl border border-slate-200 bg-white py-1 shadow-lg">
            <a href="{{ route('saya.pengaturan') }}" class="flex items-center gap-2 px-4 py-2 text-[13px] font-medium text-slate-600 transition hover:bg-slate-50">
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
        const sb = document.getElementById('userSidebar');
        const btn = document.getElementById('userSidebarToggle');
        if (sb && btn) {
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
        }
        [['pengajuanToggle', 'pengajuanSub'], ['iuranToggle', 'iuranSub']].forEach(([toggleId, subId]) => {
            const toggle = document.getElementById(toggleId);
            const sub = document.getElementById(subId);
            if (!toggle || !sub) return;
            toggle.addEventListener('click', () => {
                const hidden = sub.classList.toggle('hidden');
                toggle.setAttribute('aria-expanded', hidden ? 'false' : 'true');
                const icons = toggle.querySelectorAll('svg');
                icons[icons.length - 1]?.classList.toggle('rotate-180', !hidden);
            });
        });
        const profileBtn = document.getElementById('userProfileBtn');
        const profileMenu = document.getElementById('userProfileMenu');
        if (profileBtn && profileMenu) {
            profileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                profileMenu.classList.toggle('hidden');
            });
            document.addEventListener('click', () => profileMenu.classList.add('hidden'));
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') profileMenu.classList.add('hidden');
            });
        }
    })();
</script>
