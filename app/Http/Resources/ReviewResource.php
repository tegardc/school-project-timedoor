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
        return [
            'id' => $this->id,
            'reviewText' => $this->reviewText,
            'rating' => $this->rating,
            'userId' => $this->userId,
            'username' => $this->users->username ?? null,
            'schoolDetailId' => $this->schoolDetailId,
            'schoolDetailName' => $this->schoolDetails->name ?? null,
            // 'user' => new UserResource($this->whenLoaded('users')),
            'reviewDetails' => ReviewDetailResource::collection($this->whenLoaded('reviewDetails')),
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            // 'createdAt' => $this->createdAt,
            // 'updatedAt' => $this->updatedAt,
        ];
    }
}
