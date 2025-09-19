<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('role_id');
            $table->string('role_name', 255);
            $table->enum('role_group', ['superadmin', 'single_department', 'all_department', 'citizen'])->default('citizen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only disable foreign key checks if DB is MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::dropIfExists('roles');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        } else {
            Schema::dropIfExists('roles');
        }
    }
};
