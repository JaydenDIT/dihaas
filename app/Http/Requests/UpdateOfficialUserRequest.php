<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOfficialUserRequest extends FormRequest
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
        //user detail            
        return [
            'user_id' => 'required',
            'fullname' => 'required',
            'mobile' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user_id, 'user_id')], // Ignore current user],
            'role_id' => 'required',
            //'ministry_id' => 'nullable',
            'dsg_serial_no' => 'required',
            //dsg_serial_no is the post
            'field_dept_cd' => 'required',
            //field_dept_cd is the department under which the user should work/operate
        ];
    }
}
