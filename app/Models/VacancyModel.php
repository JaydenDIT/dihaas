<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// NOT USE IN THIS PROJECT
// TABLE cmis_vacancy_yearly is updated with dept entry 
class VacancyModel extends Model
{
    use HasFactory;
    protected $table='vacancies';
    public $timestamps = true;
   
    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
    protected $fillable=[
        'id',
        'vacancy_year',
        'post_name',
        'sanctioned_post', 
        'vacancy_of_sanctioned_post',
        'vacancy_direct',
        
        'employees_under_dih',
        'post_vacant_under_dih',
        'dept_id',
        'remarks',
        'entered_id',
        'created_at',
        'updated_at'

    ];
}

