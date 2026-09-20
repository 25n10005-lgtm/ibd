{{-- ===== Pengumuman ===== --}}
@extends('layouts.landing')

@section('title', 'Pengumuman — Smart RT')

@section('content')
@include('landing.partials.navbar')

@php
    $announcements = \App\Data\SiteContent::announcements();
    $catStyle = ['Pengumuman' => 'bg-cobalt-500', 'Informasi' => 'bg-forest-500', 'Peringatan' => 'bg-amber-500'];
@endphp

<section class="bg-white">
    <div class="mx-auto max-w-[1240px] px-6 pt-14 pb-20 lg:px-8 lg:pt-20 lg:pb-28">
        <div class="relative z-20 mb-10 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div class="shrink-0">
                <p data-reveal class="text-[13px] font-bold tracking-[0.18em] text-cobalt-600">INFORMASI RESMI</p>
                <h1 data-reveal data-delay="80" class="mt-2 text-3xl font-extrabold tracking-tight whitespace-nowrap text-slate-900 sm:text-4xl">Daftar Pengumuman</h1>
            </div>
            <div data-reveal data-delay="140" class="flex w-full items-center gap-2 sm:w-auto">
                <div class="relative flex-1 sm:w-40 sm:flex-none">
                    <input id="ann-search" type="text" placeholder="Cari acara, lokasi..." class="peer w-full rounded-xl border border-slate-200 bg-slate-50/90 py-2 pr-3 pl-9 text-[13px] text-slate-800 placeholder:text-slate-400/80 transition-all focus:border-cobalt-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-cobalt-500/10">
                    <x-hi name="search" class="absolute top-1/2 left-3 h-[18px] w-[18px] -translate-y-1/2 text-slate-400 transition-colors peer-focus:text-cobalt-500" />
                </div>
                <div class="relative">
                    <button type="button" id="ann-filter-btn" aria-label="Filter kategori" aria-expanded="false" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-cobalt-500 hover:text-cobalt-600">
                        <x-hi name="funnel" width="20" height="20" />
                    </button>
                    <span id="ann-filter-dot" class="absolute -top-1 -right-1 hidden h-3 w-3 rounded-full bg-cobalt-500 ring-2 ring-white"></span>
                    <div id="ann-filter-menu" class="absolute right-0 z-50 mt-2 hidden w-44 overflow-hidden rounded-xl border border-slate-100 bg-white shadow-xl">
                        <p class="px-4 pt-3 pb-1 text-[11px] font-bold tracking-wider text-slate-400 uppercase">Kategori</p>
                        <div class="p-1.5">
                            @foreach (['Semua', 'Pengumuman', 'Informasi', 'Peringatan'] as $cat)
                                <button type="button" data-filter-cat="{{ $cat }}" class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-[13px] font-medium text-slate-600 transition hover:bg-cobalt-50 hover:text-cobalt-600">{{ $cat }}<x-hi name="check" class="filter-check h-4 w-4 text-cobalt-600 {{ $cat === 'Semua' ? '' : 'hidden' }}" /></button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <button type="button" id="ann-sort-btn" aria-label="Urutkan" aria-expanded="false" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-cobalt-500 hover:text-cobalt-600">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5h10M11 9h7M11 13h4M3 17l3 3 3-3M6 18V4"/></svg>
                    </button>
                    <div id="ann-sort-menu" class="absolute right-0 z-50 mt-2 hidden w-52 overflow-hidden rounded-xl border border-slate-100 bg-white shadow-xl">
                        <p class="px-4 pt-3 pb-1 text-[11px] font-bold tracking-wider text-slate-400 uppercase">Urutkan</p>
                        <div class="p-1.5">
                            @foreach ([['new', 'Terbaru'], ['old', 'Terlama'], ['az', 'Judul A – Z']] as [$val, $label])
                                <button type="button" data-sort-order="{{ $val }}" class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-[13px] font-medium text-slate-600 transition hover:bg-cobalt-50 hover:text-cobalt-600">{{ $label }}<x-hi name="check" class="sort-check h-4 w-4 text-cobalt-600 {{ $val === 'new' ? '' : 'hidden' }}" /></button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="ann-grid" data-reveal class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3"></div>
        <p id="ann-empty" class="hidden py-12 text-center text-[14px] text-slate-400">Tidak ada pengumuman yang cocok dengan pencarian.</p>

        <div id="ann-pagination" data-reveal class="mt-10 flex items-center justify-center gap-2"></div>
    </div>
