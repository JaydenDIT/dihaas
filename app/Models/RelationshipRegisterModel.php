<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationshipRegisterModel extends Model
{
    use HasFactory;
    protected $table = "relationships_register";
    protected $primaryKey = 'relationship_id';
    protected $guarded = ['relationship_id'];

    public function scopeGetRelationName($query, $relationship_id){
        $data = $query->where('relationship_id',$relationship_id)->first();
        if(!is_null($data)){
            return $data->relationship_name;
        }
        return null;
    }
    
    public function scopeGetOption($query){
        $qry = $query->selectRaw("relationship_id as id, relationship_name as name");
        return $qry;
    }
}
