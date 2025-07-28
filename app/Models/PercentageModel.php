<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PercentageModel extends Model
{
    use HasFactory;
    protected $table='vpercentage';
    public $timestamps = true;
   
    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
    protected $fillable=[
         'id',
        'vpercentage',
        'old_percentage',
        'year',
        'old_year',
        'updated_at',
        'created_at'

    ];
}
