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

    public function uploadedDocuments()
    {
        return $this->hasMany(UploadedDocument::class, 'proforma_id', 'proforma_id');
    }

    public function familyDetails()
    {
        return $this->hasMany(FamilyDetail::class, 'proforma_id', 'proforma_id');
    }
    public function proformaLogs()
    {
        return $this->hasMany(ProformaLog::class, 'proforma_id', 'proforma_id');
    }

    public function uoFileSubmission()
    {
        return $this->hasOne(UoFileSubmission::class, 'proforma_id', 'proforma_id');
    }

    public function uoGeneration()
    {
        return $this->hasOne(UoGeneration::class, 'proforma_id', 'proforma_id');
    }

    //Method to get the seniority list
    public function scopeGetOverallSeniorityList($query)
    {
        $list = $query->where('proforma_status', '!=', 'completed')
            ->whereNull('mini_sequence')
            ->orderByRaw("expire_on_duty = 0, deceased_doe, created_at, applicant_dob")->get();
        //get Only the proforma id and index
        $data = [];
        foreach ($list as $key => $item) {
            $data[$item->proforma_id] = $key + 1;
        }
        return $data;
    }

    //Get the overall seniority index of a proforma
    public function scopeGetOverallSeniorityIndex($query, $proforma_id)
    {
        $list = $query->where('proforma_status', '!=', 'completed')
            ->whereNull('mini_sequence')
            ->orderByRaw("expire_on_duty = 0, deceased_doe, created_at, applicant_dob")->get();
        foreach ($list as $key => $item) {
            if ($item->proforma_id == $proforma_id) {
                //returning the index
                return ($key + 1);
            }
        }
        return 0;
    }

    //Get the departmental seniority index
    public function scopeGetDepartmentalSeniorityIndex($query, $proforma_id)
    {
        $proforma = Proforma::find($proforma_id);
        $list = $query->where('deceased_field_dept_cd', $proforma->deceased_field_dept_cd)
            ->where('proforma_status', '!=', 'completed')
            ->whereNull('mini_sequence')
            ->orderByRaw("expire_on_duty = 0, deceased_doe, created_at, applicant_dob")
            ->get();
        foreach ($list as $key => $item) {
            if ($item->proforma_id == $proforma_id) {
                //returning the index
                return ($key + 1);
            }
        }
        return 0;
    }
}
