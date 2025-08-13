<?php

namespace App\Services;

use App\Models\Review;
use App\Models\ReviewDetail;
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

    public function getAll($schoolDetailId,$perPage = null)
    {
        $review = Review::select([
             'id', 'reviewText', 'rating', 'userId',  'schoolDetailId', 'createdAt', 'updatedAt'])->where('schoolDetailId', $schoolDetailId)->where('status', Review::STATUS_APPROVED)->with('users','schoolDetails');

        return $review->paginate($perPage??10);
    }
    public function approve($id)
    {
        $review = Review::find($id);
        if(!$review){
            return false;
        }
        return Review::where('id', $id)->update(['status' => Review::STATUS_APPROVED]);
    }
    public function rejected($id)
    {
        $review = Review::find($id);
        if(!$review){
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
    public function createOrUpdateReview(array $data, int $userId, int $schoolDetailId):Review
    {
    $details = $data['details'];
    $reviewText = $data['reviewText'] ?? null;

    $totalScore = array_sum(array_column($details, 'score'));
    $rating = round($totalScore / count($details), 2);

    return DB::transaction(function () use ($userId, $schoolDetailId, $reviewText, $rating, $details) {

        $review = Review::where('userId', $userId)
            ->where('schoolDetailId', $schoolDetailId)
            ->first();

        if ($review) {
            // Update
            $review->update([
                'reviewText' => $reviewText,
                'rating' => $rating,
                'status' => Review::STATUS_PENDING
            ]);
            $review->reviewDetails()->delete();
        } else {
            // Create baru
            $review = Review::create([
                'reviewText' => $reviewText,
                'rating' => $rating,
                'userId' => $userId,
                'schoolDetailId' => $schoolDetailId,
                'status' => Review::STATUS_PENDING
            ]);
        }
        foreach ($details as $detail) {
            ReviewDetail::create([
                'reviewId' => $review->id,
                'questionId' => $detail['questionId'],
                'score' => $detail['score'],
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
            'users:id,name,image',
            'schoolDetails:id,name'
        ])
        ->orderByDesc('createdAt')
        ->limit($limit)
        ->get();
    }
}
