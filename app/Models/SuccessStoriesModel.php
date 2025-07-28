<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuccessStoriesModel extends Model
{
    use HasFactory;
    public $table = "success_story";
    protected $fillable = [
        'id',
        'name',
        'description',
        'image',
        'status'
    ];
}
