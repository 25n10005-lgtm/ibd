{{-- ===== Pengaturan (Warga) ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaturan — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php $user = auth()->user(); @endphp
<div class="flex min-h-screen">
    @include('user.partials.sidebar', ['user' => $user, 'active' => 'pengaturan'])

    <main class="min-w-0 flex-1 p-7">
        <x-page-header title="Pengaturan" subtitle="Kelola profil dan keamanan akun Anda." />

        @if (session('success'))
            <p class="mt-4 rounded-xl bg-green-50 px-4 py-3 text-[13px] font-medium text-green-700">{{ session('success') }}</p>
        @endif
        @if (session('error'))
            <p class="mt-4 rounded-xl bg-rose-50 px-4 py-3 text-[13px] font-medium text-rose-600">{{ session('error') }}</p>
        @endif

        <div class="mt-5 grid gap-4 xl:grid-cols-3">
            <section class="rounded-2xl border border-slate-100 bg-white p-6 text-center shadow-sm">
                <img src="{{ $user->avatar ?? asset('images/landing/logo.png') }}" alt="{{ $user->name }}" class="mx-auto h-20 w-20 rounded-full object-cover">
                <p class="mt-3 text-[15px] font-extrabold">{{ $user->name }}</p>
                <p class="text-[12px] text-slate-400">{{ $user->email }}</p>
                <span class="mt-2 inline-flex rounded-lg bg-[#ECFDF5] px-3 py-1 text-[11px] font-bold text-[#047857]">{{ $user->role?->label() }}</span>
            </section>

            <section class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm xl:col-span-2">
                <h2 class="text-[14.5px] font-extrabold">Ganti password</h2>
                <form method="POST" action="{{ route('account.password') }}" class="mt-4 grid gap-4 sm:grid-cols-2">
                    @csrf
                    <input type="password" name="current_password" autocomplete="current-password" placeholder="Password saat ini (kosongkan jika akun Google)" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] outline-none transition placeholder:text-slate-400 focus:border-green-500">
                    <div class="hidden sm:block"></div>
                    <input type="password" name="password" required autocomplete="new-password" placeholder="Password baru (min. 8)" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] outline-none transition placeholder:text-slate-400 focus:border-green-500">
                    <input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password baru" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] outline-none transition placeholder:text-slate-400 focus:border-green-500">
                    <div class="sm:col-span-2">
                        <button type="submit" class="rounded-xl bg-slate-900 px-6 py-2.5 text-[12.5px] font-bold text-white transition hover:bg-slate-700">Simpan Password</button>
                    </div>
                </form>
            </section>
        </div>
    </main>
</div>
</body>
</html>
