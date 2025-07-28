<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdproofModel extends Model
{
    use HasFactory;
    protected $table = "idproofs";
    protected $primaryKey = 'idproof_id';
    protected $guarded = ['idproof_id'];

    public function scopeGetIdproofName($query, $idproof_id){
        $data = $query->where('idproof_name',$idproof_id)->first();
        if(!is_null($data)){
            return $data->state_name;
        }
        return null;
    }

    public function scopeGetOption($query){
        $qry = $query->selectRaw("idproof_id as id, idproof_name as name");
        return $qry;
    }
}
