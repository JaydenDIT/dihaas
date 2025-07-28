<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentSessionModel extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $table = "current_session";
    protected $fillable = [
        'id',
        'user_id',
        'username',
        'email',
        'status',
        
        
    ];
}