<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CitizenPreRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // adjust if you have auth rules
    }

    public function rules(): array
    {
        if ($this->input('same_as_current')) {
            $this->merge([
                'permanent_address1' => $this->input('current_address1'),
                'permanent_address2' => $this->input('current_address2'),
                'permanent_address3' => $this->input('current_address3'),
                'permanent_pin'      => $this->input('current_pin'),
                'permanent_state_id' => $this->input('current_state_id'),
                'permanent_district_id' => $this->input('current_district_id'),
            ]);
        }

        return [
            'name'                  => ['required', 'string', 'max:75'],
            'mobile'                => ['required', 'integer', 'min:6000000000', 'max:9999999999'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'gender'                => ['required', 'string', 'max:20'],
            'relative_name'         => ['required', 'string', 'max:75'],
            'relationship_id'       => ['required', 'integer', 'exists:relationships,relationship_id'],
            'current_address1'      => ['required', 'string', 'max:75'],
            'current_address2'      => ['nullable', 'string', 'max:75'],
            'current_address3'      => ['nullable', 'string', 'max:75'],
            'current_pin'           => ['required', 'integer', 'min:100000', 'max:999999'],
            'current_state_id'      => ['required', 'integer', 'exists:states,state_id'],
            'current_district_id'   => ['required', 'integer', 'exists:districts,district_id'],
            'permanent_address1'    => ['required', 'string', 'max:75'],
            'permanent_address2'    => ['nullable', 'string', 'max:75'],
            'permanent_address3'    => ['nullable', 'string', 'max:75'],
            'permanent_pin'         => ['required', 'integer', 'min:100000', 'max:999999'],
            'permanent_state_id'    => ['required', 'integer', 'exists:states,state_id'],
            'permanent_district_id' => ['required', 'integer', 'exists:districts,district_id'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'                  => "Applicant Name",
            'mobile'                => "Mobile Number",
            'email'                 => "Valid Email Id",
            'gender'                => "Gender",
            'relative_name'         => "Relative Name",
            'relationship_id'       => "Relationship with Applicant",
            'current_address1'      => "Address Line 1",
            'current_address2'      => "Address Line 2",
            'current_address3'      => "Address Line 3",
            'current_pin'           => "Pin Code",
            'current_state_id'      => "State",
            'current_district_id'   => "District",
            'permanent_address1'    => "Address Line 1",
            'permanent_address2'    => "Address Line 2",
            'permanent_address3'    => "Address Line 3",
            'permanent_pin'         => "Pin Code",
            'permanent_state_id'    => "State",
            'permanent_district_id' => "District",
            'password'              => "Password",
            'password_confirmation' => "Confirmation Password",
        ];
    }
}
