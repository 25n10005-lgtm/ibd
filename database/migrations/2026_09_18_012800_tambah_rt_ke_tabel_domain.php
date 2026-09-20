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
        foreach (['keluargas', 'surat_pengajuans', 'pengaduans', 'kegiatans', 'pengumuman', 'kas_transaksis', 'iuran_pembayarans'] as $table) {
            if (Schema::hasTable($table) && ! Schema::hasColumn($table, 'rt_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->foreignId('rt_id')->nullable()->after('id')->constrained('rts')->nullOnDelete();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['keluargas', 'surat_pengajuans', 'pengaduans', 'kegiatans', 'pengumuman', 'kas_transaksis', 'iuran_pembayarans'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropConstrainedForeignId('rt_id');
            });
        }
    }
};
