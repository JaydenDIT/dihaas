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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id('user_detail_id');

            $table->unsignedBigInteger('user_id');
            $table->string('gender', 20);

            $table->string('relative_name',75);
            $table->unsignedBigInteger('relationship_id');

            $table->string('current_address1');
            $table->string('current_address2')->nullable();
            $table->string('current_address3')->nullable();
            $table->string('current_pin', 6);
            $table->unsignedBigInteger('current_country_id');
            $table->unsignedBigInteger('current_state_id');
            $table->unsignedBigInteger('current_district_id');

            $table->string('permanent_address1');
            $table->string('permanent_address2')->nullable();
            $table->string('permanent_address3')->nullable();
            $table->string('permanent_pin', 6);
            $table->unsignedBigInteger('permanent_country_id');
            $table->unsignedBigInteger('permanent_state_id');
            $table->unsignedBigInteger('permanent_district_id');

            $table->string('place_status', 10)->nullable();
            $table->string('education_status', 10)->nullable();

            $table->unsignedBigInteger('idproof_id')->nullable();
            $table->string('idproof_number', 75)->nullable();
            $table->text('idproof_link')->nullable();
            
            $table->timestamps();

            $table->boolean('bpl_status')->default(0)->comment('value is 0,1. 0 = Not. 1 = Yes.');
            $table->text('bpl_link')->nullable();


            $table->foreign('current_country_id')->references('country_id')->on('countries');
            $table->foreign('permanent_country_id')->references('country_id')->on('countries');
            $table->foreign('current_state_id')->references('state_id')->on('states');
            $table->foreign('permanent_state_id')->references('state_id')->on('states');
            $table->foreign('current_district_id')->references('district_id')->on('districts');
            $table->foreign('permanent_district_id')->references('district_id')->on('districts');
            $table->foreign('idproof_id')->references('idproof_id')->on('idproofs');
            $table->foreign('relationship_id')->references('relationship_id')->on('relationships_register');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_details');
    }
};
