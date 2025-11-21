<?php

namespace App\Services;
use App\Models\Review;
use App\Models\ReviewDetail;
use App\Models\SchoolValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'reviewText',
            'rating',
            'userId',
            'schoolDetailId',
            'createdAt'
        ])
            ->where('status', Review::STATUS_APPROVED)
            ->with([
                'users:id,fullname,image',
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


    public function getSchoolReviewsWithRating(int $schoolDetailId, array $filters = [])
    {
        $query = Review::with([
            'users' => function ($q) {
                $q->select('id', 'fullname', 'email', 'image', 'status')
                    ->with(['educationExperiences.schoolDetail:id,name']);
            },
            'schoolDetails:id,name',
            'reviewDetails:id,reviewId,questionId,score'
        ])
            ->where('schoolDetailId', $schoolDetailId)
            ->where('status', Review::STATUS_APPROVED);

        if (!empty($filters)) {
            $this->applyFilters($query, $filters);
        }

        $reviews = $query->get();
        if ($reviews->isEmpty()) {
            return [
                'reviews' => [],
                'questionStats' => [],
                'finalRating' => 0,
                'totalRating' => 0
            ];
        }

        $questionStats = ReviewDetail::select(
            'questionId',
            DB::raw('AVG(score) as avg_score'),
            DB::raw('SUM(score) as total_score')
        )
            ->whereIn('reviewId', $reviews->pluck('id'))
            ->groupBy('questionId')
            ->get();

        $totalRating = $reviews->sum('rating');
        $finalRating = $questionStats->avg('avg_score');

        return [
            'reviews' => $reviews,
            'questionStats' => $questionStats,
            'finalRating' => round($finalRating, 2),
            'totalRating' => round($totalRating, 2),
        ];
    }


    public function submitFullReview(array $data): Review
    {
        $user = Auth::user();
        $userId = $user->id;

        $details = $data['details'];
        $totalScore = array_sum(array_column($details, 'score'));
        $rating = round($totalScore / count($details), 2);

        return DB::transaction(function () use ($user, $userId, $data, $rating, $details) {
            $user->update([
                'fullname' => $data['fullname'] ?? $user->fullname,
                'email'    => $data['email'] ?? $user->email,
                'phoneNo'  => $data['phoneNo'] ?? $user->phoneNo,
            ]);
            $schoolValidation = null;
            if (!empty($data['schoolValidation'])) {
                $schoolValidation = \App\Models\SchoolValidation::updateOrCreate(
                    [
                        'userId'         => $user->id,
                        'schoolDetailId' => $data['schoolDetailId'],
                    ],
                    [
                        'fileUrl'        => $data['schoolValidation'],
                    ]
                );
            }

            $review = Review::create([
                'userId'          => $user->id,
                'schoolDetailId'  => $data['schoolDetailId'],
                'reviewText'      => $data['reviewText'] ?? null,
                'liked'           => $data['liked'] ?? null,
                'improved'        => $data['improved'] ?? null,
                'rating'          => $rating,
                'status'          => Review::STATUS_PENDING,
            ]);
            foreach ($details as $d) {
                ReviewDetail::create([
                    'reviewId'   => $review->id,
                    'questionId' => $d['questionId'],
                    'score'      => $d['score'],
                ]);
            }
            return $review->load([
                'reviewDetails.question',
                'schoolValidation' => function ($q) use ($schoolValidation) {
                    $q->where('id', $schoolValidation?->id);
                }
            ]);
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
    public function getUserReviews(int $userId = null, $perPage = 10)
    {
        $userId = $userId ?? Auth::id();

        $query = Review::select([
            'id',
            'reviewText',
            'rating',
            'schoolDetailId',
            'createdAt',
            'status',
            'isPinned',
        ])
            ->where('userId', $userId)
            ->with([
                'schoolDetails:id,name',
                'reviewDetails' => function ($q) {
                    $q->with('question:id,question');
                }
            ])
            ->orderByDesc('isPinned')
            ->orderByDesc('createdAt');

        $totalReviews = (clone $query)->count();
        $reviews = $query->paginate($perPage);

        return [
            'totalReviews' => $totalReviews,
            'reviews' => $reviews,
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
}
