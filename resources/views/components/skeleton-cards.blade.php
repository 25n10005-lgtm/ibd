{{-- Skeleton kartu statistik (Tailwind animate-pulse bawaan). --}}
@props(['count' => 4])
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-{{ $count > 3 ? 4 : $count }} animate-pulse" aria-hidden="true">
    @for ($i = 0; $i < $count; $i++)
        <div class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-5">
            <div class="h-11 w-11 shrink-0 rounded-xl bg-slate-200"></div>
            <div class="flex-1 space-y-2">
                <div class="h-3 w-1/2 rounded bg-slate-200"></div>
                <div class="h-5 w-1/3 rounded bg-slate-200"></div>
            </div>
        </div>
    @endfor
</div>
