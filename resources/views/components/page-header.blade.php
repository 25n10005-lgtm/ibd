{{-- Header halaman admin konsisten: judul + sub + slot aksi. --}}
@props(['title' => '', 'subtitle' => ''])
<div class="flex flex-wrap items-center gap-3">
    <div class="mr-auto">
        <h1 class="text-xl font-extrabold tracking-tight sm:text-2xl">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-0.5 text-[12.5px] text-slate-500">{{ $subtitle }}</p>
        @endif
    </div>
    {{ $slot }}
</div>
