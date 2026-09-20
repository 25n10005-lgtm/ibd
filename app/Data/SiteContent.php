<?php

namespace App\Data;

class SiteContent
{
    /**
     * Placeholder gambar sementara via Unsplash (nanti diganti aset asli).
     * Key = nama file lama di public/images/landing.
     */
    public const IMAGE_PLACEHOLDERS = [
        'hero.png' => 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?auto=format&fit=crop&w=1600&q=70',
        'rt-1.png' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=900&q=70',
        'rt-2.png' => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?auto=format&fit=crop&w=900&q=70',
        'rt-3.png' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&w=1200&q=70',
        'rt-4.png' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=400&q=70',
        'rt-5.png' => 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=400&q=70',
        'rt-6.png' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=400&q=70',
        'untuk-pengurus.png' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=600&q=70',
        'untuk-warga.png' => 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&w=600&q=70',
        'untuk-bendahara.png' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=600&q=70',
        'untuk-admin.png' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=600&q=70',
        'testimoni-video.png' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=1200&q=70',
    ];

    /**
     * Resolve gambar landing: URL penuh diteruskan, nama file lama
     * dipetakan ke Unsplash, sisanya fallback aset lokal.
     */
    public static function image(string $file): string
    {
        if (str_starts_with($file, 'http')) {
            return $file;
        }

        return self::IMAGE_PLACEHOLDERS[$file] ?? asset('images/landing/'.$file);
    }

    /** @return array<string, string> */
    public static function imageMap(): array
    {
        return self::IMAGE_PLACEHOLDERS;
    }

    public static function events(): array
    {
        return [
            ['id' => 1, 'day' => 12, 'title' => 'Kerja Bakti Akbar Lingkungan', 'date' => '12 Sep 2026', 'time' => '07.00 – 10.00 WIB', 'loc' => 'Balai Warga RT 03', 'org' => 'Pengurus RT 03 / RW 02', 'status' => 'Selesai', 'img' => 'untuk-pengurus.png', 'desc' => 'Seluruh warga bergotong royong membersihkan selokan, memangkas ranting, dan mengecat ulang pos ronda. Sebanyak 85 KK berpartisipasi dan 3 titik drainase berhasil dinormalisasi.'],
            ['id' => 2, 'day' => 13, 'title' => 'Posyandu Balita & Lansia', 'date' => '13 Sep 2026', 'time' => '08.00 – 11.00 WIB', 'loc' => 'Posyandu Mawar, Pamulang Barat', 'org' => 'Kader Posyandu', 'status' => 'Selesai', 'img' => 'untuk-warga.png', 'desc' => 'Pelayanan penimbangan balita, imunisasi dasar, dan pemeriksaan tekanan darah lansia. Tercatat 42 balita dan 28 lansia menerima layanan kesehatan gratis.'],
            ['id' => 3, 'day' => 16, 'title' => 'Musyawarah Warga Bulanan', 'date' => '16 Sep 2026', 'time' => '19.30 – 21.30 WIB', 'loc' => 'Balai Warga RT 03', 'org' => 'Pengurus RT 03 / RW 02', 'status' => 'Berlangsung', 'img' => 'untuk-admin.png', 'desc' => 'Forum terbuka membahas laporan kas bulan berjalan, rencana penambahan CCTV gerbang, dan jadwal ronda triwulan. Seluruh kepala keluarga diundang hadir.'],
            ['id' => 4, 'day' => 18, 'title' => 'Sosialisasi Iuran via Smart RT', 'date' => '18 Sep 2026', 'time' => '19.00 – 20.30 WIB', 'loc' => 'Balai Warga RT 03', 'org' => 'Bendahara RT', 'status' => 'Segera', 'img' => 'untuk-bendahara.png', 'desc' => 'Edukasi pembayaran iuran kas secara digital melalui aplikasi, termasuk cara cek tagihan, riwayat pembayaran, dan unduh kwitansi resmi.'],
            ['id' => 5, 'day' => 19, 'title' => 'Pelatihan Bank Sampah Mandiri', 'date' => '19 Sep 2026', 'time' => '09.00 – 12.00 WIB', 'loc' => 'Bank Sampah Asri', 'org' => 'Karang Taruna', 'status' => 'Segera', 'img' => 'untuk-warga.png', 'desc' => 'Pelatihan memilah sampah anorganik, menimbang, dan mencatat setoran ke buku tabungan sampah digital. Peserta membawa sampah pilah dari rumah masing-masing.'],
            ['id' => 6, 'day' => 25, 'title' => 'Peringatan Maulid Nabi & Santunan', 'date' => '25 Sep 2026', 'time' => '18.30 – 22.00 WIB', 'loc' => 'Masjid Al-Ikhlas', 'org' => 'DKM & Pengurus RT', 'status' => 'Segera', 'img' => 'hero.png', 'desc' => 'Doa bersama, tausiah, dan santunan untuk anak yatim lingkungan. Warga yang ingin berdonasi dapat menyalurkan melalui bendahara paling lambat H-2 acara.'],
            ['id' => 7, 'day' => 27, 'title' => 'Senam Pagi Bersama Warga', 'date' => '27 Sep 2026', 'time' => '06.30 – 08.00 WIB', 'loc' => 'Lapangan RT 03', 'org' => 'PKK', 'status' => 'Segera', 'img' => 'untuk-pengurus.png', 'desc' => 'Senam aerobik dipandu instruktur, dilanjutkan sarapan bersama dan pemeriksaan gula darah gratis. Terbuka untuk seluruh warga tanpa pendaftaran.'],
            ['id' => 8, 'day' => 30, 'title' => 'Ronda Malam Gabungan', 'date' => '30 Sep 2026', 'time' => '23.00 – 03.00 WIB', 'loc' => 'Pos Ronda Utama', 'org' => 'Sie Keamanan', 'status' => 'Segera', 'img' => 'untuk-admin.png', 'desc' => 'Patroli gabungan seluruh blok dengan pembagian shift via aplikasi. Absensi ronda tercatat otomatis dan terhubung dengan rekap kehadiran bulanan.'],
            ['id' => 9, 'day' => 9, 'title' => 'Distribusi Gas Elpiji Subsidi', 'date' => '9 Sep 2026', 'time' => '09.00 – 12.00 WIB', 'loc' => 'Pangkalan Resmi RT 03', 'org' => 'Sie Kesejahteraan', 'status' => 'Selesai', 'img' => 'untuk-bendahara.png', 'desc' => 'Penyaluran gas tabung 3 kg tepat sasaran bagi 60 KK penerima yang terdata. Warga wajib membawa KTP dan kartu keluarga saat pengambilan.'],
        ];
    }

