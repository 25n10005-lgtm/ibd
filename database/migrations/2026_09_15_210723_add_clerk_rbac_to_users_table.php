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
        Schema::table('users', function (Blueprint $table): void {
            $table->string('clerk_id')->nullable()->unique()->after('id');
            $table->string('avatar')->nullable()->after('email');
            $table->string('role')->default('user')->after('avatar');
            $table->string('requested_role')->nullable()->after('role');
            $table->string('status')->default('active')->after('requested_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['clerk_id', 'avatar', 'role', 'requested_role', 'status']);
        });
    }
};
