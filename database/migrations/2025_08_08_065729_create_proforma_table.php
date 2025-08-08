<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('proforma', function (Blueprint $table) {
            $table->id('proforma_id');

            // from CMIS-api by sending on the ein  -data of the decease employee
            $table->string('deceased_ein', 10);
            $table->string('deceased_emp_name', 255);
            $table->string('deceased_field_dept_cd', 20);
            $table->string('deceased_field_dept_desc', 255);
            $table->integer('deceased_adm_dept_cd')->nullable();
            $table->string('deceased_adm_dept_desc', 255);
            $table->string('deceased_emp_desig', 255);
            $table->string('deceased_emp_group', 255);
            $table->date('deceased_doa');
            $table->date('deceased_dob');


            // from user -data of the decease employee
            $table->boolean('expire_on_duty');
            $table->date('deceased_doe')->comment("Date of expiry");
            $table->string('deceased_causeofdeath', 300)->nullable()->comment("if expire on duty give reason");


            //applicant request post
            $table->string('request_dsg_srno_1', 20);
            $table->string('request_group_code_1', 20);
            $table->string('request_dsg_srno_2', 20);
            $table->string('request_group_code_2', 20);

            //request for 3 other departments
            $table->string('request_field_dept_cd', 20);
            $table->string('request_field_dept_desc', 255);
            $table->string('request_dsg_srno_3', 20);
            $table->string('request_group_code_3', 20);


            //applicant details 
            $table->string('applicant_name', 255);
            $table->unsignedBigInteger('relationship_id');
            $table->string('relationship_name', 255)->comment('applicant relationship with the decease emp');
            $table->date('applicant_dob');
            $table->string('applicant_mobile', 10);
            $table->string('applicant_email', 255);
            $table->string('applicant_sex', 20)->enum('male', 'female', 'transgender');

            // applicant other details
            $table->unsignedBigInteger('caste_id');
            $table->boolean('physically_handicapped');
            $table->unsignedBigInteger('applicant_qualification_id');
            $table->string('applicant_qualification_name', 255)->nullable();

            //applicant address
            $table->string('applicant_current_locality', 255);
            $table->unsignedBigInteger('applicant_current_state_id');
            $table->unsignedBigInteger('applicant_current_district_id');
            $table->unsignedBigInteger('applicant_current_subdivision_id');
            $table->integer('applicant_current_pincode');

            $table->string('applicant_permanent_locality', 255);
            $table->unsignedBigInteger('applicant_permanent_state_id');
            $table->unsignedBigInteger('applicant_permanent_district_id');
            $table->unsignedBigInteger('applicant_permanent_subdivision_id');
            $table->integer('applicant_permanent_pincode');








            // required for dynamic process
            $table->integer('process_id')->nullable();
            $table->string('proforma_status', 30)->enum('new', 'pending', 'rejected', 'reverted', 'completed')->nullable();
            $table->integer('process_sequence')->nullable();

            // others
            $table->timestamps();
            $table->unsignedBigInteger('create_by');

            // Optional: If you want to support soft deletes
            // $table->softDeletes();

            //foreign keys
            $table->foreign('relationship_id')->references('relationship_id')->on('relationships');
            $table->foreign('applicant_current_state_id')->references('state_id')->on('states');
            $table->foreign('applicant_permanent_state_id')->references('state_id')->on('states');

            $table->foreign('applicant_current_district_id')->references('district_id')->on('districts');
            $table->foreign('applicant_permanent_district_id')->references('district_id')->on('districts');

            $table->foreign('applicant_current_subdivision_id')->references('subdivision_id')->on('subdivisions');
            $table->foreign('caste_id')->references('caste_id')->on('castes');
            $table->foreign('applicant_qualification_id')->references('qualification_id')->on('qualifications');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proforma');
    }
};
