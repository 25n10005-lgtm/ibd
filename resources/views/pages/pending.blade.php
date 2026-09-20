{{-- ===== Menunggu validasi ===== --}}
@extends('layouts.landing')

@section('title', 'Menunggu Validasi — Smart RT')

@section('content')
<section class="flex min-h-screen items-center justify-center bg-gradient-to-b from-cobalt-50/70 via-[#F2F6FD] to-white px-6">
    <div class="w-full max-w-md rounded-3xl border border-slate-100 bg-white p-8 text-center shadow-xl">
        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-amber-50 text-amber-500">
            <x-hi name="clock" width="26" height="26" />
        </span>
        <h1 class="mt-4 text-xl font-extrabold text-slate-900">Pengajuan Anda sedang ditinjau</h1>

        @if (session('success'))
            <p class="mt-4 rounded-xl bg-forest-50 px-4 py-3 text-[13px] font-medium text-forest-600">{{ session('success') }}</p>
        @endif

        @php($account = auth()->user())
        @if ($account && $account->requested_role)
            <p class="mt-2 text-[13.5px] leading-relaxed text-slate-500">
                Permintaan peran <span class="font-bold text-slate-700">{{ $account->requested_role->label() }}</span>
                menunggu validasi Super Admin. Anda akan mendapat akses penuh setelah disetujui.
            </p>
            @if ($account->status?->value === 'active')
                <a href="{{ route('dashboard') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-cobalt-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-cobalt-700">
                    Kembali ke Dashboard
                </a>
            @endif
        @else
            <p class="mt-2 text-[13.5px] leading-relaxed text-slate-500">
                Akun Anda sedang menunggu validasi. Silakan kembali lagi nanti.
            </p>
        @endif

        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button type="submit" class="text-[13px] font-semibold text-slate-400 hover:text-rose-600">Keluar</button>
        </form>
    </div>
</section>
@endsection
