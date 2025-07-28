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
        Schema::create('employee_cmis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ein');              
            $table->string('title');             
            $table->string('emp_lname');          
            $table->string('ftitle');             
            $table->string('father_name');        
            $table->string('mother_name');        
            $table->string('spouse_name');        
            $table->char('pan_no', 10);             
            $table->string('gender', 6);             
            $table->date('emp_birth_dt');       
            $table->date('emp_entry_dt');       
            $table->string('field_dept_desc', 70);    
            $table->char('field_dept_cd', 3);      
            $table->string('admin_dept_desc', 70);    
            $table->decimal('admin_dept_cd', 2);      
            $table->char('ddo_code', 10);           
            $table->char('perm_ddo_id', 5);        
            $table->string('ddo_office_name', 100);    
            $table->string('ddo_designation', 100);    
            $table->string('off_name', 100);           
            $table->string('cdr_desc', 50);           
            $table->char('cdr_group_cd');       
            $table->decimal('cdr_supan_age');     
            $table->string('dsg_desc');           
            $table->date('emp_supan_dt');       
            $table->string('emp_mobile_no', 10);      
            $table->string('emp_email_id', 70);       
            $table->string('emp_addr_lcality', 100);   
            $table->string('emp_addr_subdiv', 70);    
            $table->string('emp_addr_district', 50);  
            $table->string('emp_addr_assem_cons', 70);
            $table->string('pay_comm_desc', 50);      
            $table->decimal('pay_comm_cd', 2);        
            $table->decimal('da_rate', 3);            
            $table->date('da_wef');             
            $table->string('psc_dscr', 70);           
            $table->decimal('pay_basic', 6);          
            $table->decimal('npa_val', 6);            
            $table->date('pay_basic_dt');       
            $table->date('pay_incr_dt');        
            $table->string('bnk_ac_no',20);          
            $table->string('bnk_name', 40);           
            $table->string('brnm', 40);               
            $table->char('ifsc_cd', 11);            
            $table->timestamp('pull_dt');            
            $table->string('pull_by', 30);            
            $table->decimal('last_gross_pay', 6);     
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_cmis');
    }
};
