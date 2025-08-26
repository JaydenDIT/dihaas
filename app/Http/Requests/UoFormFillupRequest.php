<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UoFormFillupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'post_option' => 'required|in:applicant-prefered,department-prefered',

            // If applicant-prefered
            'applicant_prefered_post_id'            => 'required_if:post_option,applicant-prefered|nullable|string',
            'applicant_prefered_post_desc'       => 'required_if:post_option,applicant-prefered|nullable|string',
            'applicant_prefered_group_code'      => 'required_if:post_option,applicant-prefered|nullable|string',
            'applicant_prefered_dept_cd'   => 'required_if:post_option,applicant-prefered|nullable|string',
            'applicant_prefered_dept_desc'      => 'required_if:post_option,applicant-prefered|nullable|string',
            'applicant_prefered_adm_dept_cd'   => 'required_if:post_option,applicant-prefered|nullable|string',
            'applicant_prefered_adm_dept_desc' => 'required_if:post_option,applicant-prefered|nullable|string',


            // If department-prefered

            'department_prefered_post_id'        => 'required_if:post_option,department-prefered|nullable|string',
            'department_prefered_post_desc'     => 'required_if:post_option,department-prefered|nullable|string',
            'department_prefered_group_code'     => 'required_if:post_option,department-prefered|nullable|string',
            'department_prefered_dept_cd'        => 'required_if:post_option,department-prefered|nullable|string',
            'department_prefered_dept_desc'  => 'required_if:post_option,department-prefered|nullable|string',
            'department_prefered_adm_dept_cd'    => 'required_if:post_option,department-prefered|nullable|string',
            'department_prefered_adm_dept_desc' => 'required_if:post_option,department-prefered|nullable|string',

            // Always required
            'signing_authority'               => 'required|exists:users,user_id',
            'remarks'                         => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'post_option.required' => 'Please select a post option.',
            'applicant_prefered_post.required_if' => 'Please select a preferred post.',
            'applicant_prefered_group_code.required_if' => 'Group code is required for applicant preferred option.',
            'applicant_prefered_dept_cd.required_if' => 'Department code is required for applicant preferred option.',
            'department_prefered_dept_cd.required_if' => 'Please select a department for department preferred option.',
            'department_prefered_post_id.required_if' => 'Please select a post for department preferred option.',
            'signing_authority.required' => 'Please select a signing authority.',
        ];
    }
}
