<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryModel extends Model
{
    use HasFactory;
    protected $table = "countries";
    protected $primaryKey = 'country_id';
    protected $guarded = ['country_id'];

    public function scopeGetCountryName($query, $country_id){
        $data = $query->where('country_id',$country_id)->first();
        if(!is_null($data)){
            return $data->country_name;
        }
        return null;
    }

    public function scopeGetOption($query){
        $qry = $query->selectRaw("country_id as id, country_name as name");
        return $qry;
    }
}
