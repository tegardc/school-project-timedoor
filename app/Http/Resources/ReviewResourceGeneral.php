<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResourceGeneral extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id'           => $this->id,
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

            'likesCount'   => $this->likes_count ?? 0,  // dari withCount('likes')

            'userStatus'   => $this->getLatestUserStatus(),

            // timestamps
            'createdAt'    => $this->createdAt,
            // 'updatedAt'    => $this->updatedAt,

            // review details
            // 'reviewDetails' => ReviewDetailResource::collection($this->whenLoaded('reviewDetails')),

            // school validation
            // 'schoolValidation' => $this->mapSchoolValidation(),
        ];
    }


    private function getLatestUserStatus()
    {
        $latestValidation = $this->schoolValidation()
            ->orderBy('createdAt', 'desc')
            ->first();

        return $latestValidation?->status;
    }
}
