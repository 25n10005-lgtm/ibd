<?php

namespace Database\Seeders;

use App\Enums\AccountStatus;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FirstChairSeeder extends Seeder
{
    /**
     * Bootstrap akun Ketua RT (Super Admin) pertama agar antrean
     * approval Admin bisa diproses.
     *
     * Kalau email sudah terdaftar (mis. habis login via Google sebagai
     * Warga), akunnya dipromosikan. Kalau belum, dibuatkan baris baru
     * tanpa password yang diketahui — pemiliknya cukup masuk via Google
     * (email sama = akun sama) dan langsung menjadi Ketua.
     *
     * Jalankan dari host:
     * $env:DB_HOST="127.0.0.1"; $env:DB_PORT="3307";
     * $env:FIRST_CHAIR_EMAIL="ketua@email.com";
     * php artisan db:seed --class=FirstChairSeeder
     */
    public function run(): void
    {
        $email = env('FIRST_CHAIR_EMAIL');

        if (! $email) {
            $this->command->warn('Set FIRST_CHAIR_EMAIL terlebih dahulu.');

            return;
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = User::create([
                'name' => env('FIRST_CHAIR_NAME') ?: strstr($email, '@', true),
                'email' => $email,
                'password' => Str::random(32),
                'role' => Role::User,
                'status' => AccountStatus::Active,
            ]);

            $this->command->info("Akun {$email} dibuat. Masuk sekali via Google untuk mengaktifkannya.");
        }

        $user->update([
            'role' => Role::SuperAdmin,
            'requested_role' => null,
            'status' => AccountStatus::Active,
        ]);

        $this->command->info("{$email} kini Ketua RT (Super Admin) dan bisa memvalidasi pengajuan Admin.");
    }
}
