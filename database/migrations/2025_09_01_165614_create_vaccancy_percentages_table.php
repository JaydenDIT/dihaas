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
        /**
         * This table is a configuration table that tell how many posts out of the total vaccant posts will be reserved
         * for the die-in-harness. The reservation is done in terms of percentage.
         * 
         * Note: This table must contain only one row.
         */
        Schema::create('vaccancy_percentages', function (Blueprint $table) {
            $table->id();
            $table->double('dia_percentage')->default(0.0)->comment('This tells how many posts out of the total vaccant posts 
            will be reserved for die-in-harness');
            $table->double('old_dia_percentage')->nullable()->default(0.0)->comment("Old reserved % value."); //just before the current value
            $table->date('effective_date')->comment('The effective date (w.e.f) from which reserved percentage is applicable');
            $table->date('old_effective_date')->nullable()->comment('The old year'); //just before the current value
            $table->timestamps();
            $table->comment("This table is a configuration table that tell how many posts out of the total vaccant posts will be reserved 
            for the die-in-harness. The reservation is done in terms of percentage.");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vaccancy_percentages');
    }
};
