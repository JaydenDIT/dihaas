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
        Schema::create('tasks_role_mapping', function (Blueprint $table) {
            $table->id('tasks_role_mapping_id'); // bigserial primary key
            $table->unsignedBigInteger('tasks_id');
            $table->unsignedBigInteger('role_id');

            // Optional: Add foreign key constraints
            $table->foreign('tasks_id')->references('tasks_id')->on('tasks')->onDelete('cascade');
            $table->foreign('role_id')->references('role_id')->on('roles')->onDelete('cascade');
            $table->unique(['tasks_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks_role_mapping');
    }
};
