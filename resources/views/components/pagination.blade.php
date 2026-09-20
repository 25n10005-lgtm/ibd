{{-- Pagination bernomor 1,2,3.. + prev/next konsisten. --}}
@props(['rows'])
<div class="ml-auto flex items-center gap-1.5">
    @if ($rows->onFirstPage())
        <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-100 text-slate-300">
            <x-hi name="chev-left" width="14" height="14" />
        </span>
    @else
        <a href="{{ $rows->previousPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-400 transition hover:border-slate-300 hover:text-slate-700" title="Sebelumnya">
            <x-hi name="chev-left" width="14" height="14" />
        </a>
    @endif
    @foreach ($rows->getUrlRange(max(1, $rows->currentPage() - 2), min($rows->lastPage(), $rows->currentPage() + 2)) as $page => $url)
        @if ($page === $rows->currentPage())
            <span class="h-8 w-8 rounded-lg bg-cobalt-600 text-center text-[12.5px] leading-8 font-bold text-white">{{ $page }}</span>
        @else
            <a href="{{ $url }}" class="h-8 w-8 rounded-lg text-center text-[12.5px] leading-8 font-bold text-slate-500 transition hover:bg-slate-100">{{ $page }}</a>
        @endif
    @endforeach
    @if ($rows->hasMorePages())
        <a href="{{ $rows->nextPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-400 transition hover:border-slate-300 hover:text-slate-700" title="Berikutnya">
            <x-hi name="chev-right" width="14" height="14" />
        </a>
    @else
        <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-100 text-slate-300">
            <x-hi name="chev-right" width="14" height="14" />
        </span>
    @endif
</div>
