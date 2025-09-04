<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProformaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Add permission checks if needed
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if ($this->input('same_as_current')) {
            $this->merge([
                'applicant_permanent_locality' => $this->input('applicant_current_locality'),
                'applicant_permanent_state_id' => $this->input('applicant_current_state_id'),
                'applicant_permanent_district_id' => $this->input('applicant_current_district_id'),
                'applicant_permanent_subdivision_id' => $this->input('applicant_current_subdivision_id'),
                'applicant_permanent_pincode' => $this->input('applicant_current_pincode'),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            // Deceased details
            'deceased_ein' => 'required|string|max:10',
            'deceased_emp_name' => 'required|string|max:255',
            'deceased_field_dept_cd' => 'required|string|max:20',
            'deceased_field_dept_desc' => 'required|string|max:255',
            'deceased_adm_dept_cd' => 'nullable|integer',
            'deceased_adm_dept_desc' => 'required|string|max:255',
            'deceased_emp_desig' => 'required|string|max:255',
            'deceased_emp_group' => 'required|string|max:255',
            'deceased_doa' => 'required|date',
            'deceased_dob' => 'required|date',
            'expire_on_duty' => 'required|boolean',
            'deceased_doe' => 'required|date',
            'deceased_causeofdeath' => 'nullable|string|max:300',

            // Request posts
            'request_dsg_srno_1' => 'required|string|max:20',
            'request_dsg_desc_1' => 'required|string|max:255',
            'request_group_code_1' => 'required|string|max:20',

            'request_dsg_srno_2' => 'required|string|max:20',
            'request_dsg_desc_2' => 'required|string|max:255',
            'request_group_code_2' => 'required|string|max:20',

            'request_adm_dept_cd_3' => 'required|string|max:20',
            'request_adm_dept_desc_3' => 'required|string|max:255',
            'request_field_dept_cd_3' => 'required|string|max:20',
            'request_field_dept_desc_3' => 'required|string|max:255',
            'request_dsg_srno_3' => 'required|string|max:20',
            'request_dsg_desc_3' => 'required|string|max:255',
            'request_group_code_3' => 'required|string|max:20',

            // Applicant details
            'applicant_name' => 'required|string|max:255',
            'relationship_id' => 'required|integer|exists:relationships,relationship_id',
            'applicant_dob' => 'required|date',
            'applicant_mobile' => 'required|string|size:10',
            'applicant_email' => 'required|email|max:255',
            'applicant_sex' => 'required|in:male,female,transgender',
            'proforma_submission_date' => 'nullable|date',

            // Other details
            'caste_id' => 'required|integer|exists:castes,caste_id',
            'physically_handicapped' => 'required|boolean',
            'applicant_qualification_id' => 'required|integer|exists:qualifications,qualification_id',
            'applicant_qualification_name' => 'nullable|string|max:255',

            // Current address
            'applicant_current_locality' => 'required|string|max:255',
            'applicant_current_state_id' => 'required|integer|exists:states,state_id',
            'applicant_current_district_id' => 'required|integer|exists:districts,district_id',
            'applicant_current_subdivision_id' => 'required|integer|exists:subdivisions,subdivision_id',
            'applicant_current_pincode' => 'required|digits:6',

            // Permanent address
            'applicant_permanent_locality' => 'required|string|max:255',
            'applicant_permanent_state_id' => 'required|integer|exists:states,state_id',
            'applicant_permanent_district_id' => 'required|integer|exists:districts,district_id',
            'applicant_permanent_subdivision_id' => 'required|integer|exists:subdivisions,subdivision_id',
            'applicant_permanent_pincode' => 'required|digits:6',
        ];
    }
}
