<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProformaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check(); // Add permission checks if needed
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
        $rules = [
            // Deceased details
            'deceased_ein' => 'required|string|max:10',
            'deceased_emp_name' => 'required|string|max:255',
            'deceased_field_dept_cd' => 'required|string|max:20',
            'deceased_field_dept_desc' => 'required|string|max:255',
            'deceased_adm_dept_cd' => 'required|integer',
            'deceased_adm_dept_desc' => 'required|string|max:255',
            'deceased_emp_desig' => 'required|string|max:255',
            'deceased_emp_group' => 'required|string|max:255',
            'deceased_doa' => 'required|date',
            'deceased_dob' => 'required|date',
            'expire_on_duty' => 'required|boolean',
            'deceased_doe' => 'required|date', // Date of expiry of the deceased person
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

        if (Auth::user()->role->role_group != 'citizen') {
            /**
             * 'proforma_submission_date' is required when data entry is done by non-citizen users, which means only 
             * for back-locked data entry.            
             */
            $rules['proforma_submission_date'] = 'required|date';
        }
        return $rules;
    }

    /**
     * Add custom validation after the basic rules.
     
     * Configure the validator instance with custom logic.
     *
     * After the default validation rules are applied,
     * this ensures that:
     * proforma_submission_date must be within the 6 months after the expiry of the deceased person.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $proforma_submission_date = $this->input('proforma_submission_date', date('Y-m-d'));
            $doe = $this->input('deceased_doe');

            /**
             * The logic is simple:
             * 
             * If there is 'proforma_submission_date' in the request, then we consider that, otherwise
             * we consider the current date on which request is submitted.
             * 
             * Point to be noted is that: 'proforma_submission_date' will be present only in case of back locked data entry, otherwise
             * the current date (on which request is received) will be considered as 'proforma_submission_date'
             * 
             * Purpose:
             * Our requirement is that we will allow receiving the proprorma application form submission only within the configured months 
             * (generally 6 months) after the expiry of the deceased person, if exceeds, form submission will not be allowed. 
             * 
             * Check if submission date is more than 6 months after death
             * 
             * */

            if ($doe) {
                $dateOfDeath = Carbon::parse($doe);
                $dateOfSubmission = Carbon::parse($proforma_submission_date);

                // Check if submission date is more than the configured months after death
                $months = config('proforma.proforma_validity', 6); //By default 6
                if ($dateOfSubmission->gt($dateOfDeath->copy()->addMonth($months))) {
                    $validator->errors()->add(
                        'proforma_submission_date',
                        'Your application must be submitted within ' . $months . ' months after the date of death.'
                    );
                }
            }
        });
    }
}
