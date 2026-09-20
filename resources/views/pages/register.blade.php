{{-- ===== Daftar: kartu kompak sesuai referensi ===== --}}
@extends('layouts.landing')

@section('title', 'Daftar — Smart RT')

@section('content')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
@php $bgAuth = file_exists(public_path('images/auth/bg-login.jpg')) ? asset('images/auth/bg-login.jpg') : ''; @endphp
<div class="min-h-screen w-full relative overflow-x-hidden flex items-center justify-center lg:justify-end">

  <div class="fixed inset-0 w-full h-full -z-10 bg-[#f4f8fe]">
    @if($bgAuth !== '')
      <img src="{{ $bgAuth }}" alt="Smart RT Hero Background" class="w-full h-full object-cover object-left">
    @endif
  </div>

  <div class="w-full min-h-screen flex items-center justify-center lg:justify-end px-6 lg:pr-24 py-8 z-10">
    <div data-reveal class="w-full max-w-[410px] bg-white rounded-[32px] shadow-[0_24px_50px_rgba(32,101,209,0.08),0_4px_12px_rgba(0,0,0,0.04)] border border-white p-6 sm:p-7 flex flex-col items-center">

      <div class="w-10 h-10 rounded-full bg-[#eff5ff] border border-[#dbeafe] flex items-center justify-center mb-3">
        <span class="material-symbols-outlined text-[22px] text-[#2065D1]">person</span>
      </div>

      <h1 class="text-[20px] sm:text-[22px] font-bold text-[#111827] tracking-tight text-center mb-1">
        Buat akun
      </h1>
      <p class="text-xs text-[#64748b] text-center mb-5 max-w-[300px] leading-relaxed">
        Satu akun untuk masuk via email &amp; password maupun Google (email sama = akun sama).
      </p>

      @if ($errors->any())
        <div class="w-full mb-4 rounded-xl bg-rose-50 px-4 py-3 text-[13px] font-medium text-rose-600">
          <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('register.store') }}" class="w-full flex flex-col gap-3.5">
        @csrf
        <div class="flex flex-col gap-1.5">
          <label class="text-xs font-bold text-[#334155] tracking-wide" for="name">Nama lengkap</label>
          <div class="relative flex items-center">
            <span class="material-symbols-outlined absolute left-3.5 text-[#94a3b8] text-[20px] pointer-events-none">person</span>
            <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Nama Anda" required autocomplete="name" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-[#e2e8f0] bg-white text-sm text-[#1e293b] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#2065d1] focus:ring-4 focus:ring-[#2065d1]/10 transition-all">
          </div>
        </div>

        <div class="flex flex-col gap-1.5">
          <label class="text-xs font-bold text-[#334155] tracking-wide" for="email">Email</label>
          <div class="relative flex items-center">
            <span class="material-symbols-outlined absolute left-3.5 text-[#94a3b8] text-[20px] pointer-events-none">mail</span>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autocomplete="email" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-[#e2e8f0] bg-white text-sm text-[#1e293b] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#2065d1] focus:ring-4 focus:ring-[#2065d1]/10 transition-all">
          </div>
        </div>

        <div class="grid gap-3.5 sm:grid-cols-2">
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-bold text-[#334155] tracking-wide" for="password">Password</label>
            <div class="relative flex items-center">
              <span class="material-symbols-outlined absolute left-3.5 text-[#94a3b8] text-[20px] pointer-events-none">lock</span>
              <input id="password" type="password" name="password" placeholder="Min. 8 karakter" required autocomplete="new-password" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-[#e2e8f0] bg-white text-sm text-[#1e293b] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#2065d1] focus:ring-4 focus:ring-[#2065d1]/10 transition-all">
            </div>
          </div>
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-bold text-[#334155] tracking-wide" for="password_confirmation">Ulangi password</label>
            <div class="relative flex items-center">
              <span class="material-symbols-outlined absolute left-3.5 text-[#94a3b8] text-[20px] pointer-events-none">lock</span>
              <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Ulangi password" required autocomplete="new-password" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-[#e2e8f0] bg-white text-sm text-[#1e293b] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#2065d1] focus:ring-4 focus:ring-[#2065d1]/10 transition-all">
            </div>
          </div>
        </div>

        <div class="flex flex-col gap-1.5">
          <label class="text-xs font-bold text-[#334155] tracking-wide" for="role">Daftar sebagai</label>
          <select id="role" name="role" required class="w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] bg-white text-sm font-semibold text-[#1e293b] focus:outline-none focus:border-[#2065d1] focus:ring-4 focus:ring-[#2065d1]/10 transition-all">
            <option value="user" {{ old('role', 'user') === 'user' ? 'selected' : '' }}>Warga</option>
            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Pengurus / Admin</option>
          </select>
          <p class="text-[11px] leading-relaxed text-[#94a3b8]">Pendaftar Admin otomatis dikirim ke approval dan menunggu validasi Super Admin.</p>
        </div>

        <div class="flex flex-col gap-1.5">
          <label class="text-xs font-bold text-[#334155] tracking-wide" for="rt_id">RT Tempat Tinggal</label>
          @if (($rts ?? collect())->isNotEmpty())
            <select id="rt_id" name="rt_id" required class="w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] bg-white text-sm font-semibold text-[#1e293b] focus:outline-none focus:border-[#2065d1] focus:ring-4 focus:ring-[#2065d1]/10 transition-all">
              <option value="" disabled {{ old('rt_id') ? '' : 'selected' }}>Pilih RT tempat tinggal</option>
              @foreach ($rts as $rt)
                <option value="{{ $rt->id }}" {{ (string) old('rt_id') === (string) $rt->id ? 'selected' : '' }}>{{ $rt->label() }}</option>
              @endforeach
            </select>
            <p class="text-[11px] leading-relaxed text-[#94a3b8]">Data Anda hanya berlaku di RT ini dan diverifikasi pengurus RT tersebut.</p>
          @else
            <p class="rounded-xl bg-amber-50 px-4 py-3 text-[13px] font-medium text-amber-700">Belum ada RT terdaftar. Hubungi pengurus agar RT Anda didaftarkan lebih dulu.</p>
          @endif
        </div>

        <button type="submit" class="w-full mt-1 py-3 px-5 rounded-xl bg-[#2065d1] hover:bg-[#1854b4] text-white font-bold text-sm flex items-center justify-center gap-2 shadow-md shadow-[#2065d1]/25 hover:shadow-lg hover:shadow-[#2065d1]/35 active:scale-[0.99] transition-all">
          <span class="material-symbols-outlined text-[18px]">person_add</span>
          <span>Daftar</span>
        </button>
      </form>

      <div class="mt-4 text-xs text-[#64748b]">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-[#2065d1] hover:text-[#154ec1] font-bold ml-0.5 hover:underline">
          Masuk di sini
        </a>
      </div>

      <div class="mt-3 flex items-center justify-center gap-1.5 text-[11px] text-[#64748b]/90">
        <span class="material-symbols-outlined text-[15px] text-[#64748b]">verified_user</span>
        <span>Akun Anda aman bersama Smart RT</span>
      </div>

    </div>
  </div>
</div>
@endsection
