<?php

namespace App\Http\Requests;

use App\Enums\CharacterEnum;
use App\Enums\GenderEnum;
use App\Enums\PerformanceFlagEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlayerRequest extends FormRequest
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
            'gender' => ['required', Rule::enum(GenderEnum::class)],
            'age' => 'required|integer',
            'character' => ['required', Rule::enum(CharacterEnum::class)],
            'performance_flag' => ['nullable', Rule::enum(PerformanceFlagEnum::class)],
        ];
    }
}
