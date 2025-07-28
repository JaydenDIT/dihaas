<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    use HasFactory;
    public $timestamps = true;
    public $table = "roles_tbl";
    protected $fillable = [
        'id',
        'role_id' ,
        'role_name',
    
    ];
    // use HasFactory;
    // protected $table = "roles_tbl";
    // protected $primaryKey = 'role_id';
    // protected $guarded = ['role_id'];



    public function scopeGetRoleName($query, $roleId){
        $role = $query->where('role_id',$roleId)->first();
        if(!is_null($role)){
            return $role->role_name;
        }
        return null;
    }
}
