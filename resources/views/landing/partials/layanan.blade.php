{{-- ===== Layanan Section (Impeccable Smart RT Civic Catalog) ===== --}}
<section id="layanan" class="relative mb-4 bg-white pt-14 pb-20 lg:mb-6 lg:pt-20 lg:pb-28">
    <div class="mx-auto max-w-[1240px] px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="mb-10 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <h2 data-reveal class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                    Layanan Warga RT 03/RW 02
                </h2>
                <p data-reveal data-delay="80" class="mt-2 max-w-[620px] text-[15px] leading-relaxed text-slate-500">
                    Akses kebutuhan administrasi surat, iuran kas, fasilitas lingkungan, bantuan darurat, dan kegiatan warga dalam satu genggaman.
                </p>
            </div>

            {{-- Interactive Search Filter Box --}}
            <div data-reveal data-delay="180" class="flex items-center gap-3">
                <div class="relative w-full sm:w-72">
                    <input id="layanan-search" type="text" placeholder="Cari layanan lingkungan..." class="w-full rounded-2xl border border-slate-200 bg-slate-50/90 py-2.5 pr-4 pl-10 text-[13.5px] text-slate-800 placeholder:text-slate-400 transition-all focus:border-cobalt-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-cobalt-500/10">
                    <x-hi name="search" class="absolute top-3 left-3.5 h-4 w-4 text-slate-400" />
                </div>
            </div>
        </div>

        {{-- 14 Illustrated Circular Service Avatars Grid (7 cols desktop x 2 rows) --}}
        <div id="layanan-grid" class="grid grid-cols-2 gap-x-4 gap-y-10 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 lg:gap-x-6 lg:gap-y-12">
            @php
                $layananList = [
                    [
                        'id' => 'darurat',
                        'title' => 'Layanan Darurat & SOS',
                        'sub' => '24 Jam Siaga',
                        'bg' => 'bg-sky-50',
                        'ring' => 'group-hover:ring-rose-400 group-hover:bg-rose-50',
                        'custom_icon' => 'sos'
                    ],
                    [
                        'id' => 'tamu',
                        'title' => 'Lapor Tamu & Warga Baru',
                        'sub' => '1x24 Jam',
                        'bg' => 'bg-sky-50',
                        'ring' => 'group-hover:ring-sky-400 group-hover:bg-sky-100',
                        'custom_icon' => 'visitor'
                    ],
                    [
                        'id' => 'umkm',
                        'title' => 'UMKM & Usaha Warga',
                        'sub' => 'Ekonomi RT',
                        'bg' => 'bg-cyan-50',
                        'ring' => 'group-hover:ring-cyan-400 group-hover:bg-cyan-100',
                        'custom_icon' => 'business'
                    ],
                    [
                        'id' => 'disabilitas',
                        'title' => 'Lansia & Disabilitas',
                        'sub' => 'Layanan Prioritas',
                        'bg' => 'bg-teal-50',
                        'ring' => 'group-hover:ring-teal-400 group-hover:bg-teal-100',
                        'custom_icon' => 'accessibility'
                    ],
                    [
                        'id' => 'administrasi',
                        'title' => 'Administrasi Surat RT',
                        'sub' => 'Domisili, SKCK, dll',
                        'bg' => 'bg-blue-50',
                        'ring' => 'group-hover:ring-blue-400 group-hover:bg-blue-100',
                        'custom_icon' => 'admin_civic'
                    ],
                    [
                        'id' => 'iuran',
                        'title' => 'Iuran & Kas Lingkungan',
                        'sub' => 'Transparan Real-Time',
                        'bg' => 'bg-amber-50',
                        'ring' => 'group-hover:ring-amber-400 group-hover:bg-amber-100',
                        'custom_icon' => 'tax_iuran'
                    ],
                    [
                        'id' => 'edukasi',
                        'title' => 'Edukasi & Pojok Baca',
                        'sub' => 'Bimbel & Literasi',
                        'bg' => 'bg-indigo-50',
                        'ring' => 'group-hover:ring-indigo-400 group-hover:bg-indigo-100',
                        'custom_icon' => 'education'
                    ],
                    [
                        'id' => 'kerja-bakti',
                        'title' => 'Kerja Bakti & Pelatihan',
                        'sub' => 'Gotong Royong RT',
                        'bg' => 'bg-pink-50',
                        'ring' => 'group-hover:ring-pink-400 group-hover:bg-pink-100',
                        'custom_icon' => 'training'
                    ],
                    [
                        'id' => 'kesehatan',
                        'title' => 'Kesehatan & Posyandu',
                        'sub' => 'Balita & Lansia',
                        'bg' => 'bg-blue-50',
                        'ring' => 'group-hover:ring-blue-400 group-hover:bg-blue-100',
                        'custom_icon' => 'health'
                    ],
                    [
                        'id' => 'transportasi',
                        'title' => 'Parkir & Akses Portal',
                        'sub' => 'Mobilitas & Stiker',
                        'bg' => 'bg-sky-50',
                        'ring' => 'group-hover:ring-sky-400 group-hover:bg-sky-100',
                        'custom_icon' => 'transport'
                    ],
                    [
                        'id' => 'kebersihan',
                        'title' => 'Kebersihan & Sampah',
                        'sub' => 'Pemukiman Bersih',
                        'bg' => 'bg-emerald-50',
                        'ring' => 'group-hover:ring-emerald-400 group-hover:bg-emerald-100',
                        'custom_icon' => 'environment'
                    ],
                    [
                        'id' => 'fasilitas',
                        'title' => 'Ruang Terbuka & Fasilitas',
                        'sub' => 'Balai & Tenda Acara',
                        'bg' => 'bg-lime-50',
                        'ring' => 'group-hover:ring-lime-400 group-hover:bg-lime-100',
                        'custom_icon' => 'facility'
                    ],
                    [
                        'id' => 'lainnya',
                        'title' => 'Inventaris & Layanan Lain',
                        'sub' => 'Dasawisma & Aset',
                        'bg' => 'bg-cyan-50',
                        'ring' => 'group-hover:ring-cyan-400 group-hover:bg-cyan-100',
                        'custom_icon' => 'other'
                    ],
                    [
                        'id' => 'pengaduan',
                        'title' => 'Kanal Aspirasi & Lapor',
                        'sub' => 'Suara Warga',
                        'bg' => 'bg-rose-50',
                        'ring' => 'group-hover:ring-rose-400 group-hover:bg-rose-100',
                        'custom_icon' => 'complaint'
                    ],
                ];
            @endphp

            @foreach ($layananList as $index => $item)
            <a href="#fitur" data-layanan-item data-search-term="{{ strtolower($item['title'] . ' ' . $item['sub']) }}" data-reveal data-delay="{{ ($index % 7) * 45 }}" class="group flex flex-col items-center text-center transition-all duration-300">

                {{-- Ikon ilustrasi (sudah termasuk background circle) --}}
                <div class="relative flex h-20 w-20 items-center justify-center transition-all duration-300 group-hover:-translate-y-2 sm:h-24 sm:w-24">

                    @if ($item['custom_icon'] === 'sos')
                        {{-- SOS Icon --}}
                        <div class="relative flex h-full w-full items-center justify-center">
                            <span class="absolute -top-1.5 right-0 rounded-full bg-red-500 px-1.5 py-0.5 text-[9px] font-black tracking-wider text-white shadow">SOS</span>
                            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-red-500 text-white shadow-md transition-transform group-hover:scale-105">
                                <x-hi name="phone" class="h-6 w-6 rotate-[15deg]" />
                            </div>
                        </div>

                    @elseif ($item['custom_icon'] === 'visitor')
                        {{-- Person with Headset --}}
                        <div class="flex h-full w-full items-center justify-center">
                            <svg class="h-20 w-20 drop-shadow transition-transform duration-300 group-hover:scale-110 sm:h-24 sm:w-24" viewBox="0 0 64 64" fill="none">
                                <circle cx="32" cy="32" r="30" fill="#38BDF8" fill-opacity="0.2"/>
                                <circle cx="32" cy="24" r="10" fill="#FCD34D"/>
                                <path d="M18 48c0-7.732 6.268-14 14-14s14 6.268 14 14" fill="#6366F1"/>
                                <path d="M22 24c0-5.523 4.477-10 10-10s10 4.477 10 10" stroke="#3B82F6" stroke-width="2.5" stroke-linecap="round"/>
                                <rect x="40" y="22" width="4" height="7" rx="2" fill="#2563EB"/>
                                <rect x="20" y="22" width="4" height="7" rx="2" fill="#2563EB"/>
                                <path d="M42 27v3a5 5 0 0 1-5 5h-2" stroke="#2563EB" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>

                    @elseif ($item['custom_icon'] === 'business')
                        {{-- Business Chart & Rp --}}
                        <div class="flex h-full w-full items-center justify-center">
                            <svg class="h-20 w-20 drop-shadow transition-transform duration-300 group-hover:scale-110 sm:h-24 sm:w-24" viewBox="0 0 64 64" fill="none">
                                <circle cx="32" cy="32" r="30" fill="#22D3EE" fill-opacity="0.2"/>
                                <rect x="18" y="16" width="28" height="32" rx="4" fill="#6366F1"/>
                                <rect x="21" y="20" width="22" height="24" rx="2" fill="#F8FAFC"/>
                                <path d="M25 36l4-5 4 3 6-8" stroke="#06B6D4" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="39" cy="26" r="2" fill="#06B6D4"/>
                                <rect x="25" y="22" width="14" height="5" rx="1.5" fill="#E2E8F0"/>
                                <text x="27" y="26" font-size="4" font-weight="bold" fill="#475569">Rp</text>
                            </svg>
                        </div>

                    @elseif ($item['custom_icon'] === 'accessibility')
                        {{-- Accessibility Symbol --}}
                        <div class="flex h-full w-full items-center justify-center">
                            <svg class="h-20 w-20 drop-shadow transition-transform duration-300 group-hover:scale-110 sm:h-24 sm:w-24" viewBox="0 0 64 64" fill="none">
                                <circle cx="32" cy="32" r="30" fill="#14B8A6" fill-opacity="0.2"/>
                                <circle cx="34" cy="18" r="4.5" fill="#F59E0B"/>
                                <path d="M34 25v10l7 10M25 40a9 9 0 1 0 9-9h-6" stroke="#F59E0B" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>

                    @elseif ($item['custom_icon'] === 'admin_civic')
                        {{-- Civic Buildings --}}
                        <div class="flex h-full w-full items-center justify-center">
                            <svg class="h-20 w-20 drop-shadow transition-transform duration-300 group-hover:scale-110 sm:h-24 sm:w-24" viewBox="0 0 64 64" fill="none">
                                <circle cx="32" cy="32" r="30" fill="#60A5FA" fill-opacity="0.2"/>
                                <rect x="18" y="26" width="14" height="24" rx="1.5" fill="#93C5FD"/>
                                <rect x="28" y="16" width="18" height="34" rx="2" fill="#3B82F6"/>
                                <rect x="38" y="28" width="12" height="22" rx="1.5" fill="#FCD34D"/>
                                <circle cx="37" cy="22" r="2" fill="#FFFFFF"/>
                                <rect x="32" y="28" width="3" height="3" fill="#FFFFFF" fill-opacity="0.7"/>
                                <rect x="32" y="34" width="3" height="3" fill="#FFFFFF" fill-opacity="0.7"/>
                                <rect x="32" y="40" width="3" height="3" fill="#FFFFFF" fill-opacity="0.7"/>
                            </svg>
                        </div>

                    @elseif ($item['custom_icon'] === 'tax_iuran')
                        {{-- Iuran Envelope --}}
                        <div class="flex h-full w-full items-center justify-center">
                            <svg class="h-20 w-20 drop-shadow transition-transform duration-300 group-hover:scale-110 sm:h-24 sm:w-24" viewBox="0 0 64 64" fill="none">
                                <circle cx="32" cy="32" r="30" fill="#FBBF24" fill-opacity="0.2"/>
                                <path d="M18 24h28v24H18z" fill="#F59E0B"/>
                                <path d="M18 24l14 12 14-12" fill="#FCD34D"/>
                                <rect x="22" y="14" width="20" height="18" rx="2" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5"/>
                                <rect x="25" y="17" width="14" height="4" rx="1" fill="#3B82F6"/>
                                <text x="27" y="20" font-size="3" font-weight="bold" fill="#FFFFFF">IURAN</text>
                                <path d="M25 24h14M25 27h9" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </div>

                    @elseif ($item['custom_icon'] === 'education')
                        {{-- Graduation Toga --}}
                        <div class="flex h-full w-full items-center justify-center">
                            <svg class="h-20 w-20 drop-shadow transition-transform duration-300 group-hover:scale-110 sm:h-24 sm:w-24" viewBox="0 0 64 64" fill="none">
                                <circle cx="32" cy="32" r="30" fill="#818CF8" fill-opacity="0.2"/>
                                <path d="M32 16l16 7-16 7-16-7 16-7z" fill="#1E293B"/>
                                <path d="M22 28v9c0 3 4.5 6 10 6s10-3 10-6v-9" fill="#334155"/>
                                <path d="M48 23v10" stroke="#F59E0B" stroke-width="2" stroke-linecap="round"/>
                                <path d="M18 48c4-2 9-2 14 0 5 2 10 1 14-1" stroke="#38BDF8" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                        </div>

                    @elseif ($item['custom_icon'] === 'training')
                        {{-- Training & Note --}}
                        <div class="flex h-full w-full items-center justify-center">
                            <svg class="h-20 w-20 drop-shadow transition-transform duration-300 group-hover:scale-110 sm:h-24 sm:w-24" viewBox="0 0 64 64" fill="none">
                                <circle cx="32" cy="32" r="30" fill="#F472B6" fill-opacity="0.2"/>
                                <rect x="18" y="16" width="24" height="32" rx="3" fill="#EC4899"/>
                                <rect x="22" y="20" width="18" height="24" rx="1.5" fill="#06B6D4"/>
                                <circle cx="20" cy="22" r="1.5" fill="#FFFFFF"/>
                                <circle cx="20" cy="28" r="1.5" fill="#FFFFFF"/>
                                <circle cx="20" cy="34" r="1.5" fill="#FFFFFF"/>
                                <path d="M36 44l10-14 4 3-10 14-4-3z" fill="#F59E0B"/>
                                <path d="M46 30l4 3" stroke="#DC2626" stroke-width="2"/>
                            </svg>
                        </div>

                    @elseif ($item['custom_icon'] === 'health')
                        {{-- Health Doctor & Cross --}}
                        <div class="flex h-full w-full items-center justify-center">
                            <svg class="h-20 w-20 drop-shadow transition-transform duration-300 group-hover:scale-110 sm:h-24 sm:w-24" viewBox="0 0 64 64" fill="none">
                                <circle cx="32" cy="32" r="30" fill="#3B82F6" fill-opacity="0.2"/>
                                <circle cx="32" cy="22" r="7" fill="#FCD34D"/>
                                <circle cx="21" cy="25" r="5.5" fill="#FCD34D"/>
                                <circle cx="43" cy="25" r="5.5" fill="#FCD34D"/>
                                <path d="M20 48c0-6 4-11 12-11s12 5 12 11" fill="#FFFFFF" stroke="#E2E8F0"/>
                                <path d="M12 48c0-4 3-8 9-8" stroke="#3B82F6" stroke-width="2.5" fill="none"/>
                                <path d="M52 48c0-4-3-8-9-8" stroke="#3B82F6" stroke-width="2.5" fill="none"/>
                                <circle cx="32" cy="42" r="4.5" fill="#EF4444"/>
                                <path d="M32 39.5v5M29.5 42h5" stroke="#FFFFFF" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                        </div>

                    @elseif ($item['custom_icon'] === 'transport')
                        {{-- Transport Gate & Train --}}
                        <div class="flex h-full w-full items-center justify-center">
                            <svg class="h-20 w-20 drop-shadow transition-transform duration-300 group-hover:scale-110 sm:h-24 sm:w-24" viewBox="0 0 64 64" fill="none">
                                <circle cx="32" cy="32" r="30" fill="#0EA5E9" fill-opacity="0.2"/>
                                <rect x="16" y="24" width="32" height="18" rx="6" fill="#1E3A8A"/>
                                <rect x="20" y="28" width="24" height="6" rx="2" fill="#38BDF8"/>
                                <path d="M16 36h32" stroke="#F59E0B" stroke-width="2"/>
                                <path d="M14 46h36M18 49h28" stroke="#64748B" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>

                    @elseif ($item['custom_icon'] === 'environment')
                        {{-- Clean Environment & House --}}
                        <div class="flex h-full w-full items-center justify-center">
                            <svg class="h-20 w-20 drop-shadow transition-transform duration-300 group-hover:scale-110 sm:h-24 sm:w-24" viewBox="0 0 64 64" fill="none">
                                <circle cx="32" cy="32" r="30" fill="#10B981" fill-opacity="0.2"/>
                                <circle cx="43" cy="26" r="10" fill="#10B981"/>
                                <path d="M22 28l12-9 12 9v18H22V28z" fill="#FBBF24"/>
                                <path d="M20 28l14-11 14 11" stroke="#DC2626" stroke-width="3" stroke-linecap="round"/>
                                <rect x="28" y="34" width="8" height="12" fill="#B45309"/>
                                <rect x="25" y="30" width="5" height="5" fill="#60A5FA"/>
                            </svg>
                        </div>

                    @elseif ($item['custom_icon'] === 'facility')
                        {{-- Park Bench & Facility Garden --}}
                        <div class="flex h-full w-full items-center justify-center">
                            <svg class="h-20 w-20 drop-shadow transition-transform duration-300 group-hover:scale-110 sm:h-24 sm:w-24" viewBox="0 0 64 64" fill="none">
                                <circle cx="32" cy="32" r="30" fill="#84CC16" fill-opacity="0.2"/>
                                <circle cx="22" cy="20" r="4" fill="#F59E0B"/>
                                <circle cx="43" cy="24" r="10" fill="#22C55E"/>
                                <path d="M20 34h20M20 38h20M20 42h20" stroke="#B45309" stroke-width="2.5" stroke-linecap="round"/>
                                <path d="M24 34v14M36 34v14" stroke="#475569" stroke-width="2.5" stroke-linecap="round"/>
                            </svg>
                        </div>

                    @elseif ($item['custom_icon'] === 'other')
                        {{-- Landmark Tower / Asset --}}
                        <div class="flex h-full w-full items-center justify-center">
                            <svg class="h-20 w-20 drop-shadow transition-transform duration-300 group-hover:scale-110 sm:h-24 sm:w-24" viewBox="0 0 64 64" fill="none">
                                <circle cx="32" cy="32" r="30" fill="#06B6D4" fill-opacity="0.2"/>
                                <path d="M30 46h4v-18h-4v18z" fill="#E2E8F0"/>
                                <path d="M26 46h12v4H26z" fill="#94A3B8"/>
                                <path d="M30 26l2-8 2 8h-4z" fill="#F59E0B"/>
                                <circle cx="32" cy="16" r="3" fill="#EF4444"/>
                            </svg>
                        </div>

                    @elseif ($item['custom_icon'] === 'complaint')
                        {{-- Megaphone / Lapor --}}
                        <div class="flex h-full w-full items-center justify-center">
                            <svg class="h-20 w-20 drop-shadow transition-transform duration-300 group-hover:scale-110 sm:h-24 sm:w-24" viewBox="0 0 64 64" fill="none">
                                <circle cx="32" cy="32" r="30" fill="#F43F5E" fill-opacity="0.2"/>
                                <path d="M38 22l-14 6v8l14 6V22z" fill="#EF4444"/>
                                <rect x="18" y="28" width="6" height="8" rx="1.5" fill="#3B82F6"/>
                                <path d="M24 36l3 10h4l-2-10" fill="#B45309"/>
                                <path d="M42 26c3 2 4 4 4 6s-1 4-4 6" stroke="#EF4444" stroke-width="2.5" stroke-linecap="round"/>
                                <path d="M45 22c5 3 7 7 7 10s-2 7-7 10" stroke="#EF4444" stroke-width="2" stroke-linecap="round" stroke-dasharray="2 2"/>
                            </svg>
                        </div>
                    @endif

                </div>

                {{-- Title & Subtitle --}}
                <span class="mt-3 block max-w-[135px] text-center text-[13px] font-bold leading-tight text-slate-800 transition-colors group-hover:text-cobalt-600 sm:text-[13.5px]">
                    {{ $item['title'] }}
                </span>
                <span class="mt-1 block text-[11px] font-medium text-slate-400 group-hover:text-slate-500">
                    {{ $item['sub'] }}
                </span>
            </a>
            @endforeach
        </div>



    </div>
</section>

{{-- Client-side quick filter script --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('layanan-search');
        const items = document.querySelectorAll('[data-layanan-item]');

        if (searchInput && items.length > 0) {
            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase().trim();
                items.forEach(item => {
                    const text = item.dataset.searchTerm || '';
                    if (!query || text.includes(query)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
