<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id('department_id');
            $table->unsignedTinyInteger('ministry_id')->nullable(); // numeric(2)
            $table->string('department_name', 70)->nullable();            // varchar(70)
            $table->string('department_short_name', 30)->nullable();           // varchar(30)
            $table->foreign('ministry_id')->references('ministry_id')->on('ministry');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_dept');
    }
};
