<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubDivision extends Model
{
    use HasFactory;
    protected $table = 'subdivisions';
    protected $primaryKey = 'subdivision_id';
    protected $guarded = ['subdivision_id'];

    public function district()
    {
        return $this->hasOne(District::class, 'district_id', 'district_id');
    }
}
