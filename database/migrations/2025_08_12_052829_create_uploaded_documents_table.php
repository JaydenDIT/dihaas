<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uploaded_documents', function (Blueprint $table) {
            $table->id('uploaded_document_id');

            $table->unsignedBigInteger('proforma_id');
            $table->unsignedBigInteger('document_list_id');

            // File details
            $table->string('file_path'); // storage/app/public/...
            $table->string('file_name')->nullable(); // original name
            $table->string('file_type')->nullable(); // mime type
            $table->unsignedBigInteger('file_size')->nullable(); // in bytes

            // Status flags
            $table->boolean('verified')->default(false);
            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->foreign('proforma_id')
                ->references('proforma_id')
                ->on('proforma')
                ->cascadeOnDelete();

            $table->foreign('document_list_id')
                ->references('document_list_id')
                ->on('document_list')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uploaded_documents');
    }
};
