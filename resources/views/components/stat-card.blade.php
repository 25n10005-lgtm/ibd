{{-- Kartu statistik konsisten: ikon + label + angka + sub. --}}
@props(['label' => '', 'value' => '', 'sub' => '', 'icon' => 'chart', 'tone' => 'text-cobalt-600 bg-cobalt-50', 'subTone' => 'text-slate-400'])
<div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_10px_30px_-18px_rgba(15,23,42,0.25)]">
    <div class="flex items-center gap-4">
        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $tone }}">
            <x-hi :name="$icon" width="19" height="19" />
        </span>
        <div class="min-w-0">
            <p class="text-[12.5px] font-semibold text-slate-500">{{ $label }}</p>
            <p class="truncate text-[22px] leading-7 font-extrabold tracking-tight">{{ $value }}</p>
        </div>
    </div>
    @if ($sub)
        <p class="mt-2 text-[11.5px] font-medium {{ $subTone }}">{{ $sub }}</p>
    @endif
</div>
