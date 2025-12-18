<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ReviewRequest;
use App\Http\Requests\ReviewSubmitRequest;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\ReviewUserResource;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request, ReviewService $service, $schoolDetailId)
    // {
    //     try {
    //         $perPage = $request->query('perPage',10);
    //         $review = $service->getAll($schoolDetailId,$perPage);
    //         if($review->isEmpty()) return ResponseHelper::notFound('Review Not Found');
    //         $reviewTransform = ReviewResource::collection($review);
    //         return ResponseHelper::success([
    //             'datas' => $reviewTransform,
    //             'meta' => [
    //                 'current_page' => $reviewTransform->currentPage(),
    //                 'last_page' => $reviewTransform->lastPage(),
    //                 'limit' => $reviewTransform->perPage(),
    //                 'total' => $reviewTransform->total(),
    //             ]
    //             ],'Review Display Success');

    //     } catch (\Exception $e) {
    //         return ResponseHelper::serverError("Oops display all review is failed ", $e, "[REVIEW INDEX]: ");
    //     }
    //     //
    // }
    public function index(int $schoolDetailId, Request $request, ReviewService $service)
    {
        try {
            $filters = $request->only([
                'provinceName',
                'districtName',
                'subDistrictName',
                'educationLevelName',
                'statusName',
                'accreditationCode',
                'search',
                'sortBy',
                'sortDirection',
                'minRating',
                'maxRating',
                'starRating',
                'role',
                'sort',
            ]);

            // Ambil limit / perPage
            $perPage = $request->query('perPage', 10);

            // Ambil result dari service (HARUS return paginator)
            $result = $service->getSchoolReviewsWithRating($schoolDetailId, $filters, $perPage);

            // Cek kalau kosong
            if ($result['reviews']->isEmpty()) {
                return ResponseHelper::notFound("Review untuk sekolah ini belum tersedia.");
            }

            // Response dengan data likes
            return ResponseHelper::success([
                'reviews' => ReviewUserResource::collection($result['reviews']),
                'meta' => [
                    'current_page'   => $result['reviews']->currentPage(),
                    'last_page'      => $result['reviews']->lastPage(),
                    'limit'          => $result['reviews']->perPage(),
                    'total'          => $result['reviews']->total(),
                    'finalRating'    => $result['finalRating'],
                    'totalRating'    => $result['totalRating'],
                    'questionStats'  => $result['questionStats'],

                    'likesStats' => [
                        'totalLikes' => $result['reviews']->sum('likesCount'),
                        'reviewsWithLikes' => $result['reviews']->filter(function ($review) {
                            return $review->likesCount > 0;
                        })->count(),
                    ],
                ],
            ], 'Success get school reviews and rating');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display all review is failed ", $e, "[REVIEW INDEX]: ");
        }
    }
    public function approve($id)
    {
        try {
            $review = Review::find($id);
            if (!$review) {
                return ResponseHelper::notFound('Review Not Found');
            }
            $review->status = review::STATUS_APPROVED;
            $review->save();
            return ResponseHelper::success('Review Approved Successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops approved review is failed ", $e, "[REVIEW APPROVE]: ");
        }
    }
    public function reject($id)
    {
        try {
            $review = Review::find($id);
            if (!$review) {
                return ResponseHelper::notFound('Review Not Found');
            }
            $review->status = review::STATUS_REJECTED;
            $review->save();

            return ResponseHelper::success('Review Reject and Delete');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops rejected review is failed ", $e, "[REVIEW REJECTED]: ");
        }
    }
    public function pendingReviews(ReviewService $service)
    {
        try {
            $reviews = $service->getPendingReviews();

            if ($reviews->isEmpty()) {
                return ResponseHelper::notFound('Review Not Found');
            }

            return ResponseHelper::success(
                ReviewUserResource::collection($reviews),
                'List Review For Approved Or Reject'
            );
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display pending review is failed ", $e, "[REVIEW PENDINGREVIEWS]: ");
        }
    }
    public function rejectedReviews(ReviewService $service)
    {
        try {
            $reviews = $service->getRejectedReviews();

            if ($reviews->isEmpty()) {
                return ResponseHelper::notFound('Review Not Found');
            }

            return ResponseHelper::success(
                ReviewUserResource::collection($reviews),
                'List Review Rejected'
            );
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display rejected review is failed ", $e, "[REVIEW REJECTEDREVIEWS]: ");
        }
    }

    public function approvedReviews(ReviewService $service)
    {
        try {
            $reviews = $service->getApprovedReviews();

            if ($reviews->isEmpty()) {
                return ResponseHelper::notFound('Review Not Found');
            }

            return ResponseHelper::success(
                ReviewUserResource::collection($reviews),
                'List Review Approved'
            );
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display approved review is failed ", $e, "[REVIEW APPROVEREVIEWS]: ");
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(ReviewService $service, ReviewRequest $request, $schoolDetailId)
    // {
    //     $validated = $request->validated();
    //     try {
    //         // $userId = auth()->id();
    //         $review = $service->createOrUpdateReview(
    //             $request->only(['reviewText', 'details']),
    //             $request->user()->id,
    //             $schoolDetailId
    //         );
    //         $message = $review->wasRecentlyCreated ? 'Review Created Successfully.' : 'Review Updated Successfully';
    //         return ResponseHelper::success(new ReviewResource($review), $message);
    //     } catch (\Exception $e) {
    //         return ResponseHelper::serverError("Oops created review is failed ", $e, "[REVIEW STORE]: ");
    //     }
    //  } catch (\Exception $e) {
    //     return response()->json([
    //         'status' => 500,
    //         'success' => false,
    //         'message' => 'Error Updating Data: ' . $e->getMessage(),
    //     ], 500);
    // }
    //
    // }
    public function store(ReviewRequest $request, ReviewService $service, int $schoolDetailId)
    {
        try {
            $review = $service->createOrUpdate($request->validated(), $schoolDetailId);
            return ResponseHelper::success($review, 'Review saved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops created review is failed ", $e, "[REVIEW STORE]: ");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ReviewService $service, $id)
    {
        try {
            $review = $service->getReviewDetail($id);
            if (!$review) return ResponseHelper::notFound('Data Not Found');
            return ResponseHelper::success(new ReviewResource($review), 'Review Display Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display review is failed ", $e, "[REVIEW SHOW]: ");
        }
    }

    public function destroy(ReviewService $service, $id)
    {
        try {
            $review = Review::findOrFail($id);
            if (!$review) return ResponseHelper::notFound('Data Not Found');
            $service->softDelete($id);
            return ResponseHelper::success(null, 'Review moved to trash successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops deleted review is failed ", $e, "[REVIEW DELETED]: ");
        }
    }

    public function trash(ReviewService $service)
    {
        try {
            $review = $service->trash();
            if ($review->isEmpty()) {
                return ResponseHelper::notFound('Reviews not found');
            }
            return ResponseHelper::success(ReviewResource::collection($review), 'Review trashed items retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display review is failed ", $e, "[REVIEW TRASH]: ");
        }
    }
    public function restore(ReviewService $service, $id)
    {
        try {

            $review = $service->restore($id);
            if (!$review) return ResponseHelper::notFound('Data Not Found');

            return ResponseHelper::success([], 'Review restored successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops restore review is failed ", $e, "[REVIEW RESTORE]: ");
        }
    }
    public function recent(ReviewService $service)
    {
        try {
            $review = $service->getRecentReview(5);
            if ($review->isEmpty()) {
                return ResponseHelper::notFound('Reviews not found');
            }
            return ResponseHelper::success(ReviewUserResource::collection($review), 'Review recent items retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display review is failed ", $e, "[REVIEW RECENT]: ");
        }
    }
    public function getAllReview(Request $request, ReviewService $service)
    {
        try {
            $filters = $request->only([
                'provinceName',
                'districtName',
                'subDistrictName',
                'educationLevelName',
                'statusName',
                'accreditationCode',
                'search',
                'sortBy',
                'sortDirection',
                'minRating',
                'maxRating',
                'starRating'
            ]);
            $perPage = $request->query('perPage', 12);

            $review = $service->AllReview($filters, $perPage);
            if ($review->isEmpty()) {
                return ResponseHelper::success([], 'Reviews not found');
            }
            $reviewTransform = ReviewResource::collection($review);
            return ResponseHelper::success([
                'datas' => $reviewTransform,
                'meta' => [
                    'current_page' => $reviewTransform->currentPage(),
                    'last_page' => $reviewTransform->lastPage(),
                    'limit' => $reviewTransform->perPage(),
                    'total' => $reviewTransform->total(),
                ]
            ], 'Review Display Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display review is failed ", $e, "[REVIEW RECENT]: ");
        }
    }
    public function submitReview(ReviewService $service, ReviewSubmitRequest $request)
    {
        $user = Auth::user();
        if ($service->checkSchoolValidation($request->schoolDetailId, $user->id))
            return ResponseHelper::badRequest('Review Anda Masih Belum Divalidasi');

        $review = $service->submitFullReview($request->validated());

        $datas = [
            'user' => $user,
            'review' => $review
        ];

        return ResponseHelper::success(
            new ReviewResource($datas),
            'Review berhasil dikirim dan menunggu verifikasi admin.'
        );
    }

    public function togglePin($id, ReviewService $reviewService)
    {
        try {
            $review = $reviewService->togglePin($id);

            $message = $review->isPinned
                ? 'Review berhasil di-pin.'
                : 'Review berhasil di-unpin.';

            return ResponseHelper::success($review, $message);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 404);
        }
    }
    public function getUserReviews(ReviewService $reviewService, Request $request)
    {
        $perPage = $request->get('perPage', 10);
        $userId = $request->get('userId');

        $result = $reviewService->getUserReviews($userId, $perPage);

        return response()->json([
            'message' => 'Daftar review pengguna.',
            'totalReviews' => $result['totalReviews'],
            'data' => ReviewUserResource::collection($result['reviews'])
        ]);
    }
    // public function getUserReviews(ReviewService $reviewService)
    // {
    //     try {
    //         $user = Auth::user();

    //         // Panggil service
    //         $data = $reviewService->getUserReviews($user->id);

    //         return response()->json([
    //             'status'  => 'success',
    //             'message' => 'Data review berhasil diambil',
    //             'data'    => $data
    //         ], 200);

    //     } catch (\Exception $e) {
    //         Log::error('Error fetching user reviews: ' . $e->getMessage());

    //         return response()->json([
    //             'status'  => 'error',
    //             'message' => 'Gagal mengambil data review',
    //             'error'   => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function update(Request $request, int $id, ReviewService $service)
    {
        try {
            $data = $request->validate([
                'reviewText' => 'nullable|string',
                'liked'      => 'nullable|string',
                'improved'   => 'nullable|string',
                'details'    => 'required|array',
                'details.*.questionId' => 'required|integer',
                'details.*.score'      => 'required|integer|min:1|max:5',
            ]);

            $review = $service->updateUserReview($id, $data);

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil diperbarui. Menunggu approval admin.',
                'data' => $review
            ]);
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops update review is failed ", $e, "[REVIEW UPDATE]: ");
        }
    }
    public function deleteReviewForUser(int $id, ReviewService $service)
    {
        try {
            $deleted = $service->deleteUserReview($id);

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops deleted review is failed ", $e, "[REVIEW DELETED]: ");
        }
    }
    public function toggleLike(ReviewService $reviewLikeService, $reviewId)
    {
        try {
            $result = $reviewLikeService->toggleLike($reviewId);

            return response()->json([
                'success' => true,
                'data' => $result
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Mendapatkan jumlah likes untuk review
     * GET /api/reviews/{reviewId}/likes/count
     */
    public function getLikesCount(ReviewService $reviewLikeService, $reviewId)
    {
        try {
            $count = $reviewLikeService->getLikesCount($reviewId);

            return response()->json([
                'success' => true,
                'data' => [
                    'reviewId' => $reviewId,
                    'likesCount' => $count
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Cek apakah user sudah like review
     * GET /api/reviews/{reviewId}/likes/check
     */
    public function checkIfLiked(ReviewService $reviewLikeService, $reviewId)
    {
        try {
            $isLiked = $reviewLikeService->isLikedByUser($reviewId);

            return response()->json([
                'success' => true,
                'data' => [
                    'reviewId' => $reviewId,
                    'isLiked' => $isLiked
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Mendapatkan list user yang like review
     * GET /api/reviews/{reviewId}/likes/users
     */
    public function getUsersWhoLiked(ReviewService $reviewLikeService, $reviewId, Request $request)
    {
        try {
            $limit = $request->input('limit', 10);
            $users = $reviewLikeService->getUsersWhoLiked($reviewId, $limit);

            return response()->json([
                'success' => true,
                'data' => $users
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Mendapatkan review dengan likes terbanyak
     * GET /api/reviews/most-liked
     */
    public function getMostLiked(ReviewService $reviewLikeService, Request $request)
    {
        try {
            $limit = $request->input('limit', 10);
            $reviews = $reviewLikeService->getMostLikedReviews($limit);

            return response()->json([
                'success' => true,
                'data' => $reviews
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
