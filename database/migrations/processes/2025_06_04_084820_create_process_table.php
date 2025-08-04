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
        Schema::create('process', function (Blueprint $table) {
            $table->id('process_id');
            $table->string('process_name', 255);
            $table->text('process_description')->nullable();
            $table->text('process_criteria')->nullable();
            $table->integer('total_tasks')->nullable();
            $table->timestamps(); // adds created_at and updated_at
            $table->softDeletes(); // adds deleted_at for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process');
    }
};
