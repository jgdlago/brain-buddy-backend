<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserInstitutionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'institutions' => 'required|array',
            'institutions.*.id' => 'required|integer|exists:App\Models\Institution,id',
        ];
    }
}
