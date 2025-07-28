<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMembers extends Model
{
    use HasFactory;
    protected $fillable = [
        'ein',
        'name',
        'dob',
        'gender',
        'relation',
      //  'remarks',
        'client_ip'
    ];
}
