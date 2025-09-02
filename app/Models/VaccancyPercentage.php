<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccancyPercentage extends Model
{
    use HasFactory;

    protected $table = "vaccancy_percentages";

    protected $fillable = [
        'dia_percentage',
        'old_dia_percentage',
        'year',
        'old_year',
    ];
}
