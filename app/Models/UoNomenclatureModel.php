<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UoNomenclatureModel extends Model
{
    use HasFactory;
    protected $table = 'uo_nomenclature';
	public $timestamps = true;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
        'uo_format',
		'uo_file_no',
		'year',
		'suffix',
		'updated_at',
        'created_at'

	];
}
