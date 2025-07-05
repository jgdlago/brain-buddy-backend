<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HelpFlagRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'player_id' => 'required|integer|exists:App\Models\Player,id',
            'trigger_date' => 'required|date',
            'level_id' => 'required|integer|exists:App\Models\Level,id',
        ];
    }
}
