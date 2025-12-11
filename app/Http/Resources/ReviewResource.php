<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // ==================================================================
        // 1. NORMALISASI DATA (ADAPTER PATTERN)
        // ==================================================================

        $user = null;
        $reviewData = null;
        $schoolValidationData = null;
        $reviewDetailsData = [];
        $schoolDetailName = null;

        // CEK: Skenario 1 - Dari Submit (Input berupa Array)
        if (is_array($this->resource) && isset($this->resource['user'])) {
            $userRaw = $this['user'];
            $serviceResult = $this['review'];

            $user = (object) $userRaw;
            $reviewData = $serviceResult['review'];

            $schoolValidationData = $serviceResult['schoolValidation'] ?? null;
            $reviewDetailsData = $serviceResult['reviewDetails'] ?? [];

            $schoolDetailName = $reviewData['school_details']['name']
                                ?? $reviewData['schoolDetails']['name']
                                ?? null;
        }
        // CEK: Skenario 2 - Dari Get List (Input berupa Model Eloquent)
        else {
            $user = $this->users;

            // Gunakan $this->resource->toArray() agar aman
            $reviewData = $this->resource->toArray();

            $reviewDetailsData = $this->reviewDetails;
            $schoolDetailName = $this->schoolDetails->name ?? null;

            // --- [PERBAIKAN UTAMA DISINI] ---
            // 1. Coba ambil dari relasi langsung
            $sv = $this->schoolValidation;

            // 2. LOGIKA PENCARIAN (FALLBACK):
            // Jika direct relation kosong, kita cari di dalam list validasi milik User.
            // (Service getUserReviews memuat 'users.schoolValidations')
            if (!$sv && $this->relationLoaded('users') && $this->users->relationLoaded('schoolValidations')) {
                // Filter: Cari validasi milik user ini, untuk sekolah yang direview ini
                $sv = $this->users->schoolValidations
                        ->where('schoolDetailId', $this->schoolDetailId)
                        // PENTING: Ambil yang ID-nya paling besar (Terbaru) agar status 'aktif' muncul
                        ->sortByDesc('id')
                        ->first();
            }

            $schoolValidationData = $sv;
            // ------------------------------------
        }

        // Helper Normalisasi Data
        $svArr = is_array($schoolValidationData) ? $schoolValidationData : (is_object($schoolValidationData) ? $schoolValidationData->toArray() : []);

        // ==================================================================
        // 2. RETURN JSON FORMAT
        // ==================================================================

        return [
            'id'             => $reviewData['id'] ?? null,
            'userId'         => $user->id ?? null,
            'fullname'       => $user->fullname ?? null,
            'image'          => $user->image ?? null,

            'userStatus'     => $svArr['status'] ?? null, // Sekarang pasti muncul
            'schoolDetailId'   => $reviewData['schoolDetailId'] ?? null,
            'schoolDetailName' => $schoolDetailName,

            // 'reviewText'     => $reviewData['reviewText'] ?? null,
            'liked'          => $reviewData['liked'] ?? null,
            'improved'       => $reviewData['improved'] ?? null,
            'rating'         => isset($reviewData['rating']) ? (float) $reviewData['rating'] : 0,
            'status'         => $reviewData['status'] ?? 'pending',

            'createdAt'      => $reviewData['createdAt'] ?? null,
            'updatedAt'      => $reviewData['updatedAt'] ?? null,

            'review_details' => collect($reviewDetailsData)->map(function ($detail) {
                $d = (object) $detail;
                $q = $d->question ?? null;
                $qText = is_array($q) ? ($q['question'] ?? null) : ($q->question ?? null);

                return [
                    'id'         => $d->id,
                    'questionId' => $d->questionId,
                    'score'      => number_format((float) $d->score, 2),
                    'question'   => $qText,
                ];
            }),

            'school_validation' => !empty($svArr) ? [
                'file'       => $svArr['fileUrl'] ?? $svArr['file_url'] ?? null,
                'userStatus' => $svArr['status'] ?? null,
            ] : null,
        ];
    }
}
