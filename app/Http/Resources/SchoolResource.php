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
            'province_id' => $this->province_id,
            'district_id' => $this->district_id,
            'sub_district_id' => $this->sub_district_id,
            'operational_license' => $this->operational_license,
            'telp_no' => $this->telp_no,
            'exam_info' => $this->exam_info,
            'created_at' => $this->created_at,
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
