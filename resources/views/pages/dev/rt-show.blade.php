{{-- ===== Kelola RT + Otorisasi (Developer) ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola {{ $rt->label() }} — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php $user = auth()->user(); @endphp
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['user' => $user, 'active' => 'rt'])

    <main class="min-w-0 flex-1 p-7">
        <x-page-header title="Kelola {{ $rt->label() }}" subtitle="Kode {{ $rt->kode }} • {{ $rt->users_count ?? $rt->users->count() }} akun terhubung.">
            <a href="{{ route('dev.rt.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[12.5px] font-bold text-slate-600">← Semua RT</a>
        </x-page-header>

        @if (session('success'))
            <p class="mt-4 rounded-xl bg-green-50 px-4 py-3 text-[13px] font-medium text-green-700">{{ session('success') }}</p>
        @endif
        @if ($errors->any())
            <p class="mt-4 rounded-xl bg-rose-50 px-4 py-3 text-[13px] font-medium text-rose-600">{{ $errors->first() }}</p>
        @endif

        <div class="mt-5 grid gap-4 xl:grid-cols-2">
            {{-- Profil RT --}}
            <section class="h-fit rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <h2 class="text-[14.5px] font-extrabold">Profil RT</h2>
                <form method="POST" action="{{ route('dev.rt.update', $rt) }}" class="mt-4 grid gap-3 sm:grid-cols-2">
                    @csrf
                    @method('PUT')
                    <label class="block"><span class="mb-1.5 block text-[12px] font-bold text-slate-600">Nama</span><input type="text" name="nama" value="{{ $rt->nama }}" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] outline-none"></label>
                    <label class="block"><span class="mb-1.5 block text-[12px] font-bold text-slate-600">RW</span><input type="text" name="rw" value="{{ $rt->rw }}" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] outline-none"></label>
                    <label class="block sm:col-span-2"><span class="mb-1.5 block text-[12px] font-bold text-slate-600">Alamat</span><input type="text" name="alamat" value="{{ $rt->alamat }}" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] outline-none"></label>
                    <label class="flex items-center gap-2 text-[13px] font-medium text-slate-600"><input type="checkbox" name="aktif" value="1" @checked($rt->aktif) class="h-4 w-4 rounded accent-slate-900"> RT aktif</label>
                    <div class="sm:col-span-2"><button type="submit" class="rounded-xl bg-slate-900 px-6 py-2.5 text-[12.5px] font-bold text-white">Simpan</button></div>
                </form>
            </section>

            {{-- Admin RT ini --}}
            <section class="h-fit rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <h2 class="text-[14.5px] font-extrabold">Admin RT ini</h2>
                <p class="text-[12px] text-slate-500">Admin hanya bisa mengakses RT yang ditetapkan di sini.</p>
                <form method="POST" action="{{ route('dev.rt.assign-admin', $rt) }}" class="mt-3 flex gap-2">
                    @csrf
                    <select name="user_id" required class="min-w-0 flex-1 rounded-xl border border-slate-200 px-3 py-2.5 text-[13px] outline-none">
                        <option value="">— Pilih admin —</option>
                        @foreach ($admins as $a)
                            <option value="{{ $a->id }}">{{ $a->name }} ({{ $a->email }}){{ $a->rt_id === $rt->id ? ' ✓' : '' }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="shrink-0 rounded-xl bg-cobalt-600 px-4 py-2.5 text-[12.5px] font-bold text-white">Tetapkan</button>
                </form>
                <ul class="mt-3 divide-y divide-slate-50">
                    @forelse ($rt->users->where('role', \App\Enums\Role::Admin) as $a)
                        <li class="flex items-center gap-2 py-2 text-[13px]">
                            <span class="font-bold">{{ $a->name }}</span>
                            <span class="text-slate-400">{{ $a->email }}</span>
                            <form method="POST" action="{{ route('dev.rt.unassign-admin', [$rt, $a]) }}" class="ml-auto" onsubmit="return confirm('Cabut akses admin ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[12px] font-bold text-rose-500 hover:text-rose-600">Cabut</button>
                            </form>
                        </li>
                    @empty
                        <li class="py-4 text-center text-[12px] text-slate-400">Belum ada admin untuk RT ini.</li>
                    @endforelse
                </ul>
            </section>

            {{-- Batas Super Admin --}}
            <section class="h-fit rounded-2xl border border-slate-100 bg-white p-5 shadow-sm xl:col-span-2">
                <h2 class="text-[14.5px] font-extrabold">Batas Super Admin (multi-RT)</h2>
                <p class="text-[12px] text-slate-500">Super Admin tanpa batasan bisa mengakses semua RT. Tambahkan RT di sini untuk membatasinya hanya ke RT tertentu.</p>
                <form method="POST" action="{{ route('dev.rt.attach-superadmin', $rt) }}" class="mt-3 flex gap-2 sm:max-w-md">
                    @csrf
                    <select name="user_id" required class="min-w-0 flex-1 rounded-xl border border-slate-200 px-3 py-2.5 text-[13px] outline-none">
                        <option value="">— Pilih super admin —</option>
                        @foreach ($superAdmins as $s)
                            <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->email }})</option>
                        @endforeach
                    </select>
                    <button type="submit" class="shrink-0 rounded-xl bg-cobalt-600 px-4 py-2.5 text-[12.5px] font-bold text-white">Batasi ke RT ini</button>
                </form>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    @forelse ($superAdmins as $s)
                        @php $punya = $s->rts->contains($rt->id); @endphp
                        <div class="flex items-center gap-2 rounded-xl border border-slate-100 p-3 text-[13px]">
                            <div class="min-w-0 flex-1">
                                <p class="font-bold">{{ $s->name }}</p>
                                <p class="truncate text-[11.5px] text-slate-400">
                                    @if ($s->rts->isEmpty() && ! $s->rt_id)
                                        Akses: semua RT
                                    @else
                                        Akses: {{ $s->rts->pluck('kode')->implode(', ') }}{{ $s->rt_id ? ' (+'.$s->rt?->kode.')' : '' }}
                                    @endif
                                </p>
                            </div>
                            @if ($punya)
                                <form method="POST" action="{{ route('dev.rt.detach-superadmin', [$rt, $s]) }}" onsubmit="return confirm('Cabut batas RT ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg bg-slate-100 px-3 py-1.5 text-[11px] font-bold text-slate-500 hover:bg-rose-50 hover:text-rose-600">Lepas</button>
                                </form>
                            @else
                                <span class="rounded-lg bg-slate-50 px-3 py-1.5 text-[11px] font-bold text-slate-400">Tidak dibatasi</span>
                            @endif
                        </div>
                    @empty
                        <p class="col-span-full py-4 text-center text-[12px] text-slate-400">Belum ada super admin.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </main>
</div>
</body>
</html>
