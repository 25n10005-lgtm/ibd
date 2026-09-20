<?php

namespace Database\Seeders;

use App\Models\IuranPembayaran;
use App\Models\KasTransaksi;
use App\Models\Kegiatan;
use App\Models\Keluarga;
use App\Models\Pengaduan;
use App\Models\Pengumuman;
use App\Models\Rt;
use App\Models\SuratPengajuan;
use App\Models\Warga;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Data contoh mengikuti angka Figma (total warga, KK, surat,
     * pengaduan, iuran, kas, kegiatan). Idempoten: aman dijalankan
     * ulang tanpa menduplikasi data.
     */
    public function run(): void
    {
        $rt = Rt::firstOrCreate(['kode' => 'RT04'], [
            'nama' => 'RT 04',
            'rw' => 'RW 02',
            'alamat' => 'Jl. Melati, Kel. Sukamaju',
        ]);
        Rt::firstOrCreate(['kode' => 'RT05'], [
            'nama' => 'RT 05',
            'rw' => 'RW 02',
            'alamat' => 'Jl. Mawar, Kel. Sukamaju',
        ]);

        // Baris lama (sebelum multi-tenant) ikut RT 04.
        foreach (['keluargas', 'surat_pengajuans', 'pengaduans', 'kegiatans', 'pengumuman', 'kas_transaksis', 'iuran_pembayarans'] as $table) {
            \DB::table($table)->whereNull('rt_id')->update(['rt_id' => $rt->id]);
        }
        \App\Models\User::whereNull('rt_id')->update(['rt_id' => $rt->id]);
        $families = [
            ['3201010101000001', 'Budi Santoso', 'Jl. Melati No. 03', [
                ['3201011005800003', 'Budi Santoso', 'L', '1980-05-10', 'Tetap', true, '0812-9876-5432'],
                ['3201012505820004', 'Dewi Lestari', 'P', '1982-08-25', 'Tetap', true, '0813-8765-4321'],
            ]],
            ['3201010101000002', 'Andi Wijaya', 'Jl. Melati No. 12', [
                ['3201011505880001', 'Andi Wijaya', 'L', '1988-05-15', 'Tetap', true, '0812-3456-7890'],
                ['3201012205870002', 'Siti Rahma', 'P', '1987-05-22', 'Tetap', true, '0813-2345-6789'],
            ]],
            ['3201010101000003', 'Agus Prasetyo', 'Jl. Mawar No. 07', [
                ['3201011705900005', 'Agus Prasetyo', 'L', '1990-05-17', 'Kontrak', true, '0812-1122-3344'],
                ['3201013005910006', 'Rina Marlina', 'P', '1991-05-30', 'Kontrak', false, '0813-5566-7788'],
            ]],
            ['3201010101000004', 'Hendra Gunawan', 'Jl. Kenanga No. 21', [
                ['3201010505850007', 'Hendra Gunawan', 'L', '1985-05-05', 'Tetap', true, '0812-9988-7766'],
                ['3201011905880008', 'Yuniarti', 'P', '1988-05-19', 'Tetap', true, '0813-4433-2211'],
            ]],
            ['3201010101000005', 'Dedi Kurniawan', 'Jl. Flamboyan No. 09', [
                ['3201012805920009', 'Dedi Kurniawan', 'L', '1992-05-28', 'Tetap', true, '0812-6677-8899'],
                ['3201011405930010', 'Sari Puspita', 'P', '1993-05-14', 'Tetap', true, '0813-2211-0099'],
            ]],
        ];

        foreach ($families as [$noKk, $kepala, $alamat, $members]) {
            $keluarga = Keluarga::firstOrCreate(['no_kk' => $noKk], [
                'rt_id' => $rt->id,
                'kepala_keluarga' => $kepala,
                'alamat' => $alamat,
            ]);

            foreach ($members as [$nik, $nama, $jk, $lahir, $tinggal, $aktif, $hp]) {
                $warga = Warga::firstOrCreate(['nik' => $nik], [
                    'keluarga_id' => $keluarga->id,
                    'nama' => $nama,
                    'jenis_kelamin' => $jk,
                    'tanggal_lahir' => $lahir,
                    'status_tinggal' => $tinggal,
                    'aktif' => $aktif,
                    'no_hp' => $hp,
                    'alamat' => $alamat,
                ]);

                IuranPembayaran::firstOrCreate(
                    ['warga_id' => $warga->id, 'periode' => '2026-09'],
                    [
                        'rt_id' => $rt->id,
                        'nama_pembayar' => $nama,
                        'jumlah' => 50000,
                        'tanggal_bayar' => $aktif ? '2026-09-0'.(string) (($warga->id % 9) + 1) : null,
                        'status' => $aktif ? 'Lunas' : 'Menunggak',
                    ]
                );
            }
        }

        foreach ([
            ['SKD-2026-0081', 'Surat Keterangan Domisili', 'Andi Wijaya', 'Blok A/12', 'KTP, KK', 'Menunggu', null],
            ['SKN-2026-0079', 'Surat Pengantar Nikah', 'Siti Rahma', 'Blok B/05', 'KTP, KK, Akta', 'Selesai', null],
            ['SKU-2026-0077', 'Surat Keterangan Usaha', 'Hendra Gunawan', 'Blok C/02', 'KTP, SKU lama', 'Diproses', null],
            ['SKCK-2026-0075', 'Surat Pengantar SKCK', 'Dedi Kurniawan', 'Blok A/09', 'KTP, KK', 'Selesai', null],
            ['SKTM-2026-0072', 'Surat Keterangan Tidak Mampu', 'Yuniarti', 'Blok C/11', 'KTP', 'Ditolak', 'Di luar wilayah RT 03.'],
            ['SKD-2026-0070', 'Surat Keterangan Domisili', 'Agus Prasetyo', 'Blok B/07', 'KTP, KK', 'Selesai', null],
        ] as [$no, $jenis, $pemohon, $blok, $lampiran, $status, $catatan]) {
            SuratPengajuan::firstOrCreate(['no_surat' => $no], [
                'rt_id' => $rt->id,
                'jenis' => $jenis,
                'pemohon' => $pemohon,
                'blok' => $blok,
                'lampiran' => $lampiran,
                'status' => $status,
                'catatan' => $catatan,
            ]);
        }

        foreach ([
            ['Lampu jalan mati', 'Blok C depan taman', 'Andi Wijaya', 'Belum'],
            ['Sampah belum diangkut', 'Area Blok A ujung', 'Siti Rahma', 'Proses'],
            ['Jalan berlubang', 'Jl. Melati No. 22', 'Hendra Gunawan', 'Proses'],
            ['Air PDAM keruh', 'Blok B/05', 'Dedi Kurniawan', 'Belum'],
            ['Pos ronda rusak', 'Pos 2 dekat mushola', 'Agus Prasetyo', 'Selesai'],
            ['Suara bising malam hari', 'Blok A/09', 'Yuniarti', 'Selesai'],
            ['Got tersumbat', 'Jl. Kenanga No. 21', 'Rudi Hartono', 'Ditolak'],
            ['Penerangan gang kurang', 'Gang Melati 3', 'Sari Puspita', 'Selesai'],
        ] as [$judul, $lokasi, $pelapor, $status]) {
            Pengaduan::firstOrCreate(['judul' => $judul, 'pelapor' => $pelapor], [
                'rt_id' => $rt->id,
                'lokasi' => $lokasi,
                'status' => $status,
            ]);
        }

        foreach ([
            ['masuk', 'Iuran warga – Andi Wijaya', 50000, 'Iuran', '2026-09-02'],
            ['masuk', 'Iuran warga – Siti Rahma', 50000, 'Iuran', '2026-09-02'],
            ['keluar', 'Pembelian lampu jalan', 350000, 'Fasilitas', '2026-09-01'],
            ['keluar', 'Kebersihan lingkungan', 200000, 'Operasional', '2026-09-01'],
            ['keluar', 'Perbaikan pos ronda', 150000, 'Fasilitas', '2026-08-28'],
        ] as [$arah, $deskripsi, $jumlah, $kategori, $tanggal]) {
            KasTransaksi::firstOrCreate(['deskripsi' => $deskripsi, 'tanggal' => $tanggal], [
                'rt_id' => $rt->id,
                'arah' => $arah,
                'jumlah' => $jumlah,
                'kategori' => $kategori,
            ]);
        }

        foreach ([
            ['Kerja Bakti Lingkungan', 'Lingkungan', '2026-09-06', '07.00 – Selesai', 'Balai RT', null, 'Bersih-bersih lingkungan dan saluran air.'],
            ['Rapat Warga', 'Rapat', '2026-09-13', '19.00 – 21.00', 'Balai RT', null, 'Pembahasan iuran dan renovasi pos ronda.'],
            ['Posyandu Balita', 'Kesehatan', '2026-09-15', '08.00 – 11.00', 'Posyandu Mawar', null, 'Penimbangan dan imunisasi balita.'],
            ['Pengajian Rutin', 'Keagamaan', '2026-09-19', '19.30 – 21.00', 'Mushola Al-Ikhlas', null, 'Pengajian mingguan warga.'],
            ['Senam Pagi Bersama', 'Kesehatan', '2026-09-20', '06.30 – 08.00', 'Lapangan RT', null, 'Senam aerobik dan sarapan bersama.'],
            ['Lomba 17 Agustus', 'Hiburan', '2026-08-17', '08.00 – 12.00', 'Lapangan RT', 'dibatalkan', 'Dibatalkan karena cuaca buruk.'],
        ] as [$judul, $kategori, $tanggal, $waktu, $lokasi, $status, $deskripsi]) {
            Kegiatan::updateOrCreate(['judul' => $judul, 'tanggal' => $tanggal], [
                'rt_id' => $rt->id,
                'kategori' => $kategori,
                'waktu' => $waktu,
                'lokasi' => $lokasi,
                'status' => $status,
                'deskripsi' => $deskripsi,
            ]);
        }

        foreach ([
            ['Kerja Bakti Lingkungan RT 04', 'Mari kita bersama-sama menjaga kebersihan lingkungan.', 'Kegiatan', 'terbit', 154, 120, '2026-05-31 08:00'],
            ['Pengingat Iuran Bulanan Juni 2026', 'Batas akhir pembayaran iuran bulan Juni segera tiba.', 'Keuangan', 'terbit', 198, 210, '2026-05-29 10:30'],
            ['Selamat Hari Raya Idul Adha 1447 H', 'Pengurus RT 04 / RW 02 mengucapkan selamat hari raya.', 'Informasi', 'terbit', 245, 240, '2026-05-27 09:00'],
            ['Rapat Pengurus RT', 'Rapat rutin pengurus RT akan dilaksanakan di balai warga.', 'Rapat', 'terjadwal', 0, 0, '2026-06-08 19:30'],
            ['Vaksinasi Booster Gratis', 'Vaksinasi booster akan diadakan bekerja sama dengan puskesmas.', 'Kesehatan', 'draf', 0, 0, null],
            ['Jadwal Ronda Triwulan IV', 'Jadwal jaga malam periode Oktober–Desember telah terbit.', 'Informasi', 'terbit', 132, 98, '2026-09-14 08:00'],
            ['Iuran Kas September Ditutup', 'Batas pembayaran iuran kas bulan September tanggal 20.', 'Keuangan', 'terbit', 176, 185, '2026-09-13 09:00'],
            ['Sosialisasi Bank Sampah', 'Pelatihan memilah sampah anorganik bersama karang taruna.', 'Kegiatan', 'terjadwal', 0, 0, '2026-10-02 09:00'],
        ] as [$judul, $ringkasan, $kategori, $status, $views, $target, $published]) {
            Pengumuman::firstOrCreate(['judul' => $judul], [
                'rt_id' => $rt->id,
                'ringkasan' => $ringkasan,
                'kategori' => $kategori,
                'status' => $status,
                'views' => $views,
                'target_warga' => $target,
                'published_at' => $published,
            ]);
        }

        $this->command->info('Data demo terisi.');
    }
}
