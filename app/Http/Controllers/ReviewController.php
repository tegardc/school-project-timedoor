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
            return ResponseHelper::success(ReviewResource::collection($review), 'List Review For Approved Or Reject');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display pending review is failed ", $e, "[REVIEW PENDINGREVIEWS]: ");
        }
    }
    public function rejectedReviews()
    {
        try {
            $review = Review::where('status', review::STATUS_REJECTED)->with(['users', 'schoolDetails'])->get();
            return ResponseHelper::success(ReviewResource::collection($review), 'List Review Reject');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display rejected review is failed ", $e, "[REVIEW REJECTEDREVIEWS]: ");
        }
    }
    public function approvedReviews()
    {
        try {
            $review = Review::where('status', review::STATUS_APPROVED)->with(['users', 'schoolDetails'])->get();
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
        // } catch (\Exception $e) {
        //     return ResponseHelper::serverError("Oops created review is failed ", $e, "[REVIEW STORE]: ");
        // }
         } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => 'Error Updating Data: ' . $e->getMessage(),
            ], 500);
        }
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ReviewService $service,$id) {
        try {
            $review = $service->getReviewDetail($id);
            return ResponseHelper::success(new ReviewResource($review), 'Review Display Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops display review is failed ", $e, "[REVIEW SHOW]: ");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($request, $id)
    {

        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->delete();

            return ResponseHelper::success('Delete Data Success');
        } catch (\Exception $e) {
            return ResponseHelper::serverError("Oops deleted review is failed ", $e, "[REVIEW DELETED]: ");
        }
        //
    }
}
