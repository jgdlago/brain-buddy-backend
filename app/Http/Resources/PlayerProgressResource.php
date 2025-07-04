<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerProgressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total_correct' => $this->total_correct,
            'total_wrong' => $this->total_wrong,
            'total_attempts' => $this->total_attempts,
            'completed' => $this->completed,
            'completion_date' => $this->completion_date
        ];
    }
}
