<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
  
class CasteModel extends Model
{
    use HasFactory;
    protected $table = 'caste';
    protected $fillable = [
        'caste_id',
        'caste_name'
        
    ];
}
