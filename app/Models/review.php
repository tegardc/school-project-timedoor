<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory;
    public $timestamps = true;
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
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
}
