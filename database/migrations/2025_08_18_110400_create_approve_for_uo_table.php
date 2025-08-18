<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approve_for_uo', function (Blueprint $table) {
            $table->id('approve_for_uo_id');

            $table->unsignedBigInteger('proforma_id');
            $table->string('file_path'); // storage/app/public/...
            $table->string('file_name')->nullable(); // original name

            //uploaded by
            $table->unsignedBigInteger('uploaded_by');
            $table->text('remarks')->nullable();
            //verified by
            $table->boolean('verified')->default(false);
            $table->unsignedBigInteger('verified_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approve_for_uo');
    }
};
