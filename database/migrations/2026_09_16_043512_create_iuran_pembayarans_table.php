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
        Schema::create('iuran_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->nullable()->constrained('wargas')->nullOnDelete();
            $table->string('nama_pembayar');
            $table->string('periode', 7);
            $table->unsignedInteger('jumlah')->default(50000);
            $table->date('tanggal_bayar')->nullable();
            $table->enum('status', ['Lunas', 'Menunggak'])->default('Lunas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iuran_pembayarans');
    }
};
