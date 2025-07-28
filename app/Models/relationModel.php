<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class relationModel extends Model
{
    use HasFactory;
    protected $table = 'relation_employee';
	public $timestamps = true;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
        'relationship',
		'updated_at',
        'created_at'

	];
}
