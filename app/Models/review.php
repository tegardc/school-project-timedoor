<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = true;
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    public const DELETED_AT = 'deletedAt';
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    protected $dates = ['deletedAt'];
    protected $fillable = [
        'reviewText',
        'rating',
        'userId',
        'schoolDetailId',
        // 'approved',
        'status',
        'createdAt',
        'updatedAt'
    ];
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function schoolDetails()
    {
        return $this->belongsTo(SchoolDetail::class);
    }
    public function reviewDetails()
    {
        return $this->hasMany(ReviewDetail::class, 'reviewId');
    }
}