    public static function announcements(): array
    {
        $items = [
            ['id' => 1, 'date' => '14 Sep 2026', 'cat' => 'Pengumuman', 'title' => 'Jadwal Ronda Triwulan IV Telah Terbit', 'excerpt' => 'Jadwal jaga malam periode Oktober–Desember sudah dapat dilihat di aplikasi. Pastikan memverifikasi shift masing-masing.', 'body' => 'Jadwal jaga malam (ronda) untuk triwulan IV periode Oktober–Desember 2026 telah diterbitkan dan dapat dilihat melalui aplikasi Smart RT. Setiap kepala keluarga mendapatkan minimal 2 shift jaga per bulan. Absensi dilakukan melalui aplikasi dan tercatat otomatis. Bagi warga yang berhalangan, wajib mencari pengganti dan melapor ke koordinator blok maksimal H-1.', 'img' => 'untuk-admin.png'],
            ['id' => 2, 'date' => '13 Sep 2026', 'cat' => 'Informasi', 'title' => 'Iuran Kas September Ditutup Tanggal 20', 'excerpt' => 'Batas pembayaran iuran kas bulan September adalah tanggal 20. Cek status tagihan Anda di aplikasi.', 'body' => 'Bendahara RT menginformasikan bahwa batas akhir pembayaran iuran kas bulan September 2026 adalah tanggal 20 September pukul 23.59 WIB. Pembayaran dapat dilakukan tunai ke bendahara atau transfer dengan mengunggah bukti melalui aplikasi. Keterlambatan dikenakan pencatatan tunggakan yang dapat dipantau transparan oleh seluruh warga.', 'img' => 'untuk-bendahara.png'],
            ['id' => 3, 'date' => '12 Sep 2026', 'cat' => 'Pengumuman', 'title' => 'Kerja Bakti Akbar: Minggu 07.00 WIB', 'excerpt' => 'Seluruh warga diwajibkan mengikuti kerja bakti pembersihan selokan dan pengecatan pos ronda.', 'body' => 'Dalam rangka menyambut musim hujan, pengurus RT mengadakan kerja bakti akbar pada hari Minggu pukul 07.00 WIB dengan titik kumpul di Balai Warga. Agenda meliputi pembersihan selokan, pemangkasan ranting, dan pengecatan ulang pos ronda. Warga diminta membawa peralatan masing-masing (cangkul, sapu lidi, karung). Konsumsi disediakan oleh seksi sosial.', 'img' => 'untuk-pengurus.png'],
            ['id' => 4, 'date' => '11 Sep 2026', 'cat' => 'Peringatan', 'title' => 'Waspada DBD: Pemberantasan Sarang Nyamuk', 'excerpt' => 'Ditemukan 2 kasus DBD di RW tetangga. Lakukan 3M Plus dan izinkan fogging terjadwal.', 'body' => 'Sehubungan dengan ditemukannya 2 kasus Demam Berdarah di RW tetangga, seluruh warga diminta meningkatkan kewaspadaan dengan gerakan 3M Plus: menguras, menutup, mendaur ulang, plus menabur larvasida. Fogging terjadwal akan dilaksanakan Sabtu pagi. Warga diminta membuka jendela dan menutup makanan selama penyemprotan.', 'img' => 'untuk-warga.png'],
            ['id' => 5, 'date' => '10 Sep 2026', 'cat' => 'Informasi', 'title' => 'Pendaftaran Surat Domisili Kini Online', 'excerpt' => 'Pengajuan surat keterangan domisili dapat dilakukan sepenuhnya dari aplikasi tanpa antre.', 'body' => 'Mulai bulan ini, pengajuan surat keterangan domisili dan surat pengantar lainnya dilakukan sepenuhnya melalui aplikasi Smart RT. Isi formulir, unggah KTP dan KK, lalu pantau status verifikasi. Surat yang sudah ditandatangani digital dapat diunduh langsung atau diambil fisik di Balai Warga pada jam layanan.', 'img' => 'hero.png'],
            ['id' => 6, 'date' => '9 Sep 2026', 'cat' => 'Pengumuman', 'title' => 'Penyaluran Gas Elpiji Subsidi Tahap II', 'excerpt' => '60 KK penerima dapat mengambil gas 3 kg di pangkalan resmi dengan membawa KTP dan KK.', 'body' => 'Penyaluran gas elpiji 3 kg subsidi tahap II dilaksanakan mulai pukul 09.00 WIB di pangkalan resmi RT 03. Sebanyak 60 KK penerima yang terdata wajib membawa KTP dan kartu keluarga asli. Pengambilan diwakilkan hanya dengan surat kuasa bermeterai dan fotokopi KTP kedua belah pihak.', 'img' => 'untuk-bendahara.png'],
            ['id' => 7, 'date' => '8 Sep 2026', 'cat' => 'Informasi', 'title' => 'Hasil Musyawarah: Iuran Naik Rp 5.000', 'excerpt' => 'Disepakati kenaikan iuran kas menjadi Rp 30.000 mulai Oktober untuk dana CCTV gerbang.', 'body' => 'Musyawarah warga tanggal 7 September menyepakati kenaikan iuran kas bulanan sebesar Rp 5.000 menjadi Rp 30.000 per KK mulai Oktober 2026. Dana tambahan dialokasikan untuk pengadaan 4 unit CCTV gerbang dan perawatan lampu jalan. Notulen lengkap dan rincian anggaran dapat diunduh di aplikasi.', 'img' => 'untuk-admin.png'],
            ['id' => 8, 'date' => '7 Sep 2026', 'cat' => 'Peringatan', 'title' => 'Parkir Badan Jalan Dilarang 06.00–18.00', 'excerpt' => 'Truk sampah dan mobil pemadam membutuhkan akses bebas. Kendaraan pelanggar akan didata.', 'body' => 'Demi kelancaran armada kebersihan dan akses darurat, parkir kendaraan di badan jalan poros utama dilarang pukul 06.00–18.00 WIB. Warga yang memiliki lebih dari 2 kendaraan diminta mengatur parkir di halaman masing-masing. Pelanggaran berulang akan didata dan dibahas pada musyawarah berikutnya.', 'img' => 'untuk-pengurus.png'],
            ['id' => 9, 'date' => '6 Sep 2026', 'cat' => 'Informasi', 'title' => 'Posyandu: Imunisasi Dasar Lengkap', 'excerpt' => 'Pekan imunisasi balita dibuka sepanjang September di Posyandu Mawar setiap Sabtu.', 'body' => 'Posyandu Mawar membuka pekan imunisasi dasar lengkap sepanjang bulan September setiap hari Sabtu pukul 08.00–11.00 WIB. Orang tua diminta membawa buku KIA. Tersedia imunisasi BCG, DPT-HB-Hib, polio, dan campak. Konsultasi gizi dan penimbangan balita tetap berjalan seperti biasa.', 'img' => 'untuk-warga.png'],
        ];

        foreach ($items as $i => &$item) {
            $item['ts'] = 20260915 - intdiv($i, 2);
        }
        unset($item);

        return $items;
    }

