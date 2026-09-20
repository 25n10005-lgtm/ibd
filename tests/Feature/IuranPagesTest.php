<?php

namespace Tests\Feature;

use App\Enums\AccountStatus;
use App\Enums\Role;
use App\Models\IuranPembayaran;
use App\Models\Rt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IuranPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_iuran_tagihan_dan_riwayat_terpisah(): void
    {
        $rt = Rt::create(['kode' => 'RT04', 'nama' => 'RT 04']);
        $user = User::factory()->create([
            'name' => 'Budi Santoso',
            'role' => Role::User,
            'status' => AccountStatus::Active,
            'rt_id' => $rt->id,
        ]);

        IuranPembayaran::create([
            'rt_id' => $rt->id, 'nama_pembayar' => 'Budi Santoso',
            'periode' => '2026-09', 'jumlah' => 50000, 'status' => 'Menunggak',
        ]);
        IuranPembayaran::create([
            'rt_id' => $rt->id, 'nama_pembayar' => 'Budi Santoso',
            'periode' => '2026-08', 'jumlah' => 50000,
            'tanggal_bayar' => '2026-08-05', 'status' => 'Lunas',
        ]);

        // Halaman tagihan: kartu tunggakan + total, tanpa riwayat lunas.
        $this->actingAs($user)->get(route('saya.iuran'))
            ->assertOk()
            ->assertSee('September 2026', false)
            ->assertSee('Bayar Sekarang')
            ->assertDontSee('August 2026', false);

        // Halaman riwayat: desain list + semua periode + paginasi siap.
        $this->actingAs($user)->get(route('saya.iuran.riwayat'))
            ->assertOk()
            ->assertSee('Riwayat Pembayaran Saya')
            ->assertSee('August 2026', false)
            ->assertSee('September 2026', false);

        // Dropdown sidebar memuat kedua sub.
        $this->actingAs($user)->get(route('saya.iuran'))
            ->assertSee('Riwayat Iuran', false);
    }

    public function test_iuran_kosong_tampilkan_struktur_dan_cta_riwayat(): void
    {
        $rt = Rt::create(['kode' => 'RT04', 'nama' => 'RT 04']);
        $user = User::factory()->create([
            'role' => Role::User,
            'status' => AccountStatus::Active,
            'rt_id' => $rt->id,
        ]);

        $this->actingAs($user)->get(route('saya.iuran'))
            ->assertOk()->assertSee('Tidak ada tunggakan');
    }
}
