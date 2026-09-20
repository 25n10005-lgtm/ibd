<?php

namespace Tests\Feature;

use App\Enums\AccountStatus;
use App\Enums\Role;
use App\Models\Keluarga;
use App\Models\Pengaduan;
use App\Models\Pengumuman;
use App\Models\Rt;
use App\Models\User;
use App\Models\UserWargaLink;
use App\Models\Warga;
use App\Models\WargaClaim;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WargaVerificationTest extends TestCase
{
    use RefreshDatabase;

    private function makeRt(): Rt
    {
        return Rt::create(['kode' => 'RT04', 'nama' => 'RT 04', 'rw' => 'RW 02']);
    }

    private function makeUser(Role $role, ?Rt $rt = null): User
    {
        return User::factory()->create([
            'role' => $role,
            'status' => AccountStatus::Active,
            'rt_id' => $rt?->id,
        ]);
    }

    private function makeWarga(Rt $rt, array $overrides = []): Warga
    {
        $keluarga = Keluarga::create([
            'rt_id' => $rt->id,
            'no_kk' => '3174051201900012',
            'kepala_keluarga' => 'Budi Santoso',
            'alamat' => 'Blok C 12',
        ]);

        return Warga::create(array_merge([
            'keluarga_id' => $keluarga->id,
            'nik' => '3174051201900001',
            'nama' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-12',
            'status_tinggal' => 'Tetap',
            'aktif' => true,
            'alamat' => 'Blok C 12',
        ], $overrides));
    }

    private function validInput(Warga $warga): array
    {
        return [
            'nik' => $warga->nik,
            'no_kk' => $warga->keluarga->no_kk,
            'nama' => $warga->nama,
            'tanggal_lahir' => $warga->tanggal_lahir->toDateString(),
        ];
    }

    public function test_nik_tidak_ditemukan_ditolak(): void
    {
        $rt = $this->makeRt();
        $user = $this->makeUser(Role::User, $rt);

        $this->actingAs($user)->post(route('saya.keluarga.verifikasi.cek'), [
            'nik' => '3174051201900099',
            'no_kk' => '3174051201900012',
            'nama' => 'Siapa Saja',
            'tanggal_lahir' => '1990-01-01',
        ])->assertRedirect()->assertSessionHasErrors('nik');

        $this->assertDatabaseCount('warga_claims', 0);
    }

    public function test_nik_sudah_tertaut_akun_lain_ditolak(): void
    {
        $rt = $this->makeRt();
        $warga = $this->makeWarga($rt);
        $admin = $this->makeUser(Role::Admin, $rt);
        $user = $this->makeUser(Role::User, $rt);
        $other = $this->makeUser(Role::User, $rt);

        UserWargaLink::create([
            'user_id' => $other->id,
            'warga_id' => $warga->id,
            'verified_by' => $admin->id,
            'verified_at' => now(),
        ]);

        $this->actingAs($user)->post(route('saya.keluarga.verifikasi.cek'), $this->validInput($warga))
            ->assertRedirect()
            ->assertSessionHasErrors('nik');

        $this->assertDatabaseCount('warga_claims', 0);
    }

    public function test_nik_rt_lain_ditolak(): void
    {
        $a = Rt::create(['kode' => 'RT04', 'nama' => 'RT 04']);
        $b = Rt::create(['kode' => 'RT05', 'nama' => 'RT 05']);
        $warga = $this->makeWarga($b);
        $user = $this->makeUser(Role::User, $a);

        $this->actingAs($user)->post(route('saya.keluarga.verifikasi.cek'), $this->validInput($warga))
            ->assertRedirect()
            ->assertSessionHasErrors('nik');
    }

    public function test_data_dasar_tidak_cocok_ditolak(): void
    {
        $rt = $this->makeRt();
        $warga = $this->makeWarga($rt);
        $user = $this->makeUser(Role::User, $rt);

        $input = $this->validInput($warga);
        $input['nama'] = 'Nama Salah Total';

        $this->actingAs($user)->post(route('saya.keluarga.verifikasi.cek'), $input)
            ->assertRedirect()
            ->assertSessionHasErrors('nik');
    }

    public function test_alur_cocok_ajukan_lalu_disetujui_admin(): void
    {
        $rt = $this->makeRt();
        $warga = $this->makeWarga($rt);
        $user = $this->makeUser(Role::User, $rt);
        $admin = $this->makeUser(Role::Admin, $rt);

        // Langkah 1: cek -> layar konfirmasi tersensor.
        $this->actingAs($user)->post(route('saya.keluarga.verifikasi.cek'), $this->validInput($warga))
            ->assertOk()
            ->assertSee('B***');

        // Langkah 2: ajukan -> klaim + 4 jejak gate.
        $this->actingAs($user)->post(route('saya.keluarga.verifikasi.ajukan'))
            ->assertRedirect(route('saya.keluarga'));

        $claim = WargaClaim::where('user_id', $user->id)->firstOrFail();
        $this->assertSame(WargaClaim::STATUS_DIAJUKAN, $claim->status);
        $this->assertCount(4, $claim->checks);
        $this->assertTrue($claim->checks->every(fn ($c) => $c->passed));

        // Admin: antrean terlihat, lalu setujui.
        $this->actingAs($admin)->get(route('verifikasi.index'))->assertOk()->assertSee($warga->nama);
        $this->actingAs($admin)->post(route('verifikasi.approve', $claim))->assertRedirect();

        $this->assertSame(WargaClaim::STATUS_DISETUJUI, $claim->fresh()->status);
        $this->assertDatabaseHas('user_warga_links', [
            'user_id' => $user->id,
            'warga_id' => $warga->id,
        ]);
        $this->assertDatabaseHas('warga_claim_decisions', [
            'claim_id' => $claim->id,
            'decision' => WargaClaim::STATUS_DISETUJUI,
        ]);
    }

    public function test_admin_menolak_dengan_alasan(): void
    {
        $rt = $this->makeRt();
        $warga = $this->makeWarga($rt);
        $user = $this->makeUser(Role::User, $rt);
        $admin = $this->makeUser(Role::Admin, $rt);

        $this->actingAs($user)->post(route('saya.keluarga.verifikasi.cek'), $this->validInput($warga))->assertOk();
        $this->actingAs($user)->post(route('saya.keluarga.verifikasi.ajukan'))->assertRedirect();

        $claim = WargaClaim::where('user_id', $user->id)->firstOrFail();

        $this->actingAs($admin)->post(route('verifikasi.reject', $claim), ['reason' => 'Foto KTP tidak jelas, ajukan ulang.'])
            ->assertRedirect();

        $this->assertSame(WargaClaim::STATUS_DITOLAK, $claim->fresh()->status);
        $this->assertDatabaseMissing('user_warga_links', ['user_id' => $user->id]);
    }

    public function test_halaman_tambah_terpisah_dan_modal_hanya_preview(): void
    {
        $rt = $this->makeRt();
        $user = $this->makeUser(Role::User, $rt);

        $this->actingAs($user)->get(route('saya.surat.create'))->assertOk()->assertSee('Kirim Pengajuan');
        $this->actingAs($user)->get(route('saya.aduan.create'))->assertOk()->assertSee('Kirim Pengaduan');
        $this->actingAs($user)->get(route('saya.surat'))->assertOk()->assertSee('previewSurat', false);
        $this->actingAs($user)->get(route('saya.aduan'))->assertOk()->assertSee('previewAduan', false);
    }

    public function test_akun_tertaut_bisa_ganti_nik_via_verifikasi_ulang(): void
    {
        $rt = $this->makeRt();
        $wargaA = $this->makeWarga($rt);
        $keluargaB = Keluarga::create([
            'rt_id' => $rt->id,
            'no_kk' => '3174051201900099',
            'kepala_keluarga' => 'Siti Aminah',
            'alamat' => 'Blok D 1',
        ]);
        $wargaB = Warga::create([
            'keluarga_id' => $keluargaB->id,
            'nik' => '3174051201900002',
            'nama' => 'Siti Aminah',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '1992-05-20',
            'status_tinggal' => 'Tetap',
            'aktif' => true,
            'alamat' => 'Blok D 1',
        ]);
        $user = $this->makeUser(Role::User, $rt);
        $admin = $this->makeUser(Role::Admin, $rt);

        UserWargaLink::create([
            'user_id' => $user->id,
            'warga_id' => $wargaA->id,
            'verified_by' => $admin->id,
            'verified_at' => now(),
        ]);

        // Form tetap bisa dibuka (mode ganti), bukan redirect.
        $this->actingAs($user)->get(route('saya.keluarga.verifikasi'))
            ->assertOk()->assertSee('Ganti NIK Tertaut');

        // NIK yang sama ditolak; NIK lain yang valid lolos.
        $this->actingAs($user)->post(route('saya.keluarga.verifikasi.cek'), $this->validInput($wargaA))
            ->assertRedirect()->assertSessionHasErrors('nik');

        $this->actingAs($user)->post(route('saya.keluarga.verifikasi.cek'), [
            'nik' => $wargaB->nik,
            'no_kk' => $keluargaB->no_kk,
            'nama' => $wargaB->nama,
            'tanggal_lahir' => '1992-05-20',
        ])->assertOk();

        $this->actingAs($user)->post(route('saya.keluarga.verifikasi.ajukan'))->assertRedirect();
        $claim = WargaClaim::where('user_id', $user->id)->latest()->firstOrFail();

        $this->actingAs($admin)->post(route('verifikasi.approve', $claim))->assertRedirect();

        // Tautan lama diganti, tidak duplikat.
        $this->assertSame($wargaB->id, $user->fresh()->linkedWarga()?->id);
        $this->assertSame(1, UserWargaLink::where('user_id', $user->id)->count());
    }

    public function test_pengumuman_detail_bisa_dibuka_warga(): void
    {
        $rt = $this->makeRt();
        $user = $this->makeUser(Role::User, $rt);

        $item = Pengumuman::create([
            'rt_id' => $rt->id,
            'judul' => 'Kerja Bakti Akbar',
            'ringkasan' => 'Gotong royong membersihkan selokan.',
            'gambar' => '',
            'kategori' => 'Kegiatan',
            'status' => 'terbit',
            'published_at' => now(),
        ]);

        $this->actingAs($user)->get(route('saya.pengumuman'))->assertOk()->assertSee('Kerja Bakti Akbar');
        $this->actingAs($user)->get(route('saya.pengumuman.show', $item))
            ->assertOk()->assertSee('Gotong royong');

        $this->assertSame(1, $item->fresh()->views);

        $draf = Pengumuman::create([
            'rt_id' => $rt->id,
            'judul' => 'Masih Draf',
            'ringkasan' => 'Belum tayang.',
            'gambar' => '',
            'kategori' => 'Informasi',
            'status' => 'draf',
        ]);

        $this->actingAs($user)->get(route('saya.pengumuman.show', $draf))->assertNotFound();
    }

    public function test_aduan_menampilkan_laporan_warga_lain_dan_tab_status(): void
    {
        $rt = $this->makeRt();
        $user = $this->makeUser(Role::User, $rt);
        $user->update(['name' => 'Budi Santoso']);

        Pengaduan::create([
            'rt_id' => $rt->id, 'judul' => 'Lampu Saya Mati', 'lokasi' => 'Blok C',
            'pelapor' => 'Budi Santoso', 'status' => 'Belum',
        ]);
        Pengaduan::create([
            'rt_id' => $rt->id, 'judul' => 'Jalan Tetangga Rusak', 'lokasi' => 'Blok D',
            'pelapor' => 'Tetangga Sebelah', 'status' => 'Proses',
        ]);

        $this->actingAs($user)->get(route('saya.aduan'))
            ->assertOk()
            ->assertSee('Lampu Saya Mati')
            ->assertSee('Laporan warga lain')
            ->assertSee('Jalan Tetangga Rusak');

        $this->actingAs($user)->get(route('saya.aduan', ['status' => 'Belum']))
            ->assertOk()->assertSee('Lampu Saya Mati');
    }

    public function test_sidebar_punya_dropdown_pengajuan_saya(): void
    {
        $rt = $this->makeRt();
        $user = $this->makeUser(Role::User, $rt);

        $this->actingAs($user)->get(route('saya.dashboard'))
            ->assertOk()->assertSee('Pengajuan Saya', false);
    }
}
