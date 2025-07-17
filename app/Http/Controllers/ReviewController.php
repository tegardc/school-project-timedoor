<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ReviewService $service, $schoolDetailId)
    {
        try {
            $perPage = $request->query('perPage',10);
            $review = $service->getAll($schoolDetailId,$perPage);
            if($review->isEmpty()) return ResponseHelper::notFound('Review Not Found');
            return ResponseHelper::success(ReviewResource::collection($review), 'Review Display Success');

        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display all review is failed ", $e, "[REVIEW INDEX]: ");
        }
        //
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
    public function pendingReviews()
    {
        try {
            $review = Review::where('status', review::STATUS_PENDING)->with(['users', 'schoolDetails'])->get();
            if($review->isEmpty()) return ResponseHelper::notFound('Review Not Found');
            return ResponseHelper::success(ReviewResource::collection($review), 'List Review For Approved Or Reject');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display pending review is failed ", $e, "[REVIEW PENDINGREVIEWS]: ");
        }
    }
    public function rejectedReviews()
    {
        try {
            $review = Review::where('status', review::STATUS_REJECTED)->with(['users', 'schoolDetails'])->get();
            if($review->isEmpty()) return ResponseHelper::notFound('Review Not Found');
            return ResponseHelper::success(ReviewResource::collection($review), 'List Review Reject');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display rejected review is failed ", $e, "[REVIEW REJECTEDREVIEWS]: ");
        }
    }
    public function approvedReviews()
    {
        try {
            $review = Review::where('status', review::STATUS_APPROVED)->with(['users', 'schoolDetails'])->get();
            if($review->isEmpty()) return ResponseHelper::notFound('Review Not Found');
            return ResponseHelper::success(ReviewResource::collection($review), 'List Review Approved');
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
    public function store(ReviewService $service, ReviewRequest $request, $schoolDetailId)
    {
        $validated = $request->validated();
        try {
            // $userId = auth()->id();
            $review = $service->createOrUpdateReview(
                $request->only(['reviewText', 'details']),
                $request->user()->id,
                $schoolDetailId
            );
            $message = $review->wasRecentlyCreated ? 'Review Created Successfully.' : 'Review Updated Successfully';
            return ResponseHelper::success(new ReviewResource($review), $message);
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops created review is failed ", $e, "[REVIEW STORE]: ");
        }
        //  } catch (\Exception $e) {
        //     return response()->json([
        //         'status' => 500,
        //         'success' => false,
        //         'message' => 'Error Updating Data: ' . $e->getMessage(),
        //     ], 500);
        // }
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ReviewService $service,$id) {
        try {
            $review = $service->getReviewDetail($id);
            if(!$review) return ResponseHelper::notFound('Data Not Found');
            return ResponseHelper::success(new ReviewResource($review), 'Review Display Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display review is failed ", $e, "[REVIEW SHOW]: ");
        }
    }

    public function destroy(ReviewService $service, $id)
    {
        try {
            $review = Review::findOrFail($id);
            if(!$review) return ResponseHelper::notFound('Data Not Found');
            $service->softDelete($id);
            return ResponseHelper::success(null,'Review moved to trash successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops deleted review is failed ", $e, "[REVIEW DELETED]: ");
        }
        //
    }

    public function trash(ReviewService $service) {
        try {
            $review = $service->trash();
            if($review->isEmpty()) {
                return ResponseHelper::notFound('Reviews not found');
            }
            return ResponseHelper::success(ReviewResource::collection($review), 'Review trashed items retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display review is failed ", $e, "[REVIEW TRASH]: ");
        }
    }
    public function restore(ReviewService $service, $id) {
        try {

            $review = Review::find($id);
            if(!$review) return ResponseHelper::notFound('Data Not Found');
            $service->restore($id);
            return ResponseHelper::success([],'Review restored successfully');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops restore review is failed ", $e, "[REVIEW RESTORE]: ");
        }
    }

}
