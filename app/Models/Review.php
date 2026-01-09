<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = true;
    protected $guarded = ['id'];
    // protected $appends = ['like_count'];
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
        'liked',
        'improved',
        'reviewer_name',
        'source',
        // 'approved',
        'status',
        'isPinned',
        'createdAt',
        'updatedAt'
    ];
    protected $appends = ['likesCount'];
    public function users()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }
    public function schoolDetails()
    {
        return $this->belongsTo(SchoolDetail::class, 'schoolDetailId', 'id');
    }
    public function reviewDetails()
    {
        return $this->hasMany(ReviewDetail::class, 'reviewId', 'id');
    }
    public function schoolValidation()
    {
        return $this->hasOne(SchoolValidation::class, 'reviewId', 'id');
    }
    public function likes()
    {
        return $this->hasMany(ReviewLike::class, 'reviewId', 'id');
    }
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    /**
     * Cek apakah user tertentu sudah like review ini
     */
    public function isLikedByUser($userId)
    {
        return $this->likes()->where('userId', $userId)->exists();
    }
    //     public function getLikeCountAttribute()
    // {
    //     return $this->likes()->count();
    // }
    public function getAuthorNameAttribute()
    {
        if ($this->source === 'google') {
            return $this->reviewer_name; // Ambil nama manual dari kolom
        }

        // Jika internal, ambil dari relasi user, jika user sudah dihapus return 'Anonim'
        return $this->user ? $this->user->name : 'Pengguna Terhapus';
    }
}
