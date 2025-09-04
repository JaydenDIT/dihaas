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
        Schema::table('proforma', function (Blueprint $table) {
            //The date on which proforma is submitted
            $table->date('proforma_submission_date')->after('updated_at')->nullable()->comment('The date on which proforma is submitted');
        });

        // Set value = created_at for all existing rows
        DB::statement("UPDATE proforma SET proforma_submission_date = DATE(created_at)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proforma', function (Blueprint $table) {
            $table->dropColumn('proforma_submission_date');
        });
    }
};
