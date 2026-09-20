{{-- ===== Detail artikel (Berita / Pengumuman / Acara) ===== --}}
@extends('layouts.landing')

@section('title', $title . ' — Smart RT')

@section('content')
@include('landing.partials.navbar')

{{-- Kepala halaman: judul + breadcrumb --}}
<section class="border-b border-slate-100 bg-slate-50/60">
    <div class="mx-auto max-w-[1240px] px-6 pt-10 pb-6 lg:px-8">
        <h1 class="max-w-[800px] text-2xl font-extrabold leading-snug tracking-tight text-slate-900 sm:text-3xl">{{ $title }}</h1>
        <nav aria-label="breadcrumb" class="mt-3">
            <ol class="flex flex-wrap items-center gap-1.5 text-[13px]">
                <li><a href="{{ url('/') }}" class="font-medium text-cobalt-600 hover:underline">Beranda</a></li>
                <li aria-hidden="true" class="text-slate-300">/</li>
                <li><a href="{{ $sectionUrl }}" class="font-medium text-cobalt-600 hover:underline">{{ $section }}</a></li>
                <li aria-hidden="true" class="text-slate-300">/</li>
                <li aria-current="page" class="max-w-[420px] truncate font-semibold text-slate-500">{{ $title }}</li>
            </ol>
        </nav>
    </div>
</section>

{{-- Isi artikel --}}
<section class="bg-white">
    <div class="mx-auto max-w-[1240px] px-6 pt-10 pb-20 lg:px-8 lg:pb-28">
        <div class="grid gap-10 lg:grid-cols-3">
            <article class="lg:col-span-2">
                <div class="overflow-hidden rounded-2xl bg-slate-100">
                    <img src="{{ \App\Data\SiteContent::image($img) }}" alt="{{ $title }}" class="max-h-[420px] w-full object-cover">
                </div>
                <div class="mt-5 flex flex-wrap items-center gap-x-4 gap-y-1.5 text-[13px] text-slate-400">
                    @if (!empty($badge))
                        <span class="rounded px-2.5 py-1 text-[11px] font-bold text-white {{ $badgeClass }}">{{ $badge }}</span>
                    @endif
                    <span>{{ $date }}</span>
                    @if (!empty($extraMeta))
                        <span>•</span>
                        <span>{{ $extraMeta }}</span>
                    @endif
                </div>
                <div class="mt-5 space-y-4 text-[15px] leading-relaxed text-slate-600">
                    @foreach ($paragraphs as $p)
                        <p>{{ $p }}</p>
                    @endforeach
                </div>
                <div class="mt-8 flex flex-wrap items-center gap-3 border-t border-slate-100 pt-6">
                    <a href="{{ $sectionUrl }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-5 py-2.5 text-[13px] font-bold text-slate-600 transition hover:border-cobalt-500 hover:text-cobalt-600">
                        <x-hi name="arrow-left" width="15" height="15" />
                        Kembali ke {{ $section }}
                    </a>
                    <button type="button" id="detail-share" class="inline-flex items-center gap-2 rounded-xl bg-cobalt-600 px-5 py-2.5 text-[13px] font-bold text-white transition hover:bg-cobalt-700">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.6 13.5 6.8 4M15.4 6.5l-6.8 4"/></svg>
                        Bagikan
                    </button>
                    <span id="detail-share-ok" class="hidden text-[13px] font-medium text-forest-600">Tautan disalin!</span>
                </div>
            </article>

            {{-- Terkait --}}
            <aside>
                <h2 class="text-[16px] font-extrabold text-slate-900">Terkait lainnya</h2>
                <div class="mt-4 space-y-4">
                    @foreach ($related as $rel)
                        <a href="{{ $rel['url'] }}" class="group flex gap-3 rounded-2xl border border-slate-100 bg-white p-3 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                            <img src="{{ \App\Data\SiteContent::image($rel['img']) }}" alt="" class="h-16 w-20 shrink-0 rounded-xl object-cover">
                            <span>
                                <span class="block text-[12px] font-medium text-slate-400">{{ $rel['date'] }}</span>
                                <span class="mt-0.5 line-clamp-2 block text-[13px] font-bold leading-snug text-slate-800 transition-colors group-hover:text-cobalt-600">{{ $rel['title'] }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </aside>
        </div>
    </div>
</section>

@include('landing.partials.footer')

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('detail-share').addEventListener('click', async function () {
            const ok = document.getElementById('detail-share-ok');
            try {
                await navigator.clipboard.writeText(window.location.href);
                ok.classList.remove('hidden');
                setTimeout(() => ok.classList.add('hidden'), 2500);
            } catch (e) {
                window.prompt('Salin tautan:', window.location.href);
            }
        });
    });
</script>
@endsection
