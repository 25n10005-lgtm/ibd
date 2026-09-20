{{-- ===== Daftar Pengurus (Super Admin) — seluruh admin terdaftar beserta role ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Pengurus — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php
    $user = auth()->user();
    $roleTone = ['Pengelola' => 'bg-[#EFF6FF] text-[#2863D1]', 'Ketua RT' => 'bg-green-50 text-green-700', 'Developer' => 'bg-purple-50 text-purple-700'];
    $statusLabel = ['active' => 'Aktif', 'pending' => 'Menunggu', 'rejected' => 'Ditolak'];
    $statusTone = ['Aktif' => 'bg-green-50 text-green-700', 'Menunggu' => 'bg-amber-50 text-amber-600', 'Ditolak' => 'bg-rose-50 text-rose-500'];
@endphp
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['user' => $user, 'active' => 'pengurus-daftar'])

    <main class="min-w-0 flex-1 px-5 py-5 sm:px-7">
        <div class="flex flex-wrap items-center gap-3">
            <div class="mr-auto">
                <p class="text-[11px] font-bold tracking-[0.18em] text-[#2863D1]">SUPER ADMIN</p>
                <h1 class="mt-1 text-xl font-extrabold tracking-tight sm:text-2xl">Daftar Pengurus</h1>
                <p class="mt-0.5 text-[12.5px] text-slate-500">Seluruh admin (pengurus) yang terdaftar beserta perannya.</p>
            </div>
            <a href="{{ route('admin.approvals') }}" class="inline-flex items-center gap-2 rounded-xl border bg-white px-4 py-2.5 text-[12.5px] font-bold text-slate-600">Pengajuan Peran</a>
        </div>

        <div class="mt-5 grid gap-4 sm:grid-cols-3">
            @foreach ([['Total Pengurus', $pengurus->total(), 'text-[#2863D1] bg-[#EFF6FF]'], ['Aktif', $aktif, 'text-green-700 bg-green-50'], ['Menunggu Persetujuan', $menunggu, 'text-amber-600 bg-amber-50']] as [$l, $v, $tone])
                <div class="flex items-center gap-4 rounded-2xl border bg-white p-5 shadow-sm">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $tone }}">
                        <x-hi name="users" width="19" height="19" />
                    </span>
                    <div><p class="text-[12.5px] font-semibold text-slate-500">{{ $l }}</p><p class="text-[22px] leading-7 font-extrabold tracking-tight">{{ $v }}</p></div>
                </div>
            @endforeach
        </div>

        <section class="mt-4 overflow-hidden rounded-2xl border bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-[12.5px]">
                    <thead>
                        <tr class="border-y border-slate-100 text-[10.5px] font-bold tracking-wider text-slate-400 uppercase">
                            <th class="px-5 py-3">Nama</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3">Role</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Terdaftar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($pengurus as $p)
                            <tr class="transition hover:bg-slate-50/60">
                                <td class="px-5 py-3.5 font-bold">{{ $p->name }}</td>
                                <td class="px-5 py-3.5 text-slate-500">{{ $p->email }}</td>
                                <td class="px-5 py-3.5"><span class="inline-flex rounded-lg px-2.5 py-1 text-[11px] font-bold {{ $roleTone[$p->role->label()] ?? 'bg-slate-100 text-slate-500' }}">{{ $p->role->label() }}</span></td>
                                <td class="px-5 py-3.5"><span class="inline-flex rounded-lg px-2.5 py-1 text-[11px] font-bold {{ $statusTone[$statusLabel[$p->status->value]] ?? 'bg-slate-100 text-slate-500' }}">{{ $statusLabel[$p->status->value] }}</span></td>
                                <td class="px-5 py-3.5 whitespace-nowrap text-slate-500">{{ $p->created_at->translatedFormat('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-8 text-center text-slate-400">Belum ada pengurus terdaftar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex flex-wrap items-center gap-3 border-t p-4">
                <p class="text-[12px] text-slate-400">Menampilkan {{ $pengurus->firstItem() ?? 0 }}–{{ $pengurus->lastItem() ?? 0 }} dari {{ $pengurus->total() }} data</p>
                <x-per-page :value="request('per_page', 10)" />
                <x-pagination :rows="$pengurus" />
            </div>
        </section>
    </main>
</div>
</body>
</html>
