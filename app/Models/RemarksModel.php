<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemarksModel extends Model
{
    use HasFactory;
    
    public $table = "remarks_tbl";
    protected $fillable = [
        'id',
        'probable_remarks',
        'description',
        'created_at',
        'updated_at'
    ];
}
