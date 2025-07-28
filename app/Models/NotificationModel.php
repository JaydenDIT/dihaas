<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationModel extends Model
{
    use HasFactory;
    protected $table = "notifications";
    protected $primaryKey = 'notification_id';
    protected $guarded = ['notification_id'];

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
