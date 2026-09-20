{{-- ===== Dashboard Warga ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php $user = auth()->user(); @endphp
<div class="flex min-h-screen">
    @include('user.partials.sidebar', ['user' => $user, 'active' => 'dashboard'])

    <main class="min-w-0 flex-1 p-7">
        <h1 class="text-2xl font-extrabold tracking-tight">Selamat datang, {{ $user->name }} 👋</h1>
        <p class="mt-1 text-[12px] text-slate-500">Berikut ringkasan aktivitas dan lingkungan RT 04 / RW 02.</p>

        <div class="mt-5 grid grid-cols-2 gap-4 xl:grid-cols-4">
            <x-stat-card label="Tagihan Iuran" :value="$tagihan.' tunggakan'" sub="Periode September 2026" icon="currency" tone="text-amber-600 bg-amber-50" />
            <x-stat-card label="Surat Aktif" :value="$suratAktif" sub="Menunggu / diproses" icon="doc" tone="text-[#047857] bg-[#ECFDF5]" />
            <x-stat-card label="Aduan Aktif" :value="$aduanAktif" sub="Belum / proses" icon="chat" tone="text-[#2563EB] bg-[#EFF6FF]" />
            <x-stat-card label="Surat Selesai" :value="$suratSelesai" sub="Siap diambil" icon="check" tone="text-purple-600 bg-purple-50" />
        </div>

        <div class="mt-5 grid gap-5 xl:grid-cols-2">
            <section class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <div class="flex items-center">
                    <h2 class="text-[14px] font-extrabold">Kegiatan Mendatang</h2>
                    <a href="{{ route('saya.kegiatan') }}" class="ml-auto text-[12px] font-bold text-[#047857]">Lihat semua</a>
                </div>
                <ul class="mt-3 space-y-2.5">
                    @forelse ($agenda as $k)
                        <li class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 flex-col items-center justify-center rounded-xl bg-[#ECFDF5] leading-none"><span class="text-[13px] font-extrabold text-[#047857]">{{ $k->tanggal->format('d') }}</span><span class="text-[8px] font-bold text-[#047857]">{{ strtoupper($k->tanggal->translatedFormat('M')) }}</span></span>
                            <div class="min-w-0"><p class="truncate text-[12.5px] font-bold">{{ $k->judul }}</p><p class="text-[11px] text-slate-400">{{ $k->tanggal->translatedFormat('l, d M Y') }}</p></div>
                        </li>
                    @empty
                        <li class="py-4 text-center text-[12px] text-slate-400">Tidak ada agenda mendatang.</li>
                    @endforelse
                </ul>
            </section>
            <section class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <div class="flex items-center">
                    <h2 class="text-[14px] font-extrabold">Pengumuman Terbaru</h2>
                    <a href="{{ route('saya.pengumuman') }}" class="ml-auto text-[12px] font-bold text-[#047857]">Lihat semua</a>
                </div>
                <ul class="mt-3 space-y-2.5">
                    @forelse ($pengumuman as $a)
                        <li class="rounded-xl border border-slate-100 p-3">
                            <p class="text-[12.5px] font-bold">{{ $a->judul }}</p>
                            <p class="mt-0.5 line-clamp-1 text-[11.5px] text-slate-500">{{ $a->ringkasan }}</p>
                            <p class="mt-1 text-[10.5px] text-slate-400">{{ $a->published_at?->translatedFormat('d M Y') }}</p>
                        </li>
                    @empty
                        <li class="py-4 text-center text-[12px] text-slate-400">Belum ada pengumuman.</li>
                    @endforelse
                </ul>
            </section>
        </div>
    </main>
</div>
</body>
</html>
