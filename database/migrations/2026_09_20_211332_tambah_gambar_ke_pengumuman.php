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
        // Zero-null: string kosong = tanpa gambar (bukan NULL).
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->string('gambar', 500)->default('')->after('ringkasan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->dropColumn('gambar');
        });
    }
};
