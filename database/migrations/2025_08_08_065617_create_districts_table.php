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
        Schema::create('districts', function (Blueprint $table) {
            $table->id('district_id');
            $table->unsignedBigInteger('state_id');
            $table->string('district_name', 255);
            $table->timestamps();   // created_at and updated_at
            $table->softDeletes();  // deleted_at for soft deletion
            $table->foreign('state_id')->references('state_id')->on('states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('districts');
    }
};
