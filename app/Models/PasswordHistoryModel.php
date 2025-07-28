<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordHistoryModel extends Model
{
    use HasFactory;
    protected $table = "password_histories";
    protected $primaryKey = 'password_history_id';
    protected $guarded = ['password_history_id'];
}
