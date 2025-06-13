<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolDetailResource extends JsonResource
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
            'name' => $this->name,
            'institutionCode' => $this->institutionCode,
            'schoolId' => $this->schoolId,
            'statusId' => $this->statusId,
            'educationLevelId' => $this->educationLevelId,
            'ownershipStatus' => $this->ownershipStatus,
            'dateEstablishmentDecree' => $this->dateEstablishmentDecree,
            'operationalLicense' => $this->operationalLicense,
            'dateOperationalLicense' => $this->dateOperationalLicense,
            'principal' => $this->principal,
            'operator' => $this->operator,
            'accreditationId' => $this->accreditationId,
            'curriculum' => $this->curriculum,
            'telpNo' => $this->telpNo,
            'tuitionFee' => $this->tuitionFee,
            'numStudent' => $this->numStudent,
            'numTeacher' => $this->numTeacher,
            'movie' => $this->movie,
            'examInfo' => $this->examInfo,
            'rating' => $this->reviews ? round($this->reviews->avg('rating'), 1) : 0,
            'reviewers' => $this->reviews ? $this->reviews->count() : 0,
            'galleryImages' => $this->schoolGallery->pluck('imageUrl') ?? [],
            // 'createdAt' => $this->createdAt,
            // 'updatedAt' => $this->updatedAt,

        ];
    }
}
