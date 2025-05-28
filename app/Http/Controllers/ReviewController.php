<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($schoolDetailId)
    {
        $review = review::where('schoolDetailId', $schoolDetailId)->where('status', review::STATUS_APPROVED)->with(['users', 'schoolDetails'])->get();
        return ResponseHelper::success(ReviewResource::collection($review), 'Review Display Success');
        //
    }
    public function approve($id)
    {
        $review = review::find($id);
        if (!$review) {
            return ResponseHelper::notFound('Review Not Found');
        }
        $review->status = review::STATUS_APPROVED;
        $review->save();

        return ResponseHelper::success('Review Approved Successfully');
    }
    public function reject($id)
    {
        $review = review::find($id);
        if (!$review) {
            return ResponseHelper::notFound('Review Not Found');
        }
        $review->status = review::STATUS_REJECTED;
        $review->save();

        return ResponseHelper::success('Review Reject and Delete');
    }
    public function pendingReviews()
    {
        $review = review::where('status', review::STATUS_PENDING)->with(['users', 'schoolDetails'])->get();
        return ResponseHelper::success(ReviewResource::collection($review), 'List Review For Approved Or Reject');
    }
    public function rejectedReviews()
    {
        $review = review::where('status', review::STATUS_REJECTED)->with(['users', 'schoolDetails'])->get();
        return ResponseHelper::success(ReviewResource::collection($review), 'List Review Reject');
    }
    public function approvedReviews()
    {
        $review = review::where('status', review::STATUS_APPROVED)->with(['users', 'schoolDetails'])->get();
        return ResponseHelper::success(ReviewResource::collection($review), 'List Review Approved');
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
    public function store(ReviewRequest $request, $schoolDetailId)
    {
        $userId = auth()->id();
        $validated = $request->validated();
        $review = review::updateOrCreate(
            ['userId' => $userId, 'schoolDetailId' => $schoolDetailId],
            [
                'reviewText' => $validated['reviewText'],
                'rating' => $validated['rating'],
                'status' => review::STATUS_PENDING,
            ]
        );
        $message = $review->wasRecentlyCreated ? 'Review Created Successfully.' : 'Review Updated Successfully';
        return ResponseHelper::success(new ReviewResource($review), $message);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id) {}

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
        $review = review::findOrFail($id);
        $review->delete();

        return ResponseHelper::success('Delete Data Success');
        //
    }
}
