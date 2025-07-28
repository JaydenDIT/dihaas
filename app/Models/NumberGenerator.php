<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NumberGenerator extends Model
{
    use HasFactory;
    protected $table = 'number_generator';
	public $timestamps = true;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',    
		'prefix',
        'suffix',
		'updated_at',
        'created_at'

	];
}