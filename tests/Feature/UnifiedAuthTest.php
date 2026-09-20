<?php

namespace Tests\Feature;

use App\Enums\AccountStatus;
use App\Enums\Role;
use App\Models\Rt;
use App\Models\User;
use App\Services\ClerkSessionVerifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class UnifiedAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_warga_langsung_aktif(): void
    {
        $rt = Rt::create(['kode' => 'RT04', 'nama' => 'RT 04']);

        $response = $this->post(route('register.store'), [
            'name' => 'Budi Warga',
            'email' => 'budi@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'warga',
            'rt_id' => $rt->id,
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'budi@example.com',
            'role' => Role::User->value,
            'status' => AccountStatus::Active->value,
            'requested_role' => null,
            'rt_id' => $rt->id,
        ]);
    }

    public function test_halaman_register_menampilkan_dropdown_rt(): void
    {
        $rt = Rt::create(['kode' => 'RT04', 'nama' => 'RT 04', 'rw' => 'RW 02']);

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('RT Tempat Tinggal')
            ->assertSee($rt->label(), false);
    }

    public function test_register_wajib_pilih_rt(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Tanpa RT',
            'email' => 'tanpart@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'warga',
        ])->assertSessionHasErrors('rt_id');

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'tanpart@example.com']);
    }

    public function test_register_admin_masuk_antrean_approval(): void
    {
        $rt = Rt::create(['kode' => 'RT04', 'nama' => 'RT 04']);

        $response = $this->post(route('register.store'), [
            'name' => 'Siti Admin',
            'email' => 'siti@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
            'rt_id' => $rt->id,
        ]);

        $response->assertRedirect(route('account.pending'));

        $this->assertDatabaseHas('users', [
            'email' => 'siti@example.com',
            'role' => Role::User->value,
            'requested_role' => Role::Admin->value,
            'status' => AccountStatus::Pending->value,
            'rt_id' => $rt->id,
        ]);

        // Masuk antrean yang dilihat Super Admin.
        $super = User::factory()->create([
            'role' => Role::SuperAdmin,
            'status' => AccountStatus::Active,
        ]);

        $this->actingAs($super)
            ->get(route('admin.approvals'))
            ->assertOk()
            ->assertSee('siti@example.com');
    }

    public function test_login_email_password(): void
    {
        User::factory()->create([
            'email' => 'budi@example.com',
            'role' => Role::User,
            'status' => AccountStatus::Active,
        ]);

        $response = $this->post(route('login.attempt'), [
            'email' => 'budi@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs(User::where('email', 'budi@example.com')->first());
    }

    public function test_login_gagal_password_salah(): void
    {
        User::factory()->create(['email' => 'budi@example.com']);

        $this->post(route('login.attempt'), [
            'email' => 'budi@example.com',
            'password' => 'salah-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_akun_pending_dialihkan_ke_halaman_pending(): void
    {
        $user = User::factory()->create([
            'role' => Role::User,
            'requested_role' => Role::Admin,
            'status' => AccountStatus::Pending,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertRedirect(route('account.pending'));
    }

    public function test_warga_bisa_ajukan_admin_dan_tetap_aktif(): void
    {
        $user = User::factory()->create([
            'role' => Role::User,
            'status' => AccountStatus::Active,
        ]);

        $this->actingAs($user)
            ->post(route('account.request-role'), ['role' => 'admin'])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'requested_role' => Role::Admin->value,
            // Tetap aktif selama menunggu validasi.
            'status' => AccountStatus::Active->value,
        ]);

        // Terlihat di antrean approval.
        $super = User::factory()->create([
            'role' => Role::SuperAdmin,
            'status' => AccountStatus::Active,
        ]);

        $this->actingAs($super)
            ->get(route('admin.approvals'))
            ->assertSee($user->email);
    }

    public function test_admin_bisa_ajukan_ketua_rt(): void
    {
        $user = User::factory()->create([
            'role' => Role::Admin,
            'status' => AccountStatus::Active,
        ]);

        $this->actingAs($user)
            ->post(route('account.request-role'), ['role' => 'super_admin'])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'requested_role' => Role::SuperAdmin->value,
            'status' => AccountStatus::Active->value,
        ]);
    }

    public function test_super_admin_menyetujui_pengajuan(): void
    {
        $super = User::factory()->create([
            'role' => Role::SuperAdmin,
            'status' => AccountStatus::Active,
        ]);
        $calon = User::factory()->create([
            'role' => Role::User,
            'requested_role' => Role::Admin,
            'status' => AccountStatus::Pending,
        ]);

        $this->actingAs($super)
            ->post(route('admin.approvals.approve', $calon))
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $calon->id,
            'role' => Role::Admin->value,
            'requested_role' => null,
            'status' => AccountStatus::Active->value,
        ]);
    }

    public function test_tolak_upgrade_mengembalikan_aktif(): void
    {
        $super = User::factory()->create([
            'role' => Role::SuperAdmin,
            'status' => AccountStatus::Active,
        ]);
        $warga = User::factory()->create([
            'role' => Role::User,
            'requested_role' => Role::Admin,
            'status' => AccountStatus::Active,
        ]);

        $this->actingAs($super)
            ->post(route('admin.approvals.reject', $warga))
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $warga->id,
            'role' => Role::User->value,
            'requested_role' => null,
            'status' => AccountStatus::Active->value,
        ]);
    }

    public function test_akun_google_dan_email_menyatu_lewat_email(): void
    {
        config()->set('clerk.secret_key', null);

        // Pengguna sudah daftar native lebih dulu.
        $native = User::factory()->create(['email' => 'nyatu@example.com']);

        $this->fakeClerkSession('clerk_google_1', 'nyatu@example.com');

        $this->withHeader('Authorization', 'Bearer token-google')
            ->get(route('dashboard'))
            ->assertRedirect(route('saya.dashboard'));

        // Tetap satu baris, clerk_id menempel ke akun yang sama.
        $this->assertEquals(1, User::where('email', 'nyatu@example.com')->count());
        $this->assertDatabaseHas('users', [
            'id' => $native->id,
            'email' => 'nyatu@example.com',
            'clerk_id' => 'clerk_google_1',
        ]);
    }

    public function test_daftar_google_dengan_pilihan_admin_masuk_approval(): void
    {
        config()->set('clerk.secret_key', null);

        $this->fakeClerkSession('clerk_baru_1', 'baru@example.com');

        $this->withHeader('Authorization', 'Bearer token-baru')
            ->get(route('dashboard', ['requested_role' => 'admin']))
            ->assertRedirect(route('account.pending'));

        $this->assertDatabaseHas('users', [
            'email' => 'baru@example.com',
            'clerk_id' => 'clerk_baru_1',
            'requested_role' => Role::Admin->value,
            'status' => AccountStatus::Pending->value,
        ]);
    }

    public function test_register_duplikat_email_google_ditolak_dengan_petunjuk(): void
    {
        User::factory()->create(['email' => 'ganda@example.com', 'clerk_id' => 'clerk_x']);

        $this->post(route('register.store'), [
            'name' => 'Ganda',
            'email' => 'ganda@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'warga',
        ])->assertSessionHasErrors('email');
    }

    public function test_atur_password_mengaktifkan_login_email(): void
    {
        $user = User::factory()->create([
            'clerk_id' => 'clerk_tanpa_pw',
            'status' => AccountStatus::Active,
        ]);

        // Akun Google: tanpa current_password.
        $this->actingAs($user)
            ->post(route('account.password'), [
                'password' => 'baru-password-1',
                'password_confirmation' => 'baru-password-1',
            ])
            ->assertRedirect();

        $this->post(route('logout'));

        $this->post(route('login.attempt'), [
            'email' => $user->email,
            'password' => 'baru-password-1',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs(User::find($user->id));
    }

    public function test_cookie_clerk_tidak_dienkripsi_laravel(): void
    {
        // Regresi: cookie __session Clerk (JWT mentah) tidak boleh
        // di-null-kan oleh EncryptCookies, kalau tidak login Google
        // selalu mental ke halaman login.
        config()->set('clerk.secret_key', null);

        $this->fakeClerkSession('clerk_cookie_1', 'cookie@example.com');

        $this->withUnencryptedCookie('__session', 'fake-jwt-token')
            ->get(route('dashboard'))
            ->assertRedirect(route('saya.dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'cookie@example.com',
            'clerk_id' => 'clerk_cookie_1',
        ]);
    }

    public function test_cookie_clerk_v6_bersufiks_dibaca_dari_header_mentah(): void
    {
        // Clerk v6 menamai cookie sesi __clerk_db_jwt_<sufiks acak> yang
        // tidak bisa dikecualikan dari EncryptCookies satu per satu.
        config()->set('clerk.secret_key', null);

        $this->fakeClerkSession('clerk_v6_1', 'v6@example.com');

        $this->withHeader('Cookie', '__clerk_db_jwt_j3-rSZQy=fake-jwt-token; smartrt-session=abc')
            ->get(route('dashboard'))
            ->assertRedirect(route('saya.dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'v6@example.com',
            'clerk_id' => 'clerk_v6_1',
        ]);
    }

    private function fakeClerkSession(string $sub, string $email): void
    {
        $verifier = Mockery::mock(ClerkSessionVerifier::class);
        $verifier->shouldReceive('verify')->andReturn((object) [
            'sub' => $sub,
            'email' => $email,
        ]);

        $this->app->instance(ClerkSessionVerifier::class, $verifier);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
