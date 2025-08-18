<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uo_generations', function (Blueprint $table) {
            $table->id('uo_generation_id');

            //from proforma table 
            $table->unsignedBigInteger('proforma_id');
            $table->unsignedBigInteger('approve_for_uo_id');

            // auto generation 
            $table->string('uo_number', 70)->nullable();
            $table->string('generated_by', 255)->nullable();
            $table->date('generated_on')->nullable();

            //alloted post
            $table->boolean('is_applicant_choice_post')->default(true);
            $table->string('alloted_adm_dept_cd', 20);
            $table->string('alloted_adm_dept_desc', 255);
            $table->string('alloted_field_dept_cd', 20);
            $table->string('alloted_field_dept_desc', 255);
            $table->string('alloted_dsg_srno', 20)->comment('Post id selected or requested by the applicant');
            $table->string('alloted_dsg_desc', 255);
            $table->string('alloted_group_code', 20);

            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uo_generations');
    }
};
