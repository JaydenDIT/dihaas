<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreeningModel extends Model
{
    use HasFactory;
    protected $table = "screening_report";
    protected $primaryKey = 'screening_id';
    protected $guarded = ['screening_id'];

    public function scopeGetData($query){
        $qry =  $query->whereNull("is_deleted");
        return $qry;
    }

    public function scopeGetNotification($query){
        $qry =  $query->whereNull("is_deleted")
                        ->where("validity",">",date("Y-m-d"));
        return $qry;
    }
}
