<?php

namespace Tests\Feature;

use App\Enums\AccountStatus;
use App\Enums\Role;
use App\Models\Kegiatan;
use App\Models\KegiatanHadir;
use App\Models\Rt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KegiatanHadirTest extends TestCase
{
    use RefreshDatabase;

    private function makeKegiatan(Rt $rt, array $overrides = []): Kegiatan
    {
        return Kegiatan::create(array_merge([
            'rt_id' => $rt->id,
            'judul' => 'Kerja Bakti',
            'kategori' => 'Kebersihan',
            'tanggal' => today()->addDays(3)->toDateString(),
            'waktu' => '07:00',
            'lokasi' => 'Balai RT',
            'deskripsi' => 'Bersih-bersih lingkungan.',
        ], $overrides));
    }

    public function test_halaman_kegiatan_ada_modal_preview(): void
    {
        $rt = Rt::create(['kode' => 'RT04', 'nama' => 'RT 04']);
        $user = User::factory()->create(['role' => Role::User, 'status' => AccountStatus::Active, 'rt_id' => $rt->id]);
        $this->makeKegiatan($rt);

        $this->actingAs($user)->get(route('saya.kegiatan'))
            ->assertOk()
            ->assertSee('previewKegiatan', false)
            ->assertSee('Saya Akan Hadir');
    }

    public function test_warga_bisa_konfirmasi_hadir_dan_berhalangan(): void
    {
        $rt = Rt::create(['kode' => 'RT04', 'nama' => 'RT 04']);
        $user = User::factory()->create(['role' => Role::User, 'status' => AccountStatus::Active, 'rt_id' => $rt->id]);
        $kegiatan = $this->makeKegiatan($rt);

        $this->actingAs($user)->post(route('saya.kegiatan.hadir', $kegiatan), ['status' => 'hadir'])
            ->assertRedirect();

        $this->assertDatabaseHas('kegiatan_hadirs', [
            'kegiatan_id' => $kegiatan->id,
            'user_id' => $user->id,
            'status' => 'hadir',
        ]);

        // Ganti pilihan menimpa baris yang sama (tanpa duplikat).
        $this->actingAs($user)->post(route('saya.kegiatan.hadir', $kegiatan), ['status' => 'tidak'])
            ->assertRedirect();

        $this->assertSame(1, KegiatanHadir::where('kegiatan_id', $kegiatan->id)->count());
        $this->assertSame('tidak', KegiatanHadir::firstWhere('user_id', $user->id)->status);

        // Batalkan.
        $this->actingAs($user)->delete(route('saya.kegiatan.hadir.batal', $kegiatan))->assertRedirect();
        $this->assertDatabaseCount('kegiatan_hadirs', 0);
    }

    public function test_kegiatan_lewat_atau_batal_ditolak(): void
    {
        $rt = Rt::create(['kode' => 'RT04', 'nama' => 'RT 04']);
        $user = User::factory()->create(['role' => Role::User, 'status' => AccountStatus::Active, 'rt_id' => $rt->id]);

        $lewat = $this->makeKegiatan($rt, ['tanggal' => today()->subDay()->toDateString()]);
        $this->actingAs($user)->post(route('saya.kegiatan.hadir', $lewat), ['status' => 'hadir'])
            ->assertStatus(422);

        $batal = $this->makeKegiatan($rt, ['status' => 'dibatalkan']);
        $this->actingAs($user)->post(route('saya.kegiatan.hadir', $batal), ['status' => 'hadir'])
            ->assertStatus(422);
    }

    public function test_kegiatan_rt_lain_ditolak(): void
    {
        $a = Rt::create(['kode' => 'RT04', 'nama' => 'RT 04']);
        $b = Rt::create(['kode' => 'RT05', 'nama' => 'RT 05']);
        $user = User::factory()->create(['role' => Role::User, 'status' => AccountStatus::Active, 'rt_id' => $a->id]);
        $kegiatan = $this->makeKegiatan($b);

        $this->actingAs($user)->post(route('saya.kegiatan.hadir', $kegiatan), ['status' => 'hadir'])
            ->assertForbidden();
    }
}
