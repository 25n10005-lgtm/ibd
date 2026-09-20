<?php

namespace Database\Seeders;

use App\Enums\AccountStatus;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Promosikan akun (berdasar email) menjadi Admin (Pengelola).
     *
     * Kalau email sudah terdaftar (mis. habis login via Google sebagai
     * Warga), akunnya dipromosikan. Kalau belum, dibuatkan baris baru —
     * pemiliknya cukup masuk via Google (email sama = akun sama).
     *
     * Jalankan dari host:
     * $env:DB_HOST="127.0.0.1"; $env:DB_PORT="3307";
     * $env:ADMIN_EMAIL="admin@email.com";
     * php artisan db:seed --class=AdminSeeder
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');

        if (! $email) {
            $this->command->warn('Set ADMIN_EMAIL terlebih dahulu.');

            return;
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = User::create([
                'name' => env('ADMIN_NAME') ?: strstr($email, '@', true),
                'email' => $email,
                'password' => Str::random(32),
                'role' => Role::User,
                'status' => AccountStatus::Active,
            ]);

            $this->command->info("Akun {$email} dibuat. Masuk sekali via Google untuk mengaktifkannya.");
        }

        $user->update([
            'role' => Role::Admin,
            'requested_role' => null,
            'status' => AccountStatus::Active,
        ]);

        $this->command->info("{$email} kini Admin (Pengelola).");
    }
}
