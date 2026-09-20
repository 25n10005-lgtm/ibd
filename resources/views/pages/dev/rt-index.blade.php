{{-- ===== Manajemen RT (Developer) ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen RT — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php $user = auth()->user(); @endphp
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['user' => $user, 'active' => 'rt'])

    <main class="min-w-0 flex-1 p-7">
        <x-page-header title="Manajemen RT" subtitle="Multi-tenant: tiap RT punya data dan pengurus sendiri.">
            <button type="button" data-add-open class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-[12.5px] font-bold text-white transition hover:bg-slate-700">
                <span class="text-base leading-none">+</span>
                Tambah RT
            </button>
        </x-page-header>

        @if (session('success'))
            <p class="mt-4 rounded-xl bg-green-50 px-4 py-3 text-[13px] font-medium text-green-700">{{ session('success') }}</p>
        @endif
        @if ($errors->any())
            <p class="mt-4 rounded-xl bg-rose-50 px-4 py-3 text-[13px] font-medium text-rose-600">{{ $errors->first() }}</p>
        @endif

        <section class="mt-5 overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-[12.5px]">
                    <thead>
                        <tr class="border-y border-slate-100 text-[10.5px] font-bold tracking-wider text-slate-400 uppercase">
                            <th class="px-5 py-3">Kode</th>
                            <th class="px-5 py-3">RT</th>
                            <th class="px-5 py-3">Alamat</th>
                            <th class="px-5 py-3">Pengurus</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($rts as $rt)
                            <tr class="transition hover:bg-slate-50/60">
                                <td class="px-5 py-3.5 font-bold">{{ $rt->kode }}</td>
                                <td class="px-5 py-3.5">{{ $rt->label() }}</td>
                                <td class="px-5 py-3.5 text-slate-500">{{ $rt->alamat ?? '—' }}</td>
                                <td class="px-5 py-3.5 font-bold">{{ $rt->users_count }} <span class="font-medium text-slate-400">akun</span></td>
                                <td class="px-5 py-3.5"><span class="rounded-lg px-2.5 py-1 text-[11px] font-bold {{ $rt->aktif ? 'bg-[#ECFDF5] text-[#047857]' : 'bg-slate-100 text-slate-500' }}">{{ $rt->aktif ? 'Aktif' : 'Nonaktif' }}</span></td>
                                <td class="px-5 py-3.5 text-right"><a href="{{ route('dev.rt.show', $rt) }}" class="text-[12px] font-bold text-[#2563EB]">Kelola →</a></td>
                            </tr>
                        @empty
                            <x-empty-state message="Belum ada RT." :colspan="6" />
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex flex-wrap items-center gap-3 border-t p-4">
                <p class="text-[12px] text-slate-400">Menampilkan {{ $rts->firstItem() ?? 0 }}–{{ $rts->lastItem() ?? 0 }} dari {{ $rts->total() }} data</p>
                <x-pagination :rows="$rts" />
            </div>
        </section>
    </main>
</div>

<div id="addModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <h2 class="text-[15px] font-extrabold">Tambah RT</h2>
            <button type="button" data-add-close class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100"><x-hi name="x" width="16" height="16" /></button>
        </div>
        <form method="POST" action="{{ route('dev.rt.store') }}" class="space-y-4 px-5 py-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <label class="block"><span class="mb-1.5 block text-[12px] font-bold text-slate-600">Kode</span><input type="text" name="kode" required maxlength="20" placeholder="RT05" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] outline-none"></label>
                <label class="block"><span class="mb-1.5 block text-[12px] font-bold text-slate-600">RW</span><input type="text" name="rw" maxlength="10" placeholder="RW 02" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] outline-none"></label>
            </div>
            <label class="block"><span class="mb-1.5 block text-[12px] font-bold text-slate-600">Nama</span><input type="text" name="nama" required maxlength="100" placeholder="RT 05" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] outline-none"></label>
            <label class="block"><span class="mb-1.5 block text-[12px] font-bold text-slate-600">Alamat</span><input type="text" name="alamat" maxlength="255" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] outline-none"></label>
            <div class="flex justify-end gap-2">
                <button type="button" data-add-close class="rounded-xl border border-slate-200 px-5 py-2.5 text-[12.5px] font-bold text-slate-600">Batal</button>
                <button type="submit" class="rounded-xl bg-slate-900 px-5 py-2.5 text-[12.5px] font-bold text-white">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
    (() => {
        const m = document.getElementById('addModal');
        document.querySelectorAll('[data-add-open]').forEach(b => b.addEventListener('click', () => { m.classList.remove('hidden'); m.classList.add('flex'); }));
        m.querySelectorAll('[data-add-close]').forEach(b => b.addEventListener('click', () => { m.classList.add('hidden'); m.classList.remove('flex'); }));
        m.addEventListener('click', e => { if (e.target === m) { m.classList.add('hidden'); m.classList.remove('flex'); } });
    })();
</script>
</body>
</html>
