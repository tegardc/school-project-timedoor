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

            // ✅ LIKES INFORMATION
            'likesCount'   => $this->likes_count ?? 0,  // dari withCount('likes')
            'isLiked'      => $this->is_liked ?? false, // dari transform di service

            // timestamps
            'createdAt'    => $this->createdAt,
            'updatedAt'    => $this->updatedAt,

            // review details
            'reviewDetails' => ReviewDetailResource::collection($this->whenLoaded('reviewDetails')),

            // school validation
            'schoolValidation' => $this->mapSchoolValidation(),
        ];
    }

    /**
     * Map school validation data
     * Ambil validasi terbaru dari relasi
     */
    private function mapSchoolValidation()
    {
        // Cek apakah relasi users ter-load
        if (!$this->relationLoaded('users') || !$this->users) {
            return null;
        }

        // Cek apakah schoolValidations ter-load via eager loading
        if (!$this->users->relationLoaded('schoolValidations')) {
            return null;
        }

        // Filter validasi yang sesuai dengan schoolDetailId dari review ini
        $validations = $this->users->schoolValidations
            ->where('schoolDetailId', $this->schoolDetailId)
            ->sortByDesc('createdAt');

        if ($validations->isEmpty()) {
            return null;
        }

        // Return yang terbaru saja
        $latest = $validations->first();

        return [
            'file'       => $latest->fileUrl,
            'userStatus' => $latest->status,
            'createdAt'  => $latest->createdAt,
        ];
    }
}