    public static function posts(): array
    {
        return [
            ['slug' => 'rapat-koordinasi-keamanan-cctv', 'img' => 'rt-5.png', 'date' => 'Senin, 14 September 2026', 'title' => 'Rapat Koordinasi Pengurus RT: Peningkatan Keamanan & Penambahan CCTV', 'city' => 'PAMULANG BARAT', 'desc' => 'Jajaran pengurus RT 03/RW 02 menggelar forum kemitraan bersama tokoh masyarakat guna membahas penguatan sistem keamanan gerbang dan penambahan titik pantau CCTV lingkungan...'],
            ['slug' => 'integrasi-surat-pengantar-digital', 'img' => 'rt-1.png', 'date' => 'Senin, 14 September 2026', 'title' => 'Pengurus RT Perkuat Integrasi Layanan Surat Pengantar Digital Mandiri', 'city' => 'PAMULANG BARAT', 'desc' => 'Menindaklanjuti program digitalisasi administrasi lingkungan, warga kini dapat mengajukan surat domisili dan pengantar nikah secara mandiri melalui aplikasi Smart RT tanpa antre...'],
            ['slug' => 'sosialisasi-gas-elpiji-subsidi', 'img' => 'rt-4.png', 'date' => 'Senin, 14 September 2026', 'title' => 'Sosialisasi Distribusi Gas Elpiji & Bantuan Subsidi Warga Tepat Sasaran', 'city' => 'PAMULANG BARAT', 'desc' => 'Seksi Kesejahteraan Warga berkoordinasi langsung dengan pangkalan resmi guna memastikan ketersediaan dan kestabilan pasokan gas tabung bagi warga yang berhak...'],
            ['slug' => 'hari-guyub-rt-persatuan', 'img' => 'rt-3.png', 'date' => 'Minggu, 13 September 2026', 'title' => 'Peringatan Hari Guyub RT: Momentum Perkuat Persatuan dan Solidaritas Warga', 'city' => 'PAMULANG BARAT', 'desc' => 'Rangkaian silaturahmi akbar warga RT 03/RW 02 berlangsung meriah dengan agenda temu warga, evaluasi program gotong royong, serta penguatan transparansi laporan kas...'],
            ['slug' => 'maulid-nabi-santunan', 'img' => 'rt-6.png', 'date' => 'Sabtu, 12 September 2026', 'title' => 'Peringatan Maulid Nabi: Ajak Warga Pererat Silaturahmi & Santunan Lingkungan', 'city' => 'PAMULANG BARAT', 'desc' => 'Pengurus DKM bersama warga RT menyelenggarakan peringatan Maulid Nabi Muhammad SAW yang diisi dengan doa bersama serta santunan untuk anak yatim di lingkungan sekitar...'],
            ['slug' => 'libatkan-generasi-muda', 'img' => 'rt-2.png', 'date' => 'Sabtu, 12 September 2026', 'title' => 'Ketua RT Tekankan Pentingnya Libatkan Generasi Muda dalam Kegiatan Warga', 'city' => 'PAMULANG BARAT', 'desc' => 'Dalam pertemuan karang taruna lingkungan, Ketua RT menekankan peran krusial pemuda dalam mengelola bank sampah mandiri, literasi digital, dan pos ronda lingkungan...'],
        ];
    }
}
