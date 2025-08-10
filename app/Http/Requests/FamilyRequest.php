<?php
// app/Http/Requests/FamilyRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FamilyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proforma_id'     => 'required|exists:proforma,id',
            'relationship_id' => 'required|exists:relationship,id',
            'fullname'        => 'required|string|max:255',
            'gender'          => 'required|in:male,female,transgender',
        ];
    }
}
