{{-- ===== Administrasi Surat (Admin) — Figma Smart-RT node 6:1449 ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrasi Surat — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f3f5fb] font-sans text-slate-900 antialiased">
@php
    $user = auth()->user();
    $badge = ['Menunggu' => 'bg-amber-50 text-amber-600', 'Diproses' => 'bg-cobalt-50 text-cobalt-600', 'Selesai' => 'bg-forest-50 text-forest-600', 'Ditolak' => 'bg-rose-50 text-rose-500'];
@endphp
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['user' => $user, 'active' => 'surat'])

    <main class="min-w-0 flex-1 px-5 py-5 sm:px-7">
        <x-page-header title="Administrasi Surat" subtitle="Kelola pengajuan dan arsip surat warga RT 04 / RW 02. Klik baris untuk melihat isi surat.">
            <a href="{{ route('surat.semua') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[12.5px] font-bold text-slate-600 transition hover:border-slate-300">Pengajuan Masuk</a>
            <a href="{{ route('surat.pengajuan') }}" class="inline-flex items-center gap-2 rounded-xl bg-cobalt-600 px-4 py-2.5 text-[12.5px] font-bold text-white transition hover:bg-cobalt-700">
                <span class="text-base leading-none">+</span>
                Buat Surat
            </a>
        </x-page-header>

        @if (session('success'))
            <p class="mt-4 rounded-xl bg-green-50 px-4 py-3 text-[13px] font-medium text-green-700">{{ session('success') }}</p>
        @endif

        <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-stat-card label="Pengajuan Masuk" :value="$masuk" sub="Perlu diproses" icon="doc" tone="text-amber-500 bg-amber-50" />
            <x-stat-card label="Sedang Diproses" :value="$diproses" sub="Dalam pengerjaan" icon="clock" tone="text-cobalt-600 bg-cobalt-50" />
            <x-stat-card label="Selesai" :value="$selesai" sub="Surat terbit" icon="check" tone="text-forest-600 bg-forest-50" />
            <x-stat-card label="Ditolak" :value="$ditolak" sub="Tidak memenuhi syarat" icon="x" tone="text-rose-500 bg-rose-50" />
        </div>

        <section class="mt-4 rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_10px_30px_-18px_rgba(15,23,42,0.25)]">
            <div class="flex flex-wrap items-center gap-3">
                <x-search-bar placeholder="Cari surat / pemohon..." />
                <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[12.5px] font-bold text-slate-600 transition hover:border-slate-300">
                    <x-hi name="funnel" width="14" height="14" />
                    Filter Lainnya
                </button>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-[12.5px]">
                    <thead>
                        <tr class="border-y border-slate-100 text-[10.5px] font-bold tracking-[0.08em] text-slate-400">
                            <th class="py-3 pr-4">JENIS SURAT</th>
                            <th class="py-3 pr-4">PEMOHON</th>
                            <th class="py-3 pr-4">TANGGAL</th>
                            <th class="py-3 pr-4">STATUS</th>
                            <th class="py-3">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($rows as $surat)
                            <tr class="cursor-pointer transition hover:bg-slate-50/60"
                                data-row-open
                                data-jenis="{{ $surat->jenis }}"
                                data-no="{{ $surat->no_surat ?? '—' }}"
                                data-pemohon="{{ $surat->pemohon }}"
                                data-blok="{{ $surat->blok ?? '—' }}"
                                data-tanggal="{{ $surat->created_at->translatedFormat('d M Y, H:i') }}"
                                data-status="{{ $surat->status }}"
                                data-lampiran="{{ $surat->lampiran ?? '—' }}"
                                data-catatan="{{ strip_tags($surat->catatan ?? '—') }}">
                                <td class="py-3 pr-4 font-bold">{{ $surat->jenis }}</td>
                                <td class="py-3 pr-4 text-slate-500">{{ $surat->pemohon }}{{ $surat->blok ? ' ('.$surat->blok.')' : '' }}</td>
                                <td class="py-3 pr-4 whitespace-nowrap text-slate-500">{{ $surat->created_at->translatedFormat('d M Y') }}</td>
                                <td class="py-3 pr-4"><span class="inline-flex rounded-lg px-2.5 py-1 text-[11px] font-bold {{ $badge[$surat->status] }}">{{ $surat->status }}</span></td>
                                <td class="py-3">
                                    <div class="flex items-center gap-1.5">
                                        <button type="button" class="rounded-lg bg-cobalt-600 px-3 py-1.5 text-[11px] font-bold text-white transition hover:bg-cobalt-700">Proses</button>
                                        @if ($surat->status === 'Menunggu')
                                            <button type="button" data-reject-open data-id="{{ $surat->id }}" data-pemohon="{{ $surat->pemohon }}" class="rounded-lg bg-rose-500 px-3 py-1.5 text-[11px] font-bold text-white transition hover:bg-rose-600">Tolak</button>
                                        @endif
                                        <span class="group relative inline-flex">
                                            <button type="button"
                                                data-download
                                                data-jenis="{{ $surat->jenis }}"
                                                data-no="{{ $surat->no_surat ?? '—' }}"
                                                data-pemohon="{{ $surat->pemohon }}"
                                                data-blok="{{ $surat->blok ?? '—' }}"
                                                data-tanggal="{{ $surat->created_at->translatedFormat('d M Y, H:i') }}"
                                                data-status="{{ $surat->status }}"
                                                title="Download"
                                                class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-cobalt-500 hover:text-cobalt-600">
                                                <x-hi name="download" width="15" height="15" />
                                            </button>
                                            <span class="pointer-events-none absolute -top-8 left-1/2 -translate-x-1/2 rounded-md bg-slate-900 px-2 py-1 text-[10px] font-semibold whitespace-nowrap text-white opacity-0 transition group-hover:opacity-100">Download</span>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-8 text-center text-[13px] text-slate-400">Belum ada pengajuan surat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex flex-wrap items-center gap-3">
                <p class="text-[12px] text-slate-400">Menampilkan {{ $rows->count() }} pengajuan (Menunggu teratas)</p>
            </div>
        </section>
    </main>
