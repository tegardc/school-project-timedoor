<?php

namespace App\Services;

use App\Models\Review;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    public function getReview($id)
    {
        return Review::where('school_id', $id)->get();
    }

    public function getAll($schoolDetailId,$perPage = null)
    {
        $review = Review::select([
            'id','reviewsText','rating'])->where('schoolDetailId', $schoolDetailId)->where('status', Review::STATUS_APPROVED)->with('users','schoolDetails');

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
}
