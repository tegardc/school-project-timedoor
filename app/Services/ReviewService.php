<?php

namespace App\Services;

use App\Models\Review;
use App\Models\ReviewDetail;
use App\Models\ReviewLike;
use App\Models\SchoolValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ReviewService extends BaseService
{
    public function __construct()
    {
        $this->modelClass = Review::class;
    }
    public function getReview($id)
    {
        return Review::where('schoolId', $id)->get();
    }
    public function getReviewDetail($id)
    {
        return ReviewDetail::where('reviewId', $id)->get();
    }

    public function getAll($schoolDetailId, $perPage = null)
    {
        $review = Review::select([
            'id',
            'reviewText',
            'rating',
            'userId',
            'schoolDetailId',
            'createdAt',
            'updatedAt'
        ])
            ->where('schoolDetailId', $schoolDetailId)
            ->where('status', Review::STATUS_APPROVED)
            ->with([
                'users:id,username,image',
                'schoolDetails:id,name',
                'reviewDetails' => function ($q) {
                    $q->with('question:id,question');
                }
            ])
            ->orderByDesc('isPinned')
            ->orderByDesc('createdAt');

        return $review->paginate($perPage ?? 10);
    }
    public function approve($id)
    {
        $review = Review::find($id);
        if (!$review) {
            return false;
        }
        return Review::where('id', $id)->update(['status' => Review::STATUS_APPROVED]);
    }
    public function rejected($id)
    {
        $review = Review::find($id);
        if (!$review) {
            return false;
        }
        return Review::where('id', $id)->update(['status' => Review::STATUS_REJECTED]);
    }
    public function store(array $validated): Review
    {
        return DB::transaction(function () use ($validated) {
            $review = Review::create($validated);
            return $review;
        });
    }
    public function createOrUpdate(array $data, int $schoolDetailId): Review
    {
        $userId     = Auth::id();
        $details    = $data['details'];
        $reviewText = $data['reviewText'] ?? null;
        $liked       = $data['liked'] ?? null;
        $improved    = $data['improved'] ?? null;
        $totalScore = array_sum(array_column($details, 'score'));
        $rating     = round($totalScore / count($details), 2);

        return DB::transaction(function () use ($userId, $schoolDetailId, $reviewText, $liked, $improved, $details, $rating) {
            $review = Review::where('userId', $userId)
                ->where('schoolDetailId', $schoolDetailId)
                ->first();

            if ($review) {
                $review->update([
                    'reviewText' => $reviewText,
                    'rating'     => $rating,
                    'liked'      => $liked,
                    'improved'   => $improved,
                    'status'     => Review::STATUS_PENDING
                ]);
                $review->reviewDetails()->delete();
            } else {
                $review = Review::create([
                    'reviewText'     => $reviewText,
                    'liked'          => $liked,
                    'improved'       => $improved,
                    'rating'         => $rating,
                    'userId'         => $userId,
                    'schoolDetailId' => $schoolDetailId,
                    'status'         => Review::STATUS_PENDING
                ]);
            }

            foreach ($details as $detail) {
                ReviewDetail::create([
                    'reviewId'   => $review->id,
                    'questionId' => $detail['questionId'],
                    'score'      => $detail['score'],
                ]);
            }

            return $review->load(['reviewDetails.question']);
        });
    }

    public function getRecentReview($limit = 5)
    {
        return Review::select([
            'id',
            'rating',
            'liked',
            'improved',
            'status',
            'userId',
            'schoolDetailId',
            'createdAt'
        ])
            ->where('status', Review::STATUS_APPROVED)
            ->with([
                'users.schoolValidations',
                'schoolDetails:id,name',


                'reviewDetails' => function ($q) {
                    $q->with('question:id,question');
                }
            ])
            ->orderByDesc('createdAt')
            ->limit($limit)
            ->get();
    }

    public function AllReview(array $filters = [], $perPage = 10)
    {
        $query = Review::with([
            'users.roles',
            'users.children.schoolDetail.educationExperiences',
            'users.educationExperiences.schoolDetail',
            'schoolDetails:id,name',
            'reviewDetails' => function ($q) {
                $q->with('question:id,question');
            }
        ])->where('status', Review::STATUS_APPROVED);

        $query->when($filters, function ($query) use ($filters) {
            $this->applyFilters($query, $filters);
        });

        return $query->paginate($perPage);
    }

    private function applyFilters($query, array $filters)
    {
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->whereHas('schoolDetails', function ($q2) use ($filters) {
                    $q2->where('name', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('institutionCode', 'like', '%' . $filters['search'] . '%');
                })->orWhereHas('users', function ($q3) use ($filters) {
                    $q3->where('fullname', 'like', '%' . $filters['search'] . '%');
                });
            });
        }

        if (!empty($filters['provinceName'])) {
            $query->whereHas('schoolDetails.address.province', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['provinceName'] . '%');
            });
        }

        if (!empty($filters['districtName'])) {
            $query->whereHas('schoolDetails.address.district', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['districtName'] . '%');
            });
        }
        if (!empty($filters['subDistrictName'])) {
            $query->whereHas('schoolDetails.address.subdistrict', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['subDistrictName'] . '%');
            });
        }

        if (!empty($filters['educationLevelName'])) {
            $query->whereHas('schoolDetails.educationLevel', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['educationLevelName'] . '%');
            });
        }

        if (!empty($filters['statusName'])) {
            $query->whereHas('schoolDetails.status', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['statusName'] . '%');
            });
        }

        if (!empty($filters['accreditationCode'])) {
            $query->whereHas('schoolDetails.accreditation', function ($q) use ($filters) {
                $q->where('code', 'like', '%' . $filters['accreditationCode'] . '%');
            });
        }

        if (!empty($filters['minRating'])) {
            $query->where('rating', '>=', $filters['minRating']);
        }
        if (!empty($filters['maxRating'])) {
            $query->where('rating', '<=', $filters['maxRating']);
        }
        if (!empty($filters['starRating'])) {
            $star = (int) $filters['starRating'];
            $lower = max(0, $star - 0.5);
            $upper = min(5, $star + 0.5);

            $query->whereBetween('rating', [$lower, $upper]);
        }

        if (!empty($filters['role'])) {
            $query->whereHas('users.roles', function ($q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        if (!empty($filters['sort'])) {
            if ($filters['sort'] === 'newest') {
                $query->orderBy('createdAt', 'desc');
            } elseif ($filters['sort'] === 'oldest') {
                $query->orderBy('createdAt', 'asc');
            }
        } else {
            if (!empty($filters['sortBy'])) {
                $sortField = $filters['sortBy'];
                $sortDirection = $filters['sortDirection'] ?? 'asc';

                $allowedSortFields = ['rating', 'createdAt', 'updatedAt'];

                if (in_array($sortField, $allowedSortFields)) {
                    $query->orderBy($sortField, $sortDirection);
                }
            } else {
                $query->orderByDesc('createdAt');
            }
        }

        return $query;
    }


    public function getSchoolReviewsWithRating(int $schoolDetailId, array $filters = [], int $perPage = 10)
{
    $userId = Auth::id();

    $query = Review::with([
        'users' => function ($q) {
            $q->select('id', 'fullname', 'email', 'image', 'status')
                ->with(['educationExperiences.schoolDetail:id,name']);
        },
        'schoolDetails:id,name',
        'reviewDetails:id,reviewId,questionId,score'
    ])
        ->withCount('likes')
        ->where('schoolDetailId', $schoolDetailId)
        ->where('status', Review::STATUS_APPROVED);

    if (!empty($filters)) {
        $this->applyFilters($query, $filters);
    }

    $reviews = $query->paginate($perPage);

    if ($reviews->isEmpty()) {
        return [
            'reviews' => $reviews,
            'questionStats' => [],
            'finalRating' => 0,
            'totalRating' => 0
        ];
    }

    if ($userId) {
        $reviewIds = $reviews->getCollection()->pluck('id')->toArray();

        $likedReviewIds = DB::table('review_likes')
            ->where('userId', $userId)
            ->whereIn('reviewId', $reviewIds)
            ->pluck('reviewId')
            ->toArray();

        $reviews->getCollection()->transform(function ($review) use ($likedReviewIds) {
            $review->is_liked = in_array($review->id, $likedReviewIds);
            return $review;
        });
    } else {
        $reviews->getCollection()->transform(function ($review) {
            $review->is_liked = false;
            return $review;
        });
    }

    $reviewIds = $reviews->pluck('id');

    $questionStats = ReviewDetail::select(
        'questionId',
        DB::raw('AVG(score) as avg_score'),
        DB::raw('SUM(score) as total_score')
    )
        ->whereIn('reviewId', $reviewIds)
        ->groupBy('questionId')
        ->get();



    $globalStats = DB::table('reviews')
        ->where('schoolDetailId', $schoolDetailId)
        ->where('status', Review::STATUS_APPROVED)
        ->selectRaw('AVG(rating) as global_avg, COUNT(*) as total_reviews')
        ->first();

    $finalRating = $globalStats->global_avg ? round($globalStats->global_avg, 1) : 0;

    $totalRating = $globalStats->total_reviews ?? 0;

    return [
        'reviews'       => $reviews,
        'questionStats' => $questionStats,
        'finalRating'   => $finalRating,
        'totalRating'   => $totalRating,
    ];
}

    // public function submitFullReview(array $data)
    // {
    //     $user = Auth::user();

    //     Log::info($data);

    //     $details = $data['details'];
    //     $totalScore = array_sum(array_column($details, 'score'));
    //     $rating = round($totalScore / count($details), 2);

    //     return DB::transaction(function () use ($user, $data, $rating, $details) {
    //         $user->update([
    //             'fullname' => $data['fullname'] ?? $user->fullname,
    //             'email'    => $data['email'] ?? $user->email,
    //             'phoneNo'  => $data['phoneNo'] ?? $user->phoneNo,
    //         ]);

    //         $schoolValidation = null;

    //         if (!empty($data['schoolValidationFile'])) {
    //             $schoolValidation = SchoolValidation::create(
    //                 [
    //                     'userId'         => $user->id,
    //                     'schoolDetailId' => $data['schoolDetailId'],
    //                     'fileUrl'        => $data['schoolValidationFile'] ?? null,
    //                     'status'         => $data['userStatus'] ?? null,
    //                     // 'status'         => 'aktif',
    //                 ]
    //             );
    //         }

    //         $review = Review::create([
    //             'userId'          => $user->id,
    //             'schoolDetailId'  => $data['schoolDetailId'],
    //             'reviewText'      => $data['reviewText'] ?? null,
    //             'liked'           => $data['liked'] ?? null,
    //             'improved'        => $data['improved'] ?? null,
    //             'rating'          => $rating,
    //             'status'          => Review::STATUS_PENDING,
    //         ]);


    //         $detailReviews = [];
    //         foreach ($details as $d) {
    //             $detailReviews[] = [
    //                 'reviewId'   => $review->id,
    //                 'questionId' => $d['questionId'],
    //                 'score'      => $d['score'],
    //                 'createdAt'  => now(),
    //                 'updatedAt'  => now(),
    //             ];
    //         }

    //         ReviewDetail::insert($detailReviews);

    //         // return $review->load([
    //         //     'reviewDetails.question',
    //         //     'schoolValidation',
    //         // ]);

    //         $review->load('reviewDetails.question');

    //         return [
    //             'review'           => $review->toArray(),
    //             'schoolValidation' => $schoolValidation?->toArray(),
    //             'reviewDetails'    => $review->reviewDetails->toArray(),
    //         ];
    //     });
    // }
    public function submitFullReview(array $data)
{
    $user = Auth::user();
    $schoolDetailId = $data['schoolDetailId'];

    // VALIDASI: Cek apakah user punya education experience di sekolah ini
    if (!$this->userHasEducationExperience($user->id, $schoolDetailId)) {
        throw new \Exception('Anda harus memiliki pengalaman pendidikan di sekolah ini untuk memberikan review.');
    }

    $details = $data['details'];
    $totalScore = array_sum(array_column($details, 'score'));
    $rating = round($totalScore / count($details), 2);

    return DB::transaction(function () use ($user, $data, $rating, $details, $schoolDetailId) {
        $user->update([
            'fullname' => $data['fullname'] ?? $user->fullname,
            'email'    => $data['email'] ?? $user->email,
            'phoneNo'  => $data['phoneNo'] ?? $user->phoneNo,
        ]);

        $schoolValidation = null;

        if (!empty($data['schoolValidationFile'])) {
            $schoolValidation = SchoolValidation::create([
                'userId'         => $user->id,
                'schoolDetailId' => $schoolDetailId,
                'fileUrl'        => $data['schoolValidationFile'] ?? null,
                'status'         => $data['userStatus'] ?? null,
            ]);
        }

        $review = Review::create([
            'userId'          => $user->id,
            'schoolDetailId'  => $schoolDetailId,
            'liked'           => $data['liked'] ?? null,
            'improved'        => $data['improved'] ?? null,
            'rating'          => $rating,
            'status'          => Review::STATUS_PENDING,
        ]);

        if ($schoolValidation && $review) {
            $schoolValidation->update(['reviewId' => $review->id]);
        }

        $detailReviews = [];
        foreach ($details as $d) {
            $detailReviews[] = [
                'reviewId'   => $review->id,
                'questionId' => $d['questionId'],
                'score'      => $d['score'],
                'createdAt'  => now(),
                'updatedAt'  => now(),
            ];
        }

        ReviewDetail::insert($detailReviews);
        $review->load('reviewDetails.question', 'schoolValidation');

        return [
            'review'           => $review->toArray(),
            'schoolValidation' => $schoolValidation?->toArray(),
            'reviewDetails'    => $review->reviewDetails->toArray(),
        ];
    });
}


    public function togglePin(int $id): Review
    {
        $review = Review::find($id);

        if (!$review) {
            throw new \Exception('Review tidak ditemukan.');
        }

        $newStatus = !$review->isPinned;
        $review->update(['isPinned' => $newStatus]);

        return $review->refresh();
    }
    //    public function getUserReviews(int $userId = null, $perPage = 10)
    //     {
    //         $userId = $userId ?? Auth::id();

    //         $query = Review::select([
    //             'id', 'userId', 'reviewText', 'liked', 'improved', 'rating',
    //             'schoolDetailId', 'createdAt', 'status', 'isPinned',
    //         ])
    //             ->where('userId', $userId)
    //             ->with([
    //                 'schoolValidation' => function($query) use ($userId) {
    //                     $query->where('userId', $userId);
    //                 },
    //                 'reviewDetails.question',
    //                 'schoolDetails:id,name',
    //                 'users:id,fullname,image'
    //             ])
    //             ->orderByDesc('isPinned')
    //             ->orderByDesc('createdAt');

    //         // Log::info($query->get()); // Boleh dihapus jika sudah fix

    //         $totalReviews = (clone $query)->count();
    //         $reviews = $query->paginate($perPage);

    //         return [
    //             'totalReviews' => $totalReviews,
    //             'reviews' => $reviews,
    //         ];
    //     }
    public function getUserReviews(int $userId = null, $perPage = 10)
    {
        $userId = $userId ?? Auth::id();
        $query = Review::where('userId', $userId)
            ->with([
                'users.schoolValidations' => function ($q) use ($userId) {
                    $q
                        ->where('userId', $userId)
                        ->orderBy('id', 'desc');
                },
                'schoolDetails',
                'reviewDetails.question'
            ])
            ->orderBy('createdAt', 'desc');

        $totalReviews = (clone $query)->count();
        $reviews = $query->paginate($perPage);
        return [
            'totalReviews' => $totalReviews,
            'reviews'      => $reviews,
        ];
    }

    public function updateUserReview(int $reviewId, array $data)
    {
        $userId = Auth::id();
        $review = Review::where('id', $reviewId)->where('userId', $userId)->first();

        if (!$review) {
            throw new \Exception('Review tidak ditemukan atau bukan milik Anda.');
        }

        $details = $data['details'] ?? [];
        $reviewText = $data['reviewText'] ?? $review->reviewText;
        $liked      = $data['liked'] ?? $review->liked;
        $improved   = $data['improved'] ?? $review->improved;

        // Hitung ulang rating
        if (!empty($details)) {
            $totalScore = array_sum(array_column($details, 'score'));
            $rating = round($totalScore / count($details), 2);
        } else {
            $rating = $review->rating;
        }

        return DB::transaction(function () use ($review, $reviewText, $liked, $improved, $rating, $details) {

            $review->update([
                'reviewText' => $reviewText,
                'liked'      => $liked,
                'improved'   => $improved,
                'rating'     => $rating,
                'status'     => Review::STATUS_PENDING
            ]);

            if (!empty($details)) {
                $review->reviewDetails()->delete();

                foreach ($details as $detail) {
                    ReviewDetail::create([
                        'reviewId'   => $review->id,
                        'questionId' => $detail['questionId'],
                        'score'      => $detail['score'],
                    ]);
                }
            }

            return $review->load(['reviewDetails.question']);
        });
    }
    public function deleteUserReview(int $reviewId)
    {
        $userId = Auth::id();

        $review = Review::where('id', $reviewId)
            ->where('userId', $userId)
            ->first();

        if (!$review) {
            throw new \Exception('Review tidak ditemukan atau bukan milik Anda.');
        }

        return DB::transaction(function () use ($review) {
            $review->reviewDetails()->delete();
            $review->delete();
            return true;
        });
    }
    /**
     * Mengambil semua review dengan status pending
     */
    public function getPendingReviews()
    {
        return Review::where('status', Review::STATUS_PENDING)
            ->with([
                'users.schoolValidations',
                'schoolDetails:id,name',
                'reviewDetails.question'
            ])
            ->orderByDesc('createdAt')
            ->get();
    }
    /**
     * Mengambil review yang ditolak (Rejected)
     */
    public function getRejectedReviews()
    {
        return Review::where('status', Review::STATUS_REJECTED)
            ->with([
                'users.schoolValidations',
                'schoolDetails:id,name',
                'reviewDetails.question'
            ])
            ->orderByDesc('updatedAt')
            ->get();
    }

    public function getApprovedReviews()
    {
        return Review::where('status', Review::STATUS_APPROVED)
            ->with([
                // Load validasi via user agar Resource tidak null
                'users.schoolValidations',
                'schoolDetails:id,name',
                'reviewDetails.question'
            ])
            ->orderByDesc('updatedAt') // Sorting berdasarkan kapan diapprove
            ->get();
    }
    public function checkSchoolValidation($schoolDetailId, $userId)
    {
        return Review::where('userId', $userId)->where('schoolDetailId', $schoolDetailId)->exists();
    }
    public function toggleLike(int $reviewId)
    {
        $userId = Auth::id();

        if (!$userId) {
            throw new \Exception('User harus login untuk like review.');
        }

        $review = Review::find($reviewId);

        if (!$review) {
            throw new \Exception('Review tidak ditemukan.');
        }

        // Cek apakah user sudah like review ini
        $existingLike = ReviewLike::where('reviewId', $reviewId)
            ->where('userId', $userId)
            ->first();

        if ($existingLike) {
            // Unlike - hapus like
            $existingLike->delete();

            return [
                'success' => true,
                'action' => 'unliked',
                'message' => 'Review berhasil di-unlike',
                'likesCount' => $review->fresh()->likesCount
            ];
        } else {
            // Like - tambah like baru
            ReviewLike::create([
                'reviewId' => $reviewId,
                'userId' => $userId,
                'createdAt' => now()
            ]);

            return [
                'success' => true,
                'action' => 'liked',
                'message' => 'Review berhasil di-like',
                'likesCount' => $review->fresh()->likesCount
            ];
        }
    }

    /**
     * Mendapatkan total likes untuk sebuah review
     */
    public function getLikesCount(int $reviewId)
    {
        return ReviewLike::where('reviewId', $reviewId)->count();
    }

    /**
     * Cek apakah user sudah like review tertentu
     */
    public function isLikedByUser(int $reviewId, int $userId = null)
    {
        $userId = $userId ?? Auth::id();

        if (!$userId) {
            return false;
        }

        return ReviewLike::where('reviewId', $reviewId)
            ->where('userId', $userId)
            ->exists();
    }

    /**
     * Mendapatkan list user yang like review tertentu
     */
    public function getUsersWhoLiked(int $reviewId, int $limit = 10)
    {
        return ReviewLike::where('reviewId', $reviewId)
            ->with('user:id,fullname,image')
            ->orderByDesc('createdAt')
            ->limit($limit)
            ->get();
    }

    /**
     * Mendapatkan review yang paling banyak di-like
     */
    public function getMostLikedReviews(int $limit = 10)
    {
        return Review::withCount('likes')
            ->where('status', Review::STATUS_APPROVED)
            ->orderByDesc('likesCount')
            ->limit($limit)
            ->get();
    }

    public function userHasEducationExperience($userId, $schoolDetailId)
    {
        return \App\Models\EducationExperience::where('userId', $userId)
            ->where('schoolDetailId', $schoolDetailId)
            ->exists();
    }
}
