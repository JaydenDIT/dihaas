<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\DocumentList;

class StoreUploadedDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // You can add role-based logic here if needed
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'proforma_id'       => 'required|integer|exists:proforma,proforma_id',
            'document_list_id'  => 'required|integer|exists:document_list,document_list_id',
            'document_file'     => 'required|file',
        ];

        // If document_list_id is provided, add dynamic rules
        if ($this->document_list_id) {
            $documentList = DocumentList::find($this->document_list_id);
            if ($documentList) {
                // Max size KB → Laravel's max rule is in KB
                $rules['document_file'] .= '|max:' . $documentList->max_size_kb;

                // Mime type rule based on document_type
                $mimeTypes = match ($documentList->document_type) {
                    'pdf'   => ['application/pdf'],
                    'image' => ['image/jpeg', 'image/png', 'image/jpg'],
                    'docx'  => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
                    'excel' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
                    default => [],
                };

                if (!empty($mimeTypes)) {
                    $rules['document_file'] .= '|mimetypes:' . implode(',', $mimeTypes);
                }
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'document_file.max' => 'The uploaded file exceeds the maximum allowed size of :max KB.',
            'document_file.mimetypes' => 'The uploaded file must be of an allowed type.',
        ];
    }
}
