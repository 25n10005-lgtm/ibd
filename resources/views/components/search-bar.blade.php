{{-- Search bar konsisten. --}}
@props(['name' => 'q', 'value' => '', 'placeholder' => 'Cari...'])
<label class="flex min-w-0 flex-1 items-center gap-2 rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-2.5 sm:max-w-xs">
    <x-hi name="search" width="15" height="15" class="shrink-0 text-slate-400" />
    <input type="search" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}" class="w-full bg-transparent text-[12.5px] outline-none placeholder:text-slate-400">
</label>