</div>

{{-- Modal isi surat singkat --}}
<div id="suratModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-[2px]">
    <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Isi Surat</p>
                <h2 id="mJenis" class="text-[15px] font-extrabold"></h2>
            </div>
            <button type="button" data-modal-close title="Tutup" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                <x-hi name="x" width="16" height="16" />
            </button>
        </div>
        <dl class="space-y-2.5 px-5 py-4 text-[12.5px]">
            <div class="flex justify-between gap-4"><dt class="text-slate-400">No. Surat</dt><dd id="mNo" class="font-bold"></dd></div>
            <div class="flex justify-between gap-4"><dt class="text-slate-400">Pemohon</dt><dd id="mPemohon" class="text-right font-semibold"></dd></div>
            <div class="flex justify-between gap-4"><dt class="text-slate-400">Blok</dt><dd id="mBlok" class="font-semibold"></dd></div>
            <div class="flex justify-between gap-4"><dt class="text-slate-400">Tanggal</dt><dd id="mTanggal" class="font-semibold"></dd></div>
            <div class="flex justify-between gap-4"><dt class="text-slate-400">Status</dt><dd id="mStatus" class="rounded-lg bg-slate-100 px-2 py-0.5 text-[11px] font-bold"></dd></div>
            <div class="flex justify-between gap-4"><dt class="text-slate-400">Lampiran</dt><dd id="mLampiran" class="text-right font-semibold"></dd></div>
            <div class="border-t border-slate-50 pt-2.5"><dt class="text-slate-400">Catatan</dt><dd id="mCatatan" class="mt-1 text-slate-600"></dd></div>
        </dl>
    </div>
</div>

{{-- Modal tolak pengajuan --}}
<div id="tolakModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-[2px]">
    <div class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-rose-500 uppercase">Tolak Pengajuan</p>
                <h2 class="text-[15px] font-extrabold">Alasan penolakan surat</h2>
            </div>
            <button type="button" data-tolak-close title="Tutup" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                <x-hi name="x" width="16" height="16" />
            </button>
        </div>
        <form id="tolakForm" method="POST" class="space-y-4 px-5 py-4">
            @csrf
            <label class="block">
                <span class="mb-1.5 block text-[12px] font-bold text-slate-600">Nama Peminta Surat</span>
                <input type="text" id="tPemohon" readonly class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-[13px] text-slate-500 outline-none">
            </label>
            <label class="block">
                <span class="mb-1.5 block text-[12px] font-bold text-slate-600">Nama Pengurus</span>
                <input type="text" value="{{ $user->name }}" readonly class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-[13px] text-slate-500 outline-none">
            </label>
            <div>
                <span class="mb-1.5 block text-[12px] font-bold text-slate-600">Alasan Tolak</span>
                <div class="overflow-hidden rounded-xl border border-slate-200 focus-within:border-rose-400">
                    <div class="flex items-center gap-1 border-b border-slate-100 bg-slate-50/60 px-2 py-1.5">
                        <button type="button" data-cmd="bold" title="Tebal" class="rounded px-2 py-1 text-[13px] font-extrabold text-slate-600 transition hover:bg-slate-200">B</button>
                        <button type="button" data-cmd="italic" title="Miring" class="rounded px-2 py-1 text-[13px] font-bold text-slate-600 italic transition hover:bg-slate-200">I</button>
                        <button type="button" data-cmd="underline" title="Garis bawah" class="rounded px-2 py-1 text-[13px] font-bold text-slate-600 underline transition hover:bg-slate-200">U</button>
                    </div>
                    <div id="tEditor" contenteditable="true" class="min-h-28 px-4 py-2.5 text-[13px] outline-none empty:before:text-slate-400 empty:before:content-['Tulis_alasan_penolakan...']"></div>
                </div>
                <input type="hidden" name="alasan" id="tAlasan">
                <p id="tError" class="mt-1 hidden text-[12px] font-medium text-rose-500">Alasan tolak wajib diisi (min. 10 karakter).</p>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" data-tolak-close class="rounded-xl border border-slate-200 px-5 py-2.5 text-[12.5px] font-bold text-slate-600 transition hover:border-slate-300">Batal</button>
                <button type="submit" class="rounded-xl bg-rose-500 px-5 py-2.5 text-[12.5px] font-bold text-white transition hover:bg-rose-600">Tolak Pengajuan</button>
            </div>
        </form>
    </div>
