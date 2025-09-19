<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('uo_file_submissions', function (Blueprint $table) {
            $table->id('uo_file_submission_id');

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
        // Only disable foreign key checks if DB is MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Schema::dropIfExists('uo_file_submissions');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        } else {
            Schema::dropIfExists('uo_file_submissions');
        }
    }
};
