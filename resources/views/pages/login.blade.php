{{-- ===== Masuk: desain referensi (kartu kanan + background) ===== --}}
@extends('layouts.landing')

@section('title', 'Masuk — Smart RT')

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
    <div data-reveal class="w-full max-w-[410px] bg-white rounded-[32px] shadow-[0_24px_50px_rgba(32,101,209,0.08),0_4px_12px_rgba(0,0,0,0.04)] border border-white p-7 sm:p-8 flex flex-col items-center">



      <h1 class="text-[22px] sm:text-[24px] font-bold text-[#111827] tracking-tight text-center mb-1.5">
        Selamat Datang Kembali
      </h1>
      <p class="text-xs text-[#64748b] text-center mb-6 max-w-[280px] leading-relaxed">
        Masuk dengan email dan password.
      </p>

      @if (session('error'))
        <p data-server-flash class="w-full mb-4 rounded-xl bg-rose-50 px-4 py-3 text-[13px] font-medium text-rose-600">{{ session('error') }}</p>
      @endif
      @if (session('success'))
        <p class="w-full mb-4 rounded-xl bg-forest-50 px-4 py-3 text-[13px] font-medium text-forest-600">{{ session('success') }}</p>
      @endif
      @if ($errors->any())
        <div class="w-full mb-4 rounded-xl bg-rose-50 px-4 py-3 text-[13px] font-medium text-rose-600">
          <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('login.attempt') }}" class="w-full flex flex-col gap-4">
        @csrf
        <div class="flex flex-col gap-1.5">
          <label class="text-xs font-bold text-[#334155] tracking-wide" for="email">
            Email
          </label>
          <div class="relative flex items-center">
            <span class="material-symbols-outlined absolute left-3.5 text-[#94a3b8] text-[20px] pointer-events-none">
              mail
            </span>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autocomplete="email" class="w-full pl-10 pr-4 py-3 rounded-xl border border-[#e2e8f0] bg-white text-sm text-[#1e293b] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#2065d1] focus:ring-4 focus:ring-[#2065d1]/10 transition-all">
          </div>
        </div>

        <div class="flex flex-col gap-1.5 mt-1">
          <label class="text-xs font-bold text-[#334155] tracking-wide" for="passwordInput">
            Password
          </label>
          <div class="relative flex items-center">
            <span class="material-symbols-outlined absolute left-3.5 text-[#94a3b8] text-[20px] pointer-events-none">
              lock
            </span>
            <input type="password" id="passwordInput" name="password" placeholder="Masukkan password" required autocomplete="current-password" class="w-full pl-10 pr-11 py-3 rounded-xl border border-[#e2e8f0] bg-white text-sm text-[#1e293b] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#2065d1] focus:ring-4 focus:ring-[#2065d1]/10 transition-all">
            <button type="button" onclick="togglePassword()" class="absolute right-3.5 text-[#94a3b8] hover:text-[#475569] transition-colors focus:outline-none" title="Tampilkan password">
              <span id="eyeIcon" class="material-symbols-outlined text-[20px]">
                visibility_off
              </span>
            </button>
          </div>
        </div>

        <div class="flex items-center justify-between mt-1 text-xs">
          <label class="flex items-center gap-2 cursor-pointer select-none text-[#475569] font-medium">
            <input type="checkbox" name="remember" value="1" checked class="w-4 h-4 rounded text-[#2065d1] border-gray-300 focus:ring-[#2065d1] focus:ring-offset-0 transition">
            Ingat saya
          </label>
          <a href="#" class="text-[#2065d1] hover:text-[#154ec1] font-semibold transition-colors">
            Lupa password?
          </a>
        </div>

        <button type="submit" class="w-full mt-3 py-3.5 px-5 rounded-xl bg-[#2065d1] hover:bg-[#1854b4] text-white font-bold text-sm flex items-center justify-center gap-2 shadow-md shadow-[#2065d1]/25 hover:shadow-lg hover:shadow-[#2065d1]/35 active:scale-[0.99] transition-all">
          <span class="material-symbols-outlined text-[18px]">
            arrow_forward
          </span>
          <span>Masuk dengan Email</span>
        </button>

        <div class="relative flex items-center justify-center my-3">
          <div class="w-full border-t border-[#e2e8f0]"></div>
          <span class="absolute px-3 bg-white text-[11px] font-bold text-[#94a3b8] uppercase tracking-wider">
            Atau
          </span>
        </div>

        <button type="button" id="google-btn" data-publishable-key="{{ config('clerk.publishable_key') }}" data-redirect="{{ route('dashboard') }}" class="w-full py-3 px-5 rounded-xl bg-white hover:bg-[#f8fafc] text-[#334155] border border-[#e2e8f0] font-semibold text-sm flex items-center justify-center gap-3 shadow-sm hover:border-[#cbd5e1] active:scale-[0.99] transition-all">
          <svg class="w-4 h-4" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"></path>
            <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.26v3.15C3.26 21.36 7.33 24 12 24z"></path>
            <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.26C.46 8.16 0 9.97 0 12s.46 3.84 1.26 5.42l4.02-3.15z"></path>
            <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.26 6.58l4.02 3.15c.95-2.83 3.6-4.98 6.72-4.98z"></path>
          </svg>
          <span data-label>Lanjutkan dengan Google</span>
        </button>
        <div id="clerk-status" class="hidden"></div>
        <div id="clerk-captcha"></div>
      </form>

      <div class="mt-5 text-xs text-[#64748b]">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-[#2065d1] hover:text-[#154ec1] font-bold ml-0.5 hover:underline">
          Daftar di sini
        </a>
      </div>

      <div class="mt-4 flex items-center justify-center gap-1.5 text-[11px] text-[#64748b]/90">
        <span class="material-symbols-outlined text-[15px] text-[#64748b]">
          verified_user
        </span>
        <span>Akun Anda aman bersama Smart RT</span>
      </div>

    </div>
  </div>

  <script>
    function togglePassword() {
      const pwdInput = document.getElementById('passwordInput');
      const eyeIcon = document.getElementById('eyeIcon');
      if (pwdInput.type === 'password') {
        pwdInput.type = 'text';
        eyeIcon.textContent = 'visibility';
      } else {
        pwdInput.type = 'password';
        eyeIcon.textContent = 'visibility_off';
      }
    }
  </script>
</div>
@vite('resources/js/auth.js')
@endsection
