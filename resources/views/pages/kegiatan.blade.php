{{-- ===== Kegiatan ===== --}}
@extends('layouts.landing')

@section('title', 'Kegiatan — Smart RT')

@section('content')
@include('landing.partials.navbar')

@php
    $events = \App\Data\SiteContent::events();
    $statusStyle = ['Berlangsung' => 'bg-rose-600', 'Segera' => 'bg-cobalt-500', 'Selesai' => 'bg-slate-400'];
    $eventDays = array_column($events, 'day');
    $firstWday = (int) date('w', mktime(0, 0, 0, 9, 1, 2026)); // 2 = Selasa
@endphp

{{-- Hero: Kalender Acara (full navy, kartu putih di atasnya) --}}
<section class="relative overflow-hidden bg-[#0A2A5C]">
    {{-- Motif kawung + cahaya lembut --}}
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute inset-0 opacity-[0.5]" style="background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='96' height='96' viewBox='0 0 96 96'%3E%3Cg fill='none' stroke='%23ffffff' stroke-opacity='0.14' stroke-width='1'%3E%3Cpath d='M41 48h14M48 41v14M43 43l10 10M53 43l-10 10' stroke-linecap='round'/%3E%3Ccircle cx='48' cy='48' r='1.6' fill='%23ffffff' fill-opacity='0.14' stroke='none'/%3E%3Cpath d='M-3.5 0h7M0 -3.5v7M-2.5 -2.5l5 5M2.5 -2.5l-5 5M92.5 96h7M96 92.5v7M93.5 93.5l5 5M98.5 93.5l-5 5' stroke-linecap='round'/%3E%3C/g%3E%3C/svg%3E&quot;);background-size:96px 96px;"></div>
        <div class="absolute -top-24 right-16 h-80 w-80 rounded-full bg-cobalt-500/25 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/4 h-64 w-64 rounded-full bg-[#38BDF8]/10 blur-3xl"></div>
    </div>
    <div class="relative mx-auto max-w-[1240px] px-6 pt-12 pb-16 lg:px-8 lg:pt-16 lg:pb-20">
        <h1 data-reveal class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Kalender Acara</h1>
        <p data-reveal data-delay="80" class="mt-2 max-w-[620px] text-[14px] leading-relaxed text-white/70">Pilih tanggal pada kalender untuk melihat detail kegiatan warga RT 03 / RW 02.</p>

        <div class="mt-8 grid gap-6 lg:grid-cols-2">
            {{-- Kalender --}}
            <div data-reveal="left" class="rounded-md bg-white p-6 shadow-2xl sm:p-8">
                <div class="flex items-center justify-between">
                    <span class="text-[13px] font-bold text-slate-400">&lt;</span>
                    <p class="text-[15px] font-bold text-slate-900">September 2026</p>
                    <span class="text-[13px] font-bold text-slate-400">&gt;</span>
                </div>
                <div class="mt-5 grid grid-cols-7 text-center text-[12px] font-bold text-slate-900">
                    @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $d)
                        <span class="py-1">{{ $d }}</span>
                    @endforeach
                </div>
                <div class="grid grid-cols-7 text-center text-[13px] text-slate-700">
                    @for ($b = 0; $b < $firstWday; $b++)
                        <span></span>
                    @endfor
                    @for ($day = 1; $day <= 30; $day++)
                        @if (in_array($day, $eventDays))
                            <button type="button" data-cal-day="{{ $day }}" class="mx-auto my-0.5 flex h-7 w-7 items-center justify-center rounded-full bg-amber-400 text-[13px] font-bold text-slate-900 transition hover:bg-amber-500">{{ $day }}</button>
                        @else
                            <span class="py-1">{{ $day }}</span>
                        @endif
                    @endfor
                </div>
                <div id="cal-mini-list" class="mt-6 space-y-3 border-t border-slate-100 pt-5">
                    {{-- diisi via JS: 2 agenda terdekat --}}
                </div>
            </div>

            {{-- Detail acara terpilih (featured) --}}
            <div data-reveal="right" class="overflow-hidden rounded-md bg-white shadow-[0_25px_60px_-15px_rgba(0,0,0,0.5)] ring-1 ring-white/10">
                <div class="h-56 overflow-hidden sm:h-64">
                    <img id="cal-detail-img" src="{{ \App\Data\SiteContent::image('untuk-admin.png') }}" alt="Detail acara" class="h-full w-full object-cover">
                </div>
                <div class="p-6 sm:p-7">
                    <span id="cal-detail-status" class="inline-block rounded bg-rose-600 px-3 py-1 text-[11px] font-bold text-white">Berlangsung</span>
                    <h2 id="cal-detail-title" class="mt-3 text-xl font-extrabold text-slate-900">Musyawarah Warga Bulanan</h2>
                    <p id="cal-detail-meta" class="mt-1 text-[13px] font-medium text-slate-400">Balai Warga RT 03, 16 Sep 2026</p>
                    <p id="cal-detail-desc" class="mt-3 text-[13.5px] leading-relaxed text-slate-500"></p>
                    <button type="button" id="cal-detail-more" class="mt-5 inline-flex items-center justify-center gap-1.5 rounded bg-cobalt-600 px-6 py-2.5 text-[13px] font-bold text-white transition hover:bg-cobalt-700 active:scale-[0.98]">Selengkapnya <x-hi name="chev-right" class="h-4 w-4" /></button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Daftar event --}}
