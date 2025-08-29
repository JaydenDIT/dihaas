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
        Schema::create('post_vaccancies', function (Blueprint $table) {
            $table->id('vaccancy_id');
            $table->string('adm_dept_cd', 10)->comment('Administrative Department Code');
            $table->string('adm_dept_name', 150)->comment('Administrative Department Name');
            $table->string('field_dept_cd', 10)->comment('Field Department Code');
            $table->string('field_dept_name', 150)->comment('Field Department Name');
            $table->string('dsg_srno', 10)->comment('Post designation no');
            $table->string('dsg_name', 150)->comment('Post designation name');
            $table->integer('no_of_posts_dia')->comment('No. of post vaccancies for die in harness.');
            $table->integer('no_of_posts_dr')->comment('No. of post vaccancies for direct recruitment.');
            $table->unsignedBigInteger('created_by')->comment('The user who adds the record');
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
        Schema::dropIfExists('post_vaccancies');
    }
};
