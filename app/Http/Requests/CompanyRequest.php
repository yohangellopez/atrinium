<?php

namespace App\Http\Requests;

use App\Enums\DocumentType;
use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Enum;

class CompanyRequest extends FormRequest
{
   
    public function authorize(): bool
    {
        return true;
    }

   
    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'document_type'     => ['required', new Enum(DocumentType::class)],
            'document_number'   => 'required|string|max:50',
            'contact_email'     => 'required|string|email|max:255',
            'status'            => ['required', new Enum(Status::class)],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }
}
