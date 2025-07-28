<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectionLogModel extends Model
{
    use HasFactory;
    public $table = "rejection_log";
    protected $fillable = [
        'ein',
        'rejected_by',
        'rejection_details',
        'ip_address',
    ];
}
