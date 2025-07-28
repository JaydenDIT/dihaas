<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubDivision extends Model
{
   
    use HasFactory;
    protected $table = "sub_divisions";
    protected $primaryKey = 'sub_division_id';
    protected $guarded = ['sub_division_id'];

    public function scopeGetSubDivisionName1($query, $sub_district_cd_lgd){
        $data = $query->where('sub_district_cd_lgd',$sub_district_cd_lgd)->first();
        if(!is_null($data)){
            return $data->sub_division_name;
        }
        return "Sub-Division Name Not Defined!";
    }
    
    public function scopeGetOptionByDistrict1($query, $district_code_census){
        $qry = $query->selectRaw("sub_district_cd_lgd as id, sub_division_name as name")
                        ->where('district_code_census', $district_code_census);
        return $qry;
    }
}
