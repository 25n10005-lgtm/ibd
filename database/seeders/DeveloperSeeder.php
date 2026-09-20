<?php

namespace Database\Seeders;

use App\Enums\AccountStatus;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DeveloperSeeder extends Seeder
{
    /**
     * Promosikan akun (berdasar email) menjadi Developer.
     * Jalankan: DEVELOPER_EMAIL=anda@email.com php artisan db:seed --class=DeveloperSeeder
     */
    public function run(): void
    {
        $email = env('DEVELOPER_EMAIL');

        if (! $email) {
            $this->command->warn('Set DEVELOPER_EMAIL terlebih dahulu.');

            return;
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->command->warn("User dengan email {$email} belum terdaftar. Daftar dulu via /login.");

            return;
        }

        $user->update([
            'role' => Role::Developer,
            'requested_role' => null,
            'status' => AccountStatus::Active,
        ]);

        $this->command->info("{$email} kini Developer.");
    }
}
