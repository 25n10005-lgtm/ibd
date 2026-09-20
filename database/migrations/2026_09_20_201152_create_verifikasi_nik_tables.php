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
        // 1 akun <-> 1 NIK. Menggantikan pencocokan string nama.
        // Semua kolom NOT NULL (zero-null): baris ada = terverifikasi.
        Schema::create('user_warga_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->foreignId('warga_id')->unique()->constrained('wargas')->cascadeOnDelete();
            $table->foreignId('verified_by')->constrained('users');
            $table->timestamp('verified_at')->useCurrent();
            $table->timestamps();
        });

        // Pengajuan verifikasi NIK oleh warga (self-service).
        // Status: diajukan | disetujui | ditolak | dibatalkan.
        Schema::create('warga_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('warga_id')->constrained('wargas')->cascadeOnDelete();
            $table->string('status', 20)->default('diajukan');
            $table->timestamps();

            $table->unique(['user_id', 'warga_id']);
            $table->index('status');
        });

        // Jejak audit 4 gate otomatis per klaim. Semua NOT NULL;
        // detail memakai string kosong bila tidak ada keterangan.
        Schema::create('warga_claim_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained('warga_claims')->cascadeOnDelete();
            $table->string('check_type', 20);
            $table->boolean('passed')->default(false);
            $table->string('detail', 500)->default('');
            $table->timestamps();

            $table->unique(['claim_id', 'check_type']);
        });

        // Keputusan admin terpisah dari klaim agar klaim tetap
        // zero-null saat masih menunggu (belum ada decided_by).
        Schema::create('warga_claim_decisions', function (Blueprint $table) {
            $table->foreignId('claim_id')->primary()->constrained('warga_claims')->cascadeOnDelete();
            $table->foreignId('decided_by')->constrained('users');
            $table->string('decision', 20);
            $table->string('reason', 500)->default('');
            $table->timestamp('decided_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warga_claim_decisions');
        Schema::dropIfExists('warga_claim_checks');
        Schema::dropIfExists('warga_claims');
        Schema::dropIfExists('user_warga_links');
    }
};
