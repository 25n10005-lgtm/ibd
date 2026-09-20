<?php

namespace Tests\Feature;

use App\Enums\AccountStatus;
use App\Enums\Role;
use App\Models\Rt;
use App\Models\SuratPengajuan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RtMultiTenantTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(Role $role, ?Rt $rt = null): User
    {
        return User::factory()->create([
            'role' => $role,
            'status' => AccountStatus::Active,
            'rt_id' => $rt?->id,
        ]);
    }

    public function test_halaman_dev_hanya_untuk_developer(): void
    {
        $rt = Rt::create(['kode' => 'RT04', 'nama' => 'RT 04', 'rw' => 'RW 02']);
        $dev = $this->makeUser(Role::Developer);
        $admin = $this->makeUser(Role::Admin, $rt);

        $this->actingAs($dev)->get(route('dev.rt.index'))->assertOk();
        $this->actingAs($admin)->get(route('dev.rt.index'))->assertForbidden();
    }

    public function test_dev_bisa_tambah_rt_dan_tetapkan_admin(): void
    {
        $dev = $this->makeUser(Role::Developer);
        $admin = $this->makeUser(Role::Admin);

        $this->actingAs($dev)->post(route('dev.rt.store'), [
            'kode' => 'RT09',
            'nama' => 'RT 09',
            'rw' => 'RW 02',
        ])->assertRedirect();

        $rt = Rt::where('kode', 'RT09')->firstOrFail();

        $this->actingAs($dev)->post(route('dev.rt.assign-admin', $rt), [
            'user_id' => $admin->id,
        ])->assertRedirect();

        $this->assertSame($rt->id, $admin->fresh()->rt_id);
        $this->assertTrue($admin->fresh()->canAccessRt($rt->id));
    }

    public function test_admin_hanya_akses_rt_sendiri(): void
    {
        $a = Rt::create(['kode' => 'RT04', 'nama' => 'RT 04']);
        $b = Rt::create(['kode' => 'RT05', 'nama' => 'RT 05']);
        $admin = $this->makeUser(Role::Admin, $a);

        $milik = SuratPengajuan::create([
            'rt_id' => $a->id, 'no_surat' => 'A-1', 'jenis' => 'Domisili',
            'pemohon' => 'Warga A', 'status' => 'Menunggu',
        ]);
        $tetangga = SuratPengajuan::create([
            'rt_id' => $b->id, 'no_surat' => 'B-1', 'jenis' => 'Domisili',
            'pemohon' => 'Warga B', 'status' => 'Menunggu',
        ]);

        $this->actingAs($admin)->get(route('surat.detail', $milik))->assertOk();
        $this->actingAs($admin)->get(route('surat.detail', $tetangga))->assertForbidden();
    }

    public function test_super_admin_tanpa_batas_akses_semua_rt(): void
    {
        $a = Rt::create(['kode' => 'RT04', 'nama' => 'RT 04']);
        $b = Rt::create(['kode' => 'RT05', 'nama' => 'RT 05']);
        $sa = $this->makeUser(Role::SuperAdmin);

        $this->assertTrue($sa->canAccessRt($a->id));
        $this->assertTrue($sa->canAccessRt($b->id));

        $sa->rts()->attach($a->id);

        $this->assertTrue($sa->fresh()->canAccessRt($a->id));
        $this->assertFalse($sa->fresh()->canAccessRt($b->id));
    }
}
