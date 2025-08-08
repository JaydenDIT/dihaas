<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caste extends Model
{
    use HasFactory;
    protected $table = 'castes';
    protected $primaryKey = 'caste_id';

    protected $guarded = ['caste_id'];
}
