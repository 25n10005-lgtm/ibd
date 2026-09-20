{{-- ===== Verifikasi NIK (Pengurus) — antrean lolos gate otomatis ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi NIK — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php
    $user = auth()->user();
    $gateLabel = ['nik_found' => 'NIK ditemukan', 'not_linked' => 'Belum tertaut', 'same_rt' => 'RT sama', 'basic_match' => 'Data cocok'];
@endphp
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['user' => $user, 'active' => 'verifikasi'])

    <main class="min-w-0 flex-1 px-5 py-5 sm:px-7">
        <div class="mr-auto">
            <p class="text-[11px] font-bold tracking-[0.18em] text-[#2863D1]">PENGURUS</p>
            <h1 class="mt-1 text-xl font-extrabold tracking-tight sm:text-2xl">Verifikasi NIK</h1>
            <p class="mt-0.5 text-[12.5px] text-slate-500">Semua antrean di bawah sudah lolos 4 gate otomatis — tinggal setujui atau tolak.</p>
        </div>

        @if (session('success'))
            <p class="mt-4 rounded-xl bg-green-50 px-4 py-3 text-[13px] font-medium text-green-700">{{ session('success') }}</p>
        @endif
        @if (session('error'))
            <p class="mt-4 rounded-xl bg-rose-50 px-4 py-3 text-[13px] font-medium text-rose-600">{{ session('error') }}</p>
        @endif
        @if ($errors->any())
            <p class="mt-4 rounded-xl bg-rose-50 px-4 py-3 text-[13px] font-medium text-rose-600">{{ $errors->first() }}</p>
        @endif

        <div class="mt-4 space-y-3">
            @forelse ($claims as $claim)
                <article class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-start gap-3">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#EFF6FF] text-[#2863D1]">
                            <x-hi name="shield" width="19" height="19" />
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[14px] font-extrabold">{{ $claim->warga->nama }}</p>
                            <p class="mt-0.5 font-mono text-[12px] text-slate-500">NIK {{ $claim->warga->nik }} • KK {{ $claim->warga->keluarga?->no_kk }}</p>
                            <p class="mt-0.5 text-[12px] text-slate-400">Diajukan oleh {{ $claim->user->name }} ({{ $claim->user->email }}) • {{ $claim->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <ul class="mt-3 flex flex-wrap gap-1.5">
                        @foreach ($claim->checks as $check)
                            <li class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1 text-[11px] font-bold {{ $check->passed ? 'bg-[#ECFDF5] text-[#047857]' : 'bg-rose-50 text-rose-500' }}">
                                <x-hi name="{{ $check->passed ? 'check' : 'x' }}" width="12" height="12" /> {{ $gateLabel[$check->check_type] ?? $check->check_type }}
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-4">
                        <form method="POST" action="{{ route('verifikasi.approve', $claim) }}">
                            @csrf
                            <button type="submit" class="rounded-xl bg-green-600 px-5 py-2 text-[12.5px] font-bold text-white transition hover:bg-green-700">Setujui & Tautkan</button>
                        </form>
                        <form method="POST" action="{{ route('verifikasi.reject', $claim) }}" class="flex min-w-0 flex-1 flex-wrap items-center gap-2">
                            @csrf
                            <input type="text" name="reason" required minlength="10" maxlength="500" placeholder="Alasan penolakan (min. 10 karakter)..." class="min-w-52 flex-1 rounded-xl border border-slate-200 px-4 py-2 text-[12.5px] outline-none placeholder:text-slate-400 focus:border-rose-400">
                            <button type="submit" class="rounded-xl bg-slate-100 px-5 py-2 text-[12.5px] font-bold text-slate-500 transition hover:bg-rose-50 hover:text-rose-600">Tolak</button>
                        </form>
                    </div>
                </article>
            @empty
                <p class="rounded-2xl border border-slate-100 bg-white p-10 text-center text-[13px] text-slate-400">Tidak ada pengajuan menunggu. Semua warga sudah terverifikasi.</p>
            @endforelse
        </div>

        <div class="mt-4 flex flex-wrap items-center gap-3">
            <p class="text-[12px] text-slate-400">Menampilkan {{ $claims->firstItem() ?? 0 }}–{{ $claims->lastItem() ?? 0 }} dari {{ $claims->total() }} data</p>
            <x-per-page :value="request('per_page', 10)" />
            <x-pagination :rows="$claims" />
        </div>
    </main>
</div>
</body>
</html>