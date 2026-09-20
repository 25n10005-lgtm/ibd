{{-- ===== Dashboard ===== --}}
@extends('layouts.landing')

@section('title', 'Dashboard — Smart RT')

@section('content')
@include('landing.partials.navbar')

<section class="bg-white">
    <div class="mx-auto max-w-[1240px] px-6 pt-14 pb-20 lg:px-8">
        <p class="text-[13px] font-bold tracking-[0.18em] text-cobalt-600">DASHBOARD</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Halo, {{ auth()->user()->name }}</h1>
        <p class="mt-2 text-[15px] text-slate-500">Peran Anda: <span class="font-bold text-slate-800">{{ auth()->user()->role?->label() }}</span> • Status: <span class="font-bold text-forest-600">Aktif</span></p>

        @if (session('success'))
            <p class="mt-6 max-w-[640px] rounded-xl bg-forest-50 px-4 py-3 text-[13px] font-medium text-forest-600">{{ session('success') }}</p>
        @endif
        @if (session('error'))
            <p class="mt-6 max-w-[640px] rounded-xl bg-rose-50 px-4 py-3 text-[13px] font-medium text-rose-600">{{ session('error') }}</p>
        @endif
        @if ($errors->any())
            <div class="mt-6 max-w-[640px] rounded-xl bg-rose-50 px-4 py-3 text-[13px] font-medium text-rose-600">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Banner pengajuan peran yang masih menunggu --}}
        @if (auth()->user()->requested_role)
            <div class="mt-6 max-w-[640px] rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4">
                <p class="text-[14px] font-bold text-amber-700">Pengajuan {{ auth()->user()->requested_role->label() }} menunggu validasi</p>
                <p class="mt-1 text-[13px] leading-relaxed text-amber-600">Akun Anda tetap aktif sebagai {{ auth()->user()->role?->label() }} selama menunggu. <a href="{{ route('account.pending') }}" class="font-bold underline">Lihat status</a></p>
            </div>
        @endif

        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['t' => 'Profil Saya', 'd' => 'Kelola data akun Anda.', 'href' => route('auth.me'), 'ext' => false],
                ['t' => 'Kegiatan', 'd' => 'Lihat agenda lingkungan.', 'href' => route('kegiatan'), 'ext' => false],
                ['t' => 'Pengumuman', 'd' => 'Info resmi terbaru.', 'href' => route('pengumuman'), 'ext' => false],
                ['t' => 'Pengaduan', 'd' => 'Lapor & pantau laporan.', 'href' => route('pengaduan'), 'ext' => false],
            ] as $c)
                <a href="{{ $c['href'] }}" class="group rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <p class="text-[15px] font-bold text-slate-900 group-hover:text-cobalt-600">{{ $c['t'] }}</p>
                    <p class="mt-1 text-[13px] text-slate-500">{{ $c['d'] }}</p>
                </a>
            @endforeach
        </div>

        <div class="mt-10 grid gap-5 lg:grid-cols-2">
            {{-- Ajukan peran Admin (masuk antrean approval) --}}
            @if (! auth()->user()->hasAtLeastRole(\App\Enums\Role::Admin) && ! auth()->user()->requested_role)
                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                    <p class="text-[15px] font-bold text-slate-900">Ingin menjadi Admin?</p>
                    <p class="mt-1 text-[13px] leading-relaxed text-slate-500">Pengajuan dikirim ke API approval dan menunggu validasi Super Admin. Akun Anda tetap aktif sebagai Warga selama menunggu.</p>
                    <form method="POST" action="{{ route('account.request-role') }}" class="mt-4">
                        @csrf
                        <input type="hidden" name="role" value="admin">
                        <button type="submit" class="rounded-xl bg-amber-400 px-6 py-2.5 text-sm font-bold text-slate-900 transition hover:bg-amber-500">
                            Ajukan sebagai Admin
                        </button>
                    </form>
                </div>
            @endif

            {{-- Samakan akun: atur password agar bisa login email+password --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                <p class="text-[15px] font-bold text-slate-900">Login email &amp; Google satu akun</p>
                <p class="mt-1 text-[13px] leading-relaxed text-slate-500">Akun Google (tanpa password) bisa mengosongkan kolom "Password saat ini" untuk mengaktifkan login email+password pada email yang sama.</p>
                <form method="POST" action="{{ route('account.password') }}" class="mt-4 grid gap-3">
                    @csrf
                    <input type="password" name="current_password" autocomplete="current-password" placeholder="Password saat ini (kosongkan jika akun Google)"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[14px] outline-none transition placeholder:text-slate-400 focus:border-cobalt-500 focus:ring-2 focus:ring-cobalt-100">
                    <div class="grid gap-3 sm:grid-cols-2">
                        <input type="password" name="password" required autocomplete="new-password" placeholder="Password baru (min. 8)"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[14px] outline-none transition placeholder:text-slate-400 focus:border-cobalt-500 focus:ring-2 focus:ring-cobalt-100">
                        <input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password baru"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[14px] outline-none transition placeholder:text-slate-400 focus:border-cobalt-500 focus:ring-2 focus:ring-cobalt-100">
                    </div>
                    <button type="submit" class="w-fit rounded-xl bg-slate-900 px-6 py-2.5 text-sm font-bold text-white transition hover:bg-slate-700">
                        Simpan Password
                    </button>
                </form>
            </div>
        </div>

        @if (auth()->user()->hasAtLeastRole(\App\Enums\Role::SuperAdmin))
            <a href="{{ route('admin.approvals') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-cobalt-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-cobalt-700">
                Validasi Pengajuan Peran
                <x-hi name="chev-right" width="15" height="15" />
            </a>
        @endif

        <form method="POST" action="{{ route('logout') }}" class="mt-8">
            @csrf
            <button type="submit" class="text-[13px] font-semibold text-slate-400 transition hover:text-rose-600">Keluar</button>
        </form>
    </div>
</section>

@include('landing.partials.footer')
@endsection
