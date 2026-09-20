{{-- Tombol ikon + tooltip saat hover. --}}
@props(['icon' => 'eye', 'tooltip' => '', 'href' => null])
<span class="group relative inline-flex">
    @if ($href)
        <a href="{{ $href }}" title="{{ $tooltip }}" {{ $attributes->merge(['class' => 'flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-cobalt-500 hover:text-cobalt-600']) }}>
            <x-hi :name="$icon" width="15" height="15" />
        </a>
    @else
        <button type="button" title="{{ $tooltip }}" {{ $attributes->merge(['class' => 'flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-cobalt-500 hover:text-cobalt-600']) }}>
            <x-hi :name="$icon" width="15" height="15" />
        </button>
    @endif
    @if ($tooltip)
        <span class="pointer-events-none absolute -top-8 left-1/2 -translate-x-1/2 rounded-md bg-slate-900 px-2 py-1 text-[10px] font-semibold whitespace-nowrap text-white opacity-0 transition group-hover:opacity-100">{{ $tooltip }}</span>
    @endif
</span>
