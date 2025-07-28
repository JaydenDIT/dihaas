<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UOGenerationModel extends Model
{
    use HasFactory;
    protected $table = 'uo_generation';
	public $timestamps = true;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
        'ein',
		'deceased_dept_id',
		'uo_number',
		'appl_number',
		'generated_by',
		'generated_on',
		'dp_file_number',
		'ad_file_number',
		'file_put_up_by',
		'signing_authority_1',
		'signing_authority_2',
		'post_id',
		'uo_allotted_date',
		'relationship',
		'updated_at',
        'created_at',

		'efile_dp',
		'dp_file_link',
		'efile_ad',
		'ad_file_link',
		'role_id',
		'post',
		'grade',
		'department'

	];
}
