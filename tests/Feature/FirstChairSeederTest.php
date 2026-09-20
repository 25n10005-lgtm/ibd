<?php

namespace Tests\Feature;

use App\Enums\AccountStatus;
use App\Enums\Role;
use App\Models\User;
use Database\Seeders\FirstChairSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FirstChairSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        unset($_ENV['FIRST_CHAIR_EMAIL'], $_SERVER['FIRST_CHAIR_EMAIL']);
        unset($_ENV['FIRST_CHAIR_NAME'], $_SERVER['FIRST_CHAIR_NAME']);

        parent::tearDown();
    }

    public function test_mempromosikan_warga_menjadi_ketua(): void
    {
        $user = User::factory()->create([
            'email' => 'ketua@example.com',
            'role' => Role::User,
            'status' => AccountStatus::Active,
        ]);

        $this->withChairEmail('ketua@example.com');
        $this->seed(FirstChairSeeder::class);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => Role::SuperAdmin->value,
            'requested_role' => null,
            'status' => AccountStatus::Active->value,
        ]);
    }

    public function test_membuat_akun_baru_untuk_ketua(): void
    {
        $this->withChairEmail('baru@example.com');
        $this->seed(FirstChairSeeder::class);

        $this->assertDatabaseHas('users', [
            'email' => 'baru@example.com',
            'role' => Role::SuperAdmin->value,
            'status' => AccountStatus::Active->value,
        ]);
        $this->assertEquals(1, User::count());
    }

    public function test_tanpa_email_tidak_mengubah_apa_pun(): void
    {
        $this->seed(FirstChairSeeder::class);

        $this->assertDatabaseCount('users', 0);
    }

    private function withChairEmail(string $email): void
    {
        $_ENV['FIRST_CHAIR_EMAIL'] = $email;
        $_SERVER['FIRST_CHAIR_EMAIL'] = $email;
    }
}
