<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->load('group', 'helpFlags');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'age' => $this->age,
            'gender' => $this->gender->label(),
            'character' => $this->character->label(),
            'grouop' => new GroupResource($this->group),
            'performance_flag' => $this->performance_flag,
            'help_flags' => $this->helpFlags->sortBy('trigger_date')
        ];
    }
}
