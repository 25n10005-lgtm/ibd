{{-- ===== Hubungkan NIK (Warga, langkah 1: isi data) ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hubungkan NIK — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php $user = auth()->user(); @endphp
<div class="flex min-h-screen">
    @include('user.partials.sidebar', ['user' => $user, 'active' => 'keluarga'])

    <main class="min-w-0 flex-1 p-7">
        <x-page-header title="{{ ($link ?? null) ? 'Ganti NIK Tertaut' : 'Hubungkan NIK' }}" subtitle="Verifikasi mandiri agar akun tertaut dengan data warga RT.">
            <a href="{{ route('saya.keluarga') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-[12.5px] font-bold text-slate-600 transition hover:bg-slate-50">
                <x-hi name="arrow-left" width="14" height="14" /> Kembali
            </a>
        </x-page-header>

        @if (($link ?? null))
            <p class="mt-5 inline-flex items-center gap-1.5 rounded-xl bg-[#ECFDF5] px-4 py-2.5 text-[12.5px] font-bold text-[#047857]">
                <x-hi name="shield" width="15" height="15" /> Saat ini tertaut: {{ $link->warga->nama }} — persetujuan baru akan menggantikannya
            </p>
        @endif

        @if ($openClaim ?? null)
            <div class="mt-5 rounded-2xl border border-amber-200 bg-amber-50 p-6 text-center">
                <p class="text-[14px] font-bold text-amber-800">Pengajuan sedang diproses</p>
                <p class="mt-1 text-[12.5px] text-amber-700">Diajukan {{ $openClaim->created_at->diffForHumans() }}. Menunggu verifikasi pengurus RT.</p>
                <form method="POST" action="{{ route('saya.keluarga.verifikasi.batal', $openClaim) }}" class="mt-3">
                    @csrf
                    <button type="submit" class="rounded-xl border border-amber-300 bg-white px-5 py-2 text-[12.5px] font-bold text-amber-700 transition hover:bg-amber-100">Batalkan pengajuan</button>
                </form>
            </div>
        @else
            <ol class="mt-5 grid gap-3 sm:grid-cols-4">
                @foreach (['Isi NIK & No KK', 'Cek otomatis', 'Konfirmasi', 'Verifikasi pengurus'] as $i => $step)
                    <li class="rounded-2xl border p-4 {{ $i === 0 ? 'border-green-200 bg-green-50/60' : 'border-slate-100 bg-white' }}">
                        <p class="flex h-7 w-7 items-center justify-center rounded-lg text-[12px] font-extrabold {{ $i === 0 ? 'bg-[#047857] text-white' : 'bg-slate-100 text-slate-400' }}">{{ $i + 1 }}</p>
                        <p class="mt-2 text-[12.5px] font-bold">{{ $step }}</p>
                    </li>
                @endforeach
            </ol>

            @if ($errors->any())
                <div class="mt-4 rounded-xl bg-rose-50 px-4 py-3 text-[13px] font-medium text-rose-600">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="mt-4 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <h2 class="text-[14.5px] font-extrabold">Data sesuai KK / KTP</h2>
                <p class="mt-1 text-[12.5px] text-slate-500">Sistem mencocokkan NIK, No KK, nama lengkap, dan tanggal lahir secara otomatis. Anggota keluarga yang belum punya NIK tidak perlu diverifikasi satu per satu.</p>
                <form method="POST" action="{{ route('saya.keluarga.verifikasi.cek') }}" class="mt-4 grid gap-4 sm:grid-cols-2">
                    @csrf
                    <label class="block">
                        <span class="mb-1.5 block text-[12px] font-bold text-slate-600">NIK (16 digit)</span>
                        <input type="text" name="nik" value="{{ old('nik') }}" required inputmode="numeric" pattern="[0-9]{16}" maxlength="16" minlength="16" placeholder="cth: 3174051201900001" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] tracking-wider outline-none placeholder:text-slate-400 focus:border-green-500">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-[12px] font-bold text-slate-600">No KK (16 digit)</span>
                        <input type="text" name="no_kk" value="{{ old('no_kk') }}" required inputmode="numeric" pattern="[0-9]{16}" maxlength="16" minlength="16" placeholder="cth: 3174051201900012" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] tracking-wider outline-none placeholder:text-slate-400 focus:border-green-500">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-[12px] font-bold text-slate-600">Nama lengkap</span>
                        <input type="text" name="nama" value="{{ old('nama', $user->name) }}" required maxlength="255" placeholder="Sesuai KTP" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] outline-none placeholder:text-slate-400 focus:border-green-500">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-[12px] font-bold text-slate-600">Tanggal lahir</span>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required max="{{ today()->toDateString() }}" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] outline-none focus:border-green-500">
                    </label>
                    <div class="sm:col-span-2">
                        <button type="submit" class="rounded-xl bg-[#047857] px-6 py-2.5 text-[12.5px] font-bold text-white transition hover:bg-green-800">Cek Data</button>
                    </div>
                </form>
            </section>
        @endif
    </main>
</div>
</body>
</html>