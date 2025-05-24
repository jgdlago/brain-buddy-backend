<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstitutionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'cnpj' => 'required|string',
            'activity_area_id' => 'required|integer|exists:App\Models\ActivityArea,id',
            'owner_user_id' => 'required|integer|exists:App\Models\User,id',
        ];
    }
}
