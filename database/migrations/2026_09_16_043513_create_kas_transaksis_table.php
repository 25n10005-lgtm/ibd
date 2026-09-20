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
        Schema::create('kas_transaksis', function (Blueprint $table) {
            $table->id();
            $table->enum('arah', ['masuk', 'keluar']);
            $table->string('deskripsi');
            $table->unsignedInteger('jumlah');
            $table->string('kategori', 50)->nullable();
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas_transaksis');
    }
};
