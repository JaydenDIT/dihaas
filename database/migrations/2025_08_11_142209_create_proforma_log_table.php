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
        Schema::create('proforma_log', function (Blueprint $table) {
            $table->id('proforma_log_id');
            $table->unsignedBigInteger('proforma_id');
            $table->unsignedBigInteger('action_by');
            $table->string('action_name', 75);
            $table->text('action_remark')->nullable();
            $table->timestamps();
            $table->foreign('proforma_id')->references('proforma_id')->on('proforma');
            $table->foreign('action_by')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proforma_log');
    }
};
