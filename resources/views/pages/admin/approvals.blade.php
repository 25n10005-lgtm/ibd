{{-- ===== Persetujuan Peran (Super Admin) — kenaikan Warga → Admin ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Persetujuan — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php $user = auth()->user(); @endphp
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['user' => $user, 'active' => 'pengurus-pengajuan'])

    <main class="min-w-0 flex-1 px-5 py-5 sm:px-7">
        <div class="mr-auto">
            <p class="text-[11px] font-bold tracking-[0.18em] text-[#2863D1]">SUPER ADMIN</p>
            <h1 class="mt-1 text-xl font-extrabold tracking-tight sm:text-2xl">Persetujuan Peran</h1>
            <p class="mt-0.5 text-[12.5px] text-slate-500">Setujui kenaikan akses warga menjadi admin / pengelola.</p>
        </div>

        @if (session('success'))
            <p class="mt-4 rounded-xl bg-green-50 px-4 py-3 text-[13px] font-medium text-green-700">{{ session('success') }}</p>
        @endif
        @if (session('error'))
            <p class="mt-4 rounded-xl bg-rose-50 px-4 py-3 text-[13px] font-medium text-rose-600">{{ session('error') }}</p>
        @endif

        <section class="mt-4 overflow-hidden rounded-2xl border bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-[13px]">
                    <thead>
                        <tr class="bg-slate-50/60 text-[11px] font-bold tracking-wider text-slate-400 uppercase">
                            <th class="px-5 py-3">Nama</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3">Meminta</th>
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($pending as $u)
                            <tr class="transition hover:bg-slate-50/60">
                                <td class="px-5 py-3.5 font-bold">{{ $u->name }}</td>
                                <td class="px-5 py-3.5 text-slate-500">{{ $u->email }}</td>
                                <td class="px-5 py-3.5"><span class="rounded-lg bg-[#EFF6FF] px-2.5 py-1 text-[12px] font-bold text-[#2863D1]">{{ $u->requested_role?->label() }}</span></td>
                                <td class="px-5 py-3.5 whitespace-nowrap text-slate-500">{{ $u->created_at->translatedFormat('d M Y') }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="flex justify-end gap-2">
                                        <form method="POST" action="{{ route('admin.approvals.approve', $u) }}">
                                            @csrf
                                            <button type="submit" class="rounded-lg bg-green-600 px-4 py-1.5 text-[12px] font-bold text-white transition hover:bg-green-700">Setujui</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.approvals.reject', $u) }}">
                                            @csrf
                                            <button type="submit" class="rounded-lg bg-slate-100 px-4 py-1.5 text-[12px] font-bold text-slate-500 transition hover:bg-rose-50 hover:text-rose-600">Tolak</button>
                                        </form>
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-10 text-center text-slate-400">Tidak ada pengajuan menunggu.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="mt-4 flex flex-wrap items-center gap-3">
            <p class="text-[12px] text-slate-400">Menampilkan {{ $pending->firstItem() ?? 0 }}–{{ $pending->lastItem() ?? 0 }} dari {{ $pending->total() }} data</p>
            <x-per-page :value="request('per_page', 10)" />
            <x-pagination :rows="$pending" />
        </div>
    </main>
</div>
</body>
</html>
