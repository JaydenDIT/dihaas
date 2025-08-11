<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProformaLog extends Model
{
    use HasFactory;
    protected $table = 'proforma_log';
    protected $primaryKey = 'proforma_log_id';

    protected $guarded = ['proforma_log_id'];
}
