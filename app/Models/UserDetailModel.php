<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetailModel extends Model
{
    use HasFactory;
    protected $table = "user_details";
    protected $primaryKey = 'user_detail_id';
    protected $guarded = ['user_detail_id'];

    
    public function scopeGetUserDetail($query, $user_id){
        $qry = $query->select([
                    'user_details.*',
                    'A.state_name as current_state_name',
                    'B.state_name as permanent_state_name',
                    
                    // 'C.country_name as current_country_name',
                    // 'D.country_name as permanent_country_name',

                    'relationships_register.relationship_name',
                    'idproofs.idproof_name'
                ])

                
                ->leftJoin("states as A","A.state_id","=","user_details.current_state_id")
                ->leftJoin("states as B","B.state_id","=","user_details.permanent_state_id")

                // ->leftJoin("countries as C","C.country_id","=","user_details.current_country_id")
                // ->leftJoin("countries as D","D.country_id","=","user_details.permanent_country_id")

                ->leftJoin("relationships_register","relationships_register.relationship_id","=","user_details.relationship_id")
                ->leftJoin("idproofs","idproofs.idproof_id","=","user_details.idproof_id")
                ->where('user_details.user_id',$user_id);
        return $qry;
    }
}