<section class="bg-white">
    <div class="mx-auto max-w-[1240px] px-6 pt-14 pb-20 lg:px-8 lg:pt-20 lg:pb-28">
        <div class="relative z-20 mb-10 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div class="shrink-0">
                <p data-reveal class="text-[13px] font-bold tracking-[0.18em] text-cobalt-600">AGENDA & KEGIATAN</p>
                <h2 data-reveal data-delay="80" class="mt-2 text-3xl font-extrabold tracking-tight whitespace-nowrap text-slate-900 sm:text-4xl">Daftar Acara</h2>
            </div>
            <div data-reveal data-delay="140" class="flex w-full items-center gap-2 sm:w-auto">
                <div class="relative flex-1 sm:w-40 sm:flex-none">
                    <input id="event-search" type="text" placeholder="Cari acara, lokasi..." class="peer w-full rounded-xl border border-slate-200 bg-slate-50/90 py-2 pr-3 pl-9 text-[13px] text-slate-800 placeholder:text-slate-400/80 transition-all focus:border-cobalt-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-cobalt-500/10">
                    <x-hi name="search" class="absolute top-1/2 left-3 h-[18px] w-[18px] -translate-y-1/2 text-slate-400 transition-colors peer-focus:text-cobalt-500" />
                </div>
                {{-- Filter status --}}
                <div class="relative">
                    <button type="button" id="event-filter-btn" aria-label="Filter status" aria-expanded="false" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-cobalt-500 hover:text-cobalt-600">
                        <x-hi name="funnel" width="20" height="20" />
                    </button>
                    <span id="event-filter-dot" class="absolute -top-1 -right-1 hidden h-3 w-3 rounded-full bg-cobalt-500 ring-2 ring-white"></span>
                    <div id="event-filter-menu" class="absolute right-0 z-50 mt-2 hidden w-44 overflow-hidden rounded-xl border border-slate-100 bg-white shadow-xl">
                        <p class="px-4 pt-3 pb-1 text-[11px] font-bold tracking-wider text-slate-400 uppercase">Status</p>
                        <div class="p-1.5">
                            @foreach (['Semua', 'Berlangsung', 'Segera', 'Selesai'] as $st)
                                <button type="button" data-filter-status="{{ $st }}" class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-[13px] font-medium text-slate-600 transition hover:bg-cobalt-50 hover:text-cobalt-600">{{ $st }}<x-hi name="check" class="filter-check h-4 w-4 text-cobalt-600 {{ $st === 'Semua' ? '' : 'hidden' }}" /></button>
                            @endforeach
                        </div>
                    </div>
                </div>
                {{-- Sort --}}
                <div class="relative">
                    <button type="button" id="event-sort-btn" aria-label="Urutkan" aria-expanded="false" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-cobalt-500 hover:text-cobalt-600">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5h10M11 9h7M11 13h4M3 17l3 3 3-3M6 18V4"/></svg>
                    </button>
                    <div id="event-sort-menu" class="absolute right-0 z-50 mt-2 hidden w-52 overflow-hidden rounded-xl border border-slate-100 bg-white shadow-xl">
                        <p class="px-4 pt-3 pb-1 text-[11px] font-bold tracking-wider text-slate-400 uppercase">Urutkan</p>
                        <div class="p-1.5">
                            @foreach ([['asc', 'Tanggal terdekat'], ['desc', 'Tanggal terjauh'], ['az', 'Nama A – Z']] as [$val, $label])
                                <button type="button" data-sort-order="{{ $val }}" class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-[13px] font-medium text-slate-600 transition hover:bg-cobalt-50 hover:text-cobalt-600">{{ $label }}<x-hi name="check" class="sort-check h-4 w-4 text-cobalt-600 {{ $val === 'asc' ? '' : 'hidden' }}" /></button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="event-grid" data-reveal class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3"></div>
        <p id="event-empty" class="hidden py-12 text-center text-[14px] text-slate-400">Tidak ada kegiatan yang cocok dengan pencarian.</p>

        <div id="event-pagination" data-reveal class="mt-10 flex items-center justify-center gap-2"></div>
    </div>
</section>

