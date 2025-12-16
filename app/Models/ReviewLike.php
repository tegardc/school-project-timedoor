<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewLike extends Model
{
    use HasFactory;

    public $timestamps = false;
    public const CREATED_AT = 'createdAt';

    protected $fillable = [
        'reviewId',
        'userId',
        'createdAt'
    ];

    protected $dates = ['createdAt'];

    /**
     * Relasi ke Review
     */
    public function review()
    {
        return $this->belongsTo(Review::class, 'reviewId', 'id');
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }
}
