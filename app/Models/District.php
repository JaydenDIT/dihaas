<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $table = 'districts';
    protected $primaryKey = 'district_id';
    protected $guarded = ['district_id'];

    public function subdivisions()
    {
        return $this->hasMany(SubDivision::class, 'district_id', 'district_id');
    }
}
