<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id'           => $this->id ?? '-',
            // 'reviewText'   => $this->reviewText,
            'rating'       => $this->rating,
            'userId'       => $this->userId,

            // user section
            'fullname'     => $this->users?->fullname,
            'image'        => $this->users?->image,

            // school detail
            'schoolDetailName' => $this->schoolDetails?->name,

            // text
            'liked'        => $this->liked,
            'improved'     => $this->improved,
            'status'       => $this->status,

            // timestamps
            'createdAt'    => $this->createdAt,
            'updatedAt'    => $this->updatedAt,

            // review details
            'reviewDetails' => ReviewDetailResource::collection($this->whenLoaded('reviewDetails')),

            // school validation
            'schoolValidation' => $this->mapSchoolValidation(),
        ];
    }

    private function mapSchoolValidation()
{
    $validations = $this->schoolValidation()
        ->orderBy('createdAt', 'desc')
        ->get();

    if ($validations->isEmpty()) {
        return null;
    }

    return $validations->map(function ($v) {
        return [
            'file'       => $v->fileUrl,
            'userStatus' => $v->status,
            'createdAt'  => $v->createdAt,
        ];
    });
}
}
