<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EligibilityModel extends Model
{
    use HasFactory;
    protected $table='eligibility';
    public $timestamps = true;
   
    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
    protected $fillable=[
        'eligible_age',
        'updated_at',
        'created_at'

    ];
}
