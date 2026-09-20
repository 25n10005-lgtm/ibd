{{-- ===== Ajukan Surat Baru (Warga, halaman terpisah) ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajukan Surat — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php
    $user = auth()->user();
    $jenisList = ['Surat Pengantar', 'Surat Domisili', 'Surat Keterangan Usaha', 'Surat Keterangan Belum Menikah', 'Surat Keterangan Ahli Waris', 'Lainnya'];
@endphp
<div class="flex min-h-screen">
    @include('user.partials.sidebar', ['user' => $user, 'active' => 'surat'])

    <main class="min-w-0 flex-1 p-7">
        <x-page-header title="Ajukan Surat" subtitle="Isi formulir, pantau statusnya di daftar pengajuan.">
            <a href="{{ route('saya.surat') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-[12.5px] font-bold text-slate-600 transition hover:bg-slate-50">
                <x-hi name="arrow-left" width="14" height="14" /> Daftar Pengajuan
            </a>
        </x-page-header>

        @if ($errors->any())
            <p class="mt-4 rounded-xl bg-rose-50 px-4 py-3 text-[13px] font-medium text-rose-600">{{ $errors->first() }}</p>
        @endif

        <section class="mx-auto mt-5 max-w-2xl rounded-2xl border border-slate-100 bg-white p-5 shadow-sm sm:p-6">
            <form method="POST" action="{{ route('saya.surat.store') }}" class="grid gap-4 sm:grid-cols-2">
                @csrf
                <label class="block">
                    <span class="mb-1.5 block text-[12px] font-bold text-slate-600">Jenis Surat</span>
                    <select name="jenis" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[13px] outline-none focus:border-green-500">
                        @foreach ($jenisList as $j)
                            <option value="{{ $j }}" @selected(old('jenis') === $j)>{{ $j }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block sm:col-span-2">
                    <span class="mb-1.5 block text-[12px] font-bold text-slate-600">Keperluan</span>
                    <textarea name="keperluan" required minlength="10" maxlength="1000" rows="4" placeholder="Tulis keperluan surat minimal 10 karakter..." class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] outline-none placeholder:text-slate-400 focus:border-green-500">{{ old('keperluan') }}</textarea>
                </label>
                <div class="sm:col-span-2">
                    <button type="submit" class="rounded-xl bg-[#047857] px-6 py-2.5 text-[12.5px] font-bold text-white transition hover:bg-green-800">Kirim Pengajuan</button>
                </div>
            </form>
        </section>
    </main>
</div>
</body>
</html>