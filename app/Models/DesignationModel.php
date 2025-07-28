<?php

namespace App\Models;

use Illuminate\Database\DBAL\TimestampType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignationModel extends Model
{
    use HasFactory;
    protected $table='designations';
    public $timestamps = true;
   
    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
    protected $fillable=[
        'id',
        'desig_name',
        'grade_id',
        'field_dept_cd',
        'dsg_srno',
        'updated_at',
        'created_at'

    ];
}
