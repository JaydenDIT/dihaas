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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id('tasks_id'); // bigserial primary key
            $table->string('tasks_name', 255);
            $table->text('tasks_description')->nullable();
            $table->unsignedBigInteger('create_by')->nullable();
            $table->string('tasks_duty', 255)->comment('Identifies the associated file/functionality name');
            $table->softDeletes(); // adds deleted_at column
            $table->timestamps(); // created_at and updated_at
            $table->foreign("create_by")->references("id")->on("users")->onDelete("set null");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
