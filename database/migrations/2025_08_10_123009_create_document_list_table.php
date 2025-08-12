<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('document_list', function (Blueprint $table) {
            $table->id('document_list_id');
            $table->string('document_name');
            $table->string('document_criteria'); // key from config/documentCriteria.php
            $table->integer('max_size_kb')->default(2048);
            $table->string('document_type'); // or json if multi-type support needed
            $table->timestamps();
            $table->softDeletes();  // deleted_at for soft deletion
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_list');
    }
};
