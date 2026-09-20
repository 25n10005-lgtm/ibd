{{-- ===== SSO callback (kembalian login Google) ===== --}}
@extends('layouts.landing')

@section('title', 'Memproses Login — Smart RT')

@section('content')
<section class="flex min-h-screen items-center justify-center bg-gradient-to-b from-cobalt-50/70 via-[#F2F6FD] to-white px-6">
    <div class="w-full max-w-sm rounded-3xl border border-slate-100 bg-white p-8 text-center shadow-xl">
        <span class="mx-auto flex h-12 w-12 items-center justify-center">
            <svg class="animate-spin text-cobalt-600" width="28" height="28" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity="0.25" stroke-width="3"/><path d="M22 12a10 10 0 0 0-10-10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
        </span>
        <h1 class="mt-4 text-lg font-extrabold text-slate-900">Memproses login Google…</h1>
        <div id="sso-callback" data-redirect="/dashboard" data-publishable-key="{{ config('clerk.publishable_key') }}"></div>
        {{-- Wadah captcha bawaan Clerk (dibutuhkan saat sign-up via transfer) --}}
        <div id="clerk-captcha"></div>
        <div id="clerk-status" class="mt-4 hidden"></div>
        <p class="mt-4 text-[12px] text-slate-400">
            <a href="{{ route('login') }}" class="transition hover:text-cobalt-600">← Kembali ke halaman masuk</a>
        </p>
    </div>
</section>

@vite('resources/js/auth.js')
@endsection
