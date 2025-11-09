<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolTemplateResource extends JsonResource
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
            'accreditationCode' => $this->accreditation->code ?? null,
             'provinceName'    => $this->address->province->name ?? null,
            'districtName'    => $this->address->district->name ?? null,
            'subDistrictName' => $this->address->subDistrict->name ?? null,
            'village'         => $this->address->village ?? null,
            'street'          => $this->address->street ?? null,
            'galleryImages' => $this->schoolGallery->pluck('imageUrl') ?? [],
            'rating' => $this->reviews ? round($this->reviews->avg('rating'), 1) : 0,
            'reviewers' => $this->reviews ? $this->reviews->count() : 0,

        ];
    }
}