</section>

@include('landing.partials.footer')

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const items = @json($announcements);
        const assetBase = "{{ asset('images/landing') }}";
        const imgMap = @json(\App\Data\SiteContent::imageMap());
        const imgUrl = (n) => imgMap[n] || (assetBase + '/' + n);
        const detailBase = "{{ url('/pengumuman') }}";
        const catStyle = @json($catStyle);

        const grid = document.getElementById('ann-grid');
        const emptyMsg = document.getElementById('ann-empty');
        const pager = document.getElementById('ann-pagination');
        const searchInput = document.getElementById('ann-search');
        const perPage = 6;
        let query = '';
        let catFilter = 'Semua';
        let sortOrder = 'new';
        let page = 1;
        let debounceTimer = null;
        let gridRevealed = false;

        const filtered = () => {
            const q = query.toLowerCase().trim();
            let list = items;
            if (catFilter !== 'Semua') list = list.filter((a) => a.cat === catFilter);
            if (q) list = list.filter((a) => (a.title + ' ' + a.excerpt).toLowerCase().includes(q));
            list = [...list];
            if (sortOrder === 'old') list.sort((a, b) => a.ts - b.ts);
            else if (sortOrder === 'az') list.sort((a, b) => a.title.localeCompare(b.title));
            else list.sort((a, b) => b.ts - a.ts);
            return list;
        };

        const cardHtml = (a) => {
            const badge = catStyle[a.cat] || 'bg-slate-400';
            return '<article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">' +
                '<a href="' + detailBase + '/' + a.id + '" class="relative block aspect-[16/10] max-h-44 w-full overflow-hidden bg-slate-100">' +
                    '<img src="' + imgUrl(a.img) + '" alt="' + a.title + '" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">' +
                    '<span class="absolute top-3 left-3 rounded-full ' + badge + ' px-3 py-1 text-[11px] font-bold text-white shadow">' + a.cat + '</span>' +
                '</a>' +
                '<div class="flex flex-1 flex-col p-5">' +
                    '<p class="text-[12px] font-medium text-slate-400">' + a.date + '</p>' +
                    '<h3 class="mt-1.5 line-clamp-2 min-h-[42px] text-[15px] font-bold leading-snug text-slate-900 transition-colors group-hover:text-cobalt-600"><a href="' + detailBase + '/' + a.id + '">' + a.title + '</a></h3>' +
                    '<p class="mt-2 mb-4 line-clamp-2 min-h-[40px] text-[13px] leading-relaxed text-slate-500">' + a.excerpt + '</p>' +
                    '<a href="' + detailBase + '/' + a.id + '" class="mt-auto inline-flex items-center justify-center gap-1.5 rounded-xl bg-cobalt-500 px-4 py-2.5 text-[13px] font-bold text-white transition hover:bg-cobalt-600 active:scale-[0.98]">Selengkapnya <x-hi name="chev-right" class="h-4 w-4" /></a>' +
                '</div></article>';
        };

        const playAnimation = () => {
            grid.querySelectorAll('article').forEach((card, i) => {
                card.animate(
                    [{ opacity: 0, transform: 'translate(36px, 36px)' }, { opacity: 1, transform: 'translate(0, 0)' }],
                    { duration: 550, delay: i * 90, easing: 'cubic-bezier(0.22, 1, 0.36, 1)', fill: 'backwards' }
                );
            });
        };

        const render = () => {
            const list = filtered();
            const totalPages = Math.max(1, Math.ceil(list.length / perPage));
            page = Math.min(Math.max(1, page), totalPages);
            grid.innerHTML = list.slice((page - 1) * perPage, page * perPage).map(cardHtml).join('');
            emptyMsg.classList.toggle('hidden', list.length > 0);
            pager.innerHTML = '';
            if (gridRevealed) playAnimation();
            if (totalPages <= 1) return;
            const btn = (label, target, disabled, active) => {
                const b = document.createElement('button');
                b.type = 'button';
                b.textContent = label;
                b.disabled = !!disabled;
                b.className = 'flex h-10 min-w-10 items-center justify-center rounded-xl px-3 text-[13px] font-bold transition ' +
                    (active ? 'bg-cobalt-500 text-white shadow-lg shadow-cobalt-500/25'
                        : 'bg-white text-slate-500 ring-1 ring-slate-200 hover:border-cobalt-500 hover:text-cobalt-600 disabled:opacity-40');
                if (!disabled) b.addEventListener('click', () => { page = target; render(); window.scrollTo({ top: grid.offsetTop - 120, behavior: 'smooth' }); });
                pager.appendChild(b);
            };
            btn('‹', page - 1, page === 1, false);
            for (let p = 1; p <= totalPages; p++) btn(String(p), p, false, p === page);
            btn('›', page + 1, page === totalPages, false);
        };

        const gridObserver = new IntersectionObserver((entries) => {
            entries.forEach((en) => {
                if (en.isIntersecting) {
                    gridRevealed = true;
                    playAnimation();
                    gridObserver.disconnect();
                }
            });
        }, { threshold: 0.1 });
        gridObserver.observe(grid);

        searchInput.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                query = e.target.value;
                page = 1;
                render();
            }, 300);
        });

        const filterBtn = document.getElementById('ann-filter-btn');
        const filterMenu = document.getElementById('ann-filter-menu');
        const filterDot = document.getElementById('ann-filter-dot');
        const sortBtn = document.getElementById('ann-sort-btn');
        const sortMenu = document.getElementById('ann-sort-menu');
        const closeMenus = () => {
            filterMenu.classList.add('hidden');
            sortMenu.classList.add('hidden');
            filterBtn.setAttribute('aria-expanded', 'false');
            sortBtn.setAttribute('aria-expanded', 'false');
        };
        filterBtn.addEventListener('click', (e) => { e.stopPropagation(); const w = filterMenu.classList.contains('hidden'); closeMenus(); if (w) { filterMenu.classList.remove('hidden'); filterBtn.setAttribute('aria-expanded', 'true'); } });
        sortBtn.addEventListener('click', (e) => { e.stopPropagation(); const w = sortMenu.classList.contains('hidden'); closeMenus(); if (w) { sortMenu.classList.remove('hidden'); sortBtn.setAttribute('aria-expanded', 'true'); } });
        document.addEventListener('click', (e) => { if (!filterMenu.contains(e.target) && !sortMenu.contains(e.target)) closeMenus(); });
        document.querySelectorAll('[data-filter-cat]').forEach((b) => {
            b.addEventListener('click', () => {
                catFilter = b.dataset.filterCat;
                document.querySelectorAll('[data-filter-cat]').forEach((x) => x.querySelector('.filter-check').classList.toggle('hidden', x !== b));
                filterDot.classList.toggle('hidden', catFilter === 'Semua');
                page = 1;
                render();
                closeMenus();
            });
        });
        document.querySelectorAll('[data-sort-order]').forEach((b) => {
            b.addEventListener('click', () => {
                sortOrder = b.dataset.sortOrder;
                document.querySelectorAll('[data-sort-order]').forEach((x) => x.querySelector('.sort-check').classList.toggle('hidden', x !== b));
                page = 1;
                render();
                closeMenus();
            });
        });

        render();
    });
</script>
@endsection
