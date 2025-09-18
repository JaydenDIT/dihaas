<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostVaccancyRequest extends FormRequest
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
    public function rules()
    {
        return [
            'vaccancy_id'           => ['required', 'integer'],
            'adm_dept_cd'           => ['required'],
            'adm_dept_name'         => ['nullable', 'string'],
            'field_dept_cd'         => ['required'],
            'field_dept_name'       => ['nullable', 'string'],
            'dsg_srno'              => ['required'],
            'dsg_name'              => ['nullable', 'string'],
            'total_vaccant_post'    => ['required', 'integer'],
            'no_of_posts_dia'       => ['required', 'integer'],
            'no_of_posts_dr'        => ['required', 'integer'],
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'adm_dept_cd.required' => 'Missing code for administrative department.',
            'field_dept_cd.required' => 'Missing code for field department.',
            'dsg_srno.required' => 'Please select a post.',
            'total_vaccant_post.required' => 'Please enter total number of posts.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * This replaces field names with more user-friendly labels
     * in error messages displayed to the user.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'adm_dept_cd' => 'Administrative Department',
            'field_dept_cd' => 'Field Department',
            'dsg_srno' => 'Post',
            'total_vaccant_post' => 'Total vaccant posts',
            'no_of_posts_dia' => 'No. of posts for Die-in-Harness',
            'no_of_posts_dr' => 'No. of posts for direct recruitment',
        ];
    }

    /**
     * Add custom validation after the basic rules.
     
     * Configure the validator instance with custom logic.
     *
     * After the default validation rules are applied,
     * this ensures that:
     * total_vaccant_post = no_of_posts_dia + no_of_posts_dr
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $total = (int) $this->input('total_vaccant_post');
            $dia   = (int) $this->input('no_of_posts_dia'); // Die-in-Harness
            $dr    = (int) $this->input('no_of_posts_dr');  // Direct Recruitment

            if ($total !== ($dia + $dr)) {
                $validator->errors()->add(
                    'total_vaccant_post',
                    'The total vacant posts must equal the sum of Die-in-Harness and Direct Recruitment posts.'
                );
            }
        });
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'adm_dept_cd' => splitCode($this->input('adm_dept_cd'))['code'] ?? null,
            'adm_dept_name' => is_null($this->input('adm_dept_name', null)) ? splitCode($this->input('adm_dept_cd'))['name'] ?? null : $this->input('adm_dept_name'),

            'field_dept_cd' => splitCode($this->input('field_dept_cd'))['code'] ?? null,
            'field_dept_name' => is_null($this->input('field_dept_name', null)) ? splitCode($this->input('field_dept_cd'))['name'] ?? null : $this->input('field_dept_name'),

            'dsg_srno' => splitCode($this->input('dsg_srno'))['code'] ?? null,
            'dsg_name' => is_null($this->input('dsg_name', null)) ? splitCode($this->input('dsg_srno'))['name'] ?? null : $this->input('dsg_name'),
        ]);
    }
}
