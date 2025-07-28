<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProformaModel extends Model
{
    use HasFactory;
    protected $table = 'proforma';
	public $timestamps = true;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
    
    'id',
	'ein',
    'deceased_emp_name',
    'ministry',
    'dept_name',
    'desig_name',
    'grade_name',
    'deceased_doa',
    'deceased_doe',
    'deceased_dob',
    'deceased_causeofdeath',
    'appl_number',
    'appl_date',
    'applicant_name',
    'relationship',
    'applicant_dob',
    'applicant_edu_id',
    'applicant_mobile',
    'applicant_email_id',
    'caste_id',
    'applicant_desig_id',
    'applicant_grade',
    'emp_addr_lcality',
    'emp_addr_subdiv',
    'emp_addr_district',
   
    'emp_state',
    'emp_pincode',
    'pull_dt',
    'pull_by',
    'status',
    'form_status',
    'upload_status',
    'client_ip',
    
    'transaction_status',
    'emp_addr_lcality_ret',
    'emp_addr_subdiv_ret',
    'emp_addr_district_ret',
    'other_qualification',
    'emp_state_ret',
    'emp_pincode_ret',
    'created_at',
    'updated_at',
    'rejected_status', 
    'remark',
    'remark_details',
    'forwarded_by',
    'forwarded_on',
    'received_by',
    'sent_by',
    'expire_on_duty',
   // 'accept_transfer',
   'ministry_id',
   'sex',
   'dept_id',
    'second_appl_name',
    'physically_handicapped',
    'uploaded_id',
    'uploader_role_id',
    'file_status',
    'change_status',
    'family_details_status',

    'second_post_id',
    'second_grade_id',
    'dept_id_option',
    'third_post_id',
    'third_grade_id',

    'dept_alloted_id',
    'post_alloted_id',
    'efile_dp',
    'dp_file_link',
    'efile_ad',
    'ad_file_link',
    'role_id',
    'transfer_status',
    'transfer_dept_id',
    'transfer_post_id',
    'transfer_remark',
    'transfer_remark_details',
    'pdf_file'

	];

    
}
