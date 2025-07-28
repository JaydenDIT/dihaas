<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistrictModel extends Model
{
   
    use HasFactory;
    protected $table = "districts";
    protected $primaryKey = 'district_id';
    protected $guarded = ['district_id'];

    public function scopeGetDistrictName($query, $district_id){
        $data = $query->where('district_id',$district_id)->first();
        if(!is_null($data)){
            return $data->district_name_english;
        }
        return "District Name Not Defined!";
    }
    
    public function scopeGetOptionByState($query, $state_code_census){
        $qry = $query->selectRaw("district_id as id, district_name_english as name")
                        ->where('state_code_census', $state_code_census);
        return $qry;
    }
}
