<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return [
        //     'id' => $this->id,
        //     'reviewText' => $this->reviewText,
        //     'rating' => $this->rating,
        //     'userId' => $this->userId,
        //     'username' => $this->users->username ?? null,
        //     'image' => $this->users->image ?? null,
        //     'schoolDetailId' => $this->schoolDetailId,
        //     'schoolDetailName' => $this->schoolDetails->name ?? null,
        //     // 'user' => new UserResource($this->whenLoaded('users')),
        //     'reviewDetails' => ReviewDetailResource::collection($this->whenLoaded('reviewDetails')),
        //     'createdAt' => $this->createdAt,
        //     'updatedAt' => $this->updatedAt,
        //     // 'createdAt' => $this->createdAt,
        //     // 'updatedAt' => $this->updatedAt,
        // ];
        return [
            'id'             => $this->id,
            'userId'         => $this->userId,
            'fullname'         => $this->users->fullname ?? null,
            'image'            => $this->users->image ?? null,
            'userStatus'       => $this->users->status ?? null,
            'schoolDetailId'   => $this->schoolDetailId,
            'schoolDetailName' => $this->schoolDetails->name ?? null,
            'reviewText'     => $this->reviewText,
            'liked'          => $this->liked,
            'improved'       => $this->improved,
            'rating'         => (float) $this->rating,
            'status'         => $this->status,
            'createdAt'      => $this->createdAt,
            'updatedAt'      => $this->updatedAt,

            // 🔹 Review detail + pertanyaan
            'review_details' => $this->whenLoaded('reviewDetails', function () {
                return $this->reviewDetails->map(function ($detail) {
                    return [
                        'id'         => $detail->id,
                        'questionId' => $detail->questionId,
                        'score'      => number_format($detail->score, 2),
                        'question'   => $detail->question?->question,
                    ];
                });
            }),
            'schoolValidationFile' => $this->whenLoaded('schoolValidation', function () {
                return $this->schoolValidation->fileUrl ?? null;
            }),
        ];
    }
}
