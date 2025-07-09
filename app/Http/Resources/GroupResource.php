<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->load('responsibleUser', 'institution');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'responsible_user' => new SimplyUserResource($this->responsibleUser),
            'institution' => new SimplyInstitutionResource($this->institution),
            'access_code' => $this->access_code,
            'education_level' => $this->education_level?->label()
        ];
    }
}
