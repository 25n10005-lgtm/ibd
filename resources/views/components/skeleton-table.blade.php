{{-- Skeleton tabel (Tailwind animate-pulse bawaan). --}}
@props(['rows' => 5, 'cols' => 4])
<div class="animate-pulse" aria-hidden="true">
    <div class="flex gap-3 border-b border-slate-100 pb-3">
        @for ($i = 0; $i < $cols; $i++)
            <div class="h-3 flex-1 rounded bg-slate-200"></div>
        @endfor
    </div>
    @for ($r = 0; $r < $rows; $r++)
        <div class="flex items-center gap-3 border-b border-slate-50 py-3.5">
            <div class="h-8 w-8 shrink-0 rounded-lg bg-slate-200"></div>
            <div class="flex-1 space-y-2">
                <div class="h-3 w-2/3 rounded bg-slate-200"></div>
                <div class="h-2.5 w-1/3 rounded bg-slate-100"></div>
            </div>
            <div class="h-6 w-16 rounded-lg bg-slate-200"></div>
        </div>
    @endfor
</div>
