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

    // Relationships
    public function relationship()
    {
        return $this->belongsTo(Relationship::class, 'relationship_id', 'relationship_id');
    }

    public function caste()
    {
        return $this->belongsTo(Caste::class, 'caste_id', 'caste_id');
    }

    public function qualification()
    {
        return $this->belongsTo(Qualification::class, 'applicant_qualification_id', 'qualification_id');
    }

    public function currentState()
    {
        return $this->belongsTo(State::class, 'applicant_current_state_id', 'state_id');
    }

    public function permanentState()
    {
        return $this->belongsTo(State::class, 'applicant_permanent_state_id', 'state_id');
    }

    public function currentDistrict()
    {
        return $this->belongsTo(District::class, 'applicant_current_district_id', 'district_id');
    }

    public function permanentDistrict()
    {
        return $this->belongsTo(District::class, 'applicant_permanent_district_id', 'district_id');
    }

    public function currentSubdivision()
    {
        return $this->belongsTo(Subdivision::class, 'applicant_current_subdivision_id', 'subdivision_id');
    }
    public function permanentSubdivision()
    {
        return $this->belongsTo(Subdivision::class, 'applicant_permanent_subdivision_id', 'subdivision_id');
    }
}
