<?php

// database/migrations/2025_08_10_000000_create_families_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('family_details', function (Blueprint $table) {
            $table->id('family_detail_id');
            $table->unsignedBigInteger('proforma_id');
            $table->unsignedBigInteger('relationship_id');
            $table->string('fullname', 255);
            $table->enum('gender', ['male', 'female', 'transgender']);
            $table->date('dob');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('proforma_id')->references('proforma_id')->on('proforma')->onDelete('cascade');
            $table->foreign('relationship_id')->references('relationship_id')->on('relationships')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('family_details', function (Blueprint $table) {
            $table->dropForeign(['proforma_id']);
        });
        Schema::dropIfExists('family_details');
    }
};