</div>

<script>
    (() => {
        const modal = document.getElementById('suratModal');
        const fillDetail = (d) => {
            document.getElementById('mJenis').textContent = d.jenis;
            document.getElementById('mNo').textContent = d.no;
            document.getElementById('mPemohon').textContent = d.pemohon;
            document.getElementById('mBlok').textContent = d.blok;
            document.getElementById('mTanggal').textContent = d.tanggal;
            document.getElementById('mStatus').textContent = d.status;
            document.getElementById('mLampiran').textContent = d.lampiran;
            document.getElementById('mCatatan').textContent = d.catatan;
        };
        const openDetail = () => { modal.classList.remove('hidden'); modal.classList.add('flex'); };
        const closeDetail = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); };

        // Klik baris mana pun membuka modal isi surat
        document.querySelectorAll('[data-row-open]').forEach(row => {
            row.addEventListener('click', (e) => {
                if (e.target.closest('button, a')) return;
                fillDetail(row.dataset);
                openDetail();
            });
        });
        modal.querySelector('[data-modal-close]').addEventListener('click', closeDetail);
        modal.addEventListener('click', e => { if (e.target === modal) closeDetail(); });

        // Modal tolak
        const tolakModal = document.getElementById('tolakModal');
        const tolakForm = document.getElementById('tolakForm');
        const editor = document.getElementById('tEditor');
        const openTolak = (btn) => {
            document.getElementById('tPemohon').value = btn.dataset.pemohon;
            tolakForm.action = `/administrasi-surat/${btn.dataset.id}/tolak`;
            editor.innerHTML = '';
            document.getElementById('tError').classList.add('hidden');
            tolakModal.classList.remove('hidden');
            tolakModal.classList.add('flex');
        };
        const closeTolak = () => { tolakModal.classList.add('hidden'); tolakModal.classList.remove('flex'); };
        document.querySelectorAll('[data-reject-open]').forEach(b => b.addEventListener('click', (e) => { e.stopPropagation(); openTolak(b); }));
        tolakModal.querySelectorAll('[data-tolak-close]').forEach(b => b.addEventListener('click', closeTolak));
        tolakModal.addEventListener('click', e => { if (e.target === tolakModal) closeTolak(); });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeDetail(); closeTolak(); } });

        // Toolbar rich editor sederhana
        tolakModal.querySelectorAll('[data-cmd]').forEach(b => b.addEventListener('click', () => {
            editor.focus();
            document.execCommand(b.dataset.cmd, false, null);
        }));
        tolakForm.addEventListener('submit', (e) => {
            const text = editor.innerText.trim();
            if (text.length < 10) {
                e.preventDefault();
                document.getElementById('tError').classList.remove('hidden');
                return;
            }
            document.getElementById('tAlasan').value = editor.innerHTML;
        });

        // Download ringkasan surat sebagai .txt
        document.querySelectorAll('[data-download]').forEach(b => b.addEventListener('click', (e) => {
            e.stopPropagation();
            const d = b.dataset;
            const text = `SMART RT 04 / RW 02\n${d.jenis}\nNo: ${d.no}\nPemohon: ${d.pemohon}\nBlok: ${d.blok}\nTanggal: ${d.tanggal}\nStatus: ${d.status}\n`;
            const a = document.createElement('a');
            a.href = URL.createObjectURL(new Blob([text], { type: 'text/plain' }));
            a.download = `surat-${(d.no || 'pengajuan').replace(/[^A-Za-z0-9-]+/g, '-')}.txt`;
            a.click();
            URL.revokeObjectURL(a.href);
        }));
    })();
</script>
</body>
</html>
