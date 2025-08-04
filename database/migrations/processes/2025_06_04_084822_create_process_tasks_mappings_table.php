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
        Schema::create('process_tasks_mappings', function (Blueprint $table) {
            $table->id('process_tasks_mapping_id');
            $table->unsignedBigInteger('process_id');
            $table->unsignedBigInteger('tasks_id');
            $table->integer('sequence');
            $table->integer('allow_drop')->default(0);
            $table->integer('allow_reject')->default(0);
            $table->integer('allow_esign')->default(0);

            // Optional: Add foreign keys if process and tasks tables exist
            $table->foreign('process_id')->references('process_id')->on('process')->onDelete('cascade');
            $table->foreign('tasks_id')->references('tasks_id')->on('tasks')->onDelete('cascade');
            $table->unique(['process_id', 'tasks_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process_tasks_mappings');
    }
};
