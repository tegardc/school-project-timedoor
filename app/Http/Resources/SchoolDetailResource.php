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
            'schoolName' => $this->schools->name ?? null,
            'statusId' => $this->statusId,
            'statusName' => $this->status->name ?? null,
            'educationLevelId' => $this->educationLevelId,
            'educationLevelName' => $this->educationLevel->name ?? null,
            'provinceName' => $this->schools->province->name ?? null,
            'provinceId' => $this->schools->provinceId,
            'districtName' => $this->schools->district->name ?? null,
            'districtId' => $this->schools->districtId,
            'subDistrictName' => $this->schools->subDistrict->name ?? null,
            'subDistrictId' => $this->schools->subDistrictId,
            'ownershipStatus' => $this->ownershipStatus,
            'dateEstablishmentDecree' => $this->dateEstablishmentDecree,
            'operationalLicense' => $this->operationalLicense,
            'dateOperationalLicense' => $this->dateOperationalLicense,
            'principal' => $this->principal,
            'operator' => $this->operator,
            'accreditationId' => $this->accreditationId,
            'accreditationName' => $this->accreditation->code ?? null,
            'curriculum' => $this->curriculum,
            // 'contacts' => ContactResource::collection($this->whenLoaded('contacts')),
            'contacts'=> $this->contacts->pluck('value','type') ?? [],
            // 'telpNo' => $this->telpNo,
            'tuitionFee' => $this->tuitionFee,
            'numStudent' => $this->numStudent,
            'numTeacher' => $this->numTeacher,
            'movie' => $this->movie,
            'examInfo' => $this->examInfo,
            'rating' => $this->reviews ? round($this->reviews->avg('rating'), 1) : 0,
            'reviewers' => $this->reviews ? $this->reviews->count() : 0,
            'galleryImages' => $this->schoolGallery->pluck('imageUrl') ?? [],
            // 'facilities' => FacilityResource::collection($this->whenLoaded('facilities')),
            'facilities' => $this->facilities->pluck('name') ?? [],
            // 'createdAt' => $this->createdAt,
            // 'updatedAt' => $this->updatedAt,

        ];
    }
}
