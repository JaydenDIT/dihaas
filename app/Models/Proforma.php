<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proforma extends Model
{
    use HasFactory;
    protected $table = 'proforma';
    protected $primaryKey = 'proforma_id';

    protected $guarded = ['proforma_id'];
}
