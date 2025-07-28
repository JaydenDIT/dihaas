<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLogModel extends Model
{
    use HasFactory;
    protected $table = "user_logs";
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function scopeGetAttemptNumber($query, $user_id){
        $data = $query->where('user_id',$user_id)->orderBy("last_attempt_date","desc")->first();
        if(is_null($data)){
            return 0;
        }
       $diff = abs(strtotime($data->last_attempt_date) - strtotime( date('Y-m-d H:i:s') ));

       //return date('Y-m-d H:i:s') ;
       //$hourdiff = round( abs(strtotime($data->last_attempt_date) - strtotime(date('Y-m-d H:i:s') ) )/3600, 1);

        if($diff > 60*60){
            return 0;
        }
        return $data->attempt_number;
    }
    public function scopeGetAllLog($query, $roles=[]){
        $qry =  $query->select([
                    'users.name as current_fullname',
                    'users.mobile',
                    'users.email',
                    'users.active_status',            
                    'user_logs.*',
                ])
                ->leftJoin("users","users.id","=","user_logs.user_id");
        return $qry;
    }
}