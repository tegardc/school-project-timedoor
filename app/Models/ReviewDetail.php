<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewDetail extends Model
{
    use HasFactory;
    public $timestamps = true;
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    protected $fillable = [
        'reviewId',
        'questionId',
        'score',
    ];

    public function review()
    {
        return $this->belongsTo(Review::class, 'reviewId');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'questionId');
    }
    //
}