@include('landing.partials.footer')

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const events = @json($events);
        const assetBase = "{{ asset('images/landing') }}";
        const imgMap = @json(\App\Data\SiteContent::imageMap());
        const imgUrl = (n) => imgMap[n] || (assetBase + '/' + n);
        const detailBase = "{{ url('/kegiatan') }}";
        const statusStyle = @json($statusStyle);

        // ===== Kalender -> detail =====
        const dImg = document.getElementById('cal-detail-img');
        const dStatus = document.getElementById('cal-detail-status');
        const dTitle = document.getElementById('cal-detail-title');
        const dMeta = document.getElementById('cal-detail-meta');
        const dDesc = document.getElementById('cal-detail-desc');
        let currentDetailId = null;
        const showDetail = (ev) => {
            currentDetailId = ev.id;
            dImg.src = imgUrl(ev.img);
            dImg.alt = ev.title;
            dTitle.textContent = ev.title;
            dMeta.textContent = ev.loc + ', ' + ev.date;
            dDesc.textContent = ev.desc;
            dStatus.textContent = ev.status;
            dStatus.className = 'inline-block rounded-full px-3 py-1 text-[11px] font-bold text-white ' + (statusStyle[ev.status] || 'bg-slate-400');
        };
        document.querySelectorAll('[data-cal-day]').forEach((btn) => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('[data-cal-day]').forEach((b) => b.classList.remove('ring-2', 'ring-offset-2', 'ring-slate-900'));
                btn.classList.add('ring-2', 'ring-offset-2', 'ring-slate-900');
                const ev = events.find((e) => e.day === parseInt(btn.dataset.calDay, 10));
                if (ev) showDetail(ev);
            });
        });
        const first = events.find((e) => e.day === 16) || events[0];
        if (first) {
            showDetail(first);
            document.querySelector('[data-cal-day="16"]')?.classList.add('ring-2', 'ring-offset-2', 'ring-slate-900');
        }

        // ===== Mini list: 2 agenda terdekat =====
        const miniList = document.getElementById('cal-mini-list');
        events.slice(0, 2).forEach((ev) => {
            const row = document.createElement('button');
            row.type = 'button';
            row.className = 'flex w-full items-center gap-3 rounded-xl p-2 text-left transition hover:bg-slate-50';
            row.innerHTML =
                '<img src="' + imgUrl(ev.img) + '" alt="" class="h-11 w-16 shrink-0 rounded-lg object-cover">' +
                '<span class="min-w-0 flex-1 truncate text-[12.5px] font-semibold text-slate-700">' + ev.title + '</span>' +
                '<span class="shrink-0 border-l-2 border-amber-400 pl-3 text-[12.5px] font-bold text-slate-900">' + ev.date.split(' ').slice(0, 2).join(' ') + '</span>';
            row.addEventListener('click', () => showDetail(ev));
            miniList.appendChild(row);
        });

        // ===== Grid + search (debounce) + pagination =====
        const grid = document.getElementById('event-grid');
        const emptyMsg = document.getElementById('event-empty');
        const pager = document.getElementById('event-pagination');
        const searchInput = document.getElementById('event-search');
        const perPage = 6;
        let query = '';
        let page = 1;
        let debounceTimer = null;

        const filtered = () => {
            const q = query.toLowerCase().trim();
            let list = events;
            if (statusFilter !== 'Semua') list = list.filter((e) => e.status === statusFilter);
            if (q) list = list.filter((e) => (e.title + ' ' + e.loc + ' ' + e.org).toLowerCase().includes(q));
            list = [...list];
            if (sortOrder === 'desc') list.sort((a, b) => b.day - a.day);
            else if (sortOrder === 'az') list.sort((a, b) => a.title.localeCompare(b.title));
            else list.sort((a, b) => a.day - b.day);
            return list;
        };

        const cardHtml = (ev) => {
            const badge = statusStyle[ev.status] || 'bg-slate-400';
            return '<article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">' +
                '<a href="' + detailBase + '/' + ev.id + '" class="relative block aspect-[4/3] max-h-40 w-full overflow-hidden bg-white">' +
                    '<img src="' + imgUrl(ev.img) + '" alt="' + ev.title + '" loading="lazy" class="h-full w-full scale-105 object-cover transition-transform duration-500 group-hover:scale-110">' +
                    '<span class="absolute top-3 left-3 rounded-full ' + badge + ' px-3 py-1 text-[11px] font-bold text-white shadow">' + ev.status + '</span>' +
                '</a>' +
                '<div class="flex flex-1 flex-col p-5">' +
                    '<h3 class="line-clamp-2 min-h-[42px] text-[15px] font-bold leading-snug text-slate-900 transition-colors group-hover:text-cobalt-600"><a href="' + detailBase + '/' + ev.id + '">' + ev.title + '</a></h3>' +
                    '<div class="mt-3 mb-4 space-y-1.5 text-[12.5px] text-slate-400">' +
                        '<p class="flex items-center gap-1.5"><x-hi name="cal" class="h-3.5 w-3.5" />' + ev.date + ' • ' + ev.time + '</p>' +
                        '<p class="flex items-center gap-1.5"><x-hi name="pin" class="h-3.5 w-3.5" />' + ev.loc + '</p>' +
                        '<p class="flex items-center gap-1.5"><x-hi name="users" class="h-3.5 w-3.5" />' + ev.org + '</p>' +
                    '</div>' +
                    '<a href="' + detailBase + '/' + ev.id + '" class="mt-auto inline-flex items-center justify-center gap-1.5 rounded-xl bg-cobalt-500 px-4 py-2.5 text-[13px] font-bold text-white transition hover:bg-cobalt-600 active:scale-[0.98]">Selengkapnya <x-hi name="chev-right" class="h-4 w-4" /></a>' +
                '</div></article>';
        };

        const render = () => {
            const list = filtered();
            const totalPages = Math.max(1, Math.ceil(list.length / perPage));
            page = Math.min(Math.max(1, page), totalPages);
            const slice = list.slice((page - 1) * perPage, page * perPage);
            grid.innerHTML = slice.map((ev) => cardHtml(ev)).join('');
            if (gridRevealed) playCardAnimation();
            emptyMsg.classList.toggle('hidden', list.length > 0);
            pager.innerHTML = '';
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

        searchInput.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                query = e.target.value;
                page = 1;
                render();
            }, 300);
        });

        // ===== Filter status + sort (dropdown tooltip) =====
        let statusFilter = 'Semua';
        let sortOrder = 'asc';
        const filterBtn = document.getElementById('event-filter-btn');
        const filterMenu = document.getElementById('event-filter-menu');
        const filterDot = document.getElementById('event-filter-dot');
        const sortBtn = document.getElementById('event-sort-btn');
        const sortMenu = document.getElementById('event-sort-menu');
        const closeMenus = () => {
            filterMenu.classList.add('hidden');
            sortMenu.classList.add('hidden');
            filterBtn.setAttribute('aria-expanded', 'false');
            sortBtn.setAttribute('aria-expanded', 'false');
        };
        const toggleMenu = (menu, btn) => {
            const willOpen = menu.classList.contains('hidden');
            closeMenus();
            if (willOpen) {
                menu.classList.remove('hidden');
                btn.setAttribute('aria-expanded', 'true');
            }
        };
        filterBtn.addEventListener('click', (e) => { e.stopPropagation(); toggleMenu(filterMenu, filterBtn); });
        sortBtn.addEventListener('click', (e) => { e.stopPropagation(); toggleMenu(sortMenu, sortBtn); });
        document.addEventListener('click', (e) => {
            if (!filterMenu.contains(e.target) && !sortMenu.contains(e.target)) closeMenus();
        });
        document.querySelectorAll('[data-filter-status]').forEach((b) => {
            b.addEventListener('click', () => {
                statusFilter = b.dataset.filterStatus;
                document.querySelectorAll('[data-filter-status]').forEach((x) => {
                    x.querySelector('.filter-check').classList.toggle('hidden', x !== b);
                });
                filterDot.classList.toggle('hidden', statusFilter === 'Semua');
                page = 1;
                render();
                closeMenus();
            });
        });
        document.querySelectorAll('[data-sort-order]').forEach((b) => {
            b.addEventListener('click', () => {
                sortOrder = b.dataset.sortOrder;
                document.querySelectorAll('[data-sort-order]').forEach((x) => {
                    x.querySelector('.sort-check').classList.toggle('hidden', x !== b);
                });
                page = 1;
                render();
                closeMenus();
            });
        });

        // ===== Selengkapnya -> halaman detail =====
        document.getElementById('cal-detail-more').addEventListener('click', () => {
            if (currentDetailId) window.location.href = detailBase + '/' + currentDetailId;
        });

        // ===== Animasi kartu satu-persatu dari pojok kanan-bawah saat scroll =====
        let gridRevealed = false;
        const playCardAnimation = () => {
            grid.querySelectorAll('article').forEach((card, i) => {
                card.animate(
                    [
                        { opacity: 0, transform: 'translate(36px, 36px)' },
                        { opacity: 1, transform: 'translate(0, 0)' },
                    ],
                    { duration: 550, delay: i * 90, easing: 'cubic-bezier(0.22, 1, 0.36, 1)', fill: 'backwards' }
                );
            });
        };
        const gridObserver = new IntersectionObserver((entries) => {
            entries.forEach((en) => {
                if (en.isIntersecting) {
                    gridRevealed = true;
                    playCardAnimation();
                    gridObserver.disconnect();
                }
            });
        }, { threshold: 0.1 });
        gridObserver.observe(grid);

        render();
    });
</script>
@endsection
