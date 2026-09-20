{{-- Panel kiri halaman auth: gambar latar dari public/images/auth/bg-login.jpg
     (simpan file referensi ke path itu). Tanpa file: gradasi polos. --}}
@php $bgAuth = file_exists(public_path('images/auth/bg-login.jpg')) ? asset('images/auth/bg-login.jpg') : ''; @endphp
<div class="relative hidden overflow-hidden bg-[#DCEBFF] lg:block">
    @if($bgAuth !== '')
        <img src="{{ $bgAuth }}" alt="Ilustrasi lingkungan RT" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-white/25 via-transparent to-transparent"></div>
    @else
        <div class="absolute inset-0" style="background: linear-gradient(180deg, #DCEBFF 0%, #EFF6FF 55%, #D8E9FF 100%);"></div>
    @endif

    <div class="relative flex h-full flex-col justify-between p-12">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/landing/logo.png') }}" alt="Logo Smart RT" class="h-12 w-12 object-contain">
            <span class="leading-tight">
                <span class="block text-[26px] font-extrabold tracking-tight"><span class="text-slate-900">Smart</span> <span class="text-cobalt-600">RT</span></span>
                <span class="block text-[12px] font-medium text-slate-500">Manajemen RT Digital</span>
            </span>
        </div>

        <div>
            <h2 class="max-w-md text-[32px] leading-tight font-extrabold tracking-tight text-[#0F2A5C]">Bersama Membangun Lingkungan RT yang Lebih Baik</h2>
            <p class="mt-3 max-w-md text-[14px] leading-relaxed text-slate-500">Kelola data warga, administrasi, iuran, dan berbagai kegiatan RT dengan lebih mudah, cepat dan efisien.</p>
            <div class="mt-8 grid max-w-lg grid-cols-4 gap-4">
                @foreach ([['users', 'Data Warga Terpadu'], ['doc', 'Administrasi Mudah'], ['currency', 'Iuran & Keuangan Transparan'], ['cal', 'Informasi Kegiatan Terkini']] as [$icon, $label])
                    <div class="text-center">
                        <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-white text-cobalt-600 shadow-md">
                            <x-hi :name="$icon" width="20" height="20" />
                        </span>
                        <p class="mt-2 text-[11px] leading-snug font-semibold text-slate-700">{{ $label }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <p class="text-[12px] font-medium text-slate-400">Portal Resmi RT &bull; Aman &amp; Terpercaya</p>
    </div>
</div>