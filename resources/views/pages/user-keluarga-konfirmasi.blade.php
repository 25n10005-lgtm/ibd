{{-- ===== Hubungkan NIK (Warga, langkah 2: konfirmasi tersensor) ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konfirmasi Data — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php $user = auth()->user(); @endphp
<div class="flex min-h-screen">
    @include('user.partials.sidebar', ['user' => $user, 'active' => 'keluarga'])

    <main class="min-w-0 flex-1 p-7">
        <x-page-header title="Konfirmasi Data" subtitle="Pastikan data berikut adalah data Anda sebelum diajukan ke pengurus." />

        <section class="mx-auto mt-5 max-w-lg rounded-2xl border border-slate-100 bg-white p-6 text-center shadow-sm">
            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#ECFDF5] text-[#047857]">
                <x-hi name="shield" width="26" height="26" />
            </span>
            <p class="mt-3 text-[11px] font-bold tracking-wider text-slate-400 uppercase">Data cocok — identitas disensor</p>
            <p class="mt-1 text-xl font-extrabold tracking-wide">{{ $maskedNama }}</p>
            <p class="mt-1 font-mono text-[13px] text-slate-500">{{ $maskedNik }}</p>
            <p class="mt-1 text-[12px] text-slate-400">No KK {{ $keluargaKk }}</p>

            <ul class="mt-4 space-y-1.5 text-left text-[12.5px]">
                @foreach (['NIK ditemukan di data RT', 'NIK belum terhubung akun lain', 'NIK berada di RT Anda', 'No KK, nama & tanggal lahir cocok'] as $cek)
                    <li class="flex items-center gap-2 rounded-xl bg-green-50/60 px-3 py-2 font-medium text-green-800">
                        <x-hi name="check" width="14" height="14" class="shrink-0" /> {{ $cek }}
                    </li>
                @endforeach
            </ul>

            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                <a href="{{ route('saya.keluarga.verifikasi') }}" class="rounded-xl border border-slate-200 bg-white px-6 py-2.5 text-center text-[12.5px] font-bold text-slate-600 transition hover:bg-slate-50">Bukan saya / Ubah</a>
                <form method="POST" action="{{ route('saya.keluarga.verifikasi.ajukan') }}">
                    @csrf
                    <button type="submit" class="w-full rounded-xl bg-[#047857] px-6 py-2.5 text-[12.5px] font-bold text-white transition hover:bg-green-800">Ya, Ajukan Verifikasi</button>
                </form>
            </div>
        </section>
    </main>
</div>
</body>
</html>