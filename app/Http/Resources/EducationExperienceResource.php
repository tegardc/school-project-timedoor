<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EducationExperienceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'role' => $this->role,
            'educationLevelId' => $this->educationLevelId,
            'schoolDetailId' => $this->schoolDetailId,
            'educationProgramId' => $this->educationProgramId,
            'degree' => $this->degree,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate

        ];
    }
}
