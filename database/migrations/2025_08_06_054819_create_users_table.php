<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id'); // bigserial primary key
            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->string('remember_token', 100)->nullable();

            $table->unsignedInteger('role_id')->nullable(); //from role table 
            $table->string('username', 30)->nullable();
            $table->string('mobile', 10)->nullable();
            $table->unsignedInteger('department_id')->nullable(); //departments table 
            $table->integer('attempts')->nullable();
            $table->date('last_attempt_date')->nullable();
            $table->boolean('active_status')->default(true)->nullable();
            $table->unsignedInteger('post_id')->nullable();  //cmis api

            // Foreign key constraints
            $table->foreign('role_id')->references('role_id')->on('roles');
            $table->foreign('department_id')->references('department_id')->on('departments');
            // Indexes can be added if needed
            $table->timestamps(); // adds created_at and updated_at
            $table->softDeletes(); // adds deleted_at for soft deletes
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
