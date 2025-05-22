<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolResource extends JsonResource
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
            'provinceId' => $this->provinceId,
            'districtId' => $this->districtId,
            'subDistrictId' => $this->subDistrictId,
            'schoolEnstablishmentDecree' => $this->schoolEnstablishmentDecree,
            'telpNo' => $this->telpNo,
            'createdAt' => $this->createdAt,
        ];
    }
    // // public function toResponse($request)
    // // {
    // //     return [
    // //         'success' => true,
    // //         'message' => 'Add Data Success',
    // //         'data' => $this->toArray($request)
    // //     ];
    // }
}
