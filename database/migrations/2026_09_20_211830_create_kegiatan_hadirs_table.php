<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Konfirmasi kehadiran warga per kegiatan. Semua NOT NULL;
        // status: hadir | tidak. UNIQUE mencegah duplikat per akun.
        Schema::create('kegiatan_hadirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatans')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status', 10)->default('hadir');
            $table->timestamps();

            $table->unique(['kegiatan_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_hadirs');
    }
};
