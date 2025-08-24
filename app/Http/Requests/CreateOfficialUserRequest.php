<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateOfficialUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::check() && Auth::user()->role->role_group == "superadmin") {
            return true;
        }
        return false;
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
            'fullname' => 'required',
            'mobile' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed'],
            'password_confirmation' => 'required',
            'role_id' => 'required',
            //'ministry_id' => 'nullable',
            'dsg_serial_no' => 'required',
            //dsg_serial_no is the post
            'field_dept_cd' => 'required',
            //field_dept_cd is the department under which the user should work/operate
        ];
    }
}
