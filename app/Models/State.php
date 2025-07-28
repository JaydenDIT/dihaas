<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    protected $table = "states";
    protected $primaryKey = 'state_id';
    protected $guarded = ['state_id'];


    public function scopeGetStateName1($query, $state_code_census){
        $data = $query->where('state_code_census',$state_code_census)->first();
        if(!is_null($data)){
            return $data->state_name;
        }
        return null;
    }

    public function scopeGetOption($query){
        $qry = $query->selectRaw("state_code_census as id, state_name as name");
        return $qry;
    }
}
