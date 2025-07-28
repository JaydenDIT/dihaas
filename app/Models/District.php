<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $table = "districts";
    protected $primaryKey = 'district_id';
    protected $guarded = ['district_id'];

    public function scopeGetDistrictName1($query, $district_code_census){
        $data = $query->where('district_code_census',$district_code_census)->first();
        if(!is_null($data)){
            return $data->district_name_english;
        }
        return "District Name Not Defined!";
    }
    
    public function scopeGetOptionByState1($query, $state_code_census){
        $qry = $query->selectRaw("district_code_census as id, district_name_english as name")
                        ->where('state_code_census', $state_code_census);
        return $qry;
    }
}