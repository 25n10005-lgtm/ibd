<?php

namespace Tests\Feature;

use App\Enums\AccountStatus;
use App\Enums\Role;
use App\Models\Pengaduan;
use App\Models\SuratPengajuan;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPagesSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_semua_halaman_admin_bisa_dirender(): void
    {
        $admin = User::factory()->create([
            'role' => Role::Admin,
            'status' => AccountStatus::Active,
        ]);

        foreach ([
            'dashboard',
            'warga.index',
            'keluarga.index',
            'surat.index',
            'surat.pengajuan',
            'surat.semua',
            'aduan.index',
            'laporan.index',
            'galeri.index',
            'iuran.index',
            'kas.index',
            'agenda.index',
            'pengaturan.index',
        ] as $route) {
            $this->actingAs($admin)->get(route($route))->assertOk();
        }
    }

    public function test_tolak_pengajuan_wajib_alasan(): void
    {
        $admin = User::factory()->create([
            'role' => Role::Admin,
            'status' => AccountStatus::Active,
        ]);

        $surat = SuratPengajuan::create([
            'no_surat' => 'SKD-TEST-001',
            'jenis' => 'Surat Keterangan Domisili',
            'pemohon' => 'Warga Tes',
            'status' => 'Menunggu',
        ]);

        $this->actingAs($admin)
            ->post(route('surat.reject', $surat), ['alasan' => 'pendek'])
            ->assertSessionHasErrors('alasan');

        $this->actingAs($admin)
            ->post(route('surat.reject', $surat), ['alasan' => 'Dokumen KTP tidak terbaca dengan jelas.'])
            ->assertRedirect();

        $this->assertSame('Ditolak', $surat->fresh()->status);
    }

    public function test_tambah_transaksi_laporan(): void
    {
        $admin = User::factory()->create([
            'role' => Role::Admin,
            'status' => AccountStatus::Active,
        ]);

        $this->actingAs($admin)
            ->post(route('laporan.store'), [
                'tanggal' => '2026-09-17',
                'deskripsi' => 'Iuran warga – Tes',
                'kategori' => 'Iuran',
                'arah' => 'masuk',
                'jumlah' => 50000,
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('kas_transaksis', ['deskripsi' => 'Iuran warga – Tes']);
    }

    public function test_hapus_pengaduan(): void
    {
        $admin = User::factory()->create([
            'role' => Role::Admin,
            'status' => AccountStatus::Active,
        ]);

        $aduan = Pengaduan::create([
            'judul' => 'Hapus Saya',
            'lokasi' => 'Blok Tes',
            'pelapor' => 'Warga Tes',
            'status' => 'Belum',
        ]);

        $this->actingAs($admin)
            ->delete(route('aduan.destroy', $aduan))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('pengaduans', ['id' => $aduan->id]);
    }

    public function test_laporan_per_page_dan_all(): void
    {
        $admin = User::factory()->create([
            'role' => Role::Admin,
            'status' => AccountStatus::Active,
        ]);

        $this->actingAs($admin)->get(route('laporan.index', ['per_page' => 5]))->assertOk();
        $this->actingAs($admin)->get(route('laporan.index', ['per_page' => 'all']))->assertOk();
        $this->actingAs($admin)->get(route('laporan.index', ['per_page' => 999]))->assertOk();
    }

    public function test_halaman_warga_bisa_dirender(): void
    {
        $warga = User::factory()->create([
            'role' => Role::User,
            'status' => AccountStatus::Active,
        ]);

        $this->actingAs($warga)->get(route('dashboard'))->assertRedirect(route('saya.dashboard'));

        foreach ([
            'saya.dashboard',
            'saya.keluarga',
            'saya.surat',
            'saya.pengumuman',
            'saya.kegiatan',
            'saya.iuran',
            'saya.iuran.riwayat',
            'saya.aduan',
            'saya.pengaturan',
        ] as $route) {
            $this->actingAs($warga)->get(route($route))->assertOk();
        }
    }

    public function test_data_warga_bisa_cari_dan_filter(): void
    {
        $admin = User::factory()->create([
            'role' => Role::Admin,
            'status' => AccountStatus::Active,
        ]);

        $warga = Warga::create([
            'nik' => '3201010101999001',
            'nama' => 'Cari Saya',
            'jenis_kelamin' => 'L',
            'status_tinggal' => 'Tetap',
            'aktif' => true,
            'alamat' => 'Jl. Tes No. 1',
        ]);

        $this->actingAs($admin)->get(route('warga.index', ['q' => 'Cari Saya']))
            ->assertOk()
            ->assertSee('Cari Saya');

        $this->actingAs($admin)->get(route('warga.index', ['q' => 'Tidak Ada Nama Ini']))
            ->assertOk()
            ->assertDontSee('Cari Saya');

        $this->actingAs($admin)->get(route('warga.index', ['status_tinggal' => 'Kos']))
            ->assertOk()
            ->assertDontSee('Cari Saya');
    }
}
