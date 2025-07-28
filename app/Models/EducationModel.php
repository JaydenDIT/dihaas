<?php

namespace App\Models;

use Illuminate\Database\DBAL\TimestampType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationModel extends Model
{
    use HasFactory;
    protected $table='educations';
    public $timestamps = true;
   
    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
    protected $fillable=[
        'id',
        'edu_name',
        'updated_at',
        'created_at'

    ];
}
