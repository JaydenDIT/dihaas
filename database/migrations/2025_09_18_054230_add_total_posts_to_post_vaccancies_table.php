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
        Schema::table('post_vaccancies', function (Blueprint $table) {
            $table->integer('total_vaccant_post')->default(0)->comment('Total number of vaccant posts submitted for a department');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('The user Id of the user who updates the record.');
        });

        // Set value for total_posts for all existing rows
        DB::statement("UPDATE post_vaccancies SET total_vaccant_post = no_of_posts_dia + no_of_posts_dr");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_vaccancies', function (Blueprint $table) {
            $table->dropColumn('total_posts');
        });
    }
};
