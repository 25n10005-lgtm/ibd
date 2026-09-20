{{-- ===== Kontak ===== --}}
@extends('layouts.landing')

@section('title', 'Kontak — Smart RT')

@section('content')
@include('landing.partials.navbar')

<section class="bg-white">
    <div class="mx-auto max-w-[1240px] px-6 pt-14 pb-20 lg:px-8 lg:pt-20 lg:pb-28">
        <div class="mb-10 text-center">
            <p data-reveal class="text-[13px] font-bold tracking-[0.18em] text-cobalt-600">KONTAK KAMI</p>
            <h1 data-reveal data-delay="80" class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Hubungi Kami</h1>
            <p data-reveal data-delay="140" class="mx-auto mt-2 max-w-[620px] text-[15px] leading-relaxed text-slate-500">Ada pertanyaan, saran, atau butuh bantuan? Silakan hubungi pengurus RT melalui informasi di bawah atau kirim pesan langsung.</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Info kontak + peta --}}
            <div data-reveal="left" class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm sm:p-8">
                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <p class="text-[14px] font-bold text-slate-900">Alamat</p>
                        <p class="mt-1 text-[13.5px] leading-relaxed text-slate-500">Jl. Kebersamaan No. 10, Pamulang Barat, Tangerang Selatan</p>
                    </div>
                    <div>
                        <p class="text-[14px] font-bold text-slate-900">Telepon</p>
                        <p class="mt-1 text-[13.5px] text-slate-500">(021) 1234-5678</p>
                    </div>
                    <div>
                        <p class="text-[14px] font-bold text-slate-900">Email</p>
                        <p class="mt-1 text-[13.5px] text-slate-500">info@smartrt.id</p>
                    </div>
                    <div>
                        <p class="text-[14px] font-bold text-slate-900">Jam kerja</p>
                        <p class="mt-1 text-[13.5px] text-slate-500">Senin–Jumat: 08:00 – 16:00 WITA</p>
                    </div>
                </div>
                <div class="mt-6 border-t border-slate-100 pt-6">
                    <p class="mb-3 text-[14px] font-bold text-slate-900">Lokasi Kami</p>
                    <div class="overflow-hidden rounded-xl border border-slate-200">
                        <iframe src="https://www.google.com/maps?q=Pamulang+Barat,+Tangerang+Selatan&output=embed" width="100%" height="280" style="border:0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi Smart RT"></iframe>
                    </div>
                </div>
            </div>

            {{-- Form pesan --}}
            <div data-reveal="right" class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm sm:p-8">
                <form id="kontak-form" class="space-y-4">
                    <input required name="nama" placeholder="Nama lengkap" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 transition focus:border-cobalt-500 focus:outline-none focus:ring-4 focus:ring-cobalt-500/10">
                    <input required type="email" name="email" placeholder="Email" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 transition focus:border-cobalt-500 focus:outline-none focus:ring-4 focus:ring-cobalt-500/10">
                    <input name="subjek" placeholder="Subjek" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 transition focus:border-cobalt-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-cobalt-500/10">
                    <textarea name="pesan" required rows="5" placeholder="Pesan" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 transition focus:border-cobalt-500 focus:outline-none focus:ring-4 focus:ring-cobalt-500/10"></textarea>
                    <p id="kontak-success" class="hidden rounded-xl bg-forest-50 px-4 py-3 text-[13px] font-medium text-forest-600">Pesan Anda terkirim. Pengurus RT akan menghubungi Anda kembali.</p>
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-cobalt-600 px-8 py-3.5 text-sm font-bold text-white shadow-lg shadow-cobalt-600/25 transition-all duration-300 hover:-translate-y-0.5 hover:bg-cobalt-700 active:scale-[0.98]">Kirim Pesan <x-hi name="mail" width="15" height="15" /></button>
                </form>
            </div>
        </div>
    </div>
</section>

@include('landing.partials.footer')

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('kontak-form');
        const success = document.getElementById('kontak-success');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            // TODO: kirim ke backend saat endpoint tersedia
            success.classList.remove('hidden');
            form.querySelectorAll('input, textarea').forEach((el) => { el.value = ''; });
            setTimeout(() => success.classList.add('hidden'), 6000);
        });
    });
</script>
@endsection
