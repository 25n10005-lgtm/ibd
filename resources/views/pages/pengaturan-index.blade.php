{{-- ===== Pengaturan Akun (Admin) — Figma Smart-RT node 24:896 ===== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaturan Akun — Smart RT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8FF] font-sans text-slate-900 antialiased">
@php $user = auth()->user(); @endphp
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['user' => $user, 'active' => 'pengaturan'])

    <main class="min-w-0 flex-1 p-7">
        <h1 class="text-[28px] font-bold tracking-tight text-[#0F172A]">Pengaturan Akun</h1>
        <p class="mt-1 text-[13px] text-slate-500">Kelola informasi profil, pengaturan akun, dan keamanan Anda.</p>

        @if (session('success'))
            <p class="mt-4 rounded-xl bg-green-50 px-4 py-3 text-[13px] font-medium text-green-700">{{ session('success') }}</p>
        @endif
        @if (session('error'))
            <p class="mt-4 rounded-xl bg-rose-50 px-4 py-3 text-[13px] font-medium text-rose-600">{{ session('error') }}</p>
        @endif

        {{-- Tabs --}}
        <div class="mt-5 flex gap-9 overflow-x-auto border-b border-slate-200 text-[14px]">
            @foreach ([['Profil Saya', true], ['Pengaturan', false], ['Notifikasi', false], ['Keamanan', false], ['Lainnya', false]] as [$tab, $on])
                <button type="button" class="flex shrink-0 items-center gap-2 border-b-2 pb-3.5 font-semibold transition {{ $on ? 'border-[#2563EB] text-[#2563EB]' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                    <x-hi name="user" width="15" height="15" />
                    {{ $tab }}
                </button>
            @endforeach
        </div>

        <div class="mt-7 grid gap-7 xl:grid-cols-12">
            {{-- Left: avatar & summary --}}
            <section class="flex flex-col rounded-[19px] border border-slate-200/70 bg-white p-7 xl:col-span-4">
                <h2 class="text-[16px] font-bold text-slate-700">Informasi Profil</h2>
                <div class="relative mx-auto mt-4">
                    <img src="{{ $user->avatar ?? asset('images/landing/logo.png') }}" alt="{{ $user->name }}" class="h-28 w-28 rounded-full border-2 border-white object-cover shadow ring-2 ring-slate-100">
                    <button type="button" title="Ubah foto" class="absolute right-0 bottom-0 flex h-8 w-8 items-center justify-center rounded-full border-2 border-white bg-[#2563EB] text-white shadow">
                        <x-hi name="camera" width="14" height="14" />
                    </button>
                </div>
                <p class="mt-4 text-center text-[19px] font-bold text-[#0F172A]">{{ $user->name }}</p>
                <p class="mt-1 text-center"><span class="rounded-full border border-green-100 bg-[#ECFDF5] px-3.5 py-0.5 text-[13px] font-semibold text-green-800">{{ $user->role?->label() }}</span></p>
                <ul class="mt-6 space-y-5 text-[14px]">
                    <li class="flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-slate-400"><x-hi name="doc" width="15" height="15" /></span>
                        <div><p class="text-[12px] text-slate-400">NIK</p><p class="font-medium text-slate-700">3374041205870001</p></div>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-slate-400"><x-hi name="phone" width="15" height="15" /></span>
                        <div><p class="text-[12px] text-slate-400">No. Telepon</p><p class="font-medium text-slate-700">0812-3456-7890</p></div>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-slate-400"><x-hi name="mail" width="15" height="15" /></span>
                        <div class="min-w-0"><p class="text-[12px] text-slate-400">Email</p><p class="truncate font-medium text-slate-700">{{ $user->email }}</p></div>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-slate-400"><x-hi name="pin" width="15" height="15" /></span>
                        <div><p class="text-[12px] text-slate-400">Alamat</p><p class="font-medium text-slate-700">Jl. Melati No. 12, RT 04 / RW 02</p></div>
                    </li>
                </ul>
                <button type="button" class="mt-7 rounded-[10px] border border-slate-200 py-2.5 text-[14px] font-semibold text-slate-700 transition hover:border-slate-300">Ubah Foto</button>
            </section>

            {{-- Right stack --}}
            <div class="space-y-6 xl:col-span-8">
                <section class="rounded-[19px] border border-slate-200/70 bg-white p-7">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                        <h2 class="text-[16px] font-bold text-slate-700">Informasi Akun</h2>
                        <button type="button" class="rounded-[10px] border border-blue-200 px-3.5 py-1.5 text-[13px] font-semibold text-[#2563EB]">Edit Profil</button>
                    </div>
                    <form class="mt-4 grid grid-cols-2 gap-x-7 gap-y-4">
                        <label class="block"><span class="mb-1 block text-[12px] text-slate-400">Nama Lengkap</span><input type="text" value="{{ $user->name }}" class="w-full rounded-lg border border-slate-200 px-3.5 py-2 text-[14px] font-medium outline-none focus:border-blue-500"></label>
                        <label class="block"><span class="mb-1 block text-[12px] text-slate-400">Jabatan</span><input type="text" value="{{ $user->role?->label() }}" readonly class="w-full rounded-lg border border-slate-100 bg-slate-50 px-3.5 py-2 text-[14px] font-medium text-slate-500 outline-none"></label>
                        <label class="block"><span class="mb-1 block text-[12px] text-slate-400">Username</span><input type="text" value="{{ explode('@', $user->email)[0] }}" class="w-full rounded-lg border border-slate-200 px-3.5 py-2 text-[14px] font-medium outline-none focus:border-blue-500"></label>
                        <label class="block"><span class="mb-1 block text-[12px] text-slate-400">Email</span><input type="email" value="{{ $user->email }}" readonly class="w-full rounded-lg border border-slate-100 bg-slate-50 px-3.5 py-2 text-[14px] font-medium text-slate-500 outline-none"></label>
                    </form>
                </section>

                <section class="rounded-[19px] border border-slate-200/70 bg-white p-7">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                        <h2 class="text-[16px] font-bold text-slate-700">Permohonan ke Admin</h2>
                        @if ($user->requested_role)
                            <span class="rounded-full bg-amber-50 px-3.5 py-1 text-[12px] font-semibold text-amber-700">Menunggu validasi</span>
                        @endif
                    </div>
                    @if ($user->requested_role)
                        <p class="mt-4 text-[14px] text-slate-600">Pengajuan peran <span class="font-bold text-slate-800">{{ $user->requested_role->label() }}</span> sedang menunggu validasi. Akun Anda tetap dapat digunakan seperti biasa.</p>
                    @elseif ($user->hasAtLeastRole(\App\Enums\Role::SuperAdmin))
                        <p class="mt-4 text-[14px] text-slate-600">Anda sudah menjadi Ketua RT — permohonan peran tidak diperlukan.</p>
                        <a href="{{ route('admin.approvals') }}" class="mt-4 inline-block rounded-[10px] border border-blue-200 px-5 py-2 text-[13px] font-semibold text-[#2563EB] transition hover:bg-blue-50">Kelola Antrean Persetujuan</a>
                    @else
                        <p class="mt-4 text-[14px] text-slate-600">Ajukan permohonan menjadi <span class="font-bold text-slate-800">Ketua RT</span>. Pengajuan diteruskan ke antrean persetujuan dan menunggu validasi.</p>
                        <form method="POST" action="{{ route('account.request-role') }}" class="mt-4">
                            @csrf
                            <input type="hidden" name="role" value="super_admin">
                            <button type="submit" class="rounded-[10px] bg-[#2563EB] px-5 py-2.5 text-[13px] font-bold text-white transition hover:bg-blue-700">Ajukan sebagai Ketua RT</button>
                        </form>
                    @endif
                </section>

                <section class="rounded-[19px] border border-slate-200/70 bg-white p-7">
                    <h2 class="text-[16px] font-bold text-slate-700">Pengaturan Akun</h2>
                    <ul class="mt-2 divide-y divide-slate-50 text-[14px]">
                        @foreach ([['Bahasa', 'Bahasa Indonesia'], ['Zona Waktu', 'WIB (GMT+7)'], ['Mode Tampilan', 'Terang']] as [$l, $v])
                            <li class="flex items-center justify-between py-3.5">
                                <span class="font-medium text-slate-700">{{ $l }}</span>
                                <button type="button" class="flex items-center gap-2 text-slate-500">{{ $v }} <span>›</span></button>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <section class="rounded-[19px] border border-slate-200/70 bg-white p-7">
                    <h2 class="text-[16px] font-bold text-slate-700">Keamanan</h2>
                    <form method="POST" action="{{ route('account.password') }}" class="mt-4 grid gap-3 sm:grid-cols-2">
                        @csrf
                        <input type="password" name="current_password" autocomplete="current-password" placeholder="Password saat ini" class="rounded-lg border border-slate-200 px-3.5 py-2 text-[13px] outline-none placeholder:text-slate-400 focus:border-blue-500">
                        <input type="password" name="password" required autocomplete="new-password" placeholder="Password baru (min. 8)" class="rounded-lg border border-slate-200 px-3.5 py-2 text-[13px] outline-none placeholder:text-slate-400 focus:border-blue-500">
                        <input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password baru" class="rounded-lg border border-slate-200 px-3.5 py-2 text-[13px] outline-none placeholder:text-slate-400 focus:border-blue-500">
                        <div><button type="submit" class="rounded-lg bg-slate-900 px-5 py-2 text-[13px] font-bold text-white transition hover:bg-slate-700">Simpan Password</button></div>
                    </form>
                    <ul class="mt-4 space-y-1 text-[14px]">
                        <li class="flex items-center justify-between rounded-[10px] py-3 transition hover:bg-slate-50">
                            <div><p class="font-medium text-slate-700">Aktivitas Login</p><p class="text-[12px] text-slate-400">Lihat perangkat dan lokasi terakhir akun Anda.</p></div><span class="text-slate-300">›</span>
                        </li>
                        <li class="flex items-center justify-between rounded-[10px] py-3 transition hover:bg-slate-50">
                            <div><p class="font-medium text-slate-700">Keluar dari Semua Perangkat</p><p class="text-[12px] text-slate-400">Keluar dari semua perangkat kecuali perangkat ini.</p></div><span class="text-slate-300">›</span>
                        </li>
                    </ul>
                </section>
            </div>
        </div>

        {{-- Danger zone --}}
        <section class="mt-7 flex flex-wrap items-center gap-4 rounded-[19px] border border-red-200 bg-red-50/60 p-5">
            <span class="flex h-11 w-11 items-center justify-center rounded-[14px] bg-red-100 text-red-500">
                <x-hi name="alert" width="20" height="20" />
            </span>
            <div class="mr-auto">
                <p class="text-[14px] font-bold text-slate-800">Hapus Akun</p>
                <p class="text-[12.5px] text-slate-500">Menghapus akun akan menghilangkan seluruh data dan akses secara permanen.</p>
            </div>
            <button type="button" class="rounded-xl border border-red-300 bg-white px-5 py-2.5 text-[12px] font-bold text-red-600 transition hover:bg-red-50">Hapus Akun</button>
        </section>
    </main>
</div>
</body>
</html>
