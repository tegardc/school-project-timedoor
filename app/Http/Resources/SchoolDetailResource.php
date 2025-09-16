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
            'schoolName' => $this->schools->name ?? null,
            'statusName' => $this->status->name ?? null,
            'educationLevelName' => $this->educationLevel->name ?? null,
            'provinceName'    => $this->address->province->name ?? null,
            'districtName'    => $this->address->district->name ?? null,
            'subDistrictName' => $this->address->subDistrict->name ?? null,
            'village'         => $this->address->village ?? null,
            'street'          => $this->address->street ?? null,
            'postalCode'      => $this->address->postalCode ?? null,
            'latitude'        => $this->address->latitude ?? null,
            'longitude'       => $this->address->longitude ?? null,
            'ownershipStatus' => $this->ownershipStatus,
            'dateEstablishmentDecree' => $this->dateEstablishmentDecree,
            'operationalLicense' => $this->operationalLicense,
            'dateOperationalLicense' => $this->dateOperationalLicense,
            'principal' => $this->principal,
            'operator' => $this->operator,
            'accreditationId' => $this->accreditationId,
            'accreditationCode' => $this->accreditation->code ?? null,
            'curriculum' => $this->curriculum,
            // 'contacts' => ContactResource::collection($this->whenLoaded('contacts')),

            // 'telpNo' => $this->telpNo,
            'tuitionFee' => $this->tuitionFee,
            'numStudent' => $this->numStudent,
            'numTeacher' => $this->numTeacher,
            'movie' => $this->movie,
            'examInfo' => $this->examInfo,
            'rating' => $this->reviews ? round($this->reviews->avg('rating'), 1) : 0,
            'reviewers' => $this->reviews ? $this->reviews->count() : 0,
            'contacts'=> $this->contacts->pluck('value','type') ?? [],
            'galleryImages' => $this->schoolGallery->pluck('imageUrl') ?? [],
            // 'facilities' => FacilityResource::collection($this->whenLoaded('facilities')),
            'facilities' => $this->facilities->pluck('name') ?? [],
            // 'createdAt' => $this->createdAt,
            // 'updatedAt' => $this->updatedAt,

        ];
    }
}
