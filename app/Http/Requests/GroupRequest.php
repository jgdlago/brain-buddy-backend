<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
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
            'responsible_user_id' => 'required|integer|exists:App\Models\User,id',
            'institution_id' => 'required|integer|exists:App\Models\Institution,id',
        ];
    }
}
