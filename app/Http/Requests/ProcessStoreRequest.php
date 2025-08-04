<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProcessStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        if ($this->route('id')) {
            $this->merge([
                'process_id' => $this->route('id'),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'action' => 'required|string|in:create,update',
            'process_id' => [
                Rule::requiredIf($this->input('action') === 'update'),
                'nullable',
                'integer',
                'exists:process,process_id',
            ],
            'process_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('process', 'process_name')->ignore($this->process_id, 'process_id'),
            ],
            'process_description' => 'required|string',
            'process_criteria' => 'required|array|min:1',
            'process_criteria.*.field' => 'required|string',
            'process_criteria.*.operation' => 'required|string',
            'process_criteria.*.value' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Please specify the action (create or update).',
            'action.in' => 'Invalid action provided.',
            'process_id.exists' => 'The selected process does not exist.',
            'process_name.required' => 'Process name is required.',
            'process_name.unique' => 'This process name is already taken.',
            'process_name.max' => 'Process name must not exceed 255 characters.',
            'process_criteria.required' => 'At least one condition must be added.',
            'process_criteria.array' => 'Process criteria must be an array.',
            'process_criteria.*.field.required' => 'Field is required for every condition.',
            'process_criteria.*.operation.required' => 'Operation is required for every condition.',
            'process_criteria.*.value.required' => 'Value is required for every condition.',
        ];
    }

    public function attributes(): array
    {
        return [
            'process_name' => 'process name',
            'process_description' => 'process description',
            'process_criteria' => 'process criteria',
            'process_criteria.*.field' => 'condition field',
            'process_criteria.*.operation' => 'condition operation',
            'process_criteria.*.value' => 'condition value',
        ];
    }
}
