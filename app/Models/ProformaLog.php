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

    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by', 'user_id');
    }

    public function proforma()
    {
        return $this->belongsTo(Proforma::class, 'proforma_id', 'proforma_id');
    }
}
