<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadImageModel extends Model
{
    use HasFactory;
    public $table = "upload_images";
    protected $fillable = [
        'id',
        'status',
        'image',
       
    ];
}