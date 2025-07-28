<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeLineModel extends Model
{
    use HasFactory;
    protected $table = 'applicationsubmission_tbl';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'timeline_months', 
        'created_at',
        'updated_at'      
    ];
}
